@extends('test.layout.frame')

@section('content')
<div class="container">
	<div class="card">
		<div class="card-header">Overzicht diensten</div>
		<div class="card-body">
			
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Naam</th>
						<th>Adres</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($services as $service)
						<tr>
							<td><a href="/service/view/{{ $service->id }}">{{ $service->id }}</a></td>
							<td><a href="/service/view/{{ $service->id }}">{{ $service->val('name') }}</a></td>
							<td><a href="/service/view/{{ $service->id }}">{{ $service->val('address') }}</a></td>
						</tr>
					@endforeach
				</tbody>
			</table>
			
        </div>
    </div>
</div>
@endsection
