@extends('instructor.layout') 
@section("title","Criar Atividade") 
 
@section('breadcrumb')
<div id="breadcrumb-div">
    <div class="container mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" aria-current="page"><a href="{{route('instructor-home')}}">Meus Cursos</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="{{route('instructor-show-course',$id=$course->id)}}">{{ $course->title }}</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="{{route('instructor-show-course-content',$id=$course->id)}}">Conteúdo</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="{{route('instructor-show-form-content',$id=$course->id)}}">Editar</a></li>
                <li class="breadcrumb-item active" aria-current="page">Criar Atividade</li>
            </ol>
        </nav>
    </div>
</div>
@endsection
 
@section('conteudo')
<div class='container'>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header h4 text-white bg-dark">
                    <h1>
                        <span class="h5">{{ $course->title }}</span>
                        <i class="fas h5 fa-chevron-right"></i>
                        <span class="h5 text-su">{{ $unit->title }}</span>
                    </h1>
                    <hr class="border border-light mt-2 mb-2">
                    <h1 class="h3">{{ (isset($item)? "Editar ":"Adicionar ") }}Atividade</h1>
                </div>
                <div class="card-body">
                    <a href="{{ route('instructor-show-form-content',$id=$course->id) }}" class="btn text-white bg-info"><i class="fas fa-arrow-left"></i> Voltar sem Salvar</a>
                    @php
                        if(isset($item)){
                            $urlForm = route('instructor-edit-test',['id'=>$course->id,'idItem'=> $item->id]);
                        }else{
                            $urlForm = route('instructor-add-test',['id'=>$course->id,'idUnit'=> $unit->id]);
                        }
                    @endphp
                    <form id="formTest" action="{{ $urlForm }}" method="POST">
                        @csrf
                        @method((isset($item)? "PUT":"POST"))
                        
                        <div id="containerQuestions">
                            <div class="form-group mb-4 mt-3">
                                <label class="h4 text-center d-block">Título da Atividade:</label>
                                <input type="text" id="titleTest" class="form-control" name="title" required>
                            </div>



                        </div>
                        <button type="button" id="btnAddQuestion" class="btn btn-primary mt-3 mx-auto d-block"><i class="fas fa-plus"></i> Adicionar Questão</button>
                        <div class="alert alert-danger mt-3 d-none" id="msgAlert" role="alert">
                            
                        </div>
                        <button id="btnSubmit" type="submit" class='d-none'></button>
                        <input type="hidden" name="test" id="inputTest" value="{{ isset($item)?$item->resource:'' }}">
                    </form>
                </div>
                <div class="card-footer">
                    <a href="{{ route('instructor-show-form-content',$id=$course->id) }}" class="btn text-white bg-info mt-2 float-left"><i class="fas fa-arrow-left"></i> Voltar sem Salvar</a>
                    <button type="submit" id="btnSubmitTest" onclick="$('#btnSubmit').trigger('click');" class="btn btn-success mt-2 mr-2 float-right">Salvar Atividade</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-none" id="altTemplate">
    <div class="input-group mb-3 alternative">
        <div class="input-group-prepend">
            <div class="input-group-text">
                <input type="radio" class="altRadio" name="correctAlt__time__[]" __checked__>
            </div>
        </div>
        <input type="text" class="form-control altText" value="__altText__" required>
        <div class="input-group-append">
            <button class="btn btn-outline-danger btnRemoveAlt" type="button" id="button-addon2"><i class="fas fa-trash-alt"></i></button>
        </div>
    </div>
</div>

<div class="d-none" id="questionTemplate">
    <div class="card question bg-light mt-3" data-timestamp="__time__">
        <div class="card-body">
            <div class="form-group enunciationQuestion">
                <label class="h5">Questão:</label>
                <button type="button" class="btn btn-danger btn-md mb-1 float-right btnRemoveQuestion"><i class="fas fa-trash-alt"></i></button>
                <textarea class="form-control" rows="2" required>__questionText__</textarea>
            </div>
            <div class="altQuestion">
                <label>Insira todas as alternativas e indique qual é a CORRETA:</label>
                __alternatives__
            </div>
            <div class="text-center">
                <button type="button" class="btn-sm btn btn-primary mt-2 mx-auto d-block" id="btnAddAlt"><i class="fas fa-plus"></i> Adicionar Alternativa</button>
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
@endsection
 
@section('js')
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}"></script>
<script src="{{ asset('js/formCreateEditTest.js') }}"></script>
@endsection