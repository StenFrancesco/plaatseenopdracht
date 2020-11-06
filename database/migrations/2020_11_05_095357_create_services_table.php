<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
			CREATE TABLE `services` (
			  `id` int(10) UNSIGNED NOT NULL,
			  `data` longblob NOT NULL DEFAULT '',
			  `date_created` int(10) UNSIGNED NOT NULL,
			  `date_modified` int(10) UNSIGNED NOT NULL,
			  `created_by` int(10) UNSIGNED NOT NULL,
			  `is_deleted` tinyint(3) UNSIGNED NOT NULL
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
        Schema::dropIfExists('services');
    }
}
