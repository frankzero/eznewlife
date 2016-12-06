<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAnimations2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animations', function (Blueprint $table) {
            $table->string('org_photo')->after('title'); // Admin ,User
            $table->enum('status', array('1', '0'))->default('1')->after('title');; // Normal 1  Error 0

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
            $table->dropColumn('org_photo');

            $table->dropColumn('status');
        });
    }
}
