<?php
session_start();

    if (!$_SESSION['dependencia'])
        header ("Location: $ruta_raiz/cerrar_session.php");

	$nomfile="orfeoReport-".date("Y-m-d").".doc"; 	
	header("Content-type: application/msword; charset=utf-8 ");
	header("Content-Disposition: filename=\"$nomfile\";");
	include (realpath(dirname(__FILE__) . "/../")."/include/class/adodb/adodb-basedoc.inc.php");

?>
