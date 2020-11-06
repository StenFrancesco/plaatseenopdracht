<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersionServiceReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
			CREATE TABLE `version_service_reviews` (
			  `id` int(10) UNSIGNED NOT NULL,
			  `data` longblob NOT NULL
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
        Schema::dropIfExists('version_service_reviews');
    }
}
