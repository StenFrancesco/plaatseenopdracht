<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class CategoryController extends Controller	{

	public function __construct() {
		
	}
	
	// Get all categories for the bloodhound/typeahead
	public function json_categories() {
		
		$list = [];
		foreach (\App\Models\Category::get_all_categories() as $category) {
			$list[] = [
				'id' => $category->id,
				'title' => $category->full_path(),
				'short_title' => $category->val('title'),
				'background_color' => 'hsl(' . ($category->id * 53 % 360) . ', 46%, 50%)',
				'border_color' => 'hsl(' . ($category->id * 53 % 360) . ', 46%, 30%)'
			];
		}	
		
		return response()->json(['values' => $list]);
	}
}
?>