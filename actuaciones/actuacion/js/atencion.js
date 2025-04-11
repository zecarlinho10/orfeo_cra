function cargarDatos(){
		var id=document.actuacion.txtActuacion.value;//guarda el valor del select
		$.ajax({
			url : 'get_actuacion.php',		//pagina q me traera los datos 	
			data : { id:id},              //envio el valor de select a procesarAjax8
			type : 'GET',
			dataType : 'json',
			success : function(json) {   //json almacena el echo de procesarAjax8

				//asi lleno el formulario, formEditM es el name e id de mi formulario
				//luego del punto le pones el id del input que deseas poner la informacion traida con 
				// JSON
				
		        document.actuacion.idNombreActuacion.value=json.id;
		        document.actuacion.txtNombreActuacion.value=json.nombre;
		        document.actuacion.txtObjetivo.value=json.objetivo;
		        document.actuacion.txtFechaInicio.value=json.finicio;
				document.actuacion.txtFechaFin.value=json.ffin;
		        document.actuacion.txtExpediente.value=json.expediente;
		        document.actuacion.txtObservacion.value=json.observacion;
		        document.actuacion.txtEstado.selectedIndex=json.estado;
		        document.actuacion.txtTtramite.selectedIndex=json.tipoTramite;
			}
		});
	}

function valida_form() {
		/* validar(valido); */
		var salida = '';
		if (!$("#actuacion").valid()) {
			validator = $("#actuacion").validate();
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
		$("#actuacion").validate().form();
		if(salida=='')
			return true;
		else
			return false;
}

function diaSemana(dia,mes,anio){
    var dias=["dom", "lun", "mar", "mie", "jue", "vie", "sab"];
    
    var fiestas=new Array(new Array(2019,01,07), new Array(2019,03,25), new Array(2019,04,18), new Array(2019,04,19), new Array(2019,05,01), new Array(2019,06,03), new Array(2019,07,01), new Array(2019,07,20), new Array(2019,08,07), new Array(2019,08,19), new Array(2019,10,14), new Array(2019,11,04), new Array(2019,11,11), new Array(2019,12,25));

    var dt = new Date();
    dt.setFullYear(anio);
    dt.setMonth(mes-1);
    dt.setDate(dia);


    //document.getElementById('demo').innerHTML = "Dia de la semana : " + dia + "-" + mes + "-" + anio;
    var van1=1;
    var van2=1;
    while(van1!=0 && van2!=0){
    	van1=0;
    	if(dt.getUTCDay()==0 || dt.getUTCDay()==6){
    		dt.setDate(dt.getDate() + 1);
    		van1=1;
    	}
    	else{
    		van2=0;
    		for(i=0;i<fiestas.length;i++){
    			if(fiestas[i][0]==dt.getFullYear() && fiestas[i][1]==(dt.getMonth()+1) && fiestas[i][2]==dt.getDate()){
    				dt.setDate(dt.getDate() + 1);
    				van2=1;
    			}
    		}
    	}
    }
    //document.getElementById('demo').innerHTML = "Dia de la semana : " + dias[dt.getUTCDay()] + " --- fecha" + dia + "-" + mes + "-" + anio;    
    return dt;
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


function blurFunction() {
	// No focus = Changes the background color of input to red
	x=document.getElementById("txtExpediente").value;
	if(x != ""){
		$.ajax({
			url : 'controllerExpediente.php',		//pagina q me traera los datos 	
			data : { id:x},              //envio el valor de select a procesarAjax8
			type : 'POST',
			dataType : 'json',
			success : function(json) {   //json almacena el echo de procesarAjax8
				if(json.existe==0){
					document.getElementById("errorExpediente").innerHTML="               El expediente NO existe";
				}
				else{
					document.getElementById("errorExpediente").innerHTML="";
				}
			}
		});
	}
	else{
		document.getElementById("errorExpediente").innerHTML="";
	}
}

var componentes=["juridica","persona","esp"];
$(document)
.ready(
		function() {
			$.validator.setDefaults({
				ignore : ":hidden:not(select)"
			})
			$('.chosen-select').chosen();
			$('.chosen-select-deselect').chosen({
				allow_single_deselect : true
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

					//calcula fecha fin
					$("#txtFechaInicio").change(function(){
			            var myDate = new Date($(this).val());
			            var customDate = document.getElementById("txtFechaFin");
			            //$('#txtTmp').val(myDate.getMonth()); 
			            if(myDate.getMonth()>6){
			            	sumaYear=1;
			            	sumaMes=-6;
			            }
			            else{
			            	sumaYear=0;
			            	sumaMes=6;
			            }

			            myDate.setDate(myDate.getDate() + 1);
			            var dia=('0' + (myDate.getDate())).slice(-2);
			            var mes=('0' + (myDate.getMonth() + sumaMes)).slice(-2);
			            var year=myDate.getFullYear() + sumaYear;

			            nfecha=diaSemana(dia,mes,year);

			            var format = (nfecha.getFullYear()) + '-' + ('0' + (nfecha.getMonth() + 1 )).slice(-2) + '-' + ('0' + (nfecha.getDate())).slice(-2);
			            $('#txtFechaFin').val(format); 
			            
			        });
	});



function checkAnonimo() {
	return  $('#chkAnonimo').is(':checked');
}

;
