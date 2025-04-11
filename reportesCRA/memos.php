<?
 /* Programa que despliega Radicados de Salidas en un periodo determinado
 * @author  MARIO Manotas Duran
 * @mail    mmanotas@cra.gov.co
 * @author  modify by Mario Manotas Duran
 * @mail   mamanotasduran@gmail.com    
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


<title>Reportes Radicados Memorando </title>


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

<form action="memos.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formRadi" id="formRadi" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			LISTADO DE RADICADOS MEMORANDO
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>	
	<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>DEPENDENCIA</td>
		<td width="69%" height="30" class='listado2' align="left">
		
		 <?
$sql = "SELECT 'Todas las dependencias' as DEPE_NOMB, 0 AS DEPE_CODI FROM DEPENDENCIA
   				 UNION SELECT 'SUBDIRECCION TECNICA CONSOLIDADO' AS DEPE_NOMB, 2 AS DEPE_CODI FROM DEPENDENCIA
				 UNION SELECT 'SUBDIRECCION ADMINISTRATIVA CONSOLIDADO' AS DEPE_NOMB, 3 AS DEPE_CODI FROM DEPENDENCIA
                 UNION  SELECT DEPE_NOMB, DEPE_CODI AS DEPE_CODI FROM DEPENDENCIA
                 WHERE DEPE_CODI NOT IN (900,905,999,910,1,321,210)
				 order by DEPE_NOMB DESC";
	$rsDep = $db->conn->Execute($sql);
	if(!$s_DEPE_CODI) $s_DEPE_CODI= 0;
	print $rsDep->GetMenu2("dep","$dep",false, false, 0," class='select'");

	?>
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
			<input name="Imprimir" type="submit"  class="botones" id="envia23"   value="Imprimir">&nbsp;&nbsp;
		</center>
		
		</td>
	</tr>
</table>
</form>
<?php 

$year=date ("Y");    	
   
if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar")){
	

$where=null;
	
 			
			$where=(!empty($_POST['dep']) && ($_POST['dep'])!="")?"AND X.radi_depe_radi = ".$_POST['dep']:"";		if ($dep==2)
{
  $where=" AND X.radi_depe_radi in (401,410,420,430) ";	
}	
if ($dep==3)
{
  $where=" AND X.radi_depe_radi in (301,310,320,341,350) ";	
}	  		   					   			
 		
			$where.= " AND TRUNC (X.RADI_FECH_RADI) BETWEEN (".$db->conn->DBTimeStamp($fecha_ini).") AND (".$db->conn->DBTimeStamp($fecha_fin).")";
 		
 			
 			$order=1;  
			    	
		 			
			$isql = "select X.radi_nume_radi AS MEMO, SUBSTR(X.RADI_NUME_RADI,8,6) AS ORDEN, X.RADI_FECH_RADI AS FECHAS,B.DEPE_NOMB AS DEPENDENCIA,
					 sgd_dir_nomremdes,U.USUA_NOMB, X.SGD_EANU_CODIGO, X.RADI_DEPE_ACTU, X.RA_ASUN
					 from RADICADO X
					INNER JOIN SGD_DIR_DRECCIONES DR ON DR.RADI_NUME_RADI = X.RADI_NUME_RADI,
					USUARIO U,
    				dependencia B  
					where X.radi_nume_radi LIKE'$year%3' 
					AND X.RADI_DEPE_RADI NOT IN (905,900,910) AND (U.DEPE_CODI= B.DEPE_CODI) 
					AND X.RADI_USUA_RADI=U.USUA_CODI
					AND X.RADI_DEPE_RADI=U.DEPE_CODI
					{$where}
			    	ORDER BY ORDEN ASC";
			
			//$db->conn->debug = true;
$rs = $db->query($isql);
						
?>	
<table border=2 align="center" class="table table-bordered table-striped mart-form" width="80%">
        <tr>
                <td class="titulos2" align="center">RADICADO</td>
                <td class="titulos2"  align="center">FECHA </td>
                <td class="titulos2" align="center">ASUNTO</td>
                <td class="titulos2" align="center">CREADOR</td>
                <td class="titulos2" align="center">DEPENDENCIA</td>
                <td class="titulos2" align="center">DESTINO</td>
                <td class="titulos2"  align="center">ESTADO</td>
                

        </tr>
<?php

	while(!$rs->EOF){
		
	  				$numreginf++;
					 
	  			
			
		$memo            = $rs->fields['MEMO'];
		$fecha      			= $rs->fields['FECHAS'];
		$creador			= $rs->fields['USUA_NOMB'];
		$dependencia            = $rs->fields['DEPENDENCIA'];
		$destino            = $rs->fields['SGD_DIR_NOMREMDES'];
		$anulado			 = $rs->fields['SGD_EANU_CODIGO'];
		$actual			 = $rs->fields['RADI_DEPE_ACTU'];		 
		$asunto			 = $rs->fields['RA_ASUN'];



?>
        <tr>
                <td class="listado2" align="center"><?=$memo?></td>
                <td class="listado2" align="center"><?=$fecha?></td>
                <td class="listado2" align="center"><?=$asunto?></td>
                <td class="listado2" align="center"><?=$creador?></td>
                <td class="listado2" align="center"><?=$dependencia?></td>
                <td class="listado2" align="center"><?=$destino?></td>
        
<?   

		if ($anulado!=""){
			$estado="ANULADO";?>
			<td class="anulado" align="center"><?=$estado?></td>
	<?}	elseif ($anulado=="" && $actual==999){  $estado="ARCHIVADO";
   ?> <td class="listado2" align="center"><?=$estado?></td>
   <?  }
	elseif ( $actual!=999) { $estado="EN PROCESO";
   ?>             
                <td class="listado2" align="center"><?=$estado?></td>
   
<?php
}
		$rs->MoveNext();
		
	}

		echo "<p><span class=listado2>Número de Registros: " . $numreginf."</span>";	
		
}

?>
</div></body>
</html>