@extends('admin.layout')

@section("title","Atualizar Usuário")

@section('breadcrumb')
    <div id="breadcrumb-div">
        <div class="container mt-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('admin-home')}}">Usuários</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('admin-show-user',$id=$userUpdate->id)}}">{{$userUpdate->getFirstName()}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('conteudo')
    @include('partials.formUser',['formTitle' => 'Atualizar Usuário','formAction' => route('admin-update-user',['id'=>$userUpdate->id]), 'formMethod' => 'update','submitText' => 'Atualizar'])
@endsection