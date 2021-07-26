<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class PublishService
{
    protected $topic;
    protected $payload;

    public function __construct(string $topic, string $payyoad){
        $this->topic = $topic;
        $this->payload = $payload;
    }


    public function publish(){
        //publish message to that topic
        return Redis::publish($this->topic, $this->payload);
    }
}
