<?php

namespace App\Notifications;

use App\Mail\AdminMessageMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminMessage extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param  string  $title  Short subject shown in the notification list.
     * @param  string  $message  The body of the message.
     * @param  string  $from  Name of the sender (e.g. the admin).
     * @param  bool  $alsoEmail  Whether to also deliver a copy by email.
     */
    public function __construct(
        public string $title,
        public string $message,
        public string $from,
        public bool $alsoEmail = false,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * In-app (database) is always used; email is opt-in per message.
     */
    public function via($notifiable): array
    {
        return $this->alsoEmail ? ['database', 'mail'] : ['database'];
    }

    /**
     * Get the array representation of the notification for the database channel.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'from' => $this->from,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return new AdminMessageMail(
            title: $this->title,
            message: $this->message,
            from: $this->from,
            recipientName: $notifiable->name,
        );
    }
}