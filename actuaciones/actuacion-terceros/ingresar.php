<?php

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

require_once('../clases/crud_prestador.php');
require_once('../clases/prestador.php');
require_once('../clases/crud_actuacion.php');
require_once('../clases/actuacion.php');
require_once('../clases/crud_involucrado.php');
require_once('../clases/involucrado.php');
require_once('../clases/terceros.php');


include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

$objCrudActuaciones = new CrudActuacion($db);
$objTerceros = new Terceros($db);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>.:.Tercero Actuacion.:.</title>
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
 	</script>
	<script type="text/javascript"
		src="../../js/bootstrap/bootstrap-dialog.js">
	</script>
	<script type="text/javascript"
		src="js/actuacion-tercero.js?tes=<?php echo date("Ymdhis")?>">
	</script>
	
	<script type="text/javascript">
		$(document).ready(
			function() {

			$("#btAddFuncionario").click(function(){
	            var tds=$("#tabla tr:first td").length;
	            var trs=$("#tabla tr").length;
	            
	            var nuevaFila = "<tr><td class='col-sm-12 col-md-3'><select data-placeholder='Seleccione una opcion' title='Debe Seleccionar Funcionario, este campo  es Obligatorio' class='chosen-select form-control'  id='txtFuncionario[]' name='txtFuncionario[]' required><option value=''>-- SELECCIONE --</option><?php foreach($objTerceros->getFuncionarios() as $d):?><option value='<?php echo $d->getId(); ?>'><?php echo $d->getNombre(); ?></option><?php endforeach ?></select></td></tr>";
	            $("#tabla").append(nuevaFila);
	        });

	        $("#btRemoveFuncionario").click(function(){
	            var trs=$("#tabla tr").length;
	            if(trs>1)
	            {
	                $("#tabla tr:last").remove();
	            }
	        });
		});

		
	</script>


	<?php
		$txtActuacion = $_POST['txtActuacion'];
		$id_emp1 = $_POST['id_emp1'];
		$id_emp2 = $_POST['id_emp2'];
		$txtCoordinador = $_POST['txtCoordinador'];
		$txtLiderOAJ = $_POST['txtLiderOAJ'];
		$txtLiderRegulacion = $_POST['txtLiderRegulacion'];
		$txtExpediente = $_POST['txtExpediente'];
		$txtFuncionario = $_POST['txtFuncionario'];
		$txtAdministrador = $_POST['txtAdministrador'];
		
		if($txtActuacion){
			$objCrudPrestador = new CrudPrestador($db);
			$objCrudInvolucrado = new CrudInvolucrado($db);

			//LIMPIAR INVOLUCRADOS
			$objCrudInvolucrado->borrarInvolucrados($txtActuacion);
			
			//INSERTA PRESTADOR 1
			$objPrestador = new Prestador();
			$objPrestador->setIdActuacion($txtActuacion);
			$objPrestador->setIdOEM($id_emp1);

			
			if($objCrudPrestador->insertar($objPrestador)==1)

			//INSERTA PRESTADOR 2
			if($id_emp2){
				$objPrestador2 = new Prestador();
				$objPrestador2->setIdActuacion($txtActuacion);
				$objPrestador2->setIdOEM($id_emp2);
				
				$objCrudPrestador->insertar($objPrestador2);
			}

			//INSERTA EXPERTO
			$objInvolucrado = new Involucrado();
			$objInvolucrado->setIdActuacion($txtActuacion);
			$objInvolucrado->setIdFuncionario($txtCoordinador);
			$objInvolucrado->setidRol(1);//1 EXPERTO

			if($objCrudInvolucrado->insertar($objInvolucrado)==1)

			//INSERTAR JEFES
			//INSERTA JEFE OAJ
			$objInvolucrado = new Involucrado();
			$objInvolucrado->setIdActuacion($txtActuacion);
			$objInvolucrado->setIdFuncionario($txtLiderOAJ);
			$objInvolucrado->setidRol(2);//2 JEFE

			if($objCrudInvolucrado->insertar($objInvolucrado)==1)

			//INSERTA JEFE REGULACION
			$objInvolucrado = new Involucrado();
			$objInvolucrado->setIdActuacion($txtActuacion);
			$objInvolucrado->setIdFuncionario($txtLiderRegulacion);
			$objInvolucrado->setidRol(2);//2 JEFE

			if($objCrudInvolucrado->insertar($objInvolucrado)==1)
				
			//INSERTA ADMINISTRADOR
			$objInvolucrado = new Involucrado();
			$objInvolucrado->setIdActuacion($txtActuacion);
			$objInvolucrado->setIdFuncionario($txtAdministrador);
			$objInvolucrado->setidRol(4);//4 administrador

			if($objCrudInvolucrado->insertar($objInvolucrado)==1)

			//INSERTA FUNCIONARIOS

			foreach ($txtFuncionario as $funcionario) {
			    $objInvolucrado = new Involucrado();
				$objInvolucrado->setIdActuacion($txtActuacion);
				$objInvolucrado->setIdFuncionario($funcionario);
				$objInvolucrado->setidRol(3);//2 FUNCIONARIO

				$objCrudInvolucrado->insertar($objInvolucrado);
			}
				?>
					<script> 
						notificacion(1);
					</script>
				<?
		}
	?>

</head>
<body id="public">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3>Formulario de Asociación de Actuaciones e Involucrados</h3>
		</div>
		<div class="panel-body">
			<div class="container-fluid">
				<form id="frmTerceros" class="" autocomplete="off" enctype="multipart/form-data" method="post" action="ingresar.php" name="frmTerceros" >
					<br />
					<div class="container-fluid col-sm-offset-3">
						<div class="pub">
							<div>

								<div class="noanonimo seltipo">
									<div class="row col-sm-12 col-md-12 dinamic">
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc desc control-label etiqueta control-label label-success form-control" for="txtActuacion">Nombre Actuación*
												</label>
											</div>
											<div class="col-sm-12  col-md-9 ">
												<select data-placeholder="Seleccione una opción"
													title="Debe Seleccionar Actuación, este campo  es Obligatorio"
													class="chosen-select form-control dropdown seleccion"
													id="txtActuacion" name="txtActuacion" required>
													<option value=''>-- SELECCIONE --</option>
													
														<?php foreach($objCrudActuaciones->getActuaciones(-1) as $d):?>
														      <option value="<?php echo $d->getId(); ?>"><?php $salida=$d->getNombre() . " - " . $d->getPrestador($db); echo $salida;?>
														      	
														      </option>
														<?php endforeach ?>
												</select>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="txtnoEmpresa1">Nombre Prestador 1
												</label>
											</div>
											<div class="col-sm-12  col-md-9 ">
												<input type="hidden" id="id_emp1" value="" name="id_emp1" /> 
												<input class="form-control obligatorio" value=""
													maxlength="150" title="el campo Nombre es obligatorio "
													minlenght="3" name="txtnoEmpresa1" tabindex="4"
													id="txtnoEmpresa1" type="text" />
											</div>
										</div>
										<div id="emp2" class="row form-group" style="display: none;">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="txtnoEmpresa2">Nombre Prestador 2
												</label>
											</div>
											<div class="col-sm-12  col-md-9 ">
												<input type="hidden" id="id_emp2" value="" name="id_emp2" /> 
												<input class="form-control obligatorio" value=""
													maxlength="150" title="el campo Nombre es obligatorio "
													minlenght="3" name="txtnoEmpresa2" tabindex="4"
													id="txtnoEmpresa2" type="text" />
											</div>
										</div>
										<div id="empresa2">
										</div>
										<div class="row form-group">
											<div id="div_adiciona" class="col-sm-12  col-md-3">
												<input type="button" id="btAdd" name="btAdd" value="Añadir Prestador" class="btn btn-primary" />
											</div>
											<div id="div_quita" class="col-sm-12  col-md-3" >
												<input type="button" id="btDel" name="btDel" value="Quitar Prestador" class="btn btn-primary" />
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc desc control-label etiqueta control-label label-success form-control" for="txtCoordinador">Coordinador
												</label>
											</div>
											<div class="col-sm-12  col-md-9 ">
												<select data-placeholder="Seleccione una opción"
													title="Debe Seleccionar Un Coordinador, este campo  es Obligatorio"
													class="chosen-select form-control dropdown seleccion"
													id="txtCoordinador" name="txtCoordinador" required>
													<option value="">-- SELECCIONE --</option>
														<?php foreach($objTerceros->getExpertos() as $d):?>
														      <option value="<?php echo $d->getId(); ?>"><?php echo $d->getNombre(); ?>
														      	
														      </option>
														<?php endforeach ?>
												</select>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc desc control-label etiqueta control-label label-success form-control" for="txtAdministrador">Administrador
												</label>
											</div>
											<div class="col-sm-12  col-md-9 ">
												<select data-placeholder="Seleccione una opción"
													title="Debe Seleccionar Un Administrador, este campo  es Obligatorio"
													class="chosen-select form-control dropdown seleccion"
													id="txtAdministrador" name="txtAdministrador" required>
													<option value="">-- SELECCIONE --</option>
														<?php foreach($objTerceros->getFuncionarios() as $d):?>
														      <option value="<?php echo $d->getId(); ?>"><?php echo $d->getNombre(); ?>
														      	
														      </option>
														<?php endforeach ?>
												</select>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="txtLiderOAJ">Líder OAJ
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<select data-placeholder="Seleccione una opción"
													title="Debe Seleccionar Lider OAJ, este campo  es Obligatorio"
													class="chosen-select form-control dropdown seleccion"
													id="txtLiderOAJ" name="txtLiderOAJ" required>
													<option value="">-- SELECCIONE --</option>
														<?php foreach($objTerceros->getJefe(12) as $d):?>
														      <option value="<?php echo $d->getId(); ?>"><?php echo $d->getNombre(); ?>
														      </option>
														<?php endforeach ?>
												</select>
											</div>
											<div class="col-sm-12  col-md-3">
												<label
													class="desc  control-label etiqueta  control-label label-success  form-control"
													for="txtLiderRegulacion">Líder SR*
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<select data-placeholder="Seleccione una opción"
													title="Debe Seleccionar Lider Regulacion de Actuación, este campo  es Obligatorio"
													class="chosen-select form-control dropdown seleccion"
													id="txtLiderRegulacion" name="txtLiderRegulacion" required>
													<option value="">-- SELECCIONE --</option>
														<?php foreach($objTerceros->getJefe(30) as $d):?>
														      <option value="<?php echo $d->getId(); ?>"><?php echo $d->getNombre(); ?>
														      </option>
														<?php endforeach ?>
												</select>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="txtExpediente">Equipo de trabajo
												</label>
											</div>
										</div>
										<div class="row form-group">
											<table id="tabla">
												
											</table>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<input type="button" class="btn btn-primary" id="btAddFuncionario" value="Añadir Funcionario" class="bt" />
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input type="button" class="btn btn-primary" id="btRemoveFuncionario" value="Eliminar Funcionario" class="bt" />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-8 text-center">
								<input id="saveForm" type="submit" value="Continuar"
									class="btn btn-primary" onclick="if(valida_form()==true) { return confirmation()}" />
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

 <script type="text/javascript">
  function display_mensaje(){
	console.log(!$("#frmTerceros").valid());
	if($("#frmTerceros").valid()){
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
