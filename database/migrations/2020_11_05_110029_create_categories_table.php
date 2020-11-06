<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
			CREATE TABLE `categories` (
			  `id` int(10) UNSIGNED NOT NULL,
			  `parent` int(10) UNSIGNED NOT NULL DEFAULT 0,
			  `title` varchar(255) NOT NULL DEFAULT '',
			  `date_created` int(10) UNSIGNED NOT NULL,
			  `date_modified` int(10) UNSIGNED NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
		");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
