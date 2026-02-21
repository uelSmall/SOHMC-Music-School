<?php

namespace App\Livewire\Frontend\Lessons;

use App\Models\User;
use Livewire\Attributes\State;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Models\LessonStudentAssignment;

class LessonSearch extends Component
{
    use WithPagination;

    #[State]
    public string $search = '';

    #[State]
    public string $filterInstrument = '';

    #[State]
    public string $filterStatus = '';

    #[State]
    public string $tab = 'all'; // 'all', 'assigned', 'completed'

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterInstrument(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingTab(): void
    {
        $this->resetPage();
    }

    public function getLessonData()
    {
        $user = auth()->user();
        $query = Lesson::query();

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Instrument filter
        if ($this->filterInstrument) {
            $query->where('instrument', $this->filterInstrument);
        }

        // Tab filtering
        if ($this->tab === 'assigned') {
            $query->whereHas('assignedStudents', function ($q) use ($user) {
                $q->where('student_id', $user->id);
            });
        } elseif ($this->tab === 'completed') {
            $query->whereHas('assignedStudents', function ($q) use ($user) {
                $q->where('student_id', $user->id)
                    ->where('status', 'completed');
            });
        }

        // Status filter (for assignments)
        if ($this->filterStatus && $this->tab !== 'all') {
            $query->whereHas('assignedStudents', function ($q) use ($user) {
                $q->where('student_id', $user->id)
                    ->where('status', $this->filterStatus);
            });
        }

        return $query->with('teacher', 'assignedStudents')
            ->orderBy('title')
            ->get();
    }

    #[\Livewire\Attributes\Computed]
    public function instruments()
    {
        return Lesson::query()
            ->whereNotNull('instrument')
            ->distinct()
            ->pluck('instrument')
            ->sort()
            ->values();
    }

    #[\Livewire\Attributes\Computed]
    public function assignmentStatuses()
    {
        return [
            'assigned' => 'Assigned',
            'started' => 'Started',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
        ];
    }

    public function render()
    {
        return view('frontend.lessons.lesson-search', [
            'lessons' => $this->getLessonData(),
            'instruments' => $this->instruments(),
            'assignmentStatuses' => $this->assignmentStatuses(),
        ]);
    }
}
