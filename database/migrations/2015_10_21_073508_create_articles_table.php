<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned()->index();
            $table->string('title');
            $table->text('content');
            $table->enum('status', array('0', '1','2'))->default(0)->index(); // 草稿 0 ，發佈 1 ,2 預留狀態
            $table->integer('created_user')->unsigned()->nullable();
            $table->integer('updated_user')->unsigned()->nullable();
            $table->dateTime('publish_at');
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
        Schema::drop('articles');
    }
}
