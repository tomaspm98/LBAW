<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuestionUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $totalQuestions;
    public $questionsLastWeek;
    public $newUsersLastWeek;

    /**
     * Create a new event instance.
     */
    public function __construct($totalQuestions, $questionsLastWeek, $newUsersLastWeek)
    {
        $this->totalQuestions = $totalQuestions;
        $this->questionsLastWeek = $questionsLastWeek;
        $this->newUsersLastWeek = $newUsersLastWeek;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('updates'),
        ];
    }
    public function broadcastWith(): array
    {
        return [
            'totalQuestions' => $this->totalQuestions,
            'questionsLastWeek' => $this->questionsLastWeek,
            'newUsersLastWeek' => $this->newUsersLastWeek,
        ];
    }
    public function broadcastAs(): string
    {
        return 'question.updated';
    }
    
}
