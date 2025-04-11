function valida_form() {
		/* validar(valido); */
		var salida = '';
		if (!$("#frmcobro").valid()) {
			validator = $("#frmcobro").validate();
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
		$("#frmcobro").validate().form();
		if(salida=='')
			return true;
		else
			return false;
}


		function confirmation() {
		    if(confirm("Confirma la actualizacion de informacion?"))
		    {
		        return true;
		    }
		    return false;
		}

		function notificacion(x){
		        //una notificación normal
		    if(x==1){
		    	alert("Cobros actualizado exitosamente."); 	
		    }
		    else{
		    	alert("Error al actualizar cobro."); 	
		    }
		      
		      return false;
		}

		function expedienteFunction() {
			x=document.getElementById("txtExpediente").value;
			if(x!=""){
				var parametros = {
	                "expediente" : x
		        };
		        $.ajax({
		                data:  parametros, //datos que se envian a traves de ajax
		                url:   '../cobro/validaExpediente.php', //archivo que recibe la peticion
		                type:  'post', //método de envio
		                beforeSend: function () {
		                        $("#resultadoExp").html("Procesando, espere por favor...");
		                },
		                success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
		                        $("#resultadoExp").html(response);
		                }
		        });
		  	}
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

		$("#txtCobro").change(function(){
			$.get("getFechaActuacion.php","txtCobro="+$("#txtCobro").val(), function(data){
				$("#fechaFinActuacion").html(data);
				console.log(data);
			});
			$.get("getCobro.php","txtCobro="+$("#txtCobro").val(), function(data){
				$("#tabla").html(data);
				console.log(data);
			});
		});


		$("#txtnoFun").autocomplete(
		{
			select : function(event, ui) {
				console.log(ui.item);
				event.preventDefault();
				$("#txtid_fun").val(ui.item.ID);
				$("#txtnoFun").val(ui.item.USUA_NOMB);
			},
			source : function(request, response) {
				$.ajax({
					type : "POST",
					url : basePath + '/cobrosCoactivos/cobro/controllerCobro.php',
					dataType : "json",
					data : {
						op : "buscarFuncionario",
						employerId : 1,
						noFuncionario : $("#txtnoFun").val()
					},
					success : function(data) {
						if (data.success) {
							response($.map(data.data, function(item) {
								return {
									ID : item.ID,
									USUA_NOMB : item.USUA_NOMB,									
									label : item.USUA_NOMB,
									per : item
								}

							}));
						}
					}
				});
			},
			minLength : 3
		});


		$("#txtnoEmpresa").autocomplete(
		{
			select : function(event, ui) {
				console.log(ui.item);
				event.preventDefault();
				$("#txtid_emp").val(ui.item.SGD_OEM_CODIGO);
				$("#txtnoEmpresa").val(ui.item.SGD_OEM_OEMPRESA);
			},
			source : function(request, response) {
				$.ajax({
					type : "POST",
					url : basePath + '/cobrosCoactivos/cobro/controllerCobro.php',
					dataType : "json",
					data : {
						op : "buscarEmpresaXnombre",
						employerId : 1,
						noEmpresa : $("#txtnoEmpresa").val()
					},
					success : function(data) {
						if (data.success) {
							response($.map(data.data, function(item) {
								return {
									SGD_OEM_CODIGO : item.IDENTIFICADOR_EMPRESA,
									SGD_OEM_NIT : item.NIT_DE_LA_EMPRESA,
									SGD_OEM_OEMPRESA : item.NOMBRE_DE_LA_EMPRESA,
									label : item.NIT_DE_LA_EMPRESA + " " + item.NOMBRE_DE_LA_EMPRESA,
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
