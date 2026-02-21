<?php

namespace App\Livewire\Backend\Assignments;

use App\Models\User;
use Illuminate\Pagination\Paginator;
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
        $lessons = Lesson::where('status', 'published')
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

        foreach ($this->selectedStudentIds as $studentId) {
            LessonStudentAssignment::updateOrCreate(
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
        }

        $this->dispatch('assignmentCreated');
        $this->closeModal();
        session()->flash('message', 'Lessons assigned successfully!');
    }

    private function resetForm(): void
    {
        $this->selectedLessonId = null;
        $this->selectedStudentIds = [];
        $this->dueDate = null;
    }
}
