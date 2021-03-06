@extends('layouts.app')

@section('slogan')
    @include('slogan.slogan')
@stop

@section('content')
<div class="container hundred-up" style="padding-top: 15px">

<div class="row py-5 sideborder50left text-center">
<div class="col">
    <i class="fa fa-search fa-4x pb-5"></i>
    
    <h2 class="text-center"><strong>VIND</strong>EENOPDRACHT</h2>
</div>    
</div>


  <div class="row py-1 pb-5">
      <div class="col"></div>
      <div class="col-6">
            <form method="POST" action="">
                @csrf
                <div class="form-group row">                
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __ ('Vind een opdracht') }}</label>
                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>                

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-warning">
                            {{ __('Zoeken') }}
                        </button>


                    </div>
                </div>
            </form>
        </div>
      <div class="col"></div>
  </div>  
</div>
@endsection

@section('categories')
    @include('categories.categories')
@stop

@section('howitworks')
    @include('howitworks.howitworks')
@stop

@section('footer')
  @include('footer.footer')
@stop
