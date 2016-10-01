$(document).ready(function() {
   $(document).on('click', '#historialPedidos>tbody>tr>td:nth-child(0), #historialPedidos>tbody>tr>td:nth-child(1), #historialPedidos>tbody>tr>td:nth-child(2), #historialPedidos>tbody>tr>td:nth-child(3)', function(event) {
        var id = $(this).parent().children().first().children().attr('id');
        
        $.ajax({
            url: Routing.generate('admin_recuperar_detalle_pedido'),
            type: 'POST',
            data: {id: id},
            success:function(data){
                if(data.error){
                    swal('',data.error,'error');
                    //id.val(data.id);
                }
                else{
                    var detalle = "";
                    var totalPedido = 0;
                    
                    detalle+='<div class="col-md-4 col-sm-6" style="margin-bottom: 14px;">';
                    detalle+= '<label>Nombre de cliente: </label>';
                    detalle+= '<p>' + data.data[0].cliente + '</p>';
                    detalle+='</div>';
                    detalle+='<div class="col-md-4 col-sm-4" style="margin-bottom: 14px;">';
                    detalle+= '<label>Correo electrónico: </label>';
                    detalle+= '<p>' + data.data[0].email + '</p>';
                    detalle+='</div>';
                    detalle+='<div class="col-md-2 col-sm-2" style="margin-bottom: 14px;">';
                    detalle+= '<label>Teléfono: </label>';
                    detalle+= '<p>' + data.data[0].telefono + '</p>';
                    detalle+='</div>';
//                    detalle+='<div class="col-md-2" style="margin-bottom: 14px;">'
//                              + '<button id="btnRegresar" class="btn btn-sm btn-default pull-right">Regresar</button>'
//                              + '</div>';
                    
                    detalle+='<div class="clearfix"></div>';
                    detalle+='<div class="col-md-6 col-sm-12" style="margin-bottom: 30px;">';
                    detalle+= '<label>Dirección de envío: </label>';
                    detalle+= '<p>' + data.data[0].direccion + '</p>';
                    detalle+='</div>';
                    
                    detalle+='<div class="col-md-2 col-sm-4" style="margin-bottom: 30px;">';
                    detalle+= '<label>Municipio: </label>';
                    detalle+= '<p>' + data.data[0].municipio + '</p>';
                    detalle+='</div>';
                    
                    detalle+='<div class="col-md-2 col-sm-4" style="margin-bottom: 30px;">';
                    detalle+= '<label>Departamento: </label>';
                    detalle+= '<p>'+data.data[0].depto + '</p>';
                    detalle+='</div>';
                    
                    detalle+='<div class="col-md-2 col-sm-4" style="margin-bottom: 30px;">';
                    detalle+= '<label>Fecha: </label>';
                    detalle+= '<p>'+data.data[0].fecha_registro + '</p>';
                    detalle+='</div>';
                    detalle+='<div class="clearfix"></div>';
                    
                    detalle+='<div class="text-center col-md-offset-1 col-md-1 col-sm-1" style="margin-bottom: 1px;">';
                    detalle+= '<label>Cantidad</label>';
                    detalle+='</div>';
                    detalle+='<div class="text-center col-md-4 col-sm-5" style="margin-bottom: 1px;">';
                    detalle+= '<label>Producto</label>';
                    detalle+='</div>';
                    detalle+='<div class="text-center col-md-2 col-sm-3" style="margin-bottom: 1px;">';
                    detalle+= '<label>Precio ($)</label>';
                    detalle+='</div>';
                    detalle+='<div class="text-center col-md-2 col-sm-3" style="margin-bottom: 1px;">';
                    detalle+= '<label>Total ($)</label>';
                    detalle+='</div>';
                    detalle+='<div class="text-center col-md-offset-1 col-md-9 col-sm-12"><hr></div>';
                    detalle+='<div class="clearfix"></div>';
                    
                    $.each( data.data, function( key, value ) {
                        detalle+='<div class="text-right col-md-offset-1 col-md-1 col-sm-1" style="margin-bottom: 1px;">';
                        detalle+= '<p>' + value.cantidad + '</p>';
                        detalle+='</div>';
                        detalle+='<div class="col-md-4 col-sm-5" style="margin-bottom: 1px;">';
                        detalle+= '<p>' + value.producto + ', talla ' + (value.talla).toLowerCase() + ' y en color ' + (value.color).toLowerCase() + '</p>';
                        detalle+='</div>';
                        detalle+='<div class="text-right col-md-2 col-sm-3" style="margin-bottom: 1px;">';
                        detalle+= '<p>' + value.precio + '</p>';
                        detalle+='</div>';
                        detalle+='<div class="text-right col-md-2 col-sm-3" style="margin-bottom: 1px;">';
                        detalle+= '<p>' + (value.cantidad * value.precio).toFixed(2) + '</p>';
                        detalle+='</div>';
                        detalle+='<div class="clearfix"></div>';
                        
                        totalPedido+=value.cantidad * value.precio;
                    });
                    
                    detalle+='<div class="text-right col-md-offset-8 col-md-2 col-sm-offset-10 col-sm-2"><hr></div>';
                    detalle+='<div class="text-right col-md-offset-6 col-md-2 col-sm-offset-6 col-sm-3" style="margin-bottom: 2px;">';
                    detalle+= '<label>Sub-total ($)</label>';
                    detalle+='</div>';
                    detalle+='<div class="text-right col-md-2 col-sm-3" style="margin-bottom: 2px;">';
                    detalle+= '<p>' + (totalPedido).toFixed(2) + '</p>';
                    detalle+='</div>';
                    detalle+='<div class="clearfix"></div>';
                    
                    detalle+='<div class="text-right col-md-offset-6 col-md-2 col-sm-offset-6 col-sm-3" style="margin-bottom: 2px;">';
                    detalle+= '<label>Shipping ($)</label>';
                    detalle+='</div>';
                    detalle+='<div class="text-right col-md-2 col-sm-3" style="margin-bottom: 2px;">';
                    detalle+= '<p>' + data.data[0].valorShipping + '</p>';
                    detalle+='</div>';
                    detalle+='<div class="clearfix"></div>';
                    
                    var total = totalPedido + parseFloat(data.data[0].valorShipping);
                    
                    detalle+='<div class="text-right col-md-offset-6 col-md-2 col-sm-offset-6 col-sm-3" style="margin-bottom: 2px;">';
                    detalle+= '<label>Total ($)</label>';
                    detalle+='</div>';
                    detalle+='<div class="text-right col-md-2 col-sm-3" style="margin-bottom: 2px;">';
                    detalle+= '<p style="font-size: 16px;">' + total + '</p>';
                    detalle+='</div>';
                    detalle+='<div class="clearfix"></div>';
                    
                    $('#mostrarPedido').html(detalle);
       
                    $('#tablaPedidos').hide();
                    $('#detallePedido').removeClass('hide');
                    $('#detallePedido').show();
                    
                    $('#btnRegresar').removeClass('hide');
                    $('#btnRegresar').show();
                }					

            },
            error:function(data){
                if(data.error){
                    swal('',data.error,'error');
                }
            }
        });                
   }); 
   
   $(document).on('click', '#btnRegresar', function(event) {
        $('#detallePedido').hide();
        $('#tablaPedidos').removeClass('hide');
        $('#tablaPedidos').show();
        
        $(this).hide();
   });
});