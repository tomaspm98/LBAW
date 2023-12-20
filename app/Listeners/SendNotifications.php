<?php

namespace App\Listeners;

use App\Events\NotificationsUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendNotifications
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotificationsUpdated $event): void
    {
        Log::info('Notifications Updated Event Fired', [
            'user' => $event->user,
            'notifications' => $event->notifications,
        ]);
    }
}
