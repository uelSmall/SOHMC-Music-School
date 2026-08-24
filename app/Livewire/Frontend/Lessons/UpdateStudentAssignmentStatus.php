<?php

namespace App\Livewire\Frontend\Lessons;

use App\Models\User;
use App\Notifications\AssignmentStatusUpdatedNotification;
use Livewire\Component;
use Modules\Lesson\Enums\AssignmentStatus;
use Modules\Lesson\Models\LessonStudentAssignment;

class UpdateStudentAssignmentStatus extends Component
{
    public LessonStudentAssignment $assignment;

    public string $status;

    public function mount(): void
    {
        $this->authorizeOwnership();
        $this->status = $this->assignment->status->value;
    }

    public function markAsStarted(): void
    {
        $this->authorizeOwnership();

        if ($this->status !== AssignmentStatus::Assigned->value) {
            return;
        }

        $this->assignment->update(['status' => AssignmentStatus::Started->value]);
        $this->status = AssignmentStatus::Started->value;

        $this->dispatch('statusUpdated');
        $this->dispatch('notify', message: 'Lesson started!', type: 'success');
    }

    public function markAsInProgress(): void
    {
        $this->authorizeOwnership();

        if (! in_array($this->status, [AssignmentStatus::Assigned->value, AssignmentStatus::Started->value])) {
            return;
        }

        $this->assignment->update(['status' => AssignmentStatus::InProgress->value]);
        $this->status = AssignmentStatus::InProgress->value;

        $this->dispatch('statusUpdated');
        $this->dispatch('notify', message: 'Marked as in progress!', type: 'success');
    }

    public function markAsCompleted(): void
    {
        $this->authorizeOwnership();

        if ($this->status === AssignmentStatus::Completed->value) {
            return;
        }

        $this->assignment->update(['status' => AssignmentStatus::Completed->value]);
        $this->status = AssignmentStatus::Completed->value;

        $this->notifyCompletion();
        $this->dispatch('statusUpdated');
        $this->dispatch('notify', message: 'Lesson completed! Well done!', type: 'success');
    }

    private function authorizeOwnership(): void
    {
        $user = auth()->user();

        if ((int) $this->assignment->student_id !== (int) $user->id) {
            abort(403, 'You can only update your own assignments.');
        }
    }

    private function notifyCompletion(): void
    {
        $assignment = $this->assignment->loadMissing('lesson:id,title,teacher_id', 'lesson.teacher:id,name', 'student:id,name');

        $teacher = $assignment->lesson->teacher;
        if ($teacher) {
            $teacher->notify(new AssignmentStatusUpdatedNotification($assignment));
        }

        User::query()
            ->whereHas('roles', function ($rolesQuery) {
                $rolesQuery->whereIn('name', ['super admin', 'administrator']);
            })
            ->where('id', '!=', $teacher?->id)
            ->get()
            ->each->notify(new AssignmentStatusUpdatedNotification($assignment));
    }

    public function render()
    {
        return view('frontend.lessons.update-student-status');
    }
}
