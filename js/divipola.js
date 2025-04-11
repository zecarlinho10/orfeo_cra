function cargarPaises() {
	$.ajax({
		type : "POST",
		url : basePath + '/atencion/controllerAtencion.php',
		data : {op : "buscarPais"},
		success : function(data) {
			$("#pais").empty();
			$("#pais").html(data);
			$("#mcpio").html("<option value=''>0 Seleccione un Municipio</option> ");
			$("#depto").html("<option value=''>0 Seleccione un Departamento</option> ");
			$('#pais').trigger("chosen:updated");
			$('#pais').chosen();
		},
		error : function() {
			alert("no hay conexi\u00f3n con el servidor");
		}
	}).done(function(){
		$("#pais").rules("add", "required");
	});
	
};
$(document)
		.ready(function() {
			cargarPaises();
					$("#pais").change(function() {var param = {
							op : "buscarDepto",pais : $("#pais").val()}
					$.ajax({
						type : "POST",
						url : basePath+ '/atencion/controllerAtencion.php',
						data : param,
						success : function(data) {
						$("#dpto").empty();
						$("#mcpio").empty();
						$("#dpto").html(data);
						$("#mcpio").html("<option value='0'>0 Seleccione un Municipio</option> ");
						$('#dpto').trigger("chosen:updated");
						$('#dpto').chosen();
						$('#mcpio').trigger("chosen:updated");
						$('#mcpio').chosen();
						
						},
						error : function() {
							alert("no hay conexi\u00f3n con el servidor");
							console.log("error");
							}
						});
					});
					
		$("#dpto").change(function() {
			var param = {op : "buscarMunicipio",
					dpto : $("#dpto").val()};
			$.ajax({
				type : "POST",
				url : basePath+ '/atencion/controllerAtencion.php',
				data : param,
				success : function(data) {
					$("#mcpio").empty();
					$("#mcpio").html(data);
					$('#mcpio').trigger("chosen:updated");
					$('#mcpio').chosen();
					},
					error : function() {
						alert("no hay conexi\u00f3n con el servidor");
						}
						});
	});

				});
