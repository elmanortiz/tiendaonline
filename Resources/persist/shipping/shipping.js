$(document).ready(function() {
    $("input[name=checktodos]").prop({'checked': false});
    
    $(document).on('click', '#btnCancel', function(event) {
//        $('.btAdd').click();
        $('#pnAdd').slideToggle();
    });
    
    $(document).on('click', '#btnSave', function(event) {
        var btn = $(this).button('loading');
        var id=$('#txtId');
        var valor=$('#txtValor');
        var depto=$('#departamento');
        var table = $('#listadoShipping').DataTable();
        var errores = 0; //Contador de errores, para antes de la persistencia

        $('.validateInput').each(function() {
            console.log($(this).val());
            
            if (!valorRequired($(this))) {
                errores++;
            }
        });

        if (errores==0) {
            var data = {
                        id: id.val(),
                        valor : valor.val(),
                        depto : depto.val()
                    };

            $.ajax({
                data: data,
                url: Routing.generate('admin_registro_shipping'),
                type: 'POST',
                dataType: 'json',
                success: function (response)
                {
                    console.log(response.msg.error);
                    
                    if(!response.msg.error){
                        swal('', response.msg.msg,'success');
                        $('#txtId').val('');
                        $('#txtValor').val('');
                        btn.button('reset');
                        table.ajax.reload();
                        $('.btAdd').click();
                    } else {
                        swal('', response.msg.error, 'error');
                        btn.button('reset');
                    }                    
                    
                    return false;
                },
                error:function(response){
                    if(response.msg.error){
                        swal('', response.msg.error, 'error');
                    }
                    btn.button('reset');
                }
            });
            
            return false;        
        }
        else {
            swal('','¡Campos en rojo, son requeridos!','error');
            btn.button('reset');
        }                
       
    });     
    
    /////Persist datatable (Edit method)
    $(document).on('click', '#listadoShipping>tbody>tr>td:nth-child(1), #listadoShipping>tbody>tr>td:nth-child(2)', function(event) {
        /////Definición de variables
        var text = $(this).prop('tagName');
        var id=$(this).parent().children().first().children().attr('id');
        var idForm=$('#txtId').val();
        
        //Cambiar nombre del panel heading para Modify
        $('.pnHeadingLabelAdd').addClass('hidden');
        $('.pnHeadingLabelEdit').removeClass('hidden');
        
        if (text=='TD' && id!=idForm) {
            $.ajax({
                url: Routing.generate('admin_recuperar_shipping'),
                type: 'POST',
                data: {id: id},
                success:function(data){
                    if(data.error){
                        swal('',data.error,'error');
                        id.val(data.id);
                    }
                    else{
                        $('#txtId').val(data.id);
                        $('#txtValor').val(data.valor);
                        $('.deptoLabel').html(data.depto);
                        
                        $('.deptoLabel').removeClass('hidden');
                        $('.departamento').addClass('hidden');
                        
                        $('#pnAdd').slideDown();
                    }					

                },
                error:function(data){
                    if(data.error){
                        swal('',data.error,'error');
                    }
                }
            });
        } 
        else {

        }					
    });
    /////Fin definición persist data (Edit method)
    
    $(document).on('click', '.chkItem', function(event) {
        var contador = 0;
        var totalchk = $('.chkItem').length;
        
        $('#txtId').val('');
        $('#txtName').val('');
        $('#pnAdd').slideUp();
        
        if ($(this).is(':checked')) {
            $('.btAdd').addClass('hidden');
            $('.btDelete').removeClass('hidden');
            
            $('.chkItem').each( function() {			
                if ($(this).is(':checked')) {
                    contador++;
                }
            });
            
            if(contador == totalchk){
                $("input[name=checktodos]").prop({'checked': true});
            }
        } else {
            $("input[name=checktodos]").prop({'checked': false});
            
            $('.chkItem').each( function() {			
                if ($(this).is(':checked')) {
                    contador++;
                }
            });
            
            if(contador == 0){
                $('.btAdd').removeClass('hidden');
                $('.btDelete').addClass('hidden');
            }
        }
    });
    
    /////Persist datatable (Delete method)
    $(document).on('click', '.btDelete', function(event) {
        var btn = $(this).button('loading');
        
        swal({
            text: "¿Desea realmente eliminar los registros seleccionados?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#1D234D",
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar"
        }).then(function(isConfirm) {
            if (isConfirm) {
                console.log('if');
                /////Definición de variables
                var id=$(this).children().first().children().attr('id');
                var ids=[];
                var table = $('#listadoShipping').DataTable();
                $('.chkItem').each(function() {
                    if ($(this).is(':checked')) {
                        ids.push($(this).parent().attr('id'));
                    }
                });	
                
                $.ajax({
                    url: Routing.generate(''),
                    type: 'POST',
                    data: {param1: ids},
                    success:function(data){
                        if(data.error){
                            swal('',data.error,'error');
                        }
                        else{
                            $('#txtId').val(data.id);
                            $('#txtName').val(data.name);
                            $("input[name=checktodos]").prop({'checked': false});
                            
                            $('.btAdd').removeClass('hidden');
                            $('.btDelete').addClass('hidden');
                            
                            swal('', data.msg,'success');
                            btn.button('reset');
                            table.ajax.reload();
                        }

                        $('#pnAdd').slideUp();
                    },
                    error:function(data){
                        if(data.error){
                            swal('',data.error,'error');
                            btn.button('reset');
                        }
                    }
                });

                
            } else {
                console.log('else');
                btn.button('reset');
            }
        });
    });
    /////Fin definición persist data (Delete method)
    
    $(document).on("input", "#txtValor", function(){
        var valor = $(this).val();
        var error = document.getElementById('error');

        if(valor < 0){
            error.style.display = 'block';
            $('#btnSave').hide();
        } else {
            error.style.display = 'none';
            $('#btnSave').show();
        }
    });
});