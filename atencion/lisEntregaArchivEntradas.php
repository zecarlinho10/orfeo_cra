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
 * @author   Jully Quicano
 * @mail    yquicano@cra.gov.co    
 * @version     1.0
 */
$ruta_raiz = "../";
session_start();
error_reporting(0);

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
//require_once($ruta_raiz."include/db/ConnectionHandler.php");

//Incluir el Nuevo Adob-paginacion para que se pueda exportar xls y Doc

//include_once($ruta_raiz."adodb/adodb-paginacion.inc.php");

//Eliminado 2016/01/28 JUAN CARLOS VILLALBA include_once('../adodb/adodb-paginacion.inc.php');



if (!$db)	$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
//$db->conn->debug=true;
//En caso de no llegar la dependencia recupera la sesi�n
if(empty($_SESSION)) include $ruta_raiz."rec_session.php";

include ("common.php");
$fechah = date("ymd") . "_" . date("hms");
$radicado=(isset($_POST['radicado']))?$_POST['radicado']:"";
$depe_nomb=(isset($_POST['depe_nomb']))?$_POST['depe_nomb']:"";
$fecha_ini=$_REQUEST['fecha_ini'];
$fecha_fin=$_REQUEST['fecha_fin'];
?>

<html>
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



 <body><div class="table-responsive">

<div id="spiffycalendar" class="text"></div>

<?
$params = "&krd=$krd";
?>

<form action="lisEntregaArchivEntradas.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formRadi" id="formRadi" >

<table class="table table-bordered table-striped mart-form">
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			LISTADO DE RADICADOS ENTRANTES
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
                 UNION SELECT 'OFICINA ASESORA JURÍDICA CONSOLIDADO' AS DEPE_NOMB, 4 AS DEPE_CODI FROM DEPENDENCIA
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


		</center>
		
		</td>
	</tr>
</table>
</form>

<!--
<form action="ficheroExcel.php" method="post" target="_blank" rel="noopener noreferrer" id="FormularioExportacion">
<p>Exportar a Excel  <img src="export_to_excel.gif" class="botonExcel" /></p>
<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
</form>
-->



<?php 

if(empty($_POST['Consultar'])){
if ($_GET['pagina']>=1){
$_POST['Consultar']="Consultar";
}
}

if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar")){
			
//			require_once($ruta_raiz."include/myPaginador.inc.php");


$where=null;

$encabezado = "dep=$dep&fecha_ini=$fecha_ini&fecha_fin=$fecha_fin";
$linkPagina = "$PHP_SELF?$encabezado&orderTipo=$orderTipo&orderNo=";

		
$where=(!empty($_POST['dep']) && ($_POST['dep'])!="")?"AND a.radi_depe_radi = ".$_POST['dep']:""; 

if ($dep==2)
{
  $where=" AND a.radi_depe_radi in (401,410,420,430) ";	
}	
if ($dep==3)
{
  $where=" AND a.radi_depe_radi in (301,310,320,341,350) ";	
}	  
if ($dep==4)
{
  $where=" AND a.radi_depe_radi in (211,212,213,214,215) ";	
} 		
			$where.= " AND TRUNC (A.RADI_FECH_RADI) BETWEEN (".$db->conn->DBTimeStamp($fecha_ini).") AND (".$db->conn->DBTimeStamp($fecha_fin).")";
 		
 			
 			$order=1;  
			    	
		 
//$titulos=array("RADICADO","REMITENTE","UBICACION","ASUNTO","FECHA RADICACION","No.HOJAS Y ANEXOS", "DEPENDENCIA","TIPO_DOC","MEDIO");


			
			$isql = "SELECT distinct A.RADI_NUME_RADI AS ENTRADA, A.RA_ASUN AS ASUNTO, to_char(A.radi_fech_radi,'yyyy/mm/dd hh24:mi:ss') AS FECHAS, 
			         A.RADI_NUME_HOJA AS PAGINAS,A.RADI_DESC_ANEX AS ANEXO, B.DEPE_NOMB AS DEPENDENCIA, 
					 C.SGD_TPR_DESCRIP AS TIPO,
					 sgd_dir_nomremdes  AS REMITENTE, 
					 (MUNI_NOMB||' - '||DPTO_NOMB)as UBICACION, 
					 case when radi_depe_actu = 999 then 'ARCHIVADO' else 'ACTIVO' end as ARCHIVADO,
					 MR.MREC_DESC AS MEDIO
					 FROM RADICADO A
					 INNER JOIN DEPENDENCIA B ON A.RADI_DEPE_RADI = B.DEPE_CODI
					 INNER JOIN SGD_TPR_TPDCUMENTO C ON C.SGD_TPR_CODIGO = A.TDOC_CODI
					 INNER JOIN SGD_DIR_DRECCIONES DR ON DR.RADI_NUME_RADI = A.RADI_NUME_RADI
					 INNER JOIN MEDIO_RECEPCION MR ON MR.MREC_CODI= A.MREC_CODI,
     				 MUNICIPIO M, DEPARTAMENTO DP
     				 WHERE A.RADI_NUME_RADI LIKE '%2' 
					 AND A.RADI_DEPE_RADI NOT IN(900,905,910)			    
					 AND DR.MUNI_CODI=M.MUNI_CODI 
					 AND DR.DPTO_CODI=M.DPTO_CODI
					 AND DR.DPTO_CODI=DP.DPTO_CODI
					 AND DR.SGD_DIR_TIPO = 1
					 {$where}
					 ORDER BY A.RADI_NUME_RADI";
					 
					 
		 
					 

//$db->conn->debug = true;

//RENDERIZO LA CONSULTA Y LA PAGINO
/**
Para que pueda funcionar correctamente este require
debe existir la variable $isql a la cual se va a paginar.
Opcionar declarar $TAMANO_PAGINA para establecer el numero de registros por pagina.
**/

 //include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once($ruta_raiz."adodb/adodb-pagination-cb.php");
//fin de renderizar y paginar.

}
?>

  <?php
$xsql=serialize($isql);
$_SESSION['xsql']=$xsql;
echo "<a style='border:0px' href='$ruta_raiz/reportes/adodb-doc.inc.php?' target='_blank'><img src='$ruta_raiz/imagenes/compfile.png' width='40' heigth='40' border='0' ></a>"; 
echo "<a href='$ruta_raiz/reportes/adodb-xls.inc.php?' target='_blank'><img src='$ruta_raiz/imagenes/spreadsheet.png' width='40' heigth='40' border='0'></a>";
?> 	
			
</div>
</div></body>
</html>
