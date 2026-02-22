<?php

namespace Tests\Feature;

use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RoleDashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_teacher_can_access_teacher_dashboard(): void
    {
        $teacher = User::where('email', 'teacher1@example.com')->firstOrFail();

        $response = $this->actingAs($teacher)->get('/teacher/dashboard');

        $response->assertStatus(200);
    }

    public function test_student_cannot_access_teacher_dashboard(): void
    {
        $student = User::where('email', 'student1@example.com')->firstOrFail();

        $response = $this->actingAs($student)->get('/teacher/dashboard');

        $response->assertStatus(403);
    }

    public function test_student_can_access_student_dashboard(): void
    {
        $student = User::where('email', 'student1@example.com')->firstOrFail();

        $response = $this->actingAs($student)->get('/student/dashboard');

        $response->assertStatus(200);
    }

    public function test_teacher_cannot_access_student_dashboard(): void
    {
        $teacher = User::where('email', 'teacher1@example.com')->firstOrFail();

        $response = $this->actingAs($teacher)->get('/student/dashboard');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::where('email', 'admin@admin.com')->firstOrFail();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_teacher_login_redirects_to_teacher_dashboard(): void
    {
        $response = Livewire::test(Login::class)
            ->set('email', 'teacher1@example.com')
            ->set('password', 'password')
            ->call('login');

        $response->assertHasNoErrors()
            ->assertRedirect(route('teacher.dashboard', absolute: false));
    }

    public function test_student_login_redirects_to_student_dashboard(): void
    {
        $response = Livewire::test(Login::class)
            ->set('email', 'student1@example.com')
            ->set('password', 'password')
            ->call('login');

        $response->assertHasNoErrors()
            ->assertRedirect(route('student.dashboard', absolute: false));
    }
}
