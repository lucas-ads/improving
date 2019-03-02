$(document).ready(function(){
    $("#field-search").on('focus',function(){
        $("#field-search").parent().addClass('focus');
    });

    $("#field-search").on('focusout',function(){
        $("#field-search").parent().removeClass('focus');
    });

    $('input.custom-file-input.png').on('change', function(){
        var input = $('input.custom-file-input')[0];
        var label = $('label.custom-file-label');
        if(input.files[0].type=="image/png"){
            label.text(input.files[0].name);
            label.removeClass('text-danger');
        }else{
            label.text('Somente imagem PNG');
            label.addClass('text-danger');
            input.value="";
        }
    });
});