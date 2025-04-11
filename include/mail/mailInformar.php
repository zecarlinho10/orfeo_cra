<?php
session_start();
$nombre_fichero = $_SESSION['entidad'].".mailInformar.php";

$ruta_fichero = $ruta_raiz.'/include/mail/'.$nombre_fichero;

if (file_exists($ruta_fichero)) {
	require "$nombre_fichero";
} else {
require "GENERAL.mailInformar.php";
}
?>
