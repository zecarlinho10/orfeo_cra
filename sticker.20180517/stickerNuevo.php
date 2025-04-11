<?php
session_start();

//ini_set('display_errors',1);
//error_reporting(E_ALL ^ E_NOTICE);

$ruta_raiz 		= "..";
include_once "$ruta_raiz/config.php";
$verradicado        = $_GET["verrad"];
define('ADODB_ASSOC_CASE', 1);
foreach ($_GET as $key=>$valor) ${$key} = $valor;

$krd            = $_SESSION["krd"];
$saludo		    = $_SESSION["saludo"];


include_once "$ruta_raiz/include/db/ConnectionHandler.php";
if ($verradicado) $verrad = $verradicado;

$numrad = $verrad;
$db     = new ConnectionHandler($ruta_raiz);

include $ruta_raiz.'/ver_datosrad.php';
$copias = empty($copias)? 0: $copias;


if('NO DEFINIDO' != $tpdoc_nombreTRD ){
    $process = "Proceso ". $tpdoc_nombreTRD;
}

$dirPlantilla='CRANuevo.php';
$dirLogo=$ruta_raiz.'/img/CRA.jpg';
$dirLogo=file_exists($dirLogo)?$dirLogo:$ruta_raiz.'/img/default.jpg';
//$db->conn->debug=true;

include ($dirPlantilla);
?>

