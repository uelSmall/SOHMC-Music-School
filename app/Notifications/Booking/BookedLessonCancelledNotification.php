<?php

namespace App\Notifications\Booking;

class BookedLessonCancelledNotification extends LessonLifecycleNotification
{
    public static function eventKey(): string
    {
        return 'booked_lesson_cancelled';
    }

    protected function title(): string
    {
        return 'Lesson Cancelled';
    }

    protected function message(): string
    {
        $instrumentName = $this->lesson->instrument?->name ?? 'lesson';
        $teacherName = $this->lesson->teacher?->name ?? 'your teacher';

        return sprintf('Your %s lesson with %s has been cancelled.', $instrumentName, $teacherName);
    }

    protected function actionUrl(): string
    {
        return route('student.booking-management.show', $this->lesson);
    }
}