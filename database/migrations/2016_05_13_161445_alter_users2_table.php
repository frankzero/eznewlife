<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsers2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('facebook_id')->after('email');
            $table->string('avatar')->after('facebook_id');
            $table->string('nick_name')->after('name');
            DB::statement('ALTER TABLE `users` CHANGE `role` `role` ENUM(\'A\',\'U\',\'V\') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT \'V\';');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('nick_name');
            $table->dropColumn('facebook_id');
            $table->dropColumn('avatar');

        });
    }
}
