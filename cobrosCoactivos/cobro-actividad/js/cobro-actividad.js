function valida_form() {
		/* validar(valido); */
		var salida = '';
		if (!$("#frmActividaes").valid()) {
			validator = $("#frmActividaes").validate();
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
		$("#frmActividaes").validate().form();
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
		    	alert("Actividad ingresada exitosamente.");
		    }
		    else{
		    	alert("Error al ingresar Actividad."); 	
		    }
		      
		      return false;
		}

		function checkear(actividadid){
	        //actualiza el estado de un trámite
		    let valor = 2;
		    let idactividad=actividadid;
	    	if( $("#txtFinalizado"+idactividad).prop('checked') ) {
                valor = 1;
            }
            else{
            	valor = 0;
            }
			
           
		    var parametros = {
                "idactividad" : idactividad,
                "valor" : valor
	        };
	        $.ajax({
	                data:  parametros, //datos que se envian a traves de ajax
	                url:   '../actuacion-actividad/actualizaEstado.php', //archivo que recibe la peticion
	                type:  'post', //método de envio
	                beforeSend: function () {
	                        $("#resultado").html("Procesando, espere por favor...");
	                },
	                success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
	                        $("#resultado").html(response);
	                }
	        });
		}

		function actualizaAdjunto(idActActividad){
	        //actualiza la observacion de una

	        var file_data = $("#archivo"+idActActividad).prop("files")[0];   
		    var form_data = new FormData();
		    form_data.append("file", file_data);
		    //alert(form_data);
		    $.ajax({
		        url: 'uploadFile.php', // point to server-side PHP script 
		        dataType: 'text',  // what to expect back from the PHP script, if anything
		        cache: false,
		        contentType: false,
		        processData: false,
		        data: form_data,
		        type: 'post',
		        beforeSend: function () {
	                $("#resultado").html("Subiendo Archivo, espere por favor...");
	            },
	            success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
	                        $("#resultado").html(response);
	                        alert (response);
	                }
	                /*
		        success: function(php_script_response){
		            //alert(php_script_response); // display response from the PHP script, if any
		            $("#resultado").html("Archivo cargado exitosamente");
		            alert("Archivo cargado exitosamente"); 
		        }
		        */
		     });
		}

		function actualizaActividad(actividadid){
	        //actualiza la observacion de una
		    let idactividad=actividadid;
	    	
			
           	observacion=$("#txtObservacion"+idactividad).val();
		    var parametros = {
                "idactividad" : idactividad,
                "observacion" : observacion
	        };
	        
	        $.ajax({
	                data:  parametros, //datos que se envian a traves de ajax
	                url:   '../actuacion-actividad/actualizaActividad.php', //archivo que recibe la peticion
	                type:  'post', //método de envio
	                beforeSend: function () {
	                        $("#resultado").html("Procesando, espere por favor...");
	                },
	                success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
	                        $("#resultado").html(response);
	                        alert ("Actualizado Exitosamente.");
	                }
	        });
		}

		function eliminaActividad(actividadid){
	        //actualiza la observacion de una
		    let idactividad=actividadid;
	    	
		    var parametros = {
                "idactividad" : idactividad,
	        };
	        
	        $.ajax({
	                data:  parametros, //datos que se envian a traves de ajax
	                url:   '../cobro-actividad/eliminaActividad.php', //archivo que recibe la peticion
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
			$.get("getFechaActuacion.php","txtCobro="+$("#txtCobro").val(), function(data){
				$("#fechaFinActuacion").html(data);
				console.log(data);
			});
			$.get("getActividades.php","txtCobro="+$("#txtCobro").val(), function(data){
				$("#tabla").html(data);
				console.log(data);
			});
		});

		$(function(){
		  $('#mi-tabla').tablesorter(); 
		});


		$("#txtRadicado").autocomplete(
		{
			select : function(event, ui) {
				console.log(ui.item);
				event.preventDefault();
				$("#txtRadicado").val(ui.item.RADI_NUME_RADI);
			},
			source : function(request, response) {
				$.ajax({
					type : "POST",
					url : basePath + '/actuacion/actuacion-actividad/controllerActividad.php',
					dataType : "json",
					data : {
						op : "buscarRadicado",
						employerId : 1,
						txtRadicado : $("#txtRadicado").val()
					},
					success : function(data) {
						if (data.success) {
							response($.map(data.data, function(item) {
								return {
									RADI_NUME_RADI : item.RADI_NUME_RADI,
									label : RADI_NUME_RADI,
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
