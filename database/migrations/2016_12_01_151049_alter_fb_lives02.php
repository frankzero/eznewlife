<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFbLives02 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fb_lives', function (Blueprint $table) {
            //
            $table->string('answers',100)->nullable()->after('title'); // Admin ,User
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fb_lives', function (Blueprint $table) {
            //
        });
    }
}
