<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topic_subscribers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id');
            $table->foreignId('subscriber_id');
            $table->timestamps();

            $table->foreign('topic_id')->references('id')->on('topics');
            $table->foreign('subscriber_id')->references('id')->on('subscribers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topic_subscribers');
    }
}
