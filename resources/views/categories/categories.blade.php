@extends('layouts.app')

@section('categories')
<div class="container-fluid py-5">
  <div class="row">
    <div class="col-md-10 offset-md-1">
      <div class="row grid">
      @foreach ($categories as $category)
        <div class="col-md-3">
		      <figure class="effect-sarah">
            <img src="{{ $category->image() }}" alt="categoryimage" />
            <figcaption>          
              <h2 class="fat">{{ $category->val('title') }}</h2>
              <p>{{ $category->val('parent') }}</p>
              <a href="#">View more</a>
            </figcaption>		          
            </figure>
		      </div>
        @endforeach        
      </div>
    </div>
  </div>
</div>
@endsection


