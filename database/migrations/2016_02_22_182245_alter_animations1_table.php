<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAnimations1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animations', function (Blueprint $table) {
            $table->integer('photo_width')->after('photo_size'); // Admin ,User
            $table->integer('photo_height')->after('photo_width'); // Admin ,User

            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animations', function (Blueprint $table) {
            //
            $table->dropColumn('photo_width');

            $table->dropColumn('photo_height');
        });
    }
}
