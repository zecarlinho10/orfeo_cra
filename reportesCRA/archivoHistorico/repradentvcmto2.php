<?
/**
 * Programa que despliega el formulario de consulta de ESP
 * @author  YULLIE QUICANO
 * @mail    yquicano@cra.gov.co
 * @author  modify by Aquiles Canto
 * @mail    xoroastro@yahoo.com    
 * @version     1.0
 */
$ruta_raiz = "../../";
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

?>
<html>
<head>


<title>Reportes Radicados Según Vencimiento</title>


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

	 
<link rel="stylesheet" type="text/css" href="../../js/spiffyCal/spiffyCal_v2_1.css">
			<script language="JavaScript" src="../../js/spiffyCal/spiffyCal_v2_1.js"></script>
			<script language="JavaScript" type="text/JavaScript">  
				setRutaRaiz('<?php echo $ruta_raiz; ?>')
		 <!--
			<?
				$ano_ini = date("Y");
				$mes_ini = substr("00".(date("m")-1),-2);
				if ($mes_ini==0) {$ano_ini==$ano_ini-1; $mes_ini="12";}
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

<form action="repradentvcmto.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			CONSULTA RADICADOS SEGUN VENCIMIENTO
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>
    </tr>
			
	<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>DEPENDENCIA</td>
		<td width="69%" height="30" class='listado2' align="left">
		
		 <?
$sql = " SELECT 'Todas las dependencias' as DEPE_NOMB, 0 AS DEPE_CODI FROM DEPENDENCIA
UNION  SELECT DEPE_NOMB, DEPE_CODI AS DEPE_CODI FROM DEPENDENCIA
					order by DEPE_NOMB";

	$rsDep = $db->conn->Execute($sql);
	if(!$s_DEPE_CODI) $s_DEPE_CODI= 0;
	print $rsDep->GetMenu2("dep","$dep",false, false, 0," class='select'");

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
				$salida=$fila['ENTRADA'];
 				break;
 			case 1:
 				$salida=$fila['FECHAE'];
				break;	
			case 2:
				$salida=$fila['RSALIDA'];
				break;
			case 3:
				$salida=$fila['FECHAS'];	
				break;
			case 4:
				$salida=$fila['ASUNTO'];
				break;	
			case 5:
				$salida=$fila['CARP_DESC'];
				break;
			case 6:
				$salida=$fila['DEPE_ACTU'];
				break;
			case 7:
				$salida=$fila['NOMB_ACTU'];
				break;
			case 8:
				$salida=$fila['FECH_VCMTO'];
				break;
			case 9:
				$salida=$fila['DIASR'];
				break;
			
			default:$salida="ERROR";
		}
		return $salida;	
	}

if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar")){
	
	
			
			require_once($ruta_raiz."include/myPaginador.inc.php");

			


$where=null;
	
 			
			$where=(!empty($_POST['dep']) && ($_POST['dep'])!="")?"AND r.radi_depe_actu = ".$_POST['dep']:"";		   					   			
	 		
			$where.= " AND  r.FECH_VCMTO>=".$db->conn->DBTimeStamp($fecha_ini)." AND r.FECH_VCMTO<=".$db->conn->DBTimeStamp($fecha_fin);
 			
 			$order=1;      	
		 
$titulos=array("1#RADICADO","2#FECHA RADICACION","3#RADICADO SALIDA","4#FECHA RADICADO SALIDA","5#ASUNTO","6#CARPETA ACTUAL","7#DEPENDENCIA","8#FUNCIONARIO","8#FECHA DE VENCIMIENTO","9#DIAS RESTANTES");
			
			$isql = "SELECT r.radi_nume_radi AS ENTRADA, 
    a.ANEX_BORRADO, r.fech_vcmto, c.carp_desc, c.carp_codi,
    to_char(r.radi_fech_radi,'yyyy/mm/dd hh24:mi:ss') as FECHAE, 
    a.radi_nume_salida as rsalida, 
    to_char(a.anex_radi_fech,'yyyy/mm/dd hh24:mi:ss') as FECHAS,
    td.sgd_tpr_descrip AS TIPO, 
    r.ra_asun AS ASUNTO, 
    d.depe_nomb AS depe_actu, 
    u.usua_nomb AS nomb_actu, 
    r.radi_usu_ante as usant, 
    round(((r.radi_fech_radi+(td.sgd_tpr_termino * 7/5))-sysdate)) as diasr,
    r.radi_depe_actu, 
    to_char(a.sgd_fech_impres,'yyyy/mm/dd hh24:mi:ss') as FECH_IMPR,
    to_char(a.anex_fech_envio,'yyyy/mm/dd hh24:mi:ss') as fech_envio,
    a.anex_estado as estado
FROM
    radicado r
    LEFT JOIN anexos a  ON a.anex_radi_nume = r.radi_nume_radi
    INNER JOIN carpeta c ON c.carp_codi=r.carp_codi
    LEFT JOIN  sgd_tpr_tpdcumento td ON r.tdoc_codi=td.sgd_tpr_codigo
    INNER JOIN usuario u  ON r.radi_usua_actu=u.usua_codi  AND u.depe_codi=r.radi_depe_actu
    INNER JOIN dependencia d ON d.depe_codi=r.radi_depe_actu
    LEFT JOIN SGD_ANU_ANULADOS AN ON r.radi_nume_radi=AN.radi_nume_radi
WHERE

    AN.radi_nume_radi is null
   	and radi_depe_actu <> 999
   	AND substr(r.radi_nume_radi,5,1) <> 9
    AND r.radi_nume_radi like '%2'
    AND (a.ANEX_BORRADO = 'N' or a.ANEX_BORRADO is null) 
    {$where}";
			
		//	$db->conn->debug = true;
		
			$paginador= new myPaginador($db,$isql,1);
			$paginador->modoPintado(true);
			$paginador->setImagenASC($ruta_raiz."iconos/flechaasc.gif");
			$paginador->setImagenDESC($ruta_raiz."iconos/flechadesc.gif");
			$paginador->setFuncionFilas("pintarResultados");
			$paginador->generarPagina($titulos);
}
?>

</div></body>
</html>