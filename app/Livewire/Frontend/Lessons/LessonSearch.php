<?php

namespace App\Livewire\Frontend\Lessons;

use App\Models\User;
use Livewire\Attributes\State;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Lesson\Models\Lesson;

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
        $accessibleStudentIds = collect();
        $isParent = $user->hasRole('parent');

        if ($user->hasRole('student')) {
            $accessibleStudentIds = collect([$user->id]);
        } elseif ($user->hasRole('parent')) {
            $accessibleStudentIds = $user->children()->pluck('users.id');
        }

        if (($user->hasRole('student') || $user->hasRole('parent')) && $accessibleStudentIds->isEmpty()) {
            return collect();
        }

        if ($user->hasRole('student') || $user->hasRole('parent')) {
            $query->whereHas('assignedStudents', function ($q) use ($accessibleStudentIds) {
                $q->whereIn('student_id', $accessibleStudentIds);
            });
        }

        // Search filter
        if (! $isParent && $this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Instrument filter
        if (! $isParent && $this->filterInstrument) {
            $query->where('instrument', $this->filterInstrument);
        }

        // Tab filtering
        if (! $isParent && $this->tab === 'assigned') {
            $query->whereHas('assignedStudents', function ($q) use ($accessibleStudentIds) {
                $q->whereIn('student_id', $accessibleStudentIds);
            });
        } elseif (! $isParent && $this->tab === 'completed') {
            $query->whereHas('assignedStudents', function ($q) use ($accessibleStudentIds) {
                $q->whereIn('student_id', $accessibleStudentIds)
                    ->where('status', 'completed');
            });
        }

        // Status filter (for assignments)
        if (! $isParent && $this->filterStatus && $this->tab !== 'all') {
            $query->whereHas('assignedStudents', function ($q) use ($accessibleStudentIds) {
                $q->whereIn('student_id', $accessibleStudentIds)
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
            ->whereRaw('LOWER(instrument) <> ?', ['general'])
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

    public function progressPercentage(?string $status): int
    {
        return match ($status) {
            'assigned' => 25,
            'started' => 45,
            'in_progress' => 70,
            'completed' => 100,
            default => 0,
        };
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
