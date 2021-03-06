	$(document).on('click', '.errorform', function(event) {
		$(this).removeClass('errorform');
		/* Act on the event */
	});
	
	/////Funcion que verifica que los campos no esten vacios
	function required(object){
            ///El valor '' es para input que requieran que se ingrese texto, el valor 0 puede ser para selects2 (dropdowns)
            var valor = object.val();
            if (valor=='' || valor==0) {
                object.addClass('errorform');
                return false;///Valor vacio (invalido)
            } 
            else {
                    return true;///Valor valido
            }
	}
        
        /////Funcion que verifica que se haya seleccionado una opcion
	function requiredSelect(object){
            var valor = object.val();
            if(valor=='' || valor == null){
                object.next().children().children().addClass('errorform');
                return false; /// Valor invalido
            } 
            else {
                return true; /// Valor valido
            }
	}
        
        /////Funcion que verifica que los campos no esten vacios y acepta el "0" como valor válido
	function valorRequired(object){
            ///El valor '' es para input que requieran que se ingrese texto, el valor 0 puede ser para selects2 (dropdowns)
            var valor = object.val();
            if (valor=='') {
                    object.addClass('errorform');
                    return false;///Valor vacio (invalido)
            } 
            else {
                    return true;///Valor valido
            }
	}

	function isAlphaOrParen(str) {
  		return /^[a-zA-Z() ]+$/.test(str);
	}

	$('.chkItemAll').prop({'checked': false});
