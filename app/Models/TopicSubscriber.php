<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'subscriber_id'
    ];
}
