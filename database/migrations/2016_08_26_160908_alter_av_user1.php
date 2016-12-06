<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAvUser1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('av_users', function (Blueprint $table) {
            //
           // $table->longText('description');
            DB::statement('ALTER TABLE `av_users` CHANGE `avatar` `avatar` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('av_users', function (Blueprint $table) {
            //
            DB::statement('ALTER TABLE `av_users` CHANGE `avatar` `avatar`  VARCHAR(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;');
    });
    }
}
