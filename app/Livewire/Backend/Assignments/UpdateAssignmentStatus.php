<?php

namespace App\Livewire\Backend\Assignments;

use Livewire\Component;
use Modules\Lesson\Enums\AssignmentStatus;
use Modules\Lesson\Models\LessonStudentAssignment;

class UpdateAssignmentStatus extends Component
{
    public LessonStudentAssignment $assignment;

    public string $status;

    #[\Livewire\Attributes\Computed]
    public function statusOptions()
    {
        return collect(AssignmentStatus::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()]);
    }

    public function mount(): void
    {
        $this->authorizeOwnership();
        $this->status = $this->assignment->status->value;
    }

    public function updateStatus(string $newStatus): void
    {
        $this->authorizeOwnership();

        $validValues = implode(',', array_map(fn($case) => $case->value, AssignmentStatus::cases()));
        $this->validate([
            'status' => "required|string|in:{$validValues}",
        ]);

        // Re-validate the argument against the same allowed values
        if (! in_array($newStatus, array_column(AssignmentStatus::cases(), 'value'))) {
            abort(403, 'Invalid status value.');
        }

        $this->assignment->update(['status' => $newStatus]);
        $this->status = $newStatus;

        $this->dispatch('statusUpdated');
        $this->dispatch('notify', message: 'Assignment status updated successfully.', type: 'success');
    }

    private function authorizeOwnership(): void
    {
        $user = auth()->user();

        if ($user->hasAnyRole(['administrator', 'super admin'])) {
            return;
        }

        $lessonOwnerId = $this->assignment->lesson?->teacher_id;

        if ($user->hasRole('teacher') && $lessonOwnerId && (int) $lessonOwnerId === (int) $user->id) {
            return;
        }

        abort(403, 'You do not have permission to modify this assignment.');
    }

    public function render()
    {
        return view('backend.assignments.update-status', [
            'statusOptions' => $this->statusOptions(),
        ]);
    }
}
