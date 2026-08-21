<?php

namespace App\Livewire\Frontend\Lessons;

use App\Models\User;
use App\Notifications\AssignmentStatusUpdatedNotification;
use Livewire\Component;
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

    public function incrementStatus(): void
    {
        $this->authorizeOwnership();

        $statuses = ['assigned', 'started', 'in_progress', 'completed'];
        $currentIndex = array_search($this->status, $statuses);

        if ($currentIndex !== false && $currentIndex < count($statuses) - 1) {
            $this->status = $statuses[$currentIndex + 1];
            $this->assignment->update(['status' => $this->status]);
            if ($this->status === 'completed') {
                $this->notifyCompletion();
            }
            $this->dispatch('statusUpdated');
            session()->flash('message', 'Assignment status updated!');
        }
    }

    public function markAsStarted(): void
    {
        $this->authorizeOwnership();
        $this->assignment->update(['status' => 'started']);
        $this->status = 'started';
        $this->dispatch('statusUpdated');
        session()->flash('message', 'Marked as started!');
    }

    public function markAsInProgress(): void
    {
        $this->authorizeOwnership();
        $this->assignment->update(['status' => 'in_progress']);
        $this->status = 'in_progress';
        $this->dispatch('statusUpdated');
        session()->flash('message', 'Marked as in progress!');
    }

    public function markAsCompleted(): void
    {
        $this->authorizeOwnership();
        $this->assignment->update(['status' => 'completed']);
        $this->status = 'completed';
        $this->notifyCompletion();
        $this->dispatch('statusUpdated');
        session()->flash('message', 'Marked as completed!');
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
        $assignment = $this->assignment->loadMissing('lesson.teacher');

        $recipients = User::query()
            ->where(function ($query) use ($assignment) {
                $query->whereIn('id', [$assignment->lesson->teacher_id])
                    ->orWhereHas('roles', function ($rolesQuery) {
                        $rolesQuery->whereIn('name', ['super admin', 'administrator']);
                    });
            })
            ->get();

        foreach ($recipients as $recipient) {
            $recipient->notify(new AssignmentStatusUpdatedNotification($assignment));
        }
    }

    public function render()
    {
        return view('frontend.lessons.update-student-status');
    }
}
