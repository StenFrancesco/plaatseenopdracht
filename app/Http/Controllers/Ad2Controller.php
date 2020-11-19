<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class Ad2Controller extends Controller	{

	public function __construct() {
		$this->middleware('auth');
	}

	// Create a new ad or update an existing one
	public function edit(Request $request, $id = false) {
		$user = auth()->user();
		
		if ($request->input('mode') == 'store') {
			$update = [
				'title' => $request->input('title'),
				'categories' => array_filter(array_map('intval', $request->input('category_ids'))),
				'description' => $request->input('description'),
				'address' => $request->input('address'),
				'postcode' => $request->input('postcode'),
				'city' => $request->input('city'),
				'status' => $request->input('status'),
				'deadline' => $request->input('deadline'),
				'date_start' => $request->input('date_start'),
				'images' => [],
				'budget' => [
					'budget' => $request->input('budget/budget')
				]
			];
			
			if ($request->input('existing_image') && is_array($request->input('existing_image'))) {
				foreach ($request->input('existing_image') as $i => $existing_image_id) {
					$update['images'][] = intval($existing_image_id);
				}
			}
			
			if ($request->input('file_name') && is_array($request->input('file_name'))) {
				$file_data = $request->input('file_data');
				foreach ($request->input('file_name') as $i => $file_name) {
					if ($userfile_id = \App\Models\User_file::create_userfile(base64_decode($file_data[$i]), $file_name, $user->id)) {
						$update['images'][] = $userfile_id;
					}
				}
			}
			
			if ($id) {
				if ($ad_id = \App\Models\Ad::update_ad($id, $update, $user->id)) {
					return redirect('ad/overview');
				}
				else {
					
				}
			}
			else {
				$update['user'] = $user->id;
				if ($ad_id = \App\Models\Ad::create_ad($update, $user->id)) {
					return redirect('ad/overview');
				}
				else {
					
				}
			}
		}
		
		$ad = false;
		if ($id) $ad = \App\Models\Ad::get_ad_by_id($id);
		
		return view('test/ad/edit', [
			'ad' => $ad
		]);
	}
	
	
	// View an ad
	public function view(Request $request, $id = false) {
		$user = auth()->user();
		
		$ad = false;
		if ($id) $ad = \App\Models\Ad::get_ad_by_id($id);
		
		return view('test/ad/view', [
			'ad' => $ad,
			'users' => \App\Models\User::get()
		]);
	}
	
	
	// List all the ads
	public function overview(Request $request) {
		
		return view('test/ad/overview', [
			'ads' => \App\Models\Ad::get_all_ads()
		]);
	}
}
?>