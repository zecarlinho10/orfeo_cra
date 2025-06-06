<?php
session_start ();
$ruta_raiz = "..";
if (! $_SESSION ['dependencia'])
	header ( "Location: $ruta_raiz/cerrar_session.php" );

if (! isset ( $_SESSION ['dependencia'] ))
	include "../rec_session.php";

foreach ( $_GET as $key => $valor )
	${$key} = $valor;
foreach ( $_POST as $key => $valor )
	${$key} = $valor;

$nomcarpeta = $_GET ["nomcarpeta"];
if ($_GET ["tipo_carp"])
	$tipo_carp = $_GET ["tipo_carp"];

define ( 'ADODB_ASSOC_CASE', 1 );

$verrad = "";
$krd = $_SESSION ["krd"];
$dependencia = $_SESSION ["dependencia"];
$usua_doc = $_SESSION ["usua_doc"];
$codusuario = $_SESSION ["codusuario"];
$tip3Nombre = $_SESSION ["tip3Nombre"];
$tip3desc = $_SESSION ["tip3desc"];
$tip3img = $_SESSION ["tip3img"];
require_once ("$ruta_raiz/include/db/ConnectionHandler.php");
include_once "$ruta_raiz/class_control/GrupoMasiva.php";
require_once ("$ruta_raiz/include/combos.php");
require_once ("$ruta_raiz/class_control/Dependencia.php");

if (! $db)
	$db = new ConnectionHandler ( $ruta_raiz );
$db->conn->SetFetchMode ( ADODB_FETCH_ASSOC );

$grupoMas = new GrupoMasiva ( $db );
$objDepe = new Dependencia ( $db );

$sqlChar = $db->conn->SQLDate ( "Y", "SGD_RENV_FECH" );
$fecha_mes = date ( 'Y' );

$isql = "SELECT 
            SGD_FENV_CODIGO AS CODIGO, 
           MAX(cast(SGD_RENV_PLANILLA AS NUMERIC)) AS SECUENCIA
        FROM 
            SGD_RENV_REGENVIO
        WHERE 
            DEPE_CODI=$dependencia 
            AND $sqlChar = '$fecha_mes'
            and SGD_RENV_PLANILLA is not null
            and sgd_renv_planilla != ''
        GROUP BY SGD_FENV_CODIGO";

$rs4 = $db->conn->query ( $isql );
$salida = "var arrayplan = new Array();\n";

while ( ! $rs4->EOF ) {
	$sec = empty ( $rs4->fields ["SECUENCIA"] ) ? 0 : $rs4->fields ["SECUENCIA"];
	$cod = $rs4->fields ["CODIGO"];
	$salida .= "arrayplan[" . $cod . "] = " . $sec . ";\n";
	$rs4->MoveNext ();
}
echo "<script>" . $salida . "</script>\n";
?>
<script>
function myFunction(valor)
{
    var x= document.getElementById('planilla');
    if(arrayplan[valor] !== undefined ) {
        var n= arrayplan[valor]; 
        x.value = n + 1 ;
    }else{
        x.value = 1 ;
    }
}
</script>
<?php

$objDepe->Dependencia_codigo ( $dep_sel );
$codTerrEnvio = $objDepe->getDepe_codi_territorial ();
$terrEnvio = $objDepe->dependenciaArr ( $codTerrEnvio );
$grupoMas->obtenerGrupo ( $dep_sel, $radGrupo, '' );
// var arreglo que almacena los radicados inicial y final del grupo
$radsLimite = $grupoMas->getRadsLimite ();
// var arreglo que almacena el numero de radicados nacionales y locales
$numRadicados = $grupoMas->getNumNacionalesLocales ( $terrEnvio ['cont_codi'], $terrEnvio ['pais_codi'], $terrEnvio ['dpto_codi'], $terrEnvio ['muni_codi'] );
?>
<html>
<head>
<title>Envio Masiva</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Bootstrap core CSS -->
  <?php include_once $ruta_raiz."/htmlheader.inc.php"; ?>
<link href="../estilos/orfeo.css" rel="stylesheet" type="text/css">
<script src="../js/formchek.js"></script>
<script>

function back1()
{
	history.go(-1);
}

function enviar()
{
	sw=0;

	if (document.form1.empresa_envio.value!='null' && isInteger(document.form1.envio_peso.value))
	{	sw=1;	}
	else
	{	alert ('Debe suministrar los datos de la empresa de envio y el peso de los documentos');
		return;
	}

	if (document.form1.observaciones.value.length>1 && document.form1.planilla.value.length>0)
	{
		sw=1;
	}
	else
	{
		alert ('Debe suministrar las observaciones y el numero de planilla');
		return;
	}
	document.form1.submit();	
}
</script>
<style type="text/css">
<!--
.style1 {
	color: #CC0000
}
-->
</style>
</head>
<body>
	<span class=etexto>
		<center></center>
	</span>
	<form name="form1" method="POST"
		action="envio_masiva_registro.php?<?=session_name()."=".session_id()."&krd=$krd" ?>"
		class="borde_tab">
		<div class="row text-center">
			<div class="col-sm-2 col-md-2 col-lg-4"></div>
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<table width="100%"
					class="table table-striped table-bordered table-hover dataTable no-footer smart-form">
					<tr>
						<td class="titulos5" align="center"><b>ENVIO DE DOCUMENTOS -
								RADICACION MASIVA</b></td>
					</tr>
				</table>
				<table
					class="table table-striped table-bordered table-hover dataTable no-footer smart-form"
					cellspacing="5">
					<tr class=titulos2 align="center">
						<td width="26%">GRUPO</td>
						<td width="35%">EMPRESA DE ENVIO</td>
						<td width="13%">PESO(Gr) C/U</td>
						<td width="26%">U.MEDIDA</td>
					</tr>
					<tr class=listado2>
						<td height="26" align="center" width="26%"> 
          <? echo ($radsLimite[0]."<br>".$radsLimite[1]);?>
          <input type="hidden" name="rangoini"
							value="<?=$radsLimite[0]?>"> <input type="hidden" name="rangofin"
							value="<?=$radsLimite[1]?>">
						</td>
						<td height="26" align="center" width="35%"><select
							name=empresa_envio id=empresa_envio class=select
							onChange="myFunction(this.value);"
							onClick=" if (this.value!='null'&& envio_peso.value.length>1) calcular_precio('empresa_envio','envio_peso','valor_gr');">
								<option selected value="null">--- empresa de envio ---</option>
		<?
		$a = new combo ( $db );
		$s = "select SGD_FENV_CODIGO as COD,SGD_FENV_DESCRIP as DES FROM SGD_FENV_FRMENVIO WHERE SGD_FENV_ESTADO=1 ";
		$r = "COD";
		$t = "DES";
		$v = $estado;
		$sim = 0;
		$a->conectar ( $s, $r, $t, $v, $sim );
		?>
      </select></td>
						<td width="13%"><input type=text class="tex_area" name=envio_peso
							id=envio_peso size=6
							onChange="calcular_precio('empresa_envio','envio_peso','valor_gr');">
						</td>
						<TD width="26%"><input type=text name=valor_gr id=valor_gr
							class=tex_area size=30 disabled></td>
					</tr>
				</table>
				<br />
				<table border=0 width=33%>
					<tr class="titulos2" align="center">
						<td valign="top" width="12%">DESTINO</td>
						<td valign="top" width="17%">DOCUMENTOS</td>
						<td valign="top" width="34%">VALOR C/U</td>
						<td valign="top" width="28%">VALOR TOTAL</td>
						<td valign="top" rowspan="6" width="9%"><input type=button
							class="botones" name=Calcular_button id=Calcular_button
							value=Calcular
							onClick="calcular_precio('empresa_envio','envio_peso','valor_gr');">
						</td>
					</tr>
					<tr>
						<td height="21" align="center" valign="top" width="12%"
							class="titulos5">Local</td>
						<td align="center" valign="top" width="17%" class='listado2'>
							<center><?=$numRadicados["local"]?></center> <input type="hidden"
							name="local" value="<?=$numRadicados["local"]?>" id=local>
						</td>
						<td valign="midle" width="34%" class='listado2'><input type=text
							class='tex_area' name=valor_unit_local id=valor_unit_local
							readonly></Td>
						<td width="28%" class='listado2'><input type=text class='tex_area'
							name=valor_total_local id=valor_total_local readonly></Td>
					</tr>
					<tr>
						<td height="21" align="center" valign="top" width="12%"
							class="titulos5">Nacional</td>
						<td align="center" valign="top" width="17%" class='listado2'>
							<center><?=$numRadicados["nacional"]?></center> <input
							type="hidden" name="nacional"
							value="<?=$numRadicados["nacional"]?>" id=nacional>
						</td>
						<td valign="midle" width="34%" class='listado2'><input type=text
							class='tex_area' name=valor_unit_nacional id=valor_unit_nacional
							readonly></Td>
						<td width="28%" class='listado2'><input type=text class='tex_area'
							name=valor_total_nacional id=valor_total_nacional readonly></Td>
					</tr>
					<tr>
						<td height="21" align="center" valign="top" width="12%"
							class="titulos5">Int. G1</td>
						<td align="center" valign="top" width="17%" class='listado2'>
							<center><?=$numRadicados["grupo1"]?></center> <input
							type="hidden" name="grupo1" value="<?=$numRadicados["grupo1"]?>"
							id=grupo1>
						</td>
						<td valign="midle" width="34%" class='listado2'><input type=text
							class='tex_area' name=valor_unit_grupo1 id=valor_unit_grupo1
							readonly></Td>
						<td width="28%" class='listado2'><input type=text class='tex_area'
							name=valor_total_grupo1 id=valor_total_grupo1 readonly></Td>
					</tr>
					<tr>
						<td height="21" align="center" valign="top" width="12%"
							class="titulos5">Int. G2</td>
						<td align="center" valign="top" width="17%" class='listado2'>
							<center><?=$numRadicados["grupo2"]?></center> <input
							type="hidden" name="grupo2" value="<?=$numRadicados["grupo2"]?>"
							id=grupo2>
						</td>
						<td valign="midle" width="34%" class='listado2'><input type=text
							class='tex_area' name=valor_unit_grupo2 id=valor_unit_grupo2
							readonly></Td>
						<td width="28%" class='listado2'><input type=text class='tex_area'
							name=valor_total_grupo2 id=valor_total_grupo2 readonly></Td>
					</tr>
					<tr class=listado2>
						<td width="12%"></td>
						<td width="17%"><input type="hidden" name="primRadNac"
							value="<?=$grupoMas->getPrimerRadicadoNacional() ?>" id=nacional>
							<input type="hidden" name="primRadLoc"
							value="<?=$grupoMas->getPrimerRadicadoLocal() ?>" id=nacional> <input
							type="hidden" name="primRadG1"
							value="<?=$grupoMas->getPrimerRadicadoGrupo1() ?>" id=nacional> <input
							type="hidden" name="primRadG2"
							value="<?=$grupoMas->getPrimerRadicadoGrupo2() ?>" id=nacional> <input
							type="hidden" name="grupo" value="<?=$radGrupo ?>" id=nacional> <input
							type="hidden" name="renv_codigo"
							value="<?=$grupoMas->getSgd_renv_codigo()?>" id=nacional></td>
						<td width="34%">&nbsp;</Td>
						<td width="28%"><input type=text class='tex_area' name=valor_total
							id=valor_total readonly></Td>
				
				</table>
				<br />
				<table
					class="table table-striped table-bordered table-hover dataTable no-footer smart-form"
					width="33%" border="0" align="center">
					<tbody>
						<tr class=titulos5 bgcolor="">
							<td class="#cccccc" width="29%">Observaciones o desc. anexos</td>
							<td class="#cccccc" width="71%"><input id="observaciones"
								name="observaciones" type="text" size="56" class='tex_area'></td>
						</tr>
						<tr class=titulos5 bgcolor="">
							<td class="#cccccc" width="29%">No. De Planilla - <?=$numplan?></td>
							<td class="#cccccc" width="71%"><input value="<?=$ultplan?>"
								id="planilla" name="planilla" type="text" class='tex_area'></td>
						</tr>
					</tbody>
				</table>
				<p align="center">
					<input name=reg_envio type=button class="botones_largo"
						value='GENERAR REGISTRO DE ENVIO' onClick='enviar()'>
				</p>
			</div>
		</div>

	</form>
	<span class=vinculos>
		<center>
			<a href=javascript:back1()>Regresar a Listado</a>
		</center>
	</span>
	<SCRIPT type="text/javascript">
<?php
$grupoMas->javascriptCalcularPrecio ( $numRadicados ["local"], $numRadicados ["nacional"], $numRadicados ["grupo1"], $numRadicados ["grupo2"] );
?>
</SCRIPT>
</body>
</html>
