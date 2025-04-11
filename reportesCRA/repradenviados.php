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

//En caso de no llegar la dependencia recupera la sesi�n
if(empty($_SESSION)) include $ruta_raiz."rec_session.php";

include ("common.php");
$fechah = date("ymd") . "_" . date("hms");
$radicado=(isset($_POST['radicado']))?$_POST['radicado']:"";
$depe_nomb=(isset($_POST['depe_nomb']))?$_POST['depe_nomb']:"";
$fecha_ini=$_POST['fecha_ini'];
$fecha_fin=$_POST['fecha_fin'];  
$dep=(isset($_POST['dep']))?$_POST['dep']:"";


?>
<html>
<head>


<title>Reportes Radicados de Entrada </title>


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

<form action="repradenviados.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formRadi" id="formRadi" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			LISTADO DE RADICADOS ENVIADOS
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>	
	<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>DEPENDENCIA</td>
		<td width="69%" height="30" class='listado2' align="left">
		
		 <?
$sql="SELECT 'Todas las dependencias' as DEPE_NOMB, 0 AS DEPE_CODI FROM DEPENDENCIA
   				 UNION SELECT 'SUBDIRECCION TECNICA CONSOLIDADO' AS DEPE_NOMB, 2 AS DEPE_CODI FROM DEPENDENCIA
				 UNION SELECT 'SUBDIRECCION ADMINISTRATIVA CONSOLIDADO' AS DEPE_NOMB, 3 AS DEPE_CODI FROM DEPENDENCIA
				 UNION SELECT 'OFICINA ASESORA JURÍDICA CONSOLIDADO' AS DEPE_NOMB, 4 AS DEPE_CODI FROM DEPENDENCIA
                 UNION  SELECT DEPE_NOMB, DEPE_CODI AS DEPE_CODI FROM DEPENDENCIA
                 WHERE DEPENDENCIA_ESTADO=1
				 order by DEPE_NOMB DESC";

	$rsDep = $db->conn->Execute($sql);
	if(!$s_DEPE_CODI) $s_DEPE_CODI= 0;
	print $rsDep->GetMenu2("dep","$dep","0:-- Seleccione --", false, 0," class='select'");

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

   	

if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar"))

{
	
	
			
			require_once($ruta_raiz."include/myPaginador.inc.php");

			


$where=null;
	
 			
			$where=(!empty($_POST['dep']) && ($_POST['dep'])!="")?"AND r.radi_depe_radi = ".$_POST['dep']:"";		   if ($dep==2)
{
  $where=" AND r.radi_depe_radi in (401,410,420,430) ";	
}	
if ($dep==3)
{
  $where=" AND r.radi_depe_radi in (301,310,320,341,350) ";	
}	
if ($dep==4)
{
  $where=" AND r.radi_depe_radi in (211,212,213,214,215) ";	
} 								   			
	 		
			$where.= " AND  TRUNC (REG.SGD_RENV_FECH) BETWEEN (".$db->conn->DBTimeStamp($fecha_ini).") AND (".$db->conn->DBTimeStamp($fecha_fin).")";
 		
 			
 			$order=1;  

//CARLOS RICAURTE 19/11/18
//CAMBIO DE CONDICION EN QUERY
//select A.RADI_NUME_radi from sgd_anu_anulados A  where a.RADI_NUME_radi like '$aniom1%'
$aniom1 = $ano_ini-1;
$titulos=array("RADICADO","FECHA RAD.","ASUNTO","DESTINATARIO","UBICACION","DEPENDENCIA","ENVIO","MEDIO","P/NLLA");
			 // MODIFICADO EL 29/11/2013 ing JUAN CARLOS VILLALBA DONDE SACA LOS RADICADOS ANULADOS
			$isql = "SELECT REG.radi_nume_sal AS SALIDA,REG.SGD_RENV_NOMBRE,REG.SGD_RENV_PLANILLA,
					to_char(R.radi_fech_radi,'dd/mm/yyyy hh24:mi:ss') AS FECHAS, R.RA_ASUN,
					B.DEPE_NOMB AS DEPENDENCIA, REG.SGD_RENV_FECH,
					(REG.SGD_RENV_MPIO||' - '||REG.SGD_RENV_DEPTO)as UBICACION, F.SGD_FENV_DESCRIP
					FROM SGD_RENV_REGENVIO REG
					INNER JOIN RADICADO R ON R.RADI_NUME_RADI=REG.RADI_NUME_SAL
					INNER JOIN SGD_FENV_FRMENVIO F ON F.SGD_FENV_CODIGO=REG.SGD_FENV_CODIGO, DEPENDENCIA B
					
					WHERE REG.DEPE_CODI IN (210,321) 
					AND REG.radi_nume_sal LIKE '%1'
                    AND R.radi_depe_radi = B.DEPE_CODI
                    and sgd_RENV_DEPTO IS NOT NULL
					and R.RADI_NUME_RADI not in (select A.RADI_NUME_radi from sgd_anu_anulados A  where a.RADI_NUME_radi like '$aniom1%' OR a.RADI_NUME_radi like '$ano_ini%')
                     {$where}
                    ORDER BY FECHAS";
	    	$noArchivo = "/pdfs/reporteenv_$fecha_hoy.pdf";
			
			//$db->conn->debug = true;

			$paginador= new myPaginador($db,$isql,1);
			$paginador->modoPintado(true);
			$paginador->setImagenASC($ruta_raiz."iconos/flechaasc.gif");
			$paginador->setImagenDESC($ruta_raiz."iconos/flechadesc.gif");
			$paginador->setFuncionFilas("pintarResultados");
			//$paginador->generarPagina($titulos);
			
	
//error_reporting(0);
// revisar esta clase con la que tiene el nuevo orfeo 01/06/2015
	require "../anulacion/class_control_anu_new.php";
	$btt = new CONTROL_ORFEO($db);
	
	$campos_align = array("L","L","L","L","L","L","C");
	$campos_tabla = array("SALIDA","FECHAS","RA_ASUN","SGD_RENV_NOMBRE","UBICACION","DEPENDENCIA","SGD_RENV_FECH","SGD_FENV_DESCRIP","SGD_RENV_PLANILLA");
	$campos_width = array (105,80,200,240,200,70,65,60,45);
	$btt->campos_align = $campos_align;
	$btt->campos_tabla = $campos_tabla;
	$btt->campos_vista = $titulos;
	$btt->campos_width = $campos_width;
	$btt->tabla_sql($isql);
	error_reporting(0);
	$html= $btt->tabla_html;
	 
	error_reporting(0);
	
	define(FPDF_FONTPATH,'../fpdf/font/');
	
	require("../fpdf/html_table_reportes.php");
    error_reporting(0);
	
	$pdf = new PDF("L","mm","A4");
	$pdf->AddPage();
	$encabezado = "<td align=80 height=30><B>REPORTE DE DOCUMENTOS ENVIADOS DESDE $fecha_ini HASTA  $fecha_fin </B></td></tr>
		</table>";
	$pdf->AliasNbPages();
	$pdf->SetFont('Arial','',8);
	$pdf->WriteHTML($encabezado.$html);
	//save and redirect
	$noArchivo = "../bodega".$noArchivo;
	
	if($pdf->Output($noArchivo)){}
		?>
		<center><span class="leidos">Imprimir Reporte <a href='<?=$noArchivo?>'>Aqui</a></center>
		<?
	exit;

}
?>
		
</div></body>
</html>
