<?php

namespace App\Support\Notifications;

use App\Notifications\Booking\BookedLessonCancelledNotification;
use App\Notifications\Booking\BookedLessonRescheduledNotification;
use App\Notifications\Booking\BookedLessonRescheduledSavedNotification;
use Modules\Booking\Models\BookedLesson;

class BookedLessonNotificationService
{
    public function notifyStudentLessonCancelled(BookedLesson $lesson): void
    {
        $lesson->student?->notify(new BookedLessonCancelledNotification($lesson));
    }

    public function notifyStudentLessonRescheduled(BookedLesson $lesson): void
    {
        $lesson->student?->notify(new BookedLessonRescheduledNotification($lesson));
    }

    public function notifyTeacherLessonRescheduled(BookedLesson $lesson): void
    {
        $lesson->teacher?->notify(new BookedLessonRescheduledSavedNotification($lesson));
    }
}