@extends('admin.layout')

@section("title","Cadastrar Usuário")

@section('breadcrumb')
    <div id="breadcrumb-div">
        <div class="container mt-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('admin-home')}}">Usuários</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Novo Usuário</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('conteudo')
    @include('partials.formUser',['formTitle' => 'Registrar Usuário','formAction' => route('admin-register-user'), 'formMethod' => 'post','submitText' => 'Registrar'])
@endsection