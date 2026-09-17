<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    /**
     * Record a signed-in event for the admin Recent Activity feed.
     */
    public function handle(Login $event): void
    {
        if ($event->user !== null) {
            log_activity('signed in', $event->user, null, 'security', $event->user);
        }
    }
}