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

?>
<html>
<head>
<title>CONSULTA VIGENCIA EXPEDIENTES</title>
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
<body onLoad="crea_var_idlugar_defa(<?php echo "'".($_SESSION['cod_local'])."'"; ?>);">
<?
$params = session_name()."=".session_id()."&krd=$krd";
?>
<div id="spiffycalendar" class="text"></div>
<form action="repvigencia.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >
<input type="hidden" name="selected0" value="<?=$selected0?>">
<input type="hidden" name="selected1" value="<?=$selected1?>">
<input type="hidden" name="selected2" value="<?=$selected2?>">
<input type="hidden" name="selectedctt0" value="<?=$selectedctt0?>">
<input type="hidden" name="selectedctt1" value="<?=$selectedctt1?>">
<input type="hidden" name="nombre1" value="<?=$nombre?>">
<input type="hidden" name="tipo_masiva" value="<?=$_POST['masiva']?>">  <!-- Este valor viene cuando se invoca este archivo en selecConsultaESP.php -->
<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			CONSULTA VIGENCIA EXPEDIENTES
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>
    </tr>
    

       <tr align="center" colspan="2">
		<td width="31%" class='titulos2'>SERIE</td>
		<td width="69%" height="30" class='listado2' align="left">
		<?php
$fecha_hoy = Date("Y-m-d");
$sqlFechaHoy=$db->conn->DBDate($fecha_hoy);
					 
$sql = " select distinct srd.sgd_srd_descrip, srd.sgd_srd_codigo, s.sgd_srd_codigo 
		 from  sgd_srd_seriesrd srd, sgd_sexp_secexpedientes s
		 where ".$sqlFechaHoy." between srd.sgd_srd_fechini and srd.sgd_srd_fechfin
		 and s.sgd_srd_codigo=srd.sgd_srd_codigo
		 and s.depe_codi <> 900
		and s.depe_codi <> 905
		 order by sgd_srd_descrip";
		//$db->conn->debug = true;
		$rssrd = $db->conn->Execute($sql);
		
	if(!$s_SGD_SRD_CODIGO) $s_SGD_SRD_CODIGO= 0;
	print $rssrd->GetMenu2("tsrd", $tsrd, "0:-- Seleccione --", false,"","onChange='submit()' class='select'" );	
	
	 ?>
	</td>
     </tr>
     <tr align="center" colspan="2">
		<td width="31%" class='titulos2'>SUBSERIE</td>
		<td width="69%" height="30" class='listado2' align="left">
  
 <?
	include "$ruta_raiz/include/query/trd/queryCodiDetalle.php"; 
   	$querySub = "select distinct su.sgd_sbrd_descrip, su.sgd_sbrd_codigo 
	         from sgd_sexp_secexpedientes s, sgd_sbrd_subserierd su
			 where s.sgd_srd_codigo = '$tsrd'
				   and su.sgd_srd_codigo = '$tsrd'
			       and su.sgd_sbrd_codigo =s.sgd_sbrd_codigo
 			       and ".$sqlFechaHoy." between su.sgd_sbrd_fechini and su.sgd_sbrd_fechfin
			 
			  ";
	$rsSub=$db->conn->query($querySub);
	include "$ruta_raiz/include/tx/ComentarioTx.php";
	print $rsSub->GetMenu2("tsub", $tsub, "0:-- Seleccione --", false,"","onChange='submit()' class='select'" );
?>
	  </td>
	</tr>         
    <tr align="center" colspan="2">
      <td width="31%" class='titulos2'>DEPENDENCIA</td>
     <td width="69%" height="30" class='listado2' align="left">
			 <?
$sql = " SELECT distinct d.DEPE_NOMB,d.depe_codi, s.sgd_srd_codigo, s.sgd_sbrd_codigo, s.depe_codi
	FROM DEPENDENCIA d, sgd_sexp_secexpedientes s
	where s.sgd_srd_codigo='$tsrd' 
	and s.sgd_sbrd_codigo='$tsub' 
	and s.depe_codi= d.depe_codi
	and s.depe_codi <> 900
	and d.dependencia_estado=1
	and s.depe_codi <> 905 ";
	//$db->conn->debug = true;
	$rsDep = $db->conn->Execute($sql);
	if(!$s_DEPE_CODI) $s_DEPE_CODI= 0;
	print $rsDep->GetMenu2("dep","$dep","0:-- Seleccione --",false, "onChange='submit()' class='select'");

	
	?>
	</td>
</tr>	
 



     <tr align="center" colspan="2">
		<td width="31%" class='titulos2'>EXPEDIENTE</td>
		<td width="69%" height="30" class='listado2' align="left">
			<input name="exp" id="exp" type="input" size="50" class="tex_area" value="<?php echo $exp?>" />
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
				$salida=$fila['SGD_EXP_NUMERO'];
				break;
			case 1:
				$salida=$fila['SGD_SEXP_FECH'];
				break;
			case 2:
				$salida=$fila['PARAMETRO'];
				break;
			case 3:
				$orange ="<img src='$rutaRaiz/orfeo/iconos/orange.gif' width=6 height=6>";
				$actual=date("Y-m-d");
				$fvag=$fila['FVIGENCIA_AG'];
				if ($actual<=$fvag){
				?><span class="listado2"><?$salida=$fila['FVIGENCIA_AG']?><?;
				}
				else {
				?><span class="listado2"><?$salida=$orange." ".$fila['FVIGENCIA_AG']?><?;}
				breaK; 
			case 4:
				$red ="<img src='$rutaRaiz/orfeo/iconos/red.gif' width=6 height=6>";
				$actual=date("Y-m-d");
				$fvag=$fila['FVIGENCIA_AG_C'];
				if ($actual<=$fvag){
				?><span class="listado2"><?$salida=$fila['FVIGENCIA_AG_C']?><?;
				}
				else {
				?><span><?$salida=$red." ".$fila['FVIGENCIA_AG_C']?><?;}
				breaK; 	
			case 5:
				
				$salida=$fila['FVIGENCIA_AC'];
				break;
			Default:$salida="ERROR";
		}
		return $salida;	
	}
	
if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar")){

	       require_once($ruta_raiz."include/myPaginador.inc.php");
			
		 			
$where=null;
	
 			
			
			$where=(!empty($_POST['exp']) && trim($_POST['exp'])!="")?(
							($where!="")? $where." AND sb.SGD_EXP_NUMERO LIKE '%".strtoupper(trim($_POST['exp']))."%'":" WHERE sb.SGD_EXP_NUMERO LIKE '%".strtoupper(trim($_POST['exp'])."%' ")) 			
							:$where;
			$where=(!empty($_POST['tsrd']) && trim($_POST['tsrd'])!="")?(
							($where!="")? $where." AND sb.sgd_srd_codigo LIKE '%".strtoupper(trim($_POST['tsrd']))."%'":" WHERE sb.sgd_srd_codigo LIKE '%".strtoupper(trim($_POST['tsrd'])."%'")) 			
							:$where;
			$where=(!empty($_POST['tsbrd']) && trim($_POST['tsbrd'])!="")?(
							($where!="")? $where." AND sb.sgd_sbrd_codigo LIKE '%".strtoupper(trim($_POST['tsbrd']))."%'":" WHERE sb.sgd_sbrd_codigo LIKE '%".strtoupper(trim($_POST['tsbrd'])."%'")) 			
							:$where;
			$where=(!empty($_POST['dep']) && trim($_POST['dep'])!="")?(
							($where!="")? $where." AND sb.depe_codi = ".$_POST['dep']:" WHERE sb.depe_codi = ".$_POST['dep']) 			
							:$where;
							
			
	 		$where = (empty($where) && ($where==null))?" WHERE sb.SGD_sEXP_FECH>=".$db->conn->DBTimeStamp($fecha_ini):$where." AND  sb.SGD_sEXP_FECH>=".$db->conn->DBTimeStamp($fecha_ini);
 			$where = $where . " AND sb.SGD_sEXP_FECH<=".$db->conn->DBTimeStamp($fecha_fin);
 	      
			
 			
 			$order=1;      	
			$titulos=array("1#EXPEDIENTE","2#FECHA CREACION","3#NOMBRE","4#VIGENCIA ARCHIVO GENERAL","5#VIGENCIA ARCHIVO GENERAL CIERRE","6#VIGENCIA ARCHIVO CENTRAL");
      	
			
			$isql = "select  distinct sb.SGD_EXP_NUMERO, max(s.sgd_exp_fech) OVER (PARTITION BY s.sgd_exp_numero) as max_fech, sb.SGD_sEXP_FECH, sb.depe_codi, (sb.sgd_sexp_parexp1||' '||sb.sgd_sexp_parexp2||' '||sb.sgd_sexp_parexp3||' '||sb.sgd_sexp_parexp4||' '||sb.sgd_sexp_parexp5) as PARAMETRO, sb.sgd_srd_codigo, sb.sgd_sbrd_codigo, sub.sgd_sbrd_tiemag, sub.sgd_sbrd_tiemac, sysdate as fechactu,
					round(to_date (max(s.sgd_exp_fech) OVER (PARTITION BY s.sgd_exp_numero)+(sub.sgd_sbrd_tiemag * 365))) as fvigencia_ag, 
					round(to_date (max(s.sgd_exp_fech) OVER (PARTITION BY s.sgd_exp_numero)+(sub.sgd_sbrd_tiemag * 365) + 365)) as fvigencia_ag_c,
					round(to_date (max(s.sgd_exp_fech) OVER (PARTITION BY s.sgd_exp_numero)+(sub.sgd_sbrd_tiemac * 365))) as fvigencia_ac
					from sgd_sexp_secexpedientes sb inner join sgd_exp_expediente s on sb.sgd_exp_numero=s.sgd_exp_numero, sgd_sbrd_subserierd sub 
					{$where}
					and sb.sgd_srd_codigo=sub.sgd_srd_codigo 
					and sb.sgd_sbrd_codigo=sub.sgd_sbrd_codigo";
			
		
					//$db->conn->debug = true;		
			$rs=$db->conn->query($isql);
					
					$ftiemag=$rs->fields['FVIGENCIA_AG'];
					$ftiemac=$rs->fields['FVIGENCIA_AC'];
					$fechactu=$rs->fields['FECHACTU'];
				
					
				
											
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