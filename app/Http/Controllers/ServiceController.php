<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ServiceController extends Controller	{

	public function __construct() {
		$this->middleware('auth');
	}

	// Create a new service or update an existing one
	public function edit(Request $request, $id = false) {
		$user = auth()->user();
		
		if ($request->input('mode') == 'store') {
			$update = [
				'name' => $request->input('name'),
				'address' => $request->input('address'),
				'postcode' => $request->input('postcode'),
				'city' => $request->input('city')
			];
			
			if ($id) {
				if ($service_id = \App\Models\Service::update_service($id, $update, $user->id)) {
					return redirect('service/overview');
				}
				else {
					
				}
			}
			else {
				$update['created_by'] = $user->id;
				if ($service_id = \App\Models\Service::create_service($update, $user->id)) {
					return redirect('service/overview');
				}
				else {
					
				}
			}
		}
		
		$service = false;
		if ($id) $service = \App\Models\Service::get_service_by_id($id);
		
		return view('test/service/edit', [
			'service' => $service
		]);
	}
	
	
	// View a service
	public function view(Request $request, $id = false) {
		$user = auth()->user();
		
		if ($request->input('mode') == 'add_service_owner') {
			$update = \App\Models\Service::add_owner_to_service($id, $request->input('user'), $request->input('rights'), $user->id);
		}
		if ($request->input('mode') == 'remove_service_owner') {
			$update = \App\Models\Service::remove_owner_from_service($id, $request->input('user'), $user->id);
		}
		
		$service = false;
		if ($id) $service = \App\Models\Service::get_service_by_id($id);
		
		return view('test/service/view', [
			'service' => $service,
			'users' => \App\Models\User::get()
		]);
	}
	
	
	// List all the services
	public function overview(Request $request) {
		
		return view('test/service/overview', [
			'services' => \App\Models\Service::get_all_services()
		]);
	}
}
?>