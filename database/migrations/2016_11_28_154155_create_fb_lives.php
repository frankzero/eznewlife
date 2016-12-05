<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFbLives extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_lives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fb_video_id');
            $table->string('like_txt',100)->nullable();
            $table->string('love_txt',100)->nullable();
            $table->string('haha_txt',100)->nullable();
            $table->string('shock_txt',100)->nullable();
            $table->string('sad_txt',100)->nullable();
            $table->string('angry_txt',100)->nullable();
            $table->integer('created_user')->unsigned()->nullable();
            $table->integer('updated_user')->unsigned()->nullable();
            $table->foreign('created_user')->references('id')->on('users')
                ->onDelete('set null')
                ->onUpdate('set null');
            $table->foreign('updated_user')->references('id')->on('users')
                ->onDelete('set null')
                ->onUpdate('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fb_lives');
    }
}
