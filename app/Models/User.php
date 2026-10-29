<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

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
        if ($this->role === UserRole::Admin->value) {
            return true;
        }

        return $this->exists && method_exists($this, 'hasRole') ? $this->hasRole('admin') : false;
    }

    public function isViewer(): bool
    {
        if ($this->isAdmin()) {
            return false;
        }

        if ($this->role === UserRole::Viewer->value || empty($this->role)) {
            return true;
        }

        return $this->exists && method_exists($this, 'hasRole') ? $this->hasRole('viewer') : true;
    }

    public function getRoleEnumAttribute(): UserRole
    {
        if ($this->isAdmin()) {
            return UserRole::Admin;
        }

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
