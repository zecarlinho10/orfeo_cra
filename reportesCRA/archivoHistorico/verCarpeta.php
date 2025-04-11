<?
$krdOld = $krd;
session_start();
error_reporting(0);

$ruta_raiz = "../..";
if(!$krd) $krd=$krdOld;
if(!isset($_SESSION['dependencia']))	include "$ruta_raiz/rec_session.php";

include "$ruta_raiz/config.php";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler("$ruta_raiz");
if (!defined('ADODB_FETCH_ASSOC'))define('ADODB_FETCH_ASSOC',2);
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
include ("../../busqueda/common.php");
//echo "nombre emp --> $vEmp";
?>

<head>
<title>Consulta archivo historico</title>
<meta name="GENERATOR" content="YesSoftware CodeCharge v.2.0.5 build 11/30/2001">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="../../busqueda/Site.css" type="text/css">
<link rel="stylesheet" href="../../estilos/orfeo.css">
</head>
<body class="PageBODY">

<?
if(!$orderNo) $orderNo = 4;


 	if (!$orderTipo)  {
	   $orderTipo="desc";
	}else  {
	   $orderTipo="";
	}

 $encabezado = "".session_name()."=".session_id()."&krd=$krd&vTipo=$vTipo&vEmp=$vEmp&sinCarp=$sinCarp;
&vSerie=$vSerie&carpeta=$carpeta&nombre=$nombre&consultar=$consultar&orderTipo=$orderTipo&orderNo=";
 $linkPagina = "$PHP_SELF?$encabezado&orderNo=$orderNo";
?>
	<form name=formverCarpeta action='verCarpeta.php?<?=$encabezado?>' method=post>

 	<table width="100%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr> 
    <td width='35%' >
      <table width='100%' border='0' cellspacing='1' cellpadding='0'>
        <tr> 
          <td height="20" bgcolor="377584"><div align="left" class="titulo1">NOMBRE: </div></td>
        </tr>
		<tr class="info">
          <td height="20" class='titulos2'><?=$vEmp?></td>
        </tr>
      </table>
    </td>
     <td width='35%' >
      <table width='100%' border='0' cellspacing='1' cellpadding='0'>
        <tr> 
          <td height="20" bgcolor="377584"><div align="left" class="titulo1">TIPO: </div></td>
        </tr>
		<tr class="info">
          <td height="20" class='titulos2' ><?=$vTipo?></td>
        </tr>
      </table>
    </td>
	<td width="33%">
	    <table width='100%' border='0' cellspacing='1' cellpadding='0'>
        <tr> 
          <td height="20" bgcolor="377584"><div align="left" class="titulo1">SERIE </div></td>
        </tr>
		<tr class="info">
          <td height="20" class='titulos2' ><?=$vSerie?></td>
        </tr>
      </table>
     </td>
 </tr> 
</table>

 	<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	 <tr> 
	<td valign="top">
	<? 
	 RADICADO_show(); 
	?>
   </td>   </tr>  </table>

<?

function RADICADO_show()
{
//-------------------------------
// Initialize variables  
//-------------------------------
  
  global $carpeta;
  global $db;
  global $encabezado;
  global $krd;
  global $nombre;
  global $orderNo;
  global $orderTipo;  
  global $vTipo;
  global $vEmp;  
  global $vSerie;    
  global $linkPagina;
  global $sinCarp;
  $iRecordsPerPage = 2000;
  $iCounter = 0;
  $order = $orderNo + 1;

if($sinCarp == 1)
{
	$isql = "SELECT ANO, DEPE_CODI, RADI_NUME_RADI, TIPO_RADI, RADI_FECH_RADI,
		RADI_NUME_RADI AS IMG_Imagen, concat('historico',PATH) as HID_RADI_PATH, CARPETA, CAJA
		FROM ARCHIVO_HISTORICO
		WHERE NOMBRE = '" . $vEmp . "' AND TIPO LIKE '%" . $vTipo . "%'
		ORDER BY " . $order . " " . $orderTipo ;

}else
{

	$isql = "SELECT ANO, DEPE_CODI, RADI_NUME_RADI, TIPO_RADI, RADI_FECH_RADI,
		RADI_NUME_RADI AS IMG_Imagen, concat('historico',PATH) as HID_RADI_PATH, CARPETA, CAJA
		FROM ARCHIVO_HISTORICO
		WHERE NOMBRE = '" . $vEmp . "' AND TIPO LIKE '%" . $vTipo . "%'
		AND CARPETA = " . $carpeta . 
		" ORDER BY " . $order . " " . $orderTipo ;
}
//	$db->conn->debug = true;
	$LVarRadicado = $rs->fields["HID_RADI_PATH"];
	echo "$LVarRadicado";
  	$rs=$db->conn->Execute($isql);
	if (!$rs->fields["HID_RADI_PATH"])  {
		echo "<table class=table table-bordered table-striped mart-form width='100%'><tr><td class=titulosError><center>NO se encontro nada con el criterio de 									busqueda</center></td></tr></table>";
 
}

else {
    
	
		$pager = new ADODB_Pager($db,$isql,'adodb', true,$orderNo,$orderTipo);
		$pager->toRefLinks = $linkPagina;
		$pager->toRefVars = $encabezado;
		$pager->Render($rows_per_page=50,$linkPagina,$checkbox="chkEnviar");

	}
}
?>
</form>
</div></body>
</html>
