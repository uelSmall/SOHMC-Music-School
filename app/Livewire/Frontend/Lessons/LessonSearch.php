<?php

namespace App\Livewire\Frontend\Lessons;

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
        $isStudent = $user->hasRole('student');
        $isParent = $user->hasRole('parent');
        $isTeacher = $user->hasRole('teacher');

        $query = Lesson::query()->where('status', 'published');

        if ($isTeacher) {
            // Teachers see only their own published lessons
            $query->where('teacher_id', $user->id);
        } elseif ($isParent) {
            // Parents see published lessons assigned to their children
            $accessibleStudentIds = $user->children()->pluck('users.id');
            if ($accessibleStudentIds->isEmpty()) {
                return collect();
            }
            $query->whereHas('assignedStudents', function ($q) use ($accessibleStudentIds) {
                $q->whereIn('student_id', $accessibleStudentIds);
            });
        }
        // Students and admins: all published lessons (no additional filter)

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
            if ($isStudent) {
                $query->whereHas('assignedStudents', fn ($q) => $q->where('student_id', $user->id));
            } elseif ($isParent) {
                $accessibleStudentIds = $user->children()->pluck('users.id');
                $query->whereHas('assignedStudents', fn ($q) => $q->whereIn('student_id', $accessibleStudentIds));
            }
        } elseif ($this->tab === 'completed') {
            if ($isStudent) {
                $query->whereHas('assignedStudents', fn ($q) => $q->where('student_id', $user->id)->where('status', 'completed'));
            } elseif ($isParent) {
                $accessibleStudentIds = $user->children()->pluck('users.id');
                $query->whereHas('assignedStudents', fn ($q) => $q->whereIn('student_id', $accessibleStudentIds)->where('status', 'completed'));
            }
        }

        // Status filter (assignments tab only)
        if ($this->filterStatus && $this->tab !== 'all') {
            if ($isStudent) {
                $query->whereHas('assignedStudents', fn ($q) => $q->where('student_id', $user->id)->where('status', $this->filterStatus));
            } elseif ($isParent) {
                $accessibleStudentIds = $user->children()->pluck('users.id');
                $query->whereHas('assignedStudents', fn ($q) => $q->whereIn('student_id', $accessibleStudentIds)->where('status', $this->filterStatus));
            }
        }

        // Eager load: only load the current user's assignment (not all students')
        $assignmentUserId = $user->id;

        return $query->with(['teacher:id,name', 'assignedStudents' => function ($q) use ($assignmentUserId) {
            $q->where('student_id', $assignmentUserId);
        }])
            ->orderBy('title')
            ->get();
    }

    #[\Livewire\Attributes\Computed]
    public function instruments()
    {
        return Lesson::query()
            ->where('status', 'published')
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
