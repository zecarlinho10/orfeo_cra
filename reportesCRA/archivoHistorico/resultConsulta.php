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
//	$db->conn->debug = true;
	
?>


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Consulta archivo historico</title>
<link rel="stylesheet" href="../../estilos/orfeo.css">
<script language="JavaScript" src="../../js/crea_combos_2.js"></script>
</head>

 <body><div class="table-responsive">
<?
 $encabezado = "".session_name()."=".session_id()."&krd=$krd&vTipo=$vTipo&vEmp=$vEmp&sinCarp=$sinCarp&nombre=$nombre&consultar=$consultar&orderTipo=$orderTipo&orderNo=";
?>
	<form name=formEnviar action='resultConsulta.php?<?=$encabezado?>' method=post>
<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
		<tr align="center">
		<td height="25" colspan="2" class='titulos2'>Seleccionar Empresa</td>
	<td>
	<?
	    $order=1; 
	$isql = "SELECT NOMBRE
		FROM ARCHIVO_HISTORICO
		WHERE NOMBRE LIKE '%$nombre%' 
		GROUP BY NOMBRE
		ORDER BY NOMBRE";
	$rsEmp = $db->conn->Execute($isql);
	if(!$vEmp) $vEmp="";
	print $rsEmp->GetMenu2("vEmp","$vEmp",false, false, 0," class='select'");
	?>	
	</td>
	</tr>

	<tr align="center">
	<td width="12%" height="26" class="titulos2">Seleccionar tipo de carpeta</td>
	<td>
	<?
	    $order=1; 
	$isql = "SELECT TIPO 
		FROM ARCHIVO_HISTORICO
		WHERE NOMBRE LIKE '%$nombre%' 
		GROUP BY TIPO";
	$rsTipo = $db->conn->Execute($isql);
	if(!$vTipo) $vTipo="";
	print $rsTipo->GetMenu2("vTipo","$vTipo",false, false, 0," class='select'");
	?>	
	</td>
	<td width="3%" height="1" class="listado2">
	<input name="consultar" type="submit"  class="botones" id="consultar"  value="Consultar">
	</td>
	</tr>
	</table>

<?
if ($consultar) {
?>
 <table>  <tr>    <td valign="top">
	<? 
	 RADICADO_show(); 
	?>
   </td>   </tr>  </table>
<?
}


function RADICADO_show()
{
//-------------------------------
// Initialize variables  
//-------------------------------
  
  global $db;
  global $encabezado;
  global $nombre;
  global $vTipo;
  global $vEmp; 
  global $vSerie;  
  global $order;
    global $sinCarp;

  $iRecordsPerPage = 2000;
  $iCounter = 0;
?>
     
<table class="FormTABLE" width="715">
  <tr align="center"> 
  <td width="142" height="25" class="ColumnTD"><a href='resultConsulta.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=1&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Carpeta</font>	</a> </td>
  <td width="142" height="25" class="ColumnTD"><a href='resultConsulta.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=1&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Fecha Inicial</font>	</a> </td>
  <td width="142" height="25" class="ColumnTD"><a href='resultConsulta.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=1&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Fecha Final</font>	</a> </td>

  </tr>
<?
//  echo " empresa --> $vEmp";
	$isql = "SELECT MIN(SERIE) AS SERIE, CARPETA, MIN(FECHA_INICIAL) AS FECHA_INICIAL, MIN(FECHA_FINAL) AS FECHA_FINAL
		FROM ARCHIVO_HISTORICO
		WHERE NOMBRE = '" . $vEmp . "' AND TIPO LIKE '%" . $vTipo . "%'
		GROUP BY CARPETA
		order by " .$order . " " .$orderTipo;

//	$db->conn->debug = true;
  	$rs=$db->conn->Execute($isql);
	if (!$rs->fields["CARPETA"])  {
		echo "<table class=table table-bordered table-striped mart-form width='100%'><tr><td class=titulosError><center>NO se encontro nada con el criterio de 									busqueda</center></td></tr></table>";}
	else  {
		/******/
		// Initialize page counter and records per page
//-------------------------------
	   	while(!$rs->EOF && $iCounter < $iRecordsPerPage)
    	{
//-------------------------------
// Create field variables based on database fields
//-------------------------------

	    $fldCARPETA = $rs->fields["CARPETA"];
	    $fldFECHA_INICIAL = $rs->fields["FECHA_INICIAL"];
	    $fldFECHA_FINAL    = $rs->fields["FECHA_FINAL"];	
	    $vSerie    = $rs->fields["SERIE"];	
	    $rs->MoveNext();  

?>
      <tr>
      <td class="DataTD"><font class="DataFONT"><a href="verCarpeta.php?nombre=<?=$nombre."&".session_name()."=".session_id()."&krd=$krd&sinCarp=0&vTipo=$vTipo&vEmp=$vEmp&vSerie=$vSerie&carpeta=$fldCARPETA"?>">
      <?= tohtml($fldCARPETA) ?>&nbsp;</a></font></td>
	    <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldFECHA_INICIAL) ?>&nbsp;</font></td>
	    <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldFECHA_FINAL) ?>&nbsp;</font></td>
	  </tr>
	  <?
	  
    $iCounter++;
  }
 
}
//-------------------------------
// Finish form processing
//-------------------------------
//  $sinCarp = true;
  ?>
  <TR>
        <td class="DataTD"><font class="DataFONT"><a href="verCarpeta.php?nombre=<?=$nombre."&".session_name()."=".session_id()."&krd=$krd&sinCarp=1&vTipo=$vTipo&vEmp=$vEmp&vSerie=$vSerie&carpeta=$fldCARPETA"?>">
      VER TODAS LAS CARPETAS</a></font></td>
  </TR>
    </table>
  <?


	}
	
?>
</form>
</div></body>
</html>
