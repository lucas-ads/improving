@extends('user.layout')

@section("title","Feedback")

@section('css')
    <style>
        .stars label span::before{
            content: "\f005";
            font-family: "Font Awesome 5 Free";
            font-weight: 100;
            cursor: pointer;
            font-size: 2.1em;
            margin-left: 0.5em;
            color: #ffa500;
        }

        .stars .lbl5:hover span::before,
        .stars .lbl5:hover ~ label span::before{
            font-weight: 600;
        }

        .stars .lbl4:hover span::before,
        .stars .lbl4:hover ~ label span::before{
            font-weight: 600;
        }

        .stars .lbl3:hover span::before,
        .stars .lbl3:hover ~ label span::before{
            font-weight: 600;
        }

        .stars .lbl2:hover span::before,
        .stars .lbl2:hover ~ label span::before{
            font-weight: 600;
        }

        .stars .lbl1:hover span::before{
            font-weight: 600;
        }

        #input1Stars:checked ~ .lbl1 span::before{
            font-weight: 600;
        }

        #input2Stars:checked ~ .lbl1 span::before,
        #input2Stars:checked ~ .lbl2 span::before{
            font-weight: 600;
        }

        #input3Stars:checked ~ .lbl1 span::before,
        #input3Stars:checked ~ .lbl2 span::before,
        #input3Stars:checked ~ .lbl3 span::before{
            font-weight: 600;
        }

        #input4Stars:checked ~ .lbl1 span::before,
        #input4Stars:checked ~ .lbl2 span::before,
        #input4Stars:checked ~ .lbl3 span::before,
        #input4Stars:checked ~ .lbl4 span::before{
            font-weight: 600;
        }

        #input5Stars:checked ~ .lbl1 span::before,
        #input5Stars:checked ~ .lbl2 span::before,
        #input5Stars:checked ~ .lbl3 span::before,
        #input5Stars:checked ~ .lbl4 span::before,
        #input5Stars:checked ~ .lbl5 span::before{
            font-weight: 600;
        }
    </style>
@endsection

@section('conteudo')
    <div class="container mt-4">
        <div class="row">
            <div class="col-12 alert alert-success py-4">
                <h3 class="alert-heading">Parabéns!</h3>
                <hr>
                <p class="h4">Você concluiu o curso <b>"{{$course->title}}"</b>.</p>
                <p class="mb-0"><a href="{{route('user-download-certificate',$id=$course->id)}}" target="_blank" class="btn btn-primary"><i class="fas fa-award"></i> Baixar Certificado</a></p>
            </div>
            
            <div class="col-12 mt-4">
                <h4 class="px-2 text-center">Deixe sua opinião sobre o curso:</h4>
            </div>
            <div class="col-12 col-md-8 offset-md-2">



                        <div class="card mb-5">
                                <div class="card-header">Avaliação a respeito do curso</div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('user-save-feedback',$id=$course->id) }}">
                                        @csrf
                                        
                                        <div class="form-group row stars justify-content-center">
                                            <p class="col-12 text-center h5">Dê uma nota de 1 a 5:</p>
                                            <input type="radio" value="1" name="inputStar" id="input1Stars" class="d-none">
                                            <input type="radio" value="2" name="inputStar" id="input2Stars" class="d-none">
                                            <input type="radio" value="3" name="inputStar" id="input3Stars" class="d-none">
                                            <input type="radio" value="4" name="inputStar" id="input4Stars" class="d-none">
                                            <input type="radio" value="5" name="inputStar" id="input5Stars" class="d-none">
                                            <label class="lbl5 order-5" for="input5Stars"><span></span></label>
                                            <label class="lbl4 order-4" for="input4Stars"><span></span></label>
                                            <label class="lbl3 order-3" for="input3Stars"><span></span></label>
                                            <label class="lbl2 order-2" for="input2Stars"><span></span></label>
                                            <label class="lbl1 order-1" for="input1Stars"><span></span></label>
                                        </div>

                                        <div class="form-group row justify-content-center">
                                            <div class="col-12 col-md-8 text-center">
                                                <label class="h5" for="comment">Deixe seu comentário:</label>
                                                <textarea class="form-control" name="comment" id="comment" rows="5"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row justify-content-center">
                                            <div class="col-12 col-md-8 text-center">
                                                <button type="submit" class="btn btn-primary" id="btnSendFeedback"><i class="fas fa-paper-plane"></i> Publicar</button>
                                            </div>
                                        </div>
                                      
                                    </form>
                                </div>
                            </div>



                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('#btnSendFeedback').on('click',function(event){
            var radio = $('.stars input[type="radio"]:checked');
            
            if(radio.length==0 || $(radio[0]).attr('name')!='inputStar'){
                event.preventDefault();
            }
        });
    </script>
@endsection