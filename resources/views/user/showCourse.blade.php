@extends('user.layout')

@section("title",$course->title)

@php
    use App\Subscription;

    $subscription = Subscription::findSubscription($user->id, $course->id)
@endphp

@section('css')
    <style>
        #icon-course{
            width: 100%;
        }

        header.row #title-course h1{
            font-size: 1.3em;
        }

        header.row #title-course h2{
            font-size: 1.1em;
            margin-bottom: 0;
        }

        div.infoCourse p{
            font-size: 1em;
        }

        .grayColor{
            color: #525252;
        }

        div.infoCourse p span{
            font-weight: 600;
        }

        .mainList .unitTitle{
            background-color: rgb(233, 236, 239);
            
        }

        .mainList .itemTitle{
            padding-left: 3em;
        }

        .mainList .itemTitle>span{
            font-family: "Font Awesome 5 Free";
            margin-right: 5px;
            font-weight: 600;
        }
        
        .mainList .itemTitle.videolesson span::before{
            content: "\f04b";
        }

        .mainList .itemTitle.link span::before{
            content: "\f15c";
        }

        .mainList .itemTitle.test span::before{
            content: "\f059";
        }

        @media(min-width: 768px){
            header.row #title-course{
                font-size: 1.2em;
            }

            div.infoCourse p{
                font-size: 1.2em;
            }
        }

        @media(min-width: 992px){
            header.row #title-course{
                font-size: 1.4em;
            }
        }

        @media(min-width: 1200px){
            header.row #title-course{
                font-size: 1.6em;
            }
        }

        .stars{
            color: #ffa500;
        }
    </style>
@endsection

@section('conteudo')
    <div class="container mt-4">
        <header class="row align-items-center">
            <div class="col-4 col-sm-3 col-md-2">
                <img id="icon-course" src="{{ $course->getUrlIcon() }}" alt="">
            </div>
            <div class="col-8 col-sm-9 col-md-10" id="title-course">
                <h1>{{ $course->title }}</h1>
                <h2>{{ $course->category->title }}</h2>
            </div>
        </header>
        <hr class="m-0 mt-4 mb-4">
        <div class="row infoCourse grayColor mb-4">
            <p class="col-12 col-lg-3 mb-1"><span>Carga Horária: </span>{{ $course->workload }} horas</p>
            <p class="col-12 col-lg-5 mb-1"><span>Instrutor: </span>{{ $course->user->name }}</p>
            <p class="col-12 col-lg-4 mb-1"><span>Publicado em: </span>{{ $course->getPublicationDate() }}</p>
        </div>
        <div class="row mb-3">
            <div class="col-12 col-sm-6 col-md-4 ">
                @if(count($subscription)==0)
                    <a class="btn btn-primary d-block" href="{{ route('user-register-in-course',$id=$course->id) }}">Matricular-se agora!</a>
                @else
                <a class="btn btn-success d-block" href="{{ route('user-continue-course',['id'=>$course->id,'idItem'=>'']) }}">Continuar Curso</a>
                @endif
            </div>
        </div>
        <div class="row">
            <h2 class="grayColor col-12">Conteúdo:</h2>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <ol class="list-group mainList">
                    @foreach ($course->units as $unit)
                        <li class="list-group-item unitTitle grayColor h6 mt-3">
                            {{ $unit->title }}
                        </li>
                        @foreach ($unit->items as $item)
                            <li class="list-group-item itemTitle {{$item->getType()}}"><span> </span>{{ $item->title }}</li>
                        @endforeach
                    @endforeach
                </ol>
            </div>
        </div>
        <div class="row mt-4">
            <h2 class="grayColor col-12">Avaliações:</h2>
        </div>
        <div class="row mb-5">
            @foreach (Subscription::all()->where('course_id',$course->id) as $subs)
                @if($subs->starsCourse != null)
                    <div class="col-12 mt-3">
                        <p class="mb-1 h5">{{$subs->user->name}}:</p>
                        <p class="mb-1 h5 stars">
                            @for($i = 0; $i < $subs->starsCourse; $i++)
                                <span><i class="fas fa-star"></i></span>
                            @endfor
                        </p>
                        <p class="mb-1 h6">{{$subs->comment}}</p>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    
@endsection