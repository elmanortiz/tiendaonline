/// Funcion que realiza acciones al hacer click en un checkbox 
function seleccionarChk(){
    /////Definición de variables
    var contador = 0;
    var totalchk = $('.chkItem').length;
    
    //Si el checkbox se ha seleccionado
    if ($(this).is(':checked')) {
        $('.btAdd').addClass('hidden');
        $('.btDelete').removeClass('hidden');

        //Se obtiene el numero de checkboxes que se ha seleccionado
        $('.chkItem').each( function() {			
            if ($(this).is(':checked')) {
                contador++;
            }
        });
        
        //Si se han seleccionado todos los checkboxes
        if(contador == totalchk){
            $("input[name=checktodos]").prop({'checked': true});
        }
    //Si el checkbox se ha deseleccionado
    } else {
        $("input[name=checktodos]").prop({'checked': false});
        
        //Se obtiene el numero de checkboxes que se ha seleccionado
        $('.chkItem').each( function() {			
            if ($(this).is(':checked')) {
                contador++;
            }
        });
        
        //Si no se ha seleccionado ningun los checkbox
        if(contador == 0){
            $('.btAdd').removeClass('hidden');
            $('.btDelete').addClass('hidden');
        }
    }
}

/// Funcion que seleciona y deselecciona todos los checkboxes de la tabla
function seleccionarTodo() {	
    /////Definición de variables
    var id=$(this).children().first().children().attr('id');
    
    //$('#txtId').val('');
    //$('#txtName').val('');
    $('#pnAdd').slideUp();
    
    //Se recorren todos los checkboxes
    $('input[type=checkbox]').each( function() {	
        //Si se ha seleccionado el checkbox con nombre checktodos
        if($("input[name=checktodos]:checked").length == 1){
            $('.btAdd').addClass('hidden');
            $('.btDelete').removeClass('hidden');
            $(this).prop({'checked': true});
        //Si se ha deseleccionado el checkbox con nombre checktodos
        } else {
           $('.btAdd').removeClass('hidden');
            $('.btDelete').addClass('hidden');
            $(this).prop({'checked': false}); 
        }
    });	       
}

function convertirASelect2Ajax(id, ruta){
    $('#' + id).select2({
        ajax: {
            url: Routing.generate(ruta),
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, 
                    page: params.page
                };
            },
            processResults: function (data, params) {
                var select2Data = $.map(data.data, function (obj) {
                    obj.id = obj.objid;
                    obj.text = obj.nombre;


                    if(obj.disponible == 0) {
                        obj.disabled = true;
                    } 

                    return obj;
                });

                return {
                    results: select2Data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, 
        minimumInputLength: 1,
        templateResult: formatRepoProducto, 
        templateSelection: formatRepoSelectionProducto,
        formatInputTooShort: function () {
            return "Enter 1 Character";
        }
    });  
    
    return 0;
}

