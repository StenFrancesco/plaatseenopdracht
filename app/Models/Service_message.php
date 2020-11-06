<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

class ServiceMessageObject {
	
	public function val($key, $default = '') {
		return isset($this->data[$key]) ? $this->data[$key] : $default;
	}
	
	public function __construct($row) {
		$this->id = $row->id;
		$this->data = $row->data ? json_decode(gzinflate($row->data), true) : [];
	}
}


class ServiceMessageVersionObject {
	
	public function versions() {
		return $this->data;
	}
	
	public function __construct($row) {
		$this->id = $row->id;
		$this->data = $row->data ? json_decode(gzinflate($row->data), true) : [];
	}
}

class Service_message {
    
	
	/**
	* Create a new service
	*/
	public static function create_service_message($update, $version_user = false) {
		return self::update_service_message(false, $update, $version_user);
	}
	
	/**
	* Update an existing service or create a new one
	*/
	public static function update_service_message($id, $update, $version_user = false) {
		
		// Add default fields if not set
		if (!isset($update['date_modified'])) $update['date_modified'] = time();
		if (!isset($update['date_created']) && !$id) $update['date_created'] = time();
		
		// Load the existing data and merge the changes
		if ($id) {
			if ($row = DB::table('service_messages')->select('*')->where('id', $id)->get()->first()) {
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
			DB::table('service_messages')->where('id', $id)->update($update_db);
		}
		else {
			$id = DB::table('service_messages')->insertGetId($update_db);
		}
		
		// Add a version row
		$version_row = [
			'date_created' => time(),
			'user' => $version_user,
			'data' => $update
		];
		if ($version_rows = DB::table('version_service_messages')->select('*')->where('id', $id)->get()->first()) {
			$version_rows = json_decode(gzinflate($version_rows->data), true);
			$version_rows[] = $version_row;
			DB::table('version_service_messages')->where('id', $id)->update([
				'id' => $id,
				'data' => gzdeflate(json_encode($version_rows))
			]);
		}
		else {
			DB::table('version_service_messages')->insert([
				'id' => $id,
				'data' => gzdeflate(json_encode([$version_row]))
			]);
		}
		
		// Return the service message id
		return $id;
	}
	
	
	/**
	* Get a service message object by its id
	*/
	public static function get_service_message_by_id($id) {
		if ($row = DB::table('service_messages')->select('*')->where('id', $id)->get()->first()) {
			return new ServiceMessageObject($row);
		}
		return false;
	}
	
	
	/**
	* Get all services messages that are linked to a user
	*/
	public static function get_service_messages_for_user($user_id) {
		
		$list = Array();
		foreach (DB::table('service_messages')->select('*')->where('user', $user_id)->get() as $row) {
			$list[] = new ServiceMessageObject($row);
		}
		return $list;
	}
	
	
	/**
	* Get all services messages that are linked to a service
	*/
	public static function get_service_messages_for_service($service_id) {
		
		$list = Array();
		foreach (DB::table('service_messages')->select('*')->where('service', $service_id)->get() as $row) {
			$list[] = new ServiceMessageObject($row);
		}
		return $list;
	}
	
	
	/**
	* Get the version history for a service message object
	*/
	public static function get_version_history_for_service_message($id) {
		if ($row = DB::table('version_service_messages')->select('*')->where('id', $id)->get()->first()) {
			return new ServiceMessageVersionObject($row);
		}
		return false;
	}
}
