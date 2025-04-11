<?php
$ruta_raiz = "../";
session_start();
if (empty($_SESSION["krd"])) {
    if (empty($_REQUEST["krd"])) {
        header('Location: ../login.php');
    }
    include "../rec_session.php";
}
require_once realpath(dirname(__FILE__) . "/../") . "/atencion/AtencionTipos.php";

$fecha = date("Ymdhis");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="../favicon.png">

<title>Dashboard Template for Bootstrap</title>
<!-- Placed at the end of the document so the pages load faster -->
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/chosen.jquery.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<script src="../js/chosen.jquery.js"></script>
<!-- Just to make our placeholder images work. Don't actually copy the next line! -->
<script src="../js/holder.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../js/ie10-viewport-bug-workaround.js"></script>
<!-- Bootstrap core CSS -->
<link href="../estilos/bootstrap.min.css" rel="stylesheet">
<link href="../estilos/bootstrap-chosen.css" rel="stylesheet">
<link href="../estilos/jquery-ui.css" rel="stylesheet" />



<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="../estilos/ie10-viewport-bug-workaround.css"
	rel="stylesheet">

<!-- Custom styles for this template -->
<link href="../estilos/dashboard.css" rel="stylesheet">
<link href="../estilos/editor.css" rel="stylesheet" />
<link href="../estilos/font-awesome.min.css" rel="stylesheet" />
<link href="../js/summernote/summernote.css"rel="stylesheet" />
<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="../.../js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="../js/ie-emulation-modes-warning.js"></script>
<script type="text/javascript" src="../js/summernote/summernote.min.js"></script>
    i
<script type="text/javascript"
	src="../js/jquery.validate.min.js?sdf=<?php echo date("ymdhis")?>"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="../js3/html5shiv.min.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
<script type="text/javascript">
function cargarDep(){
		$.ajax({
			type : "POST",
			url : basePath+ '/atencion/controllerAtencion.php',
			data : {op:"listaDependencia"},
			success : function(data) {
				$("#dependencia").empty();
				$("#dependencia").html(data);
				$('#dependencia').trigger("chosen:updated");
				$('#dependencia').chosen();
				},
				error : function() {
					alert("no hay conexi\u00f3n con el servidor");
					}
					});
}

$(document).ready( function() {
	$.validator.setDefaults({
		ignore: [":hidden:not(#contenido),.note-editable.panel-body"]
	})
	cargarDep();
    var v = $("#formularioRadicar").validate({
				rules : {
					respuesta:{ required : true},
					dependencia:{required : true},
					tipoPeticion : {required : true},
                    contenido : {required : true}
					
				},
				messages : {
					'respuesta' : "Debe Seleccionar el Tipo de respuesta, que sera generada",
					'tipoPeticion' : "Debe Seleccionar el Tipo de Solicitud, Petición, Queja ., que desea instaurar ante la Entidad ",
					dependencia:"Debe seleccionar la dependencia"
				}
			});
	 $('input:radio[name=respuesta]').change(function() {
		 //alert($(this).val());
	        if ($(this).val()== '2') {
	        	$("#dependeForm").show()
					$("#dependencia").prop("disabled",false);
				$("#rsept").hide();
				$("#respuestas").removeAttr("required");
	        }
	        else if ($(this).val() == '1') {
	        	$("#dependeForm").hide()
					$("#dependencia").prop("disabled",true);
				$("#rsept").show();
				$("#respuestas").attr("required","true");


	        }
	 });
	   var respu=$("#respuestas");
	respu.summernote(
			{
				callbacks: {
				onChange: function(contents, $editable) {
				 	respu.val(respu.summernote('isEmpty') ? "" : contents);
				 	v.element(respu);
					}
				}
			}
	  );
        var editor =$("#contenido");
        editor.summernote(
            {
                callbacks: {
                    onChange: function(contents, $editable) {
                        editor.val(editor.summernote('isEmpty') ? "" : contents);
                        v.element(editor);
                    }
                }
            }
        );
	});
	
var basePath ='<?php echo $ruta_raiz?>';
	$(function() {
		
		$('.chosen-select').chosen();
		$('.chosen-select-deselect').chosen({
			allow_single_deselect : true
		});
	});
	
	
	
</script>
</head>
<body>
	<div class="container-fluid">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3>Radicación</h3>
			</div>
			<div class="panel-body">
				<form role="form" name="formularioRadicar" id="formularioRadicar"
					action="generarRadicado.php" method="post">
					<div class="row">
						<div class="col-md-2 "></div>
						<div class="col-md-10 ">
					
					<?php
                         foreach ($_POST as $clave => $value) {
                             echo "<input type='hidden' name='$clave' value='$value' />";
                         }
    
                     ?>
					<div class="row form-group">
							<div class="row form-group col-sm-12  col-md-12 text-center">
								<div class="col-sm-2  col-md-2 ">
									<label
										class="etiqueta  control-label label-success form-control"
										for="txtnombre2">Tipo de Respuesta</label>
								</div>
								<div class="col-sm-4  col-md-4 ">
									<label class="radio-inline">
										<!-- 29/10/2018 CARLOS RICAURTE se oculta type="radio" value="1" name="respuesta">Inmediata -->
										<!--  <input type="radio" value="1" name="respuesta">Inmediata</label> -->
										<label class="radio-inline">
											<input type="radio" value="2" name="respuesta" checked> Respuesta Posterior</label> 
										<label for="respuesta" class="error" style="display: none;">Por favor Seleccione Una opción</label>
								</div>
							</div>

						</div>
						<div class="row col-sm-12  col-md-12" id="dependeForm">
							<div class="row form-group">
								<div class="col-sm-2  col-md-2 ">
									<label
										class="etiqueta  control-label label-success form-control"
										for="tipoDocumento">Dependencia*</label>
								</div>
								<div class="col-sm-4  col-md-4 ">
									<label for="dependencia" class="error" style="display: none;">debe
										seleccionar la dependencia</label> <select
										data-placeholder="dependencia"
										class="chosen-select form-control dropdown seleccion required"
										id="dependencia" name="dependencia" required>
									</select>
								</div>
							</div>
						</div>
						<div class="row form-group">
							<div class="col-sm-2  col-md-2 ">
								<label
									class="etiqueta  control-label label-success form-control"
									for="tipoPersona">Asunto</label>
							</div>
							<div class="col-sm-8  col-md-8 ">
								<input class="form-control" id="asunto" type="text"
									name="asunto" required
									title="el asunto de la solicitud es obligatorio">
							</div>
						</div>
						<div class="row col-sm-12  col-md-12 dinamic" id="persona">
							<div class="row form-group">
								<div class="col-sm-2  col-md-2 ">
									<label
										class="etiqueta  control-label label-success form-control"
										for="tipoDocumento">Solicitud</label>
								</div>
								<div class="col-sm-8  col-md-8 ">
									<textarea class="form-control" id="contenido" cols="10"
										name="contenido" placeholder="Escriba ac&aacute; ..."
										minlength="6",
                                        required,
										title="la descripción de la solicitud es obligatorio y debe ser de máximo 2000 carácteres y minímo 3"></textarea>
								</div>
							</div>
						</div>
						<div class="row col-sm-12  col-md-12 dinamic" style="display:none" id="rsept">
                             <div class="row form-group">
                                 <div class="col-sm-2  col-md-2 ">
                                     <label
                                         class="etiqueta  control-label label-success form-control"
                                         for="respuesta">Respuesta</label>
                                 </div>
                                 <div class="col-sm-8  col-md-8 ">
                                     <textarea class="form-control" id="respuestas" cols="10"
                                         name="respuestas" placeholder="Escriba ac&aacute; ..."
                                         minlength="6",
                                         title="el contenido de la respuesta es obligatorio y debe ser de máximo 2000 carácteres y minímo 3"></textarea>
                                 </div>
                             </div>
                         </div>
						 <div class="row form-group">
						 <div class = "col-sm-2  col-md-2"></div>
						<div class = "col-sm-10  col-md-10">
						  <?php
	                         $atencioTipos = new AtencionTipos();
	                         $atencionTipo = $atencioTipos->findActivas();
                            if (! empty($atencionTipo)) {
                                foreach ($atencionTipo as $clave => $value) {
                            echo '
                                    <div class="row">
                                    <div class="col-md-3   text-justify">
									<input type="radio" name="tipoPeticion" value="' . $value["id"] . '" id="' . strtolower($value["nombre"]) . '" />
									<label for="department_37"><strong> ' . strtoupper($value["nombre"]) . ': </strong> </label>
                                    </div>
                                    <div class="col-md-7 text-justify"><span class =" text-justify">
									' . $value["descripcion"] . '
									 </span>
                                    </div>
                                    </div>
                                 ';
                                }
                            }
                          ?>
                         <label for="tipoPeticion" class="error" style="display: none;">Por
                                     favor Seleccione Una opción</label>
                         </div>
						 </div>
                    </div>
                    <div class="row col-sm-12  col-md-12 text-center" id="continuar">
                    		<button type="submit" name="op" class="btn btn-primary">Radicar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>

