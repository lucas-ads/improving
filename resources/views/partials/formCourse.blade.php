<div class="container">
        <div class="row justify-content-center mt-4">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">{{ $formTitle }}</div>
        
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data" action="{{ $formAction }}">
                                @csrf
                                @method($formMethod)
        
                                <div class="form-group row">
                                    <label for="title" class="col-md-4 col-form-label text-md-right">Título</label>
        
                                    <div class="col-md-6">
                                        <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title', isset($cursoUpdate)?$cursoUpdate->title:"" ) }}" required autofocus>
        
                                        @if ($errors->has('title'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="workload" class="col-md-4 col-form-label text-md-right">Carga Horária (h)</label>
        
                                    <div class="col-md-6">
                                        <input id="workload" type="number" pattern="\d{1,3}" max="400" min="10" value="{{ old('workload', isset($cursoUpdate)?$cursoUpdate->workload:"" ) }}" class="form-control{{ $errors->has('workload') ? ' is-invalid' : '' }}" name="workload" required>
        
                                        @if ($errors->has('workload'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('workload') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="category" class="col-md-4 col-form-label text-md-right">Categoria</label>
        
                                    <div class="col-md-6">
                                        <select name="category" id="category" class="custom-select {{ $errors->has('category') ? ' is-invalid' : '' }}" required>
                                            <option>Selecione a categoria</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ (old("category", isset($cursoUpdate)?$cursoUpdate->category->id:"") == $cat->id ? "selected":"") }}>{{ $cat->title }}</option>
                                            @endforeach
                                        </select>

                                        
                                        @if ($errors->has('category'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('category') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="keywords" class="col-md-4 col-form-label text-md-right">Palavras-chave</label>
            
                                    <div class="col-md-6">
                                        <input id="keywords" type="text" value="{{ old('keywords', isset($cursoUpdate)?$cursoUpdate->keywords:"" ) }}" pattern="[aA-zZa-záÁàÀãÃâÂéÉèÈêÊíÍìÌóÓòÒõÕôÔúÚùÙçÇ\-\s]+" class="form-control{{ $errors->has('keywords') ? ' is-invalid' : '' }}" name="keywords" required>
            
                                        @if ($errors->has('keywords'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('keywords') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="icon" class="col-md-4 col-form-label text-md-right">Ícone do curso</label>
            
                                    <div class="col-md-6 input-group">
                                        <div class="custom-file">
                                            <input type="file" accept="image/png" class="custom-file-input png" id="icon" name="icon" {{ (isset($cursoUpdate)?"":"required" ) }}>
                                            <label class="custom-file-label" id="labelForInputIcon" for="icon">Selecione a imagem</label>
                                        </div>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button">Enviar</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 offset-md-4 input-group">
                                        <input type="hidden" class="form-control {{ $errors->has('icon') ? ' is-invalid' : '' }}">
                                        @if ($errors->has('icon'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('icon') }}</strong>
                                            </span>
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