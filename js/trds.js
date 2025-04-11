var preload = false;
function cargarSerie() {
	var param = null;
	var cargaDiv = false;
	if ($("#preload").length) {
		param = $("#preload input").serialize();
		param = param + "&op=preload";
		cargaDiv = true;
	} else {
		param = {
			op : "buscarSerie"
		};
		if ($("#coddepe").val != 0) {
			param["coddepe"] = $("#coddepe").val();
		}
	}
	$.ajax({
		type : "POST",
		url : basePath + '/trd/menu_trd_lista.php',
		data : param,
		success : function(data) {
			if (cargaDiv) {
				$("#clasificacion").html(data);
				$('#serie').chosen();
				$('#subSerie').chosen();
				$('#tipo').chosen();
			} else {
				$("#serie").html(data);
				$('#serie').trigger("chosen:updated");
				$('#serie').chosen();
			}
			activarCambios();
		},
		error : function() {
			alert("no hay conexi\u00f3n con el servidor");
		}
	}).done(function() {
		console.log("hecho");

	});
};
function validarSerie() {
	if ($("#serie").val() == 0) {
		alert("Debe Seleccionar una Serie");
		return false;
	}
	return true;
}
function validarSubSerie() {
	if ($("#subSerie").val() == 0) {
		alert("Debe Seleccionar una SubSerie");
		return false;
	}
	return true;
}

function activarCambios() {
	$("#serie")
			.change(
					function() {
						var param = {
							op : "buscarSubserie",
							serie : $("#serie").val(),
							coddepe : $("#coddepe").val()
						}
						$
								.ajax({
									type : "POST",
									url : basePath + '/trd/menu_trd_lista.php',
									data : param,
									success : function(data) {
										$("#subSerie").html(data);
										$('#subSerie')
												.trigger("chosen:updated");
										$('#subSerie').chosen();

										$("#tipo")
												.html(
														"<option value='0'>0 Seleccione un tipo</option> ");
									},
									error : function() {
										alert("no hay conexi\u00f3n con el servidor");
									}
								})
					});
	$("#subSerie").change(function() {
		if (validarSubSerie()) {
			var param = {
				op : "buscarTipo",
				serie : $("#serie").val(),
				subSerie : $("#subSerie").val(),
				coddepe : $("#coddepe").val()
			}
			$.ajax({
				type : "POST",
				url : basePath + '/trd/menu_trd_lista.php',
				data : param,
				success : function(data) {
					$("#tipo").html(data);
					$('#tipo').trigger("chosen:updated");
					$('#tipo').chosen();

				},
				error : function() {
					alert("no hay conexi\u00f3n con el servidor");
				}
			}).done(function() {
				console.log("done")
			});
		}
	});
	$("#tipo").change(
			function() {
				var param = {
					op : "buscaTipos",
					coddepe : $("#coddepe").val(),
					tipo : $("#tipo").val()
				}
				$.ajax({
					type : "POST",
					url : basePath + '/trd/menu_trd_lista.php',
					data : param,
					success : function(data) {
						var retorno = jQuery.parseJSON(data);
						$("#termino").html(retorno.termin);
						$("#terminoval").html(retorno.termin);
						if (retorno.seccau !== null
								&& retorno.seccau !== undefined) {
							var parame = {
								op : "buscaSectores",
								seccau : retorno.seccau
							};
							console.log(parame);
							$.post(basePath + '/trd/menu_trd_lista.php',
									parame, function(dat) {
										$("#sectorCapa").html(dat);
									});
						} else {
							$("#sectorCapa").html("");
						}

					},
					error : function() {
						alert("no hay conexi\u00f3n con el servidor");
					}
				});
			});

}
$(document).ready(function() {

	$.ajaxSetup({
		beforeSend : function() {
			$('.loading').fadeIn(50);
		},
		complete : function() {
			$('.loading').delay(50).fadeOut(50);
		}
	});
	if ($('#depecod').length) {
		$("#coddepe").val($('#depecod').val());
		$("#coddepe").trigger("chosen:updated");
	}
	cargarSerie();

});
