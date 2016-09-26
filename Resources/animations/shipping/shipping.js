$(document).on('click', '.btAdd', function(event) {
    var id = $('#txtId').val();

    //Cambiar nombre del panel heading para add (Inserción)
    $('.pnHeadingLabelAdd').removeClass('hidden');
    $('.pnHeadingLabelEdit').addClass('hidden');

    $('.deptoLabel').addClass('hidden');
    $('.departamento').removeClass('hidden');
    
    if (id!='') {
            $('#txtId').val('');
            $('#txtValor').val('');
            
            $('#pnAdd').slideDown();
    }
    else{
            $('#pnAdd').slideToggle();
    }
    $('#departamento').focus();
});
