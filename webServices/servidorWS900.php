<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');


//header("Content-Type: text/html;charset=UTF-8");

session_start();
// Esto le dice a PHP que usaremos cadenas UTF-8 hasta el final
mb_internal_encoding('UTF-8');
 
// Esto le dice a PHP que generaremos cadenas UTF-8
mb_http_output('UTF-8');

/**********************************************************************************
Diseno de un Web Service que permita la interconexion de aplicaciones con Orfeo
**********************************************************************************/
/**
 *
 * @author Carlos Ricaurte
 */
//Llamado a la clase nusoap
$ruta_raiz = "../";
define('RUTA_RAIZ','../');
require_once "nusoap/lib/nusoap.php";
include_once '../include/class/Unoconv.php';
use Unoconv\Unoconv;
include_once RUTA_RAIZ."include/db/ConnectionHandler.php";
require_once RUTA_RAIZ."fpdf/fpdf.php";
//Asignacion del namespace
$ns="webServices/nusoap";
//Creacion del objeto soap_server
$server = new soap_server();
$server->configureWSDL('Sistema de Gestion Documental Orfeo-Internas',$ns);
/*********************************************************************************
Se registran los servicios que se van a ofrecer, el metodo register tiene los sigientes parametros
**********************************************************************************/

$server->register('radicarTHplantilla',
	array(
		//archivo en base 64
		'file' => 'xsd:string',
		'fileName' => 'xsd:string',
		'usuarioOrigen' => 'xsd:string',
		'usuarioDestino' => 'xsd:string',
		'asunto' => 'xsd:string',
		'fechaInicio' => 'xsi:string',
        'fechaFin' => 'xsi:string',
        'fechaRegreso' => 'xsi:string',
        'dias' => 'xsi:string',
        'observaciones' => 'xsi:string',
		'key'	=> 'xsd:string'
	),
	array(
		'return' => 'xsd:string'
	),
	$ns,
	$ns."#radicarTHplantilla",
	'rpc',
	'encoded',
	'Radicacion de un documento en Orfeo'
);
/*
CARLOS RICAURTE MARZO 2022
*/
$server->register('autorizaSARA',
	array(
		//'file' => 'xsd:base64binary',										//archivo en base 64
		'radicado' => 'xsd:string',
		'usuarioOrigen' => 'xsd:string',
		'usuarioDestino' => 'xsd:string',
		'asunto' => 'xsd:string',
		'aurtoriza' => 'xsd:string',
		'key'	=> 'xsd:string'
	),
	array(
		'return' => 'xsd:string'
	),
	$ns,
	$ns."#autorizaSARA",
	'rpc',
	'encoded',
	'Aurtoriza o No solicitud'
);

$server->register('radicarSOFIA',
	array(
		'file' => 'xsd:base64Binary',
		'fileName' => 'xsd:string',
		'usuarioRadicador' => 'xsd:string',
		'asunto' => 'xsd:string',
		'tipoRadicado' => 'xsd:string',
		'pais' => 'xsd:string',
		'departamento' => 'xsd:string',
		'municipio' => 'xsd:string',
		'nombreEmpresa' => 'xsd:string',
		'representanteLegal' => 'xsd:string',
		'nit' => 'xsd:string',
		'direccion' => 'xsd:string',
		'telefono' => 'xsd:string',
		'email' => 'xsd:string',
		'key'	=> 'xsd:string'
	),
	array(
		'return' => 'xsd:string'
	),
	$ns,
	$ns."#radicarSOFIA",
	'rpc',
	'encoded',
	'Radicacion de un documento en Orfeo'
);

/**********************************************************************************
Se registran los tipos complejos y/o estructuras de datos
***********************************************************************************/
$server->wsdl->addComplexType(
        'Estructura',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'RADI_NUME_RADI' => array('name' => 'RADI_NUME_RADI', 'type' => 'xsd:string'),
        'RADI_FECH_RADI'=>array('name' => 'RADI_FECH_RADI', 'type' => 'xsd:string'),
        'TDOC_CODI'=>array('name' => 'TDOC_CODI', 'type' => 'xsd:string'),
        'RADI_PATH'=>array('name' => 'RADI_PATH', 'type' => 'xsd:string'),
        'RADI_USUA_ACTU'=>array('name' => 'RADI_USUA_ACTU', 'type' => 'xsd:string'),
        'RADI_DEPE_ACTU'=>array('name' => 'RADI_DEPE_ACTU', 'type' => 'xsd:string'),
        'RADI_USU_ANTE'=>array('name' => 'RADI_USU_ANTE', 'type' => 'xsd:string'),
        'RADI_DEPE_RADI'=>array('name' => 'RADI_DEPE_RADI', 'type' => 'xsd:string'),
        'RA_ASUN'=>array('name' => 'RA_ASUN', 'type' => 'xsd:string'),
        'RADI_USUA_RADI'=>array('name' => 'RADI_USUA_RADI', 'type' => 'xsd:string'),
        'CODI_NIVEL'=>array('name' => 'CODI_NIVEL', 'type' => 'xsd:string'),
        'DEPE_CODI'=>array('name' => 'DEPE_CODI', 'type' => 'xsd:string'),
        'SGD_DIR_CODIGO'=>array('name' => 'SGD_DIR_CODIGO', 'type' => 'xsd:string'),
        'SGD_DIR_TIPO'=>array('name' => 'SGD_DIR_TIPO', 'type' => 'xsd:string'),
        'SGD_OEM_CODIGO'=>array('name' => 'SGD_OEM_CODIGO', 'type' => 'xsd:string'),
        'SGD_CIU_CODIGO'=>array('name' => 'SGD_CIU_CODIGO', 'type' => 'xsd:string'),
        'MUNI_CODI'=>array('name' => 'MUNI_CODI', 'type' => 'xsd:string'),
        'DPTO_CODI'=>array('name' => 'DPTO_CODI', 'type' => 'xsd:string'),
        'ID_PAIS'=>array('name' => 'ID_PAIS', 'type' => 'xsd:string'),
        'SGD_DIR_DIRECCION'=>array('name' => 'SGD_DIR_DIRECCION', 'type' => 'xsd:string'),
        'SGD_DIR_TELEFONO'=>array('name' => 'SGD_DIR_TELEFONO', 'type' => 'xsd:string'),
        'SGD_DIR_MAIL'=>array('name' => 'SGD_DIR_MAIL', 'type' => 'xsd:string'),
        'SGD_DIR_NOMBRE'=>array('name' => 'SGD_DIR_NOMBRE', 'type' => 'xsd:string'),
        'SGD_DIR_NOMREMDES'=>array('name' => 'SGD_DIR_NOMREMDES', 'type' => 'xsd:string'),
        'SGD_DIR_DOC'=>array('name' => 'SGD_DIR_DOC', 'type' => 'xsd:string'))
);
//Adicionando un tipo complejo MATRIZ
$server->wsdl->addComplexType(
    'Matriz',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
	array(),
    array(
    array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:Vector[]')
    ),
    'tns:Vector'
);
//Adicionando un tipo complejo VECTOR
$server->wsdl->addComplexType(
    'Vector',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
	array(),
    array(
    array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'xsd:string[]')
    ),
    'xsd:string'
);
/******************************************************************************
 Servicios  que se ofrecen
******************************************************************************/
/**
 *
 * Esta funcion pretende radicar desde SOFIA
 *
 * @author Carlos Enrique Ricaurte Pardo
 *
 * @return pdf radicado en formato base64
 *
 */
function radicarSOFIA($file,$filename,$usuarioRadicador,$asunto,$tipoRadicado,$pais,$departamento,$municipio,$nombreEmpresa,$representanteLegal,$nit,$direccion,$telefono,$email, $key)
{
	//crea cadena ky
	$semilla = "SOFIA";

	$dia = date("d");
	$mes = date("m");
	$ano = date("Y");
	$fecha=$dia . $mes . $ano;

	$cadena = $fecha . $usuarioRadicador . $semilla;

	$keyWS = md5($cadena);
	
	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
		//Conversiones de datos para compatibilidad con aplicaciones internas
		$destinatario = array(
		//'idcont'=>$dest_idcont,
		'idcont'=>1,
		//'idpais'=>$dest_idpais,
		'idpais'=>$pais,
		//'codep'=>$dest_codep,
		'codep'=>$departamento,
		//'muni'=>$dest_muni
		'muni'=>$municipio
		);
		$usua_destino;
		try {
			$usua_destino = getInfoUsuarioXlogin($usuarioRadicador);
			$usua_origen = getInfoUsuarioXlogin($usuarioRadicador);
			$coddepe = $radi_usua_actu;
			//$radi_usua_actu = trim($radi_usua_actu['usua_codi']);
			$coddepe = trim($coddepe['usua_depe']);
			//return $coddepe;
		}catch (Exception $e){
			return $e->getMessage();
		}
		// Fin
		global $ruta_raiz;
		include(RUTA_RAIZ."include/tx/Tx.php") ;
		include(RUTA_RAIZ."include/tx/Radicacion.php") ;
		include(RUTA_RAIZ."class_control/Municipio.php") ;
		$db = new ConnectionHandler($ruta_raiz) ;
		$hist = new Historico($db) ;
		$tmp_mun = new Municipio($db) ;
		$rad = new Radicacion($db) ;
		$tmp_mun->municipio_codigo($destinatario["codep"],$destinatario["muni"]) ;
		$rad->radiTipoDeri = 2 ;
		$rad->tipRad=1;
		$rad->radiCuentai = "'".trim($cuentai)."'";
		$rad->eespCodi =  $esp["documento"] ;
		$rad->mrecCodi =  $med;
		$rad->radiFechOfic =  date("Y-m-d");
		if(!$radicadopadre)  $radicadopadre = null;
		$rad->radiNumeDeri = trim($radicadopadre) ;
		$rad->radiPais =  $tmp_mun->get_pais_codi() ;
		$rad->descAnex = $ane ;
		$rad->raAsun = $asunto ;
		$rad->radiDepeActu = $usua_destino['usua_depe'] ;
		$rad->radiDepeRadi = $usua_destino['usua_depe'] ;
		//$rad->radiUsuaActu = trim($radi_usua_actu['usua_codi']);
		$rad->radiUsuaActu = $usua_destino['usua_codi'] ;;
		$rad->trteCodi =  $tip_rem ;
		//$rad->tdocCodi=$tdoc ;
		$rad->tdocCodi=1965 ;
		$rad->tdidCodi=$tip_doc;
		$rad->carpCodi = $carp_codi ;
		$rad->carPer = $carp_per ;
		$rad->trteCodi=$tip_rem ;
		$rad->radiPath = 'null';
		if (strlen(trim($aplintegra)) == 0)
				$aplintegra = "0" ;
		$rad->sgd_apli_codi = $aplintegra ;
		$codTx = 2 ;
		$flag = 1 ;
		$rad->usuaCodi=$rad->radiUsuaActu ;
		$rad->dependencia=$usua_destino['usua_depe'];
		//$rad->dependencia=321;
		
		$noRad = $rad->newRadicado(1,10) ;
		
		$nurad = trim($noRad) ;
		if ($noRad=="-1")
		{
			return "Error no genero un Numero de Secuencia o Inserto el radicado";
		}

		$radicadosSel[0] = $noRad;
		$hist->insertarHistorico($radicadosSel,$usua_destino['usua_depe'] , $usua_destino['usua_codi'], $usua_destino['usua_depe'], $usua_destino['usua_codi'], "Radicación desde sistema SOFIA", $codTx);
		$sgd_dir_us2=2;
		$conexion = $db;

		//INSERT DIRECCIONES_
		$nextval=$conexion->nextId("sec_dir_direcciones");

		$isql="SELECT MAX(SGD_DIR_CODIGO)+1 AS CODIGO FROM SGD_DIR_DRECCIONES";
		$rs = $conexion->query($isql);
		if (!$rs->EOF){
			 $nextval = $rs->fields['CODIGO'];
		   } else {
		   	$salida['error']="El ususario no existe o se encuentra deshabilitado";
		   }

		//return $nextval;
		$isql = "insert into SGD_DIR_DRECCIONES(SGD_TRD_CODIGO, SGD_DIR_NOMREMDES, SGD_DIR_DOC, DPTO_CODI, MUNI_CODI,id_pais, id_cont, 
						SGD_DOC_FUN, RADI_NUME_RADI, SGD_SEC_CODIGO, SGD_DIR_DIRECCION, SGD_DIR_MAIL, SGD_DIR_TIPO, SGD_DIR_CODIGO, SGD_DIR_NOMBRE, SGD_DIR_TELEFONO)
			  			values('1', '" . $nombreEmpresa . "', '" . $nit . "' , " . $departamento . " , " . $municipio . " , "  . $pais . " , 1 ,
			  			'" . $usua_origen['usua_doc'] . "', $nurad, 0,'" . $direccion . "', '" . $email . "', 1, $nextval, '".$representanteLegal."', '".$telefono."')";
		//return $isql;


		$rsg=$conexion->query($isql);
			if (!$rsg){
				return "No se ha podido actualizar la informacion de DIRECCIONES";
			}
			else {
				//return $nurad;
			}

		//SUBE ADJUNTO SI TIENE
		$pathSALIDA=RUTA_RAIZ."bodega/".substr($noRad,0,4)."/".$rad->radiDepeRadi."/".$noRad.".pdf";
		
		$pdf_b64 = base64_decode($file);

		if(file_put_contents($pathSALIDA, $pdf_b64)){
		    //just to force download by the browser
		    header("Content-type: application/pdf");

		    //print base64 decoded
		    //echo $pdf_b64;
		}

		/*
		// you record the file in existing folder
		if(file_put_contents($pathSALIDA, $pdf_b64)){
		    //just to force download by the browser
		    header("Content-type: application/pdf");

		    $sql="UPDATE RADICADO SET RADI_PATH='$radi_path_base' WHERE RADI_NUME_RADI=$noRad";
		    $rsg=$conexion->query($sql);
		    //return $pathSALIDA;
		    //return "entra base 64";
		}
		else{
			//return $pathSALIDA;
			return "Error de archivo base 64";
		}
		*/

		return $noRad;
		
	}
	else
	{
		return "Error, Llave de consulta no valida";
	}
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

/*
RADICAR TH

*/


function radicarTHplantilla($file,$filename,$usuarioOrigen,$usuarioDestino,$asunto, $fechaInicio, $fechaFin, $fechaRegreso, $dias, $observaciones, $key){
	
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
		$usua_destino;
		try {
			$usua_destino = getInfoUsuarioXlogin($usuarioDestino);
			$usua_origen = getInfoUsuarioXlogin($usuarioOrigen);
			$coddepe = $radi_usua_actu;
			$coddepe = trim($coddepe['usua_depe']);
		}catch (Exception $e){
			return $e->getMessage();
		}
		$usua_destino['usua_login']=("CAPA2");
        $usua_destino['usua_doc'] =("456782331456");
        $usua_destino['usua_depe'] =("900");
        $usua_destino['usua_nivel'] =("5");
        $usua_destino['usua_codi'] =("658");
        $usua_destino['usua_nomb'] =("usuario capacitacion 2");
        $usua_destino['usua_email'] =("cricaurte@cra.gov.co");
        $usua_destino['usua_dependencia'] =("ADMIN ORFEO");
		// Fin
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
		$rad->mrecCodi =  $med;
		$rad->radiFechOfic =  date("Y-m-d");
		if(!$radicadopadre)  $radicadopadre = null;
		$rad->radiNumeDeri = trim($radicadopadre) ;
		$rad->radiPais =  $tmp_mun->get_pais_codi() ;
		$rad->descAnex = $ane ;
		$rad->raAsun = $asunto ;
		$rad->radiDepeActu = $usua_destino['usua_depe'] ;
		//$rad->radiDepeRadi = 321 ;
		$rad->radiDepeRadi = 900 ;
		//$rad->radiUsuaActu = $usua_destino['usua_codi'];
		$rad->radiUsuaActu = 658;
		$rad->trteCodi =  $tip_rem ;
		//$rad->tdocCodi=$tdoc ;
		$rad->tdocCodi=1699 ;
		$rad->tdidCodi=$tip_doc;
		$rad->carpCodi = $carp_codi ;
		$rad->carPer = $carp_per ;
		$rad->trteCodi=$tip_rem ;
		$rad->radiPath = 'null';
		if (strlen(trim($aplintegra)) == 0)
				$aplintegra = "0" ;
		$rad->sgd_apli_codi = $aplintegra ;
		$codTx = 2 ;
		$flag = 1 ;
		$rad->usuaCodi=$rad->radiUsuaActu ;
		//$rad->dependencia=$usua_destino['usua_depe'];
		//$rad->dependencia=321;
		$rad->dependencia=900;
		$noRad = $rad->newRadicado(2,$rad->dependencia) ;
		
		$nurad = trim($noRad) ;
		if ($noRad=="-1")
		{
			return "Error no genero un Numero de Secuencia o Inserto el radicado";
		}

		$radicadosSel[0] = $noRad;
		$hist->insertarHistorico($radicadosSel,$rad->dependencia , $rad->radiUsuaActu, $usua_destino['usua_depe'], $usua_destino['usua_codi'], $asunto, $codTx);
		$sgd_dir_us2=2;
		$conexion = $db;

		//INSERT DIRECCIONES_
		$nextval=$conexion->nextId("sec_dir_direcciones");

		$isql="SELECT MAX(SGD_DIR_CODIGO)+1 AS CODIGO FROM SGD_DIR_DRECCIONES";
		$rs = $conexion->query($isql);
		if (!$rs->EOF){
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
			if (!$rsg){
				return "No se ha podido actualizar la informacion de DIRECCIONES";
			}
			else {
				//return $nurad;
			}

		//SUBE ADJUNTO SI TIENE
			//pendiente para anexo
		if(!empty($file)){
			$auxnumero = "00001";
			$codigo = $noRad.$auxnumero;
			$archivoconversion=trim("1").trim(trim($noRad))."_".trim($auxnumero).".pdf";
			$dependencia = "11";
			$sqlFechaHoy= $db->conn->OffsetDate(0,$db->conn->sysTimeStamp);

			$isql = "insert into anexos (sgd_rem_destino, anex_radi_nume, anex_codigo, anex_tipo, anex_tamano,
										anex_solo_lect, anex_creador, anex_desc, anex_numero, anex_nomb_archivo,
										anex_borrado, anex_salida, anex_depe_creador, sgd_tpr_codigo, anex_fech_anex)
				values ('1', $noRad, $codigo, '7', '1', 
				'N','RWEB', 'Anexo solicitud', '1','$archivoconversion', 
				'N', '0', $dependencia, '0', $sqlFechaHoy)";
				//echo $isql;
			$insertAnexo = $db->conn->query($isql);
			
			$radi_path_base="/".substr($noRad,0,4)."/".$rad->dependencia."/docs/".$noRad.".pdf";
			$pathSALIDA=RUTA_RAIZ."bodega/".substr($noRad,0,4)."/".$rad->dependencia."/docs/".$archivoconversion;
			// decode base64
			$pdf_b64 = base64_decode($file);

			// you record the file in existing folder
			if(file_put_contents($pathSALIDA, $pdf_b64)){
			    //just to force download by the browser
			    header("Content-type: application/pdf");

			    
			}
			else{
				//$sql="UPDATE RADICADO SET RADI_PATH='$pathSALIDA' WHERE RADI_NUME_RADI=$noRad";
			    //$rsg=$conexion->query($sql);
			}
		}
		///GENERAR OFICIO PDF
		$pathDB = "/" . substr($noRad, 0, 4) . "/".$rad->dependencia."/";
		crearPdf($noRad,$usua_origen['usua_nomb'],$usua_origen['usua_doc'],$asunto, $fechaInicio, $fechaFin, $fechaRegreso, $dias, $observaciones,"plantilla_permisos.odt");
		$rad->updatePath("'$pathDB". $noRad . ".pdf'", $noRad, 1);
		return $noRad;
	}
	else
	{
		return "Error, Llave de consulta no valida";
		//return $fecha;
	}
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
        $inputFilename = $ruta . '/bodega/plantillas/' . $plantilla;
        
        $nuevo_fichero = $ruta . '/bodega/' . $anio . '/' . $carpeta . '/' . $radicado .  '.odt';

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
       	    
        if ($zip->addFromString('content.xml', $xml)) {
            //echo 'File written!'; 
        }
        else { 
        	//echo 'File not written.  Go back and add write permissions to this folder!l'; 
    	}

        $zip->close();

        /********************************FIN DOCX_EDITOR*************************************************/

        /********************************CONVIERTE ODT A PDF ***************************************/

        $originFilePath = "../bodega/" . $anio . "/" . $carpeta . "/" . $radicado;
        $outputDirPath  = "../bodega/" . $anio . "/" . $carpeta . "/";

        Unoconv::convertToPdf($originFilePath, $outputDirPath);
        
        return 3;
    }

 /**FIN FUNCION GENERA PDF*/


/**
 *
 * funcion encargada regenerar un archivo enviado en base64
 *
 *
 *
 * @param string $ruta ruta donde se almacenara el archivo
 *
 * @param base64 $archivo archivo codificado en base64
 *
 * @param string $nombre nombre del archivo
 *
 * @return boolean retorna si se pudo decodificar el archivo
 *
 */
function subirArchivo($ruta,$archivo,$nombre){
		//try{
		//direccion donde se quiere guardar los archivos
		$fp = @fopen("{$ruta}{$nombre}", "w");
		$bytes=base64_decode($archivo);
		$salida=true;
		if( is_array($bytes) ){
			foreach($bytes as $k => $v){
				$salida=($salida && fwrite($fp,$bytes));
			}
		}else{
			$salida=fwrite($fp,$bytes);
		}
		fclose($fp);
	/*}catch (Exception $e){
		return "error";
	}*/
	return $salida;
}
/**
 *
 * funcion que crea un Anexo, y ademas decodifica el anexo enviasdo en base 64
 *
 *
 *
 * @param string $radiNume numero del radicado al cual se adiciona el anexo
 *
 * @param base64 $file archivo codificado en base64
 *
 * @param string $filename nombre original del anexo, con extension
 *
 * @param string $correo correo electronico del usuario que adiciona el anexo
 *
 * @param string $descripcion descripcion del anexo
 *
 * @return string mensaje de error en caso de fallo o el numero del anexo en caso de exito
 *
 */

/**
* Funcion que verifica si el destinatario existe, si no envia el codigo de un usuario anonimo
*
**/

function getInfoJefe($usuaLogin){
	global $ruta_raiz;
		$db = new ConnectionHandler($ruta_raiz);

        $sql="SELECT U.USUA_LOGIN, U.USUA_DOC, U.DEPE_CODI, U.CODI_NIVEL, U.USUA_CODI, U. USUA_NOMB, U.USUA_EMAIL 
			FROM USUARIO U
			WHERE USUA_CODI=1 AND USUA_ESTA = 1 AND 
				  U.DEPE_CODI =  (SELECT rownum = 1 AND A.DEPE_CODI FROM USUARIO A WHERE A.USUA_ESTA = 1 AND A.USUA_LOGIN LIKE '%$usuaLogin%' )";
        $rs=$db->query($sql);
        if($rs && !$rs->EOF){
                		$salida['usua_login']=($rs->fields["USUA_LOGIN"]);
                        $salida['usua_doc'] =($rs->fields["USUA_DOC"]);
                        $salida['usua_depe'] =($rs->fields["DEPE_CODI"]);
                        $salida['usua_nivel'] =($rs->fields["CODI_NIVEL"]);
                        $salida['usua_codi'] =($rs->fields["USUA_CODI"]);
                        $salida['usua_nomb'] =($rs->fields["USUA_NOMB"]);
                        $salida['usua_email'] =($rs->fields["USUA_EMAIL"]);
        }else{
        	throw new Exception("El usuario $usuaLoginMail no existe $sql");
        }
        return $salida;
}

function getInfoUsuarioXlogin($usuaLogin){
	global $ruta_raiz;
		$db = new ConnectionHandler($ruta_raiz);

        $sql="SELECT  U.USUA_LOGIN, U.USUA_DOC, U.DEPE_CODI, U.CODI_NIVEL, U.USUA_CODI, U.USUA_NOMB, U.USUA_EMAIL , D.DEPE_NOMB
        		FROM USUARIO U, DEPENDENCIA D
                WHERE rownum = 1 AND D.DEPE_CODI=U.DEPE_CODI AND USUA_ESTA = 1 AND USUA_LOGIN LIKE '%$usuaLogin%' ORDER BY U.DEPE_CODI";

        $rs=$db->query($sql);
                if($rs && !$rs->EOF){
                		$salida['usua_login']=($rs->fields["USUA_LOGIN"]);
                        $salida['usua_doc'] =($rs->fields["USUA_DOC"]);
                        $salida['usua_depe'] =($rs->fields["DEPE_CODI"]);
                        $salida['usua_nivel'] =($rs->fields["CODI_NIVEL"]);
                        $salida['usua_codi'] =($rs->fields["USUA_CODI"]);
                        $salida['usua_nomb'] =($rs->fields["USUA_NOMB"]);
                        $salida['usua_email'] =($rs->fields["USUA_EMAIL"]);
                        $salida['usua_dependencia'] =($rs->fields["DEPE_NOMB"]);
        }else{
        	throw new Exception("El usuario $usuaLoginMail no existe $sql");
        }
        return $salida;
}

function getInfoUsuario($usuaLoginMail,$key){
	global $ruta_raiz,$keyWS;
	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
		$db = new ConnectionHandler($ruta_raiz);
		$upperMail=strtoupper($usuaLoginMail);
		$lowerMail=strtolower($usuaLoginMail);
        $sql="SELECT USUA_LOGIN,USUA_DOC,DEPE_CODI,CODI_NIVEL,USUA_CODI,USUA_NOMB,USUA_EMAIL FROM USUARIO
                        WHERE  USUA_EMAIL='{$usuaLoginMail}' OR USUA_EMAIL='{$upperMail}' OR USUA_EMAIL='{$lowerMail}' ";
        $rs=$db->query($sql);
                if($rs && !$rs->EOF){
                		$salida['usua_login']=($rs->fields["USUA_LOGIN"]);
                        $salida['usua_doc'] =($rs->fields["USUA_DOC"]);
                        $salida['usua_depe'] =($rs->fields["DEPE_CODI"]);
                        $salida['usua_nivel'] =($rs->fields["CODI_NIVEL"]);
                        $salida['usua_codi'] =($rs->fields["USUA_CODI"]);
                        $salida['usua_nomb'] =($rs->fields["USUA_NOMB"]);
                        $salida['usua_email'] =($rs->fields["USUA_EMAIL"]);
        }else{
        	throw new Exception("El usuario $usuaLoginMail no existe $sql");
        }
        return $salida;
    }
    else
    {
			throw new Exception("Error, llave de consulta no valida");
    }
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>
