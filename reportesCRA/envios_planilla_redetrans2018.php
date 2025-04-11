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
 *Genera planillas de envio con formato de empresa postal 472
 * @author  Jully Quicano
 * @mail    yquicano@cra.gov.co
  * @version     1.0
 */
$ruta_raiz = "../";
session_start();
error_reporting(0);
require_once($ruta_raiz."include/db/ConnectionHandler.php");
require_once("$ruta_raiz/include/pdf/class.ezpdf.inc");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

//En caso de no llegar la dependencia recupera la sesi?n
if(empty($_SESSION)) include $ruta_raiz."rec_session.php";

include ("common.php");
$fechah = date("ymd") . "_" . date("hms");
$radicado=(isset($_POST['radicado']))?$_POST['radicado']:"";
$depe_nomb=(isset($_POST['depe_nomb']))?$_POST['depe_nomb']:"";
$serv=(isset($_POST['serv']))?$_POST['serv']:"";
$fecha_fin=(isset($_POST['fecha_fin']))?$_POST['fecha_fin']:"";


?>
<html>
<head>


<title>GENERACION DE PLANILLA DE ENVIOS </title>


<?include ($ruta_raiz."/htmlheader.inc.php")?>


	<script language="JavaScript" type="text/JavaScript">
/**
* Env?a el formulario de acuerdo a la opci?n seleccionada, que puede ser ver CSV o consultar
*/
	function pasar_datos(fecha)
   {
    <?
	 echo " opener.document.VincDocu.numRadi.value = fecha\n";
	 echo "opener.focus(); window.close();\n";
	?>
}


</script>

<link rel="stylesheet" type="text/css" onclick="submit" href="../js/spiffyCal/spiffyCal_v2_1.css" >
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
  var dateAvailable2 = new ctlSpiffyCalendarBox("dateAvailable2", "formRadi", "fecha_fin","btnDate2","<?=$fecha_fin?>",scBTNMODE_CUSTOMBLUE);

--></script>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>

 <body><div class="table-responsive">
<div id="spiffycalendar" class="text"></div>

<?
$params = session_name()."=".session_id()."&krd=$krd";
?>

<form action="envios_planilla.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formRadi" id="formRadi" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			GENERACION DE PLANILLA DE ENVIOS
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>

</tr>
<tr>
    <td align="center" width="30%" class="titulos2">FECHA (AAAA/MM/DD) </td>
    <td class="listado2">
	<script language="javascript">
	dateAvailable2.writeControl();
	dateAvailable2.dateFormat="yyyy/MM/dd";
	</script>&nbsp;</td>
  </tr>

	<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>TIPO DE SERVICIO</td>
		<td width="69%" height="30" class='listado2' align="left">

		 <?
$sql= "SELECT SGD_FENV_DESCRIP, SGD_FENV_CODIGO FROM SGD_FENV_FRMENVIO
				WHERE SGD_FENV_CODIGO IN (101,105,110)
				order by SGD_FENV_DESCRIP";

	$rsserv = $db->conn->Execute($sql);
	if(!$s_SGD_FENV_CODIGO) $s_SGD_FENV_CODIGO= 0;
	print $rsserv->GetMenu2("serv","$serv","0:-- Seleccione --", false, 0," class='select' onChange='submit()'");

	$sqls="select SGD_FENV_DESCRIP, SGD_FENV_CODIGO FROM SGD_FENV_FRMENVIO WHERE SGD_FENV_CODIGO='$serv'";
	$rsserv2 = $db->conn->Execute($sqls);
	$tserv            	= $rsserv2->fields['SGD_FENV_DESCRIP'];
?></td>


	<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>No.PLANILLA</td>
		<td width="69%" height="30" class='listado2' align="left">

		 <?
	$fecha_mes = substr($fecha_busq,0,7) ;
	$sqlChar = $db->conn->SQLDate("Y/m","SGD_RENV_FECH");
	$query = "SELECT max(to_number(sgd_renv_planilla)) as PLANILLA FROM sgd_renv_regenvio
				WHERE DEPE_CODI=$dependencia AND  $sqlChar = '$fecha_mes'
					AND ".$db->conn->length."(sgd_renv_planilla) > 0
					 AND sgd_fenv_codigo = $serv";
 	//$db->conn->debug = true;
	$rsbp = $db->conn->Execute($query);
	$planilla = $rsbp->fields["PLANILLA"];



$sql3= "SELECT DISTINCT G.SGD_RENV_PLANILLA, G.SGD_RENV_FECH, G.PE_DESDE, G.PE_HASTA, r.muni_codi,  m.muni_codi,
  m.locales FROM SGD_RENV_REGENVIO G
		INNER JOIN SGD_DIR_DRECCIONES R ON R.RADI_NUME_RADI=G.RADI_NUME_SAL, MUNICIPIO M
		WHERE G.SGD_RENV_FECH like to_date('".date('d/m/y', strtotime("$fecha_fin"))."', 'dd/mm/yy')
		AND G.SGD_FENV_CODIGO= ".$_POST['serv']." AND  m.muni_codi=R.muni_codi AND  m.DPTO_codi=R.DPTO_codi
		{$where}";
//$db->conn->debug = true;
	$rspla = $db->conn->Execute($sql3);
	$nop   = $rspla->fields['SGD_RENV_PLANILLA'];
	$desde   = $rspla->fields['PE_DESDE'];
	$hasta   = $rspla->fields['PE_HASTA'];
	$local = $rspla->fields["LOCALES"];
	?>
	<input type="text" name="nop1" id="nop1" value='<?=$nop?>' class='tex_area' size=15 maxlength="11" >
	 <?

	echo "<br><span class=etexto>&Uacute;ltima planilla generada : <B> $planilla </B>";?>
	</td>
</tr>





	<tr align="center">
		<td height="30" colspan="4" class='listado2'>


		<center>
			<input name="Generar" type="submit"  class="botones" id="envia22"   value="Generar">&nbsp;&nbsp;

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
				$salida=$fila['SALIDA'];
 				break;
 			case 1:
				$salida=$fila['SGD_RENV_NOMBRE'];
				break;
 			case 2:
				$salida=$fila['SGD_RENV_DIR'];
				break;
			case 3:
				$salida=$fila['SGD_RENV_MPIO'];
				break;
			case 4:
				$salida=$fila['SGD_RENV_DEPTO'];
				break;
		    case 5:
				$salida=$fila['SGD_RENV_PESO'];
				break;
			case 6:
				$salida=$fila['SGD_RENV_VALOR'];
				break;
			default:$salida="ERROR";
		}
		return $salida;
	}




if(!empty($_POST['Generar'])&& ($_POST['Generar']=="Generar")){



			require_once($ruta_raiz."include/myPaginador.inc.php");




$where=null;



			$where=(!empty($_POST['serv']) && ($_POST['serv'])!="")?"AND REG.SGD_FENV_CODIGO= ".$_POST['serv']:"";

			$where.= " AND  REG.SGD_RENV_FECH like to_date('".date('d/m/y', strtotime("$fecha_fin"))."', 'dd/mm/yy')";



$titulos=array("CIUDAD","DEPTO","DESTINATARIO","TIPODOCUMENO","DOCUMENTO","UNIDAD","KILOS","VOLUMEN","PRODUCTO","V-DECLARADO","DIRECCION","TELEFONO","CONTENIDO","COSTO","RADICADO","RADICADO");
			$tpdoc="C";
			$isql = "SELECT SALIDA,SGD_RENV_NOMBRE,ID_PAIS, SGD_RENV_VALOR,SGD_RENV_PESO,'C' TIPODOC,'0' DOCUME,'1' VOLUMEN,'SOBRES' PRODUC,
					SGD_RENV_DIR, SGD_RENV_FECH, SGD_RENV_MPIO,SGD_RENV_DEPTO FROM (SELECT REG.radi_nume_sal AS SALIDA,REG.SGD_RENV_NOMBRE,REG.SGD_RENV_DIR,R.ID_PAIS, REG.SGD_RENV_VALOR,REG.SGD_RENV_PESO, 
					R.SGD_DIR_DIRECCION, REG.SGD_RENV_FECH, REG.SGD_RENV_MPIO,REG.SGD_RENV_DEPTO, M.LOCALES, M.MUNI_CODI, R.MUNI_CODI
					FROM SGD_RENV_REGENVIO REG
					INNER JOIN SGD_DIR_DRECCIONES R ON R.RADI_NUME_RADI=REG.RADI_NUME_SAL, MUNICIPIO M
					WHERE REG.DEPE_CODI not in (900,905,999) AND REG.SGD_RENV_NOMBRE NOT IN ('Varios') AND R.SGD_DIR_TIPO IN (1,3)
					AND m.muni_codi=R.muni_codi AND M.DPTO_CODI=R.DPTO_CODI
					AND REG.SGD_RENV_PESO IS NOT NULL
                    {$where}
                    ORDER BY REG.SGD_RENV_FECH)";

						//$db->conn->debug = true;
			$rsEnvio = $db->query($isql);
 			while (!$rsEnvio->EOF){
 			$rad   = $rsEnvio->fields["SALIDA"]	;
 			$numreginf++;
 			if ($nop==""){
 			$sql = "UPDATE SGD_RENV_REGENVIO SET SGD_RENV_PLANILLA = ".$_POST['nop1']."
					WHERE radi_nume_sal='$rad'";
			//$db->conn->debug = true;
			$rs = $db->query($sql);
 			}
 			$nopla=$_POST['nop1'];

 			$rsEnvio->MoveNext();}



			$isql2 = "SELECT SUM(REG.SGD_RENV_VALOR) AS TOTAL
					FROM SGD_RENV_REGENVIO REG
					INNER JOIN SGD_DIR_DRECCIONES R ON R.RADI_NUME_RADI=REG.RADI_NUME_SAL, MUNICIPIO M
					WHERE REG.DEPE_CODI NOT IN (900) AND m.muni_codi=R.muni_codi AND M.DPTO_CODI=R.DPTO_CODI
                    {$where}";
			$rsEnvio2 = $db->query($isql2);
			//$db->conn->debug = true;
			$valor   = $rsEnvio2->fields["TOTAL"];


			$paginador= new myPaginador($db,$isql,1);
			$paginador->modoPintado(true);
			$paginador->setImagenASC($ruta_raiz."iconos/flechaasc.gif");
			$paginador->setImagenDESC($ruta_raiz."iconos/flechadesc.gif");
			$paginador->setFuncionFilas("pintarResultados");
			//$paginador->generarPagina($titulos);


error_reporting(0);
	require "../anulacion/class_control_anu.php";
	$btt = new CONTROL_ORFEO($db);
	
	$campos_align = array("L","L","L","L","L","L","L","L","L","L","L","L","L","L","L","L");
	$campos_tabla = array("SGD_RENV_MPIO","SGD_RENV_DEPTO","SGD_RENV_NOMBRE","TIPODOC","DOCUME","VOLUMEN","VOLUMEN","DOCUME","PRODUC","DOCUME","SGD_RENV_DIR","TELEFONO","CONTENIDO","COSTO","SALIDA","SALIDA");
	$campos_width = array (250,120,105,410,110,80);
	$btt->campos_align = $campos_align;
	$btt->campos_tabla = $campos_tabla;
	$btt->campos_vista = $titulos;
	$btt->campos_width = $campos_width;

	$btt->tabla_sql($isql);


	$html= $btt->tabla_html;

}
?>

</div></body>
</html>