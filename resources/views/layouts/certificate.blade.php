<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <style>
            body{
                font-family: 'Roboto', sans-serif;
            }

            div#background img{
                position: fixed;
                top:0;
                left:0;
                width: 27.3cm;
                height: 18.53cm;
            }

            #content{
                position: fixed;
                top: 5cm;
                padding-left: 1cm;
                padding-right: 1cm;
            }

        
            #name{
                font-size: 1.4em;
            }

            h1{
                width: 27.3cm;
                text-align: center;
                font-size: 2.2em;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div id="background">
            <img src="{{ asset('images/certificado.png') }}">
        </div>
        <div id="content">
            <h1>CERTIFICADO</h1>
            <p>Certificamos que</p>
            <p id="name"><b>{{strtoupper($user->name)}}</b></p>
            <p id="text">registrado sob o CPF nº <b>{{$user->getFormatedCpf()}}</b>, concluiu com êxito o curso de 
                <b>{{$subscription->course->title}}</b>, com carga horária de 
                <b>{{$subscription->course->workload}} horas</b>
                 oferecido na plataforma de aprendizagem <i>Improving</i> desta corporação.
            </p>
            <p><b>Início em: </b>{{$subscription->created_at->format('d/m/Y')}}</p>
            <p><b>Conclusão em: </b>{{$subscription->completed_at->format('d/m/Y')}}</p>
            <p><b>Código de Autenticidade:</b> {{$subscription->validationCode}}</p>
        </div>
    </body>
</html>