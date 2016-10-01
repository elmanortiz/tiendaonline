    $(document).ready(function() { 
        $('#id').val(-1);
        $( "#btnCancel" ).click(function() {                
            $('#pnAdd').slideToggle();
            $('#txtName').val(''); 
            $('#id').val(-1);
        });            

        $(document).on("click","#btnSave", function() {               
            var id = $('#id').val();
            
            if($('#id').val() === '-1'){//Esto es cuando es edicion
                console.log('Si es igual que menos uno');
                var $btn = $(this).button('loading');
                var errores = 0;//Contador de errores, para antes de la persistencia
                $('.validateInput').each(function() {
                        if (!required($(this))) {
                                errores++;                                
                        }
                });

                if (errores===0) {                                                                  
                    var name = $('#txtName').val();
                    var table = $('#typeIndustry').DataTable();
                    $.ajax({
                        type: 'POST',                    
                        data: {name:name, id:id},
                        url: Routing.generate('admin_tipoindustry_insert'),
                        success: function (data)
                        {
                            $('#pnAdd').slideToggle();
                            $('#txtName').val('');    
                            swal("Successfully entered registration!");
                            //swal("", "Successfully updated!", "success");
                            $btn.button('reset');
                            table.ajax.reload();
                        },
                        error: function (xhr, status)
                        {

                        }
                    });// Fin ajax                       
                }else{                    
                    swal('','¡Fields in red are required!','error');
                    $btn.button('reset');
                    //console.log('error');
                }
            }else{//***********************
                 var id = $('#id').val();
                var $btn = $(this).button('loading');
                var errores = 0;//Contador de errores, para antes de la persistencia
                $('.validateInput').each(function() {
                        if (!required($(this))) {
                                errores++;                                
                        }
                });

                if (errores===0) {                                                                  
                    var name = $('#txtName').val();
                    var table = $('#typeIndustry').DataTable();
                    $.ajax({
                        type: 'POST',                    
                        data: {name:name, id:id},
                        url: Routing.generate('admin_tipoindustry_insert'),
                        success: function (data)
                        {
                            $('#pnAdd').slideDown();                            
                            //swal("", "Successfully entered registration!", "success");
                            swal("", "Successfully updated!", "success");
                            $btn.button('reset');
                            table.ajax.reload();
                        },
                        error: function (xhr, status)
                        {

                        }
                    });// Fin ajax                       
                }else{                    
                    swal('','¡Fields in red are required!','error');
                    $btn.button('reset');
                    //console.log('error');
                }
            }
            
        });
            
        /////Select checkboxes (All)
        $(document).on('click', '.chkItemAll', function (event) {
            /////Definición de variables                       
            $('#txtId').val('');
            $('#txtName').val('');            
            if ($(this).is(':checked')) {                
                $('.chkItem').each(function () {
                    $('.btnAdd').addClass('hidden');
                    $('.btnDelete').removeClass('hidden');
                    $(this).prop({'checked': true});
                });
            }
            else {
                $('.chkItem').each(function () {
                    $('.btnAdd').removeClass('hidden');
                    $('.btnDelete').addClass('hidden');
                    $(this).prop({'checked': false});
                });
            }
        });
        /////Fin select checkboxes (All)
        
        //******************Select checkboxes (Single)*************************//
        $(document).on('click', '.chkItem', function (event) {                                   
            /////Definición de variables            
            var total = 0;
            var selec = 0;

            $('.chkItem').each(function () {
                total++;
                if ($(this).is(':checked')) {  
                    selec++;
                }                            
            });  

            console.log(selec);
            var text = $(this).prop('tagName');            
            if (text === 'INPUT') {                
                if ($(this).is(':checked')) {
                    if(selec > 1){
                        $('.btnAdd').addClass('hidden');
                        $('.btnDelete').removeClass('hidden');
                        $('.btnShow').addClass('hidden');
                        $(this).prop({'checked': true});
                    }else{
                        $('.btnAdd').addClass('hidden');
                        $('.btnDelete').removeClass('hidden');
                        $('.btnShow').removeClass('hidden');
                        $(this).prop({'checked': true});
                    }                           
                }else{
                        if(selec === 1 ){                            
                            $('.btnAdd').addClass('hidden');
                            $('.btnDelete').removeClass('hidden');
                            $('.btnShow').removeClass('hidden');
                            $(this).prop({'checked': false});
                        }
                        if(selec === 0 ){                            
                            $('.btnAdd').removeClass('hidden');
                            $('.btnShow').addClass('hidden');
                            $('.btnDelete').addClass('hidden');
                            $(this).prop({'checked': false});
                        }
                    }                 
            }                       
            if (total === selec) {
                $('.chkItemAll').prop({'checked': true});
            }
            if ($('.chkItemAll').is(':checked')) {
                if (total > selec) {
                    $('.chkItemAll').prop({'checked': false});
                    $('.btnAdd').addClass('hidden');
                    $('.btnDelete').removeClass('hidden');
                }
            }                          
        }); //Fin chk
        
        
        /*********Select row for edit **********/
        $(document).on('click', '.btnShow', function (event) {
            //$('.btnAdd').addClass('hidden');
            //$('.panel-heading').empty();
            //$('.panel-heading').append('Edit');
            var id=0;
            
            $('.chkItem').each(function () {
                //total++;
                if ($(this).is(':checked')) {
                    id = $(this).parent().prop('id');
                    //selec++;
                }                            
            });  
                        
            var url = Routing.generate('admin_showedit_producto',{'id':id});
            window.open(url, "_self");             
            
        });
        /************ Fin select row for edit ***********/
        
        /************ Delete item or tems of ctlindustria ***************************************/
        $(document).on('click', '.btnDelete', function (event) {
            var ids=[];
            var table = $('#producto').DataTable();
            $('.chkItem').each(function () {
                
                if ($(this).is(':checked')) {  
                    //$(this).parent().attr('id');
                    ids.push($(this).parent().attr('id'));
                    //console.log($(this).parent().attr('id'));
                }                            
            });
            
            swal({
            title: "",
            text: "Remove selected rows?",
            type: "info",
            showCancelButton: true,
            confirmButtonText: "Remove",
            cancelButtonText: "Cancel",
            reverseButtons: true
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: 'POST',
                        data: {ids: ids},
                        url: Routing.generate('admin_producto_delete'),
                        success: function (data)
                        {                            
                            $('.btnDelete').addClass('hidden');
                            $('.btnAdd').removeClass('hidden');
                            var cont=0;
                            //swal("", "Deleted Successfully!", "success");
                            table.ajax.reload();
                            //$('#pnAdd').slideDown();                            
                            var total = 0;
                            var selec = 0;

                            $('.chkItem').each(function () {
                                total++;
                                if ($(this).is(':checked')) {
                                    selec++;
                                }
                            });
                            console.log('este es el contador: '+cont);
                            if(total === selec){                                
                                $('.chkItemAll').prop({'checked': false});                                                                        
                            }
                            
                        },
                        error: function (xhr, status)
                        {
                            swal('', "", 'error');
                            $btn.button('reset');
                        }
                    });
                }
            });                                                                                                                                  
        });        
        /************************ Fin Delete item or items ctlindustria****************/
}); //Fin document ready