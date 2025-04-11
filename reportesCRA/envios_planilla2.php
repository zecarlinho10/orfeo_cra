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


?>
<html>
<head>


<title>GENERACION DE PLANILLA DE ENVIOS </title>


<?include ($ruta_raiz."/htmlheader.inc.php")?>


	<script language="JavaScript" type="text/JavaScript">
/**
* Env�a el formulario de acuerdo a la opci�n seleccionada, que puede ser ver CSV o consultar
*/
	function pasar_datos(fecha)
   {
    <?
	 echo " opener.document.VincDocu.numRadi.value = fecha\n";
	 echo "opener.focus(); window.close();\n";
	?>
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
	<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>TIPO DE SERVICIO</td>
		<td width="69%" height="30" class='listado2' align="left">
		
		 <?
$sql= "SELECT SGD_FENV_DESCRIP, SGD_FENV_CODIGO FROM SGD_FENV_FRMENVIO
				WHERE SGD_FENV_CODIGO IN (101,105,110)
				order by SGD_FENV_DESCRIP";

	$rsserv = $db->conn->Execute($sql);
	if(!$s_SGD_FENV_CODIGO) $s_SGD_FENV_CODIGO= 0;
	print $rsserv->GetMenu2("serv","$serv","0:-- Seleccione --", false, 0," onChange='submit()' class='select'");

	$sqls="select SGD_FENV_DESCRIP, SGD_FENV_CODIGO FROM SGD_FENV_FRMENVIO WHERE SGD_FENV_CODIGO='$serv'";
	$rsserv2 = $db->conn->Execute($sqls);
	$tserv            	= $rsserv2->fields['SGD_FENV_DESCRIP'];

	?>
	</td>
</tr>
	 
	<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>TIPO DE DESTINO</td>
		<td width="69%" height="30" class='listado2' align="left">
		
		 <?
$sql1= "SELECT DESCRIP_DESTINO, COD_DESTINO FROM SGD_DESTINO
				 order by DESCRIP_DESTINO";

	$rsdest = $db->conn->Execute($sql1);
	if(!$s_COD_DESTINO) $s_COD_DESTINO= 0;
	print $rsdest->GetMenu2("dest","$dest","0:-- Seleccione --", false, 0," class='select'");
	$sqls="select DESCRIP_DESTINO, COD_DESTINO FROM SGD_DESTINO WHERE COD_DESTINO='$dest'";
	$rsdest2 = $db->conn->Execute($sqls);
	$tdest   = $rsdest2->fields['DESCRIP_DESTINO'];
	?>
	</td>
</tr>

	<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>TIPO DE ENVIO</td>
		<td width="69%" height="30" class='listado2' align="left">

	<?
	    	$cum_1 = "DOCUMENTO";
			$cum_2 = "PAQUETE";
			
			?>
				<select name=cum value='<?=$cum?>' class='select'>
				<option value='<?=$cum_1?>' > <?=$cum_1?> </option>
				<option value='<?=$cum_2?>' > <?=$cum_2?> </option>
				</select>
			
			</td>
</tr>
<tr>
    <td align="center" width="30%" class="titulos2">Fecha (aaaa/mm/dd) </td>
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
	


if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar")){
	
	
			
			require_once($ruta_raiz."include/myPaginador.inc.php");

			


$where=null;


 			
			$where=(!empty($_POST['serv']) && ($_POST['serv'])!="")?"AND REG.SGD_FENV_CODIGO= ".$_POST['serv']:"";		   					   			
	 		
			$where.= " AND  REG.SGD_RENV_FECH like to_date('".date('d/m/y', strtotime("$fecha_fin"))."', 'dd/mm/yy')";
 		
			if ($dest==10) {
			$where.= " AND  REG.SGD_RENV_MPIO='BOGOTA'";
			}elseif ($dest==11){
			$where.= " AND  REG.SGD_RENV_MPIO <> 'BOGOTA' AND R.ID_PAIS=170";
			}elseif ($dest==12){
			$where.= " AND  REG.SGD_RENV_MPIO <> 'BOGOTA' AND R.ID_PAIS <> 170";
			}
			
			if ($cum==$cum_1) {
			$where.= " AND  REG.SGD_RENV_PESO < 2000";
			}elseif ($cum==$cum_2){
			$where.= " AND  REG.SGD_RENV_PESO >= 2000";
			}
 			
 			
		 	
$titulos=array("ORDEN","RADICADO","DESTINATARIO","DIRECCION","CIUDAD","DEPTO-PAIS","PESO","VALOR ENVIO", "No.SEG 4-72","ESTADO");
			
			$isql = "SELECT ROWNUM AS ID, REG.radi_nume_sal AS SALIDA,R.SGD_DIR_NOMREMDES,R.ID_PAIS, REG.SGD_RENV_VALOR,REG.SGD_RENV_PESO, 
					R.SGD_DIR_DIRECCION, REG.SGD_RENV_FECH, REG.SGD_RENV_MPIO,REG.SGD_RENV_DEPTO
					FROM SGD_RENV_REGENVIO REG
					INNER JOIN SGD_DIR_DRECCIONES R ON R.RADI_NUME_RADI=REG.RADI_NUME_SAL
					WHERE REG.DEPE_CODI NOT IN (900) 
                    {$where}
                    ORDER BY ID";
			$rsEnvio = $db->query($isql);
 			while (!$rsEnvio->EOF){
 			$numreginf++;
 			$rsEnvio->MoveNext();}	
 			
			$isql2 = "SELECT SUM(REG.SGD_RENV_VALOR) AS TOTAL
					FROM SGD_RENV_REGENVIO REG
					INNER JOIN SGD_DIR_DRECCIONES R ON R.RADI_NUME_RADI=REG.RADI_NUME_SAL
					WHERE REG.DEPE_CODI NOT IN (900) 
                    {$where}";
			$rsEnvio2 = $db->query($isql2);
		//	$db->conn->debug = true;
			$valor   = $rsEnvio2->fields["TOTAL"];
		
	    	$noArchivo = "/pdfs/reporteenv_$fecha_hoy.pdf";


		
			$paginador= new myPaginador($db,$isql,1);
			$paginador->modoPintado(true);
			$paginador->setImagenASC($ruta_raiz."iconos/flechaasc.gif");
			$paginador->setImagenDESC($ruta_raiz."iconos/flechadesc.gif");
			$paginador->setFuncionFilas("pintarResultados");
			//$paginador->generarPagina($titulos);
 				
		
error_reporting(0);
	require "../anulacion/class_control_anu.php";
	$btt = new CONTROL_ORFEO($db);
	$campos_align = array("L","L","L","L","L","L","C");
	$campos_tabla = array("ID","SALIDA","SGD_DIR_NOMREMDES","SGD_DIR_DIRECCION","SGD_RENV_MPIO","SGD_RENV_DEPTO","SGD_RENV_PESO","SGD_RENV_VALOR","","");
	$campos_width = array (30,105,410,250,120,110,80,80,80,80);
	$btt->campos_align = $campos_align;
	$btt->campos_tabla = $campos_tabla;
	$btt->campos_vista = $titulos;
	$btt->campos_width = $campos_width;
	
	$btt->tabla_sql($isql);
			
	error_reporting(0);
	
	$html= $btt->tabla_html;
	error_reporting(0);
	define(FPDF_FONTPATH,'../fpdf/font/');
	
	require("../fpdf/planillas.php");
	error_reporting(0);
	$pdf = new PDF("L","mm","Legal");
	$pdf->AddPage();
	$pdf->AliasNbPages();
	$pdf->SetFont('Arial','',8);
	
	$pdf->WriteHTML($html);
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