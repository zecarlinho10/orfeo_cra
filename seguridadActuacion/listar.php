<?php

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

<title>.:.Reporte Seguridad Actuacion.:.</title>
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
	

	<?php
		$objCrudActuacionUsuario = new CrudActuacionUsuario($db);
		$listaActuacionesUsuario=$objCrudActuacionUsuario->getLista();
		
		/*
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
		*/
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
							<table class="pub" border="1">
							  <tr>
							    <th>Expediente</th>
							    <th>Nombre Expediente</th>
							    <th>Usuario</th>
							    <th>Dependencia</th>
							  </tr>

							  <?
							  	$sql = "SELECT AU.EXPEDIENTE, SGD_SEXP_PAREXP1, USUA_LOGIN, E.DEPE_CODI
										FROM ACTUACION_USUARIO AU, USUARIO U, SGD_SEXP_SECEXPEDIENTES E
										WHERE U.ID = AU.IDUSUARIO AND E.SGD_EXP_NUMERO = EXPEDIENTE
										ORDER BY 1";
							  	$rs = $db->query ( $sql );
							  	$expediente="";
							  	$nomexpediente="";
							  	$usuario="";
							  	$dependencia="";
								while ($rs && !$rs->EOF) {
									$expediente = $rs->fields ["EXPEDIENTE"];
									$nomexpediente = $rs->fields ["SGD_SEXP_PAREXP1"];
									$usuario = $rs->fields ["USUA_LOGIN"];
									$dependencia = $rs->fields ["DEPE_CODI"];

									echo "<tr><td>$expediente</td>";
									echo "<td>$nomexpediente</td>";
									echo "<td>$usuario</td>";
									echo "<td>$dependencia</td></tr>";

									$rs->MoveNext ();
								}


							  ?>
							</table>
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
