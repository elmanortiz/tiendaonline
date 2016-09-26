$(document).ready(function() {
   $(document).on("click", "#btnSave", function(){
        var btn = $(this).button('loading');
        var passwActual = $('#txtPwdant').val();
        var passwNva = $('#txtPwdnva').val();

        swal({
            text: "¿Está seguro de cambiar su contraseña?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#1D234D",
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar",
            closeOnConfirm: false,
            closeOnCancel: false
        }).then(function(isConfirm) {
            if (isConfirm) {                
                var data = {
                    passwActual : passwActual,
                    passwNva : passwNva
                };

                $.ajax({
                    type: 'POST',
                    data: data,
                    url: Routing.generate('admin_cambio_passw'),
                    success: function (response)
                    {
                        if(!response.msg.error){
                            swal('', response.msg.msg,'success');
                            btn.button('reset');
                            $('#btnSave').hide();
                        } else {
                            swal('', response.msg.error, 'error');
                            btn.button('reset');
                        }  

                        return false;
                    },
                    error: function (response){
                        if(response.msg.error){
                            swal('', response.msg.error, 'error');
                        }
                        
                        btn.button('reset');
                    }
                });
            } else {
                btn.button('reset');
            }
        });
    });

    $(document).on("input", "#txtPwdrpt", function(){
        var passw = $('#txtPwdnva').val();
        var cpassw = $(this).val();
        var error = document.getElementById('error');

        if(passw != cpassw){
            error.style.display = 'block';
            $('#btnSave').hide();
        } else {
            error.style.display = 'none';
            $('#btnSave').show();
        }
    });
    
    $(document).on("input", "#txtPwdnva", function(){
        var cpassw = $('#txtPwdrpt').val();
        var passw = $(this).val();
        var error = document.getElementById('error');

        if(cpassw != '') {
            if(cpassw != passw){
                error.style.display = 'block';
                $('#btnSave').hide();
            } else {
                error.style.display = 'none';
                $('#btnSave').show();
            }
        }
    });
}); 
