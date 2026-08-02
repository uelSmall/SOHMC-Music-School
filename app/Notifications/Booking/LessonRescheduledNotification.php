<?php

namespace App\Notifications\Booking;

use Illuminate\Support\Carbon;

class LessonRescheduledNotification extends LessonRequestNotification
{
    public static function eventKey(): string
    {
        return 'lesson_rescheduled';
    }

    protected function title(): string
    {
        return 'Lesson Rescheduled';
    }

    protected function message(): string
    {
        $teacherName = $this->lessonRequest->teacher?->name ?? 'your teacher';
        $instrumentName = $this->lessonRequest->instrument?->name ?? 'lesson';
        $lessonDate = $this->lessonRequest->suggested_date?->format('F j, Y') ?? 'a new date';
        $lessonTime = Carbon::parse($this->lessonRequest->suggested_start_time)->format('g:i A');

        return sprintf('%s suggested moving your %s lesson to %s at %s.', $teacherName, $instrumentName, $lessonDate, $lessonTime);
    }

    protected function actionUrl(): string
    {
        return route('student.lesson-requests.index');
    }
}