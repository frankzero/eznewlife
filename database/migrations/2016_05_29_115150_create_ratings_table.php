<?php

use Illuminate\Database\Migrations\Migration;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ratings', function ($table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('rating');
            $table->morphs('rateable');
            $table->integer('av_user_id')->unsigned();
            $table->index('av_user_id');
            $table->index('rateable_id');
            $table->index('rateable_type');
            $table->foreign('av_user_id')->references('id')->on('av_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('ratings');
		
    }
}
