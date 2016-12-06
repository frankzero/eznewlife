<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAvUser3 extends Migration
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
            $table->date('login_date')->after('login_counts');

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
            $table->dropColumn('login_date');
        });
    }
}
