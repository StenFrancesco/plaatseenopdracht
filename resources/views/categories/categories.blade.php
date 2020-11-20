@extends('layouts.app')

@section('categories')
<div class="container-fluid py-5">
  <div class="row">
    <div class="col-md-10 offset-md-1">
      <div class="row grid">
      @foreach ($categories as $category)
        <div class="col-md-3">
		<figure class="effect-sarah">
          <img src="images/13.jpg" alt="img13"/>
          <figcaption>          
            <h2>{{ $category->val('parent') }}</h2>
            <p>{{ $category->val('title') }}</p>
            <a href="#">View more</a>
          </figcaption>		          
        </figure>
		</div>
        @endforeach
        <figure class="effect-sarah">
          <img src="images/13.jpg" alt="img13"/>
          <figcaption>
            <h2>Free <span>Sarah</span></h2>
            <p>Sarah likes to watch clouds. She's quite depressed.</p>
            <a href="#">View more</a>
          </figcaption>			
        </figure>
        <figure class="effect-sarah">
          <img src="images/13.jpg" alt="img13"/>
          <figcaption>
            <h2>Free <span>Sarah</span></h2>
            <p>Sarah likes to watch clouds. She's quite depressed.</p>
            <a href="#">View more</a>
          </figcaption>			
        </figure>
      </div>
    </div>
  </div>
</div>
@endsection


