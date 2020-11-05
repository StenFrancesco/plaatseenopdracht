<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>{{ config('app.name', 'Laravel') }}</title>
		<link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
		<link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
		<script src="{{ asset('js/jquery.min.js') }}"></script>
		<script src="{{ asset('js/bootstrap.min.js') }}"></script>
		<style>
			.header-image {
				float: left;
				height: 40px;
				margin-top: 10px;
				margin-right: 15px;
			}
			
			h2 {
				font-size: 20px;
				margin-top: 30px;
			}
			
			h2:first-child {
				margin-top: 0;
			}
			
			.search-input {
				margin-bottom: 15px;
			}
			
			.ad-row {
				border-bottom: 1px solid #ccc;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="/design/ads">Home</a></li>
					@if ($selected_category)
						@foreach (array_reverse($selected_category->get_parents()) as $parent_category)
							<li class="breadcrumb-item"><a href="/design/ads?category={{ $parent_category->id }}">{{ $parent_category->val('title') }}</a></li>
						@endforeach
						<li class="breadcrumb-item active" aria-current="page">{{ $selected_category->val('title') }}</li>
					@endif
				</ol>
			</nav>
			
			<div class="header">
				@if ($selected_category)
					@if ($image_url = $selected_category->image())
						<img src="{{ $image_url }}" class="header-image">
					@endif
					<h1>{{ $selected_category->val('title') }}</h1>
					<p>{{ $selected_category->val('description', 'Geen omschrijving ingevuld') }}</p>
				@else
					<h1>Home tekst</h1>
					<p>Stukje tekst</p>
				@endif
			</div>
			
			<div class="row">
				<div class="col-md-2">
					
					<h2>Subcategorie</h2>
					<ul>
						@if ($selected_category)
							@foreach ($selected_category->get_children() as $id => $child_category)
								<li><a href="/design/ads?category={{ $child_category->id }}">{{ $child_category->val('title') }}</a></li>
							@endforeach
						@else
							@foreach ($categories as $category)
								@if (!$category->parent_category)
									<li><a href="/design/ads?category={{ $category->id }}">{{ $category->val('title') }}</a></li>
								@endif
							@endforeach
						@endif
					</ul>
					
					<h2>Afstand</h2>
					<?php foreach ([5000 => 'Binnen 5 km', 10000 => 'Binnen 10 km', 50000 => 'Binnen 50 km', 100000 => 'Binnen 100 km'] as $key => $title) { ?>
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="{{ $key }}" name="afstand" id="afstand_{{ $key }}">
							<label class="form-check-label" for="afstand_{{ $key }}">
								{{ $title }}
							</label>
						</div>
					<?php } ?>
					
				</div>
				<div class="col-md-10">
					<input type="text" placeholder="Zoek opdrachten..." class="form-control search-input">
					
					<?php foreach ($ads as $ad) { ?>
						<div class="row">
							<div class="col-md-6">
								<h3>{{ $ad->val('title') }}</h3>
								<div class="tags">
									Tags: 
									<?php foreach ($ad->get_categories() as $i => $category) { ?>
										<?= $i ? ' - ' : '' ?><a href="#">{{ $category->val('title') }}</a>
									<?php } ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-6">
										<i class="fas fa-map-marker-alt"></i> <?= $ad->val('city') ?>
									</div>
									<div class="col-md-6">
										<i class="fas fa-users"></i> X reacties
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">Geplaatst op: <?= date('D M Y, H:i', $ad->val('date_created')) ?></div>
								</div>
							</div>
						</div>
					<?php } ?>
					
				</div>
			</div>
			
		</div>
	</body>
</html>
