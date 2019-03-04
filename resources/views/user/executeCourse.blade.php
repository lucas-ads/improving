@extends('user.layout')

@section("title",$course->title)

@section('css')
    <link rel="stylesheet" href="{{asset('css/executeCourse.css')}}">
@endsection

@section('conteudo')
    <div class="container" id="container-item-course">
        <div class="row align-items-center">
            <div class="col-12 mb-1 mt-4 bg-light">
                <h1 class="h5">{{$course->title}} <i class="fas fa-chevron-right"></i> {{$thisItem->unit->title}}</h1>
            </div>
            <div class="col-6 col-sm-8 col-md-9 col-lg-10 pr-0">
                <h1 class="h4 d-none d-sm-block my-2">{{$thisItem->title}}</h1>
            </div>
            <div class="col-12 col-sm-4 col-md-3 col-lg-2 text-right pl-1 my-2">
                @php
                    $prevItem = $thisItem->prevItem();
                    if($prevItem == null){
                        $prevLink = '#';
                        $prevBtn = "disabled";
                    }else{
                        $prevLink = route('user-continue-course',[
                            'id'=>$course->id,
                            'idItem'=>$prevItem->id
                        ]);
                        $prevBtn = '';
                    }

                    $nextItem = $thisItem->nextItem();
                    if($nextItem == null){
                        $nextLink = '#';
                        $nextBtn = "disabled";
                    }else{
                        $nextLink = route('user-continue-course',[
                            'id'=>$course->id,
                            'idItem'=>$nextItem->id
                        ]);
                        $nextBtn = '';
                    }
                    
                @endphp
                <a href="{{$prevLink}}" class="btn btn-primary text-white {{$prevBtn}}">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <a href="{{$nextLink}}" class="btn btn-primary text-white {{$nextBtn}}">
                    <i class="fas fa-chevron-right"></i>
                </a>
                <button class="btn btn-primary text-white ml-3 btn-expand-collapse">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if($thisItem->type == 1)
                    <div class="embed-responsive embed-responsive-16by9 itemVideoLesson">
                        <iframe src="{{ $thisItem->resource }}" class="embed-responsive-item" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                @elseif($thisItem->type == 2)
                    <div class="jumbotron text-center itemLink">
                        <h1><a href="{{ $thisItem->resource }}" target="_blank"><i class="far fa-file-alt mr-3"></i>{{$thisItem->title}}</a></h1>
                    </div>
                @elseif($thisItem->type == 3)
                    <div class="test" data-url="{{route('user-response-question',['id'=>$course->id,'idQuestion'=>$thisItem->id])}}">
                        <div class="alert py-5 d-none" id="feedbackTest">
                            <p class="mb-0 text-center h3"></p>
                        </div>
                        @php
                            $questions=json_decode($thisItem->resource)->questions;
                        @endphp
                        @foreach ($questions as $pos=>$question)
                            <div class="card question bg-white mt-3">
                                <div class="card-body">
                                    <div class="form-group enunciationQuestion">
                                        <p>
                                            <b>Questão {{$pos+1}}: </b>
                                            {!! str_replace("\n","<br/>",$question->enunciation)!!}
                                        </p>
                                    </div>
                                    <div class="altQuestion">
                                        @foreach ($question->alternatives as $posAlt=>$alternative)
                                            <div class="form-check pl-5 py-1 my-0">
                                                <input class="form-check-input" type="radio" name="question{{$pos}}" id="alt{{ $pos.$posAlt }}" value="{{ ($question->correct) == $posAlt?"certa":"errada" }}">
                                                <label class="form-check-label" for="alt{{ $pos.$posAlt }}">
                                                    {{$alternative}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <button class="btn btn-primary btn-corrigir float-right mt-4 mb-5 px-5">Enviar</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <nav class="navbar-course collapsed">
        <a href="#" class="btn-expand-collapse nav-link text-right py-1"><i class="fas fa-times"></i></a>
        <ul class="navbar-course-menu p-0">
            @foreach ($course->units as $unit)
                <li class="list-group-item unitTitle collapsed" data-toggle="collapse" data-target="#unit-{{ $unit->id }}">
                    <span class="text">{{ $unit->title }}</span>
                    <span class="showItems float-right"></span>
                </li>
                <li class="list-group-item items collapse p-0" id="unit-{{ $unit->id }}">
                    <ol class="list-group sublist">
                        @foreach ($unit->items as $item)
                            <li class="list-group-item itemTitle p-0 nav-item {{ ($item->subscriptions->where('user_id',$user->id)->first()!=null? 'acessado':'') }} {{$item->getType()}} {{ ($item->id == $thisItem->id ? ' disable':'') }} "><a href="{{ route('user-continue-course',['id'=>$course->id,'idItem'=>$item->id]) }}" class="nav-link p-3 pl-4"><span> </span>{{ $item->title }}</a></li>
                        @endforeach
                    </ol>
                </li>
            @endforeach
            <li class="list-group-item unitTitle conclusion p-0 nav-item {{$subscription->completed_at==null ? "conclusionDisable":''}}">
                <a href="{{route('user-show-form-feedback',$course->id)}}" class="nav-link p-3 pl-4">
                    <span>
                        @if($subscription->completed_at==null)
                            <i class="fas fa-lock"></i>
                        @else
                            <i class="fas fa-award"></i>
                        @endif
                    </span>
                     Conclusão</a>
            </li>
        </ul>
    </nav>
@endsection

@section('js')
    <script src="{{asset('js/executeCourse.js')}}"></script>
@endsection