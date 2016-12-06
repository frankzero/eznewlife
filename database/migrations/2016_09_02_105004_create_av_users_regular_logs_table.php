<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvUsersRegularLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('av_users_regular_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->date('record_date');
            $table->enum('type',['W','M']);
            $table->integer('visit')->default(0)->nullable();
            $table->index(['record_date', 'type']);
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
        Schema::drop('av_users_regular_logs');
    }
}
