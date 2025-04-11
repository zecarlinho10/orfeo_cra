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

$_SESSION["idFormulario"] = sha1(microtime(true) . mt_rand(10000, 90000));


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>.:.Reporte General Cobros Coactivos.:.</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!--Deshabilitar modo de compatiblidad de Internet Explorer-->

<script src="js/tableToExcel.js"></script>
<script src="js/app.js"></script>


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

	<style>
		table.negro {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  border: 1px solid #000000;
		}

		table.gris {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  border: 1px solid #999999;
		}

		table.gris td, th {
		  border: 1px solid #dddddd;
		  text-align: left;
		  padding: 8px;
		}

		table.gris tr:nth-child(even) {
		  background-color: #dddddd;
		}

	</style>

</head>
<body>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3>Reporte general</h3>
		</div>
		<form action="ficheroExcel.php" method="post" target="_blank" rel="noopener noreferrer" id="FormularioExportacion">
		  <div class="panel-body">
		  	<div class="col-sm-3  col-md-3">
				<select data-placeholder="Seleccione una opción"
						title="Debe Seleccionar Estado, este campo  es Obligatorio"
						class="chosen-select form-control dropdown"
						id="txtEstado" name="txtEstado" required>
					<option value="-1">Todos</option>
					<option value="0">Finalizados</option>
					<option value="1">Activos</option>
					<option value="2">Suspendido</option>
					<option value="3">Acumulados</option>
				</select>
			</div>
		  	<input type="button" name="btnRepGral" id="btnRepGral" onclick="reporteGeneral()" value="Generar reporte">
		  	<!-- <input type="button" onclick="tableToExcel('testTable', 'Reporte')" value="Exportar a Excel"> -->
		  	<br>
			<table id="testTable" class='negro' border="2">
				<div id="repGeneral"></div>
			</table>
		  </div>
		</form>
	</div>
	
</body>
</html>