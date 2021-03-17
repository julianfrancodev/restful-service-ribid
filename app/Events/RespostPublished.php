<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RespostPublished implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $post)
    {
        $this->post = $post;
    }


    public function broadcastOn()
    {
        return new Channel('resposts');
    }

    public function broadcastWith(){
        return [
            'id' => $this->post->id,
            'title' => $this->post->title,
        ];
    }
}
