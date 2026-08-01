<?php

namespace App\Notifications\Booking;

use Illuminate\Support\Carbon;

class BookedLessonRescheduledNotification extends LessonLifecycleNotification
{
    public static function eventKey(): string
    {
        return 'booked_lesson_rescheduled';
    }

    protected function title(): string
    {
        return 'Lesson Rescheduled';
    }

    protected function message(): string
    {
        $instrumentName = $this->lesson->instrument?->name ?? 'lesson';
        $teacherName = $this->lesson->teacher?->name ?? 'your teacher';
        $date = $this->lesson->lesson_date?->format('F j, Y') ?? 'a new date';
        $startTime = Carbon::parse($this->lesson->lesson_start_time)->format('g:i A');

        return sprintf('Your %s lesson with %s has been rescheduled to %s at %s.', $instrumentName, $teacherName, $date, $startTime);
    }

    protected function actionUrl(): string
    {
        return route('student.booking-management.show', $this->lesson);
    }
}