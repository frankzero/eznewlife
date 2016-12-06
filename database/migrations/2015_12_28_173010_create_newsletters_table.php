<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewslettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('content');
            $table->string('photo',200);

            $table->integer('created_user')->unsigned()->nullable();
            $table->integer('updated_user')->unsigned()->nullable();
            $table->dateTime('send_at');
            $table->timestamps();
            $table->foreign('created_user')->references('id')->on('users')
                ->onDelete('set null')
                ->onUpdate('set null');
            $table->foreign('updated_user')->references('id')->on('users')
                ->onDelete('set null')
                ->onUpdate('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('newsletters');
    }
}
