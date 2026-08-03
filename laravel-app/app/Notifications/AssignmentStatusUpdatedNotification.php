<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Lesson\Models\LessonStudentAssignment;

class AssignmentStatusUpdatedNotification extends Notification
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
        $assignment = $this->assignment->loadMissing('lesson:id,title', 'student:id,name');

        return [
            'type' => 'assignment_status_updated',
            'title' => 'Assignment marked completed',
            'message' => $assignment->student->name.' completed "'.$assignment->lesson->title.'".',
            'status' => $assignment->status->value,
            'lesson_id' => $assignment->lesson_id,
            'assignment_id' => $assignment->id,
            'url' => route('backend.assignments.index'),
        ];
    }
}
