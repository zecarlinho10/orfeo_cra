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

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Reportes Radicados Según Vencimiento</title>


<?include ($ruta_raiz."/htmlheader.inc.php")?>


	<script language="JavaScript" type="text/JavaScript">
/**
* Env�a el formulario de acuerdo a la opci�n seleccionada, que puede ser ver CSV o consultar
*/
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
$sql=  "SELECT 'Todas las dependencias' as DEPE_NOMB, 0 AS DEPE_CODI FROM DEPENDENCIA
   				 UNION SELECT 'SUBDIRECCION TECNICA CONSOLIDADO' AS DEPE_NOMB, 2 AS DEPE_CODI FROM DEPENDENCIA
				 UNION SELECT 'SUBDIRECCION ADMINISTRATIVA CONSOLIDADO' AS DEPE_NOMB, 3 AS DEPE_CODI FROM DEPENDENCIA
				 UNION SELECT 'OFICINA ASESORA JURÍDICA CONSOLIDADO' AS DEPE_NOMB, 4 AS DEPE_CODI FROM DEPENDENCIA
                 UNION  SELECT DEPE_NOMB, DEPE_CODI AS DEPE_CODI FROM DEPENDENCIA
                 WHERE DEPENDENCIA_ESTADO=1
				 order by DEPE_NOMB DESC";

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

   	


if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar")){
	
	
			
			require_once($ruta_raiz."include/myPaginador.inc.php");

			


$where=null;
	
 			
			$where=(!empty($_POST['dep']) && ($_POST['dep'])!="")?"AND r.radi_depe_actu = ".$_POST['dep']:"";		  if ($dep==2)
{
  $where=" AND r.radi_depe_radi in (401,410,420,430) ";	
}	
if ($dep==3)
{
  $where=" AND r.radi_depe_radi in (301,310,320,330,341,350) ";	
}	
if ($dep==4)
{
  $where=" AND r.radi_depe_radi in (211,212,213,214,215) ";	
}			 					   			
	 		
			$where.= " AND  (r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) + (select count(*) from SGD_NOH_NOHABILES S where S.NOH_FECHA BETWEEN r.radi_fech_radi AND r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) AND DESCRIP='F')) >=".$db->conn->DBTimeStamp($fecha_ini)." 
			AND (r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) + (select count(*) from SGD_NOH_NOHABILES S where S.NOH_FECHA BETWEEN r.radi_fech_radi AND r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) AND DESCRIP='F'))<=".$db->conn->DBTimeStamp($fecha_fin);
 			
 			$order=1;      	
		 
$titulos=array("RADICADO","FECHA RADICADO", "ASUNTO","CARPETA ACTUAL","DEPENDENCIA","FUNCIONARIO","FECHA DE VENCIMIENTO","No.DIAS HAB.","No.DIAS CAL.");
			
$isql = "SELECT distinct R.RADI_NUME_RADI  AS ENTRADA, r.radi_fech_radi as FECHAE,
td.sgd_tpr_termino AS TERMINO, c.carp_desc AS CARPETA, c.carp_codi,td.sgd_tpr_descrip AS TIPO, r.ra_asun AS ASUNTO, d.depe_nomb AS DEPE, u.usua_nomb AS FUNCIO, r.radi_depe_radi, 
sgd_dir_nomremdes REMITENTE, (M.MUNI_NOMB||' - '||DP.DPTO_NOMB)as UBICACION,
(r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) + (select count(*) from SGD_NOH_NOHABILES S where S.NOH_FECHA BETWEEN r.radi_fech_radi AND r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) AND DESCRIP='F')) AS FVCMTO
FROM radicado r INNER JOIN carpeta c ON c.carp_codi=r.carp_codi LEFT JOIN sgd_tpr_tpdcumento td ON r.tdoc_codi=td.sgd_tpr_codigo 
INNER JOIN usuario u ON r.radi_usua_actu=u.usua_codi AND u.depe_codi=r.radi_depe_actu INNER JOIN dependencia d ON d.depe_codi=r.radi_depe_actu 
INNER JOIN SGD_DIR_DRECCIONES DR ON DR.RADI_NUME_RADI = R.RADI_NUME_RADI, 
MUNICIPIO M, DEPARTAMENTO DP WHERE r.radi_nume_radi like '%2' AND DR.MUNI_CODI=M.MUNI_CODI AND DR.DPTO_CODI=M.DPTO_CODI AND DR.DPTO_CODI=DP.DPTO_CODI
AND RADI_DEPE_RADI NOT IN (900,905,910,999)
AND RADI_DEPE_ACTU NOT IN (999)
{$where}
order by ENTRADA";
			
			//$db->conn->debug = true;
$rs = $db->query($isql);

?>		
<table border=2 align="center" class="table table-bordered table-striped mart-form" width="80%">
        <tr>
                <td class="titulos2" align="center">RADICADO</td>
                <td class="titulos2" align="center">FECHA RADICACION</td>
                <td class="titulos2" align="center">TIPO DOC</td>
                <td class="titulos2"  align="center">TIEMPO (D.HAB)</td>
                <td class="titulos2" align="center">REMITENTE</td>
                <td class="titulos2" align="center">UBICACION</td>
                <td class="titulos2" align="center">CARPETA ACTUAL</td>
                <td class="titulos2"  align="center">DEPENDENCIA</td>
                <td class="titulos2" align="center">FUNCIONARIO</td>
                <td class="titulos2" align="center">FECHA DE VENCIMIENTO</td>
                <td class="titulos2" align="center">DIAS REST. HAB.</td>
                <td class="titulos2" align="center">DIAS REST. CAL.</td>
                
        </tr>
<?php
	while(!$rs->EOF){
		
	  				$numreginf++;
					 
	  			
			
		$radicadoin            = $rs->fields['ENTRADA'];
		$fechar      			= $rs->fields['FECHAE'];
		$tipodoc            = $rs->fields['TIPO'];
		$termino            = $rs->fields['TERMINO'];
		$remitente            = $rs->fields['REMITENTE'];
		$ubica      			= $rs->fields['UBICACION'];
		$carpeta            = $rs->fields['CARPETA'];
		$dependencia            = $rs->fields['DEPE'];
		$funcio      			= $rs->fields['FUNCIO'];
		$fvcmto            = $rs->fields['FVCMTO'];
	
		
	
$sqlsum="SELECT NOH_FECHA,SUMDIAS FROM SGD_NOH_NOHABILES WHERE NOH_FECHA= '$fvcmto'";
			//$db->conn->debug = true;
$rssum = $db->query($sqlsum);
$regfecha            = $rssum->fields['NOH_FECHA'];
$sumdia            = $rssum->fields['SUMDIAS'];

if ($regfecha!=0){
	$fvcmto=date('Y-m-d', strtotime("$fvcmto + $sumdia days"));
}

$hoy = date("Y-m-d");
if ($hoy <= $fvcmto){
$sqlnh="select count(NOH_FECHA)AS TD from SGD_NOH_NOHABILES where NOH_FECHA BETWEEN to_date('".date('d/m/y', strtotime("$hoy"))."', 'dd/mm/yy') AND to_date('".date('d/m/y', strtotime("$fvcmto"))."', 'dd/mm/yy')";
//$db->conn->debug = true;
$rsnh = $db->query($sqlnh);
$tnh = $rsnh->fields['TD'];
$s = strtotime($fvcmto)-strtotime($hoy);
$d = intval($s/86400);
$s -= $d*86400;
$h = intval($s/3600);
$s -= $h*3600;
$m = intval($s/60);
$s -= $m*60;
$drc= $d.$space; 
$drh= $drc-$tnh;
}else {
$sqlnh="select count(NOH_FECHA)AS TD from SGD_NOH_NOHABILES where NOH_FECHA BETWEEN to_date('".date('d/m/y', strtotime("$fvcmto"))."', 'dd/mm/yy') AND to_date('".date('d/m/y', strtotime("$hoy"))."', 'dd/mm/yy')";	
$rsnh = $db->query($sqlnh);
$tnh = $rsnh->fields['TD'];
$s = strtotime($fvcmto)-strtotime($hoy);
$d = intval($s/86400);
$s -= $d*86400;
$h = intval($s/3600);
$s -= $h*3600;
$m = intval($s/60);
$s -= $m*60;
$drc= $d.$space; 
$drh= $drc+$tnh;}



?>
        <tr>
                <td class="listado2" align="center"><?=$radicadoin?></td>
                <td class="listado2" align="center"><?=$fechar?></td>
                <td class="listado2" align="center"><?=$tipodoc?></td>
                <td class="listado2" align="center"><?=$termino?></td>
                <td class="listado2" align="center"><?=$remitente?></td>
                <td class="listado2" align="center"><?=$ubica?></td>
                <td class="listado2" align="center"><?=$carpeta?></td>
                <td class="listado2" align="center"><?=$dependencia?></td>
                <td class="listado2" align="center"><?=$funcio?></td>
                <td class="listado2" align="center"><?=$fvcmto?></td>
                <td class="listado2" align="center"><?=$drh?></td>
                <td class="listado2" align="center"><?=$drc?></td>
                
                
   
<?php

		$rs->MoveNext();
		
	}
		echo "<p><span class=listado2>Numero de Registros " . $numreginf."</span>";	
?>
    </tr>
</table>
<?
}
?>
</div></body>
</html>