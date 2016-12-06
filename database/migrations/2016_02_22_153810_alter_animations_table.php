<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAnimationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animations', function (Blueprint $table) {
            $table->integer('photo_size')->after('photo'); // Admin ,User
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
            $table->dropColumn('photo_size');
        });
    }
}
