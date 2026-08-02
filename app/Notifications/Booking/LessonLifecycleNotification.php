<?php

namespace App\Notifications\Booking;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Booking\Models\BookedLesson;

abstract class LessonLifecycleNotification extends Notification
{
    use Queueable;

    public function __construct(protected readonly BookedLesson $lesson)
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
        $lesson = $this->lesson->loadMissing([
            'student:id,name',
            'teacher:id,name',
            'instrument:id,name',
            'lessonRequest:id,student_note,teacher_note,status',
        ]);

        return [
            'event' => static::eventKey(),
            'title' => $this->title(),
            'message' => $this->message(),
            'lesson_id' => $lesson->id,
            'lesson_request_id' => $lesson->lesson_request_id,
            'action_url' => $this->actionUrl(),
        ];
    }
}