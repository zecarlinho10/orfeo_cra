<?php
/*********************************************************************************
 *       Filename: repVencimientos.php
 *
 *       PHP 4.0 build 26 Agosto de 2005
 *********************************************************************************/
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
//$db->conn->debug = true;
//if (!$dependencia or !$nivelus or !$usua_admin)   include "../rec_session.php";
$sFileName = "repVencimientos.php";
$sAction = get_param("FormAction");
$sForm = get_param("FormName");

session_start();

$encabezado = "&krd=$krd&dep_sel=$dep_sel&s_SELECCION=$s_SELECCION&s_RADI_NUME_RADI=$s_RADI_NUME_RADI&s_desde_RADI_FECH_RADI=$s_desde_RADI_FECH_RADI&s_hasta_RADI_FECH_RADI=$s_hasta_RADI_FECH_RADI&s_RADI_DEPE_ACTU=$s_RADI_DEPE_ACTU";

?><html>
<head>
<title>Reporte Vencimientos</title>
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
	$ps_RADI_NUME_RADI = get_param("s_RADI_NUME_RADI");
	$ps_desde_RADI_FECH_RADI = get_param("s_desde_RADI_FECH_RADI");
  	$ps_hasta_RADI_FECH_RADI = get_param("s_hasta_RADI_FECH_RADI");
	if ($ps_desde_RADI_FECH_RADI && $ps_hasta_RADI_FECH_RADI || $ps_RADI_NUME_RADI)  RADICADO_show(); else echo "<center><b>Por favor seleccione un rango de fechas</center></b>";	
	?>
   </td>
  </tr>
 </table>

<?php
//===============================
// Display Search Form
//-------------------------------
function Search_show()
{
  global $db;
  global $styles;
  global $sForm;
  global $encabezado;
  global $s_RADI_DEPE_ACTU;
  global $s_RADI_NUME_RADI;
  $sFormTitle = "Reporte Vencimientos";
  $sActionFileName = "repVencimientos.php";
  $ss_desde_RADI_FECH_RADIDisplayValue = "";
  $ss_hora_inicialDisplayValue = "";
  $ss_hora_finalDisplayValue = "";
  $cadena="";
  for ($hace=-100;$hace<=30;$hace++) {
  	$timestamp = mktime (0,0,0,date("m"),date("d")+$hace,date("Y"));
    $mes = Date('Y/m/d',$timestamp);
    $valormes = Date("M d Y", $timestamp);
    $cadena.=$mes.";". $valormes .";";
  }
  $cadena2="";
  for ($hace=0;$hace<=50;$hace++){
    $cadena2.= $hace .";" .$hace . ";";
  }
  $cadena3="";
  for ($hace=0;$hace<=50;$hace++){
    $cadena3.= $hace .";" .$hace;
	if ($hace!= 50)
		$cadena3.= ";";
  }
// Set variables with search parameters
//-------------------------------
  $flds_RADI_NUME_RADI = strip(get_param("s_RADI_NUME_RADI"));
  $flds_SELECCION = strip(get_param("s_SELECCION"));
  $flds_RADI_DEPE_ACTU = strip(get_param("s_RADI_DEPE_ACTU"));
  $flds_RADI_DEPE_RADI = strip(get_param("s_RADI_DEPE_RADI"));
  $flds_desde_RADI_FECH_RADI = strip(get_param("s_desde_RADI_FECH_RADI"));
  $flds_hasta_RADI_FECH_RADI = strip(get_param("s_hasta_RADI_FECH_RADI"));  
  $flds_hora_inicial = strip(get_param("s_hora_inicial"));
  $flds_hora_final = strip(get_param("s_hora_final"));

  $iSQLdep = "select depe_codi,depe_nomb from DEPENDENCIA ORDER BY DEPE_CODI";
//  $db->query($iSQLdep);

 $rs=$db->conn->Execute($iSQLdep);

?>
   <form name='frmCrear' action='repVencimientos.php?<?=session_name()."=".session_id()."&$encabezado"?>' method="post">
    
  <table class="FormTABLE" width="722">
	<tr>
    	<td class="FormHeaderTD" colspan="7"><a name="Search"><font class="FormHeaderFONT"><?=$sFormTitle?></font></a></td>
    </tr>
	<tr>
      <td class="FieldCaptionTD" width="154" align="right" height="25"><font class="FieldCaptionFONT">RADICADO</font></td>
  <td width="141" class="DataTD"><input class="DataFONT" type="text" name="s_RADI_NUME_RADI" maxlength="" value="<?=tohtml($flds_RADI_NUME_RADI) ?>" size="" ></td>

      <td class="FieldCaptionTD" width="200" align="right" height="25"><font class="FieldCaptionFONT">DEPENDENCIA ACTUAL</font></td>
   <td width="141" class="DataTD">
   	  <?
	$sql = " SELECT 'Todas las dependencias' as DEPE_NOMB, 0 AS DEPE_CODI FROM DEPENDENCIA
	UNION  SELECT SUBSTR(DEPE_NOMB,1,30), DEPE_CODI AS DEPE_CODI FROM DEPENDENCIA
					order by DEPE_NOMB";
//	$sql = "select UBIC_DEPE_RADI, UBIC_DEPE_RADI from UBICACION_FISICA order by 2";
	$rsDep = $db->conn->Execute($sql);
	if(!$s_RADI_DEPE_ACTU) $s_RADI_DEPE_ACTU= 0;
	print $rsDep->GetMenu2("s_RADI_DEPE_ACTU","$s_RADI_DEPE_ACTU",false, false, 0," class='select'");
	?>	</td>
    </tr>
	 </table>
 <table class="FormTABLE" width="722">
     <tr>
     <td class="FieldCaptionTD" width="154" align="right" height="25"><font class="FieldCaptionFONT">FECHA VECMTO DESDE</font></td>
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
      <td class="FieldCaptionTD" width="254" align="right" height="25"><font class="FieldCaptionFONT">FECHA VENCMTO HASTA</font></td>
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
			?></select>
	   </td>
  
      <td colspan="3"> 
        <input type="submit" value="Generar">
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
  global $encabezado;
  global $s_RADI_NUME_RADI;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $sFormTitle = "Reporte Vencimientos";
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
    if ($iSort == 1)  $sOrder = " order by entrada"    . $sDirection;
    if ($iSort == 2)  $sOrder = " order by fechae"     . $sDirection;
    if ($iSort == 3)  $sOrder = " order by tipo"       . $sDirection;
    if ($iSort == 4)  $sOrder = " order by termino"    . $sDirection;
    if ($iSort == 5)  $sOrder = " order by asunto"     . $sDirection;
    if ($iSort == 6)  $sOrder = " order by depe_actu"  . $sDirection;
    if ($iSort == 7)  $sOrder = " order by nomb_actu"  . $sDirection;	
    if ($iSort == 8)  $sOrder = " order by usant"      . $sDirection;
    if ($iSort == 9)  $sOrder = " order by fech_vcmto, entrada" . $sDirection;		

  }

//-------------------------------
// HTML column headers
//-------------------------------
?>
     
<table class="FormTABLE" width="715">
  <tr>
       
  </tr>
      
  <tr align="center"> 
    <td width="142" height="25" class="ColumnTD"><a href='repVencimientos.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=1&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Radicado</font>	</a> </td>
      
  <td width="142" height="25" class="ColumnTD"><a href='repVencimientos.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=2&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Fecha 
      Rad Entrada</font>	</a> </td>
	  
    <td width="142" height="25" class="ColumnTD"><a href='repVencimientos.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=3&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Tipo Doc</font>	</a> </td>

	  <td width="142" height="25" class="ColumnTD"><a href='repVencimientos.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=4&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Termino</font>	</a> </td>

	  <td width="142" height="25" class="ColumnTD"><a href='repVencimientos.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=5&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Asunto</font>	</a> </td>
	  <td width="142" height="25" class="ColumnTD"><a href='repVencimientos.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=6&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Dependencia Actual</font>	</a> </td>
    
	  <td width="142" height="25" class="ColumnTD"><a href='repVencimientos.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=7&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Usuario Actual</font>	</a> </td>
	
      <td width="142" height="25" class="ColumnTD"><a href='repVencimientos.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=8&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Usuario Anterior</font>	</a> </td>
    
	  <td width="142" height="25" class="ColumnTD"><a href='repVencimientos.php?<?=$phpsession ?>&encabezado=<?=$encabezado?>&FormRADICADO_Sorting=9&FormRADICADO_Sorted=<?=$form_sorting?>&' target='mainFrame' class="vinculos"><font class="ColumnFONT">Fecha Vencimiento</font>	</a> </td>
  </tr>
<?
  
//-------------------------------
// Build WHERE statement
//-------------------------------

  $ps_RADI_NUME_RADI = get_param("s_RADI_NUME_RADI");
  $ps_desde_RADI_FECH_RADI = get_param("s_desde_RADI_FECH_RADI");
  $ps_hasta_RADI_FECH_RADI = get_param("s_hasta_RADI_FECH_RADI");
  $ps_hora_inicial = get_param("s_hora_inicial");
  $ps_hora_final = get_param("s_hora_final");

  if(strlen($ps_RADI_NUME_RADI))  {

  	 $sWhereFec =  " and r.radi_nume_radi like '%" . $ps_RADI_NUME_RADI . "'" ;
	$sWhere = "	where r.radi_nume_radi like '%2' 
		AND substr(r.radi_nume_radi,5,1) != 9
		AND r.tdoc_codi = td.sgd_tpr_codigo
		AND r.radi_usua_actu=u.usua_codi 
		AND r.radi_depe_actu=u.depe_codi
		AND u.depe_codi= d.depe_codi  
		";
  }
  else  {
	  if(strlen($ps_desde_RADI_FECH_RADI))  {
    	$desde = $ps_desde_RADI_FECH_RADI . " ". "00:00:00";
	    $hasta = $ps_hasta_RADI_FECH_RADI . " ". "23:59:59";
	    $HasParam = true;
	    $sWhereFec =  " and r.fech_vcmto >= to_date('" . $desde . "','yyyy/mm/dd HH24:MI:ss')";
	    $sWhereFec .= " and ";
	    $sWhereFec = $sWhereFec . " r.fech_vcmto <= to_date('" . $hasta . "','yyyy/mm/dd HH24:MI:ss')";
	$sWhere = "	where r.radi_nume_radi not in(select anex_radi_nume from anexos where anex_estado > 2) 
		and r.radi_nume_radi not in(select radi_nume_radi from hist_eventos 
		                            where upper(substr(hist_obse,1,3)) = 'NRR'
									OR upper(substr(hist_obse,1,3)) = 'RSA'
									OR upper(substr(hist_obse,1,2)) = 'CE'
									OR upper(substr(hist_obse,1,3)) = 'TRA')
		and r.radi_nume_radi like '%2' 
		AND substr(r.radi_nume_radi,5,1) != 9
		AND r.tdoc_codi = td.sgd_tpr_codigo
		AND r.radi_usua_actu=u.usua_codi 
		AND r.radi_depe_actu=u.depe_codi
		AND u.depe_codi= d.depe_codi  
";

	  }
  }
  $ps_SELECCION = get_param("s_SELECCION");
  if(strlen($ps_SELECCION))
  {
  	if ($ps_SELECCION == 1) $sSelec = "";
  	if ($ps_SELECCION == 2) $sSelec = " r.radi_depe_actu  = 999 ";	
  	if ($ps_SELECCION == 3) $sSelec = " r.radi_depe_actu  != 999 ";		
  }

  $ps_RADI_DEPE_RADI = get_param("s_RADI_DEPE_ACTU");
  if(is_number($ps_RADI_DEPE_RADI) && strlen($ps_RADI_DEPE_RADI))
    $ps_RADI_DEPE_RADI = tosql($ps_RADI_DEPE_RADI, "Number");
  else 
    $ps_RADI_DEPE_RADI = "";

  if($ps_RADI_DEPE_RADI > 0)
  {
    $HasParam = true;
    $sWhereDep = " and r.radi_depe_actu = $ps_RADI_DEPE_RADI";
  }
  else
  {
//    $bReq = false;
  }

//**Actualizacion de la fecha de vencimiento
	$sSQL = "
	UPDATE radicado 
	set tdoc_vcmto = tdoc_codi 
	where radi_nume_radi like '%2' and tdoc_vcmto IS NULL
	";

    $rs = $db->conn->Execute($sSQL);

 	//$db->query($sSQL);
 
	$sSQL = "
	select r.radi_nume_radi 								AS ENTRADA, 
		to_char(r.radi_fech_radi,'yyyy/mm/dd hh24:mi:ss') 	AS FECHAE, 
		td.sgd_termino_real									AS TERMINO,
		r.radi_fech_radi 									AS F1
	from radicado r, sgd_tpr_tpdcumento td
	where r.radi_nume_radi like '%2'
		AND (r.fech_vcmto is null
		OR r.tdoc_vcmto != r.tdoc_codi)
		AND r.tdoc_codi = td.sgd_tpr_codigo
	";

if ($sSelec != "") $sSelec = " AND " . $sSelec;
//if ($sWhere != "") $sWhere = " AND " . $sWhere;
$sWhereC = $sWhereFec . $sWhere ;

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
// FUNCION PARA CALCULAR LA FECHA DE VENCIMIENTO
//-------------------------------  
function CALC_FECH_VENC($fldTERMINO,$fldF1)
{	
global $db;
	
	$cDiasAdicionL = 0;
	/*$db2 = new DB_Sql();
	$db2->Database = DATABASE_NAME;
	$db2->User     = DATABASE_USER;
	$db2->Password = DATABASE_PASSWORD;
	$db2->Host     = DATABASE_HOST;
	*/
	$termino = $fldTERMINO;
	$fechVenc = $fldF1;
	$cDiasCalen = 1;
	$cDiasHabil = 0;

	while($cDiasHabil < $termino)
  	{
		$isql_busca = "
			select noh_fecha, to_date('" . $fechVenc . "','yyyy/mm/dd hh24:mi:ss') + $cDiasCalen as fvenc 
			from sgd_noh_nohabiles
			where substr(to_char(noh_fecha,'yyyy/mm/dd hh24:mi:ss'),1,10) = substr(to_char(to_date('" . $fechVenc . "','yyyy/mm/dd hh24:mi:ss') + $cDiasCalen,'yyyy/mm/dd hh24:mi:ss'),1,10)
		";

	  $rs2 = $db->conn->Execute($isql_busca);

  //      $db2->query($isql_busca);
  //      $next_record_busca = $db2->next_record();
//-------------------------------
// Process empty recordset
//-------------------------------
        if(!$next_record_busca)  {
			$cDiasHabil++;
		}
		else  {
			$cDiasAdicionL++;
		}
		$cDiasCalen++;
	}
	$cDiasSumar = $cDiasHabil + $cDiasAdicionL;
	$f11=suma_fechas($fechVenc, $cDiasSumar);
	return $f11;
}

function suma_fechas($fecha,$ndias)
{
//      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
//              list($dia,$mes,$a�o)=split("-", $fecha);
     //if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
	list($a�o,$mes,$dia)=explode("-",$fecha);

//	list($a�o,$mes,$dia)=split("-",$fecha);
	if ($mes == 'JAN' or $mes == '01' ) $mesn = 1;
	else
	if ($mes == 'FEB' or $mes == '02') $mesn = 2 ;
	else
	if ($mes == 'MAR' or $mes == '03') $mesn = 3 ;
	else
	if ($mes == 'APR' or $mes == '04') $mesn = 4 ;
	else 
	if ($mes == 'MAY' or $mes == '05') $mesn = 5;
	else
	if ($mes == 'JUN' or $mes == '06') $mesn = 6 ;
	else
	if ($mes == 'JUL' or $mes == '07') $mesn = 7 ;
	else
	if ($mes == 'AUG' or $mes == '08') $mesn = 8 ;
	else
	if ($mes == 'SEP' or $mes == '09') $mesn = 9 ;
	else
	if ($mes == 'OCT' or $mes == '10') $mesn = 10 ;
	else
	if ($mes == 'NOV' or $mes == '11') $mesn = 11 ;
	else
	if ($mes == 'DEC' or $mes == '12') $mesn = 12 ;

    $nueva = mktime(0,0,0, $mesn,$dia,$a�o) + $ndias * 24 * 60 * 60;
    $nuevafecha=date("d/m/Y",$nueva);
    return ($nuevafecha);  
}

//-------------------------------
// Execute SQL statement
//-------------------------------
$sSQL .= $sWhereFec;

  $rs = $db->conn->Execute($sSQL);

 // $db->query($sSQL);
  //$next_record = $db->next_record();
//-------------------------------
// Process empty recordset
//-------------------------------
 if(!$rs->fields["ENTRADA"] != "")

  {
?>
     <tr>
      <td colspan="5" class="DataTD"><font class="DataFONT">No records  </font></td>
     </tr>
  </table>
<?

//    return;
  }
  $iRecordsPerPage = 2000;
  $iCounter = 0;

//-------------------------------
// Display grid based on recordset
//-------------------------------
  while(!$rs->EOF && $iCounter < $iRecordsPerPage)
  {
    $fldRADI_NUME_RADI = $rs->fields["ENTRADA"];
    $fldRADI_FECH_RADI = $rs->fields["FECHAE"];
	$fldTERMINO        = $rs->fields["TERMINO"];		
	$fldF1         	   = $rs->fields["F1"];		
    $rs->MoveNext();    
	$fecVenEval = CALC_FECH_VENC($fldTERMINO,$fldF1);
/*	
	$db3 = new DB_Sql();
	$db3->Database = DATABASE_NAME;
	$db3->User     = DATABASE_USER;
	$db3->Password = DATABASE_PASSWORD;
	$db3->Host     = DATABASE_HOST;
	*/
//	$uSQL = "UPDATE RADICADO SET FECH_VCMTO =  to_date('" . $fecVenEval . "','yyyy/mm/dd HH24:MI:ss')" . "WHERE radi_nume_radi = $fldRADI_NUME_RADI ";
//	$db3->query($uSQL);

  //$rs3 = $db->conn->Execute($uSQL);

    $iCounter++;
  }

	$sSQL = "
	select r.radi_nume_radi 								AS entrada, 
		to_char(r.radi_fech_radi,'yyyy/mm/dd hh24:mi:ss') 	AS fechae, 
		td.sgd_tpr_descrip 									AS tipo, 
		td.sgd_termino_real									AS termino,
		r.ra_asun 											AS asunto, 
		d.depe_nomb 										AS depe_actu, 
		u.usua_nomb 										AS nomb_actu, 
		r.radi_usu_ante 									AS usant,
		r.fech_vcmto										AS fech_vcmto,
		r.RADI_PATH 										AS R_RADI_PATH
	from radicado r, sgd_tpr_tpdcumento td, usuario u, dependencia d
	";

	$sWhere .=	$sWhereFec . $sWhereDep;
	$sSQL .= $sWhere . $sOrder;

	$sSQLCount = "Select count(*) as Total from radicado r, sgd_tpr_tpdcumento td, usuario u, dependencia d " . $sWhere;
	//$db->query($sSQLCount);

	$rs = $db->conn->Execute($sSQLCount);

	//$next_record = $db->next_record();
	$fldTotal = $rs->fields["TOTAL"];

	$rs = $db->conn->Execute($sSQL);

	//$db->query($sSQL);
 // 	$next_record = $db->next_record();
//-------------------------------
// Process empty recordset
//-------------------------------
  if(!$fldTotal > 0)
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
// Process the HTML controls
//-------------------------------
	 $iCounter = 0;

 while(!$rs->EOF && $iCounter < $iRecordsPerPage)
  	{
	    $fldRADI_NUME_RADI = $rs->fields["ENTRADA"];
    	$fldRADI_FECH_RADI = $rs->fields["FECHAE"];
	    $fldRADI_SALIDA    = $rs->fields["RSALIDA"];	
	    $fldRADI_FECH_SALI = $rs->fields["FECHAS"];
	    $fldTIPO_DOC       = $rs->fields["TIPO"];
        $fldTERMINO        = $rs->fields["TERMINO"];		
	    $fldRA_ASUN        = $rs->fields["ASUNTO"];
	    $fldSALIDA         = $rs->fields["SALIDA"];	
	    $fldDEPE_NOMB      = $rs->fields["DEPE_ACTU"];
	    $fldUSUA_ACTUAL    = $rs->fields["NOMB_ACTU"];
		$fldUSUA_ANTER     = $rs->fields["USANT"]; 
		$fldDIASR          = $rs->fields["DIASR"]; 
		$fldRADI_DEPE_ACTU = $rs->fields["RADI_DEPE_ACTU"];
		$fldFECH_VCMTO     = $rs->fields["FECH_VCMTO"];	
		$fldTERMINO        = $rs->fields["TERMINO"];		
		$fldF1         	   = $rs->fields["F1"];		
	    $fldRADI_PATH      = $rs->fields["R_RADI_PATH"];
		  $rs->MoveNext();    
//	    $next_record = $db->next_record();
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
      <?= tohtml($fldTERMINO) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldRA_ASUN) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldDEPE_NOMB) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldUSUA_ACTUAL) ?>&nbsp;</font></td>
       <td class="DataTD"><font class="DataFONT">
      <?= tohtml($fldUSUA_ANTER) ?>&nbsp;</font></td>
      <td class="DataTD"><font class="DataFONT">
      <? echo $fldFECH_VCMTO; ?>&nbsp;</font></td>
	  </tr><?
    	$iCounter++;
  	}
  }
//-------------------------------

// Finish form processing
//-------------------------------
 ?>
    </table>
  <?
}
?>
</div></body>
</html>
