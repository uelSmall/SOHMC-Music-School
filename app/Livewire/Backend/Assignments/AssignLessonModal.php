<?php

namespace App\Livewire\Backend\Assignments;

use App\Notifications\LessonAssignedNotification;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Models\LessonStudentAssignment;

class AssignLessonModal extends Component
{
    use WithPagination;

    public bool $isOpen = false;
    public ?int $selectedLessonId = null;
    public array $selectedStudentIds = [];
    public ?string $dueDate = null;

    public function render()
    {
        $user = auth()->user();

        $lessons = Lesson::query()
            ->with('teacher:id,name')
            ->where('status', 'published')
            ->when($user?->hasRole('teacher'), function ($query) use ($user) {
                $query->where('teacher_id', $user->id);
            })
            ->orderBy('title')
            ->get();

        $students = User::role('student')
            ->orderBy('name')
            ->get();

        return view('backend.assignments.assign-lesson-modal', [
            'lessons' => $lessons,
            'students' => $students,
        ]);
    }

    public function openModal(): void
    {
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->resetForm();
        $this->isOpen = false;
    }

    public function toggleStudent(int $studentId): void
    {
        if (in_array($studentId, $this->selectedStudentIds)) {
            $this->selectedStudentIds = array_filter(
                $this->selectedStudentIds,
                fn($id) => $id !== $studentId
            );
        } else {
            $this->selectedStudentIds[] = $studentId;
        }
    }

    public function assignLesson(): void
    {
        $this->validate([
            'selectedLessonId' => 'required|integer|exists:lessons,id',
            'selectedStudentIds' => 'required|array|min:1',
            'selectedStudentIds.*' => 'integer|exists:users,id',
            'dueDate' => 'nullable|date|after:today',
        ]);

        $lesson = Lesson::findOrFail($this->selectedLessonId);

        // Ownership check: teachers can only assign their own lessons
        $user = auth()->user();
        if ($user->hasRole('teacher') && (int) $lesson->teacher_id !== (int) $user->id) {
            abort(403, 'You can only assign your own lessons.');
        }
        $students = User::query()->whereIn('id', $this->selectedStudentIds)->get()->keyBy('id');

        foreach ($this->selectedStudentIds as $studentId) {
            $assignment = LessonStudentAssignment::updateOrCreate(
                [
                    'lesson_id' => $lesson->id,
                    'student_id' => $studentId,
                ],
                [
                    'status' => 'assigned',
                    'due_date' => $this->dueDate,
                    'assigned_at' => now(),
                ]
            );

            $student = $students->get($studentId);
            if ($student) {
                $student->notify(new LessonAssignedNotification($assignment));
            }
        }

        $this->dispatch('assignmentCreated');
        $this->dispatch('notify', message: 'Lessons assigned successfully.', type: 'success');
        $this->closeModal();
    }

    private function resetForm(): void
    {
        $this->selectedLessonId = null;
        $this->selectedStudentIds = [];
        $this->dueDate = null;
    }
}
