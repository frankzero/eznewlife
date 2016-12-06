<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnlUsersCollectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enl_users_collects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('enl_user_id')->unsigned()->nullable();
            $table->integer('article_id')->unsigned()->nullable();

            $table->foreign('article_id')->references('id')->on('articles')->onUpdate('CASCADE')->onDelete('NO ACTION');
            $table->foreign('enl_user_id')->references('id')->on('enl_users')->onUpdate('CASCADE')->onDelete('NO ACTION');
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
        Schema::drop('enl_users_collects');
    }
}
