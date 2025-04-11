<?
/**
 * Programa que despliega el formulario de consulta de ESP
 * @author  CARLINHO
 * @mail    cricaurte@cra.gov.co
 * @author  CARLOS RICAURTE
 * @version     1.1
 */
$ruta_raiz = "../";
session_start();
error_reporting(0);
require_once($ruta_raiz."include/db/ConnectionHandler.php");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

//En caso de no llegar la dependencia recupera la sesi�n
if(empty($_SESSION)) include $ruta_raiz."rec_session.php";

include ("common.php");
$fechah = date("ymd") . "_" . date("hms");
$fecha_ini=$_POST['fecha_ini'];
$fecha_fin=$_POST['fecha_fin'];  

?>
<html>
<head>


<title>Reportes imagenes</title>


<?include ($ruta_raiz."/htmlheader.inc.php")?>

	 
<link rel="stylesheet" type="text/css" href="../js/spiffyCal/spiffyCal_v2_1.css">
			<script language="JavaScript" src="../js/spiffyCal/spiffyCal_v2_1.js"></script>
			<script language="JavaScript" type="text/JavaScript">  
				setRutaRaiz('<?php echo $ruta_raiz; ?>')
		 <!--
			<?
				$ano_ini = date("Y");
				$mes_ini = substr("00".(date("m")-1),-2);
				if ($mes_ini==0) {$ano_ini=$ano_ini-1; $mes_ini="12";}
				$dia_ini = date("d");
				if(!$fecha_ini) $fecha_ini = "$ano_ini/$mes_ini/$dia_ini";
					$fecha_busq = date("Y/m/d") ;
				if(!$fecha_fin) $fecha_fin = $fecha_busq;
			?>
   var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "formSeleccion", "fecha_ini","btnDate1","<?=$fecha_ini?>",scBTNMODE_CUSTOMBLUE);
   var dateAvailable2 = new ctlSpiffyCalendarBox("dateAvailable2", "formSeleccion", "fecha_fin","btnDate2","<?=$fecha_fin?>",scBTNMODE_CUSTOMBLUE);

--></script>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>

 <body><div class="table-responsive">
<div id="spiffycalendar" class="text"></div>

<?
$params = session_name()."=".session_id()."&krd=$krd";
?>

<form action="sinImagen.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			RADICADOS SIN IMAGEN
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>
<tr>
    <td align="center" width="30%" class="titulos2">Desde  fecha (aaaa/mm/dd) </td>
    <td class="listado2">
	<script language="javascript">
	dateAvailable.writeControl();
	dateAvailable.dateFormat="yyyy/MM/dd";
	</script>&nbsp;</td>
  </tr>
  
<tr>
    <td align="center" width="30%" class="titulos2">Hasta  fecha (aaaa/mm/dd) </td>
    <td class="listado2">
	<script language="javascript">
	dateAvailable2.writeControl();
	dateAvailable2.dateFormat="yyyy/MM/dd";
	</script>&nbsp;</td>
  </tr>	
  
	<tr align="center">
		<td height="30" colspan="4" class='listado2'>
		<center>
			<input name="Consultar" type="submit"  class="botones" id="envia22"   value="Consultar">&nbsp;&nbsp;

		</center>
		
		</td>
	</tr>
</table>
</form>

<?php 

   	
    	
if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar")){
	
	
			
			require_once($ruta_raiz."include/myPaginador.inc.php");

			
	 		
			$where.= " AND  RADI_FECH_RADI>=".$db->conn->DBTimeStamp($fecha_ini)." AND RADI_FECH_RADI<=".$db->conn->DBTimeStamp($fecha_fin);
 		
$titulos=array("1#RADICADO","2#FECHA","3#DEPENDENCIA");
			
			$isql = "SELECT RADI_NUME_RADI, RADI_FECH_RADI, RADI_DEPE_RADI
					FROM RADICADO
					WHERE (UPPER(RADI_PATH) NOT LIKE '%.PDF' AND UPPER(RADI_PATH) NOT LIKE '%.HTML%') AND 
					       SGD_EANU_CODIGO IS NULL
{$where}
order by 2
";

error_reporting(0);
require "../anulacion/class_control_anu.php";
$btt = new CONTROL_ORFEO($db);

$btt->tabla_sql($isql);
		
			//$db->conn->debug = true;
		

$pager = new ADODB_Pager($db,$isql,'adodb', true,$adodb_next_page,$orderTipo);
$pager->checkAll = false;
$pager->checkTitulo = true;
$pager->toRefLinks = $linkPagina;
$pager->toRefVars = $encabezado;
$pager->Render($rows_per_page=20,$linkPagina,$checkbox="chkEnviar");



}
?>

</div></body>
</html>
