<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvUsersLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('av_users_logs', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('av_user_id')->unsigned()->nullable();
            $table->string('session',100);
            $table->string('refer',255);
            $table->string('url',255);
            $table->string('ip',15);
            $table->string('agent',30);
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
        Schema::drop('av_users_logs');
    }
}
