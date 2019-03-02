@extends('admin.layout')

@section("title",$otherUser->name)

@section('css')
    <style>
        #imgProfile{
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

        @media print{
            body{
                font-size: 12pt;
            }

            header #nameUser{
                font-size: 2em;
            }

            p{
                font-size: 50%;
            }

            #iconCertificate::before{
                content: '\f00c';
                font-size: 0.7em;
            }
            
            .progress, .hiddenPrint, #breadcrumb-div{
                display: none;
            }
            
        }
    </style>    
@endsection

@section('breadcrumb')
    <div id="breadcrumb-div">
        <div class="container mt-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('admin-home')}}">Usuários</a></li>
                    <li class="breadcrumb-item active" aria-current="page"> {{$otherUser->getFirstName()}} </li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('conteudo')
    <div class="container mt-4">
        <header class="row align-items-center">
            <div class="col-4 col-sm-3 col-md-2">
                <img id="imgProfile" src="{{ $otherUser->getUrlImageOrBlue() }}" alt="">
            </div>
            <div class="col-8 col-sm-9 col-md-10" id="nameUser">
                <h1>{{ $otherUser->name }}</h1>
                <h2>{{ $otherUser->email }}</h2>
            </div>
        </header>
        <hr class="m-0 mt-4 mb-4">

        <div class="row mt-2 mb-2">
            <h2 class="grayColor col-12 col-md-10 offset-md-2 mb-4">Informações Gerais:</h2>
        </div>

        <div class="row infoUser grayColor mt-2 mb-2">
            <div class="col-12 col-sm-12 row">
                <p class="col-12 col-sm-6">Data de Nascimento: </p>     
                <p class="col-12 col-sm-6">{{ $otherUser->date_of_birth->format('d/m/Y') }}</p>

                <p class="col-12 col-sm-6">CPF: </p>
                <p class="col-12 col-sm-6">{{ $otherUser->getFormatedCpf() }}</p>

                <p class="col-12 col-sm-6">Cadastrado em: </p>
                <p class="col-12 col-sm-6">{{ $otherUser->created_at->format('d/m/Y') }}</p>
            </div>
            <div class="col-12 col-sm-12 row">
                <p class="col-12 col-sm-6">Status: </p>
                <p class="col-12 col-sm-6">{{ $otherUser->status==1 ? 'Ativo' : 'Inativo' }}</p>
                
                <p class="col-12 col-sm-6">Permissões: </p>
                <p class="col-12 col-sm-6">
                    @foreach ($otherUser->getRolesArray() as $role)
                        <span class="d-block">{{$role}}</span>
                    @endforeach
                </p>
            </div>
        </div>

        <div class="row mt-2 mb-2 hiddenPrint justify-content-center">
            <div class="col-12 col-sm-6 col-md-4">
                <a class="btn btn-primary d-block" href="{{ route('admin-form-update-user',['id'=>$otherUser->id]) }}">Atualizar Usuário</a>
            </div>
        </div>

        <div class="row mt-2 mb-2">
            <h2 class="grayColor col-12 col-md-10 offset-md-2 mt-4">Matrículas:</h2>
        </div>


        <div class="row mt-2 mb-2">
            @if(count($otherUser->subscriptions)>0)
                <div class="col-12 table-responsive">
                    <table class="table grayColor" id="table-courses">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Título</th>
                                <th class="text-center">Progresso</th>
                                <th class="text-center">Média</th>
                                <th class="text-center">Concluído</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($otherUser->subscriptions as $subscription)
                                <tr class="align-items-center status-{{ $subscription->course->getStatusLegend() }}">
                                    <td>
                                        <img width="40px" src="{{ $subscription->course->getUrlIcon() }}" alt="">
                                    </td>
                                    <td>
                                        {{ $subscription->course->title }}
                                    </td>
                                    <td class="text-center">
                                        <label class="m-0">{{ $subscription->getProgress() }}%</label>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $subscription->getProgress() }}%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $media = $subscription->getPoints();
                                        @endphp
                                        <label class="m-0">{{ substr($media, 0, 4) }}</label>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: {{ is_numeric($media)?($media*10)."%":$media }}" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($subscription->completed_at != null)
                                            <a href="{{ route('admin-download-certificate',['id'=>$otherUser->id, 'idSubscription'=>$subscription->id]) }}" target="_blank" class="btn btn-lg py-0" style="font-size: 2em;"><i id="iconCertificate" class="fas fa-award"></i></a>
                                        @else
                                            --
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="col-12 alert alert-info h4 py-4 text-center">
                    <p class="m-0">O usuário não está matriculado em nenhum curso.</p>
                </div>
            @endif
        </div>

        <div class="row mt-2 mb-6 hiddenPrint justify-content-center">
            <button class="col-12 col-sm-6 col-md-4 btn btn-primary d-block" onclick="print()"><i class="fas fa-file-pdf"></i> Imprimir</button>
        </div>


    </div>
    
@endsection