<?
/*************************************************************************************/
/* ORFEO GPL:Sistema de Gestion Documental		http://www.orfeogpl.org	     */
/*	Idea Original de la SUPERINTENDENCIA DE SERVICIOS PUBLICOS DOMICILIARIOS     */
/*				COLOMBIA TEL. (57) (1) 6913005  orfeogpl@gmail.com   */
/* ===========================                                                       */
/*                                                                                   */
/* Este programa es software libre. usted puede redistribuirlo y/o modificarlo       */
/* bajo los terminos de la licencia GNU General Public publicada por                 */
/* la "Free Software Foundation"; Licencia version 2. 			                     */
/*                                                                                   */
/* Copyright (c) 2005 por :	  	  	                                                 */
/* C.R.A.  "COMISION DE REGULACION DE AGUA"                                          */
/*   Lucia Ojeda          lojedaster@gmail.com             Desarrolladora            */
/*																					 */
/* Colocar desde esta lInea las Modificaciones Realizadas Luego de la Version 3.5    */
/*  Nombre Desarrollador   Correo     Fecha   Modificacion                           */
/*************************************************************************************/
/**
 * Programa que despliega Radicados de entrada pendientes por responder
 * @author  Jully Quicano
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
$dep=(isset($_POST['dep']))?$_POST['dep']:"";

?>
<html>
<head>


<title>Reportes Radicados de Entrada </title>


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
{	document.formRadi.action=argumento+"&"+document.formRadi.params.value;
	document.formRadi.submit();
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
   var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "formRadi", "fecha_ini","btnDate1","<?=$fecha_ini?>",scBTNMODE_CUSTOMBLUE);
   var dateAvailable2 = new ctlSpiffyCalendarBox("dateAvailable2", "formRadi", "fecha_fin","btnDate2","<?=$fecha_fin?>",scBTNMODE_CUSTOMBLUE);

--></script>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>

 <body><div class="table-responsive">
<div id="spiffycalendar" class="text"></div>

<?
$params = session_name()."=".session_id()."&krd=$krd";
?>

<form action="repporresponder.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formRadi" id="formRadi" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			RADICADOS POR RESPONDER
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>	
	<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>DEPENDENCIA</td>
		<td width="69%" height="30" class='listado2' align="left">
		
		 <?
   $sql =  "SELECT 'Todas las dependencias' as DEPE_NOMB, 0 AS DEPE_CODI FROM DEPENDENCIA
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
	</td>
  </tr>	
				
	
	<tr align="center">
		<td height="30" colspan="4" class='listado2'>
		
	
		<center>
			<input name="Consulta" type="submit"  class="botones" id="envia22"   value="Consulta">&nbsp;&nbsp;

		</center>
		
		</td>
	</tr>
</table>
</form>
	
<?php 

   	

if(!empty($_POST['Consulta'])&& ($_POST['Consulta']=="Consulta")){

$where=(!empty($_POST['dep']) && ($_POST['dep'])!="")?"AND r.radi_depe_actu = ".$_POST['dep']:"";		

if ($dep==2)
{
  $where=" AND r.radi_depe_actu in (401,410,420,430) ";	
}	
if ($dep==3)
{
  $where=" AND r.radi_depe_actu in (301,310,320,341,350) ";	
}	
if ($dep==4)
{
  $where=" AND r.radi_depe_actu in (211,212,213,214,215) ";	
}		   					   			
	 		

			
$isql = "SELECT distinct R.RADI_NUME_RADI  AS ENTRADA, r.radi_fech_radi as FECHAE,
td.sgd_tpr_termino AS TERMINO, c.carp_desc AS CARPETA, c.carp_codi,td.sgd_tpr_descrip AS TIPO, r.ra_asun AS ASUNTO, d.depe_nomb AS DEPE, u.usua_nomb AS FUNCIO, r.radi_depe_actu, 
sgd_dir_nomremdes REMITENTE, (M.MUNI_NOMB||' - '||DP.DPTO_NOMB)as UBICACION,
 r.fech_vcmto as FVCMTO
FROM radicado r INNER JOIN carpeta c ON c.carp_codi=r.carp_codi LEFT JOIN sgd_tpr_tpdcumento td ON r.tdoc_codi=td.sgd_tpr_codigo 
INNER JOIN usuario u ON r.radi_usua_actu=u.usua_codi AND u.depe_codi=r.radi_depe_actu INNER JOIN dependencia d ON d.depe_codi=r.radi_depe_actu 
INNER JOIN SGD_DIR_DRECCIONES DR ON DR.RADI_NUME_RADI = R.RADI_NUME_RADI, 
MUNICIPIO M, DEPARTAMENTO DP 
WHERE r.radi_nume_radi like '%2' 
AND RADI_DEPE_ACTU NOT IN (900,905,910,999)
AND DR.MUNI_CODI=M.MUNI_CODI AND DR.DPTO_CODI=M.DPTO_CODI AND DR.DPTO_CODI=DP.DPTO_CODI
{$where}
order by ENTRADA";
			
//$db->conn->debug = true;

$rs = $db->query($isql);

	

?>		
<table border=2 align="center" class="table table-bordered table-striped mart-form" width="80%" id="Exportar_a_Excel">
        <tr>
                <td class="titulos2" align="center">RADICADO</td>
                <td class="titulos2" align="center">FECHA RADICACION</td>
                <td class="titulos2" align="center">TIPO DOC</td>
                <td class="titulos2" align="center">TIEMPO (D.HAB)</td>
                <td class="titulos2" align="center">REMITENTE</td>
                <td class="titulos2" align="center">UBICACION</td>
                <td class="titulos2" align="center">CARPETA ACTUAL</td>
                <td class="titulos2" align="center">DEPENDENCIA</td>
                <td class="titulos2" align="center">FUNCIONARIO</td>
                <td class="titulos2" align="center">FECHA DE VENCIMIENTO</td>
                <td class="titulos2" align="center">DIAS REST. HAB.</td>
                <td class="titulos2" align="center">DIAS REST. CAL.</td>
        </tr>
<?php

//7/5/18 CARLOS RICAURTE-- se pone linea en comentario para probar con la clase inicial
//include_once "../tx/diasHabilesmio.php";
include_once "../tx/diasHabiles.php";
include_once (realpath(dirname(__FILE__) . "/../include/utils/Calendario.php"));
$calendario = new Calendar($db);
	while(!empty($rs) && !$rs->EOF){
		$numreginf++;
		
		$radicadoin         = $rs->fields['ENTRADA'];
		$fechar      		= $rs->fields['FECHAE'];
		$tipodoc            = $rs->fields['TIPO'];
		$termino            = $rs->fields['TERMINO'];
		$remitente          = $rs->fields['REMITENTE'];
		$ubica      		= $rs->fields['UBICACION'];
		$carpeta            = $rs->fields['CARPETA'];
		$dependencia        = $rs->fields['DEPE'];
		$funcio      		= $rs->fields['FUNCIO'];
		if ($dependencia!=999){
			$fvcmto         = $rs->fields['FVCMTO'];
		}
		else{
			$fvcmto         = "Archivado";
		}
		
		

// Ajustado Nuevo Reporte por JUAN CARLOS VILLALBA 18/12/2015 Nemesis
$Dha = new FechaHabil($db);
//$drh =  $Dha->getDiasRestantes($radicadoin,$fvcmto,$tipodoc);


  /* 8/5/18 CARLOS RICAURTE
     SE CAMBIA DIAS CALENDARIO POR DIAS HABILES
  */ 
$fechaHoy = date("Y-m-d");
$drh = $Dha->diasHabiles($fechaHoy,$fvcmto);
$diasRestantes = $Dha->getDiasRestantes($radicadoin,$fvcmto,$tipodoc);


if ($aRADI_DEPE_ACTU==999) { 
	$drh = "Archivado";
}

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
                <td class="listado2" align="center"><?=$diasRestantes?></td>
   
<?php

		$rs->MoveNext();
		
	}
		echo "<p><span class=listado2>Numero de Registros " . $numreginf."</span>";	
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
