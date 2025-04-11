<?php 

$ruta_raiz = "..";
require_once("$ruta_raiz/include/db/ConnectionHandler.php");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

$db = new ConnectionHandler( "$ruta_raiz" );

$dependencia_id 	= $_POST["dependencia_id"];
$serie_id 	= $_POST["serie_id"];
$subserie_id 	= $_POST["subserie_id"];
$nomexpediente 	= $_POST["nomexpediente"];
$finicial 	= $_POST["finicial"];
$ffinal 	= $_POST["ffinal"];
$expediente 	= $_POST["expediente"];
$nfolios 	= $_POST["nfolios"];
$carpeta 	= $_POST["carpeta"];

$rs_dep=$db->query("SELECT DEPE_CODI, DEPE_NOMB FROM DEPENDENCIA WHERE DEPE_CODI=$dependencia_id");
$dependencia    = $rs_dep->fields["DEPE_NOMB"];

$rs_dep=$db->query("SELECT DISTINCT S.SGD_SRD_CODIGO, SGD_SRD_DESCRIP FROM SGD_SRD_SERIESRD S WHERE S.SGD_SRD_CODIGO=$serie_id ORDER BY SGD_SRD_DESCRIP");
$serie    = $rs_dep->fields["SGD_SRD_DESCRIP"];

$rs_dep=$db->query("SELECT DISTINCT SGD_SBRD_DESCRIP FROM SGD_SBRD_SUBSERIERD WHERE SGD_SRD_CODIGO=$serie_id AND SGD_SBRD_CODIGO=$subserie_id ORDER BY SGD_SBRD_DESCRIP");

$subserie    = $rs_dep->fields["SGD_SBRD_DESCRIP"];

?>

<html>
<head>
<title>Sticker por radicado</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?

$params = session_name()."=".session_id()."&krd=$krd";

$varEnvio = session_name() . "=" . session_id() . "&dependencia=$dependencia&serie=$serie&subserie=$subserie&nomexpediente=$nomexpediente&finicial=$finicial&ffinal=$ffinal&expediente=$expediente&nfolios=$nfolios&carpeta=$carpeta";

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
			<td width="31%" class='titulos2'>Dependencia</td>
			<td width="69%" height="30" class='listado2' align="left">
				<input name="dependencia_id" id="dependencia_id" value="<?=$dependencia?>" type="input" size="50" class="tex_area">
			</td>
		</tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Serie</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="serie_id" id="serie_id" value="<?=$serie?>" type="input" size="50" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Sub-Serie</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="subserie" id="subserie" value="<?=$subserie?>" type="input" size="50" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Nombre expediente</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="nomexpediente" id="nomexpediente" value="<?=$nomexpediente?>" type="input" size="50" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Fecha Inicial</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="finicial" id="finicial" value="<?=$finicial?>" type="text" size="50">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Fecha Final</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="ffinal" id="ffinal" value="<?=$ffinal?>" type="text" size="50" >
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Expediente</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="expediente" id="expediente" value="<?=$expediente?>" type="input" size="50" maxlength="18" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
					<td width="31%" class='titulos2'>Número de Fólios</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="nfolios" id="nfolios" value="<?=$nfolios?>" type="input" size="50" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
					<td width="31%" class='titulos2'>Carpeta</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="carpeta" id="carpeta" value="<?=$carpeta?>" type="input" size="50" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
		  <div id="showModificar" class="col-lg-3 <?=$modificar?>">
		  	<td colspan="2" class='titulos2'>
	          <label id="sticker"> Información del radicado Encontrado exitosamente: <a href="javascript:void(0);"
	            onClick="window.open ('./stickerNuevoExp.php?<?=$varEnvio?>&alineacion=Center','sticker<?=$radicado?> ','menubar=0,resizable=0,scrollbars=0,width=450,height=180,toolbar=0,location=0');"
	            class="btn btn-link">Imprimir Sticker</a>
	          </label>
	        </td>
    	  </div>
    	  </tr>
    	  <tr align="center">
		  	<td colspan="2" class='titulos2'>
	          <center>
	          		<a href="./imprimeStickerExpedienteNuevo.php" class="btn btn-link">Nueva busqueda 
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