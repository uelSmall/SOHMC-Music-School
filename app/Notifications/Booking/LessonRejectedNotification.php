<?php

namespace App\Notifications\Booking;

class LessonRejectedNotification extends LessonRequestNotification
{
    public static function eventKey(): string
    {
        return 'lesson_rejected';
    }

    protected function title(): string
    {
        return 'Lesson Rejected';
    }

    protected function message(): string
    {
        $teacherName = $this->lessonRequest->teacher?->name ?? 'your teacher';
        $instrumentName = $this->lessonRequest->instrument?->name ?? 'lesson';

        return sprintf('Your %s lesson request was rejected by %s.', $instrumentName, $teacherName);
    }

    protected function actionUrl(): string
    {
        return route('student.lesson-requests.index');
    }
}