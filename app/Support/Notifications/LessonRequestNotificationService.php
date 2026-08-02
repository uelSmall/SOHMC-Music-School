<?php

namespace App\Support\Notifications;

use App\Models\User;
use App\Notifications\Booking\LessonConfirmedNotification;
use App\Notifications\Booking\LessonRejectedNotification;
use App\Notifications\Booking\LessonRescheduledNotification;
use App\Notifications\Booking\LessonSuggestionAcceptedNotification;
use App\Notifications\Booking\NewLessonRequestNotification;
use Illuminate\Notifications\Notification as BaseNotification;
use Modules\Booking\Models\LessonRequest;

class LessonRequestNotificationService
{
    public function notifyTeacherOfNewRequest(LessonRequest $lessonRequest): void
    {
        $teacher = $lessonRequest->teacher;

        if (! $teacher) {
            return;
        }

        $this->sendOnce($teacher, new NewLessonRequestNotification($lessonRequest));
    }

    public function notifyStudentLessonConfirmed(LessonRequest $lessonRequest): void
    {
        $student = $lessonRequest->student;

        if (! $student) {
            return;
        }

        $this->sendOnce($student, new LessonConfirmedNotification($lessonRequest));
    }

    public function notifyStudentLessonRescheduled(LessonRequest $lessonRequest): void
    {
        $student = $lessonRequest->student;

        if (! $student) {
            return;
        }

        $this->sendOnce($student, new LessonRescheduledNotification($lessonRequest));
    }

    public function notifyStudentLessonRejected(LessonRequest $lessonRequest): void
    {
        $student = $lessonRequest->student;

        if (! $student) {
            return;
        }

        $this->sendOnce($student, new LessonRejectedNotification($lessonRequest));
    }

    public function notifyTeacherSuggestionAccepted(LessonRequest $lessonRequest): void
    {
        $teacher = $lessonRequest->teacher;

        if (! $teacher) {
            return;
        }

        $this->sendOnce($teacher, new LessonSuggestionAcceptedNotification($lessonRequest));
    }

    private function sendOnce(User $notifiable, BaseNotification $notification): void
    {
        $eventKey = $notification::eventKey();
        $notificationData = $notification->toDatabase($notifiable);
        $lessonRequestId = $notificationData['lesson_request_id'] ?? null;

        if ($lessonRequestId === null) {
            return;
        }

        $alreadySent = $notifiable->notifications()
            ->where('type', $notification::class)
            ->get()
            ->contains(function ($existing) use ($eventKey, $lessonRequestId): bool {
                return (string) data_get($existing->data, 'event') === $eventKey
                    && (int) data_get($existing->data, 'lesson_request_id') === (int) $lessonRequestId;
            });

        if ($alreadySent) {
            return;
        }

        $notifiable->notify($notification);
    }
}