<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_sources', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id')->unsigned()->index();

            $table->integer('category_id')->unsigned()->index();
            $table->integer('source_id')->unsigned()->index();
            $table->dateTime('publish_at');

            $table->string('reference', 100);
            $table->timestamps();
            $table->foreign('article_id')->references('id')->on('articles')->onUpdate('CASCADE')->onDelete('NO ACTION');
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('CASCADE')->onDelete('NO ACTION');
            $table->foreign('source_id')->references('id')->on('sources')->onUpdate('CASCADE')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('articles_sources');
    }
}
