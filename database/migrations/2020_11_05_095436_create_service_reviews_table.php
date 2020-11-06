<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
			CREATE TABLE `service_reviews` (
			  `id` int(10) UNSIGNED NOT NULL,
			  `service` int(10) UNSIGNED NOT NULL,
			  `user` int(10) UNSIGNED NOT NULL,
			  `data` longblob NOT NULL,
			  `date_created` int(10) UNSIGNED NOT NULL,
			  `date_modified` int(10) UNSIGNED NOT NULL,
			  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT 0
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
        Schema::dropIfExists('service_reviews');
    }
}
