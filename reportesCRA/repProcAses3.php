<?php
/*********************************************************************************
 *       Filename: reporte.php
 *
 *       PHP 4.0 build 26 julio de 2005
 *********************************************************************************/

//-------------------------------
// reporte CustomIncludes begin

include ("./common.php");

// reporte CustomIncludes end
//-------------------------------

session_start();

//===============================
// Save Page and File Name available into variables
//-------------------------------
$sFileName = "repProcAses3.php";
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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><link rel="stylesheet" href="Site.css" type="text/css"></head>
<body class="PageBODY">



 <table>
  <tr>
   <td valign="top">
	<? 
	 RADICADO_show(); 
	?>
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
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $sFormTitle = "Reporte Radicados Por Responder";
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
  <tr>
       <td class="FormHeaderTD" colspan="5"><a name="RADICADO"><font class="FormHeaderFONT"><?=$sFormTitle?></font></a></td>
  </tr>
      
  <tr align="center"> 
    <td class="ColumnTD" height="25" width="85"><a href="<?=$sFileName?>?<?=$form_params?>FormRADICADO_Sorting=1&FormRADICADO_Sorted=<?=$form_sorting?>&"><font class="ColumnFONT">Radicado</font></a></td>
       
    <td class="ColumnTD" width="110" height="25"><a href="<?=$sFileName?>?<?=$form_params?>FormRADICADO_Sorting=2&FormRADICADO_Sorted=<?=$form_sorting?>&"><font class="ColumnFONT">Fecha 
      Rad Entrada</font></a></td>
    <td class="ColumnTD" height="25" width="23"><a href="<?=$sFileName?>?<?=$form_params?>FormRADICADO_Sorting=5&FormRADICADO_Sorted=<?=$form_sorting?>&"><font class="ColumnFONT">Tipo Doc</font></a></td>
    <td class="ColumnTD" height="25" width="331"><a href="<?=$sFileName?>?<?=$form_params?>FormRADICADO_Sorting=6&FormRADICADO_Sorted=<?=$form_sorting?>&"><font class="ColumnFONT">Asunto</font></a></td>       
    <td width="142" height="25" class="ColumnTD"><a href="<?=$sFileName?>?<?=$form_params?>FormRADICADO_Sorting=7&FormRADICADO_Sorted=<?=$form_sorting?>&"><font class="ColumnFONT">Dependencia Actual</font></a></td>
	<td width="142" height="25" class="ColumnTD"><a href="<?=$sFileName?>?<?=$form_params?>FormRADICADO_Sorting=8&FormRADICADO_Sorted=<?=$form_sorting?>&"><font class="ColumnFONT">Usuario Actual</font></a></td>
    <td class="ColumnTD" height="25" width="331"><a href="<?=$sFileName?>?<?=$form_params?>FormRADICADO_Sorting=9&FormRADICADO_Sorted=<?=$form_sorting?>&"><font class="ColumnFONT">Usuario Anterior</font></a></td>       
    <td class="ColumnTD" height="25" width="331"><a href="<?=$sFileName?>?<?=$form_params?>FormRADICADO_Sorting=10&FormRADICADO_Sorted=<?=$form_sorting?>&"><font class="ColumnFONT">Dias Restantes</font></a></td>       
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

  $ps_RADI_DEPE_RADI = get_param("s_RADI_DEPE_ACTU");
  if(is_number($ps_RADI_DEPE_RADI) && strlen($ps_RADI_DEPE_RADI))
    $ps_RADI_DEPE_RADI = tosql($ps_RADI_DEPE_RADI, "Number");
  else 
    $ps_RADI_DEPE_RADI = "";

  if(strlen($ps_RADI_DEPE_RADI))
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
  
$sSQL = "
	select r.radi_nume_radi 											AS ENTRADA, 
		to_char(r.radi_fech_radi,'yyyy/mm/dd hh24:mi:ss') 				AS FECHAE, 
		td.sgd_tpr_descrip 												AS TIPO, 
		r.ra_asun 														AS ASUNTO, 
		d.depe_nomb 													AS depe_actu, 
		u.usua_nomb 													AS nomb_actu, 
		r.radi_usu_ante 												AS usant, 
		round(((r.radi_fech_radi+(td.sgd_tpr_termino * 7/5))-sysdate)) 	AS diasr,
		r.RADI_PATH 													AS R_RADI_PATH, 
		be.NOMBRE_DE_LA_EMPRESA
	from radicado r, sgd_tpr_tpdcumento td, usuario u, dependencia d, bodega_empresas be
";
$sWhere = "	where r.radi_nume_radi not in(select anex_radi_nume from anexos where anex_estado > 2) 
	and r.radi_nume_radi not in(select radi_nume_radi from hist_eventos where upper(substr(hist_obse,1,3)) = 'NRR')
	and r.radi_nume_radi like '%2' AND r.radi_depe_actu != 999
	AND substr(r.radi_nume_radi,5,1) != 9
	AND r.tdoc_codi=td.sgd_tpr_codigo
	AND r.radi_usua_actu=u.usua_codi 
	AND r.radi_depe_actu=u.depe_codi
	AND u.depe_codi= d.depe_codi  
	AND r.eesp_codi = be.IDENTIFICADOR_EMPRESA (+)
";
		
	$sSQL .= $sWhere . $sOrder;
//if ($sSelec != "") $sSelec = " AND " . $sSelec;
//if ($sWhere != "") $sWhere = " AND " . $sWhere;
//$sWhereC = $sSelec . $sWhere ;
//

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
//-------------------------------
//echo "<hr>$sSQL</hr>";
	$sSQLCount = "Select count(*) as Total from radicado r, sgd_tpr_tpdcumento td, usuario u, dependencia d, bodega_empresas be " . $sWhere;
	$db->query($sSQLCount);
	$next_record = $db->next_record();
	$fldTotal = $db->f("TOTAL");

  $db->query($sSQL);
  $next_record = $db->next_record();
//-------------------------------
// Process empty recordset
//-------------------------------
  if(!$next_record)
  {
?>
     <tr>
      <td colspan="5" class="DataTD"><font class="DataFONT">No Encontro Registros  </font></td>
     </tr>
  <?
  }
  else  {
  ?>
     <tr>
      <td colspan="5" class="DataTD"><font class="DataFONT"><b>Total Registros Encontrados: <?=$fldTotal?></b></font></td>
     </tr>
  <?

//-------------------------------
// Initialize page counter and records per page
//-------------------------------
  $iRecordsPerPage = 2000;
  $iCounter = 0;
//-------------------------------

//-------------------------------
// Display grid based on recordset
//-------------------------------
  while($next_record  && $iCounter < $iRecordsPerPage)
  {

//-------------------------------
// Create field variables based on database fields
//-------------------------------

    $fldRADI_NUME_RADI = $db->f("ENTRADA");
    $fldRADI_FECH_RADI = $db->f("FECHAE");
    $fldRADI_SALIDA    = $db->f("RSALIDA");	
    $fldRADI_FECH_SALI = $db->f("FECHAS");
    $fldTIPO_DOC       = $db->f("TIPO");
    $fldRA_ASUN        = $db->f("ASUNTO");
    $fldSALIDA         = $db->f("SALIDA");	
    $fldDEPE_NOMB      = $db->f("DEPE_ACTU");
    $fldUSUA_ACTUAL    = $db->f("NOMB_ACTU");
	$fldUSUA_ANTER     = $db->f("USANT"); 
	$fldDIASR          = $db->f("DIASR"); 
	$fldRADI_DEPE_ACTU = $db->f("RADI_DEPE_ACTU");
	$fldFECH_IMPR      = $db->f("FECH_IMPR");	
	$fldFECH_ENVIO     = $db->f("FECH_ENVIO");	
	$fldESTADO         = $db->f("ESTADO");		
    $fldRADI_PATH      = $db->f("R_RADI_PATH");
	  $fldEMPRESA      = $db->f("NOMBRE_DE_LA_EMPRESA");
    $next_record = $db->next_record();
    
?>
      <tr>
      <td class="DataTD"><font class="DataFONT">
	  <? if (strlen($fldRADI_PATH)){ $iii = $iii +1;?>  <a href='../bodega<?=$fldRADI_PATH?>' target='Imagen<?=$iii?>'><?}?>
	   <font class="DataFONT"><?=$fldRADI_NUME_RADI?></font>
	  <?if (strlen($fldRADI_PATH)){?></a><?}?>&nbsp;</font></td>
      <td class="DataTD"><font class="DataFONT"><a href="../verradicado.php?verrad=<?=$fldRADI_NUME_RADI."&".session_name()."=".session_id()."&krd=$krd&carpeta=8&nomcarpeta=Busquedas&tipo_carp=0"?>">
      <?= tohtml($fldRADI_FECH_RADI) ?>&nbsp;</a></font></td>
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
      <?= tohtml($fldEMPRESA) ?>&nbsp;</font></td>
	  </tr><?
	  
//-------------------------------
// RADICADO Show end
//-------------------------------

//-------------------------------
// Move to the next record and increase record counter
//-------------------------------
    
    $iCounter++;
  }

 
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