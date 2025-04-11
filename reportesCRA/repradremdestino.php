<?
/**
 * Programa que despliega Radicados de entrada en un periodo determinado
 * @author  YULLIE QUICANO
 * @mail    yquicano@cra.gov.co
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
$depe=(isset($_POST['depe']))?$_POST['depe']:"";
$tip=(isset($_POST['tip']))?$_POST['tip']:"";
$remdes=(isset($_POST['remdes']))?$_POST['remdes']:"";

?>
<html>
<head>


<title>Reportes De Transaciones Con Radicados</title>


<?include ($ruta_raiz."/htmlheader.inc.php")?>


	<script language="JavaScript" type="text/JavaScript">
/**
* Env�a el formulario de acuerdo a la opci�n seleccionada, que puede ser ver CSV o consultar
*/
function enviar(argumento)
{	document.formEnvio.action=argumento+"&"+document.formEnvio.params.value;
	document.formEnvio.submit();
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
   var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "formEnvio", "fecha_ini","btnDate1","<?=$fecha_ini?>",scBTNMODE_CUSTOMBLUE);
   var dateAvailable2 = new ctlSpiffyCalendarBox("dateAvailable2", "formEnvio", "fecha_fin","btnDate2","<?=$fecha_fin?>",scBTNMODE_CUSTOMBLUE);

--></script>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>

 <body><div class="table-responsive">
<div id="spiffycalendar" class="text"></div>

<?
$params = session_name()."=".session_id()."&krd=$krd";
?>

<form action="repradremdestino.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formEnvio" id="formEnvio" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
				REPORTE RADICADOS SEGUN REMITENTE O DESTINO
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>	
    
<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>DEPENDENCIA</td>
		<td width="69%" height="30" class='listado2' align="left">
		
		 <?
		$sql ="SELECT DEPE_NOMB, DEPE_CODI FROM DEPENDENCIA
				 WHERE DEPENDENCIA_ESTADO=1
				order by DEPE_NOMB";

	$rsdepe = $db->conn->Execute($sql);
	if(!$s_USUA_CODI) $s_USUA_CODI= 0;
	print $rsdepe->GetMenu2("depe", $depe, "0:-- Seleccione --", false,"","onChange='submit()' class='select'" );


	?>
	
    </td>
    </tr>	

<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>TIPO RADICADO</td>
		<td width="69%" height="30" class='listado2' align="left">
		
		 <?
$sql = "SELECT  CARP_DESC, CARP_CODI FROM CARPETA  WHERE CARP_CODI NOT IN (0, 9, 2, 4, 12, 11, 5, 10) UNION  SELECT 'Entrada' as CARP_DESC, 6 AS CARP_CODI FROM CARPETA
                 
				 order by CARP_DESC";

	$rsest = $db->conn->Execute($sql);
	print $rsest->GetMenu2("tip","$tip","0:-- Seleccione --", false, 0,"onChange='submit()' class='select'");

	?>
	</td>
</tr>
	
<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>REMITENTE O DESTINO 
		<td class="listado2" height="1">
		<input type=text name=remdes id=remdes value='<?=$remdes?>' size=50 maxlenght="4000" > </td>
			
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

   	
    	
function pintarResultados($fila,$i,$n){
		global $ruta_raiz;
		switch($n){
			case 0:
				$salida=$fila['RADICADO'];
 				break;
 			case 1:
				$salida=$fila['ASUNTO'];
				break;	
			case 2:
				$salida=$fila['FECH_RADI'];
				break;
			case 3:
				$salida=$fila['RADI_NUME_DERI'];
				break;	
			case 4:
				$salida=$fila['SGD_DIR_NOMREMDES'];
				break;	
			case 5:
				$salida=$fila['UBICACION'];
				break;
			case 6:
				$salida=$fila['TPR_DESCRIP'];
				break;		

			default:$salida="ERROR";
		}
		return $salida;	
	}

if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar")){
	
	
			
			require_once($ruta_raiz."include/myPaginador.inc.php");

			


$where=null;


if (!empty($_POST['tip'])&& ($_POST['tip']=="1")&& ($_POST['depe']=="0")){				
			$where=(!empty($_POST['tip']) && ($_POST['tip'])!="")?"  AND r.radi_nume_radi like '%1'":"";}

if(!empty($_POST['tip'])&& ($_POST['tip']=="6")&& ($_POST['depe']=="0")){	
			$where=(!empty($_POST['tip']) && ($_POST['tip'])!="")?"  AND r.radi_nume_radi like '%2'":"";}

if (!empty($_POST['tip'])&& ($_POST['tip']=="3")&& ($_POST['depe']=="0")){				
			$where=(!empty($_POST['tip']) && ($_POST['tip'])!="")? " AND r.radi_nume_radi like '%3'":"";}
							
			
if (!empty($_POST['tip'])&& ($_POST['tip']=="1")&& ($_POST['depe']!="0")){				
			$where=(!empty($_POST['tip']) && ($_POST['tip'])!="")?" AND R.RADI_depe_radi=".$_POST['depe']." AND r.radi_nume_radi like '%1'":"";}

if(!empty($_POST['tip'])&& ($_POST['tip']=="6")&& ($_POST['depe']!="0")){	
			$where=(!empty($_POST['tip']) && ($_POST['tip'])!="")?" AND R.RADI_depe_radi=".$_POST['depe']." AND r.radi_nume_radi like '%2'":"";}

if (!empty($_POST['tip'])&& ($_POST['tip']=="3")&& ($_POST['depe']!="0")){				
			$where=(!empty($_POST['tip']) && ($_POST['tip'])!="")? " AND R.RADI_depe_radi=".$_POST['depe']." AND r.radi_nume_radi like '%3'":"";}
			 
$where.=(!empty($_POST['remdes']) && trim($_POST['remdes'])!="")? " AND DR.sgd_dir_nomremdes LIKE '%".strtoupper(trim($_POST['remdes']))."%' ":""; 
			
$where.= " AND  TRUNC(R.RADI_FECH_RADI) BETWEEN (".$db->conn->DBTimeStamp($fecha_ini).") AND (".$db->conn->DBTimeStamp($fecha_fin).")";
		
 			$order=1;  
			    	
		 
$titulos=array("RADICADO","ASUNTO","FECHA RADICACION","ASOCIADO","REMITENTE O DESTINO","UBICACION","TIPO");

			$isql = "SELECT R.RADI_NUME_RADI AS RADICADO, R.RA_ASUN AS ASUNTO, 
					to_char(R.RADI_FECH_RADI,'yyyy/mm/dd hh24:mi:ss') AS FECH_RADI, 
					DR.sgd_dir_nomremdes, (MUNI_NOMB||' - '||DPTO_NOMB)as UBICACION,
					R.RADI_DEPE_actu,r.radi_usua_actu, TP.SGD_TPR_CODIGO, TP.SGD_TPR_DESCRIP, R.RADI_NUME_DERI
					FROM RADICADO R
     				INNER JOIN SGD_DIR_DRECCIONES DR ON DR.RADI_NUME_RADI = R.RADI_NUME_RADI,
     				MUNICIPIO M, DEPARTAMENTO DP, SGD_TPR_TPDCUMENTO TP
					WHERE R.RADI_DEPE_RADI NOT IN (900, 905, 910, 999)
					AND DR.MUNI_CODI=M.MUNI_CODI 
					AND DR.DPTO_CODI=M.DPTO_CODI
					AND DR.DPTO_CODI=DP.DPTO_CODI
					AND TP.SGD_TPR_CODIGO=R.TDOC_CODI
					
					{$where}";
			
			//$db->conn->debug = true;
		
			$paginador= new myPaginador($db,$isql,1);
			$paginador->modoPintado(true);
			$paginador->setImagenASC($ruta_raiz."iconos/flechaasc.gif");
			$paginador->setImagenDESC($ruta_raiz."iconos/flechadesc.gif");
			$paginador->setFuncionFilas("pintarResultados");
			//$paginador->generarPagina($titulos);
			
		error_reporting(0);
	require "../anulacion/class_control_anu.php";
	$btt = new CONTROL_ORFEO($db);
	$campos_align = array("L","L","L","L","C");
	$campos_tabla = array("RADICADO","ASUNTO","FECH_RADI","RADI_NUME_DERI","SGD_DIR_NOMREMDES","UBICACION","SGD_TPR_DESCRIP");
	$btt->campos_align = $campos_align;
	$btt->campos_tabla = $campos_tabla;
	$btt->campos_vista = $titulos;
	$btt->campos_width = $campos_width;
	$btt->tabla_sql($isql);	
}
?>
</div></body>
</html>
