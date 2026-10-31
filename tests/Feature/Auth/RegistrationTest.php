<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_new_users_can_register(): void
    {
        $component = Volt::test('pages.auth.register')
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password');

        $component->call('register');

        $component->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_first_registered_user_becomes_first_admin(): void
    {
        $component = Volt::test('pages.auth.register')
            ->set('name', 'First User')
            ->set('username', 'firstadmin')
            ->set('email', 'firstadmin@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password');

        $component->call('register');

        $user = User::where('email', 'firstadmin@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->isFirstAdmin());
        $this->assertTrue($user->isAdmin());
        $this->assertSame('admin', $user->role);
        $this->assertSame('approved', $user->admin_request_status);
        $this->assertTrue($user->hasRole('admin'));
    }

    public function test_subsequent_user_registers_as_viewer(): void
    {
        // Pendaftar pertama sudah ada
        User::factory()->firstAdmin()->create([
            'email' => 'admin@example.com',
        ]);

        $component = Volt::test('pages.auth.register')
            ->set('name', 'Second User')
            ->set('username', 'seconduser')
            ->set('email', 'second@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password');

        $component->call('register');

        $user = User::where('email', 'second@example.com')->first();
        $this->assertNotNull($user);
        $this->assertFalse($user->isFirstAdmin());
        $this->assertFalse($user->isAdmin());
        $this->assertTrue($user->isViewer());
        $this->assertSame('viewer', $user->role);
        $this->assertNull($user->admin_request_status);
        $this->assertTrue($user->hasRole('viewer'));
    }

    public function test_subsequent_user_can_request_admin_role_during_registration(): void
    {
        // Pendaftar pertama sudah ada
        User::factory()->firstAdmin()->create([
            'email' => 'admin@example.com',
        ]);

        $component = Volt::test('pages.auth.register')
            ->set('name', 'Applicant User')
            ->set('username', 'applicant')
            ->set('email', 'applicant@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('request_admin', true)
            ->set('admin_request_reason', 'Staf logistik mess Tanjung Lesung');

        $component->call('register');

        $user = User::where('email', 'applicant@example.com')->first();
        $this->assertNotNull($user);
        $this->assertFalse($user->isFirstAdmin());
        $this->assertFalse($user->isAdmin());
        $this->assertTrue($user->isViewer());
        $this->assertSame('pending', $user->admin_request_status);
        $this->assertSame('Staf logistik mess Tanjung Lesung', $user->admin_request_reason);
        $this->assertNotNull($user->admin_requested_at);
        $this->assertTrue($user->hasRole('viewer'));
    }
}
