<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

class User_fileObject extends \App\Classes\DefaultObject {
	
	public function url() {
		if ($this->val('file_created')) {
			return \App\Classes\BzgS3::get_url($this->val('uid_name'));
		}
		return '';
	}
	
	public function __construct($row) {
		$this->id = $row->id;
		$this->data = (Array)$row;
	}
}


class User_file {
    
	public static function create_userfile($file_data, $filename, $user_id) {
		$uid = \Illuminate\Support\Str::random(32);
		
		$ext = explode('.', $filename);
		$ext = strtolower(end($ext));
		
		$filesize = strlen($file_data);
		
		$update = [
			'filename' => $filename,
			'uid_name' => $uid . '.' . $ext,
			'created_by' => $user_id,
			'date_created' => time(),
			'filesize' => $filesize,
			'filetype' => $ext,
			'file_created' => 0,
			'thumbnail_created' => 0
		];
		
		if (\App\Classes\BzgS3::put($uid . '.' . $ext, $file_data)) {
			$update['file_created'] = 1;
		}
		
		$id = DB::table('userfile')->insertGetId($update);
		return $id;
	}
	
	public static function get_userfile($id) {
		if ($row = DB::table('userfile')->select('*')->where('id', $id)->get()->first()) {
			return new User_fileObject($row);
		}
		return false;
	}
}
