@extends('test.layout.frame')

@section('content')
<div class="container">
	<div class="card">
		<div class="card-header">Dienst aanmaken</div>
		<div class="card-body">
			
			<form class="form" method="post">
				@csrf
				<input type="hidden" name="mode" value="store">
				<div class="form-row">
					<div class="form-group col-md-12">
						<label>Bedrijfsnaam</label>
						<input type="text" class="form-control" name="name" placeholder="Naam van de dienst" value="{{ $service ? $service->val('name') : '' }}">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-6">
						<label>Adres</label>
						<input type="text" class="form-control" name="address" placeholder="Adres + huisnummer" value="{{ $service ? $service->val('address') : '' }}">
					</div>
					<div class="form-group col-md-2">
						<label>Postcode</label>
						<input type="text" class="form-control" name="postcode" placeholder="Postcode" value="{{ $service ? $service->val('postcode') : '' }}">
					</div>
					<div class="form-group col-md-4">
						<label>Plaats</label>
						<input type="text" class="form-control" name="city" placeholder="Plaatsnaam" value="{{ $service ? $service->val('city') : '' }}">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-3 offset-md-9">
						<input type="submit" value="Opslaan" class="btn btn-primary btn-block">
					</div>
				</div>
			</form>
			
        </div>
    </div>
</div>
@endsection
