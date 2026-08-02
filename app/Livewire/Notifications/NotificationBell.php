<?php

namespace App\Livewire\Notifications;

use Illuminate\View\View;
use Livewire\Component;

class NotificationBell extends Component
{
    public function render(): View
    {
        $user = auth()->user();

        return view('livewire.notifications.notification-bell', [
            'unreadCount' => $user?->unreadNotifications()->count() ?? 0,
            'recentNotifications' => $user
                ? $user->notifications()->latest()->take(5)->get()
                : collect(),
        ]);
    }
}