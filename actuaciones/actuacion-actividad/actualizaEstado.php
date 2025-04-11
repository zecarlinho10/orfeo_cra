
<?php
$ruta_raiz = "../..";
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

require_once('../clases/crud_actividad.php');

$objActividad = new CrudActividades($db);

$idactuacionactividad=$_POST['idactuacionactividad'];
$valor=$_POST['valor'];
$cierra=$_POST['cierra'];


if($objActividad->actualizaEstado($idactuacionactividad, $valor, $cierra)==true){
	echo "Actividad actualizada exitosamente";
}
else{
	echo "Error al actualizar Actividad";
}

?>