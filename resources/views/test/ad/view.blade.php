@extends('test.layout.frame')

@section('content')
<div class="container">
	<div class="card">
		<div class="card-header">
			<a href="/ad/edit/{{ $ad->id }}" class="btn btn-primary btn-sm float-right">Bewerken</a>
			Advertentie bekijken
		</div>
		<div class="card-body">
			
			<h2>{{ $ad->val('title') }}</h2>
			<p>{{ $ad->val('description') }}</p>
			<p>
				{{ $ad->val('address') }}<br>
				{{ $ad->val('postcode') }} {{ $ad->val('city') }}
			</p>
			
			<h3>Reacties op advertentie</h3>
			<p>Diensten kunnen een reactie/offerte plaatsen bij de advertenties. Dit zijn losse objecten die gekoppeld zijn aan de advertentie.</p>
			
        </div>
    </div>
</div>
@endsection
