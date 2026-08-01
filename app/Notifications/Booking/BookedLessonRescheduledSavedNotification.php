<?php

namespace App\Notifications\Booking;

use Illuminate\Support\Carbon;

class BookedLessonRescheduledSavedNotification extends LessonLifecycleNotification
{
    public static function eventKey(): string
    {
        return 'booked_lesson_rescheduled_saved';
    }

    protected function title(): string
    {
        return 'Lesson Reschedule Saved';
    }

    protected function message(): string
    {
        $studentName = $this->lesson->student?->name ?? 'The student';
        $instrumentName = $this->lesson->instrument?->name ?? 'lesson';
        $date = $this->lesson->lesson_date?->format('F j, Y') ?? 'a new date';
        $startTime = Carbon::parse($this->lesson->lesson_start_time)->format('g:i A');

        return sprintf('%s accepted the updated %s lesson time on %s at %s.', $studentName, $instrumentName, $date, $startTime);
    }

    protected function actionUrl(): string
    {
        return route('teacher.lesson-management.show', $this->lesson);
    }
}