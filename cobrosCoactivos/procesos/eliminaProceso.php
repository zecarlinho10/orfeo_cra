
<?php
$ruta_raiz = "../..";
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

require_once('../clases/crud_proceso.php');

$objProceso = new CrudProceso($db);

$id=$_POST['idproceso'];

if($objProceso->eliminar($id)==true){
	echo "Proceso eliminado exitosamente";
}
else{
	echo "Error al eliminar Proceso";
}

?>


