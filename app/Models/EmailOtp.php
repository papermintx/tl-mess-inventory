<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp',
        'payload',
        'expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Menentukan apakah kode OTP sudah kedaluwarsa.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Menentukan apakah kode OTP cocok dan masih berlaku.
     */
    public function isValid(string $inputOtp): bool
    {
        return ! $this->isExpired() && hash_equals($this->otp, trim($inputOtp));
    }
}
