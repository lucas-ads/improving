@extends('layouts.simplebase')

@section("title","Selecionar Ambiente")

@section('css')
    <link rel="stylesheet" href="{{ asset('css/ambiente.css') }}">
@endsection

@section("brand-link",route('ambiente'))

@php
    $cols=6;

    if($user->isInstructor() && $user->isAdmin()){
        $cols=4;
    }
@endphp

@section('conteudo')
    <div class="container">
        <div class="row titulo">
            <h1 class="col-12">Acessar como:</h1>
        </div>
        <div class="row options">
            <div class="col-12 col-sm-{{$cols}}">
                <a href="{{route('user-home')}}" class="jumbotron">
                    <span><i class="fas fa-user"></i></span>
                    <h2 class="d-inline d-sm-block">Usuário</h2>
                </a>
            </div>
            @if($user->isInstructor())
                <div class="col-12 col-sm-{{$cols}}">
                    <a href="{{route('instructor-home')}}" class="jumbotron">
                        <span><i class="fas fa-chalkboard-teacher"></i></span>
                        <h2 class="d-inline d-sm-block">Instrutor</h2>
                    </a>
                </div>
            @endif
            @if($user->isAdmin())
                <div class="col-12 col-sm-{{$cols}}">
                    <a href="{{route('admin-home')}}" class="jumbotron">
                        <span><i class="fas fa-user-tie"></i></span>
                        <h2 class="d-inline d-sm-block">Admin<span class="d-inline d-sm-none d-md-inline">istrador</span></h2>
                    </a>
                </div>
            @endif
        </div>
    </div>    
@endsection