<?php
/*********************************************************************************
 *       Filename: reporte.php
 *
 *       PHP 4.0 build 15 julio de 2005
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
$sFileName = "repDepTipoDoc.php";
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
  
  global $sForm;
  $sFormTitle = "Reporte Asignacion Radicados";
  $sActionFileName = "repDepTipoDoc.php";
  $ss_desde_RADI_FECH_RADIDisplayValue = "";
  $ss_hora_inicialDisplayValue = "";
  $ss_hora_finalDisplayValue = "";

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
    <form method="GET" action="<?= $sActionFileName ?>" name="Search">
    <input type="hidden" name="FormName" value="Search"><input type="hidden" name="FormAction" value="search">
    
  <table class="FormTABLE" width="722">
    <tr>
      <td class="FormHeaderTD" colspan="7"><a name="Search"><font class="FormHeaderFONT"><?=$sFormTitle?></font></a></td>
    </tr>


     <tr>
      <td class="FieldCaptionTD" width="185" align="right" height="10"><font class="FieldCaptionFONT">DEPENDENCIA</font></td>
      <td class="DataTD"  width="115" height="20"> 
		<select class="DataFONT" name="s_RADI_DEPE_ACTU">
		  <?
    		$ss_RADI_DEPE_ACTUDisplayValue = "Todas las Dependencias";
    		$l= strlen($flds_RADI_DEPE_ACTU);
		    if ($l==0){
			    echo "<option value=\"\" SELECTED>" . $ss_RADI_DEPE_ACTUDisplayValue . "</option>";
    		}else{
      			echo "<option value=\"\">" . $ss_RADI_DEPE_ACTUDisplayValue . "</option>";
    		}
			$lookup_s_RADI_DEPE_ACTU = db_fill_array("select DEPE_CODI, substr(DEPE_NOMB,1,30) from DEPENDENCIA order by 2");
			if(is_array($lookup_s_RADI_DEPE_ACTU))
			{
				reset($lookup_s_RADI_DEPE_ACTU);
			    while(list($key, $value) = each($lookup_s_RADI_DEPE_ACTU))
			    {
				    if($l>0 && $key == $flds_RADI_DEPE_ACTU) $option="<option SELECTED value=\"$key\">$value</option>";
				    else $option="<option value=\"$key\">$value</option>";
      				echo $option;
    			}
  			}
	  ?></select>	  
	</td>
	<td  class="FieldCaptionTD">
  		<font class="FieldCaptionFONT">
		<INPUT type="radio" NAME="s_solo_nomb" value="tdoc" CHECKED
		<?if($flds_solo_nomb=="All"){ echo ("CHECKED");} ?>>Por tipo documento
  		<INPUT type="radio" NAME="s_solo_nomb" value="ases"
  		<? if($flds_solo_nomb=="Any"){echo ("CHECKED");} ?>>Por asesor
  		</font>
	</td>
   </tr>

   <tr>
      <td class="FieldCaptionTD" width=50% align="right" height="25"><font class="FieldCaptionFONT">FECHA RADICACION DESDE</font></td>
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
      <td class="FieldCaptionTD" width=50% align="right" height="25"><font class="FieldCaptionFONT">FECHA RADICACION HASTA</font></td>

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
  global $sRADICADOErr;
  global $sFileName;
  global $styles;
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
     
  <tr align="center"> 
    <td class="ColumnTD" height="25" width="85"><a href="<?=$sFileName?>?<?=$form_params?>FormRADICADO_Sorting=1&FormRADICADO_Sorted=<?=$form_sorting?>&"><font class="ColumnFONT">Dependencia</font></a></td>
    <td class="ColumnTD" width="110" height="25"><a href="<?=$sFileName?>?<?=$form_params?>FormRADICADO_Sorting=2&FormRADICADO_Sorted=<?=$form_sorting?>&"><font class="ColumnFONT">Tipo Doc</font></a></td>
    <td class="ColumnTD" height="25" width="85"><a href="<?=$sFileName?>?<?=$form_params?>FormRADICADO_Sorting=3&FormRADICADO_Sorted=<?=$form_sorting?>&"><font class="ColumnFONT">Asignados</font></a></td>
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
 $sWDepe = "";
  $ps_RADI_DEPE_RADI = get_param("s_RADI_DEPE_ACTU");
  if(is_number($ps_RADI_DEPE_RADI) && strlen($ps_RADI_DEPE_RADI))
    $ps_RADI_DEPE_RADI = tosql($ps_RADI_DEPE_RADI, "Number");
  else 
    $ps_RADI_DEPE_RADI = "";

  if(strlen($ps_RADI_DEPE_RADI))
  {
    $HasParam = true;//se busca en el radicado donde sea like 'yyyyDEP%'
    $sWDepe = " AND d.depe_codi = $ps_RADI_DEPE_RADI ";

//    $sWhere = $sWhere . "R.RADI_NUME_RADI LIKE '" . substr($ps_desde_RADI_FECH_RADI,6,4) . 
//	$ps_RADI_DEPE_RADI ."%'" ;
    
  }
  else
  {
//    $bReq = false;
  }
  
  $ps_solo_nomb = get_param("s_solo_nomb");
  if ($ps_solo_nomb == "tdoc")  {
	$sSQLt = ", td.sgd_tpr_descrip 				AS tipo";
  	$sFromt= ", sgd_tpr_tpdcumento td";
	$sWheret= " AND r.tdoc_codi=td.sgd_tpr_codigo ";
	$sGroupt = " , td.sgd_tpr_descrip ";	
  }
  else  {
	$sSQLt = ", u.usua_nomb						AS tipo ";
	$sFromt= ", usuario u";
	$sWheret= "	AND substr(h.usua_codi_dest,4,3) = u.usua_codi
	AND substr(h.usua_codi_dest,1,3) = u.depe_codi 
	AND substr(h.usua_codi_dest,4,3) != '001'
	";
	$sGroupt = " , u.usua_nomb ";	
  }
  
$sSQL = "  
	select count(r.radi_nume_radi) 	AS cantRads, 
	d.depe_nomb 					AS dependen 
 " . $sSQLt;
$sFrom = "
	from dependencia d, hist_eventos h, radicado r
" . $sFromt;
$sWhere = " where hist_obse = 'Rad.' 
	and r.radi_nume_radi like '%2' 
	and r.radi_nume_radi = h.radi_nume_radi 
	AND substr(h.usua_codi_dest,1,3) = d.depe_codi 
	and d.depe_codi < 900 
	";

$sGroup = "	group by d.depe_nomb " . $sGroupt;
$sOrder = " order by dependen, tipo ";
$sWhere .= $sWheret . $sWhereFec . $sWDepe ; 
$sSQL .= $sFrom . $sWhere . $sGroup . $sOrder;
/*
$sSQLCount =
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
// echo "<hr>$sSQL</hr>";
	$sSQLCount = "Select count(*) as Total ". $sFrom . $sWhere;
	$db->query($sSQLCount);
	$next_record = $db->next_record();
	$fldTotal = $db->f("TOTAL");
//echo "$sSQL";
  $db->query($sSQL);
  $next_record = $db->next_record();
//-------------------------------
// Process empty recordset
//-------------------------------
  if(!$next_record)
  {
?>
     <tr>
      <td colspan="5" class="DataTD"><font class="DataFONT">No records</font></td>
     </tr>
<?
  }
  
  else  {
  ?>
     <tr>
      <td colspan="5" class="DataTD"><font class="DataFONT"><b>Total Registros Encontrados: <?=$fldTotal?></b></font></td>
     </tr>
  <?
  
  $iRecordsPerPage = 2000;
  $iCounter = 0;
//-------------------------------

//-------------------------------
// Display grid based on recordset
//-------------------------------
 $DEPACTUAL = $db->f("DEPENDEN");
 $CANTOTAL = 0;
  while($next_record  && $iCounter < $iRecordsPerPage)
  {
//-------------------------------
// Create field variables based on database fields
//-------------------------------
    $fldDEPENDEN 		= $db->f("DEPENDEN");
    $fldTIPO 			= $db->f("TIPO");
    $fldCANTRADS    	= $db->f("CANTRADS");	
	if ($DEPACTUAL == $fldDEPENDEN) {
?>
      <tr>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldDEPENDEN) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldTIPO) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldCANTRADS) ?>&nbsp;</font></td>
	  </tr><?
		$CANTOTAL =  $CANTOTAL + $fldCANTRADS;
    	$next_record = $db->next_record();        
	}
	else  {
?>
     <tr>
	  <td class="DataTD"><font class="DataFONT">
      <?
	  echo "<b>$DEPACTUAL</b>"; 
	  ?>&nbsp;</font></td>
      <td class="DataTD"><font class="DataFONT">
      <?
	   echo "<b>TOTAL</b>"; 
	   ?>&nbsp;</font></td>
	  <td class="DataTD"><font class="DataFONT">
      <?
	  echo "<b>$CANTOTAL</b>"; 
	  ?>&nbsp;</font></td>
	  </tr><?
		$CANTOTAL = 0;
		$DEPACTUAL = $fldDEPENDEN;
		}


//-------------------------------
// Move to the next record and increase record counter
//-------------------------------

    $iCounter++;
  }
?>
     <tr>
	  <td class="DataTD"><font class="DataFONT">
      <?
	  echo "<b>$DEPACTUAL</b>"; 
	  ?>&nbsp;</font></td>
      <td class="DataTD"><font class="DataFONT">
      <?
	   echo "<b>TOTAL</b>"; 
	   ?>&nbsp;</font></td>
	  <td class="DataTD"><font class="DataFONT">
      <?
	  echo "<b>$CANTOTAL</b>"; 
	  ?>&nbsp;</font></td>
	  </tr><?
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
