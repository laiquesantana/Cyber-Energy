<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatHistoriesTable extends Migration
{
    public function up():void
    {
        Schema::create('chat_histories', function (Blueprint $table) {
            $table->id();
            $table->text('user_input');
            $table->text('ai_response');
            $table->timestamps();
        });
    }

    public function down():void
    {
        Schema::dropIfExists('chat_histories');
    }
}
