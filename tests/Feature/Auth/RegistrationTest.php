<?php

namespace Tests\Feature\Auth;

use App\Mail\SendOtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Volt\Volt;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.register');
    }

    public function test_otp_code_is_generated_and_sent_to_email(): void
    {
        Mail::fake();

        $component = Volt::test('pages.auth.register')
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password');

        $component->call('sendOtp');

        $component->assertHasNoErrors()
            ->assertSet('step', 2);

        $this->assertDatabaseHas('email_otps', [
            'email' => 'test@example.com',
        ]);

        Mail::assertSent(SendOtpMail::class, function ($mail) {
            return $mail->hasTo('test@example.com');
        });
    }

    public function test_first_registered_user_becomes_first_admin_after_otp_verification(): void
    {
        Mail::fake();

        $component = Volt::test('pages.auth.register')
            ->set('name', 'First Admin')
            ->set('username', 'firstadmin')
            ->set('email', 'firstadmin@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password');

        $component->call('sendOtp');

        $otpRecord = EmailOtp::where('email', 'firstadmin@example.com')->first();
        $this->assertNotNull($otpRecord);

        $component->set('otp_code', $otpRecord->otp)
            ->call('verifyOtpAndRegister');

        $component->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticated();

        $user = User::where('email', 'firstadmin@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->isFirstAdmin());
        $this->assertTrue($user->isAdmin());
        $this->assertSame('admin', $user->role);
        $this->assertSame('approved', $user->admin_request_status);
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue($user->hasRole('admin'));
    }

    public function test_subsequent_user_registers_with_pending_admin_request_after_otp_verification(): void
    {
        Mail::fake();

        // Admin pertama sudah ada
        User::factory()->firstAdmin()->create([
            'email' => 'admin@example.com',
        ]);

        $component = Volt::test('pages.auth.register')
            ->set('name', 'Applicant Staff')
            ->set('username', 'applicant')
            ->set('email', 'applicant@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('admin_request_reason', 'Staf logistik mess kawasan Tanjung Lesung');

        $component->call('sendOtp');

        $otpRecord = EmailOtp::where('email', 'applicant@example.com')->first();
        $this->assertNotNull($otpRecord);

        $component->set('otp_code', $otpRecord->otp)
            ->call('verifyOtpAndRegister');

        $component->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticated();

        $user = User::where('email', 'applicant@example.com')->first();
        $this->assertNotNull($user);
        $this->assertFalse($user->isFirstAdmin());
        $this->assertFalse($user->isAdmin());
        $this->assertTrue($user->isViewer());
        $this->assertSame('pending', $user->admin_request_status);
        $this->assertSame('Staf logistik mess kawasan Tanjung Lesung', $user->admin_request_reason);
        $this->assertNotNull($user->admin_requested_at);
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue($user->hasRole('viewer'));
    }

    public function test_invalid_otp_fails_verification(): void
    {
        Mail::fake();

        $component = Volt::test('pages.auth.register')
            ->set('name', 'Invalid OTP User')
            ->set('email', 'invalid@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password');

        $component->call('sendOtp');

        $component->set('otp_code', '000000')
            ->call('verifyOtpAndRegister');

        $component->assertHasErrors(['otp_code']);
        $this->assertGuest();
    }
}
