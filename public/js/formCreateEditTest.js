    var xcsrf = $('meta[name="csrf-token"]').attr('content');
    var containerQuestions = $('#containerQuestions');
    var questionTemplate = $('#questionTemplate');
    var altTemplate = $('#altTemplate');
    var confirmDeleteModal = $('#confirmDeleteModal');
    var msgAlert = $('#msgAlert');
    
    //Configura o JSON com os dados para a submissão
    $('#formTest').on('submit',function(event){
        var titleTest = $('#titleTest').val();

        if(titleTest.length>100){
            msgAlert.text("O Título da Atividade deve ter no máximo 100 caracteres.");
            msgAlert.removeClass('d-none');
            return false;
        }

        var test = new Object();

        test.title = titleTest;
        test.questions=new Array();

        (containerQuestions.find('.card.question')).each(function(i,q){
            let obj = new Object();
            
            obj.enunciation = $(this).find('.enunciationQuestion textarea').val();
            obj.alternatives = new Array();

            ($(this).find('.alternative')).each(function(el){

                obj.alternatives.push($(this).find('.altText').val());
                if($(this).find('.altRadio').prop('checked')){
                    obj.correct = obj.alternatives.length-1;
                }
            });

            if(obj.correct == null){
                msgAlert.text("Marque a alternativa correta de cada questão.");
                msgAlert.removeClass('d-none');
                test.hasError=true;
                return false;
            }

            test.questions.push(obj);
        });    

        if(test.hasError){
            return false;
        }

        $('#formTest #inputTest').val(JSON.stringify(test));
        
    });


    //Adiciona uma alternativa a questão (enquanto n(alt) < 5)
    containerQuestions.on('click','#btnAddAlt',function(event){
        var question = $(this).parents('.question');
        var altQuestion = question.find('.altQuestion');
        if(altQuestion.find('.alternative').length<5){
            altQuestion.append(altTemplate.html().replace(/__altText__/g,"").replace(/(__time__)/g,question.attr('data-timestamp')));
        }
    });

    //Função que remove a questão solicitada
    function removeQuestion(){
        this.remove();
        if(containerQuestions.find('.question').length < 1){
            addQuestion();
        }
        confirmDeleteModal.modal('toggle');
    }

    //Chama o Modal para Confirmação de exclusão da Questão
    containerQuestions.on('click','.btnRemoveQuestion',function(){
            var item=$(this).parents('.question');

            confirmDeleteModal.find('.modal-title').text("Excluir Questão");
            confirmDeleteModal.find('.modal-text').html("Deseja excluir permanentemente a Questão?");
            confirmDeleteModal.find('#btnConfirmDelete').off('click').on('click',removeQuestion.bind(item));
            confirmDeleteModal.modal('toggle');
    });

    //Função que remove a Alternativa solicitada
    function removeAlternative(){
        var nAlts = this.parents('.altQuestion').find('.alternative').length;
        this.remove();
        if(nAlts < 3){
            $('#btnAddAlt').trigger('click');
        }
        confirmDeleteModal.modal('toggle');
    }

    //Chama o Modal para Confirmação de exclusão de Alternativa
    containerQuestions.on('click','.btnRemoveAlt',function(){
            var item=$(this).parents('.alternative');

            confirmDeleteModal.find('.modal-title').text("Excluir Alternativa");
            confirmDeleteModal.find('.modal-text').html("Deseja excluir permanentemente a Alternativa?");
            confirmDeleteModal.find('#btnConfirmDelete').off('click').on('click',removeAlternative.bind(item));
            confirmDeleteModal.modal('toggle');
    });

    //Adiciona uma Questão (enquanto n(questions) < 10 )
    function addQuestion(){
        var timestamp = new Date().getTime();

        if(containerQuestions.find('.question').length < 10){
            var alternative = altTemplate.html().replace(/(__checked__)/g, "").replace(/(__altText__)/g,"");
            containerQuestions.append( questionTemplate.html().replace(/(__questionText__)/g,"").replace(/(__alternatives__)/g,(alternative+alternative)).replace(/(__time__)/g,timestamp) );
        }
    }

    //Associa o clique do botão a função de adicionar questão
    $('#btnAddQuestion').on('click',addQuestion);

    //Popula o questionário com questões enviadas pelo Controller
    function populate(test){
        var questions=test.questions;

        $('#titleTest').val(test.title);

        for(var i=0; i<questions.length; i++){
            var alternatives="";
            
            for(var j=0;j<questions[i].alternatives.length;j+=1){
                var checked = "";
                if(questions[i].correct==j){
                    checked="checked";
                }
                alternatives+= altTemplate.html().replace(/(__checked__)/g, checked).replace(/(__altText__)/g,questions[i].alternatives[j]);
            }

            containerQuestions.append( questionTemplate.html().replace(/(__questionText__)/g,questions[i].enunciation).replace(/(__alternatives__)/g,alternatives).replace(/(__time__)/g,i) );
        }
    }

    //Adiciona uma questão logo que a página é carregada
    $(document).ready(function(){
        var inputTest = $('#inputTest');
        if(inputTest.val()!=''){
            populate(JSON.parse(inputTest.val()));

            return 0;
        }

        addQuestion();
    });