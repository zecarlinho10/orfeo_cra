<?php
//CARLOS RICAURTE 5/7/2019
//Actualizar el estado de RADICADO.SENIEGA
session_start();
$ruta_raiz = "../../"; 
if (!$_SESSION['dependencia'])
	header("Location: $ruta_raiz/cerrar_session.php");
include_once("$ruta_raiz/include/db/ConnectionHandler.php");
$db = new ConnectionHandler("$ruta_raiz");
$numrad=$_POST['numRad'];
$isql = "select SENIEGA from RADICADO where radi_nume_radi=$numrad";
$rs=$db->conn->Execute($isql);
$checkVal=$rs->fields["SENIEGA"]==1?0:1;
$isql = "update RADICADO set SENIEGA=$checkVal where radi_nume_radi=$numrad";
$rs=$db->conn->Execute($isql);
echo json_encode(array("checkVal"=>$checkVal));
?>
