@extends('user.layout')

@php
use App\Subscription;
@endphp

@section("title","Meus Cursos")

@section('css')
    <style>
        #table-courses td{
            vertical-align: middle;
        }

        #table-courses td:first-child{
            width: 70px;
        }

        #table-courses td:not(:first-child) a{
            width: 100%;
            display: inline-block;
        }

        #table-courses .status-Inativo td:first-child img{
            filter: grayscale(100%);
        }

        #table-courses .status-Inativo .courseLink{
            pointer-events: none;
            cursor: default;
        }
        
        #table-courses .status-Inativo .courseLink.arrow{
            display: none;
        }

        .text-black{
            color: black;
        }
    </style>
@endsection

@section('myCoursesMenu','active')

@section('conteudo')
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1 class="h2">Minhas Matrículas</h1>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 table-responsive">
                <table class="table" id="table-courses">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Título</th>
                            <th class="text-center d-none d-md-table-cell">Carga Horária</th>
                            <th class="text-center">Progresso</th>
                            <th class="text-center d-none d-md-table-cell">Média</th>
                            <th class="text-center">Certificado</th>
                            <th class=""></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subscriptions as $subscription)
                            <tr class="align-items-center status-{{ $subscription->course->getStatusLegend() }}">
                                <td>
                                    <a href="{{ route('user-continue-course',$id=$subscription->course->id) }}" class="courseLink">
                                        <img width="40px" src="{{ $subscription->course->getUrlIcon() }}" alt="">
                                    </a>
                                </td>
                                <td>
                                    <a class="text-black courseLink" href="{{ route('user-continue-course',$id=$subscription->course->id) }}">
                                        {{ $subscription->course->title }}
                                    </a>
                                </td>
                                <td class="text-center d-none d-md-table-cell">{{ $subscription->course->workload }} h<span class="d-none d-md-inline">oras</span></td>
                                <td class="text-center">
                                    <label class="m-0">{{ $subscription->getProgress() }}%</label>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $subscription->getProgress() }}%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <td class="text-center d-none d-md-table-cell">
                                    @php
                                        $media = $subscription->getPoints();
                                    @endphp
                                    <label class="m-0">{{ substr($media, 0, 4) }}</label>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{ is_numeric($media)?($media*10)."%":$media }}" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <td class="text-center p-0">
                                    @if($subscription->completed_at != null)
                                        <a href="{{ route('user-download-certificate',$id=$subscription->course->id) }}" target="_blank" class="btn btn-lg py-0" style="font-size: 2em;"><i class="fas fa-award"></i></a>
                                    @else
                                        --
                                    @endif
                                </td>
                                <td class=""><a class="courseLink arrow" href="{{ route('user-continue-course',$id=$subscription->course->id) }}"><i class="fas fa-arrow-right"></i></a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">Você não está matriculado em nenhum curso. <a href="{{route('user-home')}}">Clique aqui</a> para ver nosso catálogo!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection