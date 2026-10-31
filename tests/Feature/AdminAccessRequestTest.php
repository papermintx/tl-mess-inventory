<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminAccessRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);
    }

    public function test_first_admin_can_access_access_requests_page(): void
    {
        $firstAdmin = User::factory()->firstAdmin()->create();
        $firstAdmin->assignRole('admin');

        $response = $this->actingAs($firstAdmin)->get('/admin/access-requests');

        $response->assertOk()
            ->assertSeeVolt('admin.access-requests');
    }

    public function test_regular_viewer_cannot_access_access_requests_page(): void
    {
        $viewer = User::factory()->viewer()->create();
        $viewer->assignRole('viewer');

        $response = $this->actingAs($viewer)->get('/admin/access-requests');

        $response->assertForbidden();
    }

    public function test_non_first_admin_cannot_access_access_requests_page(): void
    {
        $secondAdmin = User::factory()->admin()->create([
            'is_first_admin' => false,
        ]);
        $secondAdmin->assignRole('admin');

        $response = $this->actingAs($secondAdmin)->get('/admin/access-requests');

        $response->assertForbidden();
    }

    public function test_first_admin_can_approve_pending_admin_request(): void
    {
        $firstAdmin = User::factory()->firstAdmin()->create();
        $firstAdmin->assignRole('admin');

        $applicant = User::factory()->pendingAdmin()->create();
        $applicant->assignRole('viewer');

        $this->actingAs($firstAdmin);

        Volt::test('admin.access-requests')
            ->call('approve', $applicant->id);

        $applicant->refresh();
        $this->assertSame('admin', $applicant->role);
        $this->assertSame('approved', $applicant->admin_request_status);
        $this->assertSame($firstAdmin->id, $applicant->admin_action_by);
        $this->assertNotNull($applicant->admin_action_at);
        $this->assertTrue($applicant->hasRole('admin'));
    }

    public function test_first_admin_can_reject_pending_admin_request(): void
    {
        $firstAdmin = User::factory()->firstAdmin()->create();
        $firstAdmin->assignRole('admin');

        $applicant = User::factory()->pendingAdmin()->create();
        $applicant->assignRole('viewer');

        $this->actingAs($firstAdmin);

        Volt::test('admin.access-requests')
            ->call('reject', $applicant->id);

        $applicant->refresh();
        $this->assertSame('viewer', $applicant->role);
        $this->assertSame('rejected', $applicant->admin_request_status);
        $this->assertSame($firstAdmin->id, $applicant->admin_action_by);
        $this->assertTrue($applicant->hasRole('viewer'));
    }

    public function test_first_admin_can_revoke_admin_role_from_another_admin(): void
    {
        $firstAdmin = User::factory()->firstAdmin()->create();
        $firstAdmin->assignRole('admin');

        $secondaryAdmin = User::factory()->admin()->create([
            'is_first_admin' => false,
        ]);
        $secondaryAdmin->assignRole('admin');

        $this->actingAs($firstAdmin);

        Volt::test('admin.access-requests')
            ->call('revokeAdmin', $secondaryAdmin->id);

        $secondaryAdmin->refresh();
        $this->assertSame('viewer', $secondaryAdmin->role);
        $this->assertNull($secondaryAdmin->admin_request_status);
        $this->assertTrue($secondaryAdmin->hasRole('viewer'));
    }

    public function test_viewer_can_request_admin_access_from_profile(): void
    {
        $viewer = User::factory()->viewer()->create();
        $viewer->assignRole('viewer');

        $this->actingAs($viewer);

        Volt::test('profile.request-admin-access-form')
            ->set('reason', 'Butuh hak akses kelola aset mess')
            ->call('requestAccess');

        $viewer->refresh();
        $this->assertSame('pending', $viewer->admin_request_status);
        $this->assertSame('Butuh hak akses kelola aset mess', $viewer->admin_request_reason);
        $this->assertNotNull($viewer->admin_requested_at);
    }
}
