<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
/**********************************************************************************
Diseno de un Web Service que permita la interconexion de aplicaciones con Orfeo
**********************************************************************************/
/**
 *
 * @author German Mahecha
 *
 * @author William Duarte (modificacion del archivo original y adicion de funcionalidad)
 *
 * @author Donaldo Jinete Forero
 *
 * @author Carlos Ricaurte
 */
//Llamado a la clase nusoap
$ruta_raiz = "../";
define('RUTA_RAIZ','../');
require_once "nusoap/lib/nusoap.php";

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

$server->register('radicarTH',
	array(
		//'file' => 'xsd:base64binary',										//archivo en base 64
		'file' => 'xsd:base64Binary',										//archivo carlinho a string
		'fileName' => 'xsd:string',
		'usuarioOrigen' => 'xsd:string',
		'usuarioDestino' => 'xsd:string',
		'asunto' => 'xsd:string',
		'key'	=> 'xsd:string'
	),
	array(
		'return' => 'xsd:string'
	),
	$ns,
	$ns."#radicarTH",
	'rpc',
	'encoded',
	'Radicacion de un documento en Orfeo'
);

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
		'return' => 'xsd:base64Binary'
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
		//$pathPDF=RUTA_RAIZ."bodega/2020/321/docs/120203210039462_00002.pdf";
		//$b64Doc = chunk_split(base64_encode($pathPDF));
		//$b64Doc = chunk_split(base64_encode(file_get_contents($pathPDF)));
		//$archivo = fopen(RUTA_RAIZ.'bodega/2020/321/docs/archivo.txt','a');
		//fputs($archivo,$b64Doc);
		//fclose($archivo);

		
		//$radi_path_base="/".substr($noRad,0,4)."0".$rad->radiDepeRadi.$noRad.".pdf";
		$pathSALIDA=RUTA_RAIZ."bodega/".substr($noRad,0,4)."/".$rad->radiDepeRadi."/".$noRad.".odt";
		// decode base64
		$pdf_b64 = base64_decode($file);

		// you record the file in existing folder
		if(file_put_contents($pathSALIDA, $pdf_b64)){
		    //just to force download by the browser
		    //header("Content-type: application/odt");

		    $sql="UPDATE RADICADO SET RADI_PATH='$radi_path_base' WHERE RADI_NUME_RADI=$noRad";
		    $rsg=$conexion->query($sql);
		    //return $pathSALIDA;
		}
		else{
			//return $pathSALIDA;
		}
		

		return $noRad;
	}
	else
	{
		return "Error, Llave de consulta no valida";
	}
}

/**
 *
 * Esta funcion pretende almacenar todos los usuarios de orfeo, con la informacion
 *
 * de correo, cedula, dependencia y codigo del usuario
 *
 * @author German A. Mahecha
 *
 * @return Matriz con todos los usuarios de Orfeo
 *
 */
function darUsuario($key){
	global $ruta_raiz, $keyWS;
	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
		$db = new ConnectionHandler($ruta_raiz);
		$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
		$sql = "select DEPE_CODI, USUA_CODI, USUA_DOC, USUA_EMAIL  from usuario";
		$rs = $db->getResult($sql);
		$i =0;
		while (!$rs->EOF){
				 $usuario[$i]['email'] = $rs->fields['USUA_EMAIL'];
				 $usuario[$i]['codusuario']  = $rs->fields['USUA_CODI'];
				 $usuario[$i]['dependencia'] = $rs->fields['DEPE_CODI'];
				 $usuario[$i]['documento'] =  $rs->fields['USUA_DOC'];
				 $i=$i+1;
				 $rs->MoveNext();
		}
		return $usuario;
	}
	else
	{
		return 'Error, Llave de consulta no valida';
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
			/*
		'cc_documento'=>$dest_cc_documento,
		'documento'=>$dest_cc_documento,
		'tipo_emp'=>0,
		'nombre'=>$dest_nombre,
		'prim_apel'=>$dest_prim_apel,
		'seg_apel'=>$dest_seg_apel,
		'telefono'=>$dest_telefono,
		'direccion'=>$dest_direccion,
		'mail'=>$dest_mail,
		'otro'=>$dest_otro,
		*/
		//'idcont'=>$dest_idcont,
		'idcont'=>1,
		//'idpais'=>$dest_idpais,
		'idpais'=>170,
		//'codep'=>$dest_codep,
		'codep'=>11,
		//'muni'=>$dest_muni
		'muni'=>1
		);
		$usua_destino;
		try {
			$usua_actual = getInfoJefe($usuarioOrigen);
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
		//return "noRad: . $noRad,  coddepe: $coddepe , radi_usua_actu: $radi_usua_actu, coddepe: $coddepe, radi_usua_actu: $radi_usua_actu,  Radicacion desde servicio web , codTx: $codTx";
		//return "hist:" . $hist->insertarHistorico($radicadosSel,  $coddepe , $radi_usua_actu, $coddepe, $radi_usua_actu, " Radicacion desde servicio web ", $codTx);
		//$hist->insertarHistorico($radicadosSel,  $coddepe , $rad->radiUsuaActu, $coddepe, 1, " Radicacion desde servicio web ", $codTx);
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


function radicarTH($file,$filename,$usuarioOrigen,$usuarioDestino,$asunto, $key)
{
	
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
		//'idcont'=>$dest_idcont,
		'idcont'=>1,
		//'idpais'=>$dest_idpais,
		'idpais'=>170,
		//'codep'=>$dest_codep,
		'codep'=>321,
		//'muni'=>$dest_muni
		'muni'=>1
		);
		$usua_destino;
		try {
			$usua_destino = getInfoUsuarioXlogin($usuarioDestino);
			$usua_origen = getInfoUsuarioXlogin($usuarioOrigen);
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
		$rad->radiDepeRadi = 321 ;
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
		//$rad->dependencia=$usua_destino['usua_depe'];
		$rad->dependencia=321;
		
		$noRad = $rad->newRadicado(2,321) ;
		
		$nurad = trim($noRad) ;
		if ($noRad=="-1")
		{
			return "Error no genero un Numero de Secuencia o Inserto el radicado";
		}

		$radicadosSel[0] = $noRad;
		$hist->insertarHistorico($radicadosSel,321 , 782, $usua_destino['usua_depe'], $usua_destino['usua_codi'], $asunto, $codTx);
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
		//$pathPDF=RUTA_RAIZ."bodega/2020/321/docs/120203210039462_00002.pdf";
		//$b64Doc = chunk_split(base64_encode($pathPDF));
		//$b64Doc = chunk_split(base64_encode(file_get_contents($pathPDF)));
		//$archivo = fopen(RUTA_RAIZ.'bodega/2020/321/docs/archivo.txt','a');
		//fputs($archivo,$b64Doc);
		//fclose($archivo);
			
		$radi_path_base="/".substr($noRad,0,4)."/321/".$noRad.".pdf";
		$pathSALIDA=RUTA_RAIZ."bodega/".substr($noRad,0,4)."/321/".$noRad.".pdf";
		// decode base64
		$pdf_b64 = base64_decode($file);

		// you record the file in existing folder
		if(file_put_contents($pathSALIDA, $pdf_b64)){
		    //just to force download by the browser
		    header("Content-type: application/pdf");

		    $sql="UPDATE RADICADO SET RADI_PATH='$radi_path_base' WHERE RADI_NUME_RADI=$noRad";
		    $rsg=$conexion->query($sql);
		}

		return $noRad;
	}
	else
	{
		return "Error, Llave de consulta no valida";
	}
}

/**
 *
 * Esta funcion permite obtener el path donde se debe almacenar el archivo
 *
 * @param $filename, el nombre del archivo
 *
 * @author German A. Mahecha
 *
 * @return Retorna el path
 *
 */
function getPath($filename){
	$var = explode(".",$filename);
	$path = RUTA_RAIZ."bodega/";
	$path .= substr($var[0],0,4);
	$path .= "/".substr($var[0],4,3);
	//Verificamos si se trata de una imagen o un anexo, si lleva guion bajo el nombre del
	//archivo indica que es un anexo y por lo tanto se almacena dentro de el directorio docs
	//si no lo almacenamos en la carpeta con codigo de la dependencia
	if (strstr($filename,'_'))
	{
		$path .= "/docs/".$filename;
	}
	else
	{
		$path .= "/".$filename;
	}
	return  $path;
}
/**
 *
 * funcion que rescata los valores de un usuario de orfeo
 *
 * a partir del correo electonico
 *
 *
 *
 * @param string $correo mail del usuario en orfeo
 *
 * @return array resultado de la consulta;
 *
 */
function getUsuarioCorreo($correo,$key){
	global $ruta_raiz,$keyWS;
	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
		$consulta="SELECT USUA_LOGIN,DEPE_CODI,USUA_EMAIL,CODI_NIVEL,USUA_CODI,USUA_DOC
		           FROM USUARIO WHERE USUA_EMAIL='$correo' AND USUA_ESTA=1";
		$salida=array();
		if(verificarCorreo($correo)){
		$consulta="SELECT USUA_LOGIN,DEPE_CODI,USUA_EMAIL,CODI_NIVEL,USUA_CODI,USUA_DOC
		           FROM USUARIO WHERE USUA_EMAIL='".trim($correo)."' AND USUA_ESTA=1";
		 $db = new ConnectionHandler($ruta_raiz);
		 $rs = $db->query($consulta);
		 if (!$rs->EOF){
			 $salida['email'] = $rs->fields['USUA_EMAIL'];
			 $salida['codusuario']  = $rs->fields['USUA_CODI'];
			 $salida['dependencia'] = $rs->fields['DEPE_CODI'];
			 $salida['documento'] =  $rs->fields['USUA_DOC'];
			 $salida['nivel'] = $rs->fields['CODI_NIVEL'];
			 $salida['login'] = $rs->fields['USUA_LOGIN'];
		   } else {
		   	$salida['error']="El ususario no existe o se encuentra deshabilitado";
		   }
		}else{
			$salida["error"]="el mail no corresponde a un email valido";
		}
	}
	else
	{
		$salida["error"]="Llave de consulta no valida";
	}
	return $salida;
}
/**
 *
 * funcion que verifica que un correo electronico cumpla con
 *
 * un patron estandar
 *
 *
 *
 * @param strig $correo correo a verificar
 *
 * @return boolean
 *
 */
function verificarCorreo($correo){
	 $expresion=preg_match("(^\w+([\.-] ?\w+)*@\w+([\.-]?\w+)*(\.\w+)+)",$correo);
	 return $expresion;
}
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
function getAddresseeId($identification='',$tipo_emp_us1=0)
{
	global $ruta_raiz;
	$db = new ConnectionHandler($ruta_raiz);
	$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
	//Definimos codigo de sgd ciu anonimo
	$sgd_cod_anonimo = 99999999;
	$sgd_cod = 0;
	//Validamos consulta a realizar
	if($tipo_emp_us1==0){
		$sgd_ciu_codigo=$documento_us1;
		$consulta="SELECT SGD_CIU_CODIGO FROM SGD_CIU_CIUDADANO WHERE SGD_CIU_CEDULA ='".$identification."'";
		$rs=$db->query($consulta);
		if($rs && !$rs->EOF)
		$sgd_cod = $rs->fields['SGD_CIU_CODIGO'];
	}
	//Validamos consulta empresas
	if($tipo_emp_us1==1)
		{
			$sgd_ciu_codigo=$documento_us1;
		$consulta="SELECT SGD_CIU_CODIGO FROM SGD_CIU_CIUDADANO WHERE SGD_CIU_CEDULA ='".$identification."'";
		$rs=$db->query($consulta);
		if($rs && !$rs->EOF)
		$sgd_cod = $rs->fields['SGD_CIU_CODIGO'];
	}
	//Validamos si no se asigno ningun codigo colocamos por defecto el de un usuario anonimo
	if ($sgd_cod==0) $sgd_cod = $sgd_cod_anonimo;
	//Retornamos
	return $sgd_cod;
}

function anexarExpediente($numRadicado,$numExpediente,$usuaLoginMail,$observa,$key){
	global $ruta_raiz,$keyWS;
	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
		//Validamos que el expediente exista
		if (!existeExpediente($numExpediente)) return 'El expediente '.$numExpediente.' no existe';
		$db = new ConnectionHandler($ruta_raiz);
		include_once (RUTA_RAIZ.'include/tx/Historico.php');
        $estado=estadoRadicadoExpediente($numRadicado,$numExpediente);
        $usua=getInfoUsuario($usuaLoginMail,$key);
        $tipoTx = 53;
    	$Historico = new Historico( $db );
    	$fecha=$db->conn->OffsetDate(0,$db->conn->sysTimeStamp);
    	try{
        switch ($estado){
                case 0:
                        throw new Exception("El documento con numero de radicado  {$numRadicado} ya fue anexado al expediente {$numExpediente}");
                case 1:
                        throw new Exception("El documento con numero de radicado {$numRadicado} ya fue anexado al expediente {$numExpediente} y archivado fisicamente");
                case 2:
                        $consulta="UPDATE SGD_EXP_EXPEDIENTE SET SGD_EXP_ESTADO=0,SGD_EXP_FECH={$fecha},USUA_CODI=".$usua['usua_codi'].",USUA_DOC='".$usua['usua_doc']."'
                                ,DEPE_CODI=".$usua['usua_depe']." WHERE RADI_NUME_RADI={$numRadicado}
                                                AND SGD_EXP_NUMERO='{$numExpediente}'";
                break;
                default:
                        $consulta="INSERT INTO SGD_EXP_EXPEDIENTE (SGD_EXP_NUMERO,RADI_NUME_RADI,SGD_EXP_FECH,SGD_EXP_ESTADO,USUA_CODI,USUA_DOC,DEPE_CODI)
                                          VALUES ('{$numExpediente}',{$numRadicado},{$fecha},0,".$usua['usua_codi'].",'".$usua['usua_doc']."',".$usua['usua_depe'].")";
                        break;
        	}
    	}
    	catch (Exception $e){
    		return $e->getMessage();
    	}
        if($db->query($consulta)){
        		$radicados = array($numRadicado);
                $radicados = $Historico->insertarHistoricoExp( $numExpediente, $radicados, $usua['usua_depe'], $usua['usua_codi'], $observa, $tipoTx, 0);
                return $radicados[0];
        }else{
                throw new Exception("Error y no se realizo la operacion");
        }
    }
    else
    {
    	  throw new Exception("Error, llave de consulta no valida");
    }
}
function cambiarImagenRad($numRadicado,$ext,$file,$key){
	global $ruta_raiz,$keyWS;
	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
		$db = new ConnectionHandler($ruta_raiz);
		$sql="SELECT RAPI_DEPE_RADI,RADI_FECH_OFIC FROM RADICADO WHERE RADI_NUME_RADI='{$numRadicado}'";
		$rs=$db->query($sql);
		if(!$rs->EOF){
			$year=substr($numRadicado,0,4);
			$depe=substr($numRadicado,4,3);
			$path="/{$year}/{$depe}/{$numRadicado}.{$ext}";
			$update="UPDATE RADICADO SET RADI_PATH='{$path}' where RADI_NUME_RADI='{$numRadicado}'";
			if(UploadFile($file,$numRadicado.'.'.$ext,$key)=='exito'){
				$db->query($update);
				return "OK";
			}else{
				throw new Exception("ERROR no se puede copiar el archivo");
			}
		}else{
				throw new Exception("ERROR El radicado no existe");
		}
	}
	else
	{
		throw new Exception ("Llave de consulta no valida");
	}
}
function estadoRadicadoExpediente($numRadicado,$numExpediente){
	global $ruta_raiz;
	$db = new ConnectionHandler($ruta_raiz);
	$salida=-1;
	$consulta="SELECT SGD_EXP_ESTADO FROM SGD_EXP_EXPEDIENTE WHERE RADI_NUME_RADI={$numRadicado} AND SGD_EXP_NUMERO='{$numExpediente}'";
	$resultado=$db->query($consulta);
	if($resultado && !$resultado->EOF){
		$salida=$resultado->fields['SGD_EXP_ESTADO'];
	}
	return $salida;
}

function getInfoJefe($usuaLogin){
	global $ruta_raiz;
		$db = new ConnectionHandler($ruta_raiz);

        $sql="SELECT U.USUA_LOGIN, U.USUA_DOC, U.DEPE_CODI, U.CODI_NIVEL, U.USUA_CODI, U. USUA_NOMB, U.USUA_EMAIL 
			FROM USUARIO U
			WHERE USUA_CODI=1 AND USUA_ESTA = 1 AND  U.DEPE_CODI =  (SELECT  A.DEPE_CODI FROM USUARIO A WHERE  A.USUA_LOGIN LIKE '%$usuaLogin%' )";
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
                WHERE D.DEPE_CODI=U.DEPE_CODI AND USUA_ESTA = 1 AND USUA_LOGIN LIKE '%$usuaLogin%' ";

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
//Funcion que valida si existe un expediente
function existeExpediente($numExpediente='')
{
	global $ruta_raiz;
	$db = new ConnectionHandler($ruta_raiz);
	if ($numExpediente=='') return false;
	$query = "SELECT COUNT(SGD_EXP_NUMERO) as qty FROM SGD_SEXP_SECEXPEDIENTES WHERE SGD_EXP_NUMERO = '$numExpediente'";
	$rs = $db->query($query);
	if($rs && $rs->fields['QTY'] > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function getInfoRadicado($numRadicado,$key)
{
	global $ruta_raiz,$keyWS;
	if ($keyWS==$key)
	{
		$db = new ConnectionHandler($ruta_raiz);
		$sql = "SELECT * FROM RADICADO WHERE RADI_NUME_RADI = '$numRadicado'";
		$rs = $db->query($sql);
		$radi	= array();
		if ($rs)
		{
			$radi['RADI_NUME_RADI'] = $rs->fields['RADI_NUME_RADI'];
			$radi['RADI_FECH_RADI'] = $rs->fields['RADI_FECH_RADI'];
			$radi['RA_ASUN']        = $rs->fields['RA_ASUN'];
			$radi['TDOC_CODI']      = $rs->fields['TDOC_CODI'];
			$radi['RADI_PATH']      = $rs->fields['RADI_PATH'];
			$radi['RADI_USUA_ACTU'] = $rs->fields['RADI_DEPE_ACTU'];
			$radi['RADI_DEPE_ACTU'] = $rs->fields['RADI_DEPE_ACTU'];
			$radi['RADI_USU_ANTE']  = $rs->fields['RADI_USU_ANTE'];
			$radi['RADI_DEPE_RADI'] = $rs->fields['RADI_DEPE_RADI'];
			$radi['RADI_USUA_RADI'] = $rs->fields['RADI_USUA_RADI'];
			$radi['CODI_NIVEL']     = $rs->fields['CODI_NIVEL'];
			$radi['DEPE_CODI']      = $rs->fields['DEPE_CODI'];
			//Obtenemos informacion del destinatario
			$sqlc = "SELECT * FROM SGD_DIR_DRECCIONES WHERE RADI_NUME_RADI = '$numRadicado'";
			$rsc = $db->query($sqlc);
			if ($rsc)
			{
				$radi['SGD_DIR_CODIGO']    = $rsc->fields['SGD_DIR_CODIGO'];
				$radi['SGD_DIR_TIPO']      = $rsc->fields['SGD_DIR_TIPO'];
				$radi['SGD_OEM_CODIGO']    = $rsc->fields['SGD_OEM_CODIGO'];
				$radi['SGD_CIU_CODIGO']    = $rsc->fields['SGD_CIU_CODIGO'];
				$radi['MUNI_CODI']         = $rsc->fields['DPTO_CODI'];
				$radi['ID_PAIS']           = $rsc->fields['ID_PAIS'];
				$radi['SGD_DIR_DIRECCION'] = $rsc->fields['SGD_DIR_DIRECCION'];
				$radi['SGD_DIR_TELEFONO']  = $rsc->fields['SGD_DIR_TELEFONO'];
				$radi['SGD_DIR_MAIL']      = $rsc->fields['SGD_DIR_MAIL'];
				$radi['SGD_DIR_NOMBRE']    = $rsc->fields['SGD_DIR_NOMBRE'];
				$radi['SGD_DIR_NOMREMDES'] = $rsc->fields['SGD_DIR_NOMREMDES'];
				$radi['SGD_DIR_DOC']       = $rsc->fields['SGD_DIR_DOC'];
			}
		}
		return $radi;
	}
	else
	{
		return array('error'=>'La clave de consulta del servicio web no coincide.');
	}
}
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>
