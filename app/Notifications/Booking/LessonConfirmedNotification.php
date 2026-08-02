<?php

namespace App\Notifications\Booking;

class LessonConfirmedNotification extends LessonRequestNotification
{
    public static function eventKey(): string
    {
        return 'lesson_confirmed';
    }

    protected function title(): string
    {
        return 'Lesson Confirmed';
    }

    protected function message(): string
    {
        $teacherName = $this->lessonRequest->teacher?->name ?? 'your teacher';
        $instrumentName = $this->lessonRequest->instrument?->name ?? 'lesson';

        return sprintf('Your %s lesson with %s has been confirmed.', $instrumentName, $teacherName);
    }

    protected function actionUrl(): string
    {
        return $this->lessonRequest->lesson
            ? route('lessons.show', $this->lessonRequest->lesson)
            : route('student.lesson-requests.index');
    }
}