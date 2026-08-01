<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Booking\Enums\LessonStatus;
use Modules\Booking\Models\BookedLesson;

class LessonManagementController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', BookedLesson::class);

        $studentId = (int) $request->user()->id;
        $today = now()->toDateString();

        $lessons = BookedLesson::query()
            ->where('student_id', $studentId)
            ->with([
                'teacher:id,name',
                'instrument:id,name',
                'lessonRequest:id,student_note,teacher_note',
            ])
            ->orderBy('lesson_date')
            ->orderBy('lesson_start_time')
            ->get();

        $upcomingLessons = $lessons->filter(function (BookedLesson $lesson) use ($today): bool {
            return $lesson->status === LessonStatus::Scheduled && $lesson->lesson_date?->toDateString() >= $today;
        })->values();

        $completedLessons = $lessons->where('status', LessonStatus::Completed)->values();
        $cancelledLessons = $lessons->where('status', LessonStatus::Cancelled)->values();

        return view('student.lesson-management.index', compact(
            'upcomingLessons',
            'completedLessons',
            'cancelledLessons'
        ));
    }

    public function show(BookedLesson $lesson): View
    {
        $this->authorize('view', $lesson);

        $lesson->load([
            'student:id,name',
            'teacher:id,name',
            'instrument:id,name',
            'lessonRequest:id,student_note,teacher_note',
        ]);

        return view('student.lesson-management.show', compact('lesson'));
    }
}