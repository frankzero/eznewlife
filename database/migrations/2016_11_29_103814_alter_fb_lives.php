<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFbLives extends Migration
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
            $table->string('title')->default(1)->after('fb_video_id');
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
            $table->dropColumn('title');
        });
    }
}
