<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$ruta_raiz = "../..";
session_start();
if (! $_SESSION['dependencia']) {
    header("Location: $ruta_raiz/login.php");
    echo "<script>parent.frames.location.reload();top.location.reload();</script>";
}

$fecha = date("Ymdhis");

require_once realpath(dirname(__FILE__) . "/../../") . "/atencion/AtencionTipos.php";
require_once realpath(dirname(__FILE__) . "/../../") . "/atencion/conf_form.php";
$_SESSION["idFormulario"] = sha1(microtime(true) . mt_rand(10000, 90000));

//////////////////////////////////
$ID = $_SESSION["ID"];
////////////////////////////////////

require_once('../clases/crud_actividad.php');
require_once('../clases/crud_actuacion.php');
require_once('../clases/actuacion.php');
require_once('../clases/actividad.php');
require_once('../clases/validaciones.php');

include_once ("modal_modificar.php"); 

include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

$objCrudActividades = new CrudActividades($db);
$objCrudActuaciones = new CrudActuacion($db);
$objValidaciones = new Validaciones($db);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>.:.Ingreso Actividades.:.</title>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!--Deshabilitar modo de compatiblidad de Internet Explorer-->


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	

<!-- CSS -->
<link href="../../estilos/jquery-ui.css" rel="stylesheet">
	
	<script src="../../js/chosen.jquery.js"></script>
	<script src="../../js/jquery-ui.min.js"></script>
	<!-- Just to make our placeholder images work. Don't actually copy the next line! -->
	<script src="../../js/holder.min.js"></script>
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="../../js/ie10-viewport-bug-workaround.js"></script>
	<!-- Bootstrap core CSS -->
	<link
		href="../../estilos/bootstrap-chosen.css?sdf=<?php echo date("ymdhis")?>"
		rel="stylesheet" />
	<link href="../../estilos/bootstrap-dialog.css" rel="stylesheet" />


	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->


	<link href="../../estilos/ie10-viewport-bug-workaround.css"
		rel="stylesheet" />

	<!-- Custom styles for this template -->
	<link href="../../estilos/dashboard.css" rel="stylesheet" />

	<script src="../../js/ie-emulation-modes-warning.js"></script>

	<!-- jQuery -->
	<script type="text/javascript"
		src="../../js/jquery.validate.min.js?sdf=<?php echo date("ymdhis")?>">
	</script>
	<!--funciones-->
	<script type="text/javascript">
		 var basePath ='<?php echo $ruta_raiz?>';
 	</script>
	<script type="text/javascript"
		src="../../js/bootstrap/bootstrap-dialog.js">
	</script>
	<script type="text/javascript"
		src="js/actuacion-actividad.js?tes=<?php echo date("Ymdhis")?>">
	</script>
	
	<style>
		table {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		}

		td, th {
		  border: 1px solid #dddddd;
		  text-align: left;
		  padding: 8px;
		}

		tr:nth-child(even) {
		  background-color: #dddddd;
		}
	</style>

	<script>
		

		function blurFunction() {
		  // No focus = Changes the background color of input to red
		  x=document.getElementById("txtRadicado").value;
		  
		  if(x == ""){
			  	document.getElementById("txtFuncionario").disabled=false;
				document.getElementById("txtFechaInicio").disabled=false;
				document.getElementById("txtFechaFin").disabled=false;
		  }
		  else{
			  	document.getElementById("txtFuncionario").disabled=true;
				document.getElementById("txtFechaInicio").disabled=true;
				document.getElementById("txtFechaFin").disabled=true;
				document.getElementById("txtFuncionario").value = "";
				document.getElementById("txtFechaInicio").value = "";
				document.getElementById("txtFechaFin").value = "";
		  }
		}
	</script>
	<?php
		$txtActuacion = $_POST['txtActuacion'];
		$txtActividad = $_POST['txtActividad'];
		$txtRadicado = $_POST['txtRadicado'];
		$txtObservacion = $_POST['txtObservacion'];
		$txtFuncionario = $_POST['txtFuncionario'];
		$txtFechaInicio = $_POST['txtFechaInicio'];
		$txtFechaFin = $_POST['txtFechaFin'];


		if($txtActuacion){
			if($txtRadicado && $objValidaciones->validarRadicado($txtRadicado)==0){
				echo "<script type='text/javascript'>alert('El radicado no existe');</script>";
			}

			else{
				$objActividad = new ActuacionActividad();

				$objActividad->setIdActuacion($txtActuacion);
				$objActividad->setIdActividad($txtActividad);
				$objActividad->setDescripcion($txtObservacion);
				$objActividad->setRadicado($txtRadicado);
				$objActividad->setIdEncargado($txtFuncionario);
				$objActividad->setFechaInicio($txtFechaInicio);
				$objActividad->setFechaFinal($txtFechaFin);
				?>
				<script> 
					notificacion(<? echo $objCrudActividades->insertar($objActividad) ?>);
				</script>
				<?
				if($txtActividad==22){
					?>
					<script> 
						alert("No olvide realizar Comunicación a la SSPD");
					</script>
					<?
				}
			}
		}
	?>

</head>
<body>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3>Actividades Actuaciones</h3>
		</div>
		<div >
			<div >
				<form id="frmActividaes" class="" autocomplete="off" enctype="multipart/form-data" method="post" action="ingresar.php" name="frmActividaes" >
					<br />
					<div>
						<div>
							<div>
								<div class="noanonimo seltipo">
									<div class="row col-sm-12 col-md-12 dinamic">
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label label-success form-control" for="txtActuacion">Nombre Actuación*
												</label>
											</div>
											<div class="col-sm-12  col-md-9">
												<select data-placeholder="Seleccione una opción"
													title="Debe Seleccionar Actuación, este campo  es Obligatorio"
													class="chosen-select form-control dropdown seleccion"
													id="txtActuacion" name="txtActuacion" required>
													<option value=''>-- SELECCIONE --</option>
														<?php 
	 $actua= $objCrudActuaciones->getActuaciones($ID);
	if(!empty($actua)) {	
	foreach($actua as $d):?>
														      <option value="<?php echo $d->getId(); ?>" 
														      	<?php if($d->getId()==$txtActuacion) echo 'selected';  ?> 
														      >
														      <?php echo $d->getNombre();?>
														      	
														      </option>
														<?php endforeach; } ?>
												</select>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label label-success form-control" for="txtFechaFinalizacion">Actuación
												</label>
											</div>
											<div class="col-sm-12  col-md-12 " id="fechaFinActuacion" name="fechaFinActuacion">
											</div>
										</div>
										<div class="col-sm-12  col-md-12">
											<table id="tabla" style="width:100%">
											</table>
										</div>
										
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-8 text-center">
								<input id="saveForm" type="submit" value="Guardar"
									class="btn btn-primary" onclick="if(valida_form()==true) { return confirmation()}" />
							</div>
						</div>
					</div>
				</form>
				<script type="text/javascript">
					
	                $.get("getFechaActuacion.php","txtActuacion="+<? echo $txtActuacion ?>, function(data){
						$("#fechaFinActuacion").html(data);
						console.log(data);
					});
					$.get("getActividades.php","txtActuacion="+<? echo $txtActuacion ?>, function(data){
						$("#tabla").html(data);
						console.log(data);
					});

				</script>
			</div>
		</div>
	</div>

<br/>
Resultado: <span id="resultado">0</span>

 <script type="text/javascript">
  function display_mensaje(){
	console.log(!$("#frmActividaes").valid());
	if($("#frmActividaes").valid()){
	  BootstrapDialog.alert({
			title : "Registro!!",
			message : "Informacion Registrada"
		});
	 }else{
		 valida_form();
	 }
  }

  </script>

	<!--container-->
	<div id="errores"></div>
	<div class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">×</button>
					<h4 class="modal-title glyphicon glyphicon-off">Modal title</h4>
				</div>
				<div class="modal-body">
					<p>One fine body…</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<script src="js/app.js"></script>
	<script>
		$(document).ready(function(){
			load(1);
		});
	</script>
</body>
</html>
