<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

class ServiceObject extends \App\Classes\DefaultObject {
	
	public function owners() {
		$users = [];
		foreach ($this->val('service_owners', []) as $row) {
			if ($row['user']) $users[$row['user']] = ['user' => \App\Models\User::find($row['user']), 'rights' => $row['rights']];
		}
		return $users;
	}
	
	public function __construct($row) {
		$this->id = $row->id;
		$this->data = $row->data ? json_decode(gzinflate($row->data), true) : [];
	}
}


class ServiceVersionObject {
	
	public function versions() {
		return $this->data;
	}
	
	public function __construct($row) {
		$this->id = $row->id;
		$this->data = $row->data ? json_decode(gzinflate($row->data), true) : [];
	}
}

class Service {
    
	
	/**
	* Create a new service
	*/
	public static function create_service($update, $version_user = false) {
		return self::update_service(false, $update, $version_user);
	}
	
	/**
	* Update an existing service or create a new one
	*/
	public static function update_service($id, $update, $version_user = false) {
		
		// Add default fields if not set
		if (!isset($update['date_modified'])) $update['date_modified'] = time();
		if (!isset($update['date_created']) && !$id) $update['date_created'] = time();
		
		// Load the existing data and merge the changes
		if ($id) {
			if ($row = DB::table('services')->select('*')->where('id', $id)->get()->first()) {
				if ($row->data) {
					$update = array_merge(json_decode(gzinflate($row->data), true), $update);
				}
			}
			else {
				return false;
			}
		}
		
		// Set the service owners
		$service_owners = [$update['created_by'] => ['rights' => 'admin', 'user' => $update['created_by']]];
		if (!empty($update['owners'])) {
			foreach ($update['owners'] as $owner) {
				if (!isset($service_owners[$owner['user']])) $service_owners[$owner['user']] = $owner;
			}
		}
		$update['service_owners'] = $service_owners;
		
		// Create the data that should be added to the db
		$update_db = [
			'data' => gzdeflate(json_encode($update)),
			'date_created' => $update['date_created'],
			'date_modified' => $update['date_modified'],
			'created_by' => $update['created_by'],
			'is_deleted' => !empty($update['is_deleted']) ? 1 : 0
		];
		
		// Insert/update into the db
		if ($id) {
			DB::table('services')->where('id', $id)->update($update_db);
		}
		else {
			$id = DB::table('services')->insertGetId($update_db);
		}
		
		// Link the users to the service
		$service_owner_ids = array_keys($service_owners);
		foreach (DB::table('service_owners')->select('*')->where('service', $id)->get() as $db_user) {
			if (!in_array($db_user->user, $service_owner_ids)) DB::table('service_owners')->where('id', $db_user->id)->delete();
			else $service_owner_ids = array_diff($service_owner_ids, [$db_user->user]);
		}
		foreach ($service_owner_ids as $user) {
			DB::table('service_owners')->insert([
				'user' => $user,
				'service' => $id
			]);
		}
		
		// Add a version row
		$version_row = [
			'date_created' => time(),
			'user' => $version_user,
			'data' => $update
		];
		if ($version_rows = DB::table('version_services')->select('*')->where('id', $id)->get()->first()) {
			$version_rows = json_decode(gzinflate($version_rows->data), true);
			$version_rows[] = $version_row;
			DB::table('version_services')->where('id', $id)->update([
				'id' => $id,
				'data' => gzdeflate(json_encode($version_rows))
			]);
		}
		else {
			DB::table('version_services')->insert([
				'id' => $id,
				'data' => gzdeflate(json_encode([$version_row]))
			]);
		}
		
		// Return the service id
		return $id;
	}
	
	
	/**
	* Add an owner to a service
	*/
	public static function add_owner_to_service($service_id, $user_id, $rights, $version_user = false) {
		if ($service = self::get_service_by_id($service_id)) {
			$owners = $service->val('owners', []);
			if (!isset($owners[$user_id])) {
				$owners[$user_id] = ['rights' => $rights, 'user' => $user_id];
				return self::update_service($service_id, ['owners' => $owners], $version_user);
			}
			else if ($owners[$user_id]['rights'] != $rights) {
				$owners[$user_id]['rights'] = $rights;
				return self::update_service($service_id, ['owners' => $owners], $version_user);
			}
		}
	}
	
	
	/**
	* Remove an existing service owner (not possible to remove the create user)
	*/
	public static function remove_owner_from_service($service_id, $user_id, $version_user = false) {
		if ($service = self::get_service_by_id($service_id)) {
			$owners = $service->val('owners', []);
			if (isset($owners[$user_id])) {
				unset($owners[$user_id]);
				return self::update_service($service_id, ['owners' => $owners], $version_user);
			}
		}
	}
	
	
	/**
	* Get a service object by its id
	*/
	public static function get_service_by_id($id) {
		if ($row = DB::table('services')->select('*')->where('id', $id)->get()->first()) {
			return new ServiceObject($row);
		}
		return false;
	}
	
	
	/**
	* Get all services that are linked to a user
	*/
	public static function get_services_for_user($user_id) {
		
		$list = Array();
		foreach (DB::table('service_owners')->select('*')->where('user', $user_id)->get() as $row) {
			$list[] = self::get_service_by_id($row->service);
		}
		return $list;
	}
	
	
	/**
	* Get all services from the database (only for testing)
	*/
	public static function get_all_services() {
		
		$list = Array();
		foreach (DB::table('services')->select('*')->get() as $row) {
			$list[] = new ServiceObject($row);
		}
		return $list;
	}
	
	
	/**
	* Get the version history for a service object
	*/
	public static function get_version_history_for_service($id) {
		if ($row = DB::table('version_services')->select('*')->where('id', $id)->get()->first()) {
			return new ServiceVersionObject($row);
		}
		return false;
	}
}
