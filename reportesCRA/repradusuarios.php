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
$esta=(isset($_POST['esta']))?$_POST['esta']:"";
$usua=(isset($_POST['usua']))?$_POST['usua']:"";
$tipo=(isset($_POST['tipo']))?$_POST['tipo']:"";

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
<div id="spiffycalendar" class="text" style="z-index: 100;"></div>


<?
$params = session_name()."=".session_id()."&krd=$krd";
?>

<form action="repradusuarios.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formEnvio" id="formEnvio" >
																			
<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped smart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			REPORTE RADICADOS POR USUARIO
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>	
    
<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>DEPENDENCIA</td>
		<td width="69%" height="30" class='listado2' align="left">
		
		 <?
		$sql ="SELECT DEPE_NOMB, DEPE_CODI FROM DEPENDENCIA
				order by DEPE_NOMB";

	$rsdepe = $db->conn->Execute($sql);
	if(!$s_USUA_CODI) $s_USUA_CODI= 0;
	print $rsdepe->GetMenu2("depe", $depe, "0:-- Seleccione --", false,"","onChange='submit()' class='select'" );


	?>
	</td>
</tr>

<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>ESTADO DE USUARIO</td>
	<td width="69%" height="30" class='listado2' align="left">
	
		 <?
$sql = "SELECT 'ACTIVO' as ESTA_DESC, 1 AS ESTA_CODI FROM ESTADO
                 UNION  SELECT 'INACTIVO' as ESTA_DESC, 0 AS ESTA_CODI FROM ESTADO
				 order by ESTA_DESC";

	$rsesta = $db->conn->Execute($sql);
	print $rsesta->GetMenu2("esta","$esta","2:-- Seleccione --", false, 0,"onChange='submit()' class='select'");

	?>
	</td>
</tr>

    
	 <tr align="center" colspan="2">
		<td width="31%" class='titulos2'>USUARIO</td>
	<td width="69%" height="30" class='listado2' align="left">
	<?
	
	$sql = "SELECT distinct U.USUA_NOMB, U.USUA_CODI, D.DEPE_CODI FROM USUARIO U, DEPENDENCIA D
				WHERE D.DEPE_CODI= '$depe'
				and U.DEPE_CODI = '$depe'
				and U.USUA_ESTA='$esta'
				order by USUA_NOMB";
	$rsusua = $db->conn->Execute($sql);
	include "$ruta_raiz/include/tx/ComentarioTx.php";
	if(!$s_USUA_CODI) $s_USUA_CODI= 0;
	print $rsusua->GetMenu2("usua", $usua, "0:-- Seleccione --", false,"","onChange='submit()' class='select'" );

	?>
	</td>
     </tr>

<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>RADICADOS</td>
		<td width="69%" height="30" class='listado2' align="left">
		
		 <?
$sql = "SELECT 'CREADOS' as TIPO_DESC, 1 AS TIPO_CODI FROM ESTADO
                 UNION  SELECT 'PENDIENTES' as TIPO_DESC, 2 AS TIPO_CODI FROM ESTADO
				 order by TIPO_DESC";

	$rstipo = $db->conn->Execute($sql);
	print $rstipo->GetMenu2("tipo","$tipo","0:-- Seleccione --", false, 0,"onChange='submit()' class='select'");

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

		</center>
		
		</td>
	</tr>
</table>
</form>
<?php 

   	
    	
if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar")){
	
	
			
			require_once($ruta_raiz."include/myPaginador.inc.php");
			

			


$where=null;

if(!empty($_POST['tipo'])&& ($_POST['tipo']=="1")){
	$sql="SELECT USUA_LOGIN FROM USUARIO WHERE USUA_CODI='$usua' AND DEPE_CODI='$depe'";
	 $rs = $db->conn->Execute($sql);
	 $login	= $rs->fields['USUA_LOGIN'];
			$where=(!empty($_POST['tipo']) && ($_POST['tipo'])!="")?"AND X.ANEX_CREADOR = '$login' AND U.USUA_CODI='$usua' AND U.DEPE_CODI ='$depe' AND D.DEPE_CODI ='$depe' ":"";

$where.= " AND  TRUNC(R.RADI_FECH_RADI) BETWEEN (".$db->conn->DBTimeStamp($fecha_ini).") AND (".$db->conn->DBTimeStamp($fecha_fin).")";
		
 			$order=1;  
			
			$isql = "SELECT DISTINCT R.RADI_NUME_RADI AS RADICADO, R.RA_ASUN AS ASUNTO, 
					to_char(R.RADI_FECH_RADI,'yyyy/mm/dd hh24:mi:ss') AS FECH_RADI, 
					D.DEPE_NOMB AS DEPENDENCIA, U.USUA_NOMB AS USUARIO, U.USUA_CODI as usuario_cod,D.DEPE_CODI as dependencia,
					DR.sgd_dir_nomremdes, (MUNI_NOMB||' - '||DPTO_NOMB)as UBICACION,
					R.RADI_DEPE_actu,r.radi_usua_actu, R.CARP_CODI, TP.SGD_TPR_CODIGO, TP.SGD_TPR_DESCRIP, R.RADI_NUME_DERI
					FROM RADICADO R
     				INNER JOIN SGD_DIR_DRECCIONES DR ON DR.RADI_NUME_RADI = R.RADI_NUME_RADI
     				INNER JOIN ANEXOS X ON R.RADI_NUME_RADI=X.RADI_NUME_SALIDA,
     				MUNICIPIO M, DEPARTAMENTO DP, USUARIO U, DEPENDENCIA D, SGD_TPR_TPDCUMENTO TP
					WHERE DR.MUNI_CODI=M.MUNI_CODI 
					AND DR.DPTO_CODI=M.DPTO_CODI
					AND DR.DPTO_CODI=DP.DPTO_CODI
					AND TP.SGD_TPR_CODIGO=R.TDOC_CODI
					AND DR.SGD_DIR_TIPO=1		
					{$where}";
			
		//	$db->conn->debug = true;
}

if (!empty($_POST['tipo'])&& ($_POST['tipo']=="2")){				
			$where=(!empty($_POST['tipo']) && ($_POST['tipo'])!="")?"AND r.radi_usua_actu = ".$_POST['usua']." AND r.radi_depe_actu = ".$_POST['depe']." AND U.DEPE_CODI =r.radi_depe_actu AND U.USUA_CODI=r.radi_usua_actu AND  D.DEPE_CODI =r.radi_depe_actu ":"";

$where.= " AND  TRUNC(R.RADI_FECH_RADI) BETWEEN (".$db->conn->DBTimeStamp($fecha_ini).") AND (".$db->conn->DBTimeStamp($fecha_fin).")";
		
 			$order=1;  
			
			$isql = "SELECT DISTINCT R.RADI_NUME_RADI AS RADICADO, R.RA_ASUN AS ASUNTO, 
					to_char(R.RADI_FECH_RADI,'yyyy/mm/dd hh24:mi:ss') AS FECH_RADI, 
					D.DEPE_NOMB AS DEPENDENCIA, U.USUA_NOMB AS USUARIO, U.USUA_CODI,D.DEPE_CODI,
					DR.sgd_dir_nomremdes, (MUNI_NOMB||' - '||DPTO_NOMB)as UBICACION,
					R.RADI_DEPE_actu,r.radi_usua_actu, R.CARP_CODI, TP.SGD_TPR_CODIGO, TP.SGD_TPR_DESCRIP, R.RADI_NUME_DERI
					FROM RADICADO R
     				INNER JOIN SGD_DIR_DRECCIONES DR ON DR.RADI_NUME_RADI = R.RADI_NUME_RADI,
     				MUNICIPIO M, DEPARTAMENTO DP, USUARIO U, DEPENDENCIA D, SGD_TPR_TPDCUMENTO TP
					WHERE DR.MUNI_CODI=M.MUNI_CODI 
					AND DR.DPTO_CODI=M.DPTO_CODI
					AND DR.DPTO_CODI=DP.DPTO_CODI
					AND TP.SGD_TPR_CODIGO=R.TDOC_CODI
					AND DR.SGD_DIR_TIPO=1		
					{$where}";
			
			//$db->conn->debug = true;
}
			    	
		 
$titulos=array("RADICADO","ASUNTO","FECHA RADICACION","ASOCIADO","REMITENTE O DESTINO","UBICACION","TIPO");

		
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
			<?php
$xsql=serialize($isql);
$_SESSION['xsql']=$xsql;
echo "<a style='border:0px' href='$ruta_raiz/reportes/adodb-doc.inc.php' target='_blank'><img src='$ruta_raiz/imagenes/compfile.png' width='40' heigth='40' border='0' ></a>"; 
echo "<a href='$ruta_raiz/reportes/adodb-xls.inc.php' target='_blank'><img src='$ruta_raiz/imagenes/spreadsheet.png' width='40' heigth='40' border='0'></a>";
?> 
</div></body>
</html>
