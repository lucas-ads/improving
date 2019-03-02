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
    @yield('css')
</head>
<body>
    <header id="cabecalho">
        <div class="container">
            <nav class="navbar navbar-expand-md row">
                <a href='@yield("brand-link")' class="navbar-brand white titulo">
                    <img src="{{ asset('images/logo.png') }}" class="d-inline" alt="Logo da Improving">
                    <h1 class="d-inline">Improving</h1>
                </a>
            </nav>
        </div>
    </header>
    
    <div id="conteudo">
        @yield('conteudo')
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.4/js/tether.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>