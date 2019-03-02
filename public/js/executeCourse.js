$('.btn-expand-collapse').click(function(e) {
    $('.navbar-course').toggleClass('collapsed');
});

function testFeedback(acertos, nquestions){
    var feedback = $('#feedbackTest');
    if(acertos/nquestions>=2/3){
        feedback.addClass('alert-success');
        feedback.find('p').text('Parabéns, você acertou '+acertos+' de '+nquestions+'!');
    }else{
        feedback.find('p').text('Você acertou '+acertos+' de '+nquestions+'.');
        if(acertos/nquestions>=1/3){
            feedback.addClass('alert-warning');
        }else{
            feedback.addClass('alert-danger');
        }
    }
    feedback.removeClass('d-none');
}

function sendResult(nota){
    var xcsrf = $('meta[name="csrf-token"]').attr('content');
    var url = $('div.test').attr('data-url');
    
    $.ajax({
        url: url,
        dataType: 'json',
        type: 'post',
        data: {
            nota: nota
        },
        headers: {
            'X-CSRF-TOKEN': xcsrf
        },
        success: function(result){
            console.log(result);
            if(result.concluido=="true"){
                var li = $('.conclusionDisable');
                li.removeClass('conclusionDisable');
                li.find('i').removeClass('fa-lock');
                li.find('i').addClass('fa-award');
            }
        },
        error:function(result){
            console.log('erro');
            console.log(result);
        }
    });
}

$('.btn-corrigir').on('click',function(event){
    var questions = $('.test .question');
    $('.altQuestion').removeClass('alert alert-danger');
    $('.form-check').removeClass('alert alert-danger').removeClass('alert alert-success');

    var respondidas = 0;

    for(var i=0;i<questions.length; i++){
        var alt = $(questions[i]).find('input[type="radio"]:checked');
        if(alt.length==0){
            $(questions[i]).find('.altQuestion').addClass('alert alert-danger');
        }else{
            respondidas++;
        }
    }
            
    var acertos=0;
    if(respondidas==questions.length){
        for(var i=0;i<questions.length; i++){
            var alt = $(questions[i]).find('input[type="radio"]:checked');
            if($(alt).val()=="certa"){
                $(alt).parent().addClass('alert alert-success');
                acertos++;
            }else{
                $(alt).parent().addClass('alert alert-danger');
            }
        }
        $('.question input').attr('disabled','true');
        $(event.target).attr('disabled','true');

        testFeedback(acertos,questions.length);
        sendResult((acertos/questions.length)*10);
    }
});