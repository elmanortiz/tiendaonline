/////Show forms panel

$(document).on('click', '.btnAdd', function(event) {
	//console.log('add');
	var id = $('#txtId').val();
	//console.log("ID: "+id);
	//Cambiar nombre del panel heading para add (Inserción)
	$('.pnHeadingLabelAdd').removeClass('hidden');
	$('.pnHeadingLabelEdit').addClass('hidden');
	if (id!='') {
		// console.log("if");
		$('#txtId').val('');
		$('#txtName').val('');
		$('#txtDuration').val('');
		$('#pnAdd').slideUp();
	}
	else{
		// console.log("else");
		$('#pnAdd').slideToggle();
	}
	$('#txtName').focus();
});

/////Fin show forms panel

/////Hide forms panel

$(document).on('input', 'div.dataTables_filter input', function(event) {
	//console.log('add');
	$('#txtId').val('');
	$('#txtName').val('');
	$('#txtDuracion').val('');
	$('#pnAdd').slideUp();
	$('.chkItemAll').prop({'checked': false});
	$('.btnAdd').removeClass('hidden');
	$('.btnDelete').addClass('hidden');
});


$(document).on('click', '#btnCancel, #timeNotificationsList>thead>tr>th:gt(0)', function(event) {
	//console.log('add');
	$('#txtId').val('');
	$('#txtName').val('');
	$('#txtDuracion').val('');
	$('#pnAdd').slideUp();
	$('.chkItemAll').prop({'checked': false});
	$('.btnAdd').removeClass('hidden');
	$('.btnDelete').addClass('hidden');
});


/////Fin hide forms panel