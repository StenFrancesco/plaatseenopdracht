<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class Design2Controller extends Controller {

	public function __construct() {
		
	}
	
	public function ads(Request $request) {
		
		$ads = [
			[
				'title' => 'Ervaren PHP programmeur',
				'tags' => ['PHP', 'Laravel', 'Vue', 'HTML', 'CSS'],
				'location' => 'Heiloo (3 km)',
				'created' => '20 okt 2020, 9:12',
				'replies' => 3
			],
			[
				'title' => 'Ervaren PHP programmeur',
				'tags' => ['PHP', 'Laravel', 'Vue', 'HTML', 'CSS'],
				'location' => 'Heiloo (3 km)',
				'created' => '20 okt 2020, 9:12',
				'replies' => 3
			],
			[
				'title' => 'Ervaren PHP programmeur',
				'tags' => ['PHP', 'Laravel', 'Vue', 'HTML', 'CSS'],
				'location' => 'Heiloo (3 km)',
				'created' => '20 okt 2020, 9:12',
				'replies' => 3
			]
		];
		
		$categories = \App\Models\Category::get_all_categories();
		$selected_category = $request->input('category') ? \App\Models\Category::get_category_by_id($request->input('category')) : false;
		$ads = \App\Models\Ad::get_ads([
			'categories' => $selected_category ? [$selected_category->id] : false
		]);
		
		return view('test/design/ads', [
			'ads' => $ads,
			'categories' => $categories,
			'selected_category' => $selected_category
		]);
	}
}
?>