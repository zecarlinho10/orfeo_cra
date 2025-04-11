<?php
/*********************************************************************************
 *       Filename: reporte.php
 *
 *       PHP 4.0 build 15 julio de 2005
 *********************************************************************************/

//-------------------------------
// reporte CustomIncludes begin

$krdOld = $krd;
session_start();
error_reporting(0);
$ruta_raiz = "..";
$s_hora_inicial = 0;
if(!$krd) $krd=$krdOld;
if(!isset($_SESSION['dependencia']))	include "$ruta_raiz/rec_session.php";
  $ss_desde_RADI_FECH_RADIDisplayValue = "";
    $cadena="";
  for ($hace=60;$hace>=0;$hace--){
    $timestamp = mktime (0,0,0,date("m"),date("d")-$hace,date("Y"));
    $mes = Date('d/m/Y',$timestamp);
    $valormes = Date("M d Y", $timestamp);
    $cadena.=$mes.";". $valormes .";";
  }
  //$flds_desde_RADI_FECH_RADI = strip(get_param("s_desde_RADI_FECH_RADI"));
    $flds_RADI_DEPE_RADI = $s_RADI_DEPE_RADI;
    $flds_desde_RADI_FECH_RADI = $s_desde_RADI_FECH_RADI;
	$flds_HORA_INICIAL = $s_hora_inicial;
    include "$ruta_raiz/config.php";
	include_once "$ruta_raiz/include/db/ConnectionHandler.php";
    $db = new ConnectionHandler("$ruta_raiz");
    if (!defined('ADODB_FETCH_ASSOC'))define('ADODB_FETCH_ASSOC',2);
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;


include ("../busqueda/common.php");

// reporte CustomIncludes end
//-------------------------------

//===============================
// Save Page and File Name available into variables
//-------------------------------
$sFileName = "repProcesoRadEntrada.php";
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");

//===============================

// reporte Show begin

//===============================
// Display page

//===============================
// HTML Page layout
//-------------------------------
?><html>
<head>
<title>Reporte Planilla</title>
<meta name="GENERATOR" content="YesSoftware CodeCharge v.2.0.5 build 11/30/2001">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; ccharset=UTF-8"><link rel="stylesheet" href="../busqueda/Site.css" type="text/css">
</head>
<body class="PageBODY">
<?
  global $db;
  global $styles;
  
  global $sForm;
  $sFormTitle = "Reporte proceso radicados de entrada";
  $sActionFileName = "repProcesoRadEntrada.php";
  $ss_desde_RADI_FECH_RADIDisplayValue = "";
  $ss_hora_inicialDisplayValue = "";
  $ss_hora_finalDisplayValue = "";
  global $FormRADICADO_Sorting;
  global $FormRADICADO_Sorted;
//-------------------------------
// Search Open Event begin
  $cadena="";
  for ($hace=365;$hace>=0;$hace--){
    $timestamp = mktime (0,0,0,date("m"),date("d")-$hace,date("Y"));
    $mes = Date('Y/m/d',$timestamp);
    $valormes = Date("M d Y", $timestamp);
    $cadena.=$mes.";". $valormes .";";
  }
  $cadena2="";
  for ($hace=0;$hace<=24;$hace++){
    $cadena2.= $hace .";" .$hace . ";";
  }
  $cadena3="";
  for ($hace=0;$hace<=24;$hace++){
    $cadena3.= $hace .";" .$hace;
	if ($hace!= 24)
		$cadena3.= ";";
  }
// Search Open Event end
//-------------------------------
//-------------------------------
// Set variables with search parameters
//-------------------------------
  $flds_SELECCION = strip(get_param("s_SELECCION"));
  $flds_RADI_DEPE_ACTU = $s_RADI_DEPE_ACTU;
  $flds_RADI_DEPE_RADI = strip(get_param("s_RADI_DEPE_RADI"));
  $flds_desde_RADI_FECH_RADI = strip(get_param("s_desde_RADI_FECH_RADI"));
  $flds_hasta_RADI_FECH_RADI = strip(get_param("s_hasta_RADI_FECH_RADI"));  
  $flds_hora_inicial = strip(get_param("s_hora_inicial"));
  $flds_hora_final = strip(get_param("s_hora_final"));


//-------------------------------
	$iSQLdep = "select depe_codi,depe_nomb from DEPENDENCIA ORDER BY DEPE_CODI";
// 	$db->query($iSQLdep);
	$rs = $db->conn->Execute($iSQLdep);

$encabezado = "&krd=$krd&dep_sel=$dep_sel&s_desde_RADI_FECH_RADI=$s_desde_RADI_FECH_RADI&s_hora_inicial=$s_hora_inicial&s_hora_final=$s_hora_final&s_RADI_DEPE_ACTU=$s_RADI_DEPE_ACTU&FormRADICADO_Sorting=$FormRADICADO_Sorting&sFileName=$sFileName&form_sorting=$form_sorting&FormRADICADO_Sorted=$FormRADICADO_Sorted";

echo "valor de krd -->$krd";


include ("repProcesoRadEntrada.php");
?>
</div></body>
</html>