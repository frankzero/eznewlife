<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('photo',200);
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
        Schema::drop('animations');
    }
}
