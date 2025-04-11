<?php 

$ruta_raiz = "..";
require_once("$ruta_raiz/include/db/ConnectionHandler.php");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

$radicado 	= $_POST['radicado']; 
$saludo 	= $_POST["saludo"];
$destinatario 	= $_POST["destinatario"];
$cargo 		= $_POST["cargo"];
$empresa 	= $_POST["empresa"];
$direccion 	= $_POST["direccion"];
$telefono 	= $_POST["telefono"];
$ciudad 	= $_POST["ciudad"];

?>

<html>
<head>
<title>Sticker por radicado</title>

<script type="text/javascript" src="./js/jquery.js"></script>
<link rel="stylesheet" href="../../estilos/orfeo.css">
<script language="JavaScript" src="../../js/crea_combos_2.js"></script>


<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?

$params = session_name()."=".session_id()."&krd=$krd";

$varEnvio = session_name() . "=" . session_id() . "&nurad=$radicado&ent=$ent&saludo=$saludo&destinatario=$destinatario&cargo=$cargo&empresa=$empresa&direccion=$direccion&telefono=$telefono&ciudad=$ciudad";

?>

<form action="#" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
		<tr align="center">
			<td height="25" colspan="2" class='titulos2'>
				CONSULTA INFORMACION DEL RADICADO
	        	<input name="accion" type="hidden" id="accion">
	        	<input type="hidden" name="params" value="<?=$params?>">
	      </td>
	    </tr>
		<tr align="center">
			<td width="31%" class='titulos2'>RADICADO</td>
			<td width="69%" height="30" class='listado2' align="left">
				<input name="radicado" id="radicado" value="<?=$radicado?>" type="input" size="50" class="tex_area">
			</td>
		</tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Saludo</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="saludo" id="saludo" value="<?=$saludo?>" type="input" size="50" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Destinatario / remitente</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="destinatario" id="destinatario" value="<?=$destinatario?>" type="input" size="50" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Cargo</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="cargo" id="cargo" value="<?=$cargo?>" type="input" size="50" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Empresa</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="empresa" id="empresa" value="<?=$empresa?>" type="input" size="50" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Dirección</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="direccion" id="direccion" value="<?=$direccion?>" type="input" size="50" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Teléfono</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="telefono" id="telefono" value="<?=$telefono?>" type="input" size="50" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
					<td width="31%" class='titulos2'>Ciudad</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="ciudad" id="ciudad" value="<?=$ciudad?>" type="input" size="50" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td height="30" colspan="2" class='listado2'>
				<center>
					<button type="submit" class="btn btn-default">Confirmar Cambios</button>
				</center>
				</td>
		  </tr>
		  <tr align="center">
		  <div id="showModificar" class="col-lg-3 <?=$modificar?>">
		  	<td colspan="2" class='titulos2'>
	          <label id="sticker"> Información del radicado Encontrado exitosamente: <a href="javascript:void(0);"
	            onClick="window.open ('./stickerNuevo.php?<?=$varEnvio?>&alineacion=Center','sticker<?=$radicado?> ','menubar=0,resizable=0,scrollbars=0,width=450,height=180,toolbar=0,location=0');"
	            class="btn btn-link">Imprimir Sticker</a>
	          </label>
	        </td>
    	  </div>
    	  </tr>
    	  <tr align="center">
		  	<td colspan="2" class='titulos2'>
	          <center>
	          		<a href="./imprimeStickerNuevo.php" class="btn btn-link">Nueva busqueda 
	          		</a>
			  </center>
	        </td>
    	  </tr>
    	
    	</td>
    </tr>
    <tr align="center">
		  <div id="prueba" class="col-lg-3">
		  	<td colspan="2" class='titulos2'>
	        </td>
    	  </div>
</table>
<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
</table>
</form>
		
</body>
</html>