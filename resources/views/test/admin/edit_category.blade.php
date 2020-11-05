@extends('test.layout.frame')

@section('content')
<div class="container">
	<div class="card">
		<div class="card-header">Categorie aanmaken/bewerken</div>
		<div class="card-body">
			
			<form class="form" method="post" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="mode" value="store">
				<div class="form-row">
					<div class="form-group col-md-8">
						<label>Titel</label>
						<input type="text" class="form-control" name="title" placeholder="Naam van de categorie" value="{{ $category ? $category->val('title') : '' }}">
					</div>
					<div class="form-group col-md-4">
						<label>Parent</label>
						<input type="text" class="form-control" name="parent" placeholder="" value="{{ $category ? $category->val('parent') : '' }}">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-8">
						<label>Afbeelding</label>
						<input type="text" class="form-control" name="image" placeholder="Naam van de afbeelding" value="{{ $category ? $category->val('image') : '' }}">
					</div>
					<div class="form-group col-md-4">
						<label>Afbeelding uploaden</label>
						<input type="file" class="form-control" name="uploaded_image">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-12">
						<label>Omschrijving</label>
						<textarea class="form-control" name="description">{{ $category ? $category->val('description') : '' }}</textarea>
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
