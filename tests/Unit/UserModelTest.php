<?php

namespace Tests\Unit;

use App\Enums\UserRole;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase
{
    public function test_user_has_username_in_fillable(): void
    {
        $user = new User([
            'name' => 'Admin User',
            'username' => 'admin_mess',
            'email' => 'admin@tanjunglesung.com',
            'role' => 'admin',
        ]);

        $this->assertSame('admin_mess', $user->username);
        $this->assertSame('admin', $user->role);
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isViewer());
    }

    public function test_user_defaults_to_viewer_role(): void
    {
        $user = new User([
            'name' => 'Regular User',
            'username' => 'staff1',
            'email' => 'staff@tanjunglesung.com',
        ]);

        $this->assertSame('viewer', $user->role);
        $this->assertTrue($user->isViewer());
        $this->assertFalse($user->isAdmin());
        $this->assertSame(UserRole::Viewer, $user->role_enum);
        $this->assertSame('badge-neutral', $user->role_badge_class);
    }
}
