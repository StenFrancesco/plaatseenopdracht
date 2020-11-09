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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(window).scroll(function(){
            $('nav, .navigationlinks, .subtext, .navbar').toggleClass('scrolled', $(this).scrollTop() >= 100);            
        });

    </script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-xl sticky-top navbar-dark py-5 px-5">
            <a class="navbar-brand py-5" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item px-4">
                        <a class="nav-link navilinks" href="#">
                            <p><h3>Hoe werkt het</h3></p>
                            <p class="d-none d-sm-none d-md-block subtext">Maak kennis met plaatseenopdracht</p>
                        </a>
                    </li>
                    <li class="nav-item px-4">
                        <a class="nav-link navilinks" href="#"><p><h3>Advertentie plaatsen</h3></p><p class="d-none d-sm-none d-md-block subtext">Plaats direct een opdracht</p></a>
                    </li>
                    <li class="nav-item px-4">
                        <a class="nav-link navilinks" href="#"><p><h3>Over plaatseenopdracht</h3></p><p class="d-none d-sm-none d-md-block subtext">Voor als je meer wilt weten</p></a>
                    </li>
                    <li class="nav-item px-4">
                        <a class="nav-link navilinks" href="#"><p><h3>Contact opnemen</h3></p><p class="d-none d-sm-none d-md-block subtext">Wil je contact opnemen? Klik hier</p></a>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto py-5">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item px-4">
                            <a class="nav-link" href="{{ route('login') }}" style="color: white; font-weight: bold; font-size: 15px">{{ __('Inloggen') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item px-4">
                                <a class="nav-link" href="{{ route('register') }}" style="color: white; font-weight: bold; font-size: 15px">{{ __('Registreren') }}</a>
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
        </nav>

        <main>
            @yield('slogan')
            @yield('content')
            @yield('footer')
        </main>    
    </div>
    
</body>
</html>
