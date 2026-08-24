<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Lesson\Models\LessonAssignmentComment;

class AssignmentCommentNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly LessonAssignmentComment $comment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $comment = $this->comment->loadMissing('assignment.lesson:id,title', 'user:id,name');

        $senderName = $comment->user->name ?? 'Someone';
        $lessonTitle = $comment->assignment->lesson->title ?? 'a lesson';

        return [
            'type' => 'assignment_comment',
            'title' => 'New comment on assignment',
            'message' => "{$senderName} commented on \"{$lessonTitle}\".",
            'lesson_id' => $comment->assignment->lesson_id,
            'assignment_id' => $comment->assignment_id,
            'comment_id' => $comment->id,
            'url' => route('lessons.show', $comment->assignment->lesson_id),
        ];
    }
}
