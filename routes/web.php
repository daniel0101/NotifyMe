<?php

use Illuminate\Support\Facades\Route;
use App\Services\SubscribeService;
use App\Events\TopicEvent;

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

Route::post('/subscribe/{topic}',function($topic){
    request()->validate([
        'url' => 'required'
    ]);

    // get subscriber - url
    $subscriberUrl = request()->url;
    
    // subscribe to topic
    (new SubscribeService($topic))->subscribe();

    return response()->json([
        'url' => $subscriberUrl,
        'topic' => $topic
    ],200);
});

Route::post('/publish/{topic}',function($topic){
    //send event here
    //publish to redis
    TopicEvent::dispatch($topic, request()->data);
    //send response
    return response()->json([
        'topic' => $topic,
        'data' => request()->data
    ]);
});
