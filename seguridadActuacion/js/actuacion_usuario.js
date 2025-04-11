function valida_form() {
		/* validar(valido); */
		var salida = '';
		if (!$("#frmAsegura").valid()) {
			validator = $("#frmAsegura").validate();
			if(!checkAnonimo())
				salida = "";
			for (var i = 0; i < validator.errorList.length; i++) {
				salida += validator.errorList[i].message + "\n";
			}
			BootstrapDialog.alert({
				title : "Error!!",
				message : salida
			});
		}
		$("#frmAsegura").validate().form();
		if(salida=='')
			return true;
		else
			return false;
}


		function confirmation() {
		    if(confirm("Confirma el ingreso de los involucados a la Actuación?"))
		    {
		        return true;
		    }
		    return false;
		}

		function notificacion(x){
		        //una notificación normal
		    if(x==1){
		    	alert("Actuación actualizada exitosamente."); 	
		    }
		    else{
		    	alert("Error al ingresar Actuación."); 	
		    }
		      
		      return false;
		}

// validacion caracteres

/*
 * <input type="text" onkeypress="return alpha(event,numbers)" /> <input
 * type="text" onkeypress="return alpha(event,letters)" /> <input type="text"
 * onkeypress="return alpha(event,numbers+letters+signs)" />
 */

var letters = ' ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyzáéíóúü\u0008'
var numbers = '1234567890 \u0008'
var signs = ',.:;@-\''
var mathsigns = '+-=()*/'
var custom = '<>#$%&?	'

function alpha(e, allow) {
	var k;
	k = document.all ? parseInt(e.keyCode) : parseInt(e.which);
	return (allow.indexOf(String.fromCharCode(k)) != -1 || e.keyCode == 9);
}

function alphaField(e, allow) {
	var k;
	r = true;
	for (var i = 0; i < e.length; i++) {
		if (allow.indexOf(e.charAt(i)) == -1)
			return false;
	}
	return r;
}

$(document)
.ready(
		function() {
			cargarTipos();
			$.validator.setDefaults({
				ignore : ":hidden:not(select)"
			})
			$('.chosen-select').chosen();
			$('.chosen-select-deselect').chosen({
				allow_single_deselect : true
			});
			
			$('[data-toggle="tooltip"]').tooltip();

		$("#txtNoExpediente").autocomplete(
		{
			select : function(event, ui) {
				console.log(ui.item);
				event.preventDefault();
				$("#id_emp1").val(ui.item.SGD_EXP_NUMERO);
				$("#txtNoExpediente").val(ui.item.SGD_EXP_NUMERO);
			},
			source : function(request, response) {
				$.ajax({
					type : "POST",
					url : '../seguridadActuacion/controllerActuacion.php',
					dataType : "json",
					data : {
						op : "buscarExpediente",
						employerId : 1,
						noExpediente : $("#txtNoExpediente").val()
					},
					success : function(data) {
						if (data.success) {
							response($.map(data.data, function(item) {
								return {
									SGD_EXP_NUMERO : item.SGD_EXP_NUMERO,
									SGD_SEXP_PAREXP1 : item.SGD_SEXP_PAREXP1,
									label : item.SGD_EXP_NUMERO + " - " + item.SGD_SEXP_PAREXP1,
									per : item
								}

							}));
						}
					}
				});
			},
			minLength : 3
		});

	});

function cargarTipos() {	
};
