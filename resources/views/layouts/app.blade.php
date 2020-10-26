<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
    
        
    
    </style>
    
</head>
<body>
    <div id="app" style="background-image: url('/images/caspar-camille-rubin-7SDoly3FV_0-unsplash.jpg'); background-repeat: no-repeat">
        <div class="container">
            <nav class="navbar navbar-expand-md sticky-top navbar-dark">
                <div class="container my-5">
                    <a class="navbar-brand pr-3" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item px-4">
                                <a class="nav-link" href="#" style="color: white; font-weight: bold; font-size: 15px"><p>HOE WERKT HET</p><p>Maak kennis met plaatseenopdracht</p></a>
                            </li>
                            <li class="nav-item px-4">
                                <a class="nav-link" href="#" style="color: white; font-weight: bold; font-size: 15px"><p>ADVERTENTIE PLAATSEN</p><p>Plaats direct een opdracht</p></a>
                            </li>
                            <li class="nav-item px-4">
                                <a class="nav-link" href="#" style="color: white; font-weight: bold; font-size: 15px"><p>OVER PLAATSJEOPDRACHT</p><p>Voor als je meer wilt weten</p></a>
                            </li>
                            <li class="nav-item px-4">
                                <a class="nav-link" href="#" style="color: white; font-weight: bold; font-size: 15px"><p>CONTACT OPNEMEN</p><p>Wil je contact opnemen? Klik hier</p></a>
                            </li>
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto pl-5">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item px-4">
                                    <a class="nav-link" href="{{ route('login') }}" style="color: white; font-weight: bold; font-size: 15px" id="loginnav">{{ __('LOGIN') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item px-4">
                                        <a class="nav-link" href="{{ route('register') }}" style="color: white; font-weight: bold; font-size: 15px" id="loginnav">{{ __('REGISTER') }}</a>
                                    </li>
                                @endif
                            @else
                            
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <!-- {{ Auth::user()->name }} -->
                                        <img src="https://mdbootstrap.com/img/Photos/Avatars/avatar-2.jpg" class="rounded-circle z-depth-0" alt="avatar image">
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
            <br />
            <br />
            <br />
            <h1 style="color: white">PLAATS EEN OPDRACHT SLOGAN</h1>
            <br />
            <br />
            <br />
            <br />
            <br />
            

            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
