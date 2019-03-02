@extends('instructor.showCourseLayout')

@section("title",$course->title)

@section('css')
    @parent

    <style>
        div.info{
            font-size: 1.00em;
        }

        div.info p{
            width: 60%;
            margin-bottom: 0;
            margin-top: 0;
        }

        div.info p:first-child{
            width: 40%;
            text-align: right;
            font-weight: bold;
            padding-right: 15px;
        }

        div.info .status-info{
            padding: 0 10px 0 10px;
        }
    </style>
@endsection

@section('breadcrumb')
    <div id="breadcrumb-div">
        <div class="container mt-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('instructor-home')}}">Meus Cursos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $course->title }}</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('info-course','active')

@section('conteudoCurso')
    <div class="container info mt-5 mb-5">
        <div class="row">
            <p>Código:</p> <p>{{ $course->id }}</p>
        </div>
        <hr class="d-block">
        <div class="row">
            <p>Status:</p> 
            <p>
                {{ $course->getStatusLegend() }}
                <button type="button" class="btn btn-md text-primary bg-light status-info" data-toggle="popover" title="{{ $course->getStatusLegend() }}" data-content="{{ $course->getStatusDescription() }}"><i class="fas fa-question-circle"></i></button>
            </p>
        </div>
        <hr class="d-block">
        <div class="row">
            <p>Carga Horária:</p> <p>{{ $course->workload }} horas</p>
        </div>
        <hr class="d-block">
        <div class="row">
            <p>Palavras-chave:</p> <p>{{ $course->getKeywordsFormated() }}.</p>
        </div>
        <hr class="d-block">
        <div class="row">
            <p>Instrutor:</p> <p>{{ $course->user->name }}</p>
        </div>
        <hr class="d-block">
        <div class="row">
            <p>Criado em:</p> <p>{{ $course->created_at->format('d/m/Y') }}</p>
        </div>
        <hr class="d-block">
        <div class="row">
            <p>Úlima Edição:</p> <p>{{ $course->updated_at->format('d/m/Y') }}</p>
        </div>
        <hr class="d-block">
        <div class="row">
            <p>Publicado em:</p> <p>{{ $course->getPublicationDate() }}</p>
        </div>
        <hr class="d-block">
        <div class="row">

        </div>
    </div>
@endsection

@section('js')
    @parent

    <script type="text/javascript">
        $(function () {
            $('.status-info').popover({
                container: 'body'
            });
        });

        $('.status-info').popover({
            trigger: 'focus'
        })
    </script>
@endsection