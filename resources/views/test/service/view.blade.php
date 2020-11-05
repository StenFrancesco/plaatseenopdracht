@extends('test.layout.frame')

@section('content')
<div class="container">
	<div class="card">
		<div class="card-header">
			<a href="/service/edit/{{ $service->id }}" class="btn btn-primary btn-sm float-right">Bewerken</a>
			Dienst bekijken
		</div>
		<div class="card-body">
			
			<h2>{{ $service->val('name') }}</h2>
			<p>
				{{ $service->val('address') }}<br>
				{{ $service->val('postcode') }} {{ $service->val('city') }}
			</p>
			
			<h3>Eigenaars van deze dienst</h3>
			<p>Meerdere gebruikers kunnen eigenaar zijn van één dienst. Een bedrijf kan meerdere medewerkers hebben die kunnen reageren op opdrachten. Hier moet wel een opsplitsing komen in de rechten zodat niet iedereen alle gegevens kan aanpassen/bekijken.</p>
			<table class="table">
				<thead>
					<tr>
						<th>Id</th>
						<th>Naam</th>
						<th>Email</th>
						<th>Rechten</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach ($service->owners() as $owner)
						<tr>
							<td>{{ $owner['user']->id }}</td>
							<td>{{ $owner['user']->name }}</td>
							<td>{{ $owner['user']->email }}</td>
							<td>{{ $owner['rights'] }}</td>
							<td><a href="?mode=remove_service_owner&user={{ $owner['user']->id }}" class="btn btn-secondary btn-block btn-sm">Verwijder</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			</p>
			<h4>Gebruiker koppelen</h4>
			<form class="form" method="post">
				@csrf
				<input type="hidden" name="mode" value="add_service_owner">
				<div class="row">
					<div class="col-md-4">
						<select name="user" class="form-control">
							@foreach ($users as $user)
								<option value="{{ $user->id }}">{{ $user->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-4">
						<select name="rights" class="form-control">
							<option value="view">Alleen weergave</option>
							<option value="edit">Bewerken</option>
							<option value="admin">Admin</option>
						</select>
					</div>
					<div class="col-md-2">
						<input type="submit" value="Toevoegen" class="btn btn-block btn-primary">
					</div>
				</div>
			</form>
			
			<h3>Berichten naar dienst</h3>
			<p>Gebruikers moeten direct een offerteaanvraag kunnen doen bij een bedrijf of extra gegevens aanvragen. Deze berichten zijn alleen zichtbaar voor de dienst en de gebruiker. De berichtobjecten bevatten een "chat" met verschillende berichten en wat algemene informatie.</p>
			
			<h3>Dienst reviews</h3>
			<p>De reviews zijn voor iedereen zichtbaar en gebruiker kunnen een rating meegeven. Diensten hebben de optie om te reageren op reviews.</p>
			
        </div>
    </div>
</div>
@endsection
