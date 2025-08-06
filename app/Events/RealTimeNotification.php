<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RealTimeNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $type;
    private $userId;

    public function __construct(string $message, string $type, int $userId)
    {
        $this->message = $message;
        $this->type = $type;
        $this->userId = $userId;
    }

    // Hum private channel use karenge taaki notification sirf sahi user ko jaye
    public function broadcastOn(): array
    {
        // Channel ka naam: 'notifications.USER_ID' (e.g., 'notifications.1')
        return [new PrivateChannel('notifications.' . $this->userId)];
    }
}
