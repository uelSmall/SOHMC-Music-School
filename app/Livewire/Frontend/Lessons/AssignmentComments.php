<?php

namespace App\Livewire\Frontend\Lessons;

use App\Notifications\AssignmentCommentNotification;
use Livewire\Component;
use Modules\Lesson\Models\LessonAssignmentComment;
use Modules\Lesson\Models\LessonStudentAssignment;

class AssignmentComments extends Component
{
    public int $assignmentId;

    public string $commentBody = '';

    public function mount(int $assignmentId): void
    {
        $this->assignmentId = $assignmentId;
        $this->authorizeAccess();
    }

    public function sendComment(): void
    {
        $this->authorizeAccess();

        $this->validate([
            'commentBody' => 'required|string|min:1|max:5000',
        ]);

        $assignment = LessonStudentAssignment::findOrFail($this->assignmentId);

        $comment = LessonAssignmentComment::create([
            'lesson_student_assignment_id' => $assignment->id,
            'user_id' => auth()->id(),
            'body' => $this->commentBody,
        ]);

        $this->notifyRecipient($comment, $assignment);

        $this->commentBody = '';
        $this->dispatch('notify', message: 'Comment sent.', type: 'success');
    }

    private function notifyRecipient(LessonAssignmentComment $comment, LessonStudentAssignment $assignment): void
    {
        $teacher = $assignment->lesson?->teacher;
        if ($teacher && (int) $teacher->id !== (int) auth()->id()) {
            $teacher->notify(new AssignmentCommentNotification($comment));
        }

        $student = $assignment->student;
        if ($student && (int) $student->id !== (int) auth()->id()) {
            $student->notify(new AssignmentCommentNotification($comment));
        }
    }

    private function authorizeAccess(): void
    {
        $user = auth()->user();
        $assignment = LessonStudentAssignment::with('lesson:teacher_id')->findOrFail($this->assignmentId);

        if ((int) $assignment->student_id === (int) $user->id) {
            return;
        }

        if ((int) $assignment->lesson->teacher_id === (int) $user->id) {
            return;
        }

        if ($user->hasAnyRole(['administrator', 'super admin'])) {
            return;
        }

        abort(403);
    }

    public function getComments()
    {
        return LessonAssignmentComment::where('lesson_student_assignment_id', $this->assignmentId)
            ->with('user:id,name')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function render()
    {
        return view('frontend.lessons.assignment-comments', [
            'comments' => $this->getComments(),
        ]);
    }
}
