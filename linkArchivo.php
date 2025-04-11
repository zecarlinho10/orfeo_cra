<?php
 /*
  * Invocado por una funcion javascript (funlinkArchivo(numrad,rutaRaiz))
  * Consulta el path del radicado 
  * @author Liliana Gomez Velasquez
  * @since 5 de noviembre de 2009
  * @category imagenes
 */
session_start();
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;
$krd                = $_SESSION["krd"];
$dependencia        = $_SESSION["dependencia"];
$ln          = $_SESSION["digitosDependencia"];

$digitos_totales = 14;
if (!$ruta_raiz) $ruta_raiz = ".";

if (isset($db)) unset($db);
include_once("$ruta_raiz/include/db/ConnectionHandler.php");
$db = new ConnectionHandler("$ruta_raiz");
$db->conn->SetFetchMode( ADODB_FETCH_ASSOC );
include_once "$ruta_raiz/tx/verLinkArchivo.php";
include_once (realpath(dirname(__FILE__) . "/")."/config.php");
$verLinkArchivo = new verLinkArchivo($db);
if (strlen( $numrad) <= $digitos_totales){

  $resulVali = $verLinkArchivo->valPermisoRadi($numrad,$_SESSION["USUA_PERM_ENRUTADOR_TRD"]);
 $seguridadRadicado =  $_SESSION['seguridadradicado'] ;
 unset($_SESSION['seguridadradicado'] );
 
  $verImg = $resulVali['verImg'];
 $pathImagen = $resulVali['pathImagen'];
 if($resulVali['verImg']=="NO"){
	 die ("No tiene permisos para ver el documento");
 }elseif(empty($pathImagen)){
	 	die ("Documento sin Fichero Digital");
	}
  if(substr($pathImagen,0,9) == "../bodega") {
  	$pathImagen=str_replace('../bodega',BODEGA,$pathImagen);
  	$file = $pathImagen;
  }elseif(substr($pathImagen,0,12) == "../../bodega") {
    $pathImagen=str_replace('../../bodega',BODEGA,$pathImagen);
  	$file = $pathImagen;
  }
  	else {
  		$file = BODEGA."/".$pathImagen;
  }	
}else {
  //Se trata de un anexo	
  $resulValiA = $verLinkArchivo->valPermisoAnex($numrad);
  $verImg = $resulValiA['verImg'];
  $pathImagen = $resulValiA['pathImagen'];
  //if(strlen($dependencia)==2){
  if(substr($numrad, 4, 1) == '0'){
	    $file = BODEGA."/".substr(trim($numrad),0,4)."/".substr($numrad, 5, 2)."/docs/".trim($pathImagen);
  }
  else
  {
	    $file = BODEGA."/".substr(trim($numrad),0,4)."/".substr(trim($numrad),4,3)."/docs/".trim($pathImagen);
  }
  echo $file;
}
$fileArchi = $file;
$tmpExt = explode('.',$pathImagen);
$es_pdf = ($tmpExt[1] == 'pdf')? 'pdf' : null;
$filedatatype = ($es_pdf)? $es_pdf : $pathImagen;

// Si se tiene una extension 
if(count($tmpExt)>1){
   $filedatatype =  $tmpExt[count($tmpExt)-1];
}

//04/04/2018 CARLOS RICAURTE
//VALIDA QUE EL USUARIO TENGA EL PERMISO 407 Y SI EL EXPEDIENTE DEL RADICADO ES: SERIE Y SUBSERIE 3108301 ANTIGUO O 
$idPermisoBase="407";

$sql="SELECT AUTG_ID FROM AUTM_MEMBRESIAS 
  WHERE AUTU_ID = (SELECT ID FROM USUARIO WHERE USUA_CODI=" . $_SESSION["codusuario"] . " AND DEPE_CODI=" . $_SESSION["dependencia"] .") AND AUTG_ID=". $idPermisoBase;
$rs1 = $db->conn->Execute($sql);

$IDpermisoTH="";
if (! $rs1->EOF) {
  $IDpermisoTH = $rs1->fields["AUTG_ID"];
}

$sql="SELECT SGD_EXP_NUMERO FROM sgd_exp_expediente WHERE radi_nume_radi = '" . $numrad . "' AND (SGD_EXP_NUMERO LIKE '%8301%' OR SGD_EXP_NUMERO LIKE '%15900%')";

$Nexpediente = "";
$esExpTH = 0;

$rs2 = $db->conn->Execute($sql);
if (! $rs2->EOF) {
        $Nexpediente = $rs2->fields["SGD_EXP_NUMERO"];
        $esExpTH = 1;
}


$Thumano=0;
if($IDpermisoTH==$idPermisoBase and $esExpTH==1){
  $Thumano=1;
}
//fin desarrollo

if($verImg=="SI" or $_SESSION["nivelus"]==5 or $seguridadRadicado == 0 or $Thumano==1){

  if (file_exists($fileArchi)) {
    header('Content-Description: File Transfer');
    
    switch($filedatatype) {
         case 'odt':
			   header('Content-Type: application/vnd.oasis.opendocument.text');
			   break;
         case 'doc':
               header('Content-Type: application/msword');
               break;
         case 'tif':
               header('Content-Type: image/TIFF');
               break;
         case 'pdf':
               header('Content-Type: application/pdf');
               break;  
         case 'xls':
               header('Content-Type: application/vnd.ms-excel');
               break;
         case 'csv':
               header('Content-Type: application/vnd.ms-excel');
               break;
         case 'ods':
               header('Content-Type: application/vnd.ms-excel');
               break;  
         case 'html':
               header('Content-Type: text/html');
               break; 
         default :
		      header('Content-Type: application/octet-stream');
			  break;  
        }
         
        if ($filedatatype == 'html') {
	        header('Content-Disposition: inline; filename='.basename($file));
         }else{
            header('Content-Disposition: attachment; filename='.basename($file));
	    }

        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
       ob_end_flush();
        
        readfile($file);
        exit;
  }else {
 	   die ("<B><center>  NO se encontro el Archivo  </a><br>");
    }
  }elseif($verImg == "NO"){ 
  	die ("<B><CENTER>  NO tiene permiso para acceder al Archivo </a><br>");
  }
else{
    die ("<B><CENTER>  NO se ha podido encontrar informacion del Documento</a><br>"); 
}
?>
