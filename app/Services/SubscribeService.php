<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class SubscribeService
{
    protected $topic;

    public function __construct(string $topic){
        $this->topic = $topic;
    }

    public function subscribe(){
        return Redis::subscribe([$this->topic], function($message){
            echo $message;
        });
    }
}
