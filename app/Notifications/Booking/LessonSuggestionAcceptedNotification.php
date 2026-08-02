<?php

namespace App\Notifications\Booking;

use Illuminate\Support\Carbon;

class LessonSuggestionAcceptedNotification extends LessonRequestNotification
{
    public static function eventKey(): string
    {
        return 'lesson_suggestion_accepted';
    }

    protected function title(): string
    {
        return 'Suggestion Accepted';
    }

    protected function message(): string
    {
        $studentName = $this->lessonRequest->student?->name ?? 'The student';
        $instrumentName = $this->lessonRequest->instrument?->name ?? 'lesson';
        $lessonDate = $this->lessonRequest->suggested_date?->format('F j, Y') ?? 'the suggested date';
        $lessonTime = Carbon::parse($this->lessonRequest->suggested_start_time)->format('g:i A');

        return sprintf('%s accepted your suggested %s lesson time on %s at %s.', $studentName, $instrumentName, $lessonDate, $lessonTime);
    }

    protected function actionUrl(): string
    {
        return route('teacher.lesson-requests.show', $this->lessonRequest);
    }
}