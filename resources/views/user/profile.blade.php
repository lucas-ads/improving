@extends('user.layout')

@section("title","Meu Perfil")

@section('css')
    <style>
        #imgProfile,.btnChange{
            width: 100%;
        }

        header.row #nameUser h1{
            font-size: 1.3em;
        }

        header.row #nameUser h2{
            font-size: 1.1em;
            margin-bottom: 0;
        }

        div.infoUser p{
            font-size: 1em;
            margin-bottom: 0.4em;
        }

        .grayColor{
            color: #525252;
        }

        div.infoUser p:nth-child(odd){
            font-weight: 600;
            margin-bottom: 0;
        }

        #table-courses{
            font-size: 1.15em;
        }

        #table-courses td{
            vertical-align: middle;
        }

        .mb-6{
            margin-bottom: 15em;
        }

        .custom-file-label{
            overflow: hidden;
            white-space: nowrap;
        }

        @media(min-width: 576px){
            div.infoUser p:nth-child(odd){
                text-align: right;
            }
        }

        @media(min-width: 768px){
            header.row #nameUser{
                font-size: 1.2em;
            }

            div.infoUser p{
                font-size: 1.2em;
            }
        }

        @media(min-width: 992px){
            header.row #nameUser{
                font-size: 1.4em;
            }
        }

        @media(min-width: 1200px){
            header.row #nameUser{
                font-size: 1.6em;
            }
        }
    </style> 
@endsection

@section('conteudo')
    <div class="container mt-4">
        <header class="row align-items-center">
            <div class="col-4 col-sm-3 col-md-2">
                <img id="imgProfile" src="{{ $user->getUrlImageOrBlue() }}" alt="">
                <button class="btn btn-primary btnChange mt-2" data-toggle="modal" data-target=".imageModal"><i class="fas fa-user-edit"></i> <span class="d-none d-xl-inline">Atualizar</span> Imagem</button>
            </div>
            <div class="col-8 col-sm-9 col-md-10" id="nameUser">
                <h1>{{ $user->name }}</h1>
                <h2>{{ $user->email }}</h2>
            </div>
        </header>
        <hr class="m-0 mt-4 mb-4">

        <div class="row mt-2 mb-2">
            <h2 class="grayColor col-12 col-md-10 offset-md-2 mb-4">Informações Gerais:</h2>
        </div>

        <div class="row infoUser grayColor mt-2 mb-2">
            <div class="col-12 col-sm-12 row">
                <p class="col-12 col-sm-6">Data de Nascimento: </p>     
                <p class="col-12 col-sm-6">{{ $user->date_of_birth->format('d/m/Y') }}</p>

                <p class="col-12 col-sm-6">CPF: </p>
                <p class="col-12 col-sm-6">{{ $user->getFormatedCpf() }}</p>

                <p class="col-12 col-sm-6">Cadastrado em: </p>
                <p class="col-12 col-sm-6">{{ $user->created_at->format('d/m/Y') }}</p>
            </div>
            <div class="col-12 col-sm-12 row">
                <p class="col-12 col-sm-6">Status: </p>
                <p class="col-12 col-sm-6">{{ $user->status==1 ? 'Ativo' : 'Inativo' }}</p>
                
                <p class="col-12 col-sm-6">Permissões: </p>
                <p class="col-12 col-sm-6">
                    @foreach ($user->getRolesArray() as $role)
                        <span class="d-block">{{$role}}</span>
                    @endforeach
                </p>
            </div>
        </div>
    </div>
    
    <div class="modal fade imageModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="unit-modal-title">Atualizar Imagem de Perfil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="formImage" action="{{route('user-set-image-profile')}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="alert alert-info">
                            Selecione uma imagem com resolução mínima de 200x200, e que seja quadrada (proporção 1x1).
                        </div>
                        <div class="form-group row">
                            <label for="icon" class="col-md-4 col-form-label text-md-right">Imagem</label>
            
                            <div class="col-md-6 input-group">
                                <div class="custom-file">
                                    <input type="file" accept="image/png,image/jpeg" class="custom-file-input inputProfile" id="newImage" name="newImage" required>
                                    <label class="custom-file-label" id="labelForInputIcon" for="newImage">Selecione a imagem</label>
                                </div>
                            </div>

                            <div class="col-md-6 offset-md-4 input-group">
                                <input type="hidden" class="form-control {{ $errors->has('newImage') ? ' is-invalid' : '' }}">
                                @if ($errors->has('newImage'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('newImage') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>



                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnSendImage">Enviar</button>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@section('js')
    <script>
       $('input.custom-file-input.inputProfile').on('change', function(){
            var input = $('input.custom-file-input')[0];
            var label = $('label.custom-file-label');
            console.log(input.files[0].type);
            var type = input.files[0].type;
            if(type=="image/png" || type=="image/jpg" || type=="image/jpeg"){
                label.text(input.files[0].name);
                label.removeClass('text-danger');
            }else{
                label.text('Somente imagem PNG ou JPEG');
                label.addClass('text-danger');
                input.value="";
            }
        });

        $('#btnSendImage').on('click',function(){
            if($('input.custom-file-input.inputProfile')[0].files.length==1){
                $('#formImage').submit();
            }
        });

        $(document).ready(function() {
            if($('.is-invalid').length>0){
                $('.imageModal').modal('show');
            }
        });
    </script>    
@endsection