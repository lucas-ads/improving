@extends('instructor.showCourseLayout')

@section("title",$course->title)

@section('breadcrumb')
    <div id="breadcrumb-div">
        <div class="container mt-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('instructor-home')}}">Meus Cursos</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('instructor-show-course',$id=$course->id)}}">{{ $course->title }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Conteúdo</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content-course','active')

@section('conteudoCurso')
    <div class="container">
        <ol class="list-group mt-5 mainList">

            @foreach ($course->units as $unit)
                <li class="list-group-item unitTitle collapsed" data-toggle="collapse" data-target="#unit-{{ $unit->id }}">
                    {{ $unit->title }} 
                    <span class="showItems float-right"></span>
                </li>
                <li class="list-group-item items collapse p-0 pl-4" id="unit-{{ $unit->id }}">
                    <ol class="list-group sublist">
                        @foreach ($unit->items as $item)
                            <li class="list-group-item itemTitle {{$item->getType()}}"><span> </span>{{ $item->title }}</li>
                        @endforeach
                    </ol>
                </li>
            @endforeach

        </ol>
    </div>
@endsection

@section('css')
    @parent
    <style>
        .list-group li{            
            border: none;
            border-radius: 0.3em;
        }
        
        .mainList .unitTitle{
            background-color: rgba(19, 28, 87,0.9);
            color: white;
            margin-top: 0.5em;
            cursor: pointer;
        }

        .mainList .unitTitle span.showItems{
            display: inline-block;
            background-color: white;
            border-radius: 2em;
            width: 23px;
            padding-left: 6px;
            height: 23px;
        }

        .mainList .unitTitle span.showItems::before{
            content: "\f077";
            font-family: "Font Awesome 5 Free";
            font-weight: 600;
            color: rgba(19, 28, 87,0.9);
        }

        .mainList .unitTitle.collapsed span.showItems::before{
            content: "\f078";
        }

        .mainList .items{
            background-color: rgba(0,0,0,0);
        }

        .mainList .sublist .itemTitle{
            margin-top: 0.5em;
            background-color: rgb(235, 235, 235);
        }

        .mainList .sublist .itemTitle>span{
            font-family: "Font Awesome 5 Free";
            margin-right: 5px;
            font-weight: 600;
        }
        
        .mainList .sublist .itemTitle.videolesson span::before{
            content: "\f04b";
        }

        .mainList .sublist .itemTitle.link span::before{
            content: "\f15c";
        }

        .mainList .sublist .itemTitle.test span::before{
            content: "\f059";
        }

    </style>
@endsection