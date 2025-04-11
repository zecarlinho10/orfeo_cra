<?php
/*********************************************************************************
 *       Filename: repRadicadosEntrada.php
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

    include "$ruta_raiz/config.php";
	include_once "$ruta_raiz/include/db/ConnectionHandler.php";
    $db = new ConnectionHandler("$ruta_raiz");
    if (!defined('ADODB_FETCH_ASSOC'))define('ADODB_FETCH_ASSOC',2);
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

include ("../busqueda/common.php");

// reporte CustomIncludes end
//-------------------------------

session_start();

//===============================
// Save Page and File Name available into variables
//-------------------------------
$sFileName = "repRadicadosEntrada.php";
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");


$encabezado = "&krd=$krd&dep_sel=$dep_sel&s_SELECCION=$s_SELECCION&s_desde_RADI_FECH_RADI=$s_desde_RADI_FECH_RADI&s_hasta_RADI_FECH_RADI=$s_hasta_RADI_FECH_RADI&s_RADI_DEPE_ACTU=$s_RADI_DEPE_ACTU";
//===============================

// reporte Show begin

//===============================
// Display page

//===============================
// HTML Page layout
//-------------------------------
?><html>
<head>
<title>Reporte Radicados de Entrada </title>
<meta name="GENERATOR" content="YesSoftware CodeCharge v.2.0.5 build 11/30/2001">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><link rel="stylesheet" href="../busqueda/Site.css" type="text/css"></head>
<body class="PageBODY">

 <table>
  <tr>
   <td valign="top">
<?php Search_show() ?>
    
   </td>
  </tr>
 </table>

 <table>
  <tr>
   <td valign="top">
<? 
  $ps_desde_RADI_FECH_RADI = get_param("s_desde_RADI_FECH_RADI");
  $ps_hasta_RADI_FECH_RADI = get_param("s_hasta_RADI_FECH_RADI");

if ($ps_desde_RADI_FECH_RADI &&  $ps_hasta_RADI_FECH_RADI) 	RADICADO_show(); else 		echo "<center><b>Por favor seleccione un rango de fechas</center></b>";	
	
?>
   </td>
  </tr>
 </table>



<?php

// reporte Show end


//===============================
// Display Search Form
//-------------------------------
function Search_show()
{
  global $db;
  global $styles;
  global $dbB;
  global $sForm;
  global $encabezado;
  global $s_RADI_DEPE_ACTU;
  $sFormTitle = "Reporte Radicados de Entrada";
  $sActionFileName = "repRadicadosEntrada.php";
  $ss_desde_RADI_FECH_RADIDisplayValue = "";
  $ss_hora_inicialDisplayValue = "";
  $ss_hora_finalDisplayValue = "";

//-------------------------------
// Search Open Event begin
  $cadena="";
  for ($hace=500;$hace>=0;$hace--){
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

  $flds_RADI_DEPE_ACTU = strip(get_param("s_RADI_DEPE_ACTU"));
  $flds_RADI_DEPE_RADI = strip(get_param("s_RADI_DEPE_RADI"));
  $flds_desde_RADI_FECH_RADI = strip(get_param("s_desde_RADI_FECH_RADI"));
  $flds_hasta_RADI_FECH_RADI = strip(get_param("s_hasta_RADI_FECH_RADI"));  
  $flds_hora_inicial = strip(get_param("s_hora_inicial"));
  $flds_hora_final = strip(get_param("s_hora_final"));

//-------------------------------
// Search Show begin
//-------------------------------


//-------------------------------
// Search Show Event begin
// Search Show Event end
//-------------------------------
	$iSQLdep = "select depe_codi,depe_nomb from DEPENDENCIA ORDER BY DEPE_CODI";
 	$db->query($iSQLdep);
?>
  <form name='frmCrear' action='repRadicadosEntrada.php?<?=session_name()."=".session_id()."&$encabezado"?>' method="post">
    
  <table class="FormTABLE" width="882">
    <tr>
      <td class="FormHeaderTD" colspan="7"><a name="Search"><font class="FormHeaderFONT"><?=$sFormTitle?></font></a></td>
    </tr>
  
     <tr>
      <td class="FieldCaptionTD" width="114" align="right" height="25"><font class="FieldCaptionFONT">DEPENDENCIA</font></td>
      <td class="DataTD" width="41" height="25"> 
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
          <tr>
      <td class="FieldCaptionTD" width="114" align="right" height="25"><font class="FieldCaptionFONT">FECHA RADICACION DESDE</font></td>
      <td class="DataTD" width="41" height="25"> 
	  
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
      <td class="FieldCaptionTD" width="161" align="right" height="25"><font class="FieldCaptionFONT">FECHA RADICACION HASTA</font></td>

      <td class="DataTD" width="546" height="25"> 
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
?></select></td>
     </tr>

    <tr align="center"> 
      <td colspan="3"> 
        <input type="submit" value="BUSCAR">
      </td>
    </tr>
   </table>
   </form>
<?


}


//===============================
// Display Grid Form
//-------------------------------
function RADICADO_show()
{
//-------------------------------
// Initialize variables  
//-------------------------------
  
  
  global $db;
  global $dbB;
  global $sRADICADOErr;
  global $sFileName;
  global $styles;
  global $encabezado;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $sFormTitle = "Reporte";
  $HasParam = false;
  $bReq = true;
  $iRecordsPerPage = 4000;
  $iCounter = 0;
  $iSort = "";
  $iSorted = "";
  $sDirection = "";
  $sSortParams = "";
  $iTmpI = 0;
  $iTmpJ = 0;
  $sCountSQL = "";

  $transit_params = "";
  $form_params = "s_RADI_DEPE_RADI=" . tourl(get_param("s_RADI_DEPE_RADI")) . 
          "&s_RADI_DEPE_ACTU=" . tourl(get_param("s_RADI_DEPE_ACTU")) . 
          "&s_SELECCION=" . tourl(get_param("s_SELECCION")) . 
          "&s_desde_RADI_FECH_RADI=" . tourl(get_param("s_desde_RADI_FECH_RADI")) . 
		  "&s_hasta_RADI_FECH_RADI=" . tourl(get_param("s_hasta_RADI_FECH_RADI")) . "&";

//-------------------------------
// Build ORDER BY statement
//-------------------------------
  $sOrder = " order by entrada Asc";
  $iSort = get_param("FormRADICADO_Sorting");
  $iSorted = get_param("FormRADICADO_Sorted");
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
    if ($iSort == 3)  $sOrder = " order by tipo"      . $sDirection;
	if ($iSort == 4)  $sOrder = " order by asunto"    . $sDirection;
    if ($iSort == 5)  $sOrder = " order by remitente"  . $sDirection;
    if ($iSort == 6)  $sOrder = " order by depe_actu" . $sDirection;
    if ($iSort == 7)  $sOrder = " order by nomb_actu" . $sDirection;		
    if ($iSort == 8)  $sOrder = " order by diasr"     . $sDirection;		

  }

//-------------------------------
// HTML column headers
//-------------------------------
?>
     
<table class="FormTABLE" width="880">
  <tr>
       <td class="FormHeaderTD" colspan="5"><a name="RADICADO"><font class="FormHeaderFONT"><?=$sFormTitle?></font></a></td>
  </tr>
      
  <tr align="center"> 
    <td width="142" height="25" class="ColumnTD"><a href='repRadicadosEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=1&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Radicado</font>	</a> </td>
	
	   <td width="142" height="25" class="ColumnTD"><a href='repRadicadosEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=2&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Fecha Rad Entrada</font>	</a> </td>

	   <td width="142" height="25" class="ColumnTD"><a href='repRadicadosEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=3&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Tipo Doc</font>	</a> </td>

	   <td width="142" height="25" class="ColumnTD"><a href='repRadicadosEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=4&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Asunto</font>	</a> </td>

	   <td width="142" height="25" class="ColumnTD"><a href='repRadicadosEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=5&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Remitente</font>	</a> </td>

	   <td width="142" height="25" class="ColumnTD"><a href='repRadicadosEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=6&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Dependencia Actual</font>	</a> </td>

	   <td width="142" height="25" class="ColumnTD"><a href='repRadicadosEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=7&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Usuario Actual</font>	</a> </td>

	   <td width="142" height="25" class="ColumnTD"><a href='repRadicadosEntrada.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=8&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Dias Restantes</font>	</a> </td>
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
 $sSelec = "";
/*FIN  /* Seleccion Todo - Solo archivados - Solo NO archivados */

  $ps_RADI_DEPE_RADI = get_param("s_RADI_DEPE_ACTU");
  if(is_number($ps_RADI_DEPE_RADI) && strlen($ps_RADI_DEPE_RADI))
    $ps_RADI_DEPE_RADI = tosql($ps_RADI_DEPE_RADI, "Number");
  else 
    $ps_RADI_DEPE_RADI = "";

  if($ps_RADI_DEPE_RADI > 0)
  {
    $HasParam = true;//se busca en el radicado donde sea like 'yyyyDEP%'
    $sWhere = $sWhere . " r.radi_depe_radi = $ps_RADI_DEPE_RADI";

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
	td.sgd_tpr_descrip AS TIPO, 
	r.ra_asun AS ASUNTO,
	dir.sgd_dir_nombre AS REMITENTE,
	d1.depe_nomb AS depe_actu, 
	u1.usua_nomb AS nomb_actu, 
	round(((r.radi_fech_radi+(td.sgd_tpr_termino * 7/5))-sysdate)) as diasr, 
	r.radi_depe_actu,
	dir.sgd_esp_codi as ESP,
	dir.sgd_oem_codigo AS OEM,
	dir.sgd_ciu_codigo AS CIU
from radicado r, sgd_tpr_tpdcumento td, usuario u1, dependencia d1, sgd_dir_drecciones dir
where r.radi_nume_radi = dir.radi_nume_radi 
    and dir.sgd_dir_tipo = 1
	AND r.radi_nume_radi like '%2' 
	AND r.tdoc_codi=td.sgd_tpr_codigo 
	AND r.codi_nivel <=5 
	AND r.radi_usua_actu=u1.usua_codi 
	AND r.radi_depe_actu=u1.depe_codi 
	AND u1.depe_codi=d1.depe_codi 
	AND substr(r.radi_nume_radi,5,1) != 9 
";
		
// $sOrder = " ORDER BY entrada " ;

if ($sSelec != "") $sSelec = " AND " . $sSelec;
if ($sWhere != "") $sWhere = " AND " . $sWhere;
$sWhereC = $sSelec . $sWhere ;
//
$sSQL = $sSQL_1 . $sWhereC . $sWhereFec . $sOrder;
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
//-------------------------------
//echo "<hr>$sSQL</hr>";
  $rs = $db->conn->Execute($sSQL);
//  $db->query($sSQL);

  //$next_record = $db->next_record();
//-------------------------------
// Process empty recordset
//-------------------------------
  if(!$rs->fields["ENTRADA"] != "")
  {
?>
     <tr>
      <td colspan="5" class="DataTD"><font class="DataFONT">No records</font></td>
     </tr>
<?
   
?>
  </table>
<?

    return;
  }
//-------------------------------
// Initialize page counter and records per page
//-------------------------------
  $iRecordsPerPage = 4000;
  $iCounter = 0;
     while(!$rs->EOF && $iCounter < $iRecordsPerPage)
    {
    $fldRADI_NUME_RADI = $rs->fields["ENTRADA"];
    $fldRADI_FECH_RADI = $rs->fields["FECHAE"];
    $fldTIPO_DOC       = $rs->fields["TIPO"];
    $fldRA_ASUN        = $rs->fields["ASUNTO"];
	$fldREMITENTE      = $rs->fields["REMITENTE"];
    $fldDEPE_NOMB      = $rs->fields["DEPE_ACTU"];
    $fldUSUA_ACTUAL    = $rs->fields["NOMB_ACTU"];
	$fldDIASR          = $rs->fields["DIASR"]; 
	$fldRADI_DEPE_ACTU = $rs->fields["RADI_DEPE_ACTU"];
	$fldCODESP         = $rs->fields["ESP"];
	$fldCODOEM         = $rs->fields["OEM"];
	$fldCODCIU         = $rs->fields["CIU"];
	//--------------------------------
	//Determina el Remitente
	//--------------------------------
	if($fldCODESP != 0)
	   {
	     $sSQL_BR = "
			select nombre_de_la_empresa as NOMBRE
			from bodega_empresas
			where identificador_empresa = '$fldCODESP'
          ";
		//  $dbB->query($sSQL_BR);
		    $rsESP = $db->conn->Execute($sSQL_BR);
		//  $next_recordB = $dbB->next_record();
		  $fldNOMBRE   = $rsESP->fields["NOMBRE"];
	   }
	 else
	 {
	 	if($fldCODOEM != 0)
	   		{
	     	$sSQL_BR = "
				select sgd_oem_oempresa as NOMBRE
				from sgd_oem_oempresas
				where sgd_oem_codigo = '$fldCODOEM'
          	";
//		  	$dbB->query($sSQL_BR);
		    $rsOEM = $db->conn->Execute($sSQL_BR);
//		    $next_recordB = $dbB->next_record();
		  	$fldNOMBRE   = $rsOEM->fields["NOMBRE"];
	   		}
		   else {
		         if($fldCODCIU != 0)
	   		       {
	     	         $sSQL_BR = "
				 		select sgd_ciu_nombre as NOMBRE,
						 sgd_ciu_apell1 as APE1,
						 sgd_ciu_apell2 as APE2
						from sgd_ciu_ciudadano
						where sgd_ciu_codigo = '$fldCODCIU'
          			";
		  			//$dbB->query($sSQL_BR);
			        $rsCIU = $db->conn->Execute($sSQL_BR);
					//$next_recordB = $dbB->next_record();
		  			$fldNOMBRE   = trim($rsCIU->fields["NOMBRE"]);
					$fldNOMBRE   .= trim($rsCIU->fields["APE1"]);
					$fldNOMBRE   .= trim($rsCIU->fields["APE2"]);
	   	        } 
		   }
	  } 
	   
	//Fin Determina Remitente
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
      <?= tohtml($fldTIPO_DOC) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldRA_ASUN) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">
	   <?= tohtml($fldNOMBRE) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">	   
      <?= tohtml($fldDEPE_NOMB) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldUSUA_ACTUAL) ?>&nbsp;</font></td>
      <td class="DataTD"><font class="DataFONT">
      <? if ($fldRADI_DEPE_ACTU!=999){ echo tohtml($fldDIASR);} else {echo "<b>Archivado</b>";} ?>&nbsp;</font></td>
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


//-------------------------------
// RADICADO Close Event begin
// RADICADO Close Event end
//-------------------------------
}
//===============================

?>
</div></body>
</html>
