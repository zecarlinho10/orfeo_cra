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

<form action="actualizavencimiento.php" method="post">
 <p>Radicado: <input type="text" name="radicado" value = <? echo $valueRadicado ?> ></p>
  <p><input type="submit" value ="Actualizar fecha de Vencimiento" /></p>
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
		echo "<p>Actualizar Fecha Vencimiento del radicado No. $numRadicado </p>";
	
$fecha_de_radicacion = null;
$termino_radicado = 0;
$dias_festivos = 0;
	
		//Consulta en donde traigo todos los radicados que no aparecen en SGD_DIR_DRECCIONES
		$sqli = "select RADI_FECH_RADI,TDOC_CODI  from radicado where radi_nume_radi = $numRadicado";
		$rs = $db->conn->Execute($sqli);
		
		while(!$rs->EOF){
		echo "<br>  Fecha Radicacion =".	$fecha_de_radicacion = $rs->fields["RADI_FECH_RADI"]; 
			$tdoc_codi = $rs->fields["TDOC_CODI"];
			$rs->MoveNext();
		}
		
		//Consultamos la Terminologia
		$sql_getcodtpr ="select SGD_TPR_TERMINO  as Termino from sgd_tpr_tpdcumento where SGD_TPR_CODIGO = '$tdoc_codi'";
		$_exec_getcodtpr =$db->conn->Execute($sql_getcodtpr);
		$termino_radicado = $_exec_getcodtpr ->fields["TERMINO"];
echo "<br> Termino: ".$termino_radicado; 
echo "<br>";
$nuevafecha = $a->getFVencimiento($fecha_de_radicacion,$termino_radicado);
//echo "Dias Festivos: ".$dias_festivos; 
//echo "<br>";


  //               $sqli = "update radicado set FECH_VCMTO = '$nuevafecha' where radi_nume_radi = $numRadicado";
  //               $rs = $db->conn->Execute($sqli);

if ($rs){
echo "<br> Se a actualizado la fecha de vencimiento del radicado a : $nuevafecha";
}else{
echo "<br> a ocurrido un error al intentar actualizar el radicado"; 
}
$mitermino =  $a->getTermino($numRadicado);
echo "<br><br> Termino funcion = ".$mitermino; 
echo "<br><br> ";
$mifecha =  $a->getFechRadicado($numRadicado);
echo "Fecha de radicado =".$mifecha;

  echo "<br><br> ";
  $mifecha2 =  $a->getFVencimiento($mifecha,$mitermino);
  echo "Fecha de vencimiento =".$mifecha2;

 echo "<br><br> ";
 $midiasrestantes =  $a->getDiasRestantes($numRadicado);
 echo "Dias restantes = ".$midiasrestantes;
	}
}

?>

</body>

</html>


