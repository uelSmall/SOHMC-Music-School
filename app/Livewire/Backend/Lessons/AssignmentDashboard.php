<?php

namespace App\Livewire\Backend\Lessons;

use App\Models\User;
use App\Notifications\AssignmentCommentNotification;
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
            ->where(function ($query) {
                $this->scopeToOwnOrAll($query);
            })
            ->findOrFail($assignmentId);

        $this->commentAssignmentId = $assignment->id;
        $this->commentBody = '';
    }

    public function cancelComment(): void
    {
        $this->reset(['commentAssignmentId', 'commentBody']);
    }

    public function saveComment(): void
    {
        $this->validate([
            'commentAssignmentId' => 'required|integer|exists:lesson_student_assignments,id',
            'commentBody' => 'required|string|min:1|max:5000',
        ]);

        $assignment = LessonStudentAssignment::query()
            ->where(function ($query) {
                $this->scopeToOwnOrAll($query);
            })
            ->findOrFail($this->commentAssignmentId);

        $comment = LessonAssignmentComment::create([
            'lesson_student_assignment_id' => $assignment->id,
            'user_id' => auth()->id(),
            'body' => $this->commentBody,
        ]);

        $this->notifyRecipient($comment, $assignment);

        $this->dispatch('notify', message: 'Comment sent.', type: 'success');
        $this->commentBody = '';
    }

    private function notifyRecipient(LessonAssignmentComment $comment, LessonStudentAssignment $assignment): void
    {
        $student = $assignment->student;
        if ($student && (int) $student->id !== (int) auth()->id()) {
            $student->notify(new AssignmentCommentNotification($comment));
        }

        $teacher = $assignment->lesson?->teacher;
        if ($teacher && (int) $teacher->id !== (int) auth()->id()) {
            $teacher->notify(new AssignmentCommentNotification($comment));
        }
    }

    public function render()
    {
        $assignments = LessonStudentAssignment::query()
            ->with(['lesson:id,title,teacher_id', 'student:id,name', 'latestComment.user:id,name'])
            ->where(function ($query) {
                $this->scopeToOwnOrAll($query);
            })
            ->orderBy('assigned_at', 'desc')
            ->get();

        $comments = [];
        if ($this->commentAssignmentId) {
            $comments = LessonAssignmentComment::where('lesson_student_assignment_id', $this->commentAssignmentId)
                ->with('user:id,name')
                ->orderBy('created_at', 'asc')
                ->get();
        }

        $layout = request()->routeIs('teacher.*') ? 'layouts.app' : 'backend.layouts.app';

        return view('backend.lessons.assignments-dashboard', [
            'assignments' => $assignments,
            'comments' => $comments,
        ])->layout($layout);
    }

    private function scopeToOwnOrAll($query): void
    {
        $user = auth()->user();

        if ($user->hasAnyRole(['administrator', 'super admin'])) {
            $query->whereHas('lesson', fn ($q) => $q->whereNotNull('id'));
        } else {
            $query->whereHas('lesson', fn ($q) => $q->where('teacher_id', $user->id));
        }
    }
}
