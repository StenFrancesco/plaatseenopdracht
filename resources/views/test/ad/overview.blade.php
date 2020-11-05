@extends('test.layout.frame')

@section('content')
<div class="container">
	<div class="card">
		<div class="card-header">Overzicht advertenties</div>
		<div class="card-body">
			
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Titel</th>
						<th>Adres</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($ads as $ad)
						<tr>
							<td><a href="/ad/view/{{ $ad->id }}">{{ $ad->id }}</a></td>
							<td><a href="/ad/view/{{ $ad->id }}">{{ $ad->val('title') }}</a></td>
							<td><a href="/ad/view/{{ $ad->id }}">{{ $ad->val('address') }}</a></td>
						</tr>
					@endforeach
				</tbody>
			</table>
			
        </div>
    </div>
</div>
@endsection
