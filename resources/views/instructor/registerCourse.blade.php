@extends('instructor.layout')

@section("title","Criar curso")

@section('css')
    <style>
        .custom-file-label{
            overflow: hidden;
        }
    </style>
@endsection

@section('breadcrumb')
    <div id="breadcrumb-div">
        <div class="container mt-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('instructor-home')}}">Meus Cursos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Novo Curso</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('conteudo')
    @include('partials.formCourse',['formTitle' => 'Criar curso','formAction' => route('instructor-create-course'), 'formMethod' => 'post','submitText' => 'Salvar','categories'=>$categories])
@endsection