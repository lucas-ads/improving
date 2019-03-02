@extends('admin.layout')

@section("title","Admin")

@section('usersItemMenu','active')

@section('css')
    <style>
        .permissions .invisible{
            color: white;
        }

        #table-users td{
            vertical-align: middle;
        }

        #table-users td:first-child{
            width: 60px;
        }

        #table-users .status{
            text-align: center;
            white-space: nowrap;
        }

        #table-users td:not(:first-child) a{
            width: 100%;
            display: inline-block;
        }

        .text-black{
            color: black;
        }

        @media (min-width: 576px) {
            #table-users .status{
                text-align: left;
            }
        }
    </style>
@endsection

@section('breadcrumb')
    <div id="breadcrumb-div">
        <div class="container mt-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Usuários</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('conteudo')
    <div class="container mt-4">
        <div class="row justify-content-end">
            <a class="btn btn-primary text-white mb-3 mr-3" href="{{route('admin-form-register-user')}}"><i class="fas fa-plus"></i>  Cadastrar Usuário</a>
        </div>
        <div class="table-responsive">
            <table class="table" id="table-users">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nome</th>
                        <th class="text-center d-none d-md-table-cell">Data de Nascimento</th>
                        <th class="text-center d-none d-md-table-cell">Permissões</th>
                        <th class="status d-none d-sm-table-cell">Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $otheruser)
                        <tr class="align-items-center">
                            <td>
                                <a href="{{ route('admin-show-user',$id=$otheruser->id) }}">
                                    <img width="40px" src="{{ $otheruser->getUrlImageOrBlue() }}" alt="">
                                </a>
                            </td>
                            <td>
                                <a class="text-black" href="{{ route('admin-show-user',$id=$otheruser->id) }}">
                                    {{ $otheruser->name }}
                                </a>
                            </td>
                            <td class="text-center d-none d-md-table-cell">
                                {{ $otheruser->date_of_birth->format('d/m/Y') }}
                            </td>
                            <td class="d-none d-md-table-cell text-primary permissions">
                                <div class="d-flex justify-content-around h4 align-items-baseline ">
                                    <span class="">
                                        <i class="fas fa-user" title="Usuário"></i>
                                    </span>
                                    <span class="{{$otheruser->isInstructor()?'':'invisible'}}">
                                        <i class="fas fa-chalkboard-teacher" title="Instrutor"></i>
                                    </span>
                                    <span class="{{$otheruser->isAdmin()?'':'invisible'}}">
                                        <i class="fas fa-user-tie" title="Administrador"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="status d-none d-sm-table-cell">
                                @if($otheruser->status==1)
                                    <i class="fas fa-circle text-success"></i>
                                    <span class="d-none d-sm-inline">Ativo</span>
                                @else
                                    <i class="fas fa-circle text-secondary"></i>
                                    <span class="d-none d-sm-inline">Inativo</span>
                                @endif
                            </td>
                            <td class="justify-content-around d-flex">
                                <a class="btn btn-primary mx-2" href="{{ route('admin-form-update-user',$id=$otheruser->id) }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a class="btn mx-2" href="{{ route('admin-show-user',$id=$otheruser->id) }}">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Não há usuários a exibir.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="row justify-content-end">
            <a class="btn btn-primary text-white mb-3 mr-3" href="{{route('admin-form-register-user')}}"><i class="fas fa-plus"></i>  Cadastrar Usuário</a>
        </div>        
    </div>
@endsection