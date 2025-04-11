<?php
session_start();
ini_set("display_errors",1);
date_default_timezone_set('America/Bogota');
foreach ($_GET as $key => $valor)
    $$key = $valor;
foreach ($_POST as $key => $valor)
    $$key = $valor;
$krd = $_SESSION ["krd"];
$dependencia = $_SESSION ["dependencia"];
$usua_doc = $_SESSION ["usua_doc"];
$codusuario = $_SESSION ["codusuario"];
$ruta_raiz = "../../";
//print_r($_POST); 
include ('orfeoCloud-class.php');
include_once 'file-class.php';
//$ruta_owonclod='/var/www/html/owncloud/data/';
//$ruta_owonclod='/mnt/nube/';
//echo $ruta_owonclod .'<hr>';
//include "config-inc.php";
include_once "$ruta_raiz/config.php";
//echo $ruta_owonclod.'<hr>';
$cloud = new orfeoCloud($ruta_owonclod, $ruta_raiz);
//$carpS='FINDESEMANASANTA';
$cloud->setUserLogin($_SESSION['krd']);
$cloud->dataUser();
$userOwncloud = $cloud->getUserCloud();
$ukrd = strtolower($krd);

$dependencia = $_SESSION['dependencia'];
$codusuario = $_SESSION['codusuario'];
$rol_id = $_SESSION['id_rol'];
$peso = $_POST['pesoA'];
//Cargando variables de log
if(isset($_SERVER['HTTP_X_FORWARD_FOR'])){
    $proxy=$_SERVER['HTTP_X_FORWARD_FOR'];
}else
    $proxy=$_SERVER['REMOTE_ADDR'];
include_once "log.php";
$log = new log($ruta_raiz);
$log->setAddrC($REMOTE_ADDR);
$log->setProxyAd($proxy);
$log->setUsuaCodi($codusuario);
$log->setDepeCodi($dependencia);
$log->setRolId($id_rol);
$log->setDenomDoc('Radicado');

//$pages=3; ing jerson quintero 320 4992012 

switch ($action) {
    case 'Rsubir':
        $carpS = 'RADICADOS';
        $rutaArch = $ruta_owonclod . "$ukrd/files/$carpS/";
        $observacion = $_POST['r'];
        $x = $cloud->subirR($rutaArch, $name, $dependencia, $codusuario, $_SESSION['id_rol'], $pages, $peso, $observacion);
        $encrypt = new file();
        $radi = substr($name, 0, 14);
        $fileGrb = substr($radi, 0, 4) . "/" . substr($radi, 4, 3) . "/" . strtolower($name);
        $url = $ruta_raiz . "/core/vista/image.php?nombArchivo=" . $encrypt->encriptar(RUTA_BODEGA . $fileGrb);
        $log->setNumDocu($radi);
	$log->setOpera("Digitalizacion de documento por OwnCloud $name");
	$log->registroEvento();
        echo " $x <a href='$url' target='_blank'>" . $radi . " </a>";
        break;
    case 'Fsubir':
        $carpS = 'ENTRADA';
        $rutaArch = $ruta_owonclod . "$ukrd/files/$carpS/";
        $observacion = $_POST['r'];
        $x = $cloud->subirC($rutaArch, $name, $dependencia, $codusuario, $_SESSION['id_rol'], $pages, $observacion);
        $encrypt = new file();
        $radi = substr($name, 0, 14);
        $fileGrb = substr($radi, 0, 4) . "/" . substr($radi, 4, 3) . "/" . strtolower($name);
	$url = $ruta_raiz . "/core/vista/image.php?nombArchivo=" . $encrypt->encriptar(BODEGA . $fileGrb);
        $log->setNumDocu($radi);
	      $log->setOpera("Digitalizacion de documento de entrada por OwnCloud $name");
	      $log->registroEvento();
        echo "$x <a  onclick=\"funlinkArchivo('$radi','../');\" target='_blank'>" . $radi . " </a>";
        break;
    case 'Asubir':
        $carpS = 'ANEXOS';
        $arch = $_POST['name'];
        $rutaArch = $ruta_owonclod . "$ukrd/files/$carpS/";
        $tpdoc=0;
        //echo $cloud->subirA($rutaArch, $name, $dependencia, $codusuario, $rol_id, $pages, $pesoA, $tpdoc, $comentario);
        $comentario = "Anexado por OwnCloud";
        echo $cloud->subirA($rutaArch, $name, $dependencia, $codusuario, $rol_id, $pesoA, $tpdoc, $comentario);
        $log->setNumDocu(substr($name,0,14));
	$log->setOpera("Digitalizacion de Anexo por OwnCloud $name");
	$log->registroEvento();
        break;

    case 'Listar':
        exec("ls -lh " . $ruta_owonclod . "/$ukrd/files/clientsync/$carpS/ | awk '{ print $9,$5 }'", $data);
        // print_r($data);
        for ($i = 1; $i < count($data); $i++) {
            $g = explode(' ', $data[$i]);
            $resp.=$g[0] . " peso " . $g[1] . "<br>";
        }
        echo $i;
        echo $resp;

        break;
    default:
        echo 'Nose realizo nada';
        break;
}
?>
