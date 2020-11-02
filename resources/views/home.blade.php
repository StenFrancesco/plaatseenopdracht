@extends('layouts.app')

@section('slogan')
    @include('slogan.slogan')
@stop

@section('content')
<div class="container" id="hundred-up">
  <div class="row py-5">
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
