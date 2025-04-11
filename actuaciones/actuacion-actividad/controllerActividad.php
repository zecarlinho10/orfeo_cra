<?php
session_start();
if (empty($_SESSION["krd"])) {
    if (empty($_REQUEST["krd"])) {
        header('Location: ../../login.php');
    }
    include "../../rec_session.php";
}
$ruta_raiz = "../../";
$fecha = date("Ymdhis");

require_once('../clases/validaciones.php');


include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );

$db = new ConnectionHandler( "$ruta_raiz" );

$objValidaciones = new Validaciones($db);

switch ($_POST["op"]) {
    case "buscarRadicado":
        $txtRadicado = $_POST["txtRadicado"];
        $objValidaciones->validarRadicado($txtRadicado);
        echo $sal;
        break;
    default:
        break;
}
?>
