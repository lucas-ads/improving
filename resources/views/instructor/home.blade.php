@extends('instructor.layout')

@section("title","Instrutor")

@section('css')
    <style>
        #table-courses td{
            vertical-align: middle;
        }

        #table-courses td:first-child{
            width: 70px;
        }

        #table-courses .status{
            text-align: center;
            white-space: nowrap;
        }

        #table-courses td:not(:first-child) a{
            width: 100%;
            display: inline-block;
        }

        #table-courses .status-Inativo td:first-child img{
            filter: grayscale(100%);
        }

        .text-black{
            color: black;
        }

        @media (min-width: 576px) {
            #table-courses .status{
                text-align: left;
            }
        }
    </style>
@endsection

@section('myCoursesMenu','active')

@section('breadcrumb')
    <div id="breadcrumb-div">
        <div class="container mt-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Meus Cursos</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('conteudo')
    <div class="container mt-4">
        <div class="row justify-content-end">
            <a class="btn btn-primary text-white mb-3 mr-3" href="{{route('instructor-form-create-course')}}"><i class="fas fa-plus"></i>  Novo Curso</a>
        </div>
        <div class="table-responsive">
            <table class="table" id="table-courses">
                <thead>
                    <tr>
                        <th></th>
                        <th>Título</th>
                        <th class="text-center d-none d-md-table-cell">Carga Horária</th>
                        <th class="text-center d-none d-md-table-cell">Data de Publicação</th>
                        <th class="status">Status</th>
                        <th class="d-none"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($user->courses as $course)
                        <tr class="align-items-center status-{{ $course->getStatusLegend() }}">
                            <td>
                                <a href="{{ route('instructor-show-course',$id=$course->id) }}">
                                    <img width="40px" src="{{ $course->getUrlIcon() }}" alt="">
                                </a>
                            </td>
                            <td>
                                <a class="text-black" href="{{ route('instructor-show-course',$id=$course->id) }}">
                                    {{ $course->title }}
                                </a>
                            </td>
                            <td class="text-center d-none d-md-table-cell">{{ $course->workload }} h<span class="d-none d-md-inline">oras</span></td>
                            <td class="text-center d-none d-md-table-cell">{{ $course->getPublicationDate() }}</td>
                            <td class="status"><i class="fas fa-circle {{ $course->getStatusColor() }}"></i> <span class="d-none d-sm-inline">{{ $course->getStatusLegend() }}</span></td>
                            <td class="d-none d-sm-table-cell"><a href="{{ route('instructor-show-course',$id=$course->id) }}"><i class="fas fa-arrow-right"></i></a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Não há cursos registrados em seu nome.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="row justify-content-end">
            <a class="btn btn-primary text-white mb-3 mr-3" href="{{route('instructor-form-create-course')}}"><i class="fas fa-plus"></i>  Novo Curso</a>
        </div>        
    </div>
@endsection