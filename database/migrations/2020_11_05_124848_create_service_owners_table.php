<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
			CREATE TABLE `service_owners` (
			  `id` int(10) UNSIGNED NOT NULL,
			  `service` int(10) UNSIGNED NOT NULL,
			  `user` int(10) UNSIGNED NOT NULL
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
        Schema::dropIfExists('service_owners');
    }
}
