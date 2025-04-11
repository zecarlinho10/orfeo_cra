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
$fecha_ini=$_POST['fecha_ini'];
$fecha_fin=$_POST['fecha_fin'];  
$dep=(isset($_POST['dep']))?$_POST['dep']:"";


?>
<html>
<head>


<title>CONSULTA RADICADOS NO TRAMITADOS POR LA DIRECCION</title>


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

<form action="rep_sindirecc.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			CONSULTA RADICADOS NO TRAMITADOS POR LA DIRECCION
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
				 UNION SELECT 'SUBDIRECCION ADMINISTRATIVA Y FINANCIERA CONSOLIDADO' AS DEPE_NOMB, 3 AS DEPE_CODI FROM DEPENDENCIA
                 UNION SELECT 'OFICINA ASESORA JURÍDICA CONSOLIDADO' AS DEPE_NOMB, 4 AS DEPE_CODI FROM DEPENDENCIA
                 UNION  SELECT DEPE_NOMB, DEPE_CODI AS DEPE_CODI FROM DEPENDENCIA
                 WHERE DEPENDENCIA_ESTADO=1
				 order by DEPE_NOMB DESC";

	$rsDep = $db->conn->Execute($sql);
	if(!$s_DEPE_CODI) $s_DEPE_CODI= 0;
	print $rsDep->GetMenu2("dep","$dep","0:-- Seleccione --", false, 0," class='select'");

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



if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar"))
{
	
	
			
			require_once($ruta_raiz."include/myPaginador.inc.php");


$where=null;

 			
$where=(!empty($_POST['dep']) && ($_POST['dep'])!="")?"AND r.radi_depe_radi = ".$_POST['dep']:"";		   					

if ($dep==2)
{
  $where=" AND r.radi_depe_radi in (401,410,420,430) ";	
}	
if ($dep==3)
{
  $where=" AND r.radi_depe_radi in (301,310,320,341,350) ";	
}	  
if ($dep==4)
{
  $where=" AND a.radi_depe_radi in (211,212,213,214,215) ";	
} 		
	   			
	 		
			$where.= " AND  TRUNC(RADI_FECH_RADI) BETWEEN (".$db->conn->DBTimeStamp($fecha_ini).") AND (".$db->conn->DBTimeStamp($fecha_fin).")";
 			
 			$order=1;      	
		 
$titulos=array("RADICADO","REMITENTE O DESTINO","UBICACION","FECHA RADICACION","TIPO DOC","DIAS TERMINO","RADICADO SALIDA","FECHA RADICADO SALIDA","ASUNTO","CARPETA ACTUAL","DEPENDENCIA","FUNCIONARIO","FECHA DE VENCIMIENTO","DIAS RESTANTES");
			
			$isql = "SELECT DISTINCT r.radi_nume_radi AS RADICADO, r.radi_fech_radi as FECHA, A.RADI_NUME_SALIDA AS RSALIDA,
td.sgd_tpr_descrip AS TIPO, DR.sgd_dir_nomremdes AS REM_DEST, r.ra_asun AS ASUNTO,
d.depe_nomb AS DEPE, (M.MUNI_NOMB||' - '||DP.DPTO_NOMB)as UBICACION 
FROM radicado r LEFT JOIN anexos a ON A.ANEX_RADI_NUME = r.radi_nume_radi 
LEFT JOIN sgd_tpr_tpdcumento td ON r.tdoc_codi=td.sgd_tpr_codigo 
INNER JOIN dependencia d ON d.depe_codi=r.radi_depe_radi
INNER JOIN SGD_DIR_DRECCIONES DR 
ON DR.RADI_NUME_RADI = R.RADI_NUME_RADI, MUNICIPIO M, DEPARTAMENTO DP WHERE substr(r.radi_nume_radi, 5, 1) <> 9 
and a.anex_estado >= 3
AND R.RADI_NUME_RADI NOT IN (SELECT RADI_NUME_RADI FROM HIST_EVENTOS WHERE (DEPE_CODI=201 AND USUA_CODI=126 AND SGD_TTR_CODIGO NOT IN (7,8)))
AND DR.MUNI_CODI=M.MUNI_CODI AND DR.DPTO_CODI=M.DPTO_CODI AND DR.DPTO_CODI=DP.DPTO_CODI AND DR.SGD_DIR_TIPO = 1 and r.radi_nrr <> 1 
    {$where}
    ORDER BY FECHA";
			
			//$db->conn->debug = true;
			$rs = $db->query($isql);
		
?>		
<table border=2 align="center" class="table table-bordered table-striped mart-form" width="80%">
        <tr>
                <td class="titulos2" align="center">RADICADO</td>
                <td class="titulos2" align="center">REMITENTE O DESTINO</td>
                <td class="titulos2" align="center">UBICACION</td>
                <td class="titulos2"  align="center">FECHA </td>
                <td class="titulos2" align="center">TIPO</td>
                <td class="titulos2" align="center">RESPUESTA</td>
                <td class="titulos2" align="center">ASUNTO</td>
                <td class="titulos2" align="center">DEPENDENCIA</td>


        </tr>
<?php
	while(!$rs->EOF){
		
	  				$numreginf++;
					 
	  			
			
		$radicadoin            = $rs->fields['RADICADO'];
		$fechar      			= $rs->fields['FECHA'];
		$tipodoc            = $rs->fields['TIPO'];
		$radicadosal            = $rs->fields['RSALIDA'];
		$asunto            = $rs->fields['ASUNTO'];
		$remitente            = $rs->fields['REM_DEST'];
		$ubica      			= $rs->fields['UBICACION'];
		$dependencia            = $rs->fields['DEPE'];
		

?>
        <tr>
                <td class="listado2" align="center"><?=$radicadoin?></td>
                <td class="listado2" align="center"><?=$remitente?></td>
                <td class="listado2" align="center"><?=$ubica?></td>
                <td class="listado2" align="center"><?=$fechar?></td>
                <td class="listado2" align="center"><?=$tipodoc?></td>
                <td class="listado2" align="center"><?=$radicadosal?></td>
                <td class="listado2" align="center"><?=$asunto?></td>
                <td class="listado2" align="center"><?=$dependencia?></td>


   
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
