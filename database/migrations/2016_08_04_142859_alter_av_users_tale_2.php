<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAvUsersTale2 extends Migration
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
            $table->integer('login_counts')->default(1)->after('remember_token');
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
            $table->dropColumn('login_counts');
        });
    }
}
