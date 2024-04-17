<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSendEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $title;
    public $content;
    public $content_type;
    public $count;

    public function __construct($user_id, $title, $content, $content_type, $count)
    {
        $this->user_id = $user_id;
        $this->title = $title;
        $this->content = $content;
        $this->content_type = $content_type;
        $this->count = $count;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notification' . $this->user_id);
    }
}
