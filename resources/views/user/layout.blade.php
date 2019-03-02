@extends('layouts.base')

@section("brand-link",route('user-home'))

@section('menu-options')
    <li class="nav-item">
        <a href="{{ route('user-home') }}" class="btn btn-primary @yield('homeMenu')">Página Inicial</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('user-show-courses') }}" class="btn btn-primary @yield('myCoursesMenu')">Meus Cursos</a>
    </li>
@endsection

@section("placeholder-search","Buscar Curso")

@section("barra-busca-class","d-none")