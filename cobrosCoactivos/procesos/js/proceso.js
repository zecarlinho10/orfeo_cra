function valida_form() {
		/* validar(valido); */
		var salida = '';
		if (!$("#frmProcesos").valid()) {
			validator = $("#frmProcesos").validate();
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
		$("#frmProcesos").validate().form();
		if(salida=='')
			return true;
		else
			return false;
}


		function confirmation() {
		    if(confirm("Confirma el ingreso de la Actividad?"))
		    {
		        return true;
		    }
		    return false;
		}

		function notificacion(x){
		        //una notificación normal
		    if(x==1){
		    	alert("Proceso ingresada exitosamente.");
		    }
		    else{
		    	alert("Error al ingresar Proceso."); 	
		    }
		      
		      return false;
		}

		
		function radicadoFunction() {
			x=document.getElementById("radicado").value;
			if(x!=""){
				var parametros = {
	                "radicado" : x
		        };
		        $.ajax({
		                data:  parametros, //datos que se envian a traves de ajax
		                url:   '../procesos/validaRadicado.php', //archivo que recibe la peticion
		                type:  'post', //método de envio
		                beforeSend: function () {
		                        $("#resultado").html("Procesando, espere por favor...");
		                },
		                success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
		                        $("#resultado").html(response);
		                }
		        });
		  	}
		}

		function validaRadicadoJ() {

			x=document.getElementById("txtRadicado").value;

			if(x!=""){
				var parametros = {
	                "radicado" : x
		        };
		        $.ajax({
		                data:  parametros, //datos que se envian a traves de ajax
		                url:   '../procesos/validaRadicado.php', //archivo que recibe la peticion
		                type:  'post', //método de envio
		                beforeSend: function () {
		                        $("#resultadoRadicado").html("Procesando, espere por favor...");
		                },
		                success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
		                        $("#resultadoRadicado").html(response);
		                }
		        });
		  	}
		}

		
		function eliminaProceso(procesoid){
	        //actualiza la observacion de una
		    let idproceso=procesoid;
	    	
		    var parametros = {
                "idproceso" : idproceso,
	        };
	        
	        $.ajax({
	                data:  parametros, //datos que se envian a traves de ajax
	                url:   '../procesos/eliminaProceso.php', //archivo que recibe la peticion
	                type:  'post', //método de envio
	                beforeSend: function () {
	                        $("#resultado").html("Procesando, espere por favor...");
	                },
	                success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
	                        $("#resultado").html(response);
	                        alert ("Eliminado Exitosamente.");
	                        $("#txtCobro").change();
	                }
	        });
	        
		}

		function myFunction_suscribirse(respuesta_id) {
		  let suscribirse;
		  var id_respuesta = respuesta_id;
		  var d = document.getElementById("suscripcion_"+id_respuesta);
		  var form = document.getElementById("suscribirse_"+id_respuesta);
		  var respuesta_id = "respuesta_" + respuesta_id;

		  if( $("#suscribe_"+id_respuesta).prop('checked') ) {
		    suscribirse = 1;
		    d.style.background = "transparent";
		    d.style.color = "#949494";
		  } else {
		    suscribirse = 0;
		    form.style.background = "transparent";
		  }

		  console.log(suscribirse)

		  $.ajax ({
		    type: 'POST',
		    url: 'proces_suscribe.php',
		    data: { "corazon": suscribirse, "id_respuesta":id_respuesta }
		  });
		};


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
			$.get("getProcesos.php","txtCobro="+$("#txtCobro").val(), function(data){
				$("#tabla").html(data);
				console.log(data);
			});
		});

		$(function(){
		  $('#mi-tabla').tablesorter(); 
		});
					

	});


function cargarTipos() {
	
};
