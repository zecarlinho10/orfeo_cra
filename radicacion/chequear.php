<?php
/**
 * @module chequear
 *
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright

 SIIM2 Models are the data definition of SIIM2 Information System
 Copyright (C) 2013 Infometrika Ltda.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

session_start();

$ruta_raiz = "..";
if (!$_SESSION['dependencia'])
header ("Location: $ruta_raiz/cerrar_session.php");

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;
$krd         = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc    = $_SESSION["usua_doc"];
$codusuario  = $_SESSION["codusuario"];
$tpNumRad    = $_SESSION["tpNumRad"];
$tpPerRad    = $_SESSION["tpPerRad"];
$tpDescRad   = $_SESSION["tpDescRad"];
$tpDepeRad   = $_SESSION["tpDepeRad"];
$radMail     = $_GET["radMail"];
$tip3Nombre=$_SESSION["tip3Nombre"];
$nombreTp1 = $tip3Nombre[1][$ent];
$nombreTp2 = $tip3Nombre[2][$ent];
$nombreTp3 = $tip3Nombre[3][$ent];
include_once "../include/db/ConnectionHandler.php";

$tipoMed = $_SESSION['tipoMedio'];

$db = new ConnectionHandler("$ruta_raiz");
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

$fechah = date("dmy") . "_" . date("hms");
if($buscar_por_radicado) $buscar_por_radicado = trim($buscar_por_radicado);

if($faxPath) {
	$iSql= " SELECT SGD_RFAX_FAX, USUA_LOGIN, SGD_RFAX_FECHRADI
		FROM   SGD_RFAX_RESERVAFAX
		WHERE SGD_RFAX_FAX='$faxPath'
		AND USUA_LOGIN='$krd'
		";
	$rs = $db->conn->query($iSql);
	$krdTmp = $rs->fields("USUA_LOGIN");
	$fechRadicacion = $rs->fields("SGD_RFAX_FECHRADI");

	if($krdTmp!=$krd) {
		if(!$krdTmp) {
		}
		{
			if(trim($krdTmp)!="")
			{
				?>
					<hr><font color=red>POR FAVOR VERIFIQUE CON <?=$krdTmp?> QUE EL FAX QUE TOMO NO SEA RADICADO POR SEGUNDA VEZ, YA OTRA PERSONA (<?=$krdTmp?>) lo habia radicado</font><hr>
					<?
			}
			if($fechRadicacion)
			{
				?>
					<hr></font color=red>CUIDADO !!!!! ESTE FAX YA APARECE CON FECHA DE RADICACION !!!!!!! <?=$krdTmp?> CONSULTE
					CON (<?=$krdTmp?>) FECHA DE RADICACION <?=$fechRadicacion?></font><hr>
					<?
			}
		}
		$codigoFax = substr($faxPath,3,9);
		$iSql= " insert into SGD_RFAX_RESERVAFAX
			( sgd_rfax_codigo
			  , sgd_rfax_fax
			  , usua_login
			  , sgd_rfax_fech
			) values
			(
			 $codigoFax
			 ,'$faxPath'
			 ,'$krd'
			 ,".$db->conn->OffsetDate(0,$db->conn->sysTimeStamp)."
			)
			";
		$db->conn->query($iSql);
	}
}

/**
 * Radicacion de eMails
 * @autor Orlando Burgos
 * @año 2008
 * @vesrion OrfeoGpl 3.7
 */

if( !$_SESSION['tipoMedio']){
	$tipoMedio = $_GET['tipoMedio'];
	if(!$tipoMedio) $_POST['tipoMedio'];
}

if ($tipoMedio=='eMail' or $_SESSION['tipoMedio']=='eMail'){
	if($_GET['eMailPid'])
	{
		$eMailAmp=$_GET['eMailAmp'];
		$eMailMid=$_GET['eMailMid'];
		$eMailPid=$_GET['eMailPid'];
		$_SESSION['eMailPid'] = $_GET['eMailPid'];
		$_SESSION['eMailMid'] = $_GET['eMailMid'];
	}else{
		$eMailPid = $_SESSION['eMailPid'];
		$eMailMid = $_SESSION['eMailMid'];
		$eMailAmp = $_SESSION['eMailAmp'];
	}

	$fileeMailAtach=$_GET['fileeMailAtach'];

}


$ano_ini = date("Y");
$mes_ini = substr("00".(date("m")-1),-2);
if ($mes_ini=="00"){
	$ano_ini=$ano_ini-1; 
	$mes_ini="12";
}
$dia_ini = date("d");
if(!$fecha_ini) $fecha_ini = "$ano_ini/$mes_ini/$dia_ini";
$fecha_busq = date("Y/m/d") ;
if(!$fecha_fin) $fecha_fin = $fecha_busq;

?>
<html>
<head>
<?php if (!isset($radMail)) include_once("$ruta_raiz/htmlheader.inc.php") ?>
<?php include_once("$ruta_raiz/js/funtionImage.php")?>
<link rel="stylesheet" href="../tooltips/jquery-ui.css">
<script src="../tooltips/jquery-ui.js"></script> 
<link rel="stylesheet" href="../tooltips/tool.css">
<script src="../tooltips/tool.js"></script>
</head>

<body topmargin="0" bgcolor="#FFFFFF">

<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
<h1 class="page-title txt-color-blueDark">
Radicaci&oacute;n previa <?=$tRadicacionDesc ?> (<?=$dependencia ?>)
</h1>
</div>

<div class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
<div id="content" style="opacity: 1;">
<form name="formulario" method="post"  class="smart-form"  action='chequear.php?<?=session_name()."=".session_id()?>&krd=<?=$krd?>&dependencia=<?=$dependencia?>&krd=<?=$krd?>&faxPath=<?=$faxPath?>'>
<input type=hidden name=ent value='<?=$ent?>'>
<?php include "formRadPrevia.php"; ?>
</form>
<?
if(!$busq)  $busq = 1;
if(!$tip_rem){$tip_rem=3;}
if($Submit)
{
	if($busq ==1){$cuentai = $buscar_por;}
	if($busq ==2){$noradicado = $buscar_por;}
	if($busq ==3){$documento = $buscar_por;}
	if($busq ==4){$nombres = $buscar_por;}

}
$query = "select SGD_TRAD_CODIGO
, SGD_TRAD_DESCR from sgd_trad_tiporad
where SGD_TRAD_CODIGO=$ent";

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$rs=$db->conn->query($query);
$db->conn->debug=true;
$tRadicacionDesc = " - " .strtoupper($rs->fields["SGD_TRAD_DESCR"]);
$datos_enviar = session_name()."=".trim(session_id())."&krd=$krd&fechah=$fechah&faxPath=$faxPath";
?>
<form action='NEW.php?<?=session_name()."=".trim(session_id())?>&dependencia=<?=$dependencia?>&faxPath=<?=$faxPath?>' class="smart-form" method="post" name="form1">
<fieldset>
<input type='hidden' name='<?=session_name()?>' value='<?=session_id()?>'>
<input type=hidden name=ent value='<?=$ent?>'>
<tr>
<td colspan="3"> <div align="center">
<?php if($Submit) { ?>
	<div class="widget-body">
		<div class="row">
		<h1>RADICAR COMO...</h1>
		<br />
		<p> Selecciona, una opci&oacute;n para copiar los datos y crear un anexo apartir de uno anterior ó crea uno nuevo.  </p>
		<br />
		<section class="col col-4">
		<input class="btn btn-success btn-sm" name="rad1" type=submit value="Nuevo (Copia Datos)">
		</section>
		<section class="col col-4">
		<input class="btn btn-success btn-sm" name="rad0" type=submit value="Como Anexo">
		</section>
		<section class="col col-4">
		<input class="btn btn-success btn-sm" name="rad2" type=submit value="Asociado">
		</section>
		</div>
		</div>
		<?php
}
$accion="&accion=buscar";
$variables = "&pnom=".strtoupper($pnom)."&papl=".$papl."$sapl=".$sapl."&numdoc=".$numdoc.$accion;
$target="_parent";
$hoy = date('d/m/Y');
$hace_catorce_dias = date ('d/m/Y', mktime (0,0,0,date('m'),date('d')-14,date('Y')));
$where ="   ";
// **** limite de Fecha
$fecha_ini = mktime(00,00,00,substr($fecha_ini,5,2),substr($fecha_ini,8,2),substr($fecha_ini,0,4));
$fecha_fin = mktime(23,59,59,substr($fecha_fin,5,2),substr($fecha_fin,8,2),substr($fecha_fin,0,4));
$where_fecha = " and (a.radi_fech_radi >= ". $db->conn->DBTimeStamp($fecha_ini) ." and a.radi_fech_radi <= ". $db->conn->DBTimeStamp($fecha_fin).") " ;
$dato1=1;
$where_general  = " $where_fecha ";
if(!$and_cuentai){ $and_cuentai = " and ";}else { $and_cuentai = " or ";}
if(!$and_radicado){ $and_radicado = " and ";}else { $and_radicado = " or ";}
if(!$and_exp){ $and_exp = " and ";}else { $and_exp = " or ";}
if(!$and_doc){ $and_doc = " and ";}else { $and_doc = " or ";}
if(!$and_nombres){ $and_nombres = " and ";}else { $and_nombres = " or ";}
if($buscar_por_cuentai){ $where_general .= " $and_cuentai a.radi_cuentai like '%$buscar_por_cuentai%' ";}
if($buscar_por_radicado){ $where_general .= " $and_radicado a.radi_nume_radi = $buscar_por_radicado ";}
if($buscar_por_dep_rad){ $where_general .= " $and a.radi_depe_radi in($buscar_por_dep_rad) ";}
if($buscar_por_doc){
	$where_ciu .= " $and_doc d.SGD_DIR_DOC = '$buscar_por_doc'";
}
if($buscar_por_exp){
	$where_ciu .= " $and_exp  g.SGD_EXP_NUMERO LIKE '%$buscar_por_exp%' ";
}
$nombres = strtoupper(trim($buscar_por_nombres));
$nombres= ConnectionHandler::fullUpper($nombres);

if(trim($nombres)) {	$array_nombre = explode(" ",$nombres);
	$strCamposConcat = $db->conn->Concat("UPPER(d.SGD_DIR_NOMREMDES)","UPPER(d.SGD_DIR_NOMBRE)");
	for($i=0;$i<count($array_nombre);$i++)
	{	$nombres = trim($array_nombre[$i]);
		$where_ciu .= " and $strCamposConcat LIKE '%$nombres%' ";
	}

}
$query_direcciones = "";
if(($buscar_por_doc) or trim($nombres))
{
	$query_direcciones = " and a.radi_nume_radi in ( select d2.radi_nume_radi from sgd_dir_drecciones d2 where a.radi_nume_Radi = d2.radi_nume_radi";
	$query_direcciones .= " $where_ciu )";

}


$dato=2;
echo "</table>";
 $estoybuscando = 0;
if($primera!=1 and $Submit=="Buscar" and 
 ($buscar_por_cuentai or $buscar_por_radicado or $buscar_por_nombres or $buscar_por_doc or $buscar_por_dep_rad or $buscar_por_exp )) {
 $estoybuscando = 1; 
	$db = new ConnectionHandler("..");
	$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
	$sqlFecha = $db->conn->SQLDate("d-m-Y H:i","a.RADI_FECH_RADI");

	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

	switch ($iBusqueda) {
		case 1:
			$query = $query1;
			$eTitulo = "<table class=\"table table-bordered table-striped\"><tr><td></td></tr></table><table class=\"table table-bordered table-striped\" width=100%><tr class=eTitulo><td><span class=tpar>Resultados B&uacute;squeda por Ciudadanos</td></tr></table>";
			break;
		case 2:
			$query = $query2;
			$eTitulo ="<table class=\"table table-bordered table-striped\" ><tr><td><span class=tpar>Resultados B&uacute;squeda por Otras Empresas</td></tr></table>";
			break;
		case 3:
			$query = $query3;
			$eTitulo ="<table class=\"table table-bordered table-striped\" ><tr><td><span class=tpar>Resultados B&uacute;squeda por Esp</td></tr></table>";
			break;
		case 4:
			$query = $query4;
			$eTitulo ="<table class=\"table table-bordered table-striped\" ><tr><td><span class=tpar>Resultados B&uacute;squeda  por Funcionarios</td></tr></table>";
			break;
	}


	$tpBuscarSel = "";
	if($tpBusqueda1) {
		echo $eTitulo;
		$tpBuscarSel = "ok";
		$whereTrd = "1";
	}

	if($tpBusqueda2) {
		echo $eTitulo;
		$tpBuscarSel = "ok";
		if($whereTrd) $whereTrd .= ",";
		$whereTrd .= "2";
	}

	if($tpBusqueda3) {
		echo $eTitulo;
		$tpBuscarSel = "ok";
		if($whereTrd) $whereTrd .= ",";
		$whereTrd .= "3";
	}
	if($tpBusqueda4) {
		echo $eTitulo;
		$tpBuscarSel = "ok";
		if($whereTrd) $whereTrd .= ",";
		$whereTrd .= "4";
	}
	$whereTrd = "";



	include "$ruta_raiz/include/query/queryChequear.php";
	$query = $query1;
	$rsCheck=$db->query($query);


	echo "<label1>";
	$varjh=1;
	if (!$rsCheck and $tpBuscarSel=="ok"){
		echo "<center><img src='img_alerta_1.gif' alt='Esta alerta indica que no se encontraron los datos que buscar e Intente buscar con otro nombre, apellido o No. IDD'>";
		echo "<center><font size='3' face='arial' class='etextomenu'><b>No se encontraron datos con las caracteristicas solicitadas</b></font>";
	}else {
		?>
			<table class="table table-bordered table-striped">
			<?
			if($tpBuscarSel=="ok"){
				?>
					<tr align="center" class="titulos2">
					<td class="titulos2" align="left">
					<input name='radicadopadre' type='radio' value='' title='Radicado No <?=$nume_radi ?>' class="ecajasfecha">
					NO TIENE PADRE</td>
					</tr>
					<?
			}
		?>
			<?php
			$cent=0;
		$varjh=2;
		$radicado_anterior=0;

		$ruta_raiz = "..";

		include_once "$ruta_raiz/tx/verLinkArchivo.php";

		$verLinkArchivo = new verLinkArchivo($db);


		while(!$rsCheck->EOF)
		{
			$nombret_us1="";
			$dignatario1="";
			$nume_radi = "";
			$nombret_us1= "";
			$nombret_us2= "";
			$nombret_us3= "";
			$dato= "";
			$fecha= "";
			$cuentai= "";
			$asociado= "";
			$asunto= "";
			$cent = 0;

			//$nume_radi = ora_getcolumn($cursor,2);
			$nume_radi = $rsCheck->fields["RADI_NUME_RADI"];

			$nurad = $nume_radi;
			$verrad = $nume_radi;
			$verradicado = $nume_radi;
			$nomb = $rsCheck->fields['SGD_CIU_NOMBRE'];
			$prim_apel = $rsCheck->fields['SGD_CIU_APELL1'];
			$segu_apel = $rsCheck->fields['SGD_CIU_APELL2'];
			$nume_deri = $rsCheck->fields['RADI_NUME_DERI'];
			$imagenf = $rsCheck->fields['RADI_PATH'];
			$hoj=$rsCheck->fields['RADI_NUME_HOJA'];
			$asunto=$rsCheck->fields['RA_ASUN'];
			$fecha=$rsCheck->fields['RADI_FECH_RADI'];
			$derivado=$rsCheck->fields['RADI_NUME_DERI'];
			$tipoderivado=$rsCheck->fields['RADI_TIPO_DERI'];
			$dir_tipo=$rsCheck->fields['SGD_DIR_TIPO'];
			$cuentai=$rsCheck->fields['RADI_CUENTAI'];
			$nume_exp=$rsCheck->fields['SGD_EXP_NUMERO'];
			$ruta_raiz = "..";
			$no_tipo = "true";
			//echo "<hr>Buscando Radicadon NO $verrad";
			$resulVali = $verLinkArchivo->valPermisoRadi($nume_radi);

			$valImg = $resulVali['verImg'];


			include "../ver_datosrad.php";
			if($dir_tipo==1)
			{
				$dir_tipo_desc = $nombreTp1;
				//$dignatario1 = ora_getcolumn($cursor,20);
				$dignatario1=$rsCheck->fields['SGD_DIR_NOMBRE'];
			}
			if($dir_tipo==2)
			{
				$dir_tipo_desc = $nombreTp2;
				//$dignatario2 = ora_getcolumn($cursor,20);

				$dignatario2=$rsCheck->fields['SGD_DIR_NOMBRE'];
			}
			if($dir_tipo==3)
			{
				$dir_tipo_desc = $nombreTp3;
				//$dignatario3 = ora_getcolumn($cursor,20);
				$dignatario3=$rsCheck->fields['SGD_DIR_NOMBRE'];
			}
			if (trim($derivado)){
				switch ($tipoderivado) {
					case 0:
						$asociado = "Anx > $derivado";
						break;
					case 1:
						$asociado = "";
						break;
					case 2:
						$asociado = "Asc > $derivado";
						break;
				}
			}

			if(trim($imagenf)==""){
				$dato="No Disp";
			}elseif($valImg == "SI"){
				$dato="<b><a class=\"vinculos\" href=\"#2\" onclick=\"funlinkArchivo('$nume_radi','$ruta_raiz');\"> Ver Imagen </a>";
				//$dato="<a href='../bodega$imagenf' target='otraventana'> Ver Imagen</a>";
			}else{
				$dato="No tiene permiso para acceder a la imagen";

			}

			if($radicado_anterior!=$nume_radi)
			{
				if($iii==1){
					$fila = "<tr class='tparr'>";
					$iii=2;
				}else{
					$fila = "<tr class='timparr'>";
					$iii=1;
				}

				?>
					<tr class='borde_tab'><?=$fila ?>
					<td class="listado2">		<table  class="table table-bordered table-striped">
					<tr>
					<td width="18%" class="titulos5"><input name='radicadopadre' type='radio' value='<?=$nume_radi ?>' title='Radicado No <?=$nume_radi ?>' >
					Radicado </td>
					<td width="39%" class=listado5><?=$nume_radi ?>
					<?
					if($dependencia==4240){
						?>
							<a href='<?=$ruta_raiz?>/verradicado.php?verrad=<?=$nume_radi?>&<?=session_name()?>=<?=session_id()?>&menu_ver_tmp=1'> (Modificar)</a>
							<?
					}
				if($nume_exp)
				{
					?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Expediente
						<?=$nume_exp ?>
						<?
				}
				?>
					</td>
					<td width="12%" class="titulos5">Fecha Rad</td>
					<td width="31%" class="listado5"><?=$fecha ?></td>
					</tr>
					<td class="titulos5" ><?=$nombreTp1?></td>
					<td class=listado5 >
					<?
					if( $nomRemDes["x1"]) $nombret_us1 =  $nomRemDes["x1"] ." (". $nomOtro["x1"].")";
				if( $nomRemDes["x2"]) $nombret_us2 =  $nomRemDes["x2"];
				?>
					<?=$nombret_us1?></td>
					<td class="titulos5"><span class="tituloListado">Referencia</span> </td>
					<td class="listado5"><?=$cuentai?></td>
					<tr>
					<td class="titulos5" ><?=$nombreTp2?></td>
					<td class=listado5 ><?=$nombret_us2 ?></td>
					<td class="titulos5"><span class="tituloListado">Doc Asociado</span> </td>
					<td class="listado5"><?=$asociado?></td>
					</tr>
					<tr>
					<td class="titulos5"><?=$nombreTp3?></td>
					<td class=listado5 ><?=$nombret_us3." - ".$dignatario3 ?>
					<?=$dato ?></td>
					<td class="titulos5">Asunto</td>
					<td class="listado5"><?=$asunto ?></td>
					</tr>
					</table></td>
					</tr>
					<?
			}
			$cent ++;
			$radicado_anterior=$nume_radi;
			$rsCheck->MoveNext();
		}
		
		if ($cent == 0 ){
			?>
<center><table class="table table-bordered table-striped" ></table><table class="table table-bordered table-striped" width="100%"><tr><td class="titulosError"><center><font color="red">¡    No se encontraron resultados!.</font> </center></td></tr></table></center>
			<? exit;
		}
	}
}else {

	if($Submit)
	{

		?>
			<center><table class="table table-bordered table-striped" ></table><table class="table table-bordered table-striped" width="100%"><tr><td class="titulosError"><center><font color="red">¡Debe digitar un Dato para realizar la b&uacute;squeda!</font> </center></td></tr></table></center>
			<?
			$nobuscar = "No Buscar";
	}
}
if(trim($imagenf)=="")
{
	$dato=" ";
}
elseif($valImg == "SI"){

	$dato="<b><a class=\"vinculos\" href=\"#2\" onclick=\"funlinkArchivo('$nume_radi','$ruta_raiz');\"> Ver Imagen </a>";


}else{

	$dato="No tiene permiso para acceder a la imagen";

}
if($varjh==2)
{
	?>
		<tr class='titulos2'>
		<td class="titulos">
		<input name='titulos' type='radio' value='' title='Radicado No <?=$nume_radi ?>' class="ecajasfecha">
		NO 	TIENE PADRE</td>
		</tr>
		<?
}
?>
</table>
<?php
$cent ++;
echo"<input type='hidden' name='usr' value='$usr'>";
echo"<input type='hidden' name='ent' value='$ent'>";
echo"<input type='hidden' name='depende' value='$depende'>";
echo"<input type='hidden' name='contra' value='$contra'>";
echo"<input type='hidden' name='pnom' value='$pnom'>";
echo"<input type='hidden' name='sapl' value='$sapl'>";
echo"<input type='hidden' name='papl' value='$papl'>";
echo"<input type='hidden' name='numdoc' value='$numdoc'>";
echo"<input type='hidden' name='tip_doc' value='$tip_doc'>";
echo"<input type='hidden' name='tip_rem' value='$tip_rem'>";
echo"<input type='hidden' name='codusuario' value='$codusuario'>";
echo"<input type='hidden' name='pcodi' value='$pcodi'>";
echo"<input type='hidden' name='hoj' value='$hoj'>";
?>
<br>
<br>
</div>
</td>
<div align="center">
<?php echo "";?>
<br>
	<?php
if($Submit and !$nobuscar)
	?>
	<br>
	</div>
	<?php
	echo "<input type=hidden name=drde value=$drde>";
	echo "<input type=hidden name=krd value=$krd>";
	echo " ";
	?>

	</fieldset>
	</form>
	</div>
	</div>
	<!-- JARVIS WIDGETS -->
	<script type="text/javascript">
	$(document).ready(function() {
			// START AND FINISH DATE
			$('#startdate').datepicker({
dateFormat : 'yy/mm/dd',
prevText : '<i class="fa fa-chevron-left"></i>',
nextText : '<i class="fa fa-chevron-right"></i>',
onSelect : function(selectedDate) {
$('#startdate').datepicker('option', 'maxDate', selectedDate);
}
});

			$('#finishdate').datepicker({
dateFormat : 'yy/mm/dd',
prevText : '<i class="fa fa-chevron-left"></i>',
nextText : '<i class="fa fa-chevron-right"></i>',
onSelect : function(selectedDate) {
$('#finishdate').datepicker('option', 'minDate', selectedDate);
}
});
			});
</script>
</body>
</html>
