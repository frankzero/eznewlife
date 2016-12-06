<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEnlUser2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enl_users', function (Blueprint $table) {
            //
            DB::statement('ALTER TABLE `enl_users` CHANGE `facebook_id` `facebook_id` VARCHAR(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;');
            DB::statement('ALTER TABLE `enl_users` ADD UNIQUE(`facebook_id`);');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enl_users', function (Blueprint $table) {
            //
            DB::statement('ALTER TABLE `enl_users` CHANGE `facebook_id` `facebook_id` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;');
            DB::statement('ALTER TABLE `enl_users` DROP UNIQUE(`facebook_id`);');
        });
    }
}
