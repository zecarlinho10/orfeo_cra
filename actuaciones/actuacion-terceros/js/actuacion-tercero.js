function valida_form() {
		/* validar(valido); */
		var salida = '';
		if (!$("#frmTerceros").valid()) {
			validator = $("#frmTerceros").validate();
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
		$("#frmTerceros").validate().form();
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

		$("#txtnoEmpresa1").autocomplete(
		{
			select : function(event, ui) {
				console.log(ui.item);
				event.preventDefault();
				$("#id_emp1").val(ui.item.SGD_OEM_CODIGO);
				$("#txtnoEmpresa1").val(ui.item.SGD_OEM_OEMPRESA);
			},
			source : function(request, response) {
				$.ajax({
					type : "POST",
					url : basePath + 'actuaciones/actuacion-terceros/controllerActuacionTercero.php',
					dataType : "json",
					data : {
						op : "buscarEmpresaXnombre",
						employerId : 1,
						noEmpresa : $("#txtnoEmpresa1").val()
					},
					success : function(data) {
						if (data.success) {
							response($.map(data.data, function(item) {
								return {
									SGD_OEM_CODIGO : item.SGD_OEM_CODIGO,
									SGD_OEM_NIT : item.SGD_OEM_NIT,
									SGD_OEM_OEMPRESA : item.SGD_OEM_OEMPRESA,
									label : item.SGD_OEM_NIT + " " + item.SGD_OEM_OEMPRESA,
									per : item
								}

							}));
						}
					}
				});
			},
			minLength : 3
		});

		$("#txtnoEmpresa2").autocomplete(
		{
			select : function(event, ui) {
				console.log(ui.item);
				event.preventDefault();
				$("#id_emp2").val(ui.item.SGD_OEM_CODIGO);
				$("#txtnoEmpresa2").val(ui.item.SGD_OEM_OEMPRESA);
			},
			source : function(request, response) {
				$.ajax({
					type : "POST",
					url : basePath + '/actuaciones/actuacion-terceros/controllerActuacionTercero.php',
					dataType : "json",
					data : {
						op : "buscarEmpresaXnombre",
						employerId : 1,
						noEmpresa : $("#txtnoEmpresa2").val()
					},
					success : function(data) {
						if (data.success) {
							response($.map(data.data, function(item) {
								return {
									SGD_OEM_CODIGO : item.SGD_OEM_CODIGO,
									SGD_OEM_NIT : item.SGD_OEM_NIT,
									SGD_OEM_OEMPRESA : item.SGD_OEM_OEMPRESA,
									label : item.SGD_OEM_NIT + " " + item.SGD_OEM_OEMPRESA,
									per : item
								}

							}));
						}
					}
				});
			},
			minLength : 3
		});
		

		$("#btAdd").click(function(){
            $("#emp2").show();
            /*
            $("#div_adiciona").hide();
            $("#div_quita").show();
            */
        });
        /**
         * Funcion para eliminar la ultima columna de la tabla.
         * Si unicamente queda una columna, esta no sera eliminada
         */
        $("#btDel").click(function(){
            $("#emp2").hide();
            $("#id_emp2").value()="";
            $("#txtnoEmpresa2").value()="";
            /*
            $("#div_adiciona").show();
            $("#div_quita").hide();
            */
        });
			

	});

function checkAnonimo() {
	return  $('#chkAnonimo').is(':checked');
}

function cargarTipos() {
	
	
	
	
};
