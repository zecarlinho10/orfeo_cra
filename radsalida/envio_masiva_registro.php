<?php
session_start ();

$ruta_raiz = "..";
if (! $_SESSION ['dependencia'])
	header ( "Location: $ruta_raiz/cerrar_session.php" );

foreach ( $_GET as $key => $valor )
	${$key} = $valor;
foreach ( $_POST as $key => $valor )
	${$key} = $valor;

include_once ("$ruta_raiz/include/db/ConnectionHandler.php");

$db = new ConnectionHandler ( "$ruta_raiz" );

$fecha_hoy = Date ( "Y-m-d" );
$sqlFechaHoy = $db->conn->OffsetDate ( 0, $db->conn->sysTimeStamp );
$dependencia = $_SESSION ['dependencia'];
$usua_doc = $_SESSION ["usua_doc"];

?>
<html>
<head>
<title>Enviar Datos</title>
<link rel="stylesheet" href="../estilos/orfeo.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Bootstrap core CSS -->
  <?php include_once $ruta_raiz."/htmlheader.inc.php"; ?>
</head>
<body>
	<form name="form1" method="post"
		action="cuerpo_masiva.php?<?=session_name()."=".session_id()."&krd=$krd" ?>">
<?php
// almacena el query
// variable requerida para radsalida/generar_planos
$no_planilla_Inicial = $planilla;
$tipo_envio = $empresa_envio;
echo "<pre>";
$isql = "UPDATE SGD_RENV_REGENVIO 
		SET sgd_renv_planilla='$planilla',
			sgd_renv_tipo = 2,
			depe_codi=$dependencia,
			sgd_renv_fech= $sqlFechaHoy,
			sgd_fenv_codigo='$empresa_envio',
                        sgd_renv_peso='$envio_peso',
			sgd_renv_valor='0',
                        sgd_renv_cantidad = 1
		WHERE sgd_renv_planilla = '00' and sgd_renv_tipo = 1
			and radi_nume_grupo = $grupo and radi_nume_sal not in 
				(select sgd_rmr_radi  from sgd_rmr_radmasivre  where sgd_rmr_grupo = $grupo) ";

$rs = $db->conn->query ( $isql );
echo "</pre>";
if (! $rs) {
	die ( "<span class='etextomenu'>No se ha podido actualizar SGD_RENV_REGENVIO ($isql)" );
}
$nombre_us = substr ( $nombre_us, 0, 29 );
// arreglo de los campos a actualizar
$values ["usua_doc"] = "'$usua_doc'";
$values ["sgd_renv_codigo"] = "'" . $renv_codigo . "'";
$values ["sgd_fenv_codigo"] = "'$empresa_envio'";
$values ["sgd_renv_fech"] = "$sqlFechaHoy";
$values ["sgd_renv_telefono"] = "'$telefono'";
$values ["sgd_renv_mail"] = "'$mail'";
$values ["sgd_renv_peso"] = "'$envio_peso'";
$values ["sgd_renv_certificado"] = 0;
$values ["sgd_renv_estado"] = 1;
$values ["sgd_renv_nombre"] = "'Varios'";
$values ["sgd_dir_codigo"] = "0";
$values ["sgd_dir_tipo"] = 1;
$values ["depe_codi"] = "'$dependencia'";
$values ["radi_nume_grupo"] = "'$grupo'";
$values ["sgd_renv_dir"] = "'Varios'";
$values ["sgd_renv_depto"] = "'$departamento_us'";
$values ["sgd_renv_planilla"] = "'$planilla'";
$values ["sgd_renv_observa"] = "'$observaciones'";

// Si existen documentos nacionales
if ($nacional > 0) {
	$values ["radi_nume_sal"] = "'$primRadNac'";
	$values ["sgd_renv_destino"] = "'Nacional'";
	$values ["sgd_renv_valor"] = "'$valor_unit_nacional'";
	$values ["sgd_renv_mpio"] = "'Nacional'";
	;
	$values ["sgd_renv_cantidad"] = "'$nacional'";
	$rs = $db->insert ( "sgd_renv_regenvio", $values );
	if (! $rs) {
		die ( "<span class='etextomenu'>No se ha podido insetar la informacion de nacionales en en sgd_renv_regenvio" );
	}
}

// Si existen documentos locales
if ($local > 0) {
	$values ["radi_nume_sal"] = "'$primRadLoc'";
	$values ["sgd_renv_destino"] = "'Local'";
	$values ["sgd_renv_valor"] = "'$valor_unit_local'";
	$values ["sgd_renv_mpio"] = "'Local'";
	;
	$values ["sgd_renv_cantidad"] = "'$local'";
	$rs = $db->insert ( "sgd_renv_regenvio", $values );
	if (! $rs) {
		die ( "<span class='etextomenu'>No se ha podido insetar la informacion de locales en en sgd_renv_regenvio" );
	}
}

// Si existen documentos internacionales grupo1
if ($_POST ['grupo1'] > 0) {
	$values ["radi_nume_sal"] = "'$primRadG1'";
	$values ["sgd_renv_destino"] = "'Int. G1'";
	$values ["sgd_renv_valor"] = "'$valor_unit_grupo1'";
	$values ["sgd_renv_mpio"] = "'Int. G1'";
	;
	$values ["sgd_renv_cantidad"] = "'" . $_POST ['grupo1'] . "'";
	$rs = $db->insert ( "sgd_renv_regenvio", $values );
	
	if (! $rs) {
		die ( "<span class='etextomenu'>No se ha podido insetar la informacion de Int. Grupo1 en sgd_renv_regenvio" );
	}
}

// Si existen documentos internacionales grupo2
if ($_POST ['grupo2'] > 0) {
	$values ["radi_nume_sal"] = "'$primRadG2'";
	$values ["sgd_renv_destino"] = "'Int. G2'";
	$values ["sgd_renv_valor"] = "'$valor_unit_grupo2'";
	$values ["sgd_renv_mpio"] = "'Int. G2'";
	;
	$values ["sgd_renv_cantidad"] = "'" . $_POST ['grupo1'] . "'";
	$rs = $db->insert ( "sgd_renv_regenvio", $values );
	
	if (! $rs) {
		die ( "<span class='etextomenu'>No se ha podido insetar la informacion de Int. Grupo2 en sgd_renv_regenvio" );
	}
}

?>
		<div class="row text-center">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<b>CONFIRMACION DEL ENVIO - RADICACION MASIVA <BR /> SE HAN MARCADO
					COMO ENVIADOS LOS RADICADOS DEL GRUPO
				</b>
			</div>
		</div>
		<div class="row text-center">
			<div class="col-sm-2 col-md-2 col-lg-4"></div>
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<table
					class="table table-striped table-bordered table-hover dataTable no-footer smart-form">
					<tr class="titulos2">
						<td valign="top" width="12%">GRUPO</td>
						<td valign="top" width="12%">DESTINO</td>
						<td valign="top" width="17%">DOCUMENTOS</td>
						<td valign="top" width="28%">VALOR TOTAL</font></td>
					</tr>
					<tr class=listado2>
						<td height="44" valign="middle" align="center" valign="top"
							rowspan="4">
		<? echo ($rangoini."<br>-<br>".$rangofin);?>
	</td>
						<td height="21" align="center" valign="top" width="12%">Local</td>
						<td align="center" valign="top" width="17%"><?=$_POST['local']?></td>
						<td width="28%" align="center"><?=$_POST['valor_total_local']?></td>
					</tr>
					<tr class=listado2>
						<td height="21" align="center" valign="top" width="12%">Nacional</td>
						<td align="center" valign="top" width="17%"> <?=$_POST['nacional']?></td>
						<td width="28%" align="center"> <?=$_POST['valor_total_nacional']?></td>
					</tr>
					<tr class=listado2>
						<td height="21" align="center" valign="top" width="12%">Int. G1</td>
						<td align="center" valign="top" width="17%"><?=$_POST['grupo1']?></td>
						<td width="28%" align="center"><?=$_POST['valor_total_grupo1']?></td>
					</tr>
					<tr class=listado2>
						<td height="21" align="center" valign="top" width="12%">Int. G2</td>
						<td align="center" valign="top" width="17%"> <?=$_POST['grupo2']?></td>
						<td width="28%" align="center"> <?=$_POST['valor_total_grupo2']?></td>
					</tr>
					<tr class="listado2">
						<td width="12%">&nbsp;</td>
						<td width="12%">&nbsp;</td>
						<td width="17%">&nbsp;</td>
						<td width="28%">&nbsp;</td>
					</tr>
					<TR>
						<TD align="center" colspan="4"><input type=submit class="botones"
							name=aceptar id=aceptar value=Aceptar></TD>
					</TR>
				</table>
			</div>
		</div>
	</form>
<?php
$generar_listado = true;
include ("$ruta_raiz/radsalida/generar_planos.php");
?>
</body>
</html>
