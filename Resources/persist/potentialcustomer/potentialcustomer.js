    $(document).ready(function() { 
        $('#pnAdd').slideToggle();
        $('#id').val(-1);
        $("#btnCancel").click(function () {
            $('#pnAdd').slideToggle();
            $('#txtName').val('');
            $('#id').val(-1);
        });   
                        
       $(document).on("click","#morephone", function() {
           //alert("asdasd");
           var phone = '';
           phone = '<div class="col-md-12 removephone">';
           phone += '<div class="row">';
           phone += '<div class="col-md-2">';
           phone += '<div class="form-group">';
           phone += '<select class="input-md form-control validateInput phonetype">';
           phone += '<option value="fijo">fijo</option>';
           phone += '<option value="movil">movil</option>';
           phone += '</select>';
           phone += '</div>';
           phone += '</div>';
           phone += '<div class="col-md-4">';
           phone += '<div class="form-group">';
           phone += '<input id="txtNumber" type="text" name="" class="input-md form-control validateInput phonenumber">';
           phone += '</div>';
           phone += '</div>';
           phone += '<div class="col-md-2">';
           phone += '<div class="form-group">';
           phone += '<input id="txtExtension" type="text" name="" class="input-md form-control validateInput phoneextension">';
           phone += '</div>';
           phone += '</div>';
           phone += '<div class="col-md-2">';
           phone += '<button id="" type="button" class="btn btn-danger delphone"><i class="fa fa-remove" aria-hidden="true"></i></button>';
           phone += '</div>';
           phone += '</div>';
           phone += '</div>';
           $('#rowphone').append(phone);
       }); 
       
       $(document).on("click",".delphone", function() {
           $(this).parent().parent().parent().remove();           
       });
       
       $(document).on("click", "#moreemail", function() { 
           var email = '';           
           email += '<div class="row">';
           email += '<div class="col-md-6">';
           email += '<div class="form-group">';
           email += '<input id="txtEmail" type="text" name="" class="input-md form-control validateInput emailaddress">';
           email += '</div>';
           email += '</div>';
           email += '<div class="col-md-2">';
           email += '<button type="button" class="btn btn-danger delemail"><i class="fa fa-plus" aria-hidden="true"></i></button>';
           email += '</div>';
           email += '<div class="col-md-4"></div>';
           email += '</div>';                       
           $('#rowemail').append(email);
       });
       
       $(document).on("click",".delemail", function() {
           $(this).parent().parent().remove();           
       });
       
        $(document).on("click", "#moreadress", function() { 
           var adress = '';
           adress += '<div class="row">';
           adress += '<div class="col-md-6">';
           adress += '<div class="form-group">';
           adress += '<input id="txtAdress" type="text" name="" class="input-md form-control validateInput addressaddress">';
           adress += '</div>';
           adress += '</div>';
           adress += '<div class="col-md-2">';
           adress += '<div class="form-group">';
           adress += '<select id="txtState" class="input-md form-control validateInput estate">';
           adress += '<option value="volvo">Estado 1</option>';
           adress += '<option value="volvo">Estado 2</option>';
           adress += '</select>';
           adress += '</div>';
           adress += '</div>';
           adress += '<div class="col-md-2">';
           adress += '<div class="form-group">';
           adress += '<select id="txtCity" class="input-md form-control validateInput city">';
           adress += '<option value="volvo">Ciudad 1</option>';
           adress += '<option value="volvo">Ciudad 2</option>';
           adress += '</select>';
           adress += '</div>';
           adress += '</div>';
           adress += '<div class="col-md-2">';
           adress += '<button type="button" class="btn btn-danger delemail"><i class="fa fa-remove" aria-hidden="true"></i></button>';
           adress += '</div>';
           adress += '<div class="col-md-4"></div>';
           adress += '</div>';
           $('#rowadress').append(adress);
        });
        $(document).on("click",".delemail", function() {
           $(this).parent().parent().remove();           
       });
       
       $(document).on("click", "#btnSave", function () {
        var id = $('#id').val();
        var phonetype = [];
        var phonenumber = [];
        var phoneextension = [];
        var emailaddress = [];
        
        var addressaddress = [];
        var state = [];
        var city = [];

        $('.phonetype').each(function () {
            phonetype.push($(this).val());
        });
        $('.phonenumber').each(function () {
            phonenumber.push($(this).val());
        });
        $('.phoneextension').each(function () {
            phoneextension.push($(this).val());
        });
               
        $('.emailaddress').each(function () {
            emailaddress.push($(this).val());
        });
        
        
        $('.addressaddress').each(function () {
            addressaddress.push($(this).val());
        });
        $('.state').each(function () {
            state.push($(this).val());
        });
        $('.city').each(function () {
            city.push($(this).val());
        });
        
        
        
        console.log(phonetype);
        console.log(phonenumber);
        console.log(phoneextension);
        console.log(emailaddress);
        
        console.log(addressaddress);
        console.log(state);
        console.log(city);
        
        if ($('#id').val() === '-1') {//Esto es cuando es una insercion
            console.log('Si es igual que menos uno');
            var $btn = $(this).button('loading');
            var errores = 0;//Contador de errores, para antes de la persistencia
            $('.validateInput').each(function () {
                if (!required($(this))) {
                    errores++;
                }
            });

            if (errores === 0) {
                //alert();                                                                                                
                var name = $('#txtName').val();
                var table = $('#typeIndustry').DataTable();
                $.ajax({
                    type: 'POST',
                    data: {name: name, id: id},
                    //url: Routing.generate('admin_tipoindustry_insert'),
                    success: function (data)
                    {
                        $('#pnAdd').slideToggle();
                        $('#txtName').val('');
                        swal("Successfully entered registration!");
                        //swal("", "Successfully updated!", "success");
                        $btn.button('reset');
                        //table.ajax.reload();
                    },
                    error: function (xhr, status)
                    {

                    }
                });// Fin ajax                       
            } else {
                swal('', '¡Fields in red are required!', 'error');
                $btn.button('reset');
                //console.log('error');
            }
        } else {//***********************
            var id = $('#id').val();
            var $btn = $(this).button('loading');
            var errores = 0;//Contador de errores, para antes de la persistencia
            $('.validateInput').each(function () {
                if (!required($(this))) {
                    errores++;
                }
            });

            if (errores === 0) {
                var name = $('#txtName').val();
                var table = $('#typeIndustry').DataTable();
                $.ajax({
                    type: 'POST',
                    data: {name: name, id: id},
                    //url: Routing.generate('admin_tipoindustry_insert'),
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
            } else {
                swal('', '¡Fields in red are required!', 'error');
                $btn.button('reset');
                //console.log('error');
            }
        }

    });
}); //Fin document ready                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        