<?php
session_start();

	$ruta_raiz = "..";
    if (!$_SESSION['dependencia'])
        header ("Location: $ruta_raiz/cerrar_session.php");

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;
/**
  * Paggina Cuerpo.php que muestra el contenido de las Carpetas
  * Creado en la SSPD en el año 2003
  * 
  * Se anadio compatibilidad con variables globales en Off
  * @autor Jairo Losada 2009-06
  * @licencia GNU/GPL V 3
  */
$krd         = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc    = $_SESSION["usua_doc"];
$codusuario  = $_SESSION["codusuario"];
$depe_codi_territorial = $_SESSION["depe_codi_territorial"];


include_once  "../include/db/ConnectionHandler.php";
$db = new ConnectionHandler($ruta_raiz);

if(!$fecha_ini) $fecha_ini=date("Y-m-d");
if(!$fecha_fin) $fecha_fin=date("Y-m-d");

$fechah = date("ymd") . "_" . date("hms");
$fecha_ini=$_POST['fecha_ini'];
$fecha_fin=$_POST['fecha_fin'];  

?>
<head>
  <?php include_once $ruta_raiz."/htmlheader.inc.php"; ?>
<link rel="stylesheet" href="../estilos/orfeo.css">
</head>
<BODY>
<div id="spiffycalendar" class="text"></div>

<link rel="stylesheet" type="text/css" href="../js/spiffyCal/spiffyCal_v2_1.css">
			<script language="JavaScript" src="../js/spiffyCal/spiffyCal_v2_1.js"></script>
			<script language="JavaScript" type="text/JavaScript">  
				setRutaRaiz('<?php echo $ruta_raiz; ?>')
		 <!--

   var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "formboton", "fecha_ini","btnDate1","<?=$fecha_ini?>",scBTNMODE_CUSTOMBLUE);
   var dateAvailable2 = new ctlSpiffyCalendarBox("dateAvailable2", "formboton", "fecha_fin","btnDate2","<?=$fecha_fin?>",scBTNMODE_CUSTOMBLUE);

--></script>
<P>
<TABLE width="100%" class='borde_tab' cellspacing="5">
  <TR>
    <TD height="30" valign="middle"   class='titulos5' align="center">GENERACION LISTADOS DE DOCUMENTOS DEVUELTOS POR AGENCIA DE CORREO</td>
  </TR>
</table>
<form name='formboton' id='formboton' method='post'  action='generar_estadisticas.php?<?=session_name()."=".session_id()."&krd=$krd&fecha_h=$fechah&fecha_ini=$fecha_ini&fecha_fin=$fecha_fin"?>'>
	<input type='hidden' name='<?=session_name()?>' value='<?=session_id()?>'> 
	<TABLE class="text-center borde_tab" width="550" style="margin: auto;" >
	  <!--DWLayoutTable-->
	  <TR>
	    <TD width="125" height="21"  class='titulos5 text-left' > Fecha desde<br>
		<?
		  echo "($fecha_ini)";
		?>
		</TD>
		<td class="listado2">
		   	<script language="javascript">
				dateAvailable.writeControl();
				dateAvailable.dateFormat="yyyy/MM/dd";
			</script>&nbsp;
		</td>
	  </TR>
	  <TR>
	    <TD  class='titulos5 text-left'> Fecha Hasta<br>
		<?
		  echo "($fecha_fin)";
		?>
		</TD>
	    <td class="listado2">
		   	<script language="javascript">
				dateAvailable2.writeControl();
				dateAvailable2.dateFormat="yyyy/MM/dd";
			</script>&nbsp;
		</td>
	  </TR>
	  <tr>
	    <TD height="26" class='titulos5'>Tipo de Salida</TD>
	    <TD valign="top" align="left" class='listado5'>
		
	    <?php
		$ss_RADI_DEPE_ACTUDisplayValue = "--- TODOS LOS TIPOS ---";
		$valor = 0;
		include "../include/query/reportes/querytipo_envio.php";
		$sqlTS = "select $sqlConcat ,SGD_FENV_CODIGO from SGD_FENV_FRMENVIO 
						order by SGD_FENV_CODIGO";
		
		$rsTs = $db->conn->Execute($sqlTS);
		print $rsTs->GetMenu2("tipo_envio","$tipo_envio",$blank1stItem = "$valor:$ss_RADI_DEPE_ACTUDisplayValue", false, 0," onChange='submit();' class='select'");	
		?>
		</TD>
	  </tr>
	    <tr>
		    <td height="26" colspan="2" valign="top" class='titulos5'> 
		      <center>
				<INPUT TYPE=SUBMIT name=generar_informe Value=' Generar Informe ' class='botones_mediano'></INPUT>
			  </center>
			</td>
		</tr>
  </TABLE>
</form>
  
<?php
if(!$fecha_ini) $fecha_ini = date("Y-m-d");
if($generar_informe){

if ($tipo_envio == 0){
	$where_tipo = "";
}
else{
	$where_tipo = " and a.SGD_FENV_CODIGO = $tipo_envio ";
}
if ($dep_sel == 0)
{
/*
*Seleccionar todas las dependencias de una territorial
*/
    include "$ruta_raiz/include/query/devolucion/querydependencia.php";
	$sqlD = "select $sqlConcat ,depe_codi from dependencia 
	        where depe_codi_territorial = $depe_codi_territorial
			order by depe_codi";
	$rsDep = $db->conn->Execute($sqlD);

	while(!$rsDep->EOF){
		$depcod = $rsDep->fields["DEPE_CODI"];
	    $lista_depcod .= " $depcod,";
	    $rsDep->MoveNext();
	}

	$lista_depcod .= "0";
}
else{
	$lista_depcod = $dep_sel;
}
//Se limita la consulta al substring del numero de radicado de salida 27092005
include "../include/query/reportes/querydepe_selecc.php";
$generar_informe = 'generar_informe';
	error_reporting(7);
	$fecha_inicial=$fecha_ini;
	$fecha_final = $fecha_fin;
	$fecha_ini = $fecha_ini;
	$fecha_fin = $fecha_fin;
	$fecha_ini = mktime(00,00,00,substr($fecha_ini,5,2),substr($fecha_ini,8,2),substr($fecha_ini,0,4));
	$fecha_fin = mktime(23,59,59,substr($fecha_fin,5,2),substr($fecha_fin,8,2),substr($fecha_fin,0,4));
	$guion     = "'-'";
	include "../include/query/reportes/querygenerar_estadisticas.php";
	
	if($tipo_envio=="101" or $tipo_envio=="108" or $tipo_envio=="109" or $tipo_envio=="111")
	{
	 $where_isql .= " and a.sgd_renv_planilla is not null and a.sgd_renv_planilla != '00'
		";
	}
	if($tipo_envio==0)
	{
	 $where_isql .= " and ((a.sgd_fenv_codigo != '101' and a.sgd_fenv_codigo != '108' and a.sgd_fenv_codigo != '109') 
	 				  or (a.sgd_renv_planilla is not null and a.sgd_renv_planilla != '00'))
		";
	}
	/* SE ELIMINA POR SOLICITUD DEL USUARIO
	$order_isql = '  ORDER BY  '.$db->conn->substr.'(a.radi_nume_sal, 5, 3), a.SGD_RENV_FECH DESC,a.SGD_RENV_PLANILLA DESC';
	
	*/
	$order_isql = " ORDER BY a.SGD_DEVE_FECH ASC";
	$query_t = $query . $where_isql . $where_depe . $where_tipo . $order_isql ;
	//echo $query_t . "<br>";
	//echo "where_depe:" . $where_depe . "<br>";
	$ruta_raiz = "..";
	error_reporting(7);
	define('ADODB_FETCH_NUM',1);
	$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
	require "../anulacion/class_control_anu.php"; 
	$db->conn->SetFetchMode(ADODB_FETCH_NUM);
	$btt = new CONTROL_ORFEO($db);
	$campos_align = array("L","C","L","L","L","L","L","L","L","L","L","L","L","L","L");
	$campos_tabla = array("sgd_fenv_descrip"  ,"depe_nomb","radi_nume_sal","sgd_renv_nombre","sgd_renv_dir","sgd_renv_mpio","sgd_renv_depto","sgd_renv_fech","sgd_deve_fech","sgd_deve_desc","firma");
	$campos_vista = array ("Forma Envio"      ,"Dependencia","Radicado","Destinatario","Direccion","Municipio","Departamento","Fecha de envio","Fecha Dev"         ,"Motivo Devolucion","Recibido");
    $campos_width = array (80           ,110          ,100        ,160           ,120        ,70         ,70           ,80            ,70                    ,150                ,110);	
	$btt->campos_align = $campos_align;
	$btt->campos_tabla = $campos_tabla;
	$btt->campos_vista = $campos_vista;
	$btt->campos_width = $campos_width;
	?>
	</center>
	<table><tr><td></td></tr></table>
	<table><tr><td></td></tr></table>	
	<table class="borde_tab" width="100%"><tr class=titulos5><td colspan="2">
	Listado de documentos Devueltos
	</td></tr>
	<tr><td width="15%" class="titulos5">Fecha Inicial </td><td width="85%" class='listado5'><?=$fecha_inicial .  "  00:00:00" ?> </td></tr>
	<tr><td  class="titulos5">Fecha Final   </td><td class='listado5'><?=$fecha_final . "  23:59:59" ?> </td></tr>
	<tr><td  class="titulos5">Fecha Generado </td><td class='listado5'><? echo date("Ymd - H:i:s"); ?></td></tr>
	</table>
	<table><tr><td></td></tr></table>
	<table><tr><td></td></tr></table>	
	<?php
	$btt->tabla_sql($query_t);
	error_reporting(7);
	
	$html= $btt->tabla_html;
	error_reporting(7);
	define(FPDF_FONTPATH,'../fpdf/font/');
	require("../fpdf/html_table.php");
	error_reporting(7);
	$pdf = new PDF("L","mm","A4");
	$pdf->AddPage();
	$pdf->SetFont('Arial','',7);
	$entidad = $db->entidad;
	$encabezado = "<table border=1>
			<tr>
			<td width=1120 height=30>$entidad</td>
			</tr>
			<tr>
			<td width=1120 height=30>REPORTE DE DEVOLUCION DE DOCUMENTOS ENTRE $fecha_ini   00:00:00  y $fecha_fin   23:59:59 </td>
			</tr>
			</table>";
	$fin = "<table border=1 bgcolor='#FFFFFF'>
			<tr>
			<td width=1120 height=60 bgcolor='#CCCCCC'>FUNCIONARIO CORRESPONDENCIA</td>
			</tr>
			<tr>
			<td width=1120 height=60></td>
			</tr>
		</table>";
	
    $pdf->WriteHTML($encabezado . $html . $fin);
	$arpdf_tmp = "../bodega/pdfs/planillas/dev/$dependencia_$krd_". date("Ymd_hms") . "_dev.pdf";
	$pdf->Output($arpdf_tmp);
/*
	 * Modificacion acceso a documentos
	 * @author Liliana Gomez Velasquez
	 * @since 11 noviembre 2009
	 */
	echo "<B><a class=\"vinculos\" href=\"#\" onclick=\"abrirArchivo('". $arpdf_tmp."?time=".time() ."');\"> Abrir Archivo Pdf</a><br>";	
}
?>
