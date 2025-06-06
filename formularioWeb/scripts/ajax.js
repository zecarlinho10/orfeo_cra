// Contador para la cantidad de archivos subidos.
var fileCountSize = 0;
// Limite para la cantidad de archivos que se pueden subir.
var fileCountLimit = 100;
// Cantidad de archivos subidos.
var addedFiles = 0;
// Limite de subida de los archivos, en total.
var fileLimit = 20 * 1024 * 1024;
// Arregloq ue contiene los archivos subidos.
var fileNamesTmpDir = new Array();

var uploader;

/**
 * Converts the given data structure to a JSON string. Argument: arr - The data
 * structure that must be converted to JSON Example: var json_string =
 * array2json(['e', {pluribus: 'unum'}]); var json =
 * array2json({"success":"Sweet","failure":false,"empty_array":[],"numbers":[1,2,3],"info":{"name":"Binny","site":"http:\/\/www.openjs.com\/"}});
 * http://www.openjs.com/scripts/data/json_encode.php
 */
function array2json(arr) {
	var parts = [];
	var is_list = (Object.prototype.toString.apply(arr) === '[object Array]');

	for ( var key in arr) {
		var value = arr[key];
		if (typeof value == "object") { // Custom handling for arrays
			if (is_list)
				parts.push(array2json(value)); /* :RECURSION: */
			else
				parts[key] = array2json(value); /* :RECURSION: */
		} else {
			var str = "";
			if (!is_list)
				str = '"' + key + '":';

			// Custom handling for multiple data types
			if (typeof value == "number")
				str += value; // Numbers
			else if (value === false)
				str += 'false'; // The booleans
			else if (value === true)
				str += 'true';
			else
				str += '"' + value + '"'; // All other things
			// :TODO: Is there any more datatype we should be in the lookout
			// for? (Functions?)

			parts.push(str);
		}
	}
	var json = parts.join(",");

	if (is_list)
		return '[' + json + ']';// Return numerical JSON
	return '{' + json + '}';// Return associative JSON
}

// JavaScript Document
// Validacion captcha en JavaScript
// @author Sebastian Ortiz V.
var valido = false;

// Validacion segun tipo de solicitud, tipo de documento y con captcha
// @author Sebastian Ortiz V.

function valida_form() {
	$("#adjuntosSubidos").val(JSON.stringify(fileNamesTmpDir));
		/* validar(valido); */
		var salida = 'Si usted opta por presentar su comunicación en forma anónima, no será posible que reciba de manera directa respuesta por parte de esta Entidad.\nRecuerde que debe diligenciar los campos Tipo de petición, Asunto, Comentario y Código de verificación.\n';
		if (!$("#contactoOrfeo").valid()) {
			validator = $("#contactoOrfeo").validate();
			for (var i = 0; i < validator.errorList.length; i++) {
				salida += validator.errorList[i].message + "\n";
			}
			if(!checkAnonimo())
				salida = "";
			var dialog = new BootstrapDialog({
				message: function(dialogRef){
					var $message = $('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><div><img src="./images/advertenciaIcono.png" alt=""></div><div class="text-center"><h1 class="titulo-alerta">Advertencia</h1></div><div class="text-center"><p>'+salida+'</div>'
					);
			
					return $message;
				},
				size: BootstrapDialog.SIZE_SMALL,
				closable: true
			});
			dialog.realize();
			dialog.getModalHeader().hide();
			dialog.getModalFooter().hide();
			dialog.getModalBody().css('background-color', '#fff');
			dialog.getModalBody().css('border-radius', '15%');
			dialog.getModalBody().css('color', '#000');
			dialog.open();
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
var numbers = '1234567890\u0008'
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

// validacion email
// http://stackoverflow.com/questions/46155/validate-email-address-in-javascript
function isEmailAddress(theElement) {
	var s = theElement.value;
	 //var org/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*(\.+[a-zA-Z0-9])+$/
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	if (s.length == 0)
		return true;
	if (re.test(s))
		return true;
	else
		return false;
}

// Adicion de campos para adjuntos
// @author Sebastian Ortiz V.

function addInputFile() {
	var container = document.getElementById('adjuntos');
	var mpo = document.getElementById('campo_adjuntos');
	var pf = document.createElement('p');
	var ifile = document.createElement('input');
	ifile.type = 'file';
	ifile.name = 'userfile[]';
	ifile.id = 'campo_adjuntos';
	ifile.onchange = campo.onchange;
	// ifile.class ='large';
	pf.appendChild(ifile);
	container.appendChild(pf);
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
							$("#mcpio").rules("add", "required");;
						}else{
							$("#dpto").rules("remove");
							$("#mcpio").rules("remove");
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
											if($(this).hasClass("email")){
												$(this).rules("add", "email");	
											}
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
											'tipoPeticion' : {
												required : true
											},
											pais : {
												required : true
											},
											tipoPersona:{
												required : true
											}
										},
										messages : {
											'tipoPeticion' : "Debe Seleccionar el Tipo de Solicitud, Petición, Queja ., que desea instaurar ante la Entidad ",
											pais:"el campo pais es obligatorio "
												
										}
									});
					createUploader();
					

					$('[data-toggle="tooltip"]').tooltip();

					$('input:radio[name=anonimo]')
							.change(
									function() {
										if (this.value == '1') {
											$(".noanonimo").show();
											$("#tipoPersona").rules("add","required");
											$("#dpto").rules("add","required");
											$("#pais").rules("add","required");
											$("#mcpio").rules("add","required");
										} else if (this.value == '2') {
											$("#dpto").rules("remove");
											$("#pais").rules("remove");
											$("#mcpio").rules("remove");
											var componente = "";
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
												componente = "";
												break;

											}
											if (componente != null
													&& componente !== "") {
												$("#" + componente).find(
														" .obligatorio").each(
														function() {
															$(this).rules(
																	"remove");
														});
											 $("#" + componente).hide();
											}
											$(".noanonimo").hide();
													var salida = "Si usted opta por presentar su comunicación en forma anónima, no será posible que reciba de manera directa respuesta por de la entidad,\n la comunicación sera publicada en <a target='_blank' rel='noopener noreferrer' href='"+urlEntidad+"'>"+urlEntidad+" </a>";
													var dialog = new BootstrapDialog({
														message: function(dialogRef){
															var $message = $('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><div><img src="./images/advertenciaIcono.png" alt=""></div><div class="text-center"><h1 class="titulo-alerta">Advertencia</h1></div><div class="text-center"><p>'+salida+'</div>'
															);
													
															return $message;
														},
														size: BootstrapDialog.SIZE_SMALL,
														closable: true
													});
													dialog.realize();
													dialog.getModalHeader().hide();
													dialog.getModalFooter().hide();
													dialog.getModalBody().css('background-color', '#fff');
													dialog.getModalBody().css('border-radius', '15%');
													dialog.getModalBody().css('color', '#000');
													dialog.open();
										}
									});
				});
function checkAnonimo() {

	return  $('#chkAnonimo').is(':checked');
}

function countChar(val) {
	var len = val.value.length;
	if (len >= 2000) {
		val.value = val.value.substring(0, 2000);
	} else {
		$("#charNum").html(
				"Carácteres disponibles: <b>" + (2000 - len) + "</b>");
	}
};

function createUploader() {
	uploader = new qq.FineUploader(
			{
				element : document.getElementById('filelimit-fine-uploader'),
				request : {
					endpoint : 'qqUploadedFileXhr.class.php',
				},
				multiple : true,
				validation : {
					sizeLimit : 20 * 1024 * 1024
				// 5.0MB = 5 * 1024 kb * 1024 bytes
				},
				text : {
					uploadButton : '<i class="icon-upload icon-white"></i> Adjuntar soportes'
				},
				autoUpload : true,
				callbacks : {
					onSubmit : function(id, fileName) {
						if ((fileCountSize + uploader._handler._files[id].size) > fileLimit) {
							$('.qq-upload-button').hide();
							$('.qq-upload-drop-area').hide();
							var mensaje = 'El tamaño máximo permitido de subida de todos los archivos es de '
									+ uploader._formatSize(fileLimit);
							var dialog = new BootstrapDialog({
								message: function(dialogRef){
									var $message = $('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><div><img src="./images/advertenciaIcono.png" alt=""></div><div class="text-center"><h1 class="titulo-alerta">Advertencia</h1></div><div class="text-center"><p>'+mensaje+'</div>'
									);
							
									return $message;
								},
								size: BootstrapDialog.SIZE_SMALL,
								closable: true
							});
							dialog.realize();
							dialog.getModalHeader().hide();
							dialog.getModalFooter().hide();
							dialog.getModalBody().css('background-color', '#fff');
							dialog.getModalBody().css('border-radius', '15%');
							dialog.getModalBody().css('color', '#000');
							dialog.open();
							return false;
						}
						fileCountSize += uploader._handler._files[id].size;
					},
					onCancel : function(id, fileName) {
						try {
							if ($.isNumeric(uploader._handler._files[id].size)) {
								fileCountSize -= uploader._handler._files[id].size;
							}
						} catch (error) {
							// Debe ser que estamos en explorer
						}
						var index = fileNamesTmpDir.indexOf(fileName);
						if (index >= 0) {
							addedFiles--;
							fileNamesTmpDir.splice(index, 1);
							// Prevenir sacar el mensaje de archivos en
							// progreso, cuando se hace un cancel manual.
							uploader._filesInProgress++;
						}

						if (fileCountSize <= fileLimit) {
							$('.qq-upload-button').show();
						}
						$('#availabeForUpload').html(
								'Tamaño máximo por archivo de 5.0MB. Disponible '
										+ uploader._formatSize(fileLimit
												- fileCountSize));

					},
					onComplete : function(id, fileName, responseJSON) {
						if (responseJSON.success) {
							fileNamesTmpDir.push(fileName);
							addedFiles++;
							$('#availabeForUpload').html('Tamaño máximo por archivo de 20.0MB. Disponible ' + uploader._formatSize(fileLimit	- fileCountSize));
							if (addedFiles >= fileCountLimit) {
								mensaje='Has alcanzado la cantidad máxima de archivos a subir, no podrás subir más de '
										+ fileCountLimit + ' archivos.';
								var dialog = new BootstrapDialog({
									message: function(dialogRef){
										var $message = $('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><div><img src="./images/advertenciaIcono.png" alt=""></div><div class="text-center"><h1 class="titulo-alerta">Advertencia</h1></div><div class="text-center"><p>'+mensaje+'</div>'
										);
								
										return $message;
									},
									size: BootstrapDialog.SIZE_SMALL,
									closable: true
								});
								dialog.realize();
								dialog.getModalHeader().hide();
								dialog.getModalFooter().hide();
								dialog.getModalBody().css('background-color', '#fff');
								dialog.getModalBody().css('border-radius', '15%');
								dialog.getModalBody().css('color', '#000');
								dialog.open();
								$('.qq-upload-button').hide();
								$('.qq-upload-drop-area').hide();
							}
						} else {
							mensaje = 'Ocurrió un error subiendo el archivo. Por favor valida que no supere los 5.0MB y en total 10.0MB';

							var dialog = new BootstrapDialog({
								message: function(dialogRef){
									var $message = $('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><div><img src="./images/advertenciaIcono.png" alt=""></div><div class="text-center"><h1 class="titulo-alerta">Advertencia</h1></div><div class="text-center"><p>'+salida+'</div>'
									);
							
									return $message;
								},
								size: BootstrapDialog.SIZE_SMALL,
								closable: true
							});
							dialog.realize();
							dialog.getModalHeader().hide();
							dialog.getModalFooter().hide();
							dialog.getModalBody().css('background-color', '#fff');
							dialog.getModalBody().css('border-radius', '15%');
							dialog.getModalBody().css('color', '#000');
							dialog.open();
						}
					},
					onError : function(id, fileName, errorReason) {
						var mensaje = 'Ocurrió un error subiendo el archivo.'
								+ errorReason;
						var dialog = new BootstrapDialog({
							message: function(dialogRef){
								var $message = $('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><div><img src="./images/advertenciaIcono.png" alt=""></div><div class="text-center"><h1 class="titulo-alerta">Advertencia</h1></div><div class="text-center"><p>'+mensaje+'</div>'
								);
						
								return $message;
							},
							size: BootstrapDialog.SIZE_SMALL,
							closable: true
						});
						dialog.realize();
						dialog.getModalHeader().hide();
						dialog.getModalFooter().hide();
						dialog.getModalBody().css('background-color', '#fff');
						dialog.getModalBody().css('border-radius', '15%');
						dialog.getModalBody().css('color', '#000');
						dialog.open();

					},
				},
				debug : true
			});
	$('#availabeForUpload').html(
			'Disponible ' + uploader._formatSize(fileLimit - fileCountSize));
}
