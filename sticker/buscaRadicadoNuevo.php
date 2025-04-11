<?php 

$ruta_raiz = "..";
require_once("$ruta_raiz/include/db/ConnectionHandler.php");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

$radicado = $_POST['radicado']; 

$isql="SELECT SGD_DIR_NOMBRE, SGD_DIR_NOMREMDES, SGD_DIR_DIRECCION, SGD_DIR_TELEFONO, MUNI_NOMB, DPTO_NOMB, NOMBRE_PAIS
			FROM RADICADO R, SGD_DIR_DRECCIONES D, MUNICIPIO M, DEPARTAMENTO DP, SGD_DEF_PAISES P
			WHERE R.RADI_NUME_RADI = D.RADI_NUME_RADI AND 
			      D.ID_PAIS=P.ID_PAIS AND D.ID_CONT=P.ID_CONT AND
			      DP.ID_CONT=DP.ID_CONT AND DP.ID_PAIS=D.ID_PAIS AND D.DPTO_CODI=DP.DPTO_CODI AND
			      M.ID_CONT=D.ID_CONT AND M.ID_PAIS=D.ID_PAIS AND D.DPTO_CODI=M.DPTO_CODI AND M.MUNI_CODI=D.MUNI_CODI AND 
			      R.RADI_NUME_RADI=$radicado";

			$rs=$db->conn->Execute($isql);
			$nombre=$rs->fields["SGD_DIR_NOMBRE"];
			$nomempresa=$rs->fields["SGD_DIR_NOMREMDES"];
			$direccion=$rs->fields["SGD_DIR_DIRECCION"];
			$telefono=$rs->fields["SGD_DIR_TELEFONO"];
			$ciudad=$rs->fields["MUNI_NOMB"] . "-" . $rs->fields["DPTO_NOMB"] . "-" . $rs->fields["NOMBRE_PAIS"] ;

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


<form action="confirmaBusqueda.php" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >

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
					<input name="destinatario" id="destinatario" value="<?=substr( $nombre, 0, 38 );?>" type="input" size="50" maxlength="38" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Cargo</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="cargo" id="cargo" value="<?=substr( $cargo, 0, 38 );?>" type="input" size="50" maxlength="38" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Empresa</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="empresa" id="empresa" value="<?=substr( $nomempresa, 0, 38 );?>" type="input" size="50" maxlength="38" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Dirección</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="direccion" id="direccion" value="<?=substr( $direccion, 0, 38 );?>" type="input" size="50" maxlength="38" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Teléfono</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="telefono" id="telefono" value="<?=$telefono?>" type="input" size="50" maxlength="38" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
					<td width="31%" class='titulos2'>Ciudad</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="ciudad" id="ciudad" value="<?=$ciudad?>" type="input" size="50" maxlength="38" class="tex_area">
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