<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Lesson\Models\LessonStudentAssignment;

class LessonAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly LessonStudentAssignment $assignment)
    {
    }

    /**
     * Create a new notification instance.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $assignment = $this->assignment->loadMissing('lesson:id,title,teacher_id', 'lesson.teacher:id,name');

        return [
            'type' => 'lesson_assigned',
            'title' => 'New lesson assigned',
            'message' => 'You have been assigned "'.$assignment->lesson->title.'".',
            'due_date' => optional($assignment->due_date)->toDateString(),
            'lesson_id' => $assignment->lesson_id,
            'assignment_id' => $assignment->id,
            'url' => route('lessons.index'),
        ];
    }
}
