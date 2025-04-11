<?
error_reporting(7);
$ruta_raiz = "../";
session_start();
error_reporting(7);
require_once($ruta_raiz."include/db/ConnectionHandler.php");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

//En caso de no llegar la dependencia recupera la sesi�n
if(empty($_SESSION)) include $ruta_raiz."rec_session.php";


$fechah = date("ymd") . "_" . date("hms");
$fecha_ini=$_POST['fecha_ini'];
$fecha_fin=$_POST['fecha_fin'];  
$dep=(isset($_POST['dep']))?$_POST['dep']:"";
$usua=(isset($_POST['usua']))?$_POST['usua']:"";


?>
<html>
<head>


<title>Reporte Indicador PDA</title>


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

<form action="PDA_curva_usuario.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			Reporte Indicador Gestión Usuarios
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>
    </tr>
			
	<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>DEPENDENCIA</td>
		<td width="69%" height="30" class='listado2' align="left">
		
 <?
$sql= "SELECT DEPE_NOMB, DEPE_CODI AS DEPE_CODI FROM DEPENDENCIA
				 WHERE DEPENDENCIA_ESTADO=1
				order by DEPE_NOMB";
//$db->conn->debug = true;
	$rsDep = $db->conn->Execute($sql);
	if(!$s_DEPE_CODI) $s_DEPE_CODI= 0;
	print $rsDep->GetMenu2("dep","$dep","0:-- Seleccione --", false, 0,"onChange='submit()' class='select'");


	?>
	</tr> 
	<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>USUARIO</td>
		<td width="69%" height="30" class='listado2' align="left">
		
 <?
$sqlus= "SELECT USUA_NOMB, USUA_CODI AS USUA_CODI FROM USUARIO
				 WHERE DEPE_CODI ='$dep' AND USUA_ESTA=1
				order by USUA_CODI";

	$rsus = $db->conn->Execute($sqlus);
	if(!$s_USUA_CODI) $s_USUA_CODI= 0;
	print $rsus->GetMenu2("usua","$usua","0:-- Seleccione --", false, 0," class='select'");

	?>
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



if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar"))
{

	if ($usua==0 or $dep==0){
		
		echo "<script>alert('Hace falta seleccionar dependencia o usuario');</script>";
		
	}else{ 
	
$sqlu="select usua_login from usuario where usua_codi='$usua' and depe_codi='$dep'";
//$db->conn->debug = true;
$rsu = $db->query($sqlu);
$login = $rsu->fields['USUA_LOGIN'];

			require_once($ruta_raiz."include/myPaginador.inc.php");


$where=null;

 			
$where=(!empty($_POST['dep']) && ($_POST['dep'])!="")?"AND a.anex_depe_creador = ".$_POST['dep']:"";		   					
  		
$where.=(!empty($_POST['usua']) && ($_POST['usua'])!="")?"AND a.anex_creador ='$login'":"";	   			
	 		
			$where.= " AND  TRUNC(A.ANEX_RADI_FECH) BETWEEN (".$db->conn->DBTimeStamp($fecha_ini).") AND (".$db->conn->DBTimeStamp($fecha_fin).")";
 			
 			$order=1;      	
		 
			
			$isql = "SELECT DISTINCT r.radi_nume_radi AS ENTRADA, 
    a.ANEX_BORRADO,
    r.radi_fech_radi as FECHAE, 
    a.radi_nume_salida as RSALIDA, 
    a.anex_radi_fech as FECHAS,
    td.sgd_tpr_descrip AS TIPO, 
    r.ra_asun AS ASUNTO, 
    DR.sgd_dir_nomremdes REMITENTE,
    td.sgd_tpr_termino as termino,
    a.anex_creador,extract(month from a.anex_radi_fech) as mes,extract(year from a.anex_radi_fech) as anio,
    (r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) + (select count(*) from SGD_NOH_NOHABILES S where S.NOH_FECHA BETWEEN r.radi_fech_radi AND r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) AND DESCRIP='F')) as FVCMTO,
    sgd_dir_nomremdes, (M.MUNI_NOMB||' - '||DP.DPTO_NOMB)as UBICACION
    
FROM
    radicado r
    LEFT JOIN anexos a  ON a.anex_radi_nume = r.radi_nume_radi
    LEFT JOIN  sgd_tpr_tpdcumento td ON r.tdoc_codi=td.sgd_tpr_codigo
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
    ";
			
			//$db->conn->debug = true;
			$rs = $db->query($isql);
		

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
		$remitente            = $rs->fields['REMITENTE'];
		$ubica      			= $rs->fields['UBICACION'];
		$carpeta            = $rs->fields['CARPETA'];
		$dependencia            = $rs->fields['DEPE'];
		$funcio      			= $rs->fields['FUNCIO'];
		$fvcmto            = $rs->fields['FVCMTO'];			
		$responsable            = $rs->fields['ANEX_CREADOR'];	
		$mes           = $rs->fields['MES'];	
		$anio           = $rs->fields['ANIO'];
		
$sqlsum="SELECT NOH_FECHA,SUMDIAS FROM SGD_NOH_NOHABILES WHERE NOH_FECHA= '$fvcmto'";
			//$db->conn->debug = true;
$rssum = $db->query($sqlsum);
$regfecha            = $rssum->fields['NOH_FECHA'];
$sumdia            = $rssum->fields['SUMDIAS'];

if ($regfecha!=0){
	$fvcmto=date('Y-m-d', strtotime("$fvcmto + $sumdia days"));
}



if ($fvcmto>=$fechas){
	$ctr="CUMPLE";} else $ctr="NO CUMPLE";
	
		if($ctr=="CUMPLE"){
		
		$i=$i+1;
	}

		$rs->MoveNext();
		
	}
	
	
	$sqltot="select anex_creador, mes, anio, count(1) as Total from ($isql) group by anex_creador, mes, anio order by anio, mes ";
	//$db->conn->debug = true;
	$rst = $db->query($sqltot);
	
?>	
	<table border=2 align="center" class="table table-bordered table-striped mart-form" width="80%">
        <tr>
                <td class="titulos2" align="center">USUARIO</td>
                <td class="titulos2" align="center">MES</td>
                <td class="titulos2" align="center">A&Ntilde;O</td>
                <td class="titulos2" align="center">TOTAL ASIG</td>
                <td class="titulos2"  align="center">CUMPLIMIENTO </td>
                <td class="titulos2" align="center">% DESEMPEÑO</td>
                

        </tr>
	
<?	

		while(!$rst->EOF){
		  			
			
		$total            = $rst->fields['TOTAL'];
		$responsablet            = $rst->fields['ANEX_CREADOR'];	
		$mest          = $rst->fields['MES'];	
		$fvcmtot          = $rst->fields['FVCMTO'];
		
		
		

$isqlm = "SELECT DISTINCT r.radi_nume_radi AS ENTRADA, 
    a.ANEX_BORRADO,
    r.radi_fech_radi as FECHAE, 
    a.radi_nume_salida as RSALIDA, 
    a.anex_radi_fech as FECHAS,
    td.sgd_tpr_termino as termino,
    a.anex_creador,extract(month from a.anex_radi_fech) as mes,extract(year from a.anex_radi_fech) as anio,
    (r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) + (select count(*) from SGD_NOH_NOHABILES S where S.NOH_FECHA BETWEEN r.radi_fech_radi AND r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) AND DESCRIP='F')) as FVCMTO
      
FROM
    radicado r
    LEFT JOIN anexos a  ON a.anex_radi_nume = r.radi_nume_radi
    LEFT JOIN  sgd_tpr_tpdcumento td ON r.tdoc_codi=td.sgd_tpr_codigo
WHERE

    substr(r.radi_nume_radi,5,1) <> 9
    AND r.radi_nume_radi like '%2'
    AND (a.ANEX_BORRADO = 'N' or a.ANEX_BORRADO is null)
	and A.RADI_NUME_SALIDA IN (SELECT RADI_NUME_RADI FROM RADICADO WHERE SGD_EANU_CODIGO NOT IN (1,2))
	and r.radi_nrr=0
	and extract(month from a.anex_radi_fech)=$mest
	and a.anex_creador='$responsablet'
	{$where} order by anio, mes
    
    ";
			
			//$db->conn->debug = true;
			$rsm = $db->query($isqlm);
$cum=0;			
	while(!$rsm->EOF){
		
	  									 
	  			
		$fechasm            = $rsm->fields['FECHAS'];
		$fvcmtom            = $rsm->fields['FVCMTO'];			
		$responsablem            = $rsm->fields['ANEX_CREADOR'];	
		$mesm           = $rsm->fields['MES'];
		$aniom          = $rsm->fields['ANIO'];	
		
$sqlsumm="SELECT NOH_FECHA,SUMDIAS FROM SGD_NOH_NOHABILES WHERE NOH_FECHA= '$fvcmtom'";
			//$db->conn->debug = true;
$rssumm = $db->query($sqlsumm);
$regfecham            = $rssumm->fields['NOH_FECHA'];
$sumdiam            = $rssumm->fields['SUMDIAS'];

if ($regfecham!=0){
	$fvcmtom=date('Y-m-d', strtotime("$fvcmtom + $sumdiam days"));
}



if ($fvcmtom>=$fechasm){
	
		$cum=$cum+1;
	}
	$rsm->MoveNext();
	}
	
$des=round($cum/$total*100);	

if($mest==1){$mest="Ene";}elseif($mest==2){$mest="Feb";}elseif($mest==3){$mest="Mar";}elseif($mest==4){$mest="Abr";}
		elseif($mest==5){$mest="May";}elseif($mest==6){$mest="Jun";}elseif($mest==7){$mest="Jul";}elseif($mest==8){$mest="Ago";}
		elseif($mest==9){$mest="Sep";}elseif($mest==10){$mest="Oct";}elseif($mest==11){$mest="Nov";}elseif($mest==12){$mest="Dic";}
		
?>
        <tr>
                <td class="listado2" align="center"><?=$responsablet?></td>
                <td class="listado2" align="center"><?=$mest?></td>
                <td class="listado2" align="center"><?=$aniom?></td>
                <td class="listado2" align="center"><?=$total?></td>
				<td class="listado2" align="center"><?=$cum?></td>
				<td class="listado2" align="center"><?="$des%"?></td>
   
<?php

$t.="$total,";
$long=strlen($t)-1;
$resto= substr($t,0,$long);

$m.="$mest,";
$long=strlen($m)-1;
$resto2= substr($m,0,$long);

$c.="$cum,";
$long=strlen($c)-1;
$resto3= substr($c,0,$long);

$d.="$des,";
$long=strlen($d)-1;
$resto4= substr($d,0,$long);
		$rst->MoveNext();

	}




	if($numreginf > 0) $ind=round($i/$numreginf*100); else $ind=0 ;
	$ind= "$ind %";
	
		echo "<p><span class=listado2>Número de Registros: " . $numreginf."</span>";	
		echo "<p><span class=listado2>Radicados con cumplimiento: " . $i."</span>";	
		echo "<p><span class=resultados>Indicador de Gestión: ". $ind."</span>";	
		
			
?>
    </tr>
</table>

	 		<center>
<br><input type=button class="botones_largo" value="Ver Grafica Totales vs Cumplimiento" onClick='window.open("../open-fc/imagen.php?&resto=<?=$resto?>&resto2=<?=$resto2?>&resto3=<?=$resto3?>&resto4=<?=$resto4?>&responsablet=<?=$responsablet?>" , "Grafica Estadisticas - Orfeo", "top=0,left=0,location=no,status=no, menubar=no,scrollbars=yes, resizable=yes, width=800,height=600");' /> 
<br><input type=button class="botones_largo" value="Ver Grafica Desempeño" onClick='window.open("../open-fc/imagen2.php?&resto2=<?=$resto2?>&resto4=<?=$resto4?>&responsablet=<?=$responsablet?>" , "Grafica Estadisticas - Orfeo", "top=0,left=0,location=no,status=no, menubar=no,scrollbars=yes, resizable=yes, width=800,height=600");' /> 
		</center><?}}


?>
</div></body>
</html>
