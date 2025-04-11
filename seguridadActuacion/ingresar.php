<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

session_start();
if (empty($_SESSION["krd"])) {
    if (empty($_REQUEST["krd"])) {
        header('Location: ./login.php');
    }
    include "../rec_session.php";
}
$ruta_raiz = ".";
$fecha = date("Ymdhis");

$_SESSION["idFormulario"] = sha1(microtime(true) . mt_rand(10000, 90000));

require_once('./clases/crud_actuacion_usuario.php');
require_once('./clases/ActuacionUsuario.php');
require_once('./clases/terceros.php');


include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

$objTerceros = new Terceros($db);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>.:.Seguridad Actuacion.:.</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!--Deshabilitar modo de compatiblidad de Internet Explorer-->

<link rel="stylesheet" href="../estilos/bootstrap.min.css"
	type="text/css" />
<link rel="stylesheet"
	href="../estilos/CRA.bootstrap.min.css?jashd=<?php echo date("ymdhmi")?>"
	type="text/css" />

<!-- CSS -->
<link href="../estilos/jquery-ui.css" rel="stylesheet">
	
	<script src="../js/jquery.min.js"></script>
	<script src="../js/bootstrap/bootstrap.min.js"></script>
	<script src="../js/chosen.jquery.js"></script>
	<script src="../js/jquery-ui.min.js"></script>

	<!-- jQuery -->
	<script type="text/javascript"
		src="../js/jquery.validate.min.js?sdf=<?php echo date("ymdhis")?>">
	</script>

	<script type="text/javascript"
		src="./js/actuacion_usuario.js?tes=<?php echo date("Ymdhis")?>">
	</script>
	
	<script type="text/javascript">
		$(document).ready(
			function() {

			$("#btAddFuncionario").click(function(){
	            var tds=$("#tabla tr:first td").length;
	            var trs=$("#tabla tr").length;
	            
	            var nuevaFila = "<tr><td class='col-sm-12 col-md-3'><select data-placeholder='Seleccione una opcion' title='Debe Seleccionar Funcionario, este campo  es Obligatorio' class='chosen-select form-control dropdown'  id='txtFuncionario[]' name='txtFuncionario[]' required>            					            														<option value=''>-- SELECCIONE --</option><?php foreach($objTerceros->getFuncionarios() as $d):?>
	            		<option value='<?php echo $d->getId(); ?>'><?php echo $d->getNombre(); ?>
	            		</option><?php endforeach ?>
	            	</select></td></tr>";
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
		$txtNoExpediente = $_POST['txtNoExpediente'];
		$txtFuncionario = $_POST['txtFuncionario'];
		
		if($txtNoExpediente){
			$objCrudActuacionUsuario = new CrudActuacionUsuario($db);

			//LIMPIAR INVOLUCRADOS
			$objCrudActuacionUsuario->borrarActuacionUsuario($txtNoExpediente);
			
			//INSERTA FUNCIONARIOS

			foreach ($txtFuncionario as $funcionario) {
			    $objActuacionUsuario = new ActuacionUsuario();
				$objActuacionUsuario->setExpediente($txtNoExpediente);
				$objActuacionUsuario->setIdUsuario($funcionario);

				$objCrudActuacionUsuario->insertar($objActuacionUsuario);
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
			<h3>Seguridad Actuación</h3>
		</div>
		<div class="panel-body">
			<div class="container-fluid">
				<form id="frmAsegura" enctype="multipart/form-data" method="post" action="ingresar.php" name="frmAsegura" >
					<br />
					<div class="container-fluid col-sm-offset-3">
						<div class="pub">
							<div>

								<div class="noanonimo seltipo">
									<div class="row col-sm-12 col-md-12 dinamic">
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="txtNoExpediente">Numero expediente
												</label>
											</div>
											<div class="col-sm-12  col-md-9 ">
												<input type="hidden" id="id_emp1" value="" name="id_emp1" /> 
												<input class="form-control obligatorio" value=""
													maxlength="150" title="el campo Número expediente es obligatorio "
													minlenght="3" name="txtNoExpediente" tabindex="4"
													id="txtNoExpediente" type="text" required/>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="txtEquipoTrabajo">Usuarios con permisos
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
	console.log(!$("#frmAsegura").valid());
	if($("#frmAsegura").valid()){
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
