<?php

$ruta_raiz = "../..";
include "$ruta_raiz/cobrosCoactivos/clases/validaciones.php";

$objValida = new Validaciones($db);

	if ($_POST['radicado']=="") {
        $salida = "vacío";
	} 
	else {
		$x=$objValida->validarRadicado($_POST['radicado']);
		if ($x==0) {
	        $salida = "El radicado no existe";
		} 
		else {
			$salida = "Radicado Correcto";
		}
	}
 	
 	echo $salida;
?>