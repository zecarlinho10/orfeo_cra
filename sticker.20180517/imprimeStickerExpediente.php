<?
/**
 * Programa que despliega el formulario de consulta de ESP
 * @author     Lucia Ojeda Acosta
 * @version     1.0
 */
$ruta_raiz = "..";
session_start();
error_reporting(0);
require_once("$ruta_raiz/include/db/ConnectionHandler.php");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

include_once($ruta_raiz."/radicacion/crea_combos_universales.php");

//En caso de no llegar la dependencia recupera la sesi�n
if(!$_SESSION['dependencia']) include "$ruta_raiz/rec_session.php";

?>
<html>
<head>
<title>Sticker</title>
<link rel="stylesheet" href="../../estilos/orfeo.css">
<script language="JavaScript" src="../../js/crea_combos_2.js"></script>
<script language="JavaScript" type="text/JavaScript">
<?php
//HLP. Convertimos los vectores de los paises, dptos y municipios creados en crea_combos_universales.php a vectores en JavaScript.
echo arrayToJsArray($vpaisesv, 'vp');
echo arrayToJsArray($vdptosv, 'vd');
echo arrayToJsArray($vmcposv, 'vm');
?>


</script>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?

$params = session_name()."=".session_id()."&krd=$krd";

$radicado        = $_POST["radicado"];
$varEnvio = session_name() . "=" . session_id() . "&nurad=$radicado&ent=$ent";

?>

<form action="#" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="2" class='titulos2'>
			CONSULTA EXPEDIENTE POR RADICADO
        	<input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>
	<tr align="center">
		<td width="31%" class='titulos2'>RADICADO</td>
		<td width="69%" height="30" class='listado2' align="left">
			<input name="radicado" id="radicado" type="input" size="50" class="tex_area">
		</td>
	</tr>
	
	<tr align="center">
		<td height="30" colspan="2" class='listado2'>
		<center>
			<button type="submit" class="btn btn-default">Consultar</button>
		</center>
		</td>
	</tr>
	<tr align="center">
		<td height="25" colspan="2" class='titulos2'>

		<?php
		if($radicado) {
			?>
		  <div id="showModificar" class="col-lg-3 <?=$modificar?>">
	          <label id="sticker"> Expediente Encontrado exitosamente: <a href="javascript:void(0);"
	            onClick="window.open ('./stickerExpediente.php?<?=$varEnvio?>&alineacion=Center','sticker<?=$radicado?> ','menubar=0,resizable=0,scrollbars=0,width=450,height=180,toolbar=0,location=0');"
	            class="btn btn-link">Imprimir Sticker</a>
	          </label>
    	  </div>
    	<?php
		}
		else {
			?>
			<div id="showModificar" class="col-lg-3 <?=$modificar?>">
			  <label id="sticker"> Expediente NO Encontrado
	          </label>
    	  </div>
    	  <?php
		}
		?>
    	</td>
    </tr>
</table>
<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
</table>
</form>
		
</body>
</html>
