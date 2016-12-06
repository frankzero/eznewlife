<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvUsersCollectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('av_users_collects', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('av_user_id')->unsigned()->nullable();
            $table->integer('article_id')->unsigned()->nullable();

            $table->foreign('article_id')->references('id')->on('articles')->onUpdate('CASCADE')->onDelete('NO ACTION');
            $table->foreign('av_user_id')->references('id')->on('av_users')->onUpdate('CASCADE')->onDelete('NO ACTION');

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
        Schema::drop('av_users_collects');
    }
}
