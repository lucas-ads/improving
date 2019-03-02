@extends('user.layout')

@php
use App\Subscription;
@endphp

@section("title","Home")

@section('css')
    @parent
    <style>
        div.availableCourses>a{
            text-decoration: none;
        }

        div.availableCourses .card .card-header{
            background-color: rgb(233, 236, 239);
            height: 74px;
        }

        div.availableCourses .card .card-header h2{
            color: black;
            font-size: 16px;
            line-height: 18px;
        }

        div.availableCourses .card .card-header h3{
            color: #525252;
            font-size: 15px;
        }

        div.availableCourses .card .card-body img{
            width: 100%;
            height: auto;
        }

        div.availableCourses .card .card-body div.info{
            color: #525252;
        }

        div.availableCourses .card .card-body div.info button{
            width: 120px;
        }
    </style>
@endsection

@section('homeMenu','active')

@section('conteudo')
    <div class="container">
        <div class="row mt-4">
            <div class="jumbotron col-12 p-3 mb-0">
                <h1 class="text-center h2">
                    Olá {{ explode(' ',$user->name)[0] }}, o que vamos estudar hoje?
                </h1>
            </div>
        </div>
        <div class="row availableCourses">
            @foreach ($courses as $course)
                @php
                    $subscription = Subscription::findSubscription($user->id,$course->id);
                @endphp
                @if(count($subscription)>0)
                    <a class="col-12 col-md-6 col-lg-4 mt-4 aCard" href="{{ route('user-show-course',$id = $course->id) }}">
                        <div class="card">
                            <div class="card-header py-2 px-3">
                                <h2 class="card-title m-0">{{ $course->title }}</h2>
                                <h3 class="card-subtitle mt-1">{{ $course->category->title }}</h3>
                            </div>
                            <div class="card-body p-2 d-flex align-items-stretch">
                                <div class="col-3 p-0">
                                    <img class="" src="{{ $course->getUrlIcon() }}" alt="">
                                </div>
                                <div class="col-9 info align-items-center d-flex">
                                    <div class="row">
                                        <p class="col-12 card-text mb-1">Carga Horária: {{ $course->workload }} h</p>
                                        <p class="col-12 card-text mb-2">Publicado em: {{ $course->getPublicationDate() }}</p>
                                        <p class="col-12 card-text text-right">
                                            @if(count($subscription)>0)
                                                <button href="{{ route('user-continue-course',$course->id) }}" class="btn btn-success btn-sm text-white btnContinueCourse">Continuar Curso</button>
                                            @else
                                                <button class="btn btn-primary btn-sm text-white">Ver Curso</button>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
            @endforeach
            @foreach ($courses as $course)
                @if($course->status==2 && count(Subscription::findSubscription($user->id,$course->id))==0)
                    <a class="col-12 col-md-6 col-lg-4 mt-4" href="{{ route('user-show-course',$id = $course->id) }}">
                        <div class="card">
                            <div class="card-header py-2 px-3">
                                <h2 class="card-title m-0">{{ $course->title }}</h2>
                                <h3 class="card-subtitle mt-1">{{ $course->category->title }}</h3>
                            </div>
                            <div class="card-body p-2 d-flex align-items-stretch">
                                <div class="col-3 p-0">
                                    <img class="" src="{{ $course->getUrlIcon() }}" alt="">
                                </div>
                                <div class="col-9 info align-items-center d-flex">
                                    <div class="row">
                                        <p class="col-12 card-text mb-1">Carga Horária: {{ $course->workload }} h</p>
                                        <p class="col-12 card-text mb-2">Publicado em: {{ $course->getPublicationDate() }}</p>
                                        <p class="col-12 card-text text-right"><button class="btn btn-primary btn-sm text-white">Ver Curso</button></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('.card').on('click','.btnContinueCourse',function(event){
            event.preventDefault();
            window.location.href=$(event.target).attr('href');
        });
    </script>
@endsection