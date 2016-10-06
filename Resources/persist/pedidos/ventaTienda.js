var accion = 0;

$(document).ready(function() {
    $(document).on('click', '#btnSave', function(event) {
        accion = 1;
    });
    
    $(document).on('click', '#btnSaveAndNew', function(event) {
        accion = 2;
    });
    
    $('#frmVentaTienda').on('submit',(function(event) {
        
        event.preventDefault();
        var table = $('#ventasTienda').DataTable();
        var errores = 0;
        var $btn;
        var msg = '';
        
        if(accion == 1) {
            $btn = $('#btnSave').button('loading');
            $('#btnSaveAndNew').addClass('hidden');
        } else {
            $btn = $('#btnSaveAndNew').button('loading');
            $('#btnSave').addClass('hidden');
        }
        
        if(accion != 0) {
            $('.validateSelectP').each(function() {
                if (!requiredSelect($(this))) {
                    $(this).next().children().children().addClass('errorform');
                    errores++;
                    msg ='¡Se debe de seleccionar una opcion en los campos de producto!';
                }
            });
            
            $('.validateInput').each(function() {
                if (!valorRequired($(this))) {
                    $(this).addClass('errorform');
                    errores++;
                    msg ='¡Campos en rojo, son requeridos!';
                }
            });
            
            $('.validateSelect').each(function() {
                
                if (!requiredSelect($(this)) && $('#cli1').is(':checked')) {
                    $(this).next().children().children().addClass('errorform');
                    errores++;
                    msg ='¡Debe de seleccionar a un cliente para el registro de la venta!';
                }
            });

            if (errores==0) {
                $.ajax({
                    url: Routing.generate('admin_registro_venta_tienda'),
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (response)
                    {
                        if(!response.msg.error){
                            swal('', response.msg.msg,'success');
                            table.ajax.reload();
                            $btn.button('reset');
                            limpiarFormularioVenta();
                            
                            if(accion == 1) {
                                $('#registroVenta').hide();
                                $('#tablaPedidos').removeClass('hide');
                                $('#tablaPedidos').show();

                                $('#btnAdd').show();
                                $('#btnRegresar').hide();
                                
                                $('#btnSaveAndNew').removeClass('hidden');
                            } else {
                                $('#btnSave').removeClass('hidden');
                            }
                            
                            accion = 0;
                        } else {
                            swal('', response.msg.error, 'error');

                            if(accion == 1) {
                                $('#btnSaveAndNew').removeClass('hidden');
                            } else {
                                $('#btnSave').removeClass('hidden');
                            }

                            $btn.button('reset');
                            accion = 0;
                        }                    

                        return false;
                    },
                    error:function(response){
                        if(response.msg.error){
                            swal('', response.msg.error, 'error');
                        }

                        if(accion == 1) {
                            $('#btnSaveAndNew').removeClass('hidden');
                        } else {
                            $('#btnSave').removeClass('hidden');
                        }

                        $btn.button('reset');
                        accion = 0;
                    }
                });
            } else {
                swal('', msg, 'error');

                if(accion == 1) {
                    $('#btnSaveAndNew').removeClass('hidden');
                } else {
                    $('#btnSave').removeClass('hidden');
                }

                $btn.button('reset');
                accion = 0;
            }
        } else {
            $btn.button('reset');
            $('#btnSave').removeClass('hidden');
            accion = 0;
        }
        
        event.preventDefault();
        return false;
    }));
    
//    $(document).on('click', '#ventasTienda>tbody>tr', function(event) {
//        var text = $(this).prop('tagName');
//        var id=$(this).first().children().children().attr('id');
//        var idForm=$('#txtId').val();
//        
//        $('.pnHeadingLabel').html('Actualización de información de la venta');
//        $('#tablaPedidos').hide();
//        $('#registroVenta').removeClass('hide');
//        $('#registroVenta').show();
//        
//        $('#btnRegresar').removeClass('hide');
//        $('#btnRegresar').show();
//        $('#btnAdd').hide();
//        $('#btnSaveAndNew').addClass('hidden');
//        
//        if (text=='TR' && id!=idForm) {
//            $.ajax({
//                url: Routing.generate('admin_recuperar_detalle_ventatienda'),
//                type: 'POST',
//                data: {id: id},
//                success:function(data){
//                    if(data.error){
//                        swal('',data.error,'error');
//                        id.val(data.id);
//                    }
//                    else{
//                        var ventaTotal = 0;
//                        var optionsAsString = "<option value='" + data.venta[0].clienteid + "'>" + data.venta[0].cliente + "</option>";
//                        $('#sCliente').append(optionsAsString);
//                        
//                        i=0;
//                        contador=0;
//                        
//                        //$("#sCliente").select2('val', data.venta[0].clienteid, true).trigger('change');
//                        
//                        $('#sCliente').val(data.venta[0].clienteid).change().trigger("change");
//                        $('#txtId').val(data.venta[0].id_venta);
//                        
//                        $(data.venta).each(function( index, value ) {                
//                            var montoProducto = parseFloat(value.precio) * parseInt(value.cantidad);
//                            ventaTotal+=montoProducto;
//                            
//                            if(index > 0){
//                                $('.cantidad').append('<input id="txtCantidad-' + i + '" type="text" name="cantidad[]" class="cant input-sm form-control text-center validateInput" value="' + value.cantidad + '" min="1" style="margin-top:5px;">');
//                                $('.producto').append('<div id="producto-' + i + '" style="margin-top:6px;"><select id="sProducto-' + i + '" style="width:100%;" type="text" name="sProducto[]" class="sProducto input-sm form-control validateSelectP"></select></div>');
//                                $('.talla').append('<div id="talla-' + i + '" style="margin-top:6px;"><select id="sTalla-' + i + '" style="width:100%;" type="text" name="sTalla[]" class="input-sm form-control"></select></div>');
//                                $('.precio').append('<input id="txtPrecio-' + i + '" type="text" name="precio[]" class="price input-sm form-control validateInput" style="margin-top:5px; text-align: right;" value="' + parseFloat(value.precio).toFixed(2) + '">');
//                                $('.total').append('<div id="total-' + i + '" style="margin-top: 10px;"><label class="venta control-label">' + (montoProducto).toFixed(2) + '</label></div>');
//                                $('.removeRow').append('<button id="deleteProd-' + i + '" class="btn removeProd btn-danger" style="margin-top:7px;"><i class="fa fa-remove"></i></button>');   
//                                
//                                
//                                $('#sTalla-' + i).select2();
//                                $('#sProducto-' + i).select2({
//                                    ajax: {
//                                        url: Routing.generate('busqueda_producto_data'),
//                                        dataType: 'json',
//                                        delay: 250,
//                                        data: function (params) {
//                                            return {
//                                                q: params.term, 
//                                                page: params.page
//                                            };
//                                        },
//                                        processResults: function (data, params) {
//                                            var select2Data = $.map(data.data, function (obj) {
//                                                obj.id = obj.objid;
//                                                obj.text = obj.nombre;
//
//
//                                                if(obj.disponible == 0) {
//                                                    obj.disabled = true;
//                                                } 
//
//                                                return obj;
//                                            });
//
//                                            return {
//                                                results: select2Data
//                                            };
//                                        },
//                                        cache: true
//                                    },
//                                    escapeMarkup: function (markup) { return markup; }, 
//                                    minimumInputLength: 1,
//                                    templateResult: formatRepoProducto, 
//                                    templateSelection: formatRepoSelectionProducto,
//                                    formatInputTooShort: function () {
//                                        return "Enter 1 Character";
//                                    }
//                                });   
//                                
//                                $('#sProducto').val(value.productoId).change().trigger("change");
//                            } else {
//                                $('#txtCantidad-' + i).val(value.cantidad);
//                                $('#txtPrecio-' + i).val(parseFloat(value.precio).toFixed(2));
//                                $('#total-' + i).html('<label class="venta control-label">' + (montoProducto).toFixed(2) + '</label>');
//                            }
//                            
//                            i++;
//                            contador++;
//                        });
//                        
//                        if(contador > 1){
//                            $('.removeProd').removeClass('hidden');
//                        }
//                        
//                        contador--;
//                        i--;
//                        $('.totalVenta').html((ventaTotal).toFixed(2));
//                        $('.cant').numeric('.'); 
//                        $('.price').numeric('.'); 
//                    }					
//
//                },
//                error:function(data){
//                    if(data.error){
//                        swal('',data.error,'error');
//                    }
//                }
//            });
//        }
//    });
});