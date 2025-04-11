
<?php
$ruta_raiz = "../..";
//include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

require_once('../clases/crud_actividad.php');

$objActividad = new CrudActividades($db);

$id=$_POST['idactividad'];
$valor=$_POST['valor'];


if($objActividad->actualizaEstado($id,$valor)==true){
	echo "Actividad actualizada exitosamente";
}
else{
	echo "Error al actualizar Actividad";
}

?>


