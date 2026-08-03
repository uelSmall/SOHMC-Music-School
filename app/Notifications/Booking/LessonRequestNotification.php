<?php

namespace App\Notifications\Booking;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Booking\Models\LessonRequest;

abstract class LessonRequestNotification extends Notification
{
    use Queueable;

    public function __construct(protected readonly LessonRequest $lessonRequest)
    {
    }

    abstract public static function eventKey(): string;

    abstract protected function title(): string;

    abstract protected function message(): string;

    protected function actionUrl(): string
    {
        return route('notifications.index');
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->payload();
    }

    public function toArray(object $notifiable): array
    {
        return $this->payload();
    }

    protected function payload(): array
    {
        $lessonRequest = $this->lessonRequest->loadMissing([
            'student:id,name',
            'teacher:id,name',
            'instrument:id,name',
            'lesson:id,lesson_request_id,lesson_date,lesson_start_time,lesson_end_time,status',
        ]);

        return [
            'event' => static::eventKey(),
            'title' => $this->title(),
            'message' => $this->message(),
            'lesson_request_id' => $lessonRequest->id,
            'lesson_id' => $lessonRequest->lesson?->id,
            'action_url' => $this->actionUrl(),
        ];
    }
}