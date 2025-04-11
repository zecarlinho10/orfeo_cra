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


function validaPagina(res,pag) {
	let resolucion = res;
	let pagina = pag;
	let error=false;
	let mensaje="";
	//let retorno = false;

	if(resolucion=="768" && pagina == 2){
		let error=false;
		let mensaje="";
		if(!document.getElementById('C01_acueducto').checked && !document.getElementById('C01_alcatarillado').checked){
			mensaje+="Seleccione tipo de servicio, ";	
			error=true;
		}
		if(!document.getElementById('C01_urbano').checked && !document.getElementById('C01_rural').checked){
			mensaje+="Seleccione Donde presta servicio, ";
			error=true;
		}
		if(document.getElementById('C05_fijo').checked && document.getElementById('C05_duracion').value==""){
			mensaje+="Diligencie duracion(meses)";	
			error=true;
		}
		if(error){
			alert (mensaje);
		}
		else{
			if(document.getElementById('C01_acueducto').checked){
				$(".pagina2").hide();
				$(".pagina3").show();
			}
			else {
				$(".pagina2").hide();
				$(".pagina4").show();	
			}
		}
	}

	if(resolucion=="768" && pagina == 3){
		if(document.getElementById('C01_alcatarillado').checked){
			$(".pagina3").hide();
			$(".pagina4").show();
		}
		else {
			$(".pagina3").hide();
			$(".pagina5").show();	
		}
	}

	if(resolucion=="7681" && pagina == 4){
		if(document.getElementById('C01_acueducto').checked){
			$(".pagina4").hide();
			$(".pagina3").show();
		}
		else {
			$(".pagina4").hide();
			$(".pagina2").show();	
		}
	}

	if(resolucion=="7681" && pagina == 5){
		if(document.getElementById('C01_alcatarillado').checked){
			$(".pagina5").hide();
			$(".pagina4").show();
		}
		else {
			$(".pagina5").hide();
			$(".pagina3").show();	
		}
	}

	if(resolucion=="873" && pagina == 3){
		if(document.getElementById('C02_acueducto').checked && document.getElementById('C35_diametro_minimo_acueducto').value==""){
			mensaje+="Dilgencie diametro acueducto, ";	
			error=true;
		}
		if(document.getElementById('C02_alcatarillado').checked && document.getElementById('C35_alcantarillado').value==""){
			mensaje+="Dilgencie diametro alcantarillado, ";	
			error=true;
		}
		if(error){
			alert (mensaje);
			return false;
		}
		else{
			$(".pagina3").hide();
			$(".pagina4").show();
			return true;
		}
	}

	if(resolucion=="8731" && pagina == 4){
		if(document.getElementById('C02_acueducto').checked && document.getElementById('C37_horas').value==""){
			mensaje+="Dilgencie continuidad de acueducto, ";	
			error=true;
		}
		if(document.getElementById('C02_acueducto').checked && document.getElementById('C37_metros').value==""){
			mensaje+="Dilgencie presión de servicio mínima.";	
			error=true;
		}
		if(error){
			alert (mensaje);
			event.preventDefault();
   			event.currentTarget.submit();
			return false;
		}
		else{
			return true;
		}
	}

	if(resolucion=="7781" && pagina == 2){
		if(!document.getElementById('C01_residuo').checked && 
			!document.getElementById('C01_transfer').checked && 
			!document.getElementById('C01_barrido').checked && 
			!document.getElementById('C01_cesped').checked && 
			!document.getElementById('C01_poda').checked && 
			!document.getElementById('C01_lavado').checked && 
			!document.getElementById('C01_playas').checked &&
			!document.getElementById('C01_cestar').checked && 
			!document.getElementById('C01_tratam').checked && 
			!document.getElementById('C01_dispfin').checked){

			mensaje+="Seleccione Por lo menos una actividad, ";
			error=true;
		}
		if(document.getElementById('C07_area').value==""){
			mensaje+="Debe diligenciar el area de prestacion.";
			error=true;
		}
		if(error){
			alert (mensaje);
			event.preventDefault();
   			event.currentTarget.submit();
			return false;
		}
		else{
			return true;
		}

	}

	if(resolucion=="873AAA1"  && pagina ==2 ){
		if(!document.getElementById('C02_acueducto').checked && !document.getElementById('C02_alcatarillado').checked){
			mensaje+="Seleccione tipo de servicio";	
			error=true;
		}
		if(document.getElementById('C04_area').value==""){
			mensaje+="Diligenciar nombre de la APS, ";
			error=true;
		}
		
		if(document.getElementById('C04_departamento').value==""){
			mensaje+="Diligenciar Departamento, ";
			error=true;
		}
		if(document.getElementById('C04_municipio').value==""){
			mensaje+="Digite Municipio, ";
			error=true;
		}
		if(!document.getElementById('C03_urbano').checked && !document.getElementById('C03_rural').checked){
			mensaje+="Seleccione Donde presta servicio, ";
			error=true;
		}
		if(document.getElementById('C05_fijo').checked && document.getElementById('C05_duracion').value==""){
			mensaje+="Diligencie duracion(meses)";	
			error=true;
		}
		if(error){
			alert (mensaje);
		}
		else{
			$(".pagina2").hide();
			$(".pagina3").show();
		}
	}

	else if(resolucion=="873AAA2"  && pagina ==2 ){

						
							if(document.getElementById('C00_persona_prestadora').value==""){
								mensaje+="Digite persona prestadora. ";
								error=true;
							}
							
							
							if(document.getElementById('C00_nit').value==""){
								mensaje+="Digite Nit";	
								error=true;
							}
							if(document.getElementById('C00_direccion').value==""){
								mensaje+="Digite direccion,";
								error=true;
							}
							if(document.getElementById('C00_barrio').value==""){
								mensaje+="Digite barrio";	
								error=true;
							}
							if(document.getElementById('C00_municipio').value==""){
								mensaje+="Digite municipio,";
								error=true;
							}
							if(document.getElementById('C00_departamento').value==""){
								mensaje+="Digite Departamento";	
								error=true;
							}
							if(document.getElementById('C00_correo').value==""){
								mensaje+="Digite correo,";
								error=true;
							}
							if(document.getElementById('C00_horario').value==""){
								mensaje+="Digite horario de atencion";	
								error=true;
							}
							if(error){
								alert (mensaje);
							}
							else{
								$(".pagina2").hide();
								$(".pagina3").show();
							}
							
	}
	else if(resolucion=="873AAA2"  && pagina ==3 ){
							if(!document.getElementById('C02_acueducto').checked && !document.getElementById('C02_alcatarillado').checked){
								mensaje+="Seleccione tipo de servicio";	
								error=true;
							}
							if(document.getElementById('C05_fijo').checked && document.getElementById('C05_duracion').value==""){
								mensaje+="Diligencie duracion(meses)";	
								error=true;
							}
							if(error){
								alert (mensaje);
							}
							else{
								$(".pagina3").hide();
								$(".pagina4").show();
							}
	}

	else if(resolucion=="8941"  && pagina ==4 ){
		if(!document.getElementById('C02_primer_segmento').checked && 
			!document.getElementById('C02_segundo_segmento').checked && 
			!document.getElementById('C02_tercer_segmento').checked && 
			!document.getElementById('C02_dif_acceso').checked && 
			!document.getElementById('C02_aps').checked){

			mensaje+="Seleccione Por lo menos un ESQUEMA O SEGMENTO, ";
			error=true;
		}
		if(!document.getElementById('C03_rec_trans').checked && 
			!document.getElementById('C03_transferencia').checked && 
			!document.getElementById('C03_barrylimp').checked && 
			!document.getElementById('C03_corte').checked && 
			!document.getElementById('C03_poda').checked &&
			!document.getElementById('C03_lavado').checked && 
			!document.getElementById('C03_playas').checked && 
			!document.getElementById('C03_cestas').checked &&
			!document.getElementById('C03_tratamiento').checked && 
			!document.getElementById('C03_disposicion').checked){

			mensaje+="Seleccione Por lo menos un SERVICIO, ";
			error=true;
		}
		if(!document.getElementById('C01_urbano').checked && !document.getElementById('C01_rural').checked){
			mensaje+="Seleccione Donde presta servicio, ";
			error=true;
		}

		if(document.getElementById('C05_fijo').checked && document.getElementById('C05_duracion').value==""){
			mensaje+="Diligencie duracion(meses)";	
			error=true;
		}
		if(error){
			alert (mensaje);
			return false;
		}
		else{
				$(".pagina3").hide();
				$(".pagina4").show();
		}
	}
	//return retorno;
}

function valida_form() {
	
	var salida="";
	$("#adjuntosSubidos").val(JSON.stringify(fileNamesTmpDir));
	if (!$("#contactoOrfeo").valid()) {
		var salida="";
		validator = $("#contactoOrfeo").validate();
		for (var i = 0; i < validator.errorList.length; i++) {
			salida += validator.errorList[i].message + "\n";
		}

		BootstrapDialog.alert({
			title : "Error!!",
			message : salida
		});
		return false;
	}
	return true;
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
	//alert(parseInt(e.keyCode));
	if(parseInt(e.keyCode)==13) {
		return false;
	}
	else{
		k = document.all ? parseInt(e.keyCode) : parseInt(e.which);
		return (allow.indexOf(String.fromCharCode(k)) != -1 || e.keyCode == 9);
	}
}

function alphaField(e, allow) {
	var k;
	r = true;
	for (var i = 0; i < e.length; i++) {
		if (allow.indexOf(e.charAt(i)) == -1)
			return false;
	}
	//if (allow.indexOf(e.charAt(i)) == -1)
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
					
					$("#dpto").change(function() {
						$("#mcpio").rules("add", "required");
					});
					
					$("#C05_fijo").click(
							function() {
								$(".divfijo").show();
							});
					

					$("#C05_indefinida").click(
							function() {
								$(".divfijo").hide();
							});

					$("#previous2").click(
							function() {
								$(".pagina2").hide();
								$(".pagina1").show();

							});

					$("#next2").click(

						function() {
							
							let error=false;
							let mensaje="";
							if(!document.getElementById('C01_acueducto').checked && !document.getElementById('C01_alcatarillado').checked){
								mensaje+="Seleccione tipo de servicio, ";	
								error=true;
							}
							if(!document.getElementById('C01_urbano').checked && !document.getElementById('C01_rural').checked){
								mensaje+="Seleccione Donde presta servicio, ";
								error=true;
							}
							
							if(document.getElementById('C05_fijo').checked && document.getElementById('C05_duracion').value==""){
								mensaje+="Diligencie duracion(meses)";	
								error=true;
							}
							if(error){
								alert (mensaje);
							}
							else{
								$(".pagina2").hide();
								$(".pagina3").show();
							}
							
							/*
							if(valida_form()){
								$(".pagina2").hide();
								$(".pagina3").show();
							}
							*/
						});

					$("#next1").click(
							
							
							function() {
								
								if(valida_form()){
									$(".pagina1").hide();
									$(".pagina2").show();

									$(this).rules("remove");
								}
							});

					

					$("#previous3").click(
							function() {
								$(".pagina3").hide();
								$(".pagina2").show();

							});

					$("#next3").click(
						function() {
								$(".pagina3").hide();
								$(".pagina4").show();
						});

					$("#previous4").click(
							function() {
								$(".pagina4").hide();
								$(".pagina3").show();

							});

					$("#next4").click(
							function() {						
								$(".pagina4").hide();
								$(".pagina5").show();
						});

					$("#previous5").click(
							function() {
								$(".pagina5").hide();
								$(".pagina4").show();

							});

					$("#previous6").click(
							function() {
								$(".pagina6").hide();
								$(".pagina5").show();

							});

					$("#next5").click(
							function() {
								$(".pagina5").hide();
								$(".pagina6").show();

							});

					$("#previous7").click(
							function() {
								$(".pagina7").hide();
								$(".pagina6").show();

							});

					$("#next6").click(
							function() {
								$(".pagina6").hide();
								$(".pagina7").show();

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
									$("#txtnit").rules("add","required");
								} else if (valor == 2) {
									componente = "persona";
									$("#txtdocumento").rules("add","required");
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
											tipoPersona:{
												required : true
											},
											dpto : {
												required : true
											}
										},
										messages : {
											tipoPersona : "Debe Seleccionar el Tipo de Persona",
											tipo:"el campo Departamento es obligatorio "
												
										}
									});
					createUploader();
					

					$('[data-toggle="tooltip"]').tooltip();

		});


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
				// 20.0MB = 20 * 1024 kb * 1024 bytes
				},
				text : {
					uploadButton : '<i class="icon-upload icon-white"></i> Anexar mapa'
				},
				autoUpload : true,
				callbacks : {
					onSubmit : function(id, fileName) {
						if ((fileCountSize + uploader._handler._files[id].size) > fileLimit) {
							$('.qq-upload-button').hide();
							$('.qq-upload-drop-area').hide();
							var mensaje = 'El tamaño máximo permitido de subida de todos los archivos es de '
									+ uploader._formatSize(fileLimit);
							BootstrapDialog.alert({
								title : "Error!!",
								message : mensaje
							});
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
								'Tamaño máximo por archivo de 20.0MB. Disponible '
										+ uploader._formatSize(fileLimit
												- fileCountSize));

					},
					onComplete : function(id, fileName, responseJSON) {
						if (responseJSON.success) {
							fileNamesTmpDir.push(fileName);
							addedFiles++;
							$('#availabeForUpload').html(
									'Tamaño máximo por archivo de 20.0MB. Disponible '
											+ uploader._formatSize(fileLimit
													- fileCountSize));
							if (addedFiles >= fileCountLimit) {
								mensaje='Has alcanzado la cantidad máxima de archivos a subir, no podrás subir más de '
										+ fileCountLimit + ' archivos.';
								BootstrapDialog.alert({
									title : "Error!!",
									message : mensaje
								});
								$('.qq-upload-button').hide();
								$('.qq-upload-drop-area').hide();
							}
						} else {
							mensaje = 'Ocurrió un error subiendo el archivo. Por favor valida que no supere los 5.0MB y en total 10.0MB';
							BootstrapDialog.alert({
								title : "Error!!",
								message : mensaje
							});
						}
					},
					onError : function(id, fileName, errorReason) {
						var mensaje = 'Ocurrió un error subiendo el archivo.'
								+ errorReason;
						BootstrapDialog.alert({
							title : "Error!!",
							message : mensaje
						});

					},
				},
				debug : true
			});
	$('#availabeForUpload').html(
			'Disponible ' + uploader._formatSize(fileLimit - fileCountSize));
}
