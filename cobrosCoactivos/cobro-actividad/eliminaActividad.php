
<?php
$ruta_raiz = "../..";
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

require_once('../clases/crud_actividad.php');

$objActividad = new CrudActividades($db);

$id=$_POST['idactividad'];

if($objActividad->eliminar($id)==true){
	echo "Actividad eliminada exitosamente";
}
else{
	echo "Error al eliminar Actividad";
}

?>


