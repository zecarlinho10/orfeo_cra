<?php
session_start();

foreach ($_GET  as $key => $val){ ${$key} = $val;}
foreach ($_POST as $key => $val){ ${$key} = $val;}

$ruta_raiz = "..";

if(!isset($_SESSION['dependencia']))	include "$ruta_raiz/rec_session.php";

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once("$ruta_raiz/include/combos.php");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);
//if (!defined('ADODB_FETCH_ASSOC'))	define('ADODB_FETCH_ASSOC',2);
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

/**
 * Retorna la cantidad de bytes de una expresion como 7M, 4G u 8K.
 *
 * @param char $var
 * @return numeric
 */
function return_bytes($val)
{	$val = trim($val);
	$ultimo = strtolower($val{strlen($val)-1});
	switch($ultimo)
	{	// El modificador 'G' se encuentra disponible desde PHP 5.1.0
		case 'g':	$val *= 1024;
		case 'm':	$val *= 1024;
		case 'k':	$val *= 1024;
	}
	return $val;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../estilos/orfeo.css">
  <?php include_once $ruta_raiz."/htmlheader.inc.php"; ?>
</head>
<body bgcolor="#FFFFFF" topmargin="0">
<script language="JavaScript" type="text/JavaScript">

/**
* Valida que el formulario desplegado se encuentre adecuadamente diligenciado
*/
function validar() {
	archDocto = document.formAdjuntarArchivos.archivoPlantilla.value;
	if ( (archDocto.substring(archDocto.length-1-3,archDocto.length)).indexOf(".csv") == -1){
		alert ("El archivo de datos debe ser .csv");
		return false;
	}

	if (document.formAdjuntarArchivos.archivoPlantilla.value.length<1){
		alert ("Debe ingresar el archivo CSV con los datos");
		return false;
	}

	return true;
}

function enviar() {

	if (!validar())
		return;

	document.formAdjuntarArchivos.accion.value="PRUEBA";
	document.formAdjuntarArchivos.submit();
}

</script>

<?
if(!$tipo) $tipo = 3;

//Tipo de estructura del Plano
include "seleccionar_estructura.php";

$params="dependencia=$dependencia&codiEst=$codiEst&usua_nomb=$usua_nomb&depe_nomb=$depe_nomb&usua_doc=$usua_doc&tipo=$tipo&codusuario=$codusuario";
?>
	<form action="adjuntar_PlanoEnvio.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formAdjuntarArchivos">
	<input type=hidden name=<?=session_name()?>  value='<?=session_id()?>'>
	<input type=hidden name=codiEst value='<?=$codiEst?>'>
	<br> 
		<table width="70%" align="center" border="0" cellpadding="0" cellspacing="5" class="borde_tab">
			<tr align="center">
				<td height="25" colspan="2" class="titulos4">
					SELECCIONAR ARCHIVO PLANO
					<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo return_bytes(ini_get('upload_max_filesize')); ?>">
					<input name="accion" type="hidden" id="accion">
				</td>
			</tr>
			<tr align="center">
				<td width="16%" class="titulos2">ARCHIVO </td>
				<td width="84%" height="30" class="listado2">
					<input name="archivoPlantilla" type="file" value='<?=$archivoPlantilla?>' class="tex_area"  id=archivoPlantilla accept="text/csv">
				</td>
			</tr>
			<tr align="center">
				<td height="30" colspan="2" class="celdaGris">
					<span class="celdaGris"> <span class="e_texto1">
					<input name="enviaPrueba" type="button"  class="botones" id="envia22"  onClick="enviar();" value="Cargar">
					</span></span>
				</td>
			</tr>
			<tr align="center">
				<td height="30" colspan="2" class="celdaGris">
					Esta operaci&oacute;n permite registrar
					la informaci&oacute;n suministrada por la empresa de
                                        Correo.
				</td>
			</tr>
		</table>
	</form>
</body>
</html>
