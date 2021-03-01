<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RespostPublished implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $respost;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($respost)
    {
        //
        $this->respost = $respost;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     * todo: change this for a Private Channel
     */
    public function broadcastOn()
    {
        return new PrivateChannel('respost');
    }

    public function broadcastWith(){
        return [
            'post_id_res' => $this->respost->post_id_res,
        ];
    }
}
