<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssociations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
			ALTER TABLE `ads`
			  ADD PRIMARY KEY (`id`),
			  ADD KEY `user` (`user`);
		");
		
		DB::statement("
			ALTER TABLE `ad_messages`
			  ADD PRIMARY KEY (`id`),
			  ADD KEY `ad` (`ad`),
			  ADD KEY `user` (`user`),
			  ADD KEY `ad_user` (`ad_user`);
		");
		
		DB::statement("
			ALTER TABLE `categories`
			  ADD PRIMARY KEY (`id`);
		");
		
		DB::statement("
			ALTER TABLE `services`
			  ADD PRIMARY KEY (`id`);
		");
		
		DB::statement("
			ALTER TABLE `service_messages`
			  ADD PRIMARY KEY (`id`),
			  ADD KEY `service` (`service`),
			  ADD KEY `user` (`user`);
		");
		
		DB::statement("
			ALTER TABLE `service_owners`
			  ADD PRIMARY KEY (`id`),
			  ADD KEY `service` (`service`),
			  ADD KEY `user` (`user`);
		");
		
		DB::statement("
			ALTER TABLE `service_reviews`
			  ADD PRIMARY KEY (`id`),
			  ADD KEY `service` (`service`,`date_created`),
			  ADD KEY `user` (`user`);
		");
		
		DB::statement("
			ALTER TABLE `version_ads`
			  ADD PRIMARY KEY (`id`) USING BTREE;
		");
		
		DB::statement("
			ALTER TABLE `version_ad_messages`
			  ADD PRIMARY KEY (`id`) USING BTREE;
		");
		
		DB::statement("
			ALTER TABLE `version_services`
			  ADD PRIMARY KEY (`id`) USING BTREE;
		");
		
		DB::statement("
			ALTER TABLE `version_service_messages`
			  ADD PRIMARY KEY (`id`) USING BTREE;
		");
		
		DB::statement("
			ALTER TABLE `version_service_reviews`
			  ADD PRIMARY KEY (`id`) USING BTREE;
		");
		
		DB::statement("
			ALTER TABLE `ads`
			  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
		");
		
		DB::statement("
			ALTER TABLE `ad_messages`
			  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
		");
		
		DB::statement("
			ALTER TABLE `categories`
			  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
		");
		
		DB::statement("
			ALTER TABLE `services`
			  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
		");
		
		DB::statement("
			ALTER TABLE `service_messages`
			  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
		");
		
		DB::statement("
			ALTER TABLE `service_owners`
			  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
		");
		
		DB::statement("
			ALTER TABLE `service_reviews`
			  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
		");
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('associations');
    }
}
