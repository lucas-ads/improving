@extends('layouts.simplebase')

@section("title","Validar Certificado")

@section('css')
    
@endsection

@section("brand-link",route('login'))

@section('conteudo')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Validar Certificado</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{-- route('') --}}">
                            @csrf

                            <div class="form-group row">
                                <label for="codeCertificate" class="col-md-4 col-form-label text-md-right">Código de Autenticidade:</label>

                                <div class="col-md-6">
                                    <input id="codeCertificate" type="text" maxlength="14" minlength="14" placeholder="Digite o código de 14 caracteres" class="form-control{{ $errors->has('codeCertificate') ? ' is-invalid' : '' }}" name="codeCertificate" value="{{ old('codeCertificate') }}" required>

                                    @if ($errors->has('codeCertificate'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('codeCertificate') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="cpfUser" class="col-md-4 col-form-label text-md-right">CPF do Usuário:</label>

                                <div class="col-md-6">
                                    <input id="cpfUser" type="text" pattern="[0-9]{11}" placeholder="Somente números" maxlength="11" minlength="11" class="form-control{{ $errors->has('cpfUser') ? ' is-invalid' : '' }}" name="cpfUser" value="{{ old('cpfUser') }}" required>

                                    @if ($errors->has('cpfUser'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('cpfUser') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Verificar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection