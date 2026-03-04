<?php

namespace App\Livewire\Backend\Lessons;

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
        return Lesson::query()
            ->with('teacher', 'students')
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
}
