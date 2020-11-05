<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use App\Models\Ad;

class AdMessageObject {
	
	public function val($key, $default = '') {
		return isset($this->data[$key]) ? $this->data[$key] : $default;
	}
	
	public function __construct($row) {
		$this->id = $row->id;
		$this->data = $row->data ? json_decode(gzinflate($row->data), true) : [];
	}
}


class AdMessageVersionObject {
	
	public function versions() {
		return $this->data;
	}
	
	public function __construct($row) {
		$this->id = $row->id;
		$this->data = $row->data ? json_decode(gzinflate($row->data), true) : [];
	}
}

class Ad_message {
    
	
	/**
	* Create a new ad message
	*/
	public static function create_ad_message($update, $version_user = false) {
		return self::update_ad_message(false, $update, $version_user);
	}
	
	/**
	* Update an existing ad message or create a new one
	*/
	public static function update_ad_message($id, $update, $version_user = false) {
		
		// Add default fields if not set
		if (!isset($update['date_modified'])) $update['date_modified'] = time();
		if (!isset($update['date_created']) && !$id) $update['date_created'] = time();
		
		// Load the existing data and merge the changes
		if ($id) {
			if ($row = DB::table('ad_messages')->select('*')->where('id', $id)->get()->first()) {
				if ($row->data) {
					$update = array_merge(json_decode(gzinflate($row->data), true), $update);
				}
			}
			else {
				return false;
			}
		}
		
		// Load the linked ad
		$linked_ad = $update['ad'] ? Ad::get_ad_by_id($update['ad']) : false;
		
		// Create the data that should be added to the db
		$update_db = [
			'ad' => $update['ad'],
			'service' => $update['service'],
			'ad_user' => $linked_ad ? $linked_ad->val('user') : 0,
			'data' => gzdeflate(json_encode($update)),
			'date_created' => $update['date_created'],
			'date_modified' => $update['date_modified'],
			'is_deleted' => !empty($update['is_deleted']) ? 1 : 0
		];
		
		// Insert/update into the db
		if ($id) {
			DB::table('ad_messages')->where('id', $id)->update($update_db);
		}
		else {
			$id = DB::table('ad_messages')->insertGetId($update_db);
		}
		
		// Add a version row
		$version_row = [
			'date_created' => time(),
			'user' => $version_user,
			'data' => $update
		];
		if ($version_rows = DB::table('version_ad_messages')->select('*')->where('id', $id)->get()->first()) {
			$version_rows = json_decode(gzinflate($version_rows->data), true);
			$version_rows[] = $version_row;
			DB::table('version_ad_messages')->where('id', $id)->update([
				'id' => $id,
				'data' => gzdeflate(json_encode($version_rows))
			]);
		}
		else {
			DB::table('version_ad_messages')->insert([
				'id' => $id,
				'data' => gzdeflate(json_encode([$version_row]))
			]);
		}
		
		// Return the ad id
		return $id;
	}
	
	
	/**
	* Get a ad object by its id
	*/
	public static function get_ad_message_by_id($id) {
		if ($row = DB::table('ad_messages')->select('*')->where('id', $id)->get()->first()) {
			return new AdMessageObject($row);
		}
		return false;
	}
	
	
	/**
	* Get all ad messages that are linked to a service
	*/
	public static function get_ad_messages_for_service($service_id) {
		
		$list = Array();
		foreach (DB::table('ad_messages')->select('*')->where('service', $service_id)->get() as $row) {
			$list[] = new AdMessageObject($row);
		}
		return $list;
	}
	
	/**
	* Get all ad messages that are posted to a user's ads
	*/
	public static function get_ad_replies_for_user($user_id) {
		
		$list = Array();
		foreach (DB::table('ad_messages')->select('*')->where('ad_user', $user_id)->get() as $row) {
			$list[] = new AdMessageObject($row);
		}
		return $list;
	}
	
	
	/**
	* Get all ad messages for an ad
	*/
	public static function get_ad_messages_for_ad($ad_id) {
		
		$list = Array();
		foreach (DB::table('ad_messages')->select('*')->where('ad', $ad_id)->get() as $row) {
			$list[] = new AdMessageObject($row);
		}
		return $list;
	}
	
	
	/**
	* Get the version history for an ad object
	*/
	public static function get_version_history_for_ad($id) {
		if ($row = DB::table('version_ad_messages')->select('*')->where('id', $id)->get()->first()) {
			return new AdMessageVersionObject($row);
		}
		return false;
	}
}
