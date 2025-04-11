<?php
/**
* @author Carlos Ricaurte   <carlinhoricaurte@hotmail.com>
* @license  GNU AFFERO GENERAL PUBLIC LICENSE
* Comisión de regulación de agua potable y saneamiento basico
*/

session_start();

require_once('../clases/crud_cobro.php');
require_once('../clases/cobro.php');
require_once('../clases/validaciones.php');

$ruta_raiz = "../..";
if (!$_SESSION['dependencia']) header ("Location: $ruta_raiz/cerrar_session.php");
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

if (!defined('ADODB_ASSOC_CASE')) define('ADODB_ASSOC_CASE', 1);
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );


$objCrudCobro = new CrudCobro($db);
$objValidaciones = new Validaciones($db);
$objCobro = new Cobro($db);

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>.:.Acumular Cobros.:.</title>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	
	<meta name="viewport" content="width=device-width, initial-scale=1" />
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
	<link href="../../estilos/ie10-viewport-bug-workaround.css"
		rel="stylesheet" />
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
	
	
	<style>
		table {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		  text-align: center;
		}

		td, th {
		  border: 1px solid #dddddd;
		  text-align: left;
		  padding: 8px;
		}

		tr:nth-child(even) {
		  background-color: #dddddd;
		}

		.tablaCentrada {
		  display: flex;
		  justify-content: center;
		  align-items: center;
		}
	</style>



</head>
<body>

<?php

//post
$cobro_origen = $_POST['txtCobroOrigen'];
$cobro_destino = $_POST['txtCobroDestino'];

$acumulaCobros=$objCrudCobro->acumularCobros($cobro_origen,$cobro_destino);
if($acumulaCobros!=""){
	echo '<script type="text/javascript"> 
		alert("' . $acumulaCobros . '");
	</script> ';
}
?>


<div class="container">

	<div class="row">

		<div class="col-md-12">
			<h1>Acumular Cobros</h1>
		</div>

	</div>
	<div class="row">
		<div class="col-md-10">
			<form method="post" action="index.php" onsubmit="return confirmation()">
				<div class="row">
										
					<div class="col-md-3">
						<label
							class="desc control-label control-label label-success form-control" for="txtCobro">Cobro Origen*
						</label>
					</div>
											
					<div class="col-sm-12  col-md-9">
						<select data-placeholder="Seleccione una opción"
								title="Debe Seleccionar Cobro, este campo  es Obligatorio"
								class="chosen-select form-control dropdown"
								id="txtCobro" name="txtCobroOrigen" required>
								<option value="">-- SELECCIONE --</option>
									<?php foreach($objCrudCobro->getCobrosActivos() as $d):?>
								<option value="<?php echo $d->getId(); ?>" 
								>
								<?php echo $d->getId() . " - " . $d->getExpediente() . "-" . $d->getEmpresa()->getNombre() ?>
														      	
								</option>
							<?php endforeach ?>
						</select>
					</div>	
				</div>
				<div class="row">	
					<div class="col-md-3">
						<label
							class="desc control-label control-label label-success form-control" for="txtCobro">Cobro Destino *
						</label>
					</div>
											
					<div class="col-sm-12  col-md-9">
						<select data-placeholder="Seleccione una opción"
								title="Debe Seleccionar Cobro, este campo  es Obligatorio"
								class="chosen-select form-control dropdown"
								id="txtCobro" name="txtCobroDestino" required>
								<option value="">-- SELECCIONE --</option>
									<?php foreach($objCrudCobro->getCobrosActivos() as $d):?>
								<option value="<?php echo $d->getId(); ?>" 
									<?php if($d->getId()==$txtCobro) echo 'selected';  ?>
								>
								<?php echo $d->getId() . " - " . $d->getExpediente() . "-" . $d->getEmpresa()->getNombre() ?>
														      	
								</option>
							<?php endforeach ?>
						</select>
					</div>						
				</div>

			  <button type="submit" class="btn btn-default">Ejecutar Movimiento</button>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript"
		src="js/acumula.js?tes=<?php echo date("Ymdhis")?>">
	</script>
</body>
</html>
