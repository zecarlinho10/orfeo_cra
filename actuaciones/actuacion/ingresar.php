<?php
error_reporting(0);

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


session_start();
if (empty($_SESSION["krd"])) {
    if (empty($_REQUEST["krd"])) {
        header('Location: ../../login.php');
    }
    include "../../rec_session.php";
}
$ruta_raiz = "../../";
$fecha = date("Ymdhis");
require_once realpath(dirname(__FILE__) . "/../../") . "/atencion/AtencionTipos.php";
require_once realpath(dirname(__FILE__) . "/../../") . "/atencion/conf_form.php";
$_SESSION["idFormulario"] = sha1(microtime(true) . mt_rand(10000, 90000));

require_once('../clases/crud_actuacion.php');
require_once('../clases/actuacion.php');
require_once('../clases/crud_tipo_tramite.php');
require_once('../clases/tipo_tramite.php');

include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

//post

$pNombreActuacion = $_POST['txtNombreActuacion'];
$pObjetivo = $_POST['txtObjetivo'];
$pFechaInicio = $_POST['txtFechaInicio'];
$pFechaFin = $_POST['txtFechaFin'];
$pExpediente = $_POST['txtExpediente'];
$pEstado = $_POST['txtEstado'];
$pTtramite = $_POST['txtTtramite'];
$pObservacion = $_POST['txtObservacion'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>.:.Ingreso Actuaciones.:.</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!--Deshabilitar modo de compatiblidad de Internet Explorer-->

<link rel="stylesheet" href="../../estilos/bootstrap.min.css"
	type="text/css" />
<link rel="stylesheet"
	href="../../estilos/CRA.bootstrap.min.css?jashd=<?php echo date("ymdhmi")?>"
	type="text/css" />

<!-- CSS -->
<link href="../../estilos/jquery-ui.css" rel="stylesheet">
	<script src="../../js/jquery.min.js"></script>
	<script src="../../js/bootstrap/bootstrap.min.js"></script>
	<script src="../../js/chosen.jquery.js"></script>
	<script src="../../js/jquery-ui.min.js"></script>
	<!-- Just to make our placeholder images work. Don't actually copy the next line! -->
	<script src="../../js/holder.min.js"></script>
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="../../js/ie10-viewport-bug-workaround.js"></script>
	<!-- Bootstrap core CSS -->
	<link href="../../estilos/bootstrap.min.css" rel="stylesheet" />
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
         var urlEntidad ='<?php echo $httpWebOficial ?>';
 	</script>
	<script type="text/javascript"
		src="../../js/bootstrap/bootstrap-dialog.js">
	</script>
	<script type="text/javascript"
		src="js/atencion.js?tes=<?php echo date("Ymdhis")?>">
	</script>
	<script type="text/javascript"
		src="../../js/divipola.js?tes=<?php echo date("Ymdhis")?>">
	</script>
	<script> 

	function marcar(source) 
		{
			checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
			for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
			{
				if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
				{
					checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
				}
			}
		}

	function confirmation() {
	    if(confirm("Confirma el ingreso de la nueva Actuación?"))
	    {
	        return true;
	    }
	    return false;
	}

	function notificacion(x){
	        //una notificación normal
	    if(x==1){
	    	alert("Actuación ingresada exitosamente."); 	
	    }
	    else{
	    	alert("Error al ingresar Actuación."); 	
	    }
	      
	      return false;
	}

	</script> 

</head>
<body id="public">
	<?php
		//TIPOS TRAMITE

		$objCrudTiposTramite = new CrudTipoTramite($db);

		if($pNombreActuacion){
			//ACTUACIONES 

			$objActuacion = new Actuacion();
			$objActuacion->setNombre($pNombreActuacion);
			$objActuacion->setObjetivo($pObjetivo);
			$objActuacion->setFechaInicio($pFechaInicio);
			$objActuacion->setFechaFin($pFechaFin);
			$objActuacion->setExpediente($pExpediente);
			$objActuacion->setEstado($pEstado);
			$objActuacion->setTipoTramite($pTtramite);
			$objActuacion->setObservacion($pObservacion);

			$objCrudActuacion = new CrudActuacion($db);
			
			if($objCrudActuacion->insertar($objActuacion)==true){
				?>
					<script> 
						notificacion(1);
					</script>
				<?
			}
			else{
				?>
					<script> 
						notificacion(0);
					</script>
				<?
			}
		}

	?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3>Formulario de ingreso Actuaciones Administrativas</h3>
		</div>
		<div class="panel-body">
			<div class="container-fluid">
				<form id="actuacion" class="" autocomplete="off"
					enctype="multipart/form-data" method="post" action="ingresar.php" name="actuacion" >
					<br />
					<div class="container-fluid col-sm-offset-3">
						<div class="pub">
							<div>
								<div class="noanonimo seltipo">
									<div class="row col-sm-12 col-md-12 dinamic">
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc desc control-label etiqueta control-label label-success form-control" for="txtNombreActuacion">Nombre Actuación*
												</label>
											</div>
											<div class="col-sm-12  col-md-9 ">
												<input type="hidden" id="idNombreActuacion" value="" name="idNombreActuacion" /> 
												<input
													class="form-control obligatorio" value="" maxlength="250"
													title="Debe Dilígenciar el Nombre de la Actuación el campo es obligatorio"
													minlenght="3" name="txtNombreActuacion" tabindex="4"
													id="txtNombreActuacion" type="text" required/>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc desc control-label etiqueta control-label label-success form-control" for="txtObjetivo">Objetivo
												</label>
											</div>
											<div class="col-sm-12  col-md-9 ">
												<input
													class="form-control" value="" maxlength="100"
													title="Debe Dilígenciar el Objetivo de la Actuación el campo es obligatorio"
													minlenght="3" name="txtObjetivo" tabindex="4"
													id="txtObjetivo" type="text" />
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="txtFechaInicio">Fecha Inicio*
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input class="form-control obligatorio" value=""
													maxlength="150" title="Debe Dilígenciar fecha de Inicio, el campo es obligatorio"
													minlenght="3" name="txtFechaInicio" tabindex="4"
													id="txtFechaInicio" type="date" required/>
											</div>
											<div class="col-sm-12  col-md-3">
												<label
													class="desc desc control-label etiqueta control-label label-success form-control" for="txtFechaFin">Fecha Fin
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input class="form-control"
													value="" maxlength="16"
													title="Dilígencia fecha fin"
													minlenght="3" name="txtFechaFin" tabindex="4"
													id="txtFechaFin" type="date" />
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="txtExpediente">Expediente
												</label>
											</div>
											<div id="errorExpediente" name="errorExpediente"></div>
											<div class="col-sm-12  col-md-3 ">
												<input class="form-control" value=""
													maxlength="18" tabindex="4"
													minlenght="3" name="txtExpediente" id="txtExpediente"
													type='tel' onblur='blurFunction()' />

											</div>
											<div class="col-sm-12  col-md-3">
												<label
													class="desc  control-label etiqueta  control-label label-success  form-control"
													for="grupo">Estado*
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<select data-placeholder="Seleccione una opción"
													title="Debe Seleccionar Estado de Actuación, este campo  es Obligatorio"
													class="chosen-select form-control dropdown seleccion"
													id="txtEstado" name="txtEstado" required>
													<option value=""></option>
													<option value="1">Activa</option>
													<option value="2">Suspendido</option>
													<option value="3">Finalizado</option>
												</select>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-3  col-md-3">
												<label
													class="desc desc     control-label etiqueta  control-label label-success  form-control"
													for="txtTtramite">Tipo de trámite*
												</label>
											</div>
											<div class="col-sm-3  col-md-3 ">
													<select data-placeholder="Seleccione una opción"
														title="Debe Seleccionar Tipo de trámite, este campo  es Obligatorio"
														class="chosen-select form-control dropdown seleccion"
														id="txtTtramite" name="txtTtramite" required>
														<option value="">-- SELECCIONE --</option>
															<?php foreach($objCrudTiposTramite->getTiposTramite() as $d):?>
															      <option value="<?php echo $d->getId(); ?>"><?php echo $d->getNombre() ?></option>
															<?php endforeach ?>
													</select>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="txtObservacion">Observación
												</label>
											</div>
											<div class="col-sm-12  col-md-6">
												<textarea class="form-control" value=""
													name="txtObservacion" id="txtObservacion"
													type="textarea" rows="2" cols="100">Digite observación</textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-8 text-center">
								<input id="saveForm" type="submit" value="Continuar"
									class="btn btn-primary" onclick="if(valida_form()==true) {return confirmation();}" />
							</div>
						</div>
				
				</form>
				
			</div>
		</div>
	</div>


 <script type="text/javascript">
  var basePath ='<?php echo $ruta_raiz?>';
  var urlOf ="<?php echo   $httpWebOficial;?>"; 
  function display_mensaje(){
	console.log(!$("#actuacion").valid());
	if($("#actuacion").valid()){
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
</body>
</html>

