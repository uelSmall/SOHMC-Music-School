<?php

namespace Tests\Feature;

use App\Livewire\Parents\Dashboard as ParentDashboard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ParentLinkTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    private function admin(): User
    {
        return User::where('email', 'admin@admin.com')->firstOrFail();
    }

    private function makeUser(string $name, string $email, string $role): User
    {
        $user = User::factory()->create(['name' => $name, 'email' => $email]);
        $user->syncRoles([$role]);

        return $user;
    }

    public function test_admin_can_link_parent_to_student_on_user_update(): void
    {
        $student = $this->makeUser('Bobby Child', 'bobby@example.com', 'student');
        $parent = $this->makeUser('Grace Parent', 'grace@example.com', 'parent');

        $response = $this->actingAs($this->admin())
            ->put(route('admin.users.update', $student), [
                'first_name' => 'Bobby',
                'last_name' => 'Child',
                'email' => 'bobby@example.com',
                'roles' => ['student'],
                'parents' => [$parent->id],
            ]);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertTrue(
            $student->fresh()->parents->contains('id', $parent->id),
            'Student should have the parent linked'
        );
        $this->assertTrue(
            $parent->fresh()->children->contains('id', $student->id),
            'Parent should have the student linked as a child'
        );
    }

    public function test_unchecking_parents_removes_the_link(): void
    {
        $student = $this->makeUser('Bobby Child', 'bobby@example.com', 'student');
        $parent = $this->makeUser('Grace Parent', 'grace@example.com', 'parent');

        $student->parents()->sync([$parent->id]);
        $this->assertCount(1, $student->fresh()->parents);

        $this->actingAs($this->admin())
            ->put(route('admin.users.update', $student), [
                'first_name' => 'Bobby',
                'last_name' => 'Child',
                'email' => 'bobby@example.com',
                'roles' => ['student'],
            ]);

        $this->assertEmpty($student->fresh()->parents, 'Unchecking parents should clear the link');
    }

    public function test_parent_dashboard_shows_linked_children(): void
    {
        $student = $this->makeUser('Bobby Child', 'bobby@example.com', 'student');
        $parent = $this->makeUser('Grace Parent', 'grace@example.com', 'parent');

        $student->parents()->sync([$parent->id]);

        $html = Livewire::actingAs($parent)
            ->test(ParentDashboard::class)
            ->html();

        $this->assertStringContainsString('Bobby Child', $html);
    }
}