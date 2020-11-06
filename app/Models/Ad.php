<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

class AdObject extends \App\Classes\DefaultObject {
	
	public function __construct($row) {
		$this->id = $row->id;
		$this->data = $row->data ? json_decode(gzinflate($row->data), true) : [];
	}
}


class AdVersionObject {
	
	public function versions() {
		return $this->data;
	}
	
	public function __construct($row) {
		$this->id = $row->id;
		$this->data = $row->data ? json_decode(gzinflate($row->data), true) : [];
	}
}

class Ad {
    
	
	/**
	* Create a new ad
	*/
	public static function create_ad($update, $version_user = false) {
		return self::update_ad(false, $update, $version_user);
	}
	
	/**
	* Update an existing ad or create a new one
	*/
	public static function update_ad($id, $update, $version_user = false) {
		
		// Add default fields if not set
		if (!isset($update['date_modified'])) $update['date_modified'] = time();
		if (!isset($update['date_created']) && !$id) $update['date_created'] = time();
		
		// Load the existing data and merge the changes
		if ($id) {
			if ($row = DB::table('ads')->select('*')->where('id', $id)->get()->first()) {
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
			'user' => $update['user'],
			'data' => gzdeflate(json_encode($update)),
			'date_created' => $update['date_created'],
			'date_modified' => $update['date_modified'],
			'is_deleted' => !empty($update['is_deleted']) ? 1 : 0
		];
		
		// Insert/update into the db
		if ($id) {
			DB::table('ads')->where('id', $id)->update($update_db);
		}
		else {
			$id = DB::table('ads')->insertGetId($update_db);
		}
		
		// Add a version row
		$version_row = [
			'date_created' => time(),
			'user' => $version_user,
			'data' => $update
		];
		if ($version_rows = DB::table('version_ads')->select('*')->where('id', $id)->get()->first()) {
			$version_rows = json_decode(gzinflate($version_rows->data), true);
			$version_rows[] = $version_row;
			DB::table('version_ads')->where('id', $id)->update([
				'id' => $id,
				'data' => gzdeflate(json_encode($version_rows))
			]);
		}
		else {
			DB::table('version_ads')->insert([
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
	public static function get_ad_by_id($id) {
		if ($row = DB::table('ads')->select('*')->where('id', $id)->get()->first()) {
			return new AdObject($row);
		}
		return false;
	}
	
	
	/**
	* Get all ads that are linked to a user
	*/
	public static function get_ads_for_user($user_id) {
		
		$list = Array();
		foreach (DB::table('ads')->select('*')->where('user', $user_id)->get() as $row) {
			$list[] = new AdObject($row);
		}
		return $list;
	}
	
	
	/**
	* Get all ads
	*/
	public static function get_all_ads() {
		
		$list = Array();
		foreach (DB::table('ads')->select('*')->get() as $row) {
			$list[] = new AdObject($row);
		}
		return $list;
	}
	
	
	/**
	* Get the version history for an ad object
	*/
	public static function get_version_history_for_ad($id) {
		if ($row = DB::table('version_ads')->select('*')->where('id', $id)->get()->first()) {
			return new AdVersionObject($row);
		}
		return false;
	}
}
