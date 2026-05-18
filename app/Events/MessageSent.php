<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message)
    {
        $this->message->load('sender');
    }

    public function broadcastOn(): array
    {
        // Private chat
        if ($this->message->receiver_id) {
            $ids = [$this->message->sender_id, $this->message->receiver_id];
            sort($ids);
            return [
                new PrivateChannel('chat.' . $ids[0] . '.' . $ids[1]),
            ];
        }

        // Group chat
        if ($this->message->group_id) {
            return [
                new Channel('group.' . $this->message->group_id),
            ];
        }

        return [];
    }

    public function broadcastWith(): array
    {
        return [
            'id'          => $this->message->id,
            'message'     => $this->message->message,
            'sender_id'   => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'group_id'    => $this->message->group_id,
            'sender_name' => $this->message->sender->name,
            'created_at'  => $this->message->created_at->format('H:i'),
        ];
    }
}
