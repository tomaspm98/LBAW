<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationsUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * The authenticated user.
     *
     * @var \App\Models\Member
     */
    public $user;
    /**
     * The authenticated user.
     *
     * @var \App\Models\Notification
     * 
     */
    public $notifications;
    /**
     * Create a new event instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->notifications = $user->unreadNotifications;
        Log::info('Unread notifications: ',$this->notifications);
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('notifications'.$this->user->id)
        ];
    }
    public function broadcastWith(): array
    {
        return [
            'notifications' => $this->notifications
        ];
    }
}
