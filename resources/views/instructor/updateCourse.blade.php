@extends('instructor.layout')

@section("title","Editar curso")

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
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('instructor-show-course',$id=$course->id)}}">{{ $course->title }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('conteudo')
    @include('partials.formCourse',['formTitle' => 'Editar curso','formAction' => route('instructor-update-course',$id=$course->id), 'formMethod' => 'put','submitText' => 'Salvar Alterações','categories'=>$categories, 'cursoUpdate' => $course])
@endsection