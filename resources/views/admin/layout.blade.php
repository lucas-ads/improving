@extends('layouts.base')

@section("brand-link",route('admin-home'))

@section('menu-options')
    <li class="nav-item">
        <a href="{{route('admin-home')}}" class="btn btn-primary @yield('usersItemMenu')">Usuários</a>
    </li>
@endsection

@section("barra-busca-class","d-none")

@section("placeholder-search","Buscar Usuário")