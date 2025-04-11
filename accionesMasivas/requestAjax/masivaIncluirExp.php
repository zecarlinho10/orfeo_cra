<?php
/*

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

session_start();

$ruta_raiz = "./";

if (!$_SESSION['dependencia']) header ("Location: $../../cerrar_session.php");

include_once( "../../include/db/ConnectionHandler.php" );
include_once( "../../include/tx/Expediente.php" );
include_once( "../../include/tx/Historico.php" );

$db = new ConnectionHandler( "$ruta_raiz" );

$expediente = new Expediente( $db );

$radicados=explode(",", $_POST['radicados']);
$nuExpediente=$_POST['expedienteain'];


	$mensaje5		= "El expediente esta inactivo o no existe";
	$mensaje10		= "Incluir radicado en Expediente - Masiva";//Mensaje de informacion
	$mensaje4 		= "No se inserto ningun radicado en el expediente";//Mensaje de informacion
	$error		=$radicados;

	$radicados 	= array_filter($radicados);
	
	$sqlBus 	= "SELECT COUNT(1) AS TOTAL
					FROM SGD_SEXP_SECEXPEDIENTES
					WHERE SGD_EXP_NUMERO  LIKE '$nuExpediente'
						AND (SGD_CERRADO <> 1 OR SGD_CERRADO IS NULL)";


	$salida = $db->conn->Execute($sqlBus);


	if($salida->fields["TOTAL"]==0){		
			echo("EL expediente $nuExpediente no existe o está cerrado.");	
			return;
	}
	else{
		$Historico = new Historico( $db );			
		$rad_error = "";
		$rad_gragados = "";
		$radicadosOK="";
		$RaiRadicadosOK;
		$radicadosBAD="";

		$cont=0;
	    foreach ($radicados as $actual){
			// Consulta si el radicado esta incluido en el expediente.	
			
			$sqlCon = "	SELECT 
							COUNT(1) AS TOTAL
						FROM 
							SGD_EXP_EXPEDIENTE SE
						WHERE 
							SE.SGD_EXP_NUMERO like '$nuExpediente'
	      					AND SE.RADI_NUME_RADI = $actual";
			
			$existeEnExp = $db->conn->Execute($sqlCon);
			
			$cant = $existeEnExp->fields["TOTAL"];
			
			$observa=$mensaje10;
			$tipoTx = 53;

			$depenUsua        	= $_SESSION["dependencia"];
			$usua_doc           = $_SESSION["usua_doc"];
			$codusuario         = $_SESSION["codusuario"];

	        if ($cant==0){
	            $saliExp = $expediente->insertar_expediente(
															$nuExpediente
															,$actual
															,$depenUsua
															,$codusuario
															,$usua_doc);
	            if($saliExp==1){
	            	$radicadosOk.=",$actual";
		            $RaiRadicadosOK[$cont]=$actual;
		        }
	            $cont++;
			}
			else{
				$radicadosBAD.=",$actual";
			}
			
			
	        
	    }
	
		$observa = $mensaje10; 
	   	$Historico->insertarHistorico($RaiRadicadosOK,  $depenUsua , $codusuario, $depenUsua,$codusuario, $observa, $tipoTx);
	  	$accion= array( 'respuesta' => true,
						'mensaje'	=> $rad_gragados.' ',
						'existen' 	=> $rad_error.'ok');	
		
		echo("Los radicados: " . $radicadosOk . " fueron ingresados exitosamente en el expediente:" . $nuExpediente . "<br><br>");
		if($radicadosBAD!="")
			echo("Los radicados: " . $radicadosBAD . " NO fueron ingresados en el expediente:" . $nuExpediente . "<br>");
	}

?>
