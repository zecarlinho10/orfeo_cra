<?php
/*********************************************************************************
 *       Filename: reporte.php
 *
 *       PHP 4.0 build 15 julio de 2005
 *********************************************************************************/

//-------------------------------
// reporte CustomIncludes begin

$krdOld = $krd;
session_start();
error_reporting(0);
$ruta_raiz = "..";
$s_hora_inicial = 0;
if(!$krd) $krd=$krdOld;
if(!isset($_SESSION['dependencia']))	include "$ruta_raiz/rec_session.php";
  $ss_desde_RADI_FECH_RADIDisplayValue = "";
    $cadena="";
  for ($hace=60;$hace>=0;$hace--){
    $timestamp = mktime (0,0,0,date("m"),date("d")-$hace,date("Y"));
    $mes = Date('d/m/Y',$timestamp);
    $valormes = Date("M d Y", $timestamp);
    $cadena.=$mes.";". $valormes .";";
  }
  //$flds_desde_RADI_FECH_RADI = strip(get_param("s_desde_RADI_FECH_RADI"));
    $flds_RADI_DEPE_RADI = $s_RADI_DEPE_RADI;
    $flds_desde_RADI_FECH_RADI = $s_desde_RADI_FECH_RADI;
	$flds_HORA_INICIAL = $s_hora_inicial;
    include "$ruta_raiz/config.php";
	include_once "$ruta_raiz/include/db/ConnectionHandler.php";
    $db = new ConnectionHandler("$ruta_raiz");
    if (!defined('ADODB_FETCH_ASSOC'))define('ADODB_FETCH_ASSOC',2);
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;


include ("../busqueda/common.php");

// reporte CustomIncludes end
//-------------------------------

//===============================
// Save Page and File Name available into variables
//-------------------------------
$sFileName = "repProcesoRadEntrada.php";
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");

//===============================

// reporte Show begin

//===============================
// Display page

//===============================
// HTML Page layout
//-------------------------------
?><html>
<head>
<title>Reporte Planilla</title>
<meta name="GENERATOR" content="YesSoftware CodeCharge v.2.0.5 build 11/30/2001">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="../busqueda/Site.css" type="text/css">
</head>
<body class="PageBODY">
<?
  global $db;
  global $styles;
  
  global $sForm;
  $sFormTitle = "Reporte proceso radicados de entrada";
  $sActionFileName = "repProcesoRadEntrada.php";
  $ss_desde_RADI_FECH_RADIDisplayValue = "";
  $ss_hora_inicialDisplayValue = "";
  $ss_hora_finalDisplayValue = "";
  global $FormRADICADO_Sorting;
  global $FormRADICADO_Sorted;
//-------------------------------
// Search Open Event begin
  $cadena="";
  for ($hace=730;$hace>=0;$hace--){
    $timestamp = mktime (0,0,0,date("m"),date("d")-$hace,date("Y"));
    $mes = Date('Y/m/d',$timestamp);
    $valormes = Date("M d Y", $timestamp);
    $cadena.=$mes.";". $valormes .";";
  }
  $cadena2="";
  for ($hace=0;$hace<=24;$hace++){
    $cadena2.= $hace .";" .$hace . ";";
  }
  $cadena3="";
  for ($hace=0;$hace<=24;$hace++){
    $cadena3.= $hace .";" .$hace;
	if ($hace!= 24)
		$cadena3.= ";";
  }
// Search Open Event end
//-------------------------------
//-------------------------------
// Set variables with search parameters
//-------------------------------
  $flds_SELECCION = strip(get_param("s_SELECCION"));
  $flds_RADI_DEPE_ACTU = $s_RADI_DEPE_ACTU;
  $flds_RADI_DEPE_RADI = strip(get_param("s_RADI_DEPE_RADI"));
  $flds_desde_RADI_FECH_RADI = strip(get_param("s_desde_RADI_FECH_RADI"));
  $flds_hasta_RADI_FECH_RADI = strip(get_param("s_hasta_RADI_FECH_RADI"));  
  $flds_hora_inicial = strip(get_param("s_hora_inicial"));
  $flds_hora_final = strip(get_param("s_hora_final"));


//-------------------------------
	$iSQLdep = "select depe_codi,depe_nomb from DEPENDENCIA ORDER BY DEPE_CODI";
// 	$db->query($iSQLdep);
	$rs = $db->conn->Execute($iSQLdep);

$encabezado = "&krd=$krd&dep_sel=$dep_sel&s_SELECCION=$s_SELECCION&s_desde_RADI_FECH_RADI=$s_desde_RADI_FECH_RADI&s_hasta_RADI_FECH_RADI=$s_hasta_RADI_FECH_RADI&s_hora_inicial=$s_hora_inicial&s_hora_final=$s_hora_final&s_RADI_DEPE_ACTU=$s_RADI_DEPE_ACTU&FormRADICADO_Sorting=$FormRADICADO_Sorting&sFileName=$sFileName&form_sorting=$form_sorting&FormRADICADO_Sorted=$FormRADICADO_Sorted&generar=$generar";

?>
<form name='frmCrear' action='repProcesoRadEntrada.php?<?=session_name()."=".session_id()."&$encabezado"?>' method="post">
     
  <table class="FormTABLE" width="722">
    <tr>
      <td class="FormHeaderTD" colspan="7"><a name="Search"><font class="FormHeaderFONT"><?=$sFormTitle?></font></a></td>
    </tr>
     <tr>
      <td class="FieldCaptionTD" width="154" align="right" height="25"><font class="FieldCaptionFONT">SELECCION</font></td>
      <td class="DataTD" width="594" height="25"> 
		<select class="DataFONT" name="s_SELECCION">
		  <?
    		$ss_SELECCIONDisplayValue = "Todo";
    		$l= strlen($flds_SELECCION);
		    if ($l==0){
			    echo "<option value=\"\" SELECTED>" . $ss_SELECCIONDisplayValue . "</option>";
    		}else{
      			echo "<option value=\"\">" . $ss_SELECCIONDisplayValue . "</option>";
    		}

				    if($l>0 && 2 == $flds_SELECCION) $option="<option SELECTED value=\"2\">Solo archivados</option>";
				    else $option="<option value=\"2\">Solo archivados</option>";
      				echo $option;
				    if($l>0 && 3 == $flds_SELECCION) $option="<option SELECTED value=\"3\">Solo NO archivados</option>";
				    else $option="<option value=\"3\">Solo NO archivados</option>";
      				echo $option;

	  ?></select>	  
	</td>




      <td class="FieldCaptionTD" width="154" align="right" height="25"><font class="FieldCaptionFONT">DEPENDENCIA ACTUAL</font></td>
      <td  class="DataFONT" width="594" height="25"> 
	  <?
	$sql = " SELECT 'Todas las dependencias' as DEPE_NOMB, 0 AS DEPE_CODI FROM DEPENDENCIA
UNION  SELECT DEPE_NOMB, DEPE_CODI AS DEPE_CODI FROM DEPENDENCIA
					order by DEPE_NOMB";
//	$sql = "select UBIC_DEPE_RADI, UBIC_DEPE_RADI from UBICACION_FISICA order by 2";
	$rsDep = $db->conn->Execute($sql);
	if(!$s_RADI_DEPE_ACTU) $s_RADI_DEPE_ACTU= 0;
	print $rsDep->GetMenu2("s_RADI_DEPE_ACTU","$s_RADI_DEPE_ACTU",false, false, 0," class='select'");
	?>
	</td>
     </tr>
  </table>
	   <table class="FormTABLE" width="722">
          <tr>
      <td class="FieldCaptionTD" width="154" align="right" height="25"><font class="FieldCaptionFONT">FECHA RADICACION DESDE</font></td>
      <td class="DataTD" width="194" height="25"> 
	  
        <select name="s_desde_RADI_FECH_RADI">
<?
    echo "<option value=\"\">" . $ss_desde_RADI_FECH_RADIDisplayValue . "</option>";
    $LOV = explode(";", "$cadena;");
  
    if(sizeof($LOV)%2 != 0) 
      $array_length = sizeof($LOV) - 1;
    else
      $array_length = sizeof($LOV);
    
    for($i = 0; $i < $array_length; $i = $i + 2)
    {
      if($LOV[$i] == $flds_desde_RADI_FECH_RADI) 
        $option="<option SELECTED value=\"" . $LOV[$i] . "\">" . $LOV[$i + 1];
      else
        $option="<option value=\"" . $LOV[$i] . "\">" . $LOV[$i + 1];

      echo $option;
    }
?></select></td>
      <td class="FieldCaptionTD" width="254" align="right" height="25"><font class="FieldCaptionFONT">FECHA RADICACION HASTA</font></td>

      <td class="DataTD" width="394" height="25"> 
        <select name="s_hasta_RADI_FECH_RADI">
<?
    echo "<option value=\"\">" . $ss_hasta_RADI_FECH_RADIDisplayValue . "</option>";
    $LOV = explode(";", "$cadena;");
  
    if(sizeof($LOV)%2 != 0) 
      $array_length = sizeof($LOV) - 1;
    else
      $array_length = sizeof($LOV);
    
    for($i = 0; $i < $array_length; $i = $i + 2)
    {
      if($LOV[$i] == $flds_hasta_RADI_FECH_RADI) 
        $option="<option SELECTED value=\"" . $LOV[$i] . "\">" . $LOV[$i + 1];
      else
        $option="<option value=\"" . $LOV[$i] . "\">" . $LOV[$i + 1];

      echo $option;
    }
?>
</select></td>



      <td colspan="3"> 
        <input type="submit" name=generar value="Generar">
      </td>
    </tr>
   </table>
<?
  if($generar1) 
  {
  echo "ordenar por usuario actual";
 $ps_desde_RADI_FECH_RADI = get_param("s_desde_RADI_FECH_RADI");
  $ps_hasta_RADI_FECH_RADI = get_param("s_hasta_RADI_FECH_RADI");
  if ($ps_desde_RADI_FECH_RADI &&  $ps_hasta_RADI_FECH_RADI) 	RADICADO_show(); 
  else 		echo "  <center><b>Por favor seleccione un rango de fechas</center></b>";
  }

  if($generar)
  {
  $ps_desde_RADI_FECH_RADI = get_param("s_desde_RADI_FECH_RADI");
  $ps_hasta_RADI_FECH_RADI = get_param("s_hasta_RADI_FECH_RADI");
  if ($ps_desde_RADI_FECH_RADI &&  $ps_hasta_RADI_FECH_RADI) 	RADICADO_show(); 
  else 		echo "  <center><b>Por favor seleccione un rango de fechas</center></b>";	
  }
?>
</form>

 <table>
  <tr>
   <td valign="top">
   </td>
  </tr>
 </table>
<?php



//===============================
// Display Grid Form
//-------------------------------
function RADICADO_show()
{
//-------------------------------
// Initialize variables  
//-------------------------------
  
  global $db;
  global $sRADICADOErr;
  global $sFileName;
  global $styles;
  global $s_RADI_DEPE_ACTU;
  global $FormRADICADO_Sorting;
  global $form_sorting;
  global $FormRADICADO_Sorted;
  global $s_desde_RADI_FECH_RADI;
  global $s_hasta_RADI_FECH_RADI;
  global $encabezado;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $sFormTitle = "Reporte";
  $HasParam = false;
  $bReq = true;
  $iRecordsPerPage = 2000;
  $iCounter = 0;
  $iSort = "";
  $iSorted = "";
  $sDirection = "";
  $sSortParams = "";
  $iTmpI = 0;
  $iTmpJ = 0;
  $sCountSQL = "";

/*  $transit_params = "";
  $form_params = "s_RADI_DEPE_RADI=" . tourl(get_param("s_RADI_DEPE_RADI")) . 
          "&s_RADI_DEPE_ACTU=" . tourl(get_param("s_RADI_DEPE_ACTU")) . 
          "&s_SELECCION=" . tourl(get_param("s_SELECCION")) . 
          "&s_desde_RADI_FECH_RADI=" . tourl(get_param("s_desde_RADI_FECH_RADI")) . 
		  "&s_hasta_RADI_FECH_RADI=" . tourl(get_param("s_hasta_RADI_FECH_RADI")) . "&";
*/
//-------------------------------
// Build ORDER BY statement
//-------------------------------
  $sOrder = " order by entrada Asc";
  $iSort = $FormRADICADO_Sorting;

  $iSorted = $FormRADICADO_Sorted;
  if(!$iSort)
  {
    $form_sorting = "";
  }
  else
  {
    if($iSort == $iSorted)
    {
      $form_sorting = "";
      $sDirection = " DESC";
      $sSortParams = "FormRADICADO_Sorting=" . $iSort . "&FormRADICADO_Sorted=" . $iSort . "&";
    }
    else
    {
      $form_sorting = $iSort;
      $sDirection = " ASC";
      $sSortParams = "FormRADICADO_Sorting=" . $iSort . "&FormRADICADO_Sorted=" . "&";
    }
    if ($iSort == 1)  $sOrder = " order by entrada"   . $sDirection;
    if ($iSort == 2)  $sOrder = " order by fechae"    . $sDirection;
    if ($iSort == 3)  $sOrder = " order by rsalida"   . $sDirection;
    if ($iSort == 4)  $sOrder = " order by fechas"    . $sDirection;
    if ($iSort == 5)  $sOrder = " order by tipo"      . $sDirection;
    if ($iSort == 6)  $sOrder = " order by asunto"    . $sDirection;
    if ($iSort == 7)  $sOrder = " order by depe_actu" . $sDirection;
    if ($iSort == 8)  $sOrder = " order by nomb_actu" . $sDirection;		
    if ($iSort == 9)  $sOrder = " order by usant"     . $sDirection;
    if ($iSort == 10) $sOrder = " order by diasr"     . $sDirection;		
    if ($iSort == 11) $sOrder = " order by FECH_IMPR"    . $sDirection;		
    if ($iSort == 12) $sOrder = " order by FECH_ENVIO"    . $sDirection;		

  }

//-------------------------------
// HTML column headers
//-------------------------------
?>
     
<table class="FormTABLE" width="715">

  <tr align="center"> 
  <td width="142" height="25" class="ColumnTD"><a href='repProcesoRadEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=1&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Radicado</font>	</a> </td>
  
 	<td width="142" height="25" class="ColumnTD"><a href='repProcesoRadEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=2&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Fecha Rad Entrada</font>	</a> </td>

	<td width="142" height="25" class="ColumnTD"><a href='repProcesoRadEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=3&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Rad Salida</font>	</a> </td>

	<td width="142" height="25" class="ColumnTD"><a href='repProcesoRadEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=4&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Fecha Rad Salida</font>	</a> </td>

	<td width="142" height="25" class="ColumnTD"><a href='repProcesoRadEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=5&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Tipo Doc</font>	</a> </td>

	<td width="142" height="25" class="ColumnTD"><a href='repProcesoRadEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=6&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Asunto</font>	</a> </td>

	<td width="142" height="25" class="ColumnTD"><a href='repProcesoRadEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=7&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Dependencia Actual</font>	</a> </td>
	
	<td width="142" height="25" class="ColumnTD"><a href='repProcesoRadEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=8&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Usuario Actual</font>	</a> </td>
	<td width="142" height="25" class="ColumnTD"><a href='repProcesoRadEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=9&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Usuario Anterior</font>	</a> </td>
	<td width="142" height="25" class="ColumnTD"><a href='repProcesoRadEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=10&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Dias Restantes</font>	</a> </td>
	<td width="142" height="25" class="ColumnTD"><a href='repProcesoRadEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=11&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Fec Impreso</font>	</a> </td>
	<td width="142" height="25" class="ColumnTD"><a href='repProcesoRadEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=12&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Fec Env�o</font>	</a> </td>
  </tr>
<?
  
//-------------------------------
// Build WHERE statement
//-------------------------------
  $ps_desde_RADI_FECH_RADI = get_param("s_desde_RADI_FECH_RADI");
  $ps_hasta_RADI_FECH_RADI = get_param("s_hasta_RADI_FECH_RADI");
  $ps_hora_inicial = get_param("s_hora_inicial");
  $ps_hora_final = get_param("s_hora_final");

  if(strlen($ps_desde_RADI_FECH_RADI))
  {
 
    $desde = $ps_desde_RADI_FECH_RADI . " ". "00:00:00";
    $hasta = $ps_hasta_RADI_FECH_RADI . " ". "23:59:59";
    $HasParam = true;
	//$desde = "2005/07/27". " ". "00:00:00";
	//$hasta = "2005/10/31". " ". "23:59:59";
    $sWhereFec =  " and R.RADI_FECH_RADI >= to_date('" .$desde . "','yyyy/mm/dd HH24:MI:ss')";
    $sWhereFec .= " and ";
    $sWhereFec = $sWhereFec . " R.RADI_FECH_RADI <= to_date('" . $hasta . "','yyyy/mm/dd HH24:MI:ss')";
	
  }

/* Seleccion Todo - Solo archivados - Solo NO archivados */
  $ps_SELECCION = get_param("s_SELECCION");
  if(strlen($ps_SELECCION))
  {
  	if ($ps_SELECCION == 1) $sSelec = "";
  	if ($ps_SELECCION == 2) $sSelec = " r.radi_depe_actu  = 999 ";	
  	if ($ps_SELECCION == 3) $sSelec = " r.radi_depe_actu  != 999 ";		
  }
/*FIN  /* Seleccion Todo - Solo archivados - Solo NO archivados */

  $ps_RADI_DEPE_RADI = $s_RADI_DEPE_ACTU;
  if(is_number($ps_RADI_DEPE_RADI) && strlen($ps_RADI_DEPE_RADI))
    $ps_RADI_DEPE_RADI = tosql($ps_RADI_DEPE_RADI, "Number");
  else 
    $ps_RADI_DEPE_RADI = "";

  if($ps_RADI_DEPE_RADI > 0)
  {

    $HasParam = true;//se busca en el radicado donde sea like 'yyyyDEP%'
    $sWhere = $sWhere . " r.radi_depe_actu = $ps_RADI_DEPE_RADI";

//    $sWhere = $sWhere . "R.RADI_NUME_RADI LIKE '" . substr($ps_desde_RADI_FECH_RADI,6,4) . 
//	$ps_RADI_DEPE_RADI ."%'" ;
    
  }
  else
  {
//    $bReq = false;
  }
  
$sSQL_1 = "
select r.radi_nume_radi AS ENTRADA, 
	to_char(r.radi_fech_radi,'yyyy/mm/dd hh24:mi:ss') as FECHAE, 
	to_char(a.radi_nume_salida) as rsalida, 
	to_char(a.anex_radi_fech,'yyyy/mm/dd hh24:mi:ss') as FECHAS,
	td.sgd_tpr_descrip AS TIPO, 
	r.ra_asun AS ASUNTO, 
	d1.depe_nomb AS depe_actu, 
	u1.usua_nomb AS nomb_actu, 
	r.radi_usu_ante as usant, 
	round(((r.radi_fech_radi+(td.sgd_tpr_termino * 7/5))-sysdate)) as diasr, 
	r.radi_depe_actu, 
    to_char(a.sgd_fech_impres,'yyyy/mm/dd hh24:mi:ss') as FECH_IMPR,
    to_char(a.anex_fech_envio,'yyyy/mm/dd hh24:mi:ss') as fech_envio,
	a.anex_estado as estado
from radicado r, anexos a, sgd_tpr_tpdcumento td, usuario u1, dependencia d1
where r.radi_nume_radi = a.anex_radi_nume 
	AND a.radi_nume_salida > 0
	AND r.radi_nume_radi like '%2' 
	AND r.tdoc_codi=td.sgd_tpr_codigo 
	AND r.codi_nivel <=5 
	AND r.radi_usua_actu=u1.usua_codi 
	AND r.radi_depe_actu=u1.depe_codi 
	AND u1.depe_codi=d1.depe_codi 
	AND substr(r.radi_nume_radi,5,1) != 9 
	AND a.radi_nume_salida NOT IN(SELECT radi_nume_radi FROM SGD_ANU_ANULADOS AN)
";
$sSQL_2 = "
	SELECT 
		r.radi_nume_radi AS ENTRADA, 
		to_char(r.radi_fech_radi,'yyyy/mm/dd hh24:mi:ss') as FECHAE, 
		'' as rsalida,
		to_char('','yyyy/mm/dd hh24:mi:ss') as FECHAS, 
		td.sgd_tpr_descrip AS TIPO, 
		r.ra_asun AS ASUNTO, 
		d1.depe_nomb AS depe_actu, 
		u1.usua_nomb AS nomb_actu, 
		r.radi_usu_ante as usant,
		round(((r.radi_fech_radi+(td.sgd_tpr_termino * 7/5))-sysdate)) as diasr, 
		r.radi_depe_actu,
		to_char('','yyyy/mm/dd hh24:mi:ss') as FECH_IMPR,
        to_char('','yyyy/mm/dd hh24:mi:ss') as fech_envio,
	    0 as estado
	FROM 
		radicado r, 
		sgd_tpr_tpdcumento td, 
		usuario u1, 
		dependencia d1
	WHERE  r.tdoc_codi=td.sgd_tpr_codigo 
		AND r.codi_nivel <=5 
		AND r.radi_usua_actu=u1.usua_codi AND r.radi_depe_actu=u1.depe_codi 
		AND u1.depe_codi=d1.depe_codi 
		AND substr(r.radi_nume_radi,14,1) = 2
		AND substr(r.radi_nume_radi,5,1) != 9 
		AND (R.RADI_NUME_RADI NOT IN(SELECT ANEX_RADI_NUME FROM ANEXOS) OR R.RADI_NUME_RADI IN(SELECT ANEX_RADI_NUME FROM ANEXOS WHERE ANEX_BORRADO = 'S'))
		";
$sSQL_3 = "
select r.radi_nume_radi AS ENTRADA, 
	to_char(r.radi_fech_radi,'yyyy/mm/dd hh24:mi:ss') as FECHAE, 
	' ANEXO ' as rsalida, 
	to_char(a.anex_radi_fech,'yyyy/mm/dd hh24:mi:ss') as FECHAS,
	td.sgd_tpr_descrip AS TIPO, 
	r.ra_asun AS ASUNTO, 
	d1.depe_nomb AS depe_actu, 
	u1.usua_nomb AS nomb_actu, 
	r.radi_usu_ante as usant, 
	round(((r.radi_fech_radi+(td.sgd_tpr_termino * 7/5))-sysdate)) as diasr, 
	r.radi_depe_actu, 
    to_char(a.sgd_fech_impres,'yyyy/mm/dd hh24:mi:ss') as FECH_IMPR,
    to_char(a.anex_fech_envio,'yyyy/mm/dd hh24:mi:ss') as fech_envio,
	a.anex_estado as estado
from radicado r, anexos a, sgd_tpr_tpdcumento td, usuario u1, dependencia d1
where r.radi_nume_radi = a.anex_radi_nume 
	AND a.radi_nume_salida is null AND a.anex_borrado = 'N'
	AND r.radi_nume_radi like '%2' 
	AND r.tdoc_codi=td.sgd_tpr_codigo 
	AND r.codi_nivel <=5 
	AND r.radi_usua_actu=u1.usua_codi 
	AND r.radi_depe_actu=u1.depe_codi 
	AND u1.depe_codi=d1.depe_codi 
	AND substr(r.radi_nume_radi,5,1) != 9
";
$sSQL_4 = "
select r.radi_nume_radi AS ENTRADA, 
	to_char(r.radi_fech_radi,'yyyy/mm/dd hh24:mi:ss') as FECHAE, 
	concat(to_char(a.radi_nume_salida),' ANULADO ')  as rsalida, 
	to_char(a.anex_radi_fech,'yyyy/mm/dd hh24:mi:ss') as FECHAS,
	td.sgd_tpr_descrip AS TIPO, 
	r.ra_asun AS ASUNTO, 
	d1.depe_nomb AS depe_actu, 
	u1.usua_nomb AS nomb_actu, 
	r.radi_usu_ante as usant, 
	round(((r.radi_fech_radi+(td.sgd_tpr_termino * 7/5))-sysdate)) as diasr, 
	r.radi_depe_actu, 
    to_char(a.sgd_fech_impres,'yyyy/mm/dd hh24:mi:ss') as FECH_IMPR,
    to_char(a.anex_fech_envio,'yyyy/mm/dd hh24:mi:ss') as fech_envio,
	a.anex_estado as estado
from radicado r, anexos a, sgd_tpr_tpdcumento td, usuario u1, dependencia d1
where r.radi_nume_radi = a.anex_radi_nume 
	AND a.radi_nume_salida > 0
	AND r.radi_nume_radi like '%2' 
	AND r.tdoc_codi=td.sgd_tpr_codigo 
	AND r.codi_nivel <=5 
	AND r.radi_usua_actu=u1.usua_codi 
	AND r.radi_depe_actu=u1.depe_codi 
	AND u1.depe_codi=d1.depe_codi 
	AND substr(r.radi_nume_radi,5,1) != 9 
	AND a.radi_nume_salida IN(SELECT radi_nume_radi FROM SGD_ANU_ANULADOS AN)
";
		
// $sOrder = " ORDER BY entrada " ;

if ($sSelec != "") $sSelec = " AND " . $sSelec;
if ($sWhere != "") $sWhere = " AND " . $sWhere;
$sWhereC = $sSelec . $sWhere ;
//
$sSQL = $sSQL_1 . $sWhereC . $sWhereFec . " UNION " . $sSQL_2 . $sWhereC . $sWhereFec . " UNION " .  $sSQL_3 . $sWhereC . $sWhereFec . " UNION " .  $sSQL_4 . $sWhereC . $sWhereFec . $sOrder;
//echo $sSQL;
/*
$db->query($sSQLCount);
$next_record = $db->next_record();
$fldTotal = $db->f("TOTAL");
*/
//-------------------------------

//-------------------------------
// Assemble full SQL statement
//-------------------------------

  if($sCountSQL == "")
  {
    $iTmpI = strpos(strtolower($sSQL), "select");
    $iTmpJ = strpos(strtolower($sSQL), "from") - 1;
    $sCountSQL = str_replace(substr($sSQL, $iTmpI + 6, $iTmpJ - $iTmpI - 6), " count(*) ", $sSQL);
    $iTmpI = strpos(strtolower($sCountSQL), "order by");
    if($iTmpI > 1) 
      $sCountSQL = substr($sCountSQL, 0, $iTmpI - 1);
  }
//-------------------------------

  

//-------------------------------
// Process if form has all required parameters
//-------------------------------
  if(!$bReq)
  {
?>
     <tr>
      
    <td colspan="5" class="DataTD" height="25"><font class="DataFONT">No records</font></td>
     </tr>
</table>
<?
    return;
  }
//-------------------------------

//-------------------------------
// Execute SQL statement
  $iRecordsPerPage = 2000;
  $iCounter = 0;

 $rs=$db->conn->Execute($sSQL);
 while(!$rs->EOF && $iCounter < $iRecordsPerPage)
    {
    $fldRADI_NUME_RADI = $rs->fields["ENTRADA"];
    $fldRADI_FECH_RADI = $rs->fields["FECHAE"];
    $fldRADI_SALIDA    = $rs->fields["RSALIDA"];	
    $fldRADI_FECH_SALI = $rs->fields["FECHAS"];
    $fldTIPO_DOC       = $rs->fields["TIPO"];
    $fldRA_ASUN        = $rs->fields["ASUNTO"];
    $fldSALIDA         = $rs->fields["SALIDA"];	
    $fldDEPE_NOMB      = $rs->fields["DEPE_ACTU"];
    $fldUSUA_ACTUAL    = $rs->fields["NOMB_ACTU"];
	$fldUSUA_ANTER     = $rs->fields["USANT"]; 
	$fldDIASR          = $rs->fields["DIASR"]; 
	$fldRADI_DEPE_ACTU = $rs->fields["RADI_DEPE_ACTU"];
	$fldFECH_IMPR      = $rs->fields["FECH_IMPR"];	
	$fldFECH_ENVIO     = $rs->fields["FECH_ENVIO"];	
	$fldESTADO         = $rs->fields["ESTADO"];		
      $rs->MoveNext();  

//-------------------------------
// Process the HTML controls
//-------------------------------
?>
      <tr>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldRADI_NUME_RADI) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldRADI_FECH_RADI) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldRADI_SALIDA) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldRADI_FECH_SALI) ?>&nbsp;</font></td>
      <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldTIPO_DOC) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldRA_ASUN) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldDEPE_NOMB) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldUSUA_ACTUAL) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldUSUA_ANTER) ?>&nbsp;</font></td>
      <td class="DataTD"><font class="DataFONT">
      <? if ($fldRADI_DEPE_ACTU!=999){ echo tohtml($fldDIASR);} else {echo "<b>Archivado</b>";} ?>&nbsp;</font></td>
      <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldFECH_IMPR) ?>&nbsp;</font></td>
      <td class="DataTD"><font class="DataFONT">
      <? if ($fldESTADO==4){ echo tohtml($fldFECH_ENVIO);} ?>&nbsp;</font></td>
	  </tr><?
	  
//-------------------------------
// RADICADO Show end
//-------------------------------

//-------------------------------
// Move to the next record and increase record counter
//-------------------------------
    
    $iCounter++;
  }

 

//-------------------------------
// Finish form processing
//-------------------------------
  ?>
    </table>
  <?

}
//===============================

?>
</div></body>
</html>
