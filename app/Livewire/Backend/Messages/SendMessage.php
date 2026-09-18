<?php

namespace App\Livewire\Backend\Messages;

use App\Models\User;
use App\Notifications\AdminMessage;
use Livewire\Component;

class SendMessage extends Component
{
    /**
     * Recipient selector value: a student id, or 'all_students' to broadcast.
     */
    public string $recipient = '';

    public string $title = '';

    public string $message = '';

    public bool $alsoEmail = false;

    public const BROADCAST = 'all_students';

    public function render()
    {
        return view('livewire.backend.messages.send-message', [
            'students' => $this->students(),
        ])->layout('components.layouts.admin');
    }

    /**
     * Active (non-deleted) students, ordered by name.
     */
    public function students()
    {
        return User::whereHas('roles', fn ($q) => $q->where('name', 'student'))
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }

    /**
     * Send the message to the chosen student (or all students).
     */
    public function send(): void
    {
        $this->validate([
            'recipient' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $isBroadcast = $this->recipient === self::BROADCAST;

        $recipients = $isBroadcast
            ? $this->students()
            : collect([User::findOrFail((int) $this->recipient)]);

        if ($recipients->isEmpty()) {
            $this->addError('recipient', __('There are no students to message yet.'));

            return;
        }

        foreach ($recipients as $student) {
            $student->notify(new AdminMessage(
                title: $this->title,
                message: $this->message,
                from: auth()->user()->name,
                alsoEmail: $this->alsoEmail,
            ));
        }

        $recipientLabel = $isBroadcast
            ? 'all '.$recipients->count().' students'
            : $recipients->first()->name;

        log_activity('sent a message to '.$recipientLabel, $isBroadcast ? null : $recipients->first(), null, 'default', auth()->user());

        session()->flash('status', $isBroadcast
            ? 'Message sent to all '.$recipients->count().' students.'
            : 'Message sent to '.$recipients->first()->name.'.');

        $this->reset(['recipient', 'title', 'message', 'alsoEmail']);
    }
}