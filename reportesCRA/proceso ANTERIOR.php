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

//En caso de no llegar la dependencia recupera la sesi?n
if(empty($_SESSION)) include $ruta_raiz."rec_session.php";

include ("common.php");
$fechah = date("ymd") . "_" . time("hms");
$radicado=(isset($_POST['radicado']))?$_POST['radicado']:"";
$depe_nomb=(isset($_POST['depe_nomb']))?$_POST['depe_nomb']:"";

?>
<html>
<head>


<title>Reportes De Transaciones Con Radicados</title>


<link rel="stylesheet" href="<?php echo $ruta_raiz?>estilos/orfeo.css">


	<script language="JavaScript" type="text/JavaScript">
/**
* Env?a el formulario de acuerdo a la opci?n seleccionada, que puede ser ver CSV o consultar
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

<body>
<div id="spiffycalendar" class="text"></div>

<?
$params = session_name()."=".session_id()."&krd=$krd";
?>

<form action="proceso TEMP.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formEnvio" id="formEnvio" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='borde_tab'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			REPORTE RADICADOS POR USUARIO DEL AREA DE CORRESPONDENCIA
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>

<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>DEPENDENCIA</td>
		<td width="69%" height="30" class='listado2' align="left">

		 <?
		$sql ="SELECT DEPE_NOMB, DEPE_CODI FROM DEPENDENCIA
				 WHERE DEPENDENCIA_ESTADO=1 and depe_codi=321
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
//$db->conn->debug = true;
	$rsusua = $db->conn->Execute($sql);
	include "$ruta_raiz/include/tx/ComentarioTx.php";
	if(!$s_USUA_CODI) $s_USUA_CODI= 0;
	print $rsusua->GetMenu2("usua", $usua, "0:-- Seleccione --", false,"","onChange='submit()' class='select'" );

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

$sql="SELECT USUA_LOGIN,USUA_DOC FROM USUARIO WHERE USUA_CODI='$usua' AND DEPE_CODI='$depe'";
$rs = $db->conn->Execute($sql);
$login	= $rs->fields['USUA_DOC'];

$where="AND h.USUA_DOC = '$login' AND h.USUA_CODI='$usua' AND h.DEPE_CODI ='$depe' AND h.DEPE_CODI ='$depe'";
$where.= " AND H.HIST_FECH BETWEEN (".$db->conn->DBTimeStamp($fecha_ini).") AND (".$db->conn->DBTimeStamp($fecha_fin).")";

 			$order=1;

$isql = "select v1.RADICADO RADICADO1, TO_CHAR(v1.FECHA, 'dd/mm/yyyy,HH:MI:SS AM') FECHA1_FORMAT, v1.DESCRIPCION DESCRIPCION1, v1.CODIGO CODIGO1, v1.OPERACION OPERACION1,
v2.RADICADO RADICADO2, TO_CHAR(v2.FECHA, 'dd/mm/yyyy,HH:MI:SS AM') FECHA2_FORMAT, v2.DESCRIPCION DESCRIPCION2, v2.CODIGO CODIGO2, v2.OPERACION OPERACION2,
to_timestamp(v2.fecha), to_timestamp(v1.fecha),
extract(day from (to_timestamp(TO_CHAR(v2.FECHA, 'dd/mm/yyyy,HH:MI:SS AM'), 'dd/mm/yyyy,HH:MI:SS AM') - to_timestamp(TO_CHAR(v1.FECHA, 'dd/mm/yyyy,HH:MI:SS AM'), 'dd/mm/yyyy,HH:MI:SS AM'))) || ' dias, ' || 
extract(hour from (to_timestamp(TO_CHAR(v2.FECHA, 'dd/mm/yyyy,HH:MI:SS AM'), 'dd/mm/yyyy,HH:MI:SS AM') - to_timestamp(TO_CHAR(v1.FECHA, 'dd/mm/yyyy,HH:MI:SS AM'), 'dd/mm/yyyy,HH:MI:SS AM'))) || ' hora(s), ' ||
extract(minute from (to_timestamp(TO_CHAR(v2.FECHA, 'dd/mm/yyyy,HH:MI:SS AM'), 'dd/mm/yyyy,HH:MI:SS AM') - to_timestamp(TO_CHAR(v1.FECHA, 'dd/mm/yyyy,HH:MI:SS AM'), 'dd/mm/yyyy,HH:MI:SS AM'))) || ' minuto(s), ' ||
extract(second from (to_timestamp(TO_CHAR(v2.FECHA, 'dd/mm/yyyy,HH:MI:SS AM'), 'dd/mm/yyyy,HH:MI:SS AM') - to_timestamp(TO_CHAR(v1.FECHA, 'dd/mm/yyyy,HH:MI:SS AM'), 'dd/mm/yyyy,HH:MI:SS AM'))) || ' segundo(s).' DEMORA
from
(select h.radi_nume_radi AS RADICADO,max(h.hist_fech) AS FECHA,h.hist_obse AS DESCRIPCION,h.sgd_ttr_codigo AS CODIGO,t.sgd_ttr_descrip AS OPERACION from fldoc.hist_eventos h, fldoc.sgd_ttr_transaccion t
where h.sgd_ttr_codigo in (2)
{$where}
AND H.SGD_TTR_CODIGO=T.SGD_TTR_CODIGO
group by h.radi_nume_radi,h.hist_obse,h.sgd_ttr_codigo,t.sgd_ttr_descrip
order by 2) v1,
(select h.radi_nume_radi AS RADICADO,max(h.hist_fech) AS FECHA,h.hist_obse AS DESCRIPCION,h.sgd_ttr_codigo AS CODIGO,t.sgd_ttr_descrip AS OPERACION from fldoc.hist_eventos h,fldoc.sgd_ttr_transaccion t
where h.sgd_ttr_codigo in (22)
{$where}
AND H.SGD_TTR_CODIGO=T.SGD_TTR_CODIGO
group by h.radi_nume_radi,h.hist_obse,h.sgd_ttr_codigo,t.sgd_ttr_descrip
order by 2) v2
WHERE v1.RADICADO = v2.RADICADO
";

//	$db->conn->debug = true;
//$rs = $db->conn->Execute($isql);
//$fecha1	= $rs->fields['v1.fecha1'];
//$fecha2	= $rs->fields['v2.fecha2'];
//$fecha = $fecha1->diff($fecha2);
//$DEMORA=printf('%d años, %d meses, %d días, %d horas, %d minutos', $fecha->y, $fecha->m, $fecha->d, $fecha->h, $fecha->i);

//echo .$FECHA;
// imprime: 2 años, 4 meses, 2 días, 1 horas, 17 minutos

}

//echo .$isql;

$titulos=array("RADICADO", "FECHA", "DESCRIPCION", "CODIGO" ,"OPERACION", "RADICADO", "FECHA", "DESCRIPCION", "CODIGO", "OPERACION","DEMORA");


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
	$campos_tabla = array("RADICADO1", "FECHA1_FORMAT", "DESCRIPCION1", "CODIGO1" ,"OPERACION1", "RADICADO2", "FECHA2_FORMAT", "DESCRIPCION2", "CODIGO2", "OPERACION2","DEMORA");
	$btt->campos_align = $campos_align;
	$btt->campos_tabla = $campos_tabla;
	$btt->campos_vista = $titulos;
	$btt->campos_width = $campos_width;
	$btt->tabla_sql($isql);

?>
</body>
</html>