<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $userId,
        public bool $isOnline,
        public string $lastSeen = ''
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('user-status')];
    }

    public function broadcastWith(): array
    {
        return [
            'user_id'   => $this->userId,
            'is_online' => $this->isOnline,
            'last_seen' => $this->lastSeen,
        ];
    }
}
