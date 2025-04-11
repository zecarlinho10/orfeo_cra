<?php
session_start();
$path_raiz = realpath(dirname(__FILE__) . "/..");
require_once ("BuscaDatos.php");
require_once ($path_raiz . "/clasesComunes/Dependencia.php");
$buscador = new BuscaDatos();

switch ($_POST["op"]) {

    case "buscarExpediente":
        $noExpediente = $_POST["noExpediente"];
        $rs = $buscador->buscarExpediente($noExpediente);
        $i = 0;
        while (! $rs->EOF) {
            $salida["data"][$i] = $rs->fields;
            $i ++;
            $rs->MoveNext();
        }

        $salida["success"] = count($salida["data"]) > 0;
        echo json_encode($salida);
        break;
  
    default:
        break;
}
?>
