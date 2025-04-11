<?
/**
 * Programa que despliega el formulario de consulta de ESP
 * @author  YULLIE QUICANO
 * @mail    yquicano@cra.gov.co
 * @author  modify by Aquiles Canto
 * @mail    xoroastro@yahoo.com    
 * @version     1.0
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
$radicado=(isset($_POST['radicado']))?$_POST['radicado']:"";
$depe_nomb=(isset($_POST['depe_nomb']))?$_POST['depe_nomb']:"";
$fecha_ini=$_POST['fecha_ini'];
$fecha_fin=$_POST['fecha_fin'];  
$dep=(isset($_POST['dep']))?$_POST['dep']:"";
$esta=(isset($_POST['esta']))?$_POST['esta']:"";
$usua=(isset($_POST['usua']))?$_POST['usua']:"";


?>
<html>
<head>


<title>Reportes CCUS 2013</title>


<?include ($ruta_raiz."/htmlheader.inc.php")?>


	<script language="JavaScript" type="text/JavaScript">
/**
* Env�a el formulario de acuerdo a la opci�n seleccionada, que puede ser ver CSV o consultar
*/
function enviar(argumento)
{	document.formSeleccion.action=argumento+"&"+document.formSeleccion.params.value;
	document.formSeleccion.submit();
}


function activa_chk(forma)
{	//alert(forma.tbusqueda.value);
	//var obj = document.getElementById(chk_desact);
	if (forma.slc_tb.value == 0)
		forma.chk_desact.disabled = false;
	else
		forma.chk_desact.disabled = true;
}

	function pasar_datos(fecha)
   {
    <?
	 echo " opener.document.VincDocu.numRadi.value = fecha\n";
	echo "opener.focus(); window.close();\n";
	?>
}

function adicionarOp (forma,combo,desc,val,posicion){
	o = new Array;
	o[0]=new Option(desc,val );
	eval(forma.elements[combo].options[posicion]=o[0]);
	//alert ("Adiciona " +val+"-"+desc );
	
}
</script>
</script>

	 
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

<form action="ccu.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			RADICADOS POR CCUS
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>
    </tr>
			
	<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>DEPENDENCIA</td>
		<td width="69%" height="30" class='listado2' align="left">
		
		 <?
$sql= "SELECT 'Todas las dependencias' as DEPE_NOMB, 0 AS DEPE_CODI FROM DEPENDENCIA
   				 UNION SELECT 'SUBDIRECCION TECNICA CONSOLIDADO' AS DEPE_NOMB, 2 AS DEPE_CODI FROM DEPENDENCIA
				 UNION SELECT 'SUBDIRECCION ADMINISTRATIVA CONSOLIDADO' AS DEPE_NOMB, 3 AS DEPE_CODI FROM DEPENDENCIA
				 UNION SELECT 'OFICINA ASESORA JURÍDICA CONSOLIDADO' AS DEPE_NOMB, 4 AS DEPE_CODI FROM DEPENDENCIA
                 UNION  SELECT DEPE_NOMB, DEPE_CODI AS DEPE_CODI FROM DEPENDENCIA
                 WHERE DEPE_CODI NOT IN (900,905,999,910,1,321,210)
				 order by DEPE_NOMB DESC";

	$rsDep = $db->conn->Execute($sql);
	if(!$s_DEPE_CODI) $s_DEPE_CODI= 0;
	print $rsDep->GetMenu2("dep","$dep","0:-- Seleccione --", false, 0,"onChange='submit()' class='select'");

	?>
	 
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
  
<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>ESTADO DE USUARIO</td>
	<td width="69%" height="30" class='listado2' align="left">
	
		 <?
$sql = "SELECT 'ACTIVO' as ESTA_DESC, 1 AS ESTA_CODI FROM ESTADO
                 UNION  SELECT 'INACTIVO' as ESTA_DESC, 0 AS ESTA_CODI FROM ESTADO
				 order by ESTA_DESC";

	$rsesta = $db->conn->Execute($sql);
	print $rsesta->GetMenu2("esta","$esta","2:-- Seleccione --", false, 0,"onChange='submit()' class='select'");

	?>
	</td>
</tr>

    
	 <tr align="center" colspan="2">
		<td width="31%" class='titulos2'>USUARIO</td>
	<td width="69%" height="30" class='listado2' align="left">
	<?
	
	$sql = "SELECT distinct U.USUA_NOMB, U.USUA_CODI, D.DEPE_CODI FROM USUARIO U, DEPENDENCIA D
				WHERE D.DEPE_CODI= '$dep'
				and U.DEPE_CODI = '$dep'
				and U.USUA_ESTA='$esta'
				order by USUA_NOMB";
	$rs = $db->conn->Execute($sql);
     //$db->conn->debug = true;
	$rsusua = $db->conn->Execute($sql);
	include "$ruta_raiz/include/tx/ComentarioTx.php";
	if(!$s_USUA_CODI) $s_USUA_CODI= 0;
	print $rsusua->GetMenu2("usua", $usua, "0:-- Seleccione --", false,"","onChange='submit()' class='select'" );

	?>
	</td>
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

   	
    	
function pintarResultados($fila,$i,$n){
		global $ruta_raiz;
		switch($n){
			case 0:
				$salida=$fila['DEPENDENCIA'];
 				break;
 			case 1:
				$salida=$fila['HISTORICO'];
				break;	
			case 2:
				$salida=$fila['USUARIO'];
				break;
			case 3:
				$salida=$fila['RADICADO'];
				break;				
			case 4:
				$salida=$fila['TIPODOCUMENTAL'];
				break;	
			case 5:
				$salida=$fila['OBSERVACIONES'];
				break;
			
			default:$salida="ERROR";
		}
		return $salida;	
	}

if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar")){
	
	
			
			require_once($ruta_raiz."include/myPaginador.inc.php");

			


$where=null;


if(!empty($_POST['esta'])&& ($_POST['esta']=="1")){
	$sql="SELECT USUA_LOGIN FROM USUARIO WHERE USUA_CODI='$usua' AND DEPE_CODI='$dep'";
	 $rs = $db->conn->Execute($sql);
	 $login= $rs->fields['USUA_LOGIN'];
$where1=(!empty($_POST['esta']) && ($_POST['esta'])!="")?"AND U.USUA_CODI='$usua'":"";
//echo "Miremos el codigo '$usua'.";
			
	}
 			
			$where=(!empty($_POST['dep']) && ($_POST['dep'])!="")?"AND h.depe_codi = ".$_POST['dep']:"";		 if ($dep==2)
{
  $where=" AND r.radi_depe_radi in (401,410,420,430) ";	
}	
if ($dep==3)
{
  $where=" AND r.radi_depe_radi in (301,310,320,341,350) ";	
}	
if ($dep==4)
{
  $where=" AND r.radi_depe_radi in (211,212,213,214,215) ";	
}  

	 		
			$where.= " AND  h.hist_fech>=".$db->conn->DBTimeStamp($fecha_ini)." AND h.hist_fech<=".$db->conn->DBTimeStamp($fecha_fin);
 		
 			
 			$order=1;      	
		 
$titulos=array("1#NOMBRE DEPENDENCIA","2#FECHA HISTORICO","3#NOMBRE DEL USUARIO","4#RADICADO","5#TIPO DOCUMENTAL","6#OBSERVACIONES");
			
			$isql = "select d.depe_nomb AS dependencia,h.HIST_FECH AS historico,u.usua_nomb as usuario,h.RADI_NUME_RADI AS radicado,te.sgd_tpr_descrip AS TIPODOCUMENTAL,h.HIST_OBSE AS OBSERVACIONES
from fldoc.hist_eventos h,fldoc.usuario u ,fldoc.dependencia d, fldoc.usuario ud ,SGD_TPR_TPDCUMENTO te , radicado r
where h.hist_obse LIKE '%CCU%'
{$where}
{$where1}
and h.depe_codi=u.depe_codi
and h.usua_codi=u.usua_codi
and h.usua_codi_dest=ud.usua_codi
and h.depe_codi_dest=ud.depe_codi
and d.depe_codi=h.depe_codi
and r.tdoc_codi=te.sgd_tpr_codigo
and h.radi_nume_radi=r.radi_nume_radi
order by h.hist_fech
";

error_reporting(0);
require "../anulacion/class_control_anu.php";
$btt = new CONTROL_ORFEO($db);
$btt->tabla_sql($isql);
		
			//$db->conn->debug = true;
		
			//$paginador= new myPaginador($db,$isql,1);
			//$paginador->modoPintado(true);
			//$paginador->setImagenASC($ruta_raiz."iconos/flechaasc.gif");
			//$paginador->setImagenDESC($ruta_raiz."iconos/flechadesc.gif");
			//$paginador->setFuncionFilas("pintarResultados");
			//$paginador->generarPagina($titulos);

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
