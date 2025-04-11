<?
   
	
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
$dep=(isset($_POST['dep']))?$_POST['dep']:"";
$fvcmto1=(isset($_POST['fvcmto1']))?$_POST['fvcmto1']:"";


?>


<html>
<head>


<title>Reporte Indicador PDA </title>


<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
<script language="javascript">
$(document).ready(function() {
	$(".botonExcel").click(function(event) {
		$("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).html());
		$("#FormularioExportacion").submit();
});
});
</script>
<style type="text/css">
.botonExcel{cursor:pointer;}
</style>



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


</head>

 <body><div class="table-responsive">
<div id="spiffycalendar" class="text"></div>

<?
$params = session_name()."=".session_id()."&krd=$krd";
?>

<form action="ind_gest2.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			Reporte Indicador Gestión 2
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
   				 UNION  SELECT DEPE_NOMB, DEPE_CODI AS DEPE_CODI FROM DEPENDENCIA
                 WHERE DEPE_CODI IN (211,401,220,230,301)
				 order by DEPE_NOMB DESC";

		 
	 				 
	$rsDep = $db->conn->Execute($sql);
	
	if(!$s_DEPE_CODI) $s_DEPE_CODI= 0;
	
	print $rsDep->GetMenu2("dep","$dep","0:-- Seleccione --", false, 0," class='select'");
	
	$sel=$_POST['fvcmto1'];
	

	?>
	
	
	 <!---- Agregue CUMPLIMIENTO----> 
	
	</td>
</tr> 
<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>TRAMITE</td>
	<td width="69%" height="30" class='listado2' align="left">
	
	<label>
<input type="radio" name="fvcmto1" value="CUMPLE" <? if ($fvcmto1=="CUMPLE") echo "checked"; else echo "";?>>
Cumple</label>
<br>
<label>
<input type="radio" name="fvcmto1" value="NO CUMPLE" <? if ($fvcmto1=="NO CUMPLE") echo "checked"; else echo "";?> >
No Cumple</label>
<label>

<input type="radio" name="fvcmto1" value="TODOS" <? if ($fvcmto1=="TODOS") echo "checked"; else echo "";?>>
Mostrar Todos</label>
<label>


	
	  <!---- Agregue CUMPLIMIENTO----> 
	
	
	 
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



if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar"))
{
	
	
			
			require_once($ruta_raiz."include/myPaginador.inc.php");


$where=null;

 			
$where=(!empty($_POST['dep']) && ($_POST['dep'])!="")?"AND r.radi_depe_radi = ".$_POST['dep']:"";		   					

 			
	 		
			$where.= " AND  TRUNC(A.ANEX_RADI_FECH) BETWEEN (".$db->conn->DBTimeStamp($fecha_ini).") AND (".$db->conn->DBTimeStamp($fecha_fin).")";
 			
 			$order=1;      	
		 
$titulos=array("RADICADO","REMITENTE O DESTINO","UBICACION","FECHA RADICACION","TIPO DOC","DIAS TERMINO","RADICADO SALIDA","FECHA RADICADO SALIDA","ASUNTO","CARPETA ACTUAL","DEPENDENCIA","FUNCIONARIO","FECHA DE VENCIMIENTO","DIAS RESTANTES");
			
			$isql = "SELECT DISTINCT r.radi_nume_radi AS ENTRADA, 
    a.ANEX_BORRADO, 
    r.radi_fech_radi as FECHAE, 
    a.radi_nume_salida as RSALIDA, 
    a.anex_radi_fech as FECHAS,
    a.anex_fech_envio as FECHAENV,
    td.sgd_tpr_descrip AS TIPO, 
    r.ra_asun AS ASUNTO, 
	d.depe_nomb AS DEPE_NOMB,
    DR.sgd_dir_nomremdes REMITENTE,
    a.anex_creador,
    td.sgd_tpr_termino as termino,
    r.fech_vcmto as FVCMTO,
     sgd_dir_nomremdes, (M.MUNI_NOMB||' - '||DP.DPTO_NOMB)as UBICACION
    
FROM
    radicado r
    LEFT JOIN anexos a  ON a.anex_radi_nume = r.radi_nume_radi
    LEFT JOIN  sgd_tpr_tpdcumento td ON r.tdoc_codi=td.sgd_tpr_codigo
    INNER JOIN dependencia d ON d.depe_codi=r.radi_depe_radi
    INNER JOIN SGD_DIR_DRECCIONES DR ON DR.RADI_NUME_RADI = R.RADI_NUME_RADI,
    MUNICIPIO M, DEPARTAMENTO DP
WHERE

    substr(r.radi_nume_radi,5,1) <> 9
    AND r.radi_nume_radi like '%2'
    AND (a.ANEX_BORRADO = 'N' or a.ANEX_BORRADO is null)
    AND DR.MUNI_CODI=M.MUNI_CODI 
	AND DR.DPTO_CODI=M.DPTO_CODI
	AND DR.DPTO_CODI=DP.DPTO_CODI 
	AND DR.SGD_DIR_TIPO = 1 
	and A.RADI_NUME_SALIDA IN (SELECT RADI_NUME_RADI FROM RADICADO WHERE SGD_EANU_CODIGO NOT IN (1,2))
	and r.radi_nrr=0
    {$where}
    ORDER BY FECHAS";
			
			$rs = $db->query($isql);

			



		
?>	  


<table border=2 align="center" class="table table-bordered table-striped mart-form" width="80%" id="Exportar_a_Excel" >
        <tr>
                <td class="titulos2" align="center">RADICADO</td>
                <td class="titulos2" align="center">REMITENTE</td>
                <td class="titulos2" align="center">UBICACION</td>
                <td class="titulos2"  align="center">FECHA </td>
                <td class="titulos2" align="center">TIPO</td>
                <td class="titulos2" align="center">TERMINO</td>
                <td class="titulos2" align="center">ASUNTO</td>
                <td class="titulos2" align="center">RESPUESTA</td>
                <td class="titulos2"  align="center">FECHA RTA</td>
				<td class="titulos2" align="center">RESPONSABLE</td>
                <td class="titulos2"  align="center">ENVIO</td>
                 <td class="titulos2" align="center">FECHA VCMTO</td>
	            <td class="titulos2" align="center">CUMPLIMIENTO</td>
                <td class="titulos2" align="center">DEPENDENCIA</td>
				
        </tr>
<?php


//Nueva Implementacion Calculo de Termino para 13 dias 28 y 8

// Calculo para Fecha De Vencimiento De Radicado
//if($fvcmto)
  //  $fvcmtof= $fvcmto+2;
//else {
   // echo $fvcmtof;
//}

include_once "../tx/diasHabilesmio.php";
$i=0;
	while(!$rs->EOF){ 
		
	  				$numreginf++;
					 
	  			
			
		$radicadoin            = $rs->fields['ENTRADA'];
		$fechar      			= $rs->fields['FECHAE'];
		$tipodoc            = $rs->fields['TIPO'];
		$termino            = $rs->fields['TERMINO'];
		$radicadosal            = $rs->fields['RSALIDA'];
		$asunto            = $rs->fields['ASUNTO'];
		$fechas            = $rs->fields['FECHAS'];
		$fechaenv            = $rs->fields['FECHAENV'];
		$remitente            = $rs->fields['REMITENTE'];
		$ubica      			= $rs->fields['UBICACION'];
		$carpeta            = $rs->fields['CARPETA'];
		$dependencia            = $rs->fields['DEPE'];
		$funcio      			= $rs->fields['FUNCIO'];
		$fvcmto            = $rs->fields['FVCMTO'];		
		$responsable            = $rs->fields['ANEX_CREADOR'];	
		$BORRADOR            = $rs->fields['ANEX_BORRADOR'];
		$depen1            = $rs->fields['DEPE_NOMB'];	
		


/*				
$sqlsum="SELECT NOH_FECHA,SUMDIAS FROM SGD_NOH_NOHABILES WHERE NOH_FECHA= '$fvcmto'";
			//$db->conn->debug = true;
$rssum = $db->query($sqlsum);
$regfecha            = $rssum->fields['NOH_FECHA'];
$sumdia            = $rssum->fields['SUMDIAS'];

if ($regfecha!=0){
	$fvcmto=date('Y-m-d', strtotime("$fvcmto + $sumdia days"));
}
*/
//sumar dias Implementado JULIO 2012
if($termino==13 or $termino==28 or $termino==8 )
 {
    $terminof= $termino+2;
   // $fvcmto= $fvcmto+2;
	$fvcmtof=date('Y-m-d', strtotime("$fvcmto + 2 days"));
 }	
    else 
	{
       echo $terminof;
       echo $fvcmtof;	  
 }



if ($fvcmtof>=$fechaenv){
	$ctr="CUMPLE";} else $ctr="NO CUMPLE";
	
	
	if($ctr==$sel || $sel=="TODOS"){
		
		$i=$i+1;
	
	
		
/*
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
*/
$Dha = new FechaHabil($db);
$drh =  $Dha->getDiasRestantes($radicadoin,$fechas,$tipodoc);
$drc =  $Dha->getDiasRestantesCalendario($radicadoin);

?>
        <tr>
                <td class="listado2" align="center"><?=$radicadoin?></td>
                <td class="listado2" align="center"><?=$remitente?></td>
                <td class="listado2" align="center"><?=$ubica?></td>
                <td class="listado2" align="center"><?=$fechar?></td>
                <td class="listado2" align="center"><?=$tipodoc?></td>
                <td class="listado2" align="center"><?=$terminof?></td>
                <td class="listado2" align="center"><?=$asunto?></td>
                <td class="listado2" align="center"><?=$radicadosal?></td>
                <td class="listado2" align="center"><?=$fechas?></td>
                <td class="listado2" align="center"><?=$responsable?></td>
                <td class="listado2" align="center"><?=$fechaenv?></td>
                <td class="listado2" align="center"><?=$fvcmtof?></td>
				<td class="listado2" align="center"><?=$ctr?></td>
				<td class="listado2" align="center"><?=$depen1?></td>
				
				
   
<?php
}
		$rs->MoveNext();
		
	}

	$ind=round($i/$numreginf*100);
	$ind= "$ind %";

	
		echo "<p><span class=listado2>Número de Registros: " . $numreginf."</span>";	
		echo "<p><span class=listado2>Radicados con cumplimiento juan:" . $i." " .$fvcmto1."</span>";	
		echo "<p><span class=resultados>Indicador de Gestión: ". $ind."</span>";	
	
?>
	
<form action="ficheroExcel.php" method="post" target="_blank" rel="noopener noreferrer" id="FormularioExportacion">
<p>Exportar a Excel  <img src="export_to_excel.gif" class="botonExcel" /></p>
<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
</form>
	
    </tr>
</table>

<?
}
?>
</div></body>
</html>
