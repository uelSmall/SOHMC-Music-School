<?php

namespace App\Livewire\Backend\Lessons;

use Livewire\Component;
use Modules\Lesson\Models\LessonStudentAssignment;

class AssignmentDashboard extends Component
{
    public function render()
    {
        $assignments = LessonStudentAssignment::query()
            ->with('lesson', 'student')
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
