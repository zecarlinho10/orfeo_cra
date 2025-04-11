<?php 

$ruta_raiz = "..";


$destinatario 	= $_POST["txt_destinatario"];
$direccion 		= $_POST["txt_direccion"];
$ciudad 		= $_POST["txt_ciudad"];
$telefono 		= $_POST["txt_telefono"];

?>

<html>
<head>
<title>Sticker por radicado</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?

$params = session_name()."=".session_id()."&krd=$krd";

$varEnvio = session_name() . "=" . session_id() . "&destinatario=$destinatario&direccion=$direccion&ciudad=$ciudad&telefono=$telefono";

?>

<form action="#" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
		<tr align="center">
			<td height="25" colspan="2" class='titulos2'>
				INFORMACION STICKER
	        	<input name="accion" type="hidden" id="accion">
	        	<input type="hidden" name="params" value="<?=$params?>">
	      </td>
	    </tr>
		<tr align="center">
			<td width="31%" class='titulos2'>Nombre destinatario</td>
			<td width="69%" height="30" class='listado2' align="left">
				<input name="txt_destinatario" id="txt_destinatario" value="<?=$destinatario?>" type="input" size="50" class="tex_area">
			</td>
		</tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Dirección</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="txt_direccion" id="txt_direccion" value="<?=$direccion?>" type="input" size="50" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Ciudad</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="txt_ciudad" id="txt_ciudad" value="<?=$ciudad?>" type="input" size="50" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Telefono</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="txt_telefono" id="txt_telefono" value="<?=$telefono?>" type="text" size="50" >
				</td>
		  </tr>
		  <tr align="center">
		  <?
		  	if($destinatario){
		  ?>
		  <div id="showModificar" class="col-lg-3 <?=$modificar?>">
		  	<td colspan="2" class='titulos2'>
	          <a onClick="window.open ('./CRANuevoCorrespondencia.php?<?=$varEnvio?>&alineacion=Center','sticker<?=$radicado?> ','menubar=0,resizable=0,scrollbars=0,width=450,height=180,toolbar=0,location=0');" class="btn btn-link">Imprimir Sticker</a>
	        </td>
    	  </div>
    	  	<?
		  		}
		  	?>
    	  </tr>
    	  <tr align="center">
		  	<td colspan="2" class='titulos2'>
	          <center>
	          		<a href="./correspondencia.php" class="btn btn-link">Nuevo sticker
	          		</a>
			  </center>
	        </td>
    	  </tr>
    	  <tr align="center">
		  	<td colspan="2" class='titulos2'>
	          <center>
	          		<input type="submit" value="Confirmar Datos" />
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