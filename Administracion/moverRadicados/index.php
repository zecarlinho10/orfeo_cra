<?php
/**
* @author Carlos Ricaurte   <carlinhoricaurte@hotmail.com>
* @license  GNU AFFERO GENERAL PUBLIC LICENSE
* Comisión de regulación de agua potable y saneamiento basico
* @copyright

SIIM2 Models are the data definition of SIIM2 Information System
Copyright (C) 2013 Infometrika Ltda.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

session_start();


$ruta_raiz = "../..";
if (!$_SESSION['dependencia']) header ("Location: $ruta_raiz/cerrar_session.php");
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

if (!defined('ADODB_ASSOC_CASE')) define('ADODB_ASSOC_CASE', 1);
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
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

<!DOCTYPE html>
<html>
<head>
	<title>.:.Mover Radicados.:.</title>
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="jquery.min.js"></script>
<script> 

function marcar(source) 
	{
		checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}

function confirmation() {
    if(confirm("Realmente desea Mover los radicados?"))
    {
        return true;
    }
    return false;
}

function notificacion(){
        //una notificación normal
      alertify.log("Radicados Editados exitosamente."); 
      return false;
}

</script> 

</head>
<body>

<?php

//session
$dependencia        = $_SESSION["dependencia"];
$usua_doc           = $_SESSION["usua_doc"];
$codusuario         = $_SESSION["codusuario"];
//post
$dependencia_origen = $_POST['dependencia_id'];
$funcionario_origen = $_POST['funcionario_id'];
$dependencia_destino = $_POST['dependenciaDes_id'];
$funcionario_destino = $_POST['funcionarioDes_id'];
$textoHistorico = $_POST['txt_observacion'];

$radicados = $_POST['radicados'];

$rs_dep=$db->query("SELECT DEPE_CODI, USUA_CODI, USUA_DOC FROM FLDOC.USUARIO WHERE USUA_LOGIN = '$funcionario_destino'");

//para insertar en radicado
$arregloUsuarioACtual = array();

while(!empty($rs_dep) && !$rs_dep->EOF){ 
	 	$arregloUsuarioACtual[]=$rs_dep->fields; 
	 	$rs_dep->MoveNext ();
	}

$cod_usua_actual=$arregloUsuarioACtual[0]["USUA_CODI"];
$doc_usua_actual=$arregloUsuarioACtual[0]["USUA_DOC"];

//echo "usuario codigo actual:" . $cod_usua_actual . "<br>";
//echo "usuario documento actual:" . $doc_usua_actual . "<br>";

$van=0;
for($i = 0; !empty($radicados) && $i < sizeof($radicados);$i++)
{
	//INSERTAR HISTORICOS
	$sql="INSERT INTO HIST_EVENTOS (DEPE_CODI, HIST_FECH, USUA_CODI, RADI_NUME_RADI, HIST_OBSE, USUA_CODI_DEST, USUA_DOC, SGD_TTR_CODIGO,HIST_DOC_DEST, DEPE_CODI_DEST)
	VALUES ($dependencia,(SYSDATE+0),1,$radicados[$i],'$textoHistorico',$cod_usua_actual,$usua_doc,9,'$doc_usua_actual',$dependencia_destino)";

	if ($db->query($sql) == TRUE) {
	    //echo "New Historico created successfully";
	    $van=1;
	} else {
	    //echo "Error: " . $sql . "<br>" . $db->error;
	}
	//UPDATE RADICADO
	$sql="UPDATE RADICADO SET RADI_DEPE_ACTU=$dependencia_destino, RADI_USUA_ACTU=$cod_usua_actual, RADI_USU_ANTE='$funcionario_origen', carp_codi=0, carp_per=0
	 WHERE RADI_NUME_RADI='$radicados[$i]'";

	if ($db->query($sql) == TRUE) {
	    //echo "update radicado created successfully";
	    $van=1;
	} else {
	    //echo "Error: " . $sql . "<br>" . $db->error;
	}
}

if ($van==1) {
	echo '<script type="text/javascript"> 
            alert("Radicados modificados exitosamente");
        </script> ';
}
?>


<div class="container">

<div class="row">

<div class="col-md-12">
<h1>Mover Radicados entre usuarios ORFEO CRA</h1>
</div>

</div>
<div class="row">
<div class="col-md-10">
<form method="post" action="index.php" onsubmit="return confirmation()">
  <div class="form-group">
    <label for="name1">Dependencia Origen</label>
    <select id="dependencia_id" class="form-control" name="dependencia_id" required>
      <option value="">-- SELECCIONE --</option>
		<?php foreach($raidDependencias as $d):?>
		      <option value="<?php echo $d["DEPE_CODI"] ?>"><?php echo $d["DEPE_NOMB"] ?></option>
		<?php endforeach; ?>
    </select>
  </div>

  <div class="form-group">
    <label for="name1">Funcionario Origen</label>
    <select id="funcionario_id" class="form-control" name="funcionario_id" required>
      <option value="">-- SELECCIONE --</option>
   </select>
  </div>

  <div class="form-group">
    <label for="name1">Radicados a trasladar</label>
    <!--
    <select id="radicado_id" class="form-control" name="radicado_id" required>
      <option value="">-- SELECCIONE --</option>
   </select>
   -->
    <!-- <div id="radicado_id" class="form-control" name="radicado_id"> -->
   		<table class="table table-responsive table-hover">
		    <thead>
		        <tr>
		            <th><input name="marca_todo" id="marca_todo" type="checkbox" onclick="marcar(this);" /> Marcar/Desmarcar todos</th>
		            <th>Numero</th>
		            <th>Fecha</th>
		            <th>Asunto</th>
		        </tr>
		    </thead>
		    <tbody id="radicado_id" name="radicado_id">

		    </tbody>
		</table>
   </div>

   <div class="form-group">
    <label for="name1">Dependencia Destino</label>
    <select id="dependenciaDes_id" class="form-control" name="dependenciaDes_id" required>
      <option value="">-- SELECCIONE --</option>
		<?php foreach($raidDependencias as $d):?>
			<option value="<?php echo $d["DEPE_CODI"] ?>"><?php echo $d["DEPE_NOMB"] ?></option>
		<?php endforeach; ?>
    </select>
  </div>

  <div class="form-group">
    <label for="name1">Funcionario Destino</label>
    <select id="funcionarioDes_id" class="form-control" name="funcionarioDes_id" required>
      <option value="">-- SELECCIONE --</option>
   </select>
  </div>

  <div class="form-group">
  	<label for="name1">Observación</label>
  	<textarea id="txt_observacion" name="txt_observacion" rows="5" cols="150" required></textarea>
  </div>

  <button type="submit" class="btn btn-default">Ejecutar Movimiento</button>
</form>
</div>
</div>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		$("#dependencia_id").change(function(){
			$.get("get_funcionarios.php","dependencia_id="+$("#dependencia_id").val(), function(data){
				$("#funcionario_id").html(data);
			});
		});

		$("#funcionario_id").change(function(){
			$.get("get_radicados.php","funcionario_id="+$("#funcionario_id").val(), function(data){
				$("#radicado_id").html(data);
			});
		});

		$("#dependenciaDes_id").change(function(){
			$.get("get_funcionarios.php","dependencia_id="+$("#dependenciaDes_id").val(), function(data){
				$("#funcionarioDes_id").html(data);
			});
		});
	});
</script>
</body>
</html>
