<?php

require_once 'nusoap8/src/nusoap.php';
$ruta_raiz = "../";
define('RUTA_RAIZ', '../');

require_once "../config.php";

include_once '../include/class/Unoconv.php';
use Unoconv\Unoconv;
include_once RUTA_RAIZ."include/db/ConnectionHandler.php";
require_once RUTA_RAIZ."fpdf/fpdf.php";

// Esto le dice a PHP que usaremos cadenas UTF-8 hasta el final
mb_internal_encoding('UTF-8');
 
// Esto le dice a PHP que generaremos cadenas UTF-8
mb_http_output('UTF-8');

// Crear una instancia del servidor SOAP
$server = new soap_server();

// Definir el namespace y el endpoint del servicio web
$namespace = 'https://gestiondocumental.cra.gov.co/webServices/servidorWS';
//$namespace = 'http://192.168.25.66/orfeo/webServices/servidorWS';
//$endpoint = 'http://ejemplo.com/servidorWS.php';
$endpoint = 'https://gestiondocumental.cra.gov.co/webServices/servidorWS.php';
//$endpoint = 'http://192.168.25.66/orfeo/webServices/servidorWS.php';


// Configurar el WSDL
$server->configureWSDL('Sistema de Gestion Documental Orfeo-Internas', $namespace, $endpoint);

// Registrar un método en el servicio web
$server->register('radicarTHplantilla',                  // Nombre del método
    array('file' => 'xsd:string',
	'fileName' => 'xsd:string',
	'usuarioOrigen' => 'xsd:string',
	'usuarioDestino' => 'xsd:string',
	'asunto' => 'xsd:string',
	'fechaInicio' => 'xsd:string',
    'fechaFin' => 'xsd:string',
    'fechaRegreso' => 'xsd:string',
    'dias' => 'xsd:string',
    'observaciones' => 'xsd:string',
	'key'	=> 'xsd:string'),
	array('radicado' => 'xsd:string'),   
    $namespace                             // Namespace
);

$server->register('autorizaSARA',                  // Nombre del método
    array('radicado' => 'xsd:string',
		'usuarioOrigen' => 'xsd:string',
		'usuarioDestino' => 'xsd:string',
		'asunto' => 'xsd:string',
		'aurtoriza' => 'xsd:string',
		'key'	=> 'xsd:string'),
	array('return' => 'xsd:string'),   
    $namespace                             // Namespace
);

function radicarTHplantilla($file,$filename,$usuarioOrigen,$usuarioDestino,$asunto, $fechaInicio, $fechaFin, $fechaRegreso, $dias, $observaciones, $key){

	$asunto = iconv('ISO-8859-1','UTF-8//TRANSLIT',$asunto);
	$observaciones = iconv('ISO-8859-1','UTF-8//TRANSLIT',$observaciones);

	  

	//crea cadena ky
	$semilla = "SARA2020ORFEO";

	$dia = date("d");
	$mes = date("m");
	$ano = date("Y");
	$fecha=$dia . $mes . $ano;

	$cadena = $fecha . $usuarioOrigen . $usuarioDestino . $semilla;

	$keyWS = md5($cadena);
	
	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
		//Conversiones de datos para compatibilidad con aplicaciones internas
		$destinatario = array(
		'idcont'=>1,
		'idpais'=>170,
		'codep'=>321,
		'muni'=>1
		);
		// TODO: Verificar el uso de la variable $usua_destino.
		//$usua_destino;
		try {
			$usua_destino = getInfoJefe($usuarioDestino);
			$usua_origen = getInfoUsuarioXlogin($usuarioOrigen);
			//$coddepe = $radi_usua_actu;
		}catch (Exception $e){
			return $e->getMessage();
		}
		// Finº
		global $ruta_raiz;
		include(RUTA_RAIZ."include/tx/Tx.php") ;
		include(RUTA_RAIZ."include/tx/Radicacion.php") ;
		include(RUTA_RAIZ."class_control/Municipio.php") ;
		$db = new ConnectionHandler($ruta_raiz) ;
		$hist = new Historico($db) ;
		$tmp_mun = new Municipio($db) ;
		$rad = new Radicacion($db) ;
		$tmp_mun->municipio_codigo($destinatario["codep"],$destinatario["muni"]) ;
		$tpRadicado=2;
		$rad->radiTipoDeri = $tpRadicado ;
		$rad->radiTipoDeri = 2 ;
		$rad->tipRad=2;
		$rad->radiCuentai = "'".trim($cuentai)."'";
		$rad->eespCodi =  $esp["documento"] ;
		$rad->mrecCodi =  15;
		$rad->radiFechOfic =  date("Y-m-d");
		if(!$radicadopadre)  $radicadopadre = null;
		$rad->radiNumeDeri = trim($radicadopadre) ;
		$rad->radiPais =  $tmp_mun->get_pais_codi() ;
		$rad->descAnex = $ane ;
		$rad->raAsun = $asunto ;
		$rad->radiDepeActu = $usua_destino['usua_depe'] ;
		$rad->radiDepeRadi = 321 ;
		//$rad->radiUsuaActu = trim($radi_usua_actu['usua_codi']);
		$rad->radiUsuaActu = $usua_destino['usua_codi'] ;;
		$rad->trteCodi =  $tip_rem ;
		//$rad->tdocCodi=$tdoc ;
		$rad->tdocCodi=1699 ;
		$rad->tdidCodi=$tip_doc;
		$rad->carpCodi = $carp_codi ;
		//$rad->carPer = $carp_per ;
		$rad->trteCodi=$tip_rem ;
		$rad->radiPath = 'null';
		if (strlen(trim($aplintegra)) == 0)
				$aplintegra = "0" ;
		$rad->sgd_apli_codi = $aplintegra ;
		$codTx = 2 ;
		$flag = 1 ;
		$rad->usuaCodi=$rad->radiUsuaActu ;
		//$rad->dependencia=$usua_destino['usua_depe'];
		$rad->dependencia=321;
		
		$noRad = $rad->newRadicado(2,321) ;
		
		$nurad = trim($noRad) ;
		if ($noRad=="-1")
		{
			return "Error no genero un Numero de Secuencia o Inserto el radicado";
		}

		$radicadosSel[0] = $noRad;
		$hist->insertarHistorico($radicadosSel,321 , 775, $usua_destino['usua_depe'], $usua_destino['usua_codi'], $asunto, $codTx);
		$sgd_dir_us2=2;
		$conexion = $db;
		//$conexion->set_charset('utf8');
		//INSERT DIRECCIONES_
		$nextval=$conexion->nextId("SEC_DIR_DIRECCIONES");
		

		$isql="SELECT MAX(SGD_DIR_CODIGO)+1 AS CODIGO FROM SGD_DIR_DRECCIONES";
		$rs = $conexion->query($isql);
		if (!empty($rs) &&  !$rs->EOF){
			 $nextval = $rs->fields['CODIGO'];
		   } else {
		   	$salida['error']="El ususario no existe o se encuentra deshabilitado";
		   }

		//return $nextval;
		$isql = "insert into SGD_DIR_DRECCIONES(SGD_TRD_CODIGO, SGD_DIR_NOMREMDES, SGD_DIR_DOC, DPTO_CODI, MUNI_CODI,id_pais, id_cont, 
						SGD_DOC_FUN, RADI_NUME_RADI, SGD_SEC_CODIGO, SGD_DIR_DIRECCION, SGD_DIR_MAIL, SGD_DIR_TIPO, SGD_DIR_CODIGO, SGD_DIR_NOMBRE)
			  			values('1', '" . $usua_origen['usua_nomb'] . "', '" . $usua_origen['usua_doc'] . "', 11,1, 170, 1,
			  			'" . $usua_origen['usua_doc'] . "', $nurad, 0,'" . $usua_origen['usua_dependencia'] . "', '" . $usua_origen['usua_email'] . "', 1, $nextval, '".$usua_origen['usua_nomb']."')";
		
		
						//return $isql;


		$rsg=$conexion->query($isql);
			if (!empty($rsg) && !$rsg){
				return "No se ha podido actualizar la informacion de DIRECCIONES";
			}
			else {
				//return $nurad;
			}

		//SUBE ADJUNTO SI TIENE
			//pendiente para anexo
		if(!empty($file)){

			$temp= explode('.',$filename);
			$extension = strtolower(end($temp));
			$tipo_anexo=7;
			if($extension=="pdf"){
				$tipo_anexo=7;
			}
			else if($extension=="png"){
				$tipo_anexo=17;
			}
			else if($extension=="jpg"){
				$tipo_anexo=15;
			}
			else if($extension=="jpeg"){
				$tipo_anexo=27;
			}
			//echo "extension:".$extension;

			$auxnumero = "00001";
			$codigo = $noRad.$auxnumero;
			$archivoconversion=trim("1").trim(trim($noRad))."_".trim($auxnumero).".".$extension;
			$dependencia = "11";
			$sqlFechaHoy= $db->conn->OffsetDate(0,$db->conn->sysTimeStamp);

			$isql = "insert into anexos (sgd_rem_destino, anex_radi_nume, anex_codigo, anex_tipo, anex_tamano,
										anex_solo_lect, anex_creador, anex_desc, anex_numero, anex_nomb_archivo,
										anex_borrado, anex_salida, anex_depe_creador, sgd_tpr_codigo, anex_fech_anex)
				values ('1', $noRad, $codigo, '$tipo_anexo', '1', 
				'N','RWEB', 'Anexo solicitud', '1','$archivoconversion', 
				'N', '0', $dependencia, '0', $sqlFechaHoy)";
				//echo $isql;
			$insertAnexo = $db->conn->query($isql);
			
			$radi_path_base="/".substr($noRad,0,4)."/321/docs/".$noRad.".".$extension;
			// TODO: Validar , por que la ruta de bodega copia ha sido, protegida. 
			$pathSALIDA=BODEGA."/".substr($noRad,0,4)."/321/docs/".$archivoconversion;
			// decode base64
			$pdf_b64 = base64_decode($file);

			// you record the file in existing folder
			if(file_put_contents($pathSALIDA, $pdf_b64)){
			    //just to force download by the browser
			    header("Content-type: application/pdf");

			    
			}
			else{
				//TODO: Codigo Else sin instrucciones, considerar eliminar la sentencia
				//$sql="UPDATE RADICADO SET RADI_PATH='$pathSALIDA' WHERE RADI_NUME_RADI=$noRad";
			    //$rsg=$conexion->query($sql);
			}
		}
		///GENERAR OFICIO PDF
		$pathDB = "/" . substr($noRad, 0, 4) . "/321/";
		crearPdf($noRad,$usua_origen['usua_nomb'],$usua_origen['usua_doc'],$asunto, $fechaInicio, $fechaFin, $fechaRegreso, $dias, $observaciones,"plantilla_permisos.odt");
		$rad->updatePath("'$pathDB". $noRad . ".pdf'", $noRad, 1);
		return $noRad;
	}
	else
	{
		return "Error, Llave de consulta no valida";
		//return $fecha;
	}

	return "aaaaa";
}

function autorizaSARA($radicado, $usuarioOrigen,$usuarioDestino, $asunto, $aurtoriza,$key)
{
	//global $keyWS;
	//crea cadena ky
	$semilla = "SARA2020ORFEO";
	$cadena = $radicado . $usuarioOrigen . $usuarioDestino . $semilla;

	$keyWS = md5($cadena);
	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{	
		//Conversiones de datos para compatibilidad con aplicaciones internas
		$destinatario = array(
		'idcont'=>1,
		'idpais'=>170,
		'codep'=>11,
		'muni'=>1
		);
		$usua_destino;
		try {
			$usua_actual = getInfoUsuarioXlogin($usuarioOrigen);
			$usua_destino = getInfoUsuarioXlogin($usuarioDestino);
			$coddepe = trim($usua_actual['usua_depe']);
		}catch (Exception $e){
			return $e->getMessage();
		}
		// Fin
		global $ruta_raiz;
		include(RUTA_RAIZ."include/tx/Tx.php") ;
		$db = new ConnectionHandler($ruta_raiz) ;
		$hist = new Historico($db) ;
		include(RUTA_RAIZ."include/tx/Radicacion.php") ;
		$rad = new Radicacion($db) ;
		$tpRadicado=2;
		$rad->raAsun = $asunto ;
		$rad->radiDepeActu = $radi_usua_actu['usua_depe'] ;
		$rad->radiUsuaActu = trim($radi_usua_actu['usua_codi']);
		$rad->radiUsuaActu = 1;
		$rad->trteCodi =  $tip_rem ;
		$rad->usuaCodi=$rad->radiUsuaActu ;
		$radicadosSel[0]=$radicado;
		$codTx=8;
		$hist->insertarHistorico($radicadosSel,  $usua_actual['usua_depe'] , $usua_actual['usua_codi'], $usua_destino['usua_depe'] , $usua_destino['usua_codi'], $asunto, $codTx);
		$sgd_dir_us2=2;	
		return "ok";
	}
	else
	{
		return "Error, Llave de consulta no valida";
	}
}

function getInfoJefe($usuaLogin){
	global $ruta_raiz;
		$db = new ConnectionHandler($ruta_raiz);

        $sql="SELECT U.USUA_LOGIN, U.USUA_DOC, U.DEPE_CODI, U.CODI_NIVEL, U.USUA_CODI, U. USUA_NOMB, U.USUA_EMAIL 
			FROM USUARIO U 
			WHERE USUA_CODI=1 AND USUA_ESTA = 1 AND U.USUA_LOGIN LIKE '%$usuaLogin%'";
        $rs=$db->query($sql);
        //echo $sql;
		//TODO: Los cursores deben validar si estan vacios con !empty
        if(!empty($rs) && !$rs->EOF){
                		$salida['usua_login']=($rs->fields["USUA_LOGIN"]);
                        $salida['usua_doc'] =($rs->fields["USUA_DOC"]);
                        $salida['usua_depe'] =($rs->fields["DEPE_CODI"]);
                        $salida['usua_nivel'] =($rs->fields["CODI_NIVEL"]);
                        $salida['usua_codi'] =($rs->fields["USUA_CODI"]);
                        $salida['usua_nomb'] =($rs->fields["USUA_NOMB"]);
                        $salida['usua_email'] =($rs->fields["USUA_EMAIL"]);
        }else{
        	throw new Exception("El usuario $usuaLogin no existe $sql");
        }
        $salida['usua_doc'] = getCedulaMasCorta($usuaLogin,$salida['usua_doc']);
        return $salida;
}

function getInfoUsuarioXlogin($usuaLogin){
	global $ruta_raiz;
		$db = new ConnectionHandler($ruta_raiz);

        $sql="SELECT  U.USUA_LOGIN, U.USUA_DOC, U.DEPE_CODI, U.CODI_NIVEL, U.USUA_CODI, U.USUA_NOMB, U.USUA_EMAIL , D.DEPE_NOMB
        		FROM USUARIO U, DEPENDENCIA D
                WHERE rownum = 1 AND D.DEPE_CODI=U.DEPE_CODI AND USUA_ESTA = 1 AND USUA_LOGIN LIKE '%$usuaLogin%' ORDER BY U.DEPE_CODI";

        $rs=$db->query($sql);
		        //TODO: Los cursores deben validar si estan vacios con !empty
                if(!empty($rs) && !$rs->EOF){
                		$salida['usua_login']=($rs->fields["USUA_LOGIN"]);
                        $salida['usua_doc'] =($rs->fields["USUA_DOC"]);
                        $salida['usua_depe'] =($rs->fields["DEPE_CODI"]);
                        $salida['usua_nivel'] =($rs->fields["CODI_NIVEL"]);
                        $salida['usua_codi'] =($rs->fields["USUA_CODI"]);
                        $salida['usua_nomb'] =($rs->fields["USUA_NOMB"]);
                        $salida['usua_email'] =($rs->fields["USUA_EMAIL"]);
                        $salida['usua_dependencia'] =($rs->fields["DEPE_NOMB"]);
        }else{
        	throw new Exception("El usuario $usuaLogin no existe $sql");
        }
        $salida['usua_doc'] = getCedulaMasCorta($usuaLogin,$salida['usua_doc']);
        return $salida;
}

function getCedulaMasCorta($login,$cedula){
	global $ruta_raiz;
		$db = new ConnectionHandler($ruta_raiz);

	$ced = substr($cedula, 0, 6);

        $sql="SELECT USUA_DOC 
			FROM USUARIO
			WHERE USUA_DOC LIKE '$ced"."%' AND USUA_LOGIN LIKE '$login%'";
        $rs=$db->query($sql);
        $usuario_corto="12345678901234567890";
        //TODO: Los cursores deben validar si estan vacios con !empty
        while (!$rs->EOF){
        	if(strlen($usuario_corto)>strlen($rs->fields["USUA_DOC"])){
        		$usuario_corto=$rs->fields["USUA_DOC"];
        	}
			$rs->MoveNext();
		}
        return $usuario_corto;
}

 /**FUNCION GENERA PDF*/

function crearPdf($radicado,$nombre,$cedula,$asunto, $fechaInicio, $fechaFin, $fechaRegreso, $dias, $observaciones,$plantilla)
    {
        include realpath(dirname(__FILE__) . "/../") . '/config.php';

        /********************************ODT_EDITOR*************************************************/
        // Create the Object.
        $zip = new ZipArchive();
        //use Unoconv\Unoconv;
        
        $anio = substr($radicado,0,4);
        $carpeta = substr($radicado,4,3);

        // Use same filename for "save" and different filename for "save as".
        $ruta = realpath(dirname(__FILE__) . "/../");
        //$inputFilename = $ruta . $plantilla;
        // TODO: Verificar para utilizar la CTE BODEGA
		$inputFilename = BODEGA.'/plantillas/' . $plantilla;
        
        $nuevo_fichero = BODEGA.'/' . $anio . '/' . $carpeta . '/' . $radicado .  '.odt';
        
        //echo $inputFilename . "..\n";
        //echo $nuevo_fichero . "..\n";
        //echo $anio . "..\n";
        //echo $carpeta . "..\n";
        //echo $ruta . "..\n";
        
        if (!copy($inputFilename, $nuevo_fichero)) {
            echo "Error al copiar $fichero...\n";
            echo $inputFilename . "..\n";
            echo $nuevo_fichero . "..\n";
        }

        // Open the Microsoft Word .docx file as if it were a zip file... because it is.
        if ($zip->open($nuevo_fichero, ZipArchive::CREATE)!==TRUE) {
            echo "Cannot open $filename :( "; die;
        }

        // Fetch the document.xml file from the word subdirectory in the archive.
        $xml = $zip->getFromName('content.xml');
        
       	$xml = str_replace ( "*RAD_S*" , $radicado , $xml);
       	$xml = str_replace ( "*F_RAD_S*" , $radicado , $xml);
       	$xml = str_replace ( "*NOMBRE*" , $nombre , $xml);
       	$xml = str_replace ( "*INDENTIFICACION*" , $cedula , $xml);
       	$xml = str_replace ( "*asunto*" , $asunto , $xml);
       	$xml = str_replace ( "*fechaInicio*" , $fechaInicio , $xml);
       	$xml = str_replace ( "*fechaFin*" , $fechaFin , $xml);
       	$xml = str_replace ( "*fechaRegreso*" , $fechaRegreso , $xml);
       	$xml = str_replace ( "*dias*" , $dias , $xml);
       	$xml = str_replace ( "*observaciones*" , $observaciones , $xml);
       	    
        //TODO: Condicional, sin expresiones de acción
		if ($zip->addFromString('content.xml', $xml)) {
            //echo 'File written!'; 
        }
        else { 
        	//echo 'File not written.  Go back and add write permissions to this folder!l'; 
    	}

        $zip->close();

        /********************************FIN DOCX_EDITOR*************************************************/

        /********************************CONVIERTE ODT A PDF ***************************************/
        
		// TODO: Verificar Rutas, se debe utilizar BODEGA.
		
        $originFilePath = BODEGA."/" . $anio . "/" . $carpeta . "/" . $radicado . ".odt";
         
        $inputFile = $originFilePath;

		// Ruta del archivo PDF de salida
		$outputFile = $outputPath;

		//$originFilePath = "/bodega/bodegaOrfeo/2023/900/20239000004552.odt";
		$command = "export HOME=/tmp; unoconv -f pdf $originFilePath";
		$outputDirPath ="";

		$last_line = system($command,   $outputDirPath );

        return 3;
    }

// Procesar la solicitud del cliente
$rawPostData = file_get_contents('php://input');
$server->service($rawPostData);

?>