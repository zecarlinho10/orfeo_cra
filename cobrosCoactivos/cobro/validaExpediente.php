<?php

$ruta_raiz = "../..";
include "$ruta_raiz/cobrosCoactivos/clases/validaciones.php";

$objValida = new Validaciones($db);

	if ($_POST['expediente']=="") {
        $salida = "vacío";
	} 
	else {
		$x=$objValida->validarExpediente($_POST['expediente']);
		if ($x==0) {
	        $salida = "El expediente no existe";
		} 
		else {
			$salida = "Expediente Correcto";
		}
	}
 	
 	echo $salida;
?>