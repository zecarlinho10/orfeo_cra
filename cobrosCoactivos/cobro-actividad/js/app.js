
 
		$('#dataUpdate').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Botón que activó el modal
		  var id = button.data('id') // Extraer la información de atributos de datos
		  var resolucion = button.data('resolucion') // Extraer la información de atributos de datos
		  var valor = button.data('valor') // Extraer la información de atributos de datos
		  var interes = button.data('interes') // Extraer la información de atributos de datos
		  var capital = button.data('capital') // Extraer la información de atributos de datos
		  var abointer = button.data('abointer') // Extraer la información de atributos de datos
		  var fecha = button.data('fecha') // Extraer la información de atributos de datos
		  var vigencia = button.data('vigencia') // Extraer la información de atributos de datos
		  var modal = $(this)
		 
		  modal.find('.modal-title').text('Modificar actividad')
		  modal.find('.modal-body #id').val(id)
		  modal.find('.modal-body #resolucion').val(resolucion)
		  modal.find('.modal-body #valor').val(valor)
		  modal.find('.modal-body #interes').val(interes)
		  modal.find('.modal-body #capital').val(capital)
		  modal.find('.modal-body #abointer').val(abointer)
		  modal.find('.modal-body #fecha').val(fecha)
		  modal.find('.modal-body #vigencia').val(vigencia)
		  
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