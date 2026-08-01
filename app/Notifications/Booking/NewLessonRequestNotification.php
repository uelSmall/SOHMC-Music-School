<?php

namespace App\Notifications\Booking;

use Illuminate\Support\Carbon;

class NewLessonRequestNotification extends LessonRequestNotification
{
    public static function eventKey(): string
    {
        return 'lesson_request_submitted';
    }

    protected function title(): string
    {
        return 'New Lesson Request';
    }

    protected function message(): string
    {
        $studentName = $this->lessonRequest->student?->name ?? 'A student';
        $instrumentName = $this->lessonRequest->instrument?->name ?? 'lesson';
        $lessonDate = $this->lessonRequest->requested_date?->format('F j, Y') ?? 'an upcoming date';
        $lessonTime = Carbon::parse($this->lessonRequest->requested_start_time)->format('g:i A');

        return sprintf('%s requested a %s lesson on %s at %s.', $studentName, $instrumentName, $lessonDate, $lessonTime);
    }

    protected function actionUrl(): string
    {
        return route('teacher.lesson-requests.show', $this->lessonRequest);
    }
}