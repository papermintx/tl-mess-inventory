<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Viewer = 'viewer';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator Pengelola',
            self::Viewer => 'Auditor / Staff Peninjau',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Admin => 'badge-primary',
            self::Viewer => 'badge-neutral',
        };
    }

    public function canManageInventory(): bool
    {
        return $this === self::Admin;
    }
}
