<?php

namespace App\Livewire\Backend\Lessons;

use Modules\Lesson\Models\LessonAssignmentComment;
use Livewire\Component;
use Modules\Lesson\Models\LessonStudentAssignment;

class AssignmentDashboard extends Component
{
    public ?int $commentAssignmentId = null;

    public string $commentBody = '';

    public function startComment(int $assignmentId): void
    {
        $assignment = LessonStudentAssignment::query()
            ->with(['lesson:id,title,teacher_id', 'student:id,name'])
            ->whereHas('lesson', function ($query) {
                $query->where('teacher_id', auth()->id());
            })
            ->findOrFail($assignmentId);

        $this->commentAssignmentId = $assignment->id;
        $this->commentBody = (string) optional($assignment->latestComment)->body;
    }

    public function cancelComment(): void
    {
        $this->reset(['commentAssignmentId', 'commentBody']);
    }

    public function saveComment(): void
    {
        $this->validate([
            'commentAssignmentId' => 'required|integer|exists:lesson_student_assignments,id',
            'commentBody' => 'required|string|min:5|max:5000',
        ]);

        $assignment = LessonStudentAssignment::query()
            ->whereHas('lesson', function ($query) {
                $query->where('teacher_id', auth()->id());
            })
            ->findOrFail($this->commentAssignmentId);

        LessonAssignmentComment::create([
            'lesson_student_assignment_id' => $assignment->id,
            'teacher_id' => auth()->id(),
            'body' => $this->commentBody,
        ]);

        $this->dispatch('notify', message: 'Teacher note saved.', type: 'success');
        $this->cancelComment();
    }

    public function render()
    {
        $assignments = LessonStudentAssignment::query()
            ->with(['lesson:id,title,teacher_id', 'student:id,name', 'latestComment.teacher:id,name'])
            ->whereHas('lesson', function ($q) {
                $q->where('teacher_id', auth()->id());
            })
            ->orderBy('assigned_at', 'desc')
            ->get();

        $layout = request()->routeIs('teacher.*') ? 'layouts.app' : 'backend.layouts.app';

        return view('backend.lessons.assignments-dashboard', [
            'assignments' => $assignments,
        ])->layout($layout);
    }
}
