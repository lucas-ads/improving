<div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ $formTitle }}</div>
    
                    <div class="card-body">
                        <form method="POST" action="{{ $formAction }}">
                            @csrf

                            @if($formMethod == 'update')
                                @method('PUT')
                            @endif
    
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Nome</label>
    
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', isset($userUpdate)?$userUpdate->name:"" ) }}" required autofocus>
    
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-mail</label>
    
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', isset($userUpdate)?$userUpdate->email:"" ) }}" required>
    
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">CPF</label>
    
                                <div class="col-md-6">
                                    <input id="cpf" type="text" pattern="[0-9]{11}" placeholder="Somente números" maxlength="11" minlength="11" value="{{ old('cpf', isset($userUpdate)?$userUpdate->cpf:"" ) }}" class="form-control{{ $errors->has('cpf') ? ' is-invalid' : '' }}" name="cpf" required>
    
                                    @if ($errors->has('cpf'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('cpf') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="dateOfBirth" class="col-md-4 col-form-label text-md-right">Data de Nascimento</label>
        
                                <div class="col-md-6">
                                    <input id="dateOfBirth" type="date" value="{{ old('dateOfBirth', isset($userUpdate)?$userUpdate->date_of_birth:"" ) }}" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" class="form-control{{ $errors->has('dateOfBirth') ? ' is-invalid' : '' }}" name="dateOfBirth" required>
        
                                    @if ($errors->has('dateOfBirth'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('dateOfBirth') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if(Route::currentRouteName() == 'admin-form-update-user')
                                <div class="form-group row">
                                    <label for="" class="col-md-4 col-form-label text-md-right">Status</label>
                
                                    <div class="col-md-6 d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" checked type="radio" name="status" id="active-status" value="active">
                                            <label class="form-check-label" for="active-status">Ativo</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" {{ $userUpdate->status != 1 ? "checked":"" }} type="radio" name="status" id="inactive-status" value="inactive">
                                            <label class="form-check-label" for="inactive-status">Inativo</label>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group row">
                                <label for="dateOfBirth" class="col-md-4 col-form-label text-md-right align-self-center">Permissões de Acesso</label>
            
                                <div class="col-md-6">
                                    <div class="checkbox disabled">
                                        <label><input type="checkbox" class="mr-2" disabled checked>Usuário</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="instructorCheck" {{ isset($userUpdate) && $userUpdate->isInstructor() ? "checked" : "" }} class="mr-2" value="">Instrutor</label>
                                    </div>
                                    @if($user->isRoot())
                                        <div class="checkbox">
                                            <label><input type="checkbox" name="adminCheck" {{ isset($userUpdate) && $userUpdate->isAdmin() ? "checked" : "" }} class="mr-2" value="">Administrador</label>
                                        </div>
                                    @endif
                                </div>
                            </div>
    
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">{{ $submitText }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>