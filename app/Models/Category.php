<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

class CategoryObject extends \App\Classes\DefaultObject {
	
	public $parent_category = null;
	public $child_categories = [];
	
	// Get all the parent ids including this id
	public function get_tree_ids() {
		$list = [$this->id];
		$temp = $this;
		for ($i = 0; $i < 100 && $temp->parent_category; $i++) {
			$list[] = $temp->parent_category->id;
			$temp = $temp->parent_category;
		}
		return $list;
	}
	
	// Get all parents of this category
	public function get_parents() {
		$list = [];
		$temp = $this;
		for ($i = 0; $i < 100 && $temp->parent_category; $i++) {
			$list[] = $temp->parent_category;
			$temp = $temp->parent_category;
		}
		return $list;
	}
	
	public function full_path() {
		$title = [$this->val('title')];
		foreach ($this->get_parents() as $parent) {
			$title[] = $parent->val('title');
		}
		return implode(' / ', array_reverse($title));
	}
	
	// Get all direct children of this category
	public function get_children() {
		return $this->child_categories;
	}
	
	public function image() {
		return $this->val('image') ? \App\Classes\BzgS3::get_url($this->val('image')) : false;
	}
	
	
	public function __construct($row) {
		$this->id = $row->id;
		$this->data = $row->data ? json_decode(gzinflate($row->data), true) : [];
	}
}

class Category {
    
	public static $category_cache = null;
	
	
	/**
	* Create a new category
	*/
	public static function create_category($update) {
		return self::update_category(false, $update);
	}
	
	/**
	* Update an existing category or create a new one
	*/
	public static function update_category($id, $update) {
		
		// Add default fields if not set
		if (!isset($update['date_modified'])) $update['date_modified'] = time();
		if (!isset($update['date_created']) && !$id) $update['date_created'] = time();
		
		// Load the existing data and merge the changes
		if ($id) {
			if ($row = DB::table('categories')->select('*')->where('id', $id)->get()->first()) {
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
			'parent' => intval($update['parent']),
			'title' => (String)$update['title'],
			'data' => gzdeflate(json_encode($update)),
			'date_created' => $update['date_created'],
			'date_modified' => $update['date_modified']
		];
		
		// Insert/update into the db
		if ($id) {
			DB::table('categories')->where('id', $id)->update($update_db);
		}
		else {
			$id = DB::table('categories')->insertGetId($update_db);
		}
		
		// Return the ad id
		return $id;
	}
	
	
	/**
	* Get a category object by its id
	*/
	public static function get_category_by_id($id) {
		if (self::$category_cache === null) self::get_all_categories();
		return isset(self::$category_cache[$id]) ? self::$category_cache[$id] : false;
	}
	
	
	/**
	* Get all categories
	*/
	public static function get_all_categories() {
		if (self::$category_cache === null) {
			self::$category_cache = [];
			foreach (DB::table('categories')->select('*')->get() as $row) {
				self::$category_cache[$row->id] = new CategoryObject($row);
			}
			foreach (self::$category_cache as $id => $category) {
				if ($category->val('parent') && isset(self::$category_cache[$category->val('parent')])) {
					$category->parent_category = self::$category_cache[$category->val('parent')];
					self::$category_cache[$category->val('parent')]->child_categories[] = $category;
				}
			}
		}
		return self::$category_cache;
	}
}
