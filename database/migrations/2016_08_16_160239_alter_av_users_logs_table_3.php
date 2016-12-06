<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAvUsersLogsTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('av_users_logs', function (Blueprint $table) {
            //
            $table->dropColumn('login_date');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('av_users_logs', function (Blueprint $table) {
            //
            $table->date('login_date')->after('av_user_id');
        });
    }
}
