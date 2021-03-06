<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique()->nullable();
            $table->string('title');
            $table->integer('channel_id')->unsigned();
            $table->integer('replies_count')->default(0);
            $table->integer('user_id')->unsigned();
            $table->bigInteger('best_reply_id')->unsigned()->nullable();
            $table->text('body');
            $table->timestamps();
            $table->boolean('locked')->default(false);
            $table->foreign('best_reply_id')
                    ->references('id')
                    ->on('replies')
                    ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}
