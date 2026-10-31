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

    public function test_user_can_be_first_admin(): void
    {
        $user = new User([
            'name' => 'First Admin',
            'username' => 'superadmin',
            'email' => 'first@tanjunglesung.com',
            'role' => 'admin',
            'is_first_admin' => true,
        ]);

        $this->assertTrue($user->isFirstAdmin());
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isViewer());
    }

    public function test_user_has_pending_admin_request(): void
    {
        $user = new User([
            'name' => 'Staff Applicant',
            'username' => 'staffapp',
            'email' => 'applicant@tanjunglesung.com',
            'role' => 'viewer',
            'admin_request_status' => 'pending',
            'admin_request_reason' => 'Perlu akses inventaris mess',
        ]);

        $this->assertFalse($user->isFirstAdmin());
        $this->assertFalse($user->isAdmin());
        $this->assertTrue($user->isViewer());
        $this->assertTrue($user->hasPendingAdminRequest());
        $this->assertSame('Perlu akses inventaris mess', $user->admin_request_reason);
    }
}
