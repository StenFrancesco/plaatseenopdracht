<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class AdminController extends Controller	{

	public function __construct() {
		$this->middleware('auth');
	}
	
	public function categories() {
		
		$categories = \App\Models\Category::get_all_categories();
		
		return view('test/admin/categories', [
			'categories' => $categories
		]);
	}
	
	public function edit_category(Request $request, $id = false) {
		
		if ($request->input('mode') == 'store') {
			$update = [
				'title' => $request->input('title'),
				'parent' => $request->input('parent'),
				'image' => $request->input('image'),
				'description' => $request->input('description')
			];
			
			if (!empty($_FILES['uploaded_image']['tmp_name'])) {
				$uid = \Illuminate\Support\Str::random(32);
				$ext = explode('.', $_FILES['uploaded_image']['name']);
				$ext = strtolower(end($ext));
				\App\Classes\BzgS3::put($uid . '.' . $ext, file_get_contents($_FILES['uploaded_image']['tmp_name']));
				$update['image'] = $uid . '.' . $ext;
			}
			
			if ($id) {
				if ($category_id = \App\Models\Category::update_category($id, $update)) {
					return redirect('admin/categories');
				}
				else {
					
				}
			}
			else {
				if ($category_id = \App\Models\Category::create_category($update)) {
					return redirect('admin/categories');
				}
				else {
					
				}
			}
		}
		
		$category = false;
		if ($id) $category = \App\Models\Category::get_category_by_id($id);
		
		return view('test/admin/edit_category', [
			'category' => $category
		]);
	}
}
?>