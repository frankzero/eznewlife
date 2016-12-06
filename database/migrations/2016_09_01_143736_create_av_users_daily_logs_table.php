<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvUsersDailyLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('av_users_daily_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->date('record_date')->unique()->index();
            $table->integer('refresh')->default(0)->nullable();
            $table->integer('visit')->default(0)->nullable();
            $table->integer('refer_other')->default(0)->nullable();
            $table->integer('refer_google')->default(0)->nullable();
            $table->integer('refer_facebook')->default(0)->nullable();
            $table->integer('refer_avbody')->default(0)->nullable();
            $table->integer('refer_ad')->default(0)->nullable();
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
        Schema::drop('av_users_daily_logs');
    }
}
