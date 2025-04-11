<?php
session_start();


$ruta_raiz 		= "..";
include_once "$ruta_raiz/config.php";

define('ADODB_ASSOC_CASE', 1);
foreach ($_GET as $key=>$valor) ${$key} = $valor;

$krd            = $_SESSION["krd"];

include_once "$ruta_raiz/include/db/ConnectionHandler.php";

$db     = new ConnectionHandler($ruta_raiz);

$dirPlantilla='CRANuevoExp.php';
$dirLogo=$ruta_raiz.'/img/CRA.jpg';
$dirLogo=file_exists($dirLogo)?$dirLogo:$ruta_raiz.'/img/default.jpg';
//$db->conn->debug=true;

include ($dirPlantilla);
?>

