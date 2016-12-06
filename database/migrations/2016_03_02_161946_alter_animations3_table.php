<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAnimations3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animations', function (Blueprint $table) {
            //
          // $table->renameColumn('status', 'feedback');
            DB::statement( "ALTER TABLE `animations` CHANGE `status` `feedback` ENUM('1','0')  NOT NULL DEFAULT '1'");
           ;
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
            DB::statement( "ALTER TABLE `animations` CHANGE `feedback` `status` ENUM('1','0')  NOT NULL DEFAULT '1'");
        });
    }
}
