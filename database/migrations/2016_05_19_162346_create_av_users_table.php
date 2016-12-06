<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('av_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('nick_name');
            $table->string('email');
            $table->string('facebook_id');
            $table->string('avatar');
            $table->string('password', 60);
            $table->rememberToken();


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
        Schema::drop('av_users');
    }
}
