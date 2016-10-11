var i = 0;
var contador = 0;
var totalVenta = 0;

function formatRepo (data) {
    console.log(data);
    if(data.nombre){
        var markup = "<div class='select2-result-repository clearfix'>" +
                     "<div class='select2-result-repository__meta'>" +
                     "<div class='select2-result-repository__title'>" + data.nombre + "</div>" +
                     "</div></div>";
    } else {
        var markup = "Seleccione una opción"; 
    }

    return markup;
}

function formatRepoSelection (data) {
    if(data.nombre){
        return data.nombre;
    } else {
        return "Seleccione una opción"; 
    }    
}

function formatRepoProducto (data) {
    if(data.nombre && (data.disponible == 1)){
        var markup = "<div class='select2-result-repository clearfix'>" +
                     "<div class='select2-result-repository__meta'>" +
                     "<div class='select2-result-repository__title'>" + data.nombre + "</div>" +
                     "</div></div>";
    } else if(data.nombre && (data.disponible == 0)) {
        var markup = "<div class='select2-result-repository clearfix'>" +
                     "<div class='select2-result-repository__meta'>" +
                     "<div class='select2-result-repository__title'>" + data.nombre + " (No disponible)</div>" +
                     "</div></div>";
    } else {
        var markup = "Seleccione una opción"; 
    }

    return markup;
}

function formatRepoSelectionProducto (data) {
    if(data.nombre){
        return data.nombre;
    } else {
        return "Seleccione una opción"; 
    }    
}

function convertirSelect2Producto (i) {
    $('#sProducto-' + i).select2({
        ajax: {
            url: Routing.generate('busqueda_producto_data'),
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
}

function limpiarFormularioVenta () {
    $('#sCliente').val('').change();
    $('#txtName').val('');
    $('#txtLastname').val('');
    $('#txtEmail').val('');
    $('#txtPhone').val('');

    $('.removeRow').html('');
    $('.cantidad').html('');
    $('.producto').html('');
    $('.talla').html('');
    $('.precio').html('');
    $('.total').html('');

    $('.cantidad').append('<input id="txtCantidad-0" type="text" name="cantidad[]" class="cant input-sm form-control text-center validateInput" value="1" min="1">');
    $('.producto').append('<div id="producto-0"><select id="sProducto-0" style="width:100%;" type="text" name="sProducto[]" class="sProducto input-sm form-control validateSelectP"></select></div>');
    $('.talla').append('<div id="talla-0"><select id="sTalla-0" style="width:100%;" type="text" name="sTalla[]" class="input-sm form-control"></select></div>');
    $('.precio').append('<input id="txtPrecio-0" type="text" name="precio[]" class="price input-sm form-control validateInput" style="text-align: right;" value="0">');
    $('.total').append('<div id="total-0"><label class="venta control-label">0.00</label></div>');
    $('.removeRow').append('<button id="deleteProd-0" class="btn removeProd btn-danger hidden"><i class="fa fa-remove"></i></button>');            
    $('.totalVenta').html('0.00');                            
    i = 0;
    contador = 0;
    
    $('#sTalla-0').select2();
    $('#sProducto-0').select2({
        ajax: {
            url: Routing.generate('busqueda_producto_data'),
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
}

$(document).ready(function() {
    $('#sCliente').select2({
        ajax: {
            url: Routing.generate('busqueda_cliente_data'),
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
        templateResult: formatRepo, 
        templateSelection: formatRepoSelection,
        formatInputTooShort: function () {
            return "Enter 1 Character";
        }
    });
    
    $('#sProducto-0').select2({
        ajax: {
            url: Routing.generate('busqueda_producto_data'),
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
    
    $('#sTalla-0').select2();
    
    //Al hacer click muestra el formulario del registro 
    //de una venta en la tienda
    $(document).on('click', '#btnAdd', function(event) {
        $('.pnHeadingLabel').html('Nueva venta');
        
        $('#tablaPedidos').hide();
        $('#registroVenta').removeClass('hide');
        $('#registroVenta').show();
        
        $('#btnRegresar').removeClass('hide');
        $('#btnRegresar').show();
        
        $('#btnSaveAndNew').removeClass('hidden');
        $('#btnSaveAndNew').show();
        $(this).hide();           
    }); 
   
    $(document).on('click', '#btnRegresar', function(event) {
        $('#registroVenta').hide();
        $('#tablaPedidos').removeClass('hide');
        $('#tablaPedidos').show();
        
        $('#btnSaveAndNew').removeClass('hidden');
        $('#btnAdd').show();
        $(this).hide();
        
        limpiarFormularioVenta();
    });
   
    $("input[name='cliente-opt']").change(function(){
        if($(this).val() == 1){
            $('#existente').removeClass('hidden');
            $('#nuevoCliente').addClass('hidden');
            
            $('#txtName').removeClass('validateInput');
            $('#txtLastname').removeClass('validateInput');
            $('#txtEmail').removeClass('validateInput');
            $('#txtPhone').removeClass('validateInput');
            
            $('#sCliente').addClass('validateSelect');
                        
        } else {
            $('#nuevoCliente').removeClass('hidden');
            $('#existente').addClass('hidden');
            
            $('#txtName').addClass('validateInput');
            $('#txtLastname').addClass('validateInput');
            $('#txtEmail').addClass('validateInput');
            $('#txtPhone').addClass('validateInput');
            
            $('#sCliente').removeClass('validateSelect');
        }
    });
    
    $(document).on('click', '#btnNuevaFila', function(event) {
        i++;
        contador++;
        
        $('.cantidad').append('<input id="txtCantidad-' + i + '" type="text" name="cantidad[]" class="cant input-sm form-control text-center validateInput" value="1" min="1" style="margin-top:5px;">');
        $('.producto').append('<div id="producto-' + i + '" style="margin-top:6px;"><select id="sProducto-' + i + '" style="width:100%;" type="text" name="sProducto[]" class="sProducto input-sm form-control validateSelectP"></select></div>');
        $('.talla').append('<div id="talla-' + i + '" style="margin-top:6px;"><select id="sTalla-' + i + '" style="width:100%;" type="text" name="sTalla[]" class="input-sm form-control"></select></div>');
        $('.precio').append('<input id="txtPrecio-' + i + '" type="text" name="precio[]" class="price input-sm form-control validateInput" style="margin-top:5px; text-align: right;" value="0">');
        $('.total').append('<div id="total-' + i + '" style="margin-top: 10px;"><label class="venta control-label">0.00</label></div>');
        
        if(contador > 1) {
            $('.removeRow').append('<button id="deleteProd-' + i + '" class="btn removeProd btn-danger" style="margin-top:7px;"><i class="fa fa-remove"></i></button>');            
        } else {
            if(i == 1) {
                $('#deleteProd-0').removeClass('hidden');
                $('#deleteProd-0').show();
            } else if(contador == 1) {
                $('.removeProd').each(function( index, value ) { 
                    $('#' + $(this).attr('id')).removeClass('hidden');
                });
            }
            
            $('.removeRow').append('<button id="deleteProd-' + i + '" class="btn removeProd btn-danger" style="margin-top:7px;"><i class="fa fa-remove"></i></button>');
        }
        
        $('.cant').numeric('.'); 
        $('.price').numeric('.'); 
        $('#sTalla-' + i).select2();
        $('#sProducto-' + i).select2({
            ajax: {
                url: Routing.generate('busqueda_producto_data'),
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
    });
    
    $(document).on('click', '.removeProd', function(event) {
        var numDel = $(this).attr('id');
        var numDelArray = numDel.split('-');
        var corr = $(this).attr('id');
        var corrArray = corr.split('-');
        var totalProducto = parseFloat($('#total-' + corrArray[1]).children().html());
        var totalVenta = parseFloat($('.totalVenta').html());   
        contador--;
        
        $('.totalVenta').html((totalVenta - totalProducto).toFixed(2));          
        $('#txtCantidad-' + numDelArray[1]).remove();
        $('#producto-' + numDelArray[1]).remove();
        $('#talla-' + numDelArray[1]).remove();
        $('#txtPrecio-' + numDelArray[1]).remove();
        $('#total-' + numDelArray[1]).remove();
        $('#deleteProd-' + numDelArray[1]).remove();
                
        if(contador == 0) {
            $('.removeProd').each(function( index, value ) { 
               $('#' + $(this).attr('id')).addClass('hidden');
            });
        }
        
        return false;
    });
    
    $(document).on('change', '.sProducto', function(event) {
        var corr = $(this).attr('id');
        var corrArray = corr.split('-');
        var id = $(this).val();
        var cantidad = $('#txtCantidad-' + corrArray[1]).val();
        var venta = 0;
        
        $.ajax({
            url: Routing.generate('admin_recuperar_detalle_producto'),
            type: 'POST',
            data: {id: id},
            success:function(data){
                if(data.error){
                    swal('',data.error,'error');
                }
                else{
                    var totalProducto = data.data[0].precio * cantidad;
                    var $talla = $('#sTalla-' + corrArray[1]);                    
                    var optionsAsString = "";
                    $talla.html(''); 
                    
                    $.each(data.data, function( key, value ) {
                        optionsAsString += "<option value='" + value.tallaid + "'>" + value.talla + "</option>";
                    });
                    
                    $talla.append(optionsAsString);
                    $talla.select2();
                   
                   $('#txtPrecio-' + corrArray[1]).val(data.data[0].precio);
                   $('#total-' + corrArray[1]).html('<label class="venta control-label">' + (totalProducto).toFixed(2) + '</label>');
                   
                   $('.venta').each(function( index, value ) { 
                        if(!isNaN(parseFloat($(this).html()))) {
                            venta+=parseFloat($(this).html()); 
                        }
                    });
                    
                    $('.totalVenta').html((venta).toFixed(2));          
                }
            },
            error:function(data){
                if(data.error){
                    swal('',data.error,'error');
                }
            }
        }); 
    });
    
    $(document).on('input', '.cant', function(event) {
        var corr = $(this).attr('id');
        var corrArray = corr.split('-');
        var venta = 0;
        var cantidad = $(this).val();
        var precio = $('#txtPrecio-' + corrArray[1]).val();
        var totalProducto = parseFloat(precio) * parseFloat(cantidad);
        
        if(!isNaN(totalProducto)) {
            $('#total-' + corrArray[1]).html('<label class="venta control-label">' + (totalProducto).toFixed(2) + '</label>');
        } 
        
        $('.venta').each(function( index, value ) { 
            if(!isNaN(parseFloat($(this).html()))) {
                venta+=parseFloat($(this).html()); 
            }
        });

        $('.totalVenta').html((venta).toFixed(2));    
    });
    
    $(document).on('input', '.price', function(event) {
        var corr = $(this).attr('id');
        var corrArray = corr.split('-');
        var venta = 0;
        var precio = $(this).val();
        var cantidad = $('#txtCantidad-' + corrArray[1]).val();
        var totalProducto = parseFloat(precio) * parseFloat(cantidad);
        
        if(!isNaN(totalProducto)) {
            $('#total-' + corrArray[1]).html('<label class="venta control-label">' + (totalProducto).toFixed(2) + '</label>');
        } 
        
        $('.venta').each(function( index, value ) { 
            if(!isNaN(parseFloat($(this).html()))) {
                venta+=parseFloat($(this).html()); 
            }
        });

        $('.totalVenta').html((venta).toFixed(2));    
    });
});