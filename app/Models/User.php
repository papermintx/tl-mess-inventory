<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const DEFAULT_ROLE = 'viewer';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    /**
     * Default model attributes.
     * Standar Keamanan Perbankan: Role default adalah viewer (Principle of Least Privilege).
     */
    protected $attributes = [
        'role' => 'viewer',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin->value;
    }

    public function isViewer(): bool
    {
        return $this->role === UserRole::Viewer->value || empty($this->role);
    }

    public function getRoleEnumAttribute(): UserRole
    {
        return UserRole::tryFrom((string) $this->role) ?? UserRole::Viewer;
    }

    public function getRoleLabelAttribute(): string
    {
        return $this->role_enum->label();
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return $this->role_enum->badgeClass();
    }

    public function uploadedPhotos()
    {
        return $this->hasMany(AssetPhoto::class, 'uploaded_by');
    }
}
