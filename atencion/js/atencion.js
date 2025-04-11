function valida_form() {
		/* validar(valido); */
		var salida = 'Si usted opta por presentar su comunicación en forma anónima, no será posible que reciba de manera directa respuesta por parte de esta Entidad.\nRecuerde que debe diligenciar los campos Tipo de petición, y los campos obligatorios \n';
		if (!$("#contactoOrfeo").valid()) {
			validator = $("#contactoOrfeo").validate();
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
		$("#contactoOrfeo").validate().form();
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
var componentes=["juridica","persona","esp"];
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
			
			$("#pais").change(function() {
				if($(this).val()=="1-170"){
					$("#dpto").rules("add", "required");
					$("#mncpio").rules("add", "required");;
				}else{
					$("#dpto").rules("remove");
					$("#mncpio").rules("remove");
				}
			});
			
			$("#tipoPersona").change(
					function() {
						$(" .obligatorio").each(function() {
							$(this).rules("remove");
						});
						var componente = "";
						$(".dinamic").hide();
						var valor = $(this).val();
						console.log(valor);
						if (valor == 1) {
							componente = "juridica";
						} else if (valor == 2) {
							componente = "persona"
						} else if (valor == 3) {
							componente = "esp";
						}
						$("#" + componente).find(".obligatorio").each(
								function() {
									$(this).rules("add", "required");
									if($(this).hasClass("digitos")){
										$(this).rules("add", "digits");	
									}
								});
						$("#" + componente).show();

					});
			$("#contactoOrfeo")
					.validate(
							{
								rules : {
									pais : {
										required : true
									},
									tipoPersona : {
										required : true
									}
									
								},
								messages : {
									pais:"el campo pais es obligatorio ",
									tipoPersona :"se debe definir el tipo de persona ",
										
								}
							});
			$('[data-toggle="tooltip"]').tooltip();

			$('input:radio[name=anonimo]')
					.change(
							function() {
								if (this.value == '1') {
									$("#ema").hide();
									$(".noanonimo").show();
									$("#pais").rules("add","required");
									$("#tipoPersona").rules("add","required");
									$('#tipoPersona').trigger("chosen:updated");
								} else if (this.value == '2') {
									$("#ema").show();
									var componente = "";
									$("#pais").rules("remove");
									var tipoSeleccionado = $(
											"#tipoPersona").val();
									$("#tipoPersona").val("");
									$("#tipoPersona").rules("remove");
									$('#tipoPersona').trigger("chosen:updated");
									switch (tipoSeleccionado) {
									case '1':
									    componente = "juridica";
										break;
									case '2':
										componente = "persona";
										break;
									case '3':
										componente = "esp"
										break;
									default:
										componente = null;
										break;

									}
									if (componente != null && componente !== "") {
										$("#" + componente).find(
												" .obligatorio").each(
												function() {
													$(this).rules(
															"remove");
												});

									$("#" + componente).hide();
									}
									$(".noanonimo").hide();
									BootstrapDialog
											.alert({
												title : "Alerta!!",
												message : "Si usted opta por presentar su comunicación en forma anónima, no será posible que reciba de manera directa respuesta por de la entidad,\n la comunicación sera publicada en <a target='_blank' rel='noopener noreferrer' href='"+urlEntidad+"'>"+urlEntidad+" </a>"
											});
								}
							});
$("#tipoPersona").change(function() {
	$(".dinamic").hide();

	var valor = $(this).val();
	if (valor == 1) {
		$("#juridica").show();
	} else if (valor == 2) {
		$("#persona").show();
	} else if (valor == 3) {
		$("#esp").show();
	} else if (valor == 4) {
		$("#anonimo").show();
	}
});

//PERSONA JURIDICA
$("#txtnit").autocomplete(

		{
			select : function(event, ui) {
				console.log(ui.item);
				event.preventDefault();
				$("#id_emp").val(ui.item.SGD_OEM_CODIGO);
				$("#txtnit").val(ui.item.SGD_OEM_NIT);
				$("#txtnoEmpresa").val(ui.item.SGD_OEM_OEMPRESA);
				$("#txtrep").val(ui.item.SGD_OEM_REP_LEGAL);
				$("#txtdirEmpresa").val(ui.item.SGD_OEM_DIRECCION);
				$("#txtcontacto").val(ui.item.SGD_OEM_TELEFONO);
				$("#emailEmpresa").val(ui.item.SGD_OEM_EMAIL);
			},
			source : function(request, response) {
				$.ajax({
					type : "POST",
					url : basePath + '/atencion/controllerAtencion.php',
					dataType : "json",
					data : {
						op : "buscarEmpresaXnit",
						employerId : 1,
						nit : $("#txtnit").val()
					},
					success : function(data) {
						if (data.success) {
							response($.map(data.data, function(item) {
								return {
									SGD_OEM_CODIGO : item.SGD_OEM_CODIGO,
									SGD_OEM_NIT : item.SGD_OEM_NIT,
									SGD_OEM_OEMPRESA : item.SGD_OEM_OEMPRESA,
									SGD_OEM_REP_LEGAL : item.SGD_OEM_REP_LEGAL,
									SGD_OEM_DIRECCION : item.SGD_OEM_DIRECCION,
									SGD_OEM_TELEFONO : item.SGD_OEM_TELEFONO,
									SGD_OEM_EMAIL : item.SGD_OEM_EMAIL,
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

$("#txtnoEmpresa").autocomplete(
		{
			select : function(event, ui) {
				console.log(ui.item);
				event.preventDefault();
				$("#id_emp").val(ui.item.SGD_OEM_CODIGO);
				$("#txtnit").val(ui.item.SGD_OEM_NIT);
				$("#txtnoEmpresa").val(ui.item.SGD_OEM_OEMPRESA);
				$("#txtrep").val(ui.item.SGD_OEM_REP_LEGAL);
				$("#txtdirEmpresa").val(ui.item.SGD_OEM_DIRECCION);
				$("#txtcontacto").val(ui.item.SGD_OEM_TELEFONO);
				$("#emailEmpresa").val(ui.item.SGD_OEM_EMAIL);
			},
			source : function(request, response) {
				$.ajax({
					type : "POST",
					url : basePath + '/atencion/controllerAtencion.php',
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
									SGD_OEM_CODIGO : item.SGD_OEM_CODIGO,
									SGD_OEM_NIT : item.SGD_OEM_NIT,
									SGD_OEM_OEMPRESA : item.SGD_OEM_OEMPRESA,
									SGD_OEM_REP_LEGAL : item.SGD_OEM_REP_LEGAL,
									SGD_OEM_DIRECCION : item.SGD_OEM_DIRECCION,
									SGD_OEM_TELEFONO : item.SGD_OEM_TELEFONO,
									SGD_OEM_EMAIL : item.SGD_OEM_EMAIL,
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

//PERSONA NATURAL
$("#documento").autocomplete(
		{
			select : function(event, ui) {
				console.log(ui.item);
				event.preventDefault();
				$("#txtapellido1").val(ui.item.SGD_CIU_APELL1);
				$("#txtapellido2").val(ui.item.SGD_CIU_APELL2);
				$("#txtnombre1").val(ui.item.SGD_CIU_NOMBRE);
				$("#txtnombre2").val();
				$("#txtdir").val(ui.item.SGD_CIU_DIRECCION);
				$("#txtemail").val(ui.item.SGD_CIU_EMAIL);
				$("#txtnfijo").val(ui.item.SGD_CIU_TELEFONO);
				$("#txtcelular").val();
				$("#tipoSexo").val();
				$("#id_ciu").val(ui.item.SGD_CIU_CODIGO)
				$("#documento").val(ui.item.SGD_CIU_CEDULA);
			},
			source : function(request, response) {
				$.ajax({
					type : "POST",
					url : basePath + '/atencion/controllerAtencion.php',
					dataType : "json",
					data : {
						op : "buscarPersona",
						employerId : 1,
						identificacion : $("#documento").val()
					},
					success : function(data) {
						if (data.success) {
							response($.map(data.data, function(item) {
								return {
									SGD_CIU_CEDULA : item.SGD_CIU_CEDULA,
									SGD_CIU_APELL1 : item.SGD_CIU_APELL1,
									SGD_CIU_APELL2 : item.SGD_CIU_APELL2,
									SGD_CIU_NOMBRE : item.SGD_CIU_NOMBRE,
									SGD_CIU_DIRECCION : item.SGD_CIU_DIRECCION,
									SGD_CIU_EMAIL : item.SGD_CIU_EMAIL,
									SGD_CIU_TELEFONO : item.SGD_CIU_TELEFONO,
									SGD_CIU_CODIGO : item.SGD_CIU_CODIGO,
									label : item.SGD_CIU_CEDULA + " " + ""
											+ item.SGD_CIU_APELL1
											+ item.SGD_CIU_APELL2 + " "
											+ item.SGD_CIU_NOMBRE,
									per : item
								}

							}));
						}
					}
				});
			},
			minLength : 3
		});

$("#txtapellido1").autocomplete(
		{
			select : function(event, ui) {
				console.log(ui.item);
				event.preventDefault();
				$("#txtapellido1").val(ui.item.SGD_CIU_APELL1);
				$("#txtapellido2").val(ui.item.SGD_CIU_APELL2);
				$("#txtnombre1").val(ui.item.SGD_CIU_NOMBRE);
				$("#txtnombre2").val();
				$("#txtdir").val(ui.item.SGD_CIU_DIRECCION);
				$("#txtemail").val(ui.item.SGD_CIU_EMAIL);
				$("#txtnfijo").val(ui.item.SGD_CIU_TELEFONO);
				$("#txtcelular").val();
				$("#tipoSexo").val();
				$("#id_ciu").val(ui.item.SGD_CIU_CODIGO)
				$("#txtdocumento").val(ui.item.SGD_CIU_CEDULA);
			},
			source : function(request, response) {
				$.ajax({
					type : "POST",
					url : basePath + '/atencion/controllerAtencion.php',
					dataType : "json",
					data : {
						op : "buscarPersonaXnombre",
						employerId : 1,
						apellido1 : $("#txtapellido1").val()
					},
					success : function(data) {
						if (data.success) {
							response($.map(data.data, function(item) {
								return {
									SGD_CIU_APELL1 : item.SGD_CIU_APELL1,
									SGD_CIU_APELL2 : item.SGD_CIU_APELL2,
									SGD_CIU_NOMBRE : item.SGD_CIU_NOMBRE,
									label : item.SGD_CIU_APELL1
											+ item.SGD_CIU_APELL2 + " "
											+ item.SGD_CIU_NOMBRE,
									per : item
								}

							}));
						}
					}
				});
			},
			minLength : 3
		});



//ESP

$("#txtnoESP").autocomplete(
		{
			select : function(event, ui) {
				console.log(ui.item);
				event.preventDefault();
				$("#idEsp").val(ui.item.SGD_CIU_CEDULA);
				$("#txtnoESP").val(ui.item.SGD_CIU_NOMBRE);
				$("#txtnitESP").val(ui.item.SGD_CIU_CEDULA);
				$("#txtRepESP").val(ui.item.SGD_CIU_APELL2);
				$("#txtDirESP").val(ui.item.SGD_CIU_DIRECCION);
				$("#txtemailESP").val(ui.item.SGD_CIU_EMAIL);
				$("#txtnfijoESP").val(ui.item.SGD_CIU_TELEFONO);
			},
			source : function(request, response) {
				$.ajax({
					type : "POST",
					url : basePath + '/atencion/controllerAtencion.php',
					dataType : "json",
					data : {
						op : "buscarEsp",
						identificacion : $("#txtnoESP").val()
					},
					success : function(data) {
						if (data.success) {
							response($.map(data.data, function(item) {
								return {
									SGD_CIU_CEDULA : item.SGD_CIU_CEDULA,
									SGD_CIU_APELL2 : item.SGD_CIU_APELL2 + " "
											+ item.SGD_CIU_APELL1,
									SGD_CIU_NOMBRE : item.SGD_CIU_NOMBRE,
									SGD_CIU_DIRECCION : item.SGD_CIU_DIRECCION,
									SGD_CIU_EMAIL : item.SGD_CIU_EMAIL,
									SGD_CIU_TELEFONO : item.SGD_CIU_TELEFONO,
									SGD_CIU_CODIGO : item.SGD_CIU_CODIGO,
									label : item.SGD_CIU_CEDULA + " "
											+ item.SGD_CIU_APELL2 + " "
											+ item.SGD_CIU_NOMBRE,
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

function checkAnonimo() {
	return  $('#chkAnonimo').is(':checked');
}

function cargarTipos() {
	$.ajax({
		type : "POST",
		url : basePath + '/atencion/controllerAtencion.php',
		data : {op : "listaTiposDoc"},
		success : function(data) {
			$("#tipoDocumento").empty();
			$("#tipoDocumento").html(data);
			$('#tipoDocumento').trigger("chosen:updated");
			$('#tipoDocumento').chosen();
		},
		error : function() {
			alert("no hay conexi\u00f3n con el servidor");
		}
	});
	$.ajax({
		type : "POST",
		url : basePath + '/atencion/controllerAtencion.php',
		data : {op : "listaTiposEmpresas"},
		success : function(data) {
			$("#tipoEmpresa").empty();
			$("#tipoEmpresa").html(data);
			$('#tipoEmpresa').trigger("chosen:updated");
			$('#tipoEmpresa').chosen();
		},
		error : function() {
			alert("no hay conexi\u00f3n con el servidor");
		}
	});
	$.ajax({
		type : "POST",
		url : basePath + '/atencion/controllerAtencion.php',
		data : {op : "listaTiposESP"},
		success : function(data) {
			$("#tipoESP").empty();
			$("#tipoESP").html(data);
			$('#tipoESP').trigger("chosen:updated");
			$('#tipoESP').chosen();
		},
		error : function() {
			alert("no hay conexi\u00f3n con el servidor");
		}
	});
	
};
