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
     * The notifications.
     *
     * @var array    Insert a new notification into your PostgreSQL database and check if the NotificationsUpdated event is triggered. You can use the Laravel broadcast artisan command to test it:
     */
    public $notifications;
    /**
     * Create a new event instance.
     */
    public function __construct($user,$notifications)
    {
        $this->user = $user;
        $this->notifications = $notifications->toArray();
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            'notifications.'.$this->user->user_id,
        ];
        // TODO: return new PrivateChannel('private-notifications.'.$this->user->user_id);
    }
    public function broadcastWith(): array
    {
        return [
            'notifications' => $this->notifications,
        ];
    }
    public function broadcastAs(): string
    {
        return 'notifications.updated';
    }
}
