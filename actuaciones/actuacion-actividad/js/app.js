
 
		$('#dataUpdate').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Botón que activó el modal
		  var id = button.data('id') // Extraer la información de atributos de datos
		  var id_actividad = button.data('id_actividad') // Extraer la información de atributos de datos
		  var radicado = button.data('radicado') // Extraer la información de atributos de datos
		  var idEncargado = button.data('idEncargado') // Extraer la información de atributos de datos
		  var inicio = button.data('inicio') // Extraer la información de atributos de datos
		  var fin = button.data('fin') // Extraer la información de atributos de datos
		  var observacion = button.data('observacion') // Extraer la información de atributos de datos
		  var nombre = button.data('nombre') // Extraer la información de atributos de datos
		  var modal = $(this)
		  if(radicado != ""){
		  	idEncargado=null;
		  	inicio=null;
		  	fin=null;
		  }

		  modal.find('.modal-title').text('Modificar actividad: '+nombre)
		  modal.find('.modal-body #id').val(id)
		  //modal.find('.modal-body #id_actividad').val(id_actividad)
		  modal.find('.modal-body #radicado').val(radicado)
		  //modal.find('.modal-body #idEncargado').val(idEncargado)
		  modal.find('.modal-body #inicio').val(inicio)
		  modal.find('.modal-body #fin').val(fin)
		  modal.find('.modal-body #observacion').val(observacion)
		  
		  $.ajax({

				type: "POST",
				url: "getActividadController.php",
				data: {'id_actividad':id_actividad}
			}).done( function (info){

            	$("#actividad_ajax").html(info);

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