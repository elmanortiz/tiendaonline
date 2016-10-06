$(document).ready(function() {
    $('#fecha-inicio').val('');
    $('#fecha-fin').val('');

    $('#fecha-inicio').Zebra_DatePicker({
        months:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        format: 'd/m/Y',
        show_clear_date:false,
        show_select_today: "Hoy",
        default_position: "below",
        offset:[-142,40],
        onSelect: function(){
            var fi = $('#fecha-inicio').val().split('/');
            var ff = $('#fecha-fin').val().split('/');
            
            if((fi[2]+'-'+fi[1]+'-'+fi[0] > ff[2]+'-'+ff[1]+'-'+ff[0]) && ($('#fecha-fin').val() !== "")) {
                swal('', 'La fecha de inicio debe de ser menor a la fecha de fin', 'error');
                $('#fecha-inicio').val('');
                $('#fecha-fin').val('');
            }
        },
        onClear: function(){
            $('canvas').remove();
        },
        pair: $('#fecha-fin')
    });

    $('#fecha-fin').Zebra_DatePicker({
        months:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        format: 'd/m/Y',
        show_clear_date:false,
        show_select_today: "Hoy",
        default_position: "below",
        offset:[-142,40],
        direction: 1
    });
    
    $(document).on('click', '#filtrar', function(event) {
        $('#consolidado').addClass('hidden');
//        $('#head').html('');
//        $('#tabla').html('');
        
        var fechaInicio = $("#fecha-inicio").val();
        var fechaFin = $("#fecha-fin").val();
        
        if( fechaInicio != '' && fechaFin != '' ){
            $.ajax({
                url: Routing.generate('admin_ingresos_venta_filtro'),
                type: 'POST',
                data: { fechaInicio:fechaInicio, fechaFin:fechaFin },
                dataType: 'json',
                success: function (response)
                {
                    if(!response.error){
                        drawTable(response.data);
                        $('#consolidado').removeClass('hidden');
                    } else {
                        swal('', response.error, 'error');
                    }                    

                    return false;
                },
                error:function(response){
                    if(response.msg.error){
                        swal('', response.error, 'error');
                    }                    
                }
            });
        }
        
        return false;
    });
    
    $(document).on('click', '#exportar-pdf', function(event) {
        var fechaInicio = $("#fecha-inicio").val();
        var fechaFin = $("#fecha-fin").val();
        var url = Routing.generate('admin_ingresos_venta_pdf', {'fechaInicio': fechaInicio, 'fechaFin': fechaFin});
        
        window.open(url, '_blank');
    });
});

function drawTable(data) {
    var rows = '';
    var row = '';
    var online = 0;
    var venta = 0;
    var total = 0;
    
    row+= '<tr>';
    row+='<th style="text-align: center;" >Mes / Año</th>';
    row+='<th style="text-align: center;" >Ventas en linea ($)</th>';
    row+='<th style="text-align: center;">Ventas en tienda ($)</th>';
    row+='<th style="text-align: center;">Total de ventas ($)</th>';
    row+='</tr>';
    
    for (var i = 0; i < data.length; i++) {
        if(!isNaN(parseFloat(data[i].online))) {
            online+= parseFloat(data[i].online);
        }
        
        if(!isNaN(parseFloat(data[i].venta))) {
            venta+= parseFloat(data[i].venta);
        }
        
        if(!isNaN(parseFloat(data[i].total))) {
            total+= parseFloat(data[i].total);
        }
        
        rows+=drawRow(data[i], i+1);
    }
    
    rows+= '<tr>';
    rows+='<td style="text-align: left; font-weight: bold;" >Total</td>';
    rows+='<td style="text-align: right; font-weight: bold;" >' + (online).toFixed(2) + '</td>';
    rows+='<td style="text-align: right; font-weight: bold;">' + (venta).toFixed(2) + '</td>';
    rows+='<td style="text-align: right; font-weight: bold;">' + (total).toFixed(2) + '</td>';
    rows+='</tr>';
    
    document.getElementById('head').innerHTML = row;
    document.getElementById('tabla').innerHTML = rows;    
}

function drawRow(rowData) {
    var row = '';
    
    row+='<tr>';
    row+='<td style="text-align: left;">' + rowData.mes + ' / ' + rowData.anio + '</center></td>';
    
    row+='<td>';
    if(rowData.online != null && rowData.online !== null){
        row+=rowData.online; 
    } else {
        row+='0.00';
    }            
    row+='</td>';
    
    row+='<td>';
    if(rowData.venta != null && rowData.venta !== null){
        row+=rowData.venta; 
    } else {
        row+='0.00';
    }            
    row+='</td>';
    
    row+='<td>' + rowData.total + '</td>';
    row+='</tr>';
        
    return row;
    
}