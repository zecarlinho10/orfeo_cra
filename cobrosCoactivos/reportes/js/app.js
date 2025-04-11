	
 function reporteGeneral() {
 		
 		var estado = document.getElementById("txtEstado").value;
 		
		var parametros = {
	        "estado" : estado
		};
		$.ajax({
		    data:  parametros, //datos que se envian a traves de ajax
		    url:   'getReporteGeneral.php', //archivo que recibe la peticion
		    type:  'post', //método de envio
		    beforeSend: function () {
		        $("#repGeneral").html("Procesando, espere por favor...");
		    },
		    success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
		        $("#repGeneral").html(response);
		    }
		});
}

		$('#dataUpdate').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Botón que activó el modal
		  var id = button.data('id') // Extraer la información de atributos de datos
		  var fecha = button.data('fecha') // Extraer la información de atributos de datos
		  var descripcion = button.data('descripcion') // Extraer la información de atributos de datos
		  var radicado = button.data('radicado') // Extraer la información de atributos de datos
		  var notificacion = button.data('notificacion') // Extraer la información de atributos de datos
		  var acto = button.data('acto') // Extraer la información de atributos de datos
		  var observacion = button.data('observacion') // Extraer la información de atributos de datos
		  var modal = $(this)
		 
		  modal.find('.modal-title').text('Modificar Proceso')
		  modal.find('.modal-body #id').val(id)
		  modal.find('.modal-body #fecha').val(fecha)
		  modal.find('.modal-body #descripcion').val(descripcion)
		  modal.find('.modal-body #radicado').val(radicado)
		  modal.find('.modal-body #notificacion').val(notificacion)
		  modal.find('.modal-body #acto').val(acto)
		  modal.find('.modal-body #observacion').val(observacion)
		  
		  //var parametros = $(this).serialize();

		   $.ajax({

				type: "POST",
				url: "getActo.php",
				data: {'acto':acto,'notificacion':notificacion}
			}).done( function (info){

            	$("#acto_ajax").html(info);

        	});


		  $('.alert').hide();//Oculto alert

		})
		
		$('#dataDelete').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Botón que activó el modal
		  var id = button.data('id') // Extraer la información de atributos de datos
		  var modal = $(this)
		  modal.find('#id_pais').val(id)
		})
 
	$( "#actualidarDatos" ).submit(function( event ) {
		var parametros = $(this).serialize();
			 $.ajax({
					type: "POST",
					url: "modificar.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#datos_ajax").html("Mensaje: Cargando...");
					  },
					success: function(datos){
					$("#datos_ajax").html(datos);
					
					load(1);
				  }
			});
		  event.preventDefault();
		});