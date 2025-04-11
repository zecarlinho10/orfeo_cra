<?php

session_start();
define('ADODB_ASSOC_CASE', 2);
ini_set("display_errors",1);
include_once "../include/db/ConnectionHandler.php";
require_once("../class_control/Mensaje.php");
if (!$db) $db = new ConnectionHandler("..");
//$db->conn->debug = true;
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);


 include_once "../tx/diasHabiles.php";
 $a = new FechaHabil($db);

?>

<html>
<head>
</head>

<form action="colocardignatario.php" method="post">
 <p>Radicado: <input type="text" name="radicado" value = <? echo $valueRadicado ?> ></p>
  <p><input type="submit" value ="Actualizar Dignatario" /></p>
</form>

<body>
<?
//$isql="insert into autg_grupos (nombre,descripcion) values ('--láááaaa','señor')";
//$isql=utf8_decode(utf8_encode ($isql));
?>
<?
if (!isset($_POST['radicado'])) {
	$Mensaje = "";
}else{
	if (!$_POST['radicado']>1) {
		$Mensaje ="";
	}else{
		$numRadicado =trim($_POST['radicado']);
		echo "<p>Agregamos datos del radicado padre a $numRadicado </p>";
	

		//Consulta en donde traigo todos los radicados que no aparecen en SGD_DIR_DRECCIONES
		$sqli = "select ANEX_RADI_NUME from anexos where radi_nume_salida = $numRadicado";
		$rs = $db->conn->Execute($sqli);
		
		while(!empty($rs) && !$rs->EOF){
			$numrad = $rs->fields["ANEX_RADI_NUME"];
			$rs->MoveNext();
		}
	
echo "radicado padre =".$numrad;
echo "<br>";

$sql = "select * from sgd_dir_drecciones where radi_nume_radi = $numRadicado";
$rs = $db->conn->Execute($sql);

 while(!!empty($rs) && $rs->EOF){
   $control = $rs->fields["RADI_NUME_RADI"];
   $rs->MoveNext();
  } 

if (!$control){
include 'dignatario_radicado_anexo.php'; 
}else{
echo "<br> Este radicado ya tiene una direccion asignada";
}

//include 'dignatario_radicado_anexo.php';	

	}
}

?>

</body>

</html>


