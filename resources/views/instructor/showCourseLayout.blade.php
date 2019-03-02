@extends('instructor.layout')

@section("title",$course->title)

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

        .rowButtons div{
            margin-bottom: 0.5em;
        }

        .rowButtons div .btn{
            display: block;
            width: 100%;
        }

        @media(min-width: 768px){
            header.row #title-course{
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
        <ul class="nav nav-tabs row mt-4">
            <li class="nav-item col-6">
                <a class="nav-link @yield('info-course')" href="{{ route('instructor-show-course',$id=$course->id) }}">Informações<span class="d-none d-sm-inline"> Gerais</span></a>
            </li>
            <li class="nav-item col-6">
                <a class="nav-link @yield('content-course')" href="{{ route('instructor-show-course-content',$id=$course->id) }}">Conteúdo</a>
            </li>
        </ul>
    </div>
    @yield('conteudoCurso')
    <div class="container mt-4 mb-5">
        <div class="row rowButtons">
            <div class="col-12 col-md-3">
                <a class="btn btn-primary" href="{{ route('instructor-form-edit-course',$id=$course->id) }}"><i class="fas fa-edit"></i> Editar Informações</a>
            </div>
            <div class="col-12 col-md-3">
                <a class="btn btn-primary" href="{{ route('instructor-show-form-content',$id=$course->id) }}"><i class="fas fa-edit"></i> Editar Conteúdo</a>
            </div>
            
            @if($course->status==1)
                <div class="col-12 col-md-3">
                    <form id="formDelete" action="{{ route('instructor-delete-course',$id=$course->id) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="button" class="btn btn-danger btnDelete"><i class="far fa-trash-alt"></i> Excluir Curso</button>
                    </form>
                </div>
                <div class="col-12 col-md-3">
                    <form id="formPublish" action="{{ route('instructor-publish-course',$id=$course->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <button type="button" class="btn btn-success btnPublish"><i class="fab fa-telegram-plane"></i> Publicar Curso</button>
                    </form>
                </div>
            @elseif($course->status==2)
                <div class="col-12 col-md-3">
                    <form id="formDisable" action="{{ route('instructor-disable-course',$id=$course->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <button type="button" class="btn btn-danger btnDisable"><i class="fas fa-power-off"></i> Desativar Curso</button>
                    </form>
                </div>
                <div class="col-12 col-md-3">
                    <form id="formSuspend" action="{{ route('instructor-suspend-course',$id=$course->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <button type="button" class="btn btn-success btnSuspend" style="background-color: #FF7C2E; border-color: #FF792E"><i class="fas fa-ban"></i> Suspender Inscrições</button>
                    </form>
                </div>
            @elseif($course->status==3)
                <div class="col-12 col-md-3">
                    <form id="formDisable" action="{{ route('instructor-disable-course',$id=$course->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <button type="button" class="btn btn-danger btnDisable"><i class="fas fa-power-off"></i> Desativar Curso</button>
                    </form>
                </div>
                <div class="col-12 col-md-3">
                    <form id="formPublish" action="{{ route('instructor-publish-course',$id=$course->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <button type="button" class="btn btn-success btnPublish"><i class="fas fa-undo-alt"></i> Republicar Curso</button>
                    </form>
                </div>
            @elseif($course->status==4)
                <div class="col-12 col-md-3">
                    <form id="formSuspend" action="{{ route('instructor-suspend-course',$id=$course->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <button type="button" class="btn btn-success btnSuspend" style="background-color: #FF7C2E; border-color: #FF792E"><i class="fas fa-ban"></i> Alterar para Suspenso</button>
                    </form>
                </div>
                <div class="col-12 col-md-3">
                    <form id="formPublish" action="{{ route('instructor-publish-course',$id=$course->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <button type="button" class="btn btn-success btnPublish"><i class="fas fa-undo-alt"></i> Republicar Curso</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
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
                    <button type="button" id="btnConfirm">Excluir</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        var modalConfirm = $('#confirmModal');
        var btnConfirm = $('#btnConfirm');

        $(document).ready(function(){

            $('.btnDelete').on('click',function(){
                modalConfirm.find('.modal-title').text("Excluir Curso");
                modalConfirm.find('.modal-text').text("Tem certeza que deseja excluir permanentemente este curso?");
                btnConfirm.attr('class','btn btn-danger').text('Excluir').off('click').on('click',function(){
                    $('#formDelete').submit();
                });

                modalConfirm.modal('toggle');

            });

            $('.btnPublish').on('click',function(){
                modalConfirm.find('.modal-title').text("Publicar Curso");
                modalConfirm.find('.modal-text').html("Tem certeza que deseja publicar o curso?<br>Após esta ação o mesmo não poderá sofrer alterações estruturais.");
                btnConfirm.attr('class','btn btn-success').text('Publicar').off('click').on('click',function(){
                    $('#formPublish').submit();
                });

                modalConfirm.modal('toggle');
            });

            $('.btnSuspend').on('click',function(){
                modalConfirm.find('.modal-title').text('Tonar o Curso "Suspenso"');
                modalConfirm.find('.modal-text').html('Tem certeza que deseja alterar o status deste curso para "Suspenso"?<br>Após esta ação o curso estará disponível para os já matrículados, mas não permitirá novas inscrições.');
                btnConfirm.attr('class','btn btn-danger').text('Tornar Suspenso').off('click').on('click',function(){
                    $('#formSuspend').submit();
                });

                modalConfirm.modal('toggle');
            });

            $('.btnDisable').on('click',function(){
                modalConfirm.find('.modal-title').text('Desativar Curso');
                modalConfirm.find('.modal-text').html('Tem certeza que deseja desativar este curso?<br>Após esta ação o curso ficará indisponível para todos os usuários.');
                btnConfirm.attr('class','btn btn-danger').text('Desativar').off('click').on('click',function(){
                    $('#formDisable').submit();
                });

                modalConfirm.modal('toggle');
            });
        });
    </script>
@endsection