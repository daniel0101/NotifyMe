<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TopicEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $topic;
    protected $topicData;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($topic, $data)
    {
        $this->topic = $topic;
        $this->topicData = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel($this->topic);
    }

    /**
     * Get data to add to broadcast
     * 
     * @param null
     * @return array
     */
    public function broadcastWith(){
        return [
            'topic' => $this->topic,
            'data' => $this->topicData
        ];
    }
}
