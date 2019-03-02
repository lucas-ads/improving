<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Improving</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('images/logoazul.png') }}" >
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    @yield('css')
</head>

@php
    $route = Route::currentRouteName();
    $routename="";
    $routetext="";

    if(($user->isOnlyInstructor() || $user->isOnlyAdmin()) && $route != 'user-home'){
        $routename="user-home";
        $routetext="Área do Usuário";
    }else{
        if($user->isOnlyInstructor() && $route == 'user-home'){
            $routename="instructor-home";
            $routetext="Área do Instrutor";
        }else{
            if($user->isOnlyAdmin() && $route == 'user-home'){
                $routename="admin-home";
                $routetext="Área do Administrador";
            }else{
                if($user->isInstructor() && $user->isAdmin()){
                    $routename="ambiente";
                    $routetext="Mudar de Ambiente";
                }
            }
        }
    }
@endphp

<body>
    <header id="cabecalho">
        <div class="container">
            <nav class="navbar navbar-expand-md row">
                <a href='@yield("brand-link")' class="navbar-brand white titulo">
                    <img src="{{ asset('images/logo.png') }}" class="d-inline" alt="Logo da Improving">
                    <h1 class="d-inline">Improving</h1>
                </a>

                <button class="navbar-toggler white" type="button" data-toggle="collapse" data-target="#menu-sanduiche" aria-controls="menu-sanduiche" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fas fa-bars"></span>
                </button>

                <div class="collapse navbar-collapse" id="menu-sanduiche">
                    <hr class="my-1 d-block d-md-none">
                    <ul class="navbar-nav ml-auto">
                        @yield('menu-options')
                       
                        @if(!$user->isOnlyUser())
                            <li class="nav-item d-inline d-md-none">
                                <a href="{{ route($routename) }}" class="btn btn-primary">{{ $routetext }}</a>
                            </li>
                        @endif
                        
                        <li class="nav-item d-inline d-md-none">
                            <a href="{{route('user-profile')}}" class="btn btn-primary">Meu Perfil</a>
                        </li>
                        <li class="nav-item d-inline d-md-none">
                            <form id="logout-form" action="{{ route('logout') }}" class="inline" method="POST">
                                @csrf
                                <a href="#" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a>
                            </form>
                        </li>
                    </ul>
                </div>

                <button class="form-toggler white bmd-collapse-inline d-md-none @yield('barra-busca-class')" type="button" data-toggle="collapse" data-target="#form-busca" aria-controls="form-busca" aria-expanded="false"><i class="fas fa-search"></i></button>

                <div class="dropdown" id="dropdown-profile">
                    <button class="btn btn-light dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span>
                            <img src="{{ $user->getUrlImageOrWhite() }}" class="">
                        </span>
                    </button>
                    <div id="profile-options" class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="{{route('user-profile')}}"><i class="fas fa-user"></i>Meu Perfil</a>

                        @if(!$user->isOnlyUser())
                            <a class="dropdown-item" href="{{ route($routename) }}"><i class="fas fa-exchange-alt"></i>{{ $routetext }}</a>
                        @endif

                        <div class="dropdown-divider"></div>
                        <form id="logout-form" action="{{ route('logout') }}" class="inline" method="POST">
                            @csrf
                            <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-power-off"></i>Sair
                            </a>
                        </form>
                    </div>
                </div>
            </nav>
        </div>
    </header>
<div id="barra-busca" class="@yield('barra-busca-class')">
        <div class="container">
            <form id="form-busca" class="row bmd-form-group bmd-collapse-inline d-md-inline-block collapse" method="GET" action="@yield('actionform')">
                {{ csrf_field() }}
                <div class="input-group my-2 ml-auto">
                    <input type="text" class="form-control" id="field-search" placeholder="@yield('placeholder-search')">
                    <div class="input-group-append">
                        <button id="btn-search" class="btn btn-primary" type="button">
                        <span class="d-none d-sm-inline">Buscar</span><i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form> 
        </div>
    </div>

    @yield('breadcrumb')

    <div id="conteudo">
        @yield('conteudo')
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.4/js/tether.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/base.js') }}"></script>
    @yield('js')
</body>
</html>