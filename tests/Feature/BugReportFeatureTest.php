<?php

namespace Tests\Feature;

use App\Models\BugReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BugReportFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    private function makeUser(string $role): User
    {
        $user = User::factory()->create([
            'name' => ucfirst($role) . ' User',
            'email' => $role . '@example.com',
        ]);

        $user->syncRoles([$role]);

        return $user;
    }

    public function test_student_can_submit_bug_report(): void
    {
        $student = $this->makeUser('student');
        $admin = $this->makeUser('administrator');

        $response = $this->actingAs($student)->post('/bug-report', [
            'title' => 'Upload button broken',
            'description' => 'Nothing happens when I click upload.',
        ]);

        $response->assertRedirect(route('bug-reports.create'));

        $this->assertDatabaseHas('bug_reports', [
            'title' => 'Upload button broken',
            'user_id' => $student->id,
            'status' => BugReport::STATUS_REPORTED,
            'browser' => 'Unknown',
        ]);

        $notification = $admin->notifications()->first();
        $this->assertNotNull($notification);
        $this->assertEquals('New Bug Report', $notification->data['title']);
        $this->assertStringContainsString('admin/bug-reports', $notification->data['url']);
    }

    public function test_admin_can_view_bug_reports(): void
    {
        $admin = $this->makeUser('administrator');
        $student = $this->makeUser('student');

        BugReport::create([
            'user_id' => $student->id,
            'title' => 'Broken link',
            'description' => 'The link leads nowhere.',
        ]);

        $response = $this->actingAs($admin)->get('/admin/bug-reports');

        $response->assertStatus(200);
        $response->assertSee('Bug Reports');
        $response->assertSee('Broken link');
    }

    public function test_admin_can_update_status(): void
    {
        $admin = $this->makeUser('administrator');
        $student = $this->makeUser('student');

        $report = BugReport::create([
            'user_id' => $student->id,
            'title' => 'Broken link',
            'description' => 'The link leads nowhere.',
            'status' => BugReport::STATUS_REPORTED,
        ]);

        $response = $this->actingAs($admin)->patch("/admin/bug-reports/{$report->id}", [
            'status' => BugReport::STATUS_IN_PROGRESS,
            'admin_notes' => 'Reproduced, working on it.',
        ]);

        $response->assertRedirect(route('admin.bug-reports.index'));

        $this->assertDatabaseHas('bug_reports', [
            'id' => $report->id,
            'status' => BugReport::STATUS_IN_PROGRESS,
            'handled_by' => $admin->id,
            'admin_notes' => 'Reproduced, working on it.',
        ]);
    }

    public function test_student_cannot_access_admin_route(): void
    {
        $student = $this->makeUser('student');

        $response = $this->actingAs($student)->get('/admin/bug-reports');

        $response->assertStatus(403);
    }

    public function test_guest_cannot_submit_bug_report(): void
    {
        $response = $this->get('/bug-report');

        $response->assertRedirect('/login');
    }
}