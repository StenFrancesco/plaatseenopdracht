@extends('test.layout.frame')

@section('content')
<div class="container">
	<div class="card">
		<div class="card-header">
			<a href="/admin/edit_category" class="btn btn-primary btn-sm float-right">Nieuwe categorie</a>
			Overzicht categorieen
		</div>
		<div class="card-body">
			
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Parent</th>
						<th>Title</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($categories as $category)
						<tr>
							<td><a href="/admin/edit_category/{{ $category->id }}">{{ $category->id }}</a></td>
							<td><a href="/admin/edit_category/{{ $category->id }}">{{ $category->val('parent') }}</a></td>
							<td><a href="/admin/edit_category/{{ $category->id }}">{{ $category->val('title') }}</a></td>
						</tr>
					@endforeach
				</tbody>
			</table>
			
        </div>
    </div>
</div>
@endsection
