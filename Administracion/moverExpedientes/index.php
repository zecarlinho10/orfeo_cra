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

?>

<!DOCTYPE html>
<html>
<head>
	<title>.:.Mover Radicados entre Expedientes Masivamente.:.</title>
<link rel="stylesheet" type="text/css" href="<?php echo $ruta_raiz?>/estilos/bootstrap.min.css">
<script type="text/javascript" src="<?php echo $ruta_raiz?>/js/jquery.min.js"></script>
<script> 


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
$expediente_origen = $_POST['expOrigen'];
$expediente_destino = $_POST['expDestino'];

//validar expedientes
		$corigen=0;
		$cdestino=0;

		$sql="SELECT COUNT(1) AS TOTAL FROM FLDOC.SGD_SEXP_SECEXPEDIENTES WHERE SGD_EXP_NUMERO = '$expediente_origen'";
		$rs_dep=$db->query($sql);

		while(!empty($rs_dep) && !$rs_dep->EOF){ 
			$corigen=$rs_dep->fields['TOTAL']; 
	 		$rs_dep->MoveNext ();
		}

		$sql="SELECT COUNT(1) AS TOTAL FROM FLDOC.SGD_SEXP_SECEXPEDIENTES WHERE SGD_EXP_NUMERO = '$expediente_destino'";
		$rs_dep=$db->query($sql);

		while(!empty($rs_dep) && !$rs_dep->EOF){ 
			$cdestino=$rs_dep->fields['TOTAL']; 
	 		$rs_dep->MoveNext ();
		}

		$valida=0;
		if(!empty($expediente_origen) or !empty($expediente_destino)){
			
			if($corigen==0){ 
					echo '<script type="text/javascript"> 
				          alert("El expediente origen no existe");
				        </script> ';
				  $valida=1;
			}

			if($cdestino==0){ 
					echo '<script type="text/javascript"> 
				          alert("El expediente destino no existe");
				        </script> ';
				  $valida=1;
			}
		}

	if($valida==0){
		$cradicados=0;
		if(!empty($expediente_origen) or !empty($expediente_destino)){
			$textoHistorico = "Movimiento masivo de radicados de expediente origen: " . $expediente_origen . " a expediente destino: " . $expediente_destino;
			
			$arregloRadicados = array();
			
			$sql="SELECT E1.RADI_NUME_RADI 
				  FROM SGD_EXP_EXPEDIENTE  E1
				  WHERE E1.SGD_EXP_NUMERO = '$expediente_origen' AND
      					E1.RADI_NUME_RADI NOT IN (SELECT E2.RADI_NUME_RADI 
                              FROM SGD_EXP_EXPEDIENTE  E2 
                              WHERE E2.SGD_EXP_NUMERO = '$expediente_destino'
                              )";
			$rs_dep=$db->query($sql);


			while(!empty($rs_dep) && !$rs_dep->EOF){ 
				$arregloRadicados[]=$rs_dep->fields['RADI_NUME_RADI']; 
		 		$cradicados++;
		 		$rs_dep->MoveNext ();
			}

			$sql="UPDATE SGD_EXP_EXPEDIENTE E1
					SET SGD_EXP_NUMERO = '$expediente_destino' 
					WHERE SGD_EXP_NUMERO = '$expediente_origen' AND
					      E1.RADI_NUME_RADI NOT IN (SELECT E2.RADI_NUME_RADI 
					        FROM SGD_EXP_EXPEDIENTE  E2 
					        WHERE E2.SGD_EXP_NUMERO = '$expediente_destino')";

			$van=0;
			if ($db->query($sql) == TRUE)
				    $van=1;

			if ($van==1) {
				for($i = 0; $i < sizeof($arregloRadicados);$i++){
					//INSERTAR HISTORICOS
					$sql="INSERT INTO HIST_EVENTOS (DEPE_CODI, HIST_FECH, USUA_CODI, RADI_NUME_RADI, HIST_OBSE, USUA_CODI_DEST, USUA_DOC, SGD_TTR_CODIGO,HIST_DOC_DEST, DEPE_CODI_DEST)
					VALUES ($dependencia,(SYSDATE+0),$codusuario ,$arregloRadicados[$i],'$textoHistorico',$codusuario,$usua_doc,62,'$codusuario',$dependencia)";
					if ($db->query($sql) == TRUE) {
					    $van=1;
					} 
					
			  }

			  echo '<script type="text/javascript"> 
			            alert("' . $cradicados . ' Radicados modificados exitosamente");
			        </script> ';
			}
			else  {
				echo '<script type="text/javascript"> 
			            alert("Error, validar expedientes");
			        </script> ';
			}
		}
	}	
		?>


<div class="container">

	<div class="row">

		<div class="col-md-12">
			<h1>Mover Radicados entre Expedientes</h1>
		</div>

	</div>
	<div class="row">
		<div class="col-md-10">
			<form method="post" action="index.php" onsubmit="return confirmation()">
			  <div class="form-group">
			    <label for="name1">Expediente Origen :</label>
			    <input type="text" name="expOrigen">
			  </div>

			  <div class="form-group">
			    <label for="name2">Expediente Destino:</label>
			    <input type="text" name="expDestino">
			  </div>

			  <button type="submit" class="btn btn-default">Ejecutar Movimiento</button>
			</form>
		</div>
	</div>
</div>

</body>
</html>
