<?php

namespace Tests\Feature;

use App\Livewire\Admin\UsersIndex;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RoleColorDisplayTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_color_map_covers_all_roles(): void
    {
        $roles = ['super admin', 'administrator', 'teacher', 'student', 'parent'];

        foreach ($roles as $role) {
            $this->assertArrayHasKey($role, Role::colorMap(), "Role '{$role}' missing from color map");
        }

        $this->assertCount(count($roles), Role::colorMap());
    }

    public function test_admin_users_table_shows_role_colors(): void
    {
        $admin = User::where('email', 'admin@admin.com')->firstOrFail();

        $namedUserColors = [
            'super admin' => ['bg-fuchsia-100', 'text-fuchsia-700'],
            'administrator' => ['bg-red-100', 'text-red-700'],
            'teacher' => ['bg-purple-100', 'text-purple-700'],
            'student' => ['bg-blue-100', 'text-blue-700'],
            'parent' => ['bg-orange-100', 'text-orange-800'],
        ];

        foreach ($namedUserColors as $role => $classes) {
            $user = User::factory()->create([
                'name' => ucwords(str_replace('_', ' ', $role)) . ' Tester',
                'email' => $role . '-tester@example.com',
            ]);

            $user->syncRoles([$role]);

            $this->assertTrue($user->hasRole($role), "Role '{$role}' was not assigned to tester user. Actual roles: " . $user->roles->pluck('name')->join(', '));
        }

        $html = Livewire::actingAs($admin)
            ->test(UsersIndex::class)
            ->html();

        foreach ($namedUserColors as $role => $classes) {
            $this->assertStringContainsString(Role::labelFor($role), $html, "Missing label for '{$role}'");

            foreach ($classes as $class) {
                $this->assertStringContainsString($class, $html, "Missing class '{$class}' for '{$role}'");
            }
        }
    }
}