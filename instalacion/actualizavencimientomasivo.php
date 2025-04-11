<?php

session_start();
define('ADODB_ASSOC_CASE', 2);
ini_set("display_errors",1);
include_once "../include/db/ConnectionHandler.php";
require_once("../class_control/Mensaje.php");
if (!$db) $db = new ConnectionHandler("..");
//$db->conn->debug = true;
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);


//include_once "../tx/diasHabiles.php";
include_once "../tx/diasHabilesmio.php";
 $a = new FechaHabil($db);

?>

<html>
<head>
</head>

<form action="actualizavencimientomasivo.php" method="post">
  <p><input type="submit" name="radicado" value ="Actualizar fecha de Vencimiento" /></p>
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
		echo "<p>Actualizar Fecha Vencimiento Masivo de los radicados que fallan </p>";



		//Consulta en donde traigo todos los radicados que no aparecen en SGD_DIR_DRECCIONES
		$sqli = "select R.RADI_NUME_RADI as NUMERO_RADICADO,
				 R.RADI_FECH_RADI AS FECHA_RADICADO ,
			     R.FECH_VCMTO AS FECHA_VENCIMIENTO,
		   	     T.SGD_TPR_TERMINO AS DIAS_TERMINO from radicado R,
	 	 		 sgd_tpr_tpdcumento T  
				 where 
				 R.radi_fech_radi > '2015-11-01' 
				 and R.radi_fech_radi < '2015-11-30' 
				 AND R.FECH_VCMTO IS not NULL 
				 and R.TDOC_CODI is not null 
				 AND t.SGD_TPR_CODIGO = r.TDOC_CODI";
//				 and rownum <= 500";
		$rs = $db->conn->Execute($sqli);
	$j = 0;	
	$k = 0;
	$t = 0;	
	while(!$rs->EOF){
		$t++;
			 $numero_radicado = $rs->fields["NUMERO_RADICADO"]; 
			 $fecha_radicado = $rs->fields["FECHA_RADICADO"];
			 $fecha_vencimiento = $rs->fields["FECHA_VENCIMIENTO"];
			 $dias_termino = $rs->fields["DIAS_TERMINO"];

			 $fecha_real_vencimiento =  $a->getFVencimiento($fecha_radicado,$dias_termino);

			 if ($fecha_real_vencimiento != $fecha_vencimiento)
			 {
				 $j++;
 		 	  echo "<br>".$numero_radicado;	 
			  echo  "<br> ( <font color = red> ".$fecha_vencimiento."</font>";
			  echo  "<br> <font color = blue> ".$fecha_real_vencimiento."</font> )";

				 
 				   $sql2 = "update radicado set FECH_VCMTO = '$fecha_real_vencimiento' where radi_nume_radi = $numero_radicado";
                 $rs2 = $db->conn->Execute($sql2);

				if (!$rs2){
					echo "<br> a ocurrido un error al intentar actualizar el radicado".$numero_radicado; 
				}
				
			 }else{
				$k++;
 		 	  //echo "<br>".$numero_radicado;	 
	   		  //echo  "<br>-> <font color = green> ".$fecha_vencimiento."</font>";
			 }

			$rs->MoveNext();
		}

echo "<br>Malos ->".$j." / ".$t;
echo "<br>Buenos ->".$k." / ".$t;

	}
}

?>

</body>

</html>


