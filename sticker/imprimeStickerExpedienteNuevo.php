<?php 

$ruta_raiz = "..";
require_once("$ruta_raiz/include/db/ConnectionHandler.php");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);


$db = new ConnectionHandler( "$ruta_raiz" );

$rs_dep=$db->query("SELECT DEPE_CODI, DEPE_NOMB FROM dependencia WHERE DEPE_ESTADO=1 ORDER BY DEPE_NOMB");
$raidDependencias = array();

$i=0;
while(!empty($rs_dep) && !$rs_dep->EOF){ 
	 	$raidDependencias[$i]=$rs_dep->fields; 
		$i++;
		$rs_dep->MoveNext ();
	}

?>

<html>
<head>
<title>Sticker Expediente</title>

<script type="text/javascript" src="jquery.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<link rel="stylesheet" href="../js/jquery-ui/jquery-ui.css">
  <script src="../js/jquery.min.js"></script>
  <script src="../js/jquery-ui/jquery-ui.min.js"</script>
  <script>
  $( function() {
    $( "#finicial" ).datepicker({
      numberOfMonths: 2,
      showButtonPanel: true
    });
  } );
  $( function() {
    $( "#ffinal" ).datepicker({
      numberOfMonths: 2,
      showButtonPanel: true
    });
  } );
  </script>

</head>
<body>


<form action="confirmaBusquedaExp.php" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
		<tr align="center">
			<td height="25" colspan="2" class='titulos2'>
				CONSULTA INFORMACION DEL RADICADO
	        	<input name="accion" type="hidden" id="accion">
	        	<input type="hidden" name="params" value="<?=$params?>">
	      </td>
	    </tr>
	    <tr align="center">
	      <td class='titulos2'>Dependencia</td>
		  <td align="left">
			  <div class="form-group">
			    <select id="dependencia_id" class="form-control" name="dependencia_id" required>
			      <option value="">-- SELECCIONE --</option>
					<?php foreach($raidDependencias as $d):?>
					      <option value="<?php echo $d["DEPE_CODI"]; ?>"><?php echo $d["DEPE_NOMB"] ?></option>
					<?php endforeach; ?>
			    </select>
			  </div>
		  </td>
		</tr>
		<tr align="center">
			<td class='titulos2'>Serie</td>
			<td align="left">
			    <div class="form-group">
				    <select id="serie_id" class="form-control" name="serie_id" required>
				      <option value="">-- SELECCIONE --</option>
				    </select>
			    </div>
			</td>
		</tr>
		<tr align="center">
			<td class='titulos2'>Sub-Serie</td>
			<td align="left">
			    <div class="form-group">
				    <select id="subserie_id" class="form-control" name="subserie_id" required>
				      <option value="">-- SELECCIONE --</option>
				    </select>
			    </div>
			</td>
		</tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Nombre expediente</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="nomexpediente" id="nomexpediente" type="input" size="50" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>
					Fecha Inicial: 
				</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input type="text" id="finicial" name="finicial">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>
					Fecha Final: 
				</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input type="text" id="ffinal" name="ffinal">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Expediente</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="expediente" id="expediente" type="input" size="50" maxlength="18" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
				<td width="31%" class='titulos2'>Número de Fólios</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="nfolios" id="nfolios" type="input" size="10" class="tex_area">
				</td>
		  </tr>
		  <tr align="center">
					<td width="31%" class='titulos2'>Carpeta</td>
				<td width="69%" height="30" class='listado2' align="left">
					<input name="carpeta" id="carpeta" type="input" size="10" class="tex_area">
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
<script type="text/javascript">
	$(document).ready(function(){
		$("#dependencia_id").change(function(){
			$.get("get_series.php","dependencia_id="+$("#dependencia_id").val(), function(data){
				$("#serie_id").html(data);
				console.log(data);
			});
		});

		$("#serie_id").change(function(){
			$.get("get_subseries.php","serie_id="+$("#serie_id").val(), function(data){
				$("#subserie_id").html(data);
				console.log(data);
			});
		});

	});
</script>
</body>
</html>
