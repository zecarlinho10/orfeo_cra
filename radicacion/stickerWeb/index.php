<?php
session_start();
if (!isset($_SESSION['USUA_PERM_STICKER']))
	die('Usted no tiene permisos para ingresar a este módulo');

//ini_set('display_errors',1);
//error_reporting(E_ALL ^ E_NOTICE);

$ruta_raiz 		= "../..";
include_once "$ruta_raiz/config.php";
$verradicado        = $_GET["verrad"];
define('ADODB_ASSOC_CASE', 1);
foreach ($_GET as $key=>$valor) ${$key} = $valor;

$krd            = $_SESSION["krd"];
$dependencia    = $_SESSION["dependencia"];
$usua_doc       = $_SESSION["usua_doc"];
$codusuario     = $_SESSION["codusuario"];
$tip3Nombre     = $_SESSION["tip3Nombre"];
$tip3desc       = $_SESSION["tip3desc"];
$tip3img        = $_SESSION["tip3img"];

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
if ($verradicado) $verrad = $verradicado;

$numrad = $verrad;
$db     = new ConnectionHandler($ruta_raiz);

include $ruta_raiz.'/ver_datosrad.php';
$copias = empty($copias)? 0: $copias;


if('NO DEFINIDO' != $tpdoc_nombreTRD ){
    $process = "Proceso ". $tpdoc_nombreTRD;
}
$noRad = $_REQUEST['nurad'];
$isql="
select
	d.depe_nomb, 
	r.radi_cuentai
from
	radicado r, 
	dependencia d 
where 
	r.radi_nume_radi=$noRad
	and r.radi_depe_actu=d.depe_codi";
$rs=$db->conn->Execute($isql);
$depeNomb=$rs->fields["DEPE_NOMB"];
$radiCuentaI=$rs->fields["RADI_CUENTAI"];
$entidad_corto=$entidad;
$noRadBarras='<span style="font-size: 44;font-family: \'Free 3 of 9\'" >'."*$noRad*".'</span>';
$dirPlantilla=$ruta_raiz.'/conf/stickers/radicado/'.$entidad_corto.'.php';
$dirPlantilla=file_exists($dirPlantilla)?$dirPlantilla:$ruta_raiz.'/conf/stickers/radicado/default.php';
$dirLogo=$ruta_raiz.'/img/'.$entidad_corto.'.jpg';
$dirLogo=file_exists($dirLogo)?$dirLogo:$ruta_raiz.'/img/default.jpg';
//$db->conn->debug=true;
$isql="
select 
	r.RADI_NUME_FOLIO, 
	TO_CHAR(r.RADI_FECH_RADI,'DD-MON-YYYY HH:MI AM') FECHA, 
	r.RADI_CUENTAI, 
	r.RADI_DESC_ANEX, 
	r.RA_ASUN, 
	r.RADI_NUME_ANEXO,
	d.SGD_DIR_NOMREMDES
from 
	radicado r,
	sgd_dir_drecciones d
where 
	r.radi_nume_radi=d.radi_nume_radi and 
	r.radi_nume_radi=$noRad";
$rs=$db->conn->Execute($isql);
$anexos=$rs->fields["RADI_NUME_ANEXO"];
$folios = $rs->fields["RADI_NUME_FOLIO"];
$anexDesc = $rs->fields["RADI_DESC_ANEX"];
$asunto = $rs->fields["RA_ASUN"];
$asunto= substr($asunto,0,35);
$referencia= $rs->fields["RADI_CUENTAI"];
$remitente= $rs->fields["SGD_DIR_NOMREMDES"];
$remitente= substr($remitente,0,35);
$radi_fech_radi=$rs->fields["FECHA"];

include ($dirPlantilla);
?>

