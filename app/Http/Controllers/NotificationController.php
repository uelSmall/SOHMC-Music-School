<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->latest()
            ->paginate(15);

        $unreadCount = $user->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function open(Request $request, string $notification): RedirectResponse
    {
        $userNotification = $this->userNotification($request, $notification);
        $this->markNotificationAsRead($userNotification);

        return redirect()->to(data_get($userNotification->data, 'action_url', route('notifications.index')));
    }

    public function markAsRead(Request $request, string $notification): RedirectResponse
    {
        $userNotification = $this->userNotification($request, $notification);
        $this->markNotificationAsRead($userNotification);

        return back();
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications()->update([
            'read_at' => now(),
        ]);

        return back()->with('notify', [
            'message' => 'All notifications marked as read.',
            'type' => 'success',
        ]);
    }

    public function destroy(Request $request, string $notification): RedirectResponse
    {
        $userNotification = $this->userNotification($request, $notification);
        $userNotification->delete();

        return back()->with('notify', [
            'message' => 'Notification deleted.',
            'type' => 'success',
        ]);
    }

    private function userNotification(Request $request, string $notificationId): DatabaseNotification
    {
        return $request->user()->notifications()->whereKey($notificationId)->firstOrFail();
    }

    private function markNotificationAsRead(DatabaseNotification $notification): void
    {
        if ($notification->read_at === null) {
            $notification->markAsRead();
        }
    }
}