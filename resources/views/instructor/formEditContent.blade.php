@extends('instructor.layout')

@section("title",$course->title)
 
@section('css')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/formEditContent.css') }}">
@endsection

@section('breadcrumb')
    <div id="breadcrumb-div">
        <div class="container mt-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('instructor-home')}}">Meus Cursos</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('instructor-show-course',$id=$course->id)}}">{{ $course->title }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('instructor-show-course-content',$id=$course->id)}}">Conteúdo</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar</li> 
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('conteudo')
    <div class='container'>
        <div class="row justify-content-center mt-4" role="alert">
            <div class="alert alert-primary col-12">
                <h4 class="alert-heading">Atenção!</h4>
                <hr class="m-2">
                <p class="h5">Todas as operações realizadas nesta tela são salvas instantaneamente.</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card {{ ($course->status==1? '' : 'safeEdit') }}">
                    <div class="card-header h4 text-center text-white bg-dark">Conteúdo - {{ $course->title }}</div>  
                    <div class="card-body" id="containerUnits" data-url-orderItems="{{ route('instructor-order-items',$id=$course->id) }}" data-url-orderUnits="{{ route('instructor-order-units',$id=$course->id) }}">
                        @foreach ($course->units as $unit)
                            <div class="card-unit card" id="unit-{{ $unit->id }}" data-id="{{ $unit->id }}">
                                <div class="card-header">
                                    <div class="moveUnit {{ ($course->status==1? 'col-2 col-sm-1' : 'col-0') }} p-0">
                                        <span class="btn btn-outline-dark btnMoveUnit btn-sm"><i class="fas fa-arrows-alt"></i></span>
                                    </div>
                                    <div class="title {{ ($course->status==1? 'col-7 col-sm-8' : 'col-9') }}">
                                        <span >{{ $unit->title }}</span>
                                    </div>
                                    <div class="button-group col-3 p-0 text-right">
                                        <button class="btn btn-danger btn-sm mb-1 btnModalDeleteUnit"><i class="fas fa-trash-alt"></i></button>
                                        <button class="btn btn-primary btn-sm mb-1 btnModalEditUnit" data-id="{{ $unit->id }}"><i class="far fa-edit"></i></button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ol class="list-group listItemsUnit" id="ol-{{ $unit->id }}">
                                        @foreach($unit->items as $item)
                                            <li class="list-group-item item {{$item->getType()}}" data-url-remove="{{ route('instructor-remove-item',[ 'id'=>$course->id,'idItem'=>$item->id ]) }}" data-id="{{$item->id}}" data-resource="{{ ($item->type!=3? $item->resource:'') }}">
                                                <div class="moveItem {{ ($course->status==1? 'col-2 col-sm-1' : 'col-0') }} p-0">
                                                    <span class="btn btnMoveItem btn-sm"><i class="fas fa-arrows-alt"></i></span>
                                                </div>
                                                <div class="{{ ($course->status==1? 'col-7 col-sm-8' : 'col-9') }} itemTitle">
                                                    <span>{{ $item->title }}</span>
                                                </div>
                                                <div class="button-group-item col-3 p-0 text-right">
                                                    <button class="btn btn-danger btn-sm mb-1 btnModalDeleteItem"><i class="fas fa-trash-alt"></i></button>
                                                    <button class="btn btn-primary btn-sm mb-1 btnModelEdit{{$item->getType()}}" {{ $item->type==3? ('url-edit='.route('instructor-form-edit-test',['id'=>$course->id,'idItem'=>$item->id])) : '' }}><i class="far fa-edit"></i></button>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ol>
                                    <div class="btn-group float-right m-2 mr-3 dropdown-addItems">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-plus"></i> <span class="d-none d-sm-inline-block">Adicionar</span> Item
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <button class="dropdown-item btnModelAddVideoLesson" data-url="{{ route('instructor-add-videolessonOrLink',['id'=>$course->id,'idUnit'=>$unit->id]) }}" type="button">Videoaula</button>
                                            <button class="dropdown-item btnModelAddLink" data-url="{{ route('instructor-add-videolessonOrLink',['id'=>$course->id,'idUnit'=>$unit->id]) }}" type="button">Material Complementar</button>
                                            <button class="dropdown-item btnAddTest" data-url="{{ route('instructor-form-add-test', ['id'=>$course->id,'idUnit'=>$unit->id]) }}" type="button">Atividade Avaliativa</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('instructor-show-course-content',$id=$course->id) }}" class="btn text-white bg-info mt-2 float-left"><i class="fas fa-arrow-left"></i> Concluir<span class="d-none d-sm-inline"> Edição</span></a>
                        <button type="button" id="btnModalAddUnit" class="btn btn-primary ml-auto mt-2 mr-2 float-right"><i class="fas fa-plus"></i> <span class="d-none d-sm-inline"> Adicionar</span> Unidade</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="modal-text">Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btnConfirmDelete">Excluir</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="unitModal" tabindex="-1" data-url="{{ route('instructor-add-unit',$id=$course->id)}}" role="dialog" aria-labelledby="Modal para criação e edição de Unidades" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="unit-modal-title">Adicionar Unidade</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-0">
                        <label for="inputTitleUnit">Título da Unidade (Sem numeração):</label>
                        <input type="text" class="form-control" id="inputTitleUnit" placeholder='Exemplo: "Introdução"'>
                    </div>
                    <span role="alert">
                        <strong class="ml-1 text-danger" id="msgErrorUnit"></strong>
                    </span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnUnit">Adicionar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="itemModal" tabindex="-1" data-edit-url="{{ route('instructor-edit-videolessonOrLink',['id'=>$course->id,'idItem'=>'']) }}" role="dialog" aria-labelledby="Modal para Adição e Edição de Videoaulas" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Adicionar Videoaula</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputTitleItem">Título da Aula:</label>
                        <input type="text" class="form-control" id="inputTitleItem">
                    </div>
                    <div class="form-group mb-0">
                        <label for="inputUrlItem">URL de Incorporação:</label>
                        <input type="url" class="form-control" id="inputUrlItem">
                    </div>
                    <span role="alert">
                        <strong class="ml-1 text-danger" id="msgErrorItem"></strong>
                    </span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnModalItem">Adicionar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="unitTemplate" class="d-none">
        <div class="card-unit card" id="unit-__idUnit__" data-id="__idUnit__">
            <div class="card-header">
                <div class="moveUnit col-2 col-sm-1 p-0">
                    <span class="btn btn-outline-dark btnMoveUnit btn-sm"><i class="fas fa-arrows-alt"></i></span>
                </div>
                <div class="title col-7 col-sm-8">
                    <span >__titleUnit__</span>
                </div>
                <div class="button-group col-3 p-0 text-right">
                    <button class="btn btn-danger btn-sm mb-1 btnModalDeleteUnit"><i class="fas fa-trash-alt"></i></button>
                    <button class="btn btn-primary btn-sm mb-1 btnModalEditUnit" data-id="__idUnit__"><i class="far fa-edit"></i></button>
                </div>
            </div>

            <div class="card-body">
                <ol class="list-group listItemsUnit" id="ol-__idUnit__">
                        
                </ol>
                <div class="btn-group float-right m-2 mr-3">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-plus"></i> <span class="d-none d-sm-inline-block">Adicionar</span> Item
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <button class="dropdown-item btnModelAddVideoLesson" data-url="{{ route('instructor-add-videolessonOrLink',['id'=>$course->id,'idUnit'=>'__idUnit__']) }}" type="button">Videoaula</button>
                        <button class="dropdown-item btnModelAddLink" data-url="{{ route('instructor-add-videolessonOrLink',['id'=>$course->id,'idUnit'=>'__idUnit__']) }}" type="button">Material Complementar</button>
                        <button class="dropdown-item btnAddTest" data-url="{{ route('instructor-form-add-test', ['id'=>$course->id,'idUnit'=>'__idUnit__']) }}" type="button">Atividade Avaliativa</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="itemTemplate" class="d-none">
        <li class="list-group-item item __itemType__" data-url-remove="{{ route('instructor-remove-item',[ 'id'=>$course->id,'idItem'=>"__itemId__" ]) }}" data-id="__itemId__" data-resource="__itemResource__">
            <div class="moveItem col-2 col-sm-1 p-0">
                <span class="btn btnMoveItem btn-sm"><i class="fas fa-arrows-alt"></i></span>
            </div>
            <div class="col-7 col-sm-8 itemTitle">
                <span>__itemTitle__</span>
            </div>
            <div class="button-group-item col-3 p-0 text-right">
                <button class="btn btn-danger btn-sm mb-1 btnModalDeleteItem"><i class="fas fa-trash-alt"></i></button>
                <button class="btn btn-primary btn-sm mb-1 btnModelEdit__itemType__"><i class="far fa-edit"></i></button>
            </div>
        </li>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('js/formEditContent.js') }}"></script>
@endsection