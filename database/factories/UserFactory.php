<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'viewer', // Standar Bank: Default role adalah viewer
        ];
    }

    /**
     * State untuk akun Administrator Pengelola
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * State untuk akun Administrator Pertama (Super Admin / First Admin)
     */
    public function firstAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'is_first_admin' => true,
            'admin_request_status' => 'approved',
            'admin_action_at' => now(),
        ]);
    }

    /**
     * State untuk pengguna yang mengajukan permohonan menjadi Admin
     */
    public function pendingAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'viewer',
            'is_first_admin' => false,
            'admin_request_status' => 'pending',
            'admin_request_reason' => 'Permohonan diajukan oleh pengguna.',
            'admin_requested_at' => now(),
        ]);
    }

    /**
     * State untuk akun Auditor / Peninjau
     */
    public function viewer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'viewer',
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
