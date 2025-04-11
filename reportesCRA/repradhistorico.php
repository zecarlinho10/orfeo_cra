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
$comen=(isset($_POST['comen']))?$_POST['comen']:"";

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

<form action="repradhistorico.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formEnvio" id="formEnvio" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
				REPORTE RADICADOS SEGUN COMENTARIOS EN HIST&Oacute;RICO
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
		<td width="31%" class='titulos2'>COMENTARIO 
		<td class="listado2" height="1">
		<input type=text name=comen id=comen value='<?=$comen?>' size=50 maxlenght="4000" > </td>
			
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


$where=null;


						
			
if (!empty($_POST['tip'])&& ($_POST['tip']=="1")){				
			$where=(!empty($_POST['tip']) && ($_POST['tip'])!="")?" AND r.radi_nume_radi like '%1'":"";}

if(!empty($_POST['tip'])&& ($_POST['tip']=="6")){	
			$where=(!empty($_POST['tip']) && ($_POST['tip'])!="")?" AND r.radi_nume_radi like '%2'":"";}

if (!empty($_POST['tip'])&& ($_POST['tip']=="3")){				
			$where=(!empty($_POST['tip']) && ($_POST['tip'])!="")? " AND r.radi_nume_radi like '%3'":"";}

$where.=(!empty($_POST['depe']) && ($_POST['depe'])!="")?" AND H.DEPE_CODI=".$_POST['depe']:"";
			
$where.=(!empty($_POST['comen']) && trim($_POST['comen'])!="")? " AND UPPER(translate(H.HIST_OBSE, '?', 'aeiouAEIOU')) LIKE UPPER(translate('%".$_POST['comen']."%', '?', 'aeiouAEIOU') ) ":""; 
			
$where.= " AND  TRUNC(H.HIST_FECH) BETWEEN (".$db->conn->DBTimeStamp($fecha_ini).") AND (".$db->conn->DBTimeStamp($fecha_fin).")";
		
 			$order=1;  
			    	
		 
$titulos=array("RADICADO","FECHA RADICACION","ASUNTO","REMITENTE O DESTINO","UBICACION","TIPO");

			$isql = "SELECT DISTINCT RADICADO, FECH_RADI , ASUNTO, RUTA, REMDES, UBICACION, TIPO    FROM (SELECT DISTINCT H.RADI_NUME_RADI AS RADICADO, R.RADI_FECH_RADI AS FECH_RADI,R.RADI_PATH AS RUTA, R.RA_ASUN AS ASUNTO, DO.DEPE_NOMB AS ORIGEN, UO.USUA_NOMB AS UORIGEN, H.HIST_FECH, DD.DEPE_NOMB AS DESTINO, UD.USUA_NOMB AS UDESTINO, H.HIST_OBSE, T.SGD_TTR_DESCRIP AS TRAN, DR.sgd_dir_nomremdes AS REMDES, (MUNI_NOMB||' - '||DPTO_NOMB)as UBICACION, TP.SGD_TPR_CODIGO, TP.SGD_TPR_DESCRIP AS TIPO 
					FROM HIST_EVENTOS H, SGD_DIR_DRECCIONES DR, RADICADO R, 
					DEPENDENCIA DO, DEPENDENCIA DD, USUARIO UO, USUARIO UD, MUNICIPIO M, DEPARTAMENTO DP, SGD_TPR_TPDCUMENTO TP , SGD_TTR_TRANSACCION T
					WHERE DR.MUNI_CODI=M.MUNI_CODI AND DR.DPTO_CODI=M.DPTO_CODI AND DR.DPTO_CODI=DP.DPTO_CODI 
					AND H.RADI_NUME_RADI = R.RADI_NUME_RADI
					AND H.DEPE_CODI=DO.DEPE_CODI AND H.DEPE_CODI_DEST=DD.DEPE_CODI
					AND H.USUA_DOC=UO.USUA_DOC AND H.HIST_DOC_DEST=UD.USUA_DOC
					AND UO.DEPE_CODI=DO.DEPE_CODI AND UD.DEPE_CODI=DD.DEPE_CODI
					AND H.SGD_TTR_CODIGO=T.SGD_TTR_CODIGO AND TP.SGD_TPR_CODIGO=R.TDOC_CODI
					AND DR.RADI_NUME_RADI = R.RADI_NUME_RADI
					{$where}) ORDER BY FECH_RADI DESC
					";
			
		//$db->conn->debug = true;
			$rssql = $db->conn->Execute($isql);

			?>
			
<table align="center" class="table table-bordered table-striped mart-form" width="80%" >

<table border=2 align="center" class="table table-bordered table-striped mart-form" width="80%">
        <tr>
                <td class="titulos2" align="center">RADICADO</td>
                <td class="titulos2" align="center">FECHA RADICACION</td>
                <td class="titulos2" align="center">ASUNTO</td>
				<td class="titulos2" align="center">REMITENTE O DESTINO</td>
				<td class="titulos2" align="center">UBICACION</td>
				<td class="titulos2"  align="center">TIPO</td>
                
        </tr>
<?php

	while(!$rssql->EOF){
		
		$numreginf++;
		
		$radi            	= $rssql->fields['RADICADO'];
		$fechradi     	    = $rssql->fields['FECH_RADI'];
		$asunto				= $rssql->fields['ASUNTO'];
		$dir			    = $rssql->fields['REMDES'];
		$ubic		       	= $rssql->fields['UBICACION'];
		$tipo		       	= $rssql->fields['TIPO'];


	$linkInfGeneral = "<a class='vinculos' href='../verradicado.php?verrad=$radi&".session_name()."=".session_id()."&krd=$krd&carpeta=8&nomcarpeta=Busquedas&tipo_carp=0'>";	

$sql2="select radi_nume_radi, sgd_spub_codigo from radicado where  radi_nume_radi='$radi'";
$rssql2 = $db->conn->Execute($sql2);
$priv= $rssql2->fields['SGD_SPUB_CODIGO'];

	
	?>

        <tr>
        		<?if ($priv==1){?>
				<td class="info" align="center"><?=$radi?></td> <?}else{?>
                <td class="info" align="center"><?=$rads= "<a href=\"{$ruta_raiz}bodega".$rssql->fields['RUTA']."\">".$rssql->fields['RADICADO']."";?></td> <?}?>
				<td class="info" align="center"><?=$linkInfGeneral?><?= $fechradi?></td>
                <td class="info" align="left"><?=$asunto?></td>
                <td class="info" align="left"><?=$dir?></td>
                <td class="info" align="left"><?=$ubic?></td>
                <td class="info" align="left"><?=$tipo?></td>
                
                </td><?
	

		$rssql->MoveNext();
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
