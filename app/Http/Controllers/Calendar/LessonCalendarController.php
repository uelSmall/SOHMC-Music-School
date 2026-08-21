<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Support\Calendar\LessonCalendarColorResolver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Booking\Models\BookedLesson;

class LessonCalendarController extends Controller
{
    public function studentEvents(Request $request): JsonResponse
    {
        $studentId = (int) $request->user()->id;

        $query = BookedLesson::query()
            ->where('student_id', $studentId);

        $events = $this->buildEvents($query, 'student', $request);

        return response()->json($events);
    }

    public function teacherEvents(Request $request): JsonResponse
    {
        $teacherId = (int) $request->user()->id;

        $query = BookedLesson::query()
            ->where('teacher_id', $teacherId);

        $events = $this->buildEvents($query, 'teacher', $request);

        return response()->json($events);
    }

    public function adminEvents(Request $request): JsonResponse
    {
        $query = BookedLesson::query();

        $events = $this->buildEvents($query, 'admin', $request);

        return response()->json($events);
    }

    private function buildEvents(Builder $query, string $viewerRole, Request $request): array
    {
        $query->with([
            'student:id,name',
            'teacher:id,name',
            'instrument:id,name',
            'lessonRequest:id,student_note,teacher_note,status',
        ]);

        $this->applyDateWindow($query, $request);

        return $query
            ->orderBy('lesson_date')
            ->orderBy('lesson_start_time')
            ->get()
            ->map(function (BookedLesson $lesson) use ($viewerRole): array {
                $start = Carbon::parse(sprintf('%s %s', $lesson->lesson_date?->toDateString(), $lesson->lesson_start_time));
                $end = (clone $start)->addMinutes((int) $lesson->lesson_duration);
                $statusValue = is_object($lesson->status) ? $lesson->status->value : (string) $lesson->status;
                $statusLabel = is_object($lesson->status) && method_exists($lesson->status, 'label')
                    ? $lesson->status->label()
                    : ucfirst(str_replace('_', ' ', $statusValue));
                $colors = LessonCalendarColorResolver::forStatus($statusValue);
                $instrumentName = $lesson->instrument?->name ?? 'Music';
                $teacherName = $lesson->teacher?->name ?? 'Teacher';
                $studentName = $lesson->student?->name ?? 'Student';

                $title = match ($viewerRole) {
                    'student' => sprintf('%s Lesson with %s', $instrumentName, $teacherName),
                    'admin' => sprintf('%s: %s → %s', $instrumentName, $teacherName, $studentName),
                    default => sprintf('Lesson with %s', $studentName),
                };

                return [
                    'id' => $lesson->id,
                    'title' => $title,
                    'start' => $start->toIso8601String(),
                    'end' => $end->toIso8601String(),
                    'backgroundColor' => $colors['backgroundColor'],
                    'borderColor' => $colors['borderColor'],
                    'textColor' => $colors['textColor'],
                    'extendedProps' => [
                        'student' => $studentName,
                        'teacher' => $teacherName,
                        'instrument' => $instrumentName,
                        'date' => $start->format('M d, Y'),
                        'start_time' => $start->format('g:i A'),
                        'end_time' => $end->format('g:i A'),
                        'duration' => (int) $lesson->lesson_duration,
                        'status' => $statusValue,
                        'status_label' => $statusLabel,
                        'student_note' => $lesson->lessonRequest?->student_note,
                        'teacher_note' => $lesson->lessonRequest?->teacher_note,
                    ],
                ];
            })
            ->values()
            ->all();
    }

    private function applyDateWindow(Builder $query, Request $request): void
    {
        $start = $request->query('start');
        $end = $request->query('end');

        if (! $start || ! $end) {
            return;
        }

        try {
            $rangeStart = Carbon::parse((string) $start)->toDateString();
            $rangeEnd = Carbon::parse((string) $end)->toDateString();
        } catch (\Throwable $exception) {
            return;
        }

        $query->whereBetween('lesson_date', [$rangeStart, $rangeEnd]);
    }
}