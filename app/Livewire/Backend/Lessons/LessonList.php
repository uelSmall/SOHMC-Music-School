<?php

namespace App\Livewire\Backend\Lessons;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Lesson\Models\Lesson;

class LessonList extends Component
{
    use WithPagination;

    public string $routePrefix = 'backend';
    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDir = 'desc';
    public string $statusFilter = '';

    public function mount(string $routePrefix = 'backend')
    {
        $this->routePrefix = $routePrefix;
    }

    #[\Livewire\Attributes\Computed]
    public function lessons()
    {
        $user = $this->currentUser();

        return Lesson::query()
            ->with('teacher', 'students')
            ->when($user->hasRole('teacher'), fn ($query) => $query->where('teacher_id', $user->id))
            ->when(! $this->canManageAllLessons($user) && ! $user->hasRole('teacher'), fn ($query) => $query->whereRaw('1 = 0'))
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(15);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function delete(Lesson $lesson)
    {
        $user = $this->currentUser();

        abort_unless(
            $this->canManageAllLessons($user)
            || ((int) $lesson->teacher_id === (int) $user->id),
            403
        );

        $lesson->delete();
        $this->dispatch('notify', message: 'Lesson deleted successfully.');
    }

    public function sort(string $column)
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
    }

    public function render()
    {
        return view('livewire.backend.lessons.lesson-list');
    }

    private function currentUser(): User
    {
        return auth()->user();
    }

    private function canManageAllLessons(User $user): bool
    {
        return $user->hasRole('super admin') || $user->hasRole('administrator');
    }
}
