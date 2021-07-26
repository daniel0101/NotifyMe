<?php

use Illuminate\Support\Facades\Route;
use App\Services\SubscribeService;
use App\Events\TopicEvent;
use App\Models\Topic;
use App\Models\Subscriber;
use App\Models\TopicSubscriber;
use Illuminate\Support\Facades\Redis;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/subscribe/{topic}',function($topicParam){
    request()->validate([
        'url' => 'required'
    ]);
    
    // get subscriber - url
    $subscriberUrl = request()->url;

    //persist topic if not exist
    $topic = Topic::where('name',$topicParam)->first();

    $subscriber = Subscriber::where('url',$subscriberUrl)->first();

    if(!$topic){
        $topic = Topic::create([
            'name' => $topicParam
        ]);
    }

    //persist subscriber
    if(!$subscriber){
        $subscriber = Subscriber::create([
            'url' => $subscriberUrl
        ]);
    }

    
    // subscribe to topic
    // (new SubscribeService($topicParam))->subscribe();

    //persist subscriber with topic
    $subscribedTopic = TopicSubscriber::where(['topic_id' => $topic->id, 'subscriber_id' => $subscriber->id])->first();
    if(!$subscribedTopic){
        TopicSubscriber::create([
            'topic_id' => $topic->id,
            'subscriber_id' => $subscriber->id
        ]);
    }

    //save url topic in redis
    Redis::set(base64_encode($subscriberUrl),$topicParam);

    return response()->json([
        'url' => $subscriberUrl,
        'topic' => $topicParam
    ],200);
});

Route::post('/publish/{topic}',function($topic){
    //send event here - it also publishes to redis since i'm using redis as broadcast channel
    TopicEvent::dispatch($topic, request()->data);
    //send response
    return response()->json([
        'topic' => $topic,
        'data' => request()->data
    ]);
});
