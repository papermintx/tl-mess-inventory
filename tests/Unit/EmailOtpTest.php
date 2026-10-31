<?php

namespace Tests\Unit;

use App\Models\EmailOtp;
use Tests\TestCase;

class EmailOtpTest extends TestCase
{
    public function test_otp_is_valid_when_matching_and_not_expired(): void
    {
        $otp = new EmailOtp([
            'email' => 'test@example.com',
            'otp' => '123456',
            'expires_at' => now()->addMinutes(10),
        ]);

        $this->assertTrue($otp->isValid('123456'));
        $this->assertFalse($otp->isValid('654321'));
        $this->assertFalse($otp->isExpired());
    }

    public function test_otp_is_invalid_when_expired(): void
    {
        $otp = new EmailOtp([
            'email' => 'test@example.com',
            'otp' => '123456',
            'expires_at' => now()->subMinute(),
        ]);

        $this->assertTrue($otp->isExpired());
        $this->assertFalse($otp->isValid('123456'));
    }
}
