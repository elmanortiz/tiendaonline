$(document).on('click', '.btnAdd', function(event) {
	var id = $('#txtId').val();
	
        //Cambiar nombre del panel heading para add (Inserción)
	$('.pnHeadingLabelAdd').removeClass('hidden');
	$('.pnHeadingLabelEdit').addClass('hidden');

	if (id!='') {
		$('#txtId').val('');
		$('#txtName').val('');
		$('#pnAdd').slideDown();
	}
	else{
		$('#pnAdd').slideToggle();
	}
	$('#txtName').focus();
});
