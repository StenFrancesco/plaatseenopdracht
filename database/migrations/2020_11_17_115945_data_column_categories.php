<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DataColumnCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		DB::statement("ALTER TABLE `categories` ADD `data` BLOB NULL DEFAULT NULL AFTER `title`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
		DB::statement("ALTER TABLE `categories` DROP `data`");
    }
}