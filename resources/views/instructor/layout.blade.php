@extends('layouts.base')

@section("brand-link",route('instructor-home'))

@section('menu-options')
    <li class="nav-item">
        <a href="{{ route("instructor-home") }}" class="btn btn-primary @yield('myCoursesMenu')">Meus Cursos</a>
    </li>
@endsection

@section("barra-busca-class","d-none")