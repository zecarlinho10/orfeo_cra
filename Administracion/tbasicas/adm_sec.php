<?
//$krdOld = $krd;
error_reporting(0);
session_start();

$ruta_raiz = "../..";


?>
<html>
<head>
<title>.:.Secuencias.:.</title>

</head>

<body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

	<?
		

	?>

<form name='adm_sec' action='adm_sec.php' method="post">

<table width="50%"  border="1" align="center">
  	<tr bordercolor="#FFFFFF">
    <td colspan="2" class="titulos4">
	<center>
	<p><B><span class=etexto>ADMINISTRACI&Oacute;N DE SECUENCIAS DE CONSECUTIVOS</span></B> </p>
	</td>
	</tr>
</table>
<table border=1 width=50% class=t_bordeGris align="center">
	<tr class=timparr>
			<td class="titulos2" height="26" width="25%" colspan="-1">TODOS</td>
			<td class="listado2" height="1" width="25%" colspan="3">
			<center> <input class="botones" type="button" name="all" id="all" Value="Reset" > </center>
			</td>
	</tr>
	<tr class=timparr>
			<td class="titulos2" height="26" width="25%" colspan="-1">SALIDAS (1)</td>
			<td class="listado2" height="1" width="25%" colspan="3">
			<center> <input type="button" name="out" id="out" Value="Reset"> </center>
			</td>
	</tr>
	<tr class=timparr>
			<td class="titulos2" height="26" width="25%" colspan="-1">ENTRADAS (2)</td>
			<td class="listado2" height="1" width="25%" colspan="3">
			<center> <input class="botones" type="button" name="in" id="in" Value="Reset" > </center>
			</td>
	</tr>
		<tr class=timparr>
			<td class="titulos2" height="26" width="25%" colspan="-1">MEMORANDOS (3)</td>
			<td class="listado2" height="1" width="25%" colspan="3">
			<center> <input class="botones" type="button" name="mem" id="mem" Value="Reset" > </center>
			</td>
	</tr>

<tr class=timparr>
			<td class="titulos2" height="26" width="25%" colspan="-1">CID (4)</td>
			<td class="listado2" height="1" width="25%" colspan="3">
			<center> <input class="botones" type="button" name="cid" id="cid" Value="Reset" > </center>
			</td>
	</tr>
	<tr class=timparr>
			<td class="titulos2" height="26" width="25%" colspan="-1">RESOLUCIONES (5)</td>
			<td class="listado2" height="1" width="25%" colspan="3">
			<center> <input class="botones" type="button" name="res" id="res" Value="Reset" > </center>
			</td>
	</tr>
	<tr class=timparr>
			<td class="titulos2" height="26" width="25%" colspan="-1">CIRCULARES (6)</td>
			<td class="listado2" height="1" width="25%" colspan="3">
			<center> <input class="botones" type="button" name="cir" id="cir" Value="Reset" > </center>
			</td>
	</tr>
	<tr class=timparr>
			<td class="titulos2" height="26" width="25%" colspan="-1">ACTA (7)</td>
			<td class="listado2" height="1" width="25%" colspan="3">
			<center> <input class="botones" type="button" name="actas" id="actas" Value="Reset" > </center>
			</td>
	</tr>
	</tr>
		<tr class=timparr>
			<td class="titulos2" height="26" width="25%" colspan="-1">AUTOS (8)</td>
			<td class="listado2" height="1" width="25%" colspan="3">
			<center> <input class="botones" type="button" name="autos" id="autos" Value="Reset" > </center>
			</td>
	</tr>
	<tr class=timparr>
			<td class="titulos2" height="26" width="25%" colspan="-1">CONTRATOS (9)</td>
			<td class="listado2" height="1" width="25%" colspan="3">
			<center> <input class="botones" type="button" name="contratos" id="contratos" Value="Reset" > </center>
			</td>
	
</table>

<table border=1 width=50% class=t_bordeGris align="center">
	<tr class=timparr>
	      <td height="30" colspan="2" class="listado2"><span class="celdaGris"> <span class="e_texto1">
	<center><a href='formAdministracion.php?<?=session_name()."=".session_id()."&$encabezado"?>'><input class="botones" type="button" name="Cancelar" id="Cancelar" Value="Cancelar"></a></center>  </span> </span></td>
	</tr>
</table>

<div id="cajatexto"></div>
</form>
	
	<script type="text/javascript">
		//SALIDA 1
		$( "#out" ).on( "click", function() {
		    var r=confirm("Esta seguro de reinicializar secuencia para radicados de Entrada?");
			if (r==true){
				$.ajax({
					type: "POST",
					url: "procesa_secuencias.php",
					data: { menu: 1 },
					beforeSend: function () {
	                        $("#cajatexto").html("Procesando, espere por favor...");
	                },
	                success:  function (response) { 
	                        $("#cajatexto").html(response);
	                }
				});
			}
			else{
				alert("Reseteo Cancelado ");
				return false;
			}
		});

		//ENTRADA 2
		$( "#in" ).on( "click", function() {
		    var r=confirm("Esta seguro de reinicializar secuencia para radicados de Entrada?");
			if (r==true){
				$.ajax({
					type: "POST",
					url: "procesa_secuencias.php",
					data: { menu: 2 },
					beforeSend: function () {
	                        $("#cajatexto").html("Procesando, espere por favor...");
	                },
	                success:  function (response) { 
	                        $("#cajatexto").html(response);
	                }
				});
			}
			else{
				alert("Reseteo Cancelado ");
				return false;
			}
		});

		//MEMO 3
		$( "#mem" ).on( "click", function() {
		    var r=confirm("Esta seguro de reinicializar secuencia para radicados de Entrada?");
			if (r==true){
				$.ajax({
					type: "POST",
					url: "procesa_secuencias.php",
					data: { menu: 3 },
					beforeSend: function () {
	                        $("#cajatexto").html("Procesando, espere por favor...");
	                },
	                success:  function (response) { 
	                        $("#cajatexto").html(response);
	                }
				});
			}
			else{
				alert("Reseteo Cancelado ");
				return false;
			}
		});

		//CID 4
		$( "#cid" ).on( "click", function() {
		    var r=confirm("Esta seguro de reinicializar secuencia para radicados de Entrada?");
			if (r==true){
				$.ajax({
					type: "POST",
					url: "procesa_secuencias.php",
					data: { menu: 4 },
					beforeSend: function () {
	                        $("#cajatexto").html("Procesando, espere por favor...");
	                },
	                success:  function (response) { 
	                        $("#cajatexto").html(response);
	                }
				});
			}
			else{
				alert("Reseteo Cancelado ");
				return false;
			}
		});

		//Resoluciones 5
		$( "#res" ).on( "click", function() {
		    var r=confirm("Esta seguro de reinicializar secuencia para radicados de Entrada?");
			if (r==true){
				$.ajax({
					type: "POST",
					url: "procesa_secuencias.php",
					data: { menu: 5 },
					beforeSend: function () {
	                        $("#cajatexto").html("Procesando, espere por favor...");
	                },
	                success:  function (response) { 
	                        $("#cajatexto").html(response);
	                }
				});
			}
			else{
				alert("Reseteo Cancelado ");
				return false;
			}
		});

		//CIRCULARRES 6
		$( "#cir" ).on( "click", function() {
		    var r=confirm("Esta seguro de reinicializar secuencia para radicados de Entrada?");
			if (r==true){
				$.ajax({
					type: "POST",
					url: "procesa_secuencias.php",
					data: { menu: 6 },
					beforeSend: function () {
	                        $("#cajatexto").html("Procesando, espere por favor...");
	                },
	                success:  function (response) { 
	                        $("#cajatexto").html(response);
	                }
				});
			}
			else{
				alert("Reseteo Cancelado ");
				return false;
			}
		});

		//ACTAS 7
		$( "#actas" ).on( "click", function() {
		    var r=confirm("Esta seguro de reinicializar secuencia para radicados de Entrada?");
			if (r==true){
				$.ajax({
					type: "POST",
					url: "procesa_secuencias.php",
					data: { menu: 7 },
					beforeSend: function () {
	                        $("#cajatexto").html("Procesando, espere por favor...");
	                },
	                success:  function (response) { 
	                        $("#cajatexto").html(response);
	                }
				});
			}
			else{
				alert("Reseteo Cancelado ");
				return false;
			}
		});

		//AUTOS 8
		$( "#autos" ).on( "click", function() {
		    var r=confirm("Esta seguro de reinicializar secuencia para radicados de Entrada?");
			if (r==true){
				$.ajax({
					type: "POST",
					url: "procesa_secuencias.php",
					data: { menu: 2 },
					beforeSend: function () {
	                        $("#cajatexto").html("Procesando, espere por favor...");
	                },
	                success:  function (response) { 
	                        $("#cajatexto").html(response);
	                }
				});
			}
			else{
				alert("Reseteo Cancelado ");
				return false;
			}
		});

		//CONTRATOS 9
		$( "#contratos" ).on( "click", function() {
		    var r=confirm("Esta seguro de reinicializar secuencia para radicados de Entrada?");
			if (r==true){
				$.ajax({
					type: "POST",
					url: "procesa_secuencias.php",
					data: { menu: 9 },
					beforeSend: function () {
	                        $("#cajatexto").html("Procesando, espere por favor...");
	                },
	                success:  function (response) { 
	                        $("#cajatexto").html(response);
	                }
				});
			}
			else{
				alert("Reseteo Cancelado ");
				return false;
			}
		});

		//TODOS 10
		$( "#all" ).on( "click", function() {
		    var r=confirm("Esta seguro de reinicializar todas las secuencia?");
			if (r==true){
				$.ajax({
					type: "POST",
					url: "procesa_secuencias.php",
					data: { menu: 10 },
					beforeSend: function () {
	                        $("#cajatexto").html("Procesando, espere por favor...");
	                },
	                success:  function (response) { 
	                        $("#cajatexto").html(response);
	                }
				});
			}
			else{
				alert("Reseteo Cancelado ");
				return false;
			}
		});
    </script>
</body>
</html>