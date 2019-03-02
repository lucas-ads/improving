        var unitTemplate = $('#unitTemplate').html();
        var itemTemplate = $('#itemTemplate').html();
        var unitModal = $('#unitModal');
        var confirmDeleteModal = $('#confirmDeleteModal');
        var itemModal = $('#itemModal');
        var containerUnits = $('#containerUnits');
        var xcsrf = $('meta[name="csrf-token"]').attr('content');

        //#################################################################################################################################### Test ########################################################################################################

        containerUnits.on('click','.btnAddTest',function(event){
            var url = $(this).attr('data-url');
            $(location).attr('href', url);
        });

        //#####################################################################################################################################  VideoLesson & Links #######################################################################################

        //Função que adiciona uma VideoLesson/Link (AJAX)
        function addVideoLessonOrLink(){
            var title = itemModal.find('#inputTitleItem').val();
            var urlVideoLesson = itemModal.find('#inputUrlItem').val();
            var url = $(this.item).attr('data-url');
            var listItems = $(this.item).parents('.card-unit').find('.listItemsUnit');
            var type = this.type;

            $.ajax({
                url: url,
                dataType: 'json',
                type: 'post',
                data: {
                    title: title,
                    url: urlVideoLesson,
                    type: type
                },
                headers: {
                    'X-CSRF-TOKEN': xcsrf
                },
                success: function(result){
                    if(result.status == true){
                        var typeText;
                        switch (type) {
                            case 1:
                                typeText="videolesson";
                                break;
                            case 2:
                                typeText="link";
                                break;
                            default:
                                break;
                        }

                        var item = itemTemplate
                            .replace(/(__itemType__)/g , typeText)
                            .replace(/(__itemId__)/g, result.id)
                            .replace(/(__itemResource__)/g, urlVideoLesson)
                            .replace(/(__itemTitle__)/g, title);
                        
                        listItems.append(item);
                        
                        itemModal.modal('toggle');
                    }else{
                        itemModal.find('#msgErrorItem').text(result.erro);
                    }
                    console.log(result);
                },
                error:function(result){
                    console.log(result);
                }
            });
            
        }

        //Função que edita a VideoLesson/Link (AJAX)
        function editVideoLessonOrLink(){
            var title = itemModal.find('#inputTitleItem').val();
            var urlVideoLesson = itemModal.find('#inputUrlItem').val();
            var url = itemModal.attr('data-edit-url')+"/"+$(this).attr('data-id');
            var item = this;
            
            $.ajax({
                url: url,
                dataType: 'json',
                type: 'put',
                data: {
                    title: title,
                    url: urlVideoLesson
                },
                headers: {
                    'X-CSRF-TOKEN': xcsrf
                },
                success: function(result){
                    if(result.status == true){
                        $(item).attr('data-resource',urlVideoLesson);
                        $(item).find('.itemTitle span').text(title);
                        itemModal.modal('toggle');
                    }else{
                        itemModal.find('#msgErrorItem').text(result.erro);
                    }
                    console.log(result);
                },
                error:function(result){
                    console.log(result);
                }
            });
        }

        //Inicializa o Modal de VideoLesson/Link de acordo com os argumentos recebidos
        function initModalVideoLesson(title,inputTitle,inputUrl,labelTitle,labelUrl,btnText,fn){
            itemModal.find('.modal-title').text(title);
            itemModal.find('label[for="inputTitleItem"]').text(labelTitle);
            itemModal.find('label[for="inputUrlItem"]').text(labelUrl);
            itemModal.find('#inputTitleItem').val(inputTitle);
            itemModal.find('#inputUrlItem').val(inputUrl);
            itemModal.find('#btnModalItem').text(btnText).off('click').on('click',fn);
            itemModal.find('#msgErrorItem').text("");

            itemModal.modal('toggle');
        }

        //Chama a função de inicialização do Modal de VideoLesson/Link passando os parametros para a operação de Edição de Videoaula
        containerUnits.on('click','.btnModelEditvideolesson',function(event){
            var item = $(this).parents('.item');
            var title = item.find('.itemTitle span').text();
            initModalVideoLesson("Editar Videoaula",title,item.attr('data-resource'),"Título da Aula:","URL de Incorporação:","Salvar",editVideoLessonOrLink.bind(item));
        });

        //Chama a função de inicialização do Modal de VideoLesson/Link passando os parametros para a operação de Edição de Link
        containerUnits.on('click','.btnModelEditlink',function(event){
            var item = $(this).parents('.item');
            var title = item.find('.itemTitle span').text();
            initModalVideoLesson("Editar Material Complementar",title,item.attr('data-resource'),"Título do Material:","URL do Material:","Salvar",editVideoLessonOrLink.bind(item));
        });

        //Chama a função de inicialização do Modal de VideoLesson/Link passando os parametros para a operação de Adição de Videoaula
        containerUnits.on('click','.btnModelAddVideoLesson',function(event){
            initModalVideoLesson("Adicionar Videoaula", "","","Título da Aula:","URL de Incorporação:","Adicionar",addVideoLessonOrLink.bind({item: this,type: 1}));
        });

        //Chama a função de inicialização do Modal de VideoLesson/Link passando os parametros para a operação de Adição de Link
        containerUnits.on('click','.btnModelAddLink',function(event){
            initModalVideoLesson("Adicionar Material Complementar", "","","Título do Material:","URL do Material:","Adicionar",addVideoLessonOrLink.bind({item: this,type: 2}));
        });

        //Redireciona para a tela de edição de Atividade
        containerUnits.on('click','.btnModelEdittest',function(event){
            //console.dir(event.target);
            var el = $(event.target);
            if(el.hasClass('btnModelEdittest')){
                window.location.href=el.attr('url-edit');
            }else{
                window.location.href=el.parent().attr('url-edit');
            }
            
        });

        //Configura no Modal de VideoLesson/Link o foco no input #title
        itemModal.on('shown.bs.modal', function () {
            $('#inputTitleItem').trigger('focus')
        });

        //Exclui o item
        function removeItem(){
            var item = this;
            var idItem = this.attr('data-id');
            var url = this.attr('data-url-remove');

            $.ajax({
                url: url,
                dataType: 'json',
                type: 'delete',
                headers: {
                    'X-CSRF-TOKEN': xcsrf
                },
                success: function(result){
                    if(result.status == true){
                        console.log(result);
                        item.remove();
                        confirmDeleteModal.modal('toggle');
                    }
                },
                error:function(result){
                    console.log(result);
                }
            });
        }

        //Chama o Modal para Confirmação de exclusão do Item
        containerUnits.on('click','.btnModalDeleteItem',function(){
            var item=$(this).parents('.list-group-item');

            confirmDeleteModal.find('.modal-title').text("Excluir Item");
            confirmDeleteModal.find('.modal-text').html("Deseja excluir permanentemente o Item <strong>\""+item.find('div.itemTitle span').text()+"\"</strong>?");
            confirmDeleteModal.find('#btnConfirmDelete').off('click').on('click',removeItem.bind(item));
            confirmDeleteModal.modal('toggle');
        });

        //#####################################################################################################################################  Units #######################################################################################

        //Exclui a unidade
        function removeUnit(){
            var idUnit = this.attr('data-id');
            var url = unitModal.attr('data-url')+'/'+idUnit;
            
            $.ajax({
                url: url,
                dataType: 'json',
                type: 'delete',
                headers: {
                    'X-CSRF-TOKEN': xcsrf
                },
                success: function(result){
                    if(result.status == true){
                        console.log(result);
                        $('#unit-'+idUnit).remove();
                        confirmDeleteModal.modal('toggle');
                    }
                },
                error:function(result){
                    console.log(result);
                }
            });
        }

        //Chama o Modal para Confirmação de exclusão da Unidade
        containerUnits.on('click','.btnModalDeleteUnit',function(){
            var unidade=$(this).parents('.card-unit');
            confirmDeleteModal.find('.modal-title').text("Excluir Unidade");
            confirmDeleteModal.find('.modal-text').html("Deseja excluir permanentemente a Unidade <strong>\""+unidade.find('div.title span').text()+"\"</strong> e todos os itens vinculados a mesma?");
            confirmDeleteModal.find('#btnConfirmDelete').off('click').on('click',removeUnit.bind(unidade));
            confirmDeleteModal.modal('toggle');
        });

        //Função que adiciona a unidade (AJAX)
        function addUnit(){
            var title = unitModal.find('#inputTitleUnit').val();
            var url = unitModal.attr('data-url');
            $.ajax({
                url: url,
                dataType: 'json',
                type: 'post',
                data: {
                    title: title
                },
                headers: {
                    'X-CSRF-TOKEN': xcsrf
                },
                success: function(result){
                    if(result.status == true){
                        containerUnits.append(unitTemplate.replace(/(__idUnit__)/g,result.id).replace(/(__titleUnit__)/g,title));
                        doSort();
                        unitModal.modal('toggle');
                    }else{
                        unitModal.find('#msgErrorUnit').text(result.erro);
                    }
                },
                error:function(result){
                    console.log(result);
                }
            });
        }

        //Função que edita a unidade (AJAX)
        function editUnit(){
            var title = unitModal.find('#inputTitleUnit').val();
            var url = unitModal.attr('data-url')+"/"+this;
            var idUnit = this;
            $.ajax({
                url: url,
                dataType: 'json',
                type: 'put',
                data: {
                    title: title
                },
                headers: {
                    'X-CSRF-TOKEN': xcsrf
                },
                success: function(result){
                    if(result.status == true){
                        $('#unit-'+idUnit).find('.card-header .title').text(title);
                        unitModal.modal('toggle');
                    }else{
                        unitModal.find('#msgErrorUnit').text(result.erro);
                    }
                    console.log(result);
                },
                error:function(result){
                    console.log(result);
                }
            });
            
        }

        //Inicializa o Modal de Unidade de acordo com os argumentos recebidos
        function initModalUnit(title,inputText,btnText,fn){
            unitModal.find('#unit-modal-title').text(title);
            unitModal.find('#inputTitleUnit').val(inputText);
            unitModal.find('#btnUnit').text(btnText).off('click').on('click',fn);
            unitModal.find('#msgErrorUnit').text("");
            unitModal.modal('toggle');
        }

        //Configura no Modal de Unidade o foco no input #title
        unitModal.on('shown.bs.modal', function () {
            $('#inputTitleUnit').trigger('focus')
        });

        //Chama a função de inicialização do Modal de Unidade passando os parametros para a operação de Criação
        $('#btnModalAddUnit').on('click',function(){
            initModalUnit("Adicionar Unidade", "","Adicionar",addUnit);
        });

        //Chama a função de inicialização do Modal de Unidade passando os parametros para a operação de Edição
        containerUnits.on('click','.btnModalEditUnit',function(){
            var title = $(this).parents('.card-header').find('div.title>span').text();
            var id = $(this).attr('data-id');

            initModalUnit("Editar Unidade", title,"Salvar",editUnit.bind(id));
        });

        //Update position of units
        $( function() {
            containerUnits.sortable({
                handle: ".btnMoveUnit",
                grid: [2000,1],
                update: function (event, ui) {
                    var unitsId = Array();
                    $('#containerUnits .card-unit').each(function(){
                        unitsId.push($(this).attr('data-id'));
                    });
                    
                    $.ajax({
                        url: containerUnits.attr('data-url-orderUnits'),
                        dataType: 'json',
                        method: 'put',
                        data: {
                            units: unitsId
                        },
                        headers: {
                            'X-CSRF-TOKEN': xcsrf
                        },
                        success: function(result){
                            console.log(result);
                        },
                        error:function(result){
                            console.log(result);
                        }
                    });
                }
            });
        } );

        //Update position of items
        var trafficLock=false;
        function doSort(){
            $('.listItemsUnit').sortable({
                grid: [2000,1],
                connectWith: ".listItemsUnit",
                handle: ".btnMoveItem",
                update: function(e){
                    if(trafficLock==false){
                        trafficLock=true;

                        var unitsItems = Array();
                        $('#containerUnits .card-unit').each(function(){
                            let items=Array();
                            $(this).find('.listItemsUnit .item').each(function(){
                                items.push($(this).attr('data-id'));
                            });
                            unitsItems.push(items);
                        });

                        $.ajax({
                            url: containerUnits.attr('data-url-orderItems'),
                            dataType: 'json',
                            method: 'put',
                            data: {
                                unitsItems: unitsItems
                            },
                            headers: {
                                'X-CSRF-TOKEN': xcsrf
                            },
                            success: function(result){
                                console.log(result);
                                trafficLock=false;
                            },
                            error:function(result){
                                console.log(result);
                                trafficLock=false;
                            }
                        });
                    }
                }
            });
        }

        $(document).ready(function() {
            doSort();
        });