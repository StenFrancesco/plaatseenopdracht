<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

class ServiceReviewObject {
	
	public function val($key, $default = '') {
		return isset($this->data[$key]) ? $this->data[$key] : $default;
	}
	
	public function __construct($row) {
		$this->id = $row->id;
		$this->data = $row->data ? json_decode(gzinflate($row->data), true) : [];
	}
}


class ServiceReviewVersionObject {
	
	public function versions() {
		return $this->data;
	}
	
	public function __construct($row) {
		$this->id = $row->id;
		$this->data = $row->data ? json_decode(gzinflate($row->data), true) : [];
	}
}

class Service_review {
    
	
	/**
	* Create a new service review
	*/
	public static function create_service_review($update, $version_user = false) {
		return self::update_service_review(false, $update, $version_user);
	}
	
	/**
	* Update an existing service review or create a new one
	*/
	public static function update_service_review($id, $update, $version_user = false) {
		
		// Add default fields if not set
		if (!isset($update['date_modified'])) $update['date_modified'] = time();
		if (!isset($update['date_created']) && !$id) $update['date_created'] = time();
		
		// Load the existing data and merge the changes
		if ($id) {
			if ($row = DB::table('service_reviews')->select('*')->where('id', $id)->get()->first()) {
				if ($row->data) {
					$update = array_merge(json_decode(gzinflate($row->data), true), $update);
				}
			}
			else {
				return false;
			}
		}
		
		// Create the data that should be added to the db
		$update_db = [
			'service' => $update['service'],
			'user' => $update['user'],
			'data' => gzdeflate(json_encode($update)),
			'date_created' => $update['date_created'],
			'date_modified' => $update['date_modified'],
			'is_deleted' => !empty($update['is_deleted']) ? 1 : 0
		];
		
		// Insert/update into the db
		if ($id) {
			DB::table('service_reviews')->where('id', $id)->update($update_db);
		}
		else {
			$id = DB::table('service_reviews')->insertGetId($update_db);
		}
		
		// Add a version row
		$version_row = [
			'date_created' => time(),
			'user' => $version_user,
			'data' => $update
		];
		if ($version_rows = DB::table('version_service_reviews')->select('*')->where('id', $id)->get()->first()) {
			$version_rows = json_decode(gzinflate($version_rows->data), true);
			$version_rows[] = $version_row;
			DB::table('version_service_reviews')->where('id', $id)->update([
				'id' => $id,
				'data' => gzdeflate(json_encode($version_rows))
			]);
		}
		else {
			DB::table('version_service_reviews')->insert([
				'id' => $id,
				'data' => gzdeflate(json_encode([$version_row]))
			]);
		}
		
		// Return the service review id
		return $id;
	}
	
	
	/**
	* Get a service review object by its id
	*/
	public static function get_service_review_by_id($id) {
		if ($row = DB::table('service_reviews')->select('*')->where('id', $id)->get()->first()) {
			return new ServiceReviewObject($row);
		}
		return false;
	}
	
	
	/**
	* Get all services reviews that are linked to a user
	*/
	public static function get_service_reviews_for_user($user_id) {
		
		$list = Array();
		foreach (DB::table('service_reviews')->select('*')->where('user', $user_id)->get() as $row) {
			$list[] = new ServiceReviewObject($row);
		}
		return $list;
	}
	
	
	/**
	* Get all services reviews that are linked to a service
	*/
	public static function get_service_reviews_for_service($service_id) {
		
		$list = Array();
		foreach (DB::table('service_reviews')->select('*')->where('service', $service_id)->get() as $row) {
			$list[] = new ServiceReviewObject($row);
		}
		return $list;
	}
	
	
	/**
	* Get the version history for a service review object
	*/
	public static function get_version_history_for_service_review($id) {
		if ($row = DB::table('version_service_reviews')->select('*')->where('id', $id)->get()->first()) {
			return new ServiceReviewVersionObject($row);
		}
		return false;
	}
}
