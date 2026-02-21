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
        $this->status = $this->assignment->status->value;
    }

    public function updateStatus(string $newStatus): void
    {
        $this->validate([
            'status' => 'required|string|in:' . implode(',', array_map(fn($case) => $case->value, AssignmentStatus::cases())),
        ]);

        $this->assignment->update(['status' => $newStatus]);
        $this->status = $newStatus;

        $this->dispatch('statusUpdated');
        session()->flash('message', 'Assignment status updated!');
    }

    public function render()
    {
        return view('backend.assignments.update-status', [
            'statusOptions' => $this->statusOptions(),
        ]);
    }
}
