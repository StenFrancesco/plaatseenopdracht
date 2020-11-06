@extends('test.layout.frame')

@section('content')
<div class="container">
	<div class="card">
		<div class="card-header">Opdracht/advertantie aanmaken</div>
		<div class="card-body">
			
			<form class="form" method="post">
				@csrf
				<input type="hidden" name="mode" value="store">
				<div class="form-row">
					<div class="form-group col-md-8">
						<label>Naam van de opdracht</label>
						<input type="text" class="form-control" name="title" placeholder="Naam van de opdracht" value="{{ $ad ? $ad->val('title') : '' }}">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-4">
						<label>Categorieen</label>
						<div class="typeahead-box">
							<div class="input-group">
								<input type="text" class="form-control category-widget" name="categories" key="category_ids" placeholder="Ids van de categorieen" value="{{ $ad && $ad->val('categories') ? implode(',', $ad->val('categories')) : '' }}" target="#checkboxes-row_id" filter-mode="buttons">
							</div>
						</div>
					</div>
					<div class="form-group col-md-8">
						<label>&nbsp;</label>
						<div id="checkboxes-row_id" class="checkboxes-result-box"></div>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-12">
						<label>Omschrijving van de opdracht</label>
						<textarea rows="5" class="form-control" name="description" placeholder="Omschrijving van de opdracht">{{ $ad ? $ad->val('description') : '' }}</textarea>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-6">
						<label>Adres</label>
						<input type="text" class="form-control" name="address" placeholder="Adres + huisnummer" value="{{ $ad ? $ad->val('address') : '' }}">
					</div>
					<div class="form-group col-md-2">
						<label>Postcode</label>
						<input type="text" class="form-control" name="postcode" placeholder="Postcode" value="{{ $ad ? $ad->val('postcode') : '' }}">
					</div>
					<div class="form-group col-md-4">
						<label>Plaats</label>
						<input type="text" class="form-control" name="city" placeholder="Plaatsnaam" value="{{ $ad ? $ad->val('city') : '' }}">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-2">
						<label>Budget</label>
						<input type="text" class="form-control" name="budget/budget" placeholder="" value="{{ $ad ? $ad->val('budget/budget') : '' }}">
					</div>
					<div class="form-group col-md-2">
						<label>Deadline</label>
						<input type="text" class="form-control" name="deadline" placeholder="YYYY-MM-DD" value="{{ $ad ? $ad->val('deadline') : '' }}">
					</div>
					<div class="form-group col-md-2">
						<label>Start datum</label>
						<input type="text" class="form-control" name="date_start" placeholder="YYYY-MM-DD" value="{{ $ad ? $ad->val('date_start') : '' }}">
					</div>
				</div>
				
				<div class="form-row">
					<div class="form-group col-md-12">
						<input type="file"  accept="image/*" name="image" id="file" multiple onchange="loadFile(event)" style="display: none;">
						<label for="file" class="btn btn-info"><i class="fas fa-upload"></i> Upload Afbeeldingen</label>
						<div style="height: 160px;" id="uploaded_images">
							@foreach ($ad->images() as $image) 
								<div class="image-block">
									<i class="fas fa-times remove-image-block"></i>
									<input type="hidden" name="existing_image[]" value="{{ $image->id }}">
									<img src="{{ $image->url() }}" style="height: 160px;">
								</div>
							@endforeach
						</div>

<script>
var loadFile = function(event) {
	for (var i = 0; i < event.target.files.length; i++) {
		$('#uploaded_images').append(draw_image_block(event.target.files[i]));
	}
};

function draw_image_block(file) {
	var node = $('<div class="image-block"><i class="fas fa-times remove-image-block"></i><input type="hidden" name="file_data[]"><input type="hidden" name="file_name[]"><img src="' + URL.createObjectURL(file) + '" style="height: 160px;"></div>');
	node.find('[name^=file_name]').val(file.name);
	var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function() {
		node.find('[name^=file_data]').val(reader.result.substr(reader.result.indexOf(',') + 1));
	}
	node.find('.remove-image-block').click(function() {
		node.remove();
	});
	return node;
}

$(window).ready(function() {
	$('.remove-image-block').click(function() {
		$(this).closest('.image-block').remove();
	});
});
</script>
				
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
