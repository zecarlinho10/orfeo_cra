
<?php
$ruta_raiz = "../..";
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

require_once('../clases/crud_actividad.php');

$objActividad = new CrudActividades($db);

$id=$_POST['idactividad'];
$observacion=$_POST['observacion'];


if($objActividad->actualizaActividad($id,$observacion)==true){
	echo "Actividad actualizada exitosamente";
}
else{
	echo "Error al actualizar Actividad";
}

?>


