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
//Servicio de transferir archivo
$server->register('UploadFile',  									 //nombre del servicio
    array('bytes' => 'xsd:base64binary', 'filename' => 'xsd:string', 'key'	=> 'xsd:string'),//entradas
    array('return' => 'xsd:string'),   								 // salidas
    $ns,                         									 //Elemento namespace para el metodo
    $ns . '#UploadFile',   											 //Soapaction para el metodo
    'rpc',                 											 //Estilo
  	'encoded',
    'Upload a File'
);
$server->register('crearAnexo',  								//nombre del servicio
    array('radiNume' => 'xsd:string',									//numero de radicado
     'file' => 'xsd:base64binary',										//archivo en base 64
     'filename' => 'xsd:string',										//nombre original del archivo
     'correo' => 'xsd:string',									       //correo electronico
     'descripcion'=>'xsd:string',										//descripcion del anexo
     'key'	=> 'xsd:string'												//Clave del servicio
     ),																//fin parametros del servicio
    array('return' => 'xsd:string'),   								//retorno del servicio
    $ns                     									 	//Elemento namespace para el metod
);

//Servicio que entrega todos los usuarios de Orfeo
$server->register('darUsuario',
	array('key'	=> 'xsd:string'), //Clave del servicio
	array('return'=>'tns:Matriz'),
	$ns
);
// Servicio que realiza una radicacion en Orfeo
$server->register('radicarDocumento',
	array(
		//'file' => 'xsd:base64binary',										//archivo en base 64
		'file' => 'xsd:string',										//archivo carlinho a string
		'fileName' => 'xsd:string',
		'correo' => 'xsd:string',
		'dest_cc_documento'=>'xsd:string',
		'dest_nombre'=>'xsd:string',
		'dest_prim_apel'=>'xsd:string',
		'dest_seg_apel'=>'xsd:string',
		'dest_telefono'=>'xsd:string',
		'dest_direccion'=>'xsd:string',
		'dest_mail'=>'xsd:string',
		'dest_otro'=>'xsd:string',
		'dest_idcont'=>'xsd:string',
		'dest_idpais'=>'xsd:string',
		'dest_codep'=>'xsd:string',
		'dest_muni'=>'xsd:string',
		'asu'=>'xsd:string',
		'med'=>'xsd:string',
		'ane'=>'xsd:string',
		'coddepe'=>'xsd:string',
		'tpRadicado'=>'xsd:string',
		'cuentai'=>'xsd:string',
		'radi_usua_actu'=>'xsd:string',
		'tip_rem'=>'xsd:string',
		'tdoc'=>'xsd:string',
		'tip_doc'=>'xsd:string',
		'carp_codi'=>'xsd:string',
		'carp_per'=>'xsd:string',
		'key'	=> 'xsd:string'
	),
	array(
		'return' => 'xsd:string'
	),
	$ns,
	$ns."#radicarDocumento",
	'rpc',
	'encoded',
	'Radicacion de un documento en Orfeo'
);
$server->register('anexarExpediente',
	array(
		'numRadicado'=>'xsd:string',
		'numExpediente'=>'xsd:string',
		'usuaLogin'=>'xsd:string',
		'observa'=>'xsd:string',
		'key'	=> 'xsd:string'
	),
	array(
		'return'=>'xsd:string'
	),
	$ns,
	$ns."#anexarExpediente",
	'rpc',
	'encoded',
	'Anexar un radicado a un expediente'
);
$server->register('getInfoUsuario',
	array(
		'usuaLoginMail'=>'xsd:string',
		'key'	=> 'xsd:string'
	),
	array(
		'return'=>'tns:Vector'
	),
	$ns,
	$ns.'#getInfoUsuario',
	'rpc',
	'encoded',
	'Obtener informacion de un usuario a partir del correo electronico'
);
$server->register('getInfoRadicado',
	array(
		'numRadicado'=>'xsd:string',
		'key'	=> 'xsd:string'
	),
	array(
		'return'=>'tns:Estructura'
	),
	$ns,
	$ns.'#getInfoRadicado',
	'rpc',
	'encoded',
	'Obtener informacion de un radicado'
);
/************RESITRO DE PRUEBA****/
$server->register(	'prueba' ,
					array('entrada'=>'xsd:string', 'key'	=> 'xsd:string')
);

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
	if ($key != $keyWS)
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
	global $keyWS;
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
	if ($key != $keyWS)
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
 * UploadFile es una funcion que permite almacenar cualquier tipo de archivo en el lado del servidor
 *
 * @param $bytes
 *
 * @param $filename es el nombre del archivo con que queremos almacenar en el servidor
 *
 * @author German A. Mahecha
 *
 * @return Retorna un String indicando si la operacion fue satisfactoria o no
 *
 */
function UploadFile($bytes, $filename,$key){
	global $keyWS;
	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
	    //$var = explode(".",$filename);
		//try{
			//direccion donde se quiere guardar los archivos
			$path = getPath($filename);
			if(!$fp = fopen("$path", "w")){
				die("fallo");
			}
			// decodificamos el archivo
			$bytes=base64_decode($bytes);
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
		if ($salida){
 
		return "Hola mundo ".$path.' <br>'."exito";
		}else{
		return "error";
		}
	}
	else
	{
		//$salida["error"]="Llave de consulta no valida";
		return "error";
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
function crearAnexo($radiNume,$file,$filename,$correo,$descripcion,$key){
	global $ruta_raiz,$keyWS;
	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
		$db = new ConnectionHandler($ruta_raiz);
		$usuario=getUsuarioCorreo($correo,$key);
		$error=(isset($usuario['error']))?true:false;
		$ruta=RUTA_RAIZ."bodega/".substr($radiNume,0,4)."/".substr($radiNume,4,3)."/docs/";
		$numAnexos=numeroAnexos($radiNume,$db)+1;
		$maxAnexos=maxRadicados($radiNume,$db)+1;
		$extension=substr($filename,strrpos($filename,".")+1);
		$numAnexo=($numAnexos > $maxAnexos)?$numAnexos:$maxAnexos;
		$nombreAnexo=$radiNume.substr("00000".$numAnexo,-5);
		$subirArchivo=subirArchivo($ruta,$file,$nombreAnexo.".".$extension);
		$tamanoAnexo = $subirArchivo / 1024; //tamano en kilobytes
		$error=($error && !$subirArchivo)?true:false;
		$fechaAnexado= $db->conn->OffsetDate(0,$db->conn->sysTimeStamp);
		$tipoAnexo=tipoAnexo($extension,$db);
		if(!$error){
			$tipoAnexo=($tipoAnexo)?$tipoAnexo:"NULL";
			$consulta="INSERT INTO ANEXOS (ANEX_CODIGO,ANEX_RADI_NUME,ANEX_TIPO,ANEX_TAMANO,ANEX_SOLO_LECT,ANEX_CREADOR,
			            ANEX_DESC,ANEX_NUMERO,ANEX_NOMB_ARCHIVO,ANEX_ESTADO,SGD_REM_DESTINO,ANEX_FECH_ANEX, ANEX_BORRADO)
			            VALUES('$nombreAnexo',$radiNume,$tipoAnexo,$tamanoAnexo,'n','".$usuario['login']."','$descripcion'
			            ,$numAnexo,'$nombreAnexo.$extension',0,1,$fechaAnexado, 'N')";
			$error=$db->query($consulta);
			$consultaVerificacion = "SELECT ANEX_CODIGO FROM ANEXOS WHERE ANEX_CODIGO = '$nombreAnexo'";
			$rs=$db->query($consultaVerificacion);
			$cod = $rs->fields['ANEX_CODIGO'];
		}
		return $cod ? 'Anexo Creado'.$nombreAnexo : 'Error en la adicion verifique: ' . $nombreAnexo;
	}
	else
	{
		return "Error, llave de consulta no valida";
	}
}
/**
 *
 * funcion que calculcula el numero de anexos que tiene un radicado
 *
 *
 *
 * @param int  $radiNume radicado al cual se realiza se adiciona el anexo
 *
 * @param ConectionHandler $db
 *
 * @return int numero de anexos del radicado
 *
 */
function numeroAnexos($radiNume,$db){
	$consulta="SELECT COUNT(1) AS NUM_ANEX FROM ANEXOS WHERE ANEX_RADI_NUME={$radiNume}";
	$salida=0;
	$rs=& $db->query($consulta);
		if($rs && !$rs->EOF)
			$salida=$rs->fields['NUM_ANEX'];
		return  $salida;
}
/**
 *
 * funcioncion que rescata el maxido del anexo de los radicados
 *
 *
 *
 * @param int $radiNume numero del radicado
 *
 * @param ConnectionHandler $db conexion con la db
 *
 * @return int maximo
 *
 */
function maxRadicados($radiNume,$db){
	$consulta="SELECT max(ANEX_NUMERO) AS NUM_ANEX FROM ANEXOS WHERE ANEX_RADI_NUME={$radiNume}";
		$rs=& $db->query($consulta);
		if($rs && !$rs->EOF)
			$salida=$rs->fields['NUM_ANEX'];
		return  $salida;
}
/**
 *
 * funcion que consulta el tipo de anexo que se esta generando
 *
 *
 *
 *
 *
 * @param sting $extension extencion del archivo
 *
 * @param ConnectionHandler $db conexion con la DB
 *
 * @return int
 *
 */
function tipoAnexo($extension,$db){
	$consulta="SELECT ANEX_TIPO_CODI FROM ANEXOS_TIPO WHERE ANEX_TIPO_EXT='".strtolower($extension)."'";
	$salida=null;
	$rs=& $db->query($consulta);
		if($rs && !$rs->EOF)
			$salida=$rs->fields['ANEX_TIPO_CODI'];
	return $salida;
}

function prueba($entrada,$key){
	global $ruta_raiz,$keyWS;
	if ($keyWS==$key)
	{
		return $entrada;
	}
	else{
		return array('error'=>'La clave de consulta del servicio web no coincide.');
	}	
	
}
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
/**
 *
 * Esta funcion permite radicar un documento en Orfeo
 *
 * @param $usuEmail, este parametro es el correo electronico del usuario
 *
 * @param $file, Archivo asociado al radicado codificado en Base64
 *
 * @param $filename, Nombre del archivo que se radica
 *
 * @param $correo, Correo del usuario
 *
 * @param $destinos, arreglo de destinatarios destinatarios,predio,esp
 *
 * @param $asu, Asunto del radicado
 *
 * @param $med, Medio de radicacion
 *
 * @param $ane, descripcion de anexos
 *
 * @param $coddepe, codigo de la dependencia
 *
 * @param $tpRadicado, tipo de radicado
 *
 * @param $cuentai, cuenta interna del radicado
 *
 * @param $radi_usua_actu,
 *
 * @param $tip_rem
 *
 * @param $tdoc
 *
 * @param $tip_doc
 *
 * @param $carp_codi
 *
 * @param $carp_per
 *
 * @author Donaldo Jinete Forero
 *
 * @return El numero del radicado o un mensaje de error en caso de fallo
 *
 */

function radicarDocumento($file,$filename,$correo,$dest_cc_documento,$dest_nombre,$dest_prim_apel,$dest_seg_apel,$dest_telefono,$dest_direccion,$dest_mail,$dest_otro,$dest_idcont,$dest_idpais,$dest_codep,$dest_muni,$asu,$med,$ane,$coddepe,$tpRadicado,$cuentai,$radi_usua_actu,$tip_rem,$tdoc,$tip_doc,$carp_codi,$carp_per,$key)
{
	/*
	return "file:$file 
			fileName:$filename 
			correo:$correo 
			dest_cc_documento:$dest_cc_documento
			dest_nombre:$dest_nombre
			dest_prim_apel:$dest_prim_apel
			dest_seg_apel:$dest_seg_apel
			dest_telefono:$dest_telefono
			dest_direccion:$dest_direccion
			dest_mail:$dest_mail
			dest_otro:$dest_otro
			dest_idcont:$dest_idcont
			dest_idpais:$dest_idpais
			dest_codep:$dest_codep
			dest_muni:$dest_muni
			asu:$asu
			med:$med
			ane:$ane
			coddepe:$coddepe
			tpRadicado:$tpRadicado
			cuentai:$cuentai
			radi_usua_actu:$radi_usua_actu
			tip_rem:$tip_rem
			tdoc:$tdoc
			tip_doc:$tip_doc
			carp_codi:$carp_codi
			carp_per:$carp_per
			key:$key";
	*/
	global $keyWS;
	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
		//Conversiones de datos para compatibilidad con aplicaciones internas
		$destinatario = array(
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
		'idcont'=>$dest_idcont,
		'idpais'=>$dest_idpais,
		'codep'=>$dest_codep,
		'muni'=>$dest_muni
		);
		try {
			$radi_usua_actu = getInfoUsuarioXlogin($radi_usua_actu);
			$coddepe = $radi_usua_actu;
			$radi_usua_actu = trim($radi_usua_actu['usua_codi']);
			$coddepe = trim($coddepe['usua_depe']);
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
		$rad->radiTipoDeri = $tpRadicado ;
		$rad->radiCuentai = "'".trim($cuentai)."'";
		$rad->eespCodi =  $esp["documento"] ;
		$rad->mrecCodi =  $med;
		$rad->radiFechOfic =  date("Y-m-d");
		if(!$radicadopadre)  $radicadopadre = null;
		$rad->radiNumeDeri = trim($radicadopadre) ;
		$rad->radiPais =  $tmp_mun->get_pais_codi() ;
		$rad->descAnex = $ane ;
		$rad->raAsun = $asu ;
		$rad->radiDepeActu = $coddepe ;
		$rad->radiDepeRadi = $coddepe ;
		$rad->radiUsuaActu = $radi_usua_actu ;
		$rad->trteCodi =  $tip_rem ;
		$rad->tdocCodi=$tdoc ;
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
		$rad->usuaCodi=$radi_usua_actu ;
		$rad->dependencia=trim($coddepe) ;
		$noRad = $rad->newRadicado($tpRadicado,$coddepe) ;
		$nurad = trim($noRad) ;
		$sql_ret = $rad->updateRadicado($nurad,"/".date("Y")."/".$coddepe."/".$noRad.".pdf");
		if ($noRad=="-1")
		{
			return "Error no genero un Numero de Secuencia o Inserto el radicado";
		}
		$radicadosSel[0] = $noRad;
		$hist->insertarHistorico($radicadosSel,  $coddepe , $radi_usua_actu, $coddepe, $radi_usua_actu, " Radicacion desde servicio web ", $codTx);
		$sgd_dir_us2=2;
		$conexion = $db;
		/*
			Preparacion de variables para llamar el codigo del
			archivo grb_direcciones.php
		*/
		$tipo_emp_us1=trim($destinatario['tipo_emp']);
		$tipo_emp_us2=trim($predio['tipo_emp']);
		$muni_us1 = trim($destinatario['muni']);
		$muni_us2 = trim($predio['muni']);
		$muni_us3 = trim($esp['muni']);
		$codep_us1 = trim($destinatario['codep']);
		$codep_us2 = trim($predio['codep']);
		$codep_us3 = trim($esp['codep']);
		$grbNombresUs1 = trim($destinatario['nombre']) . " " . trim($destinatario['prim_apel']) . " ". trim($destinatario['seg_apel']);
		$grbNombresUs2 = trim($predio['nombre']) . " " . trim($predio['prim_apel']) . " ". trim($predio['seg_apel']);
		$cc_documento_us1 = trim($destinatario['cc_documento']);
		$cc_documento_us2 = trim($predio['cc_documento']);
		$documento_us1 = trim($destinatario['documento']);
		$documento_us2 = trim($predio['documento']);
		$direccion_us1 = trim($destinatario['direccion']);
		$direccion_us2 = trim($predio['direccion']);
		$telefono_us1 = trim($destinatario['telefono']);
		$telefono_us2 = trim($predio['telefono']);
		$mail_us1 = trim($destinatario['mail']);
		$mail_us2 = trim($predio['mail']);
		$otro_us1 = trim($destinatario['otro']);
		$otro_us2 = trim($predio['otro']);
		//************** INSERTAR DIRECCIONES *******************************
		if (!$muni_us1) $muni_us1 = NULL;
		if (!$muni_us2) $muni_us2 = NULL;
		if (!$muni_us3) $muni_us3 = NULL;
		// Creamos las valores del codigo del dpto y mcpio desglozando el valor del <SELECT> correspondiente.
		if (!is_null($muni_us1)){
			$tmp_mun = new Municipio($conexion);
			$tmp_mun->municipio_codigo($codep_us1,$muni_us1);
			$tmp_idcont = $tmp_mun->get_cont_codi();
			$tmp_idpais = $tmp_mun->get_pais_codi();
			$muni_tmp1 = explode("-",$muni_us1);
			switch (count($muni_tmp1))
			{
				case 4:
				{
					$idcont1 = $muni_tmp1[0];
					$idpais1 = $muni_tmp1[1];
					$dpto_tmp1 = $muni_tmp1[2];
					$muni_tmp1 = $muni_tmp1[3];
				}
				break;
			case 3:
				{
					$idcont1 = $tmp_idcont;
					$idpais1 = $muni_tmp1[0];
					$dpto_tmp1 = $muni_tmp1[1];
					$muni_tmp1 = $muni_tmp1[2];
				}
				break;
			case 2:
				{
					$idcont1 = $tmp_idcont;
					$idpais1 = $tmp_idpais;
					$dpto_tmp1 = $muni_tmp1[0];
					$muni_tmp1 = $muni_tmp1[1];
				}
				break;
			}
			unset($tmp_mun);
			unset($tmp_idcont);
			unset($tmp_idpais);
		}
		if (!is_null($muni_us2))
		{
			$tmp_mun = new Municipio($conexion);
			$tmp_mun->municipio_codigo($codep_us2,$muni_us2);
			$tmp_idcont = $tmp_mun->get_cont_codi();
			$tmp_idpais = $tmp_mun->get_pais_codi();
			$muni_tmp2 = explode("-",$muni_us2);
			switch (count($muni_tmp2))
			{
				case 4:
				{
					$idcont2 = $muni_tmp2[0];
					$idpais2 = $muni_tmp2[1];
					$dpto_tmp2 = $muni_tmp2[2];
					$muni_tmp2 = $muni_tmp2[3];
				}
				break;
			case 3:
				{
					$idcont2 = $tmp_idcont;
					$idpais2 = $muni_tmp2[0];
					$dpto_tmp2 = $muni_tmp2[1];
					$muni_tmp2 = $muni_tmp2[2];
				}
				break;
			case 2:
				{
					$idcont2 = $tmp_idcont;
					$idpais2 = $tmp_idpais;
					$dpto_tmp2 = $muni_tmp2[0];
					$muni_tmp2 = $muni_tmp2[1];
				}
				break;
			}
			unset($tmp_mun);unset($tmp_idcont);unset($tmp_idpais);
		}
		if (!is_null($muni_us3))
		{
			$tmp_mun = new Municipio($conexion);
			$tmp_mun->municipio_codigo($codep_us3,$muni_us3);
			$tmp_idcont = $tmp_mun->get_cont_codi();
			$tmp_idpais = $tmp_mun->get_pais_codi();
			$muni_tmp3 = explode("-",$muni_us3);
			switch (count($muni_tmp3))
			{
				case 4:
				{
					$idcont3 = $muni_tmp3[0];
					$idpais3 = $muni_tmp3[1];
					$dpto_tmp3 = $muni_tmp3[2];
					$muni_tmp3 = $muni_tmp3[3];
				}
				break;
				case 3:
				{
					$idcont1 = $tmp_idcont;
					$idpais3 = $muni_tmp3[0];
					$dpto_tmp3 = $muni_tmp3[1];
					$muni_tmp3 = $muni_tmp3[2];
				}
				break;
			case 2:
				{
					$idcont3 = $tmp_idcont;
					$idpais3 = $tmp_idpais;
					$dpto_tmp3 = $muni_tmp3[0];
					$muni_tmp3 = $muni_tmp3[1];
				}
				break;
			}
			unset($tmp_mun);unset($tmp_idcont);unset($tmp_idpais);
		}
		$newId = false;
		if(!$modificar)
		{
	   		$nextval=$conexion->nextId("sec_dir_direcciones");
		}
		if ($nextval==-1)
		{
			return "No se encontro la secuencia sec_dir_direcciones ";
		}
		global $ADODB_COUNTRECS;
		if($documento_us1!='')
		{
			$sgd_ciu_codigo=0;
			$sgd_oem_codigo=0;
			$sgd_esp_codigo=0;
			$sgd_fun_codigo=0;
	  		if($tipo_emp_us1==0)
	  		{
	  			$sgd_ciu_codigo= getAddresseeId($documento_us1);
				$sgdTrd = "1";
			}
			if($tipo_emp_us1==1)
			{
				$sgd_esp_codigo=$documento_us1;
				$sgdTrd = "3";
			}
			if($tipo_emp_us1==2)
			{
				$sgd_oem_codigo=$documento_us1;
				$sgdTrd = "2";
			}
			if($tipo_emp_us1==6)
			{
				$sgd_fun_codigo=$documento_us1;
				$sgdTrd = "4";
			}

			$ADODB_COUNTRECS = true;
			$record = array();
			//$record['SGD_TRD_CODIGO'] = $sgdTrd;
			$record['SGD_TRD_CODIGO'] = 6;
			$record['SGD_DIR_NOMREMDES'] = $grbNombresUs1;
			$record['SGD_DIR_DOC'] = $cc_documento_us1;
			$record['MUNI_CODI'] = $dest_muni;
			$record['DPTO_CODI'] = $dest_codep;
			$record['ID_PAIS'] = $dest_idpais;
			//$record['ID_CONT'] = $idcont1;
			$record['ID_CONT'] = 1;
			$record['SGD_DOC_FUN'] = $sgd_fun_codigo;
			$record['SGD_OEM_CODIGO'] = $sgd_oem_codigo;
			$record['SGD_CIU_CODIGO'] = $sgd_ciu_codigo;
			$record['SGD_OEM_CODIGO'] = $sgd_oem_codigo;
			$record['SGD_ESP_CODI'] = $sgd_esp_codigo;
			$record['RADI_NUME_RADI'] = $nurad;
			$record['SGD_SEC_CODIGO'] = 0;
			$record['SGD_DIR_DIRECCION'] = $direccion_us1;
			$record['SGD_DIR_TELEFONO'] = trim($telefono_us1);
			$record['SGD_DIR_MAIL'] = $mail_us1;
			$record['SGD_DIR_TIPO'] = 1;
			$record['SGD_DIR_CODIGO'] = $nextval;
			$record['SGD_DIR_NOMBRE'] = $otro_us1;

			return " SGD_TRD_CODIGO: " . $record['SGD_TRD_CODIGO'] .
					" - SGD_DIR_NOMREMDES: " . $record['SGD_DIR_NOMREMDES'] .
					" - SGD_DIR_DOC: " . $record['SGD_DIR_DOC'] .
					" - MUNI_CODI: " . $record['MUNI_CODI'] .
					" - DPTO_CODI: " . $record['DPTO_CODI'] .
					" - ID_PAIS: " . $record['ID_PAIS'] .
					" - ID_CONT: " . $record['ID_CONT'] .
					" - SGD_DOC_FUN: " . $record['SGD_DOC_FUN'] .
					" - SGD_OEM_CODIGO: " . $record['SGD_OEM_CODIGO'] .
					" - SGD_CIU_CODIGO: " . $record['SGD_CIU_CODIGO'] .
					" - SGD_OEM_CODIGO: " . $record['SGD_OEM_CODIGO'] .
					" - SGD_ESP_CODI: " . $record['SGD_ESP_CODI'] .
					" - RADI_NUME_RADI: " . $record['RADI_NUME_RADI'] .
					" - SGD_SEC_CODIGO: " . $record['SGD_SEC_CODIGO'] .
					" - SGD_DIR_DIRECCION: " . $record['SGD_DIR_DIRECCION'] .
					" - SGD_DIR_TELEFONO: " . $record['SGD_DIR_TELEFONO'] .
					" - SGD_DIR_MAIL: " . $record['SGD_DIR_MAIL'] .
					" - SGD_DIR_TIPO: " . $record['SGD_DIR_TIPO'] .
					" - SGD_DIR_CODIGO: " . $record['SGD_DIR_CODIGO'] .
					" - SGD_DIR_NOMBRE: " . $record['SGD_DIR_NOMBRE'] ;

		$insertSQL = $conexion->conn->Replace("SGD_DIR_DRECCIONES", $record, array('RADI_NUME_RADI','SGD_DIR_TIPO'), $autoquote = true);
		//return $insertSQL;
		switch ($insertSQL)
		{	case 1:{	//Insercion Exitosa
						$dir_codigo_new = $nextval;
						$newId=true;
					}break;
			case 2:{	//Update Exitoso
						$newId = false;
					}break;
			case 0:{	//Error Transaccion.
						return  "No se ha podido actualizar la informacion de SGD_DIR_DRECCIONES UNOss -- $isql --";
					}break;
		}
		unset($record);
		$ADODB_COUNTRECS = false;
	}

		// ***********************  us2
		if($documento_us2!='')
		{
			$sgd_ciu_codigo=0;
		    $sgd_oem_codigo=0;
		    $sgd_esp_codigo=0;
			$sgd_fun_codigo=0;
		  if($tipo_emp_us2==0){
				$sgd_ciu_codigo=$documento_us2;
				$sgdTrd = "1";
			}
			if($tipo_emp_us2==1){
				$sgd_esp_codigo=$documento_us2;
				$sgdTrd = "3";
			}
			if($tipo_emp_us2==2){
				$sgd_oem_codigo=$documento_us2;
				$sgdTrd = "2";
			}
			if($tipo_emp_us2==6){
				$sgd_fun_codigo=$documento_us2;
				$sgdTrd = "4";
			}
			$isql = "select * from sgd_dir_drecciones where radi_nume_radi=$nurad and sgd_dir_tipo=2";
			$rsg=$conexion->query($isql);
		    if 	($rsg->EOF)
			{
				//if($newId==true)
					//{
					   $nextval=$conexion->nextId("sec_dir_direcciones");
					//}
					if ($nextval==-1)
					{
						//$db->conn->RollbackTrans();
						echo "<span class='etextomenu'>No se encontr� la secuencia sec_dir_direcciones ";
					}
				$isql = "insert into SGD_DIR_DRECCIONES(SGD_TRD_CODIGO, SGD_DIR_NOMREMDES, SGD_DIR_DOC, DPTO_CODI, MUNI_CODI,
		      			id_pais, id_cont, SGD_DOC_FUN, SGD_OEM_CODIGO, SGD_CIU_CODIGO, SGD_ESP_CODI, RADI_NUME_RADI, SGD_SEC_CODIGO,
		      			SGD_DIR_DIRECCION, SGD_DIR_TELEFONO, SGD_DIR_MAIL, SGD_DIR_TIPO, SGD_DIR_CODIGO, SGD_DIR_NOMBRE)
			  			values('$sgdTrd', '$grbNombresUs2', '$cc_documento_us2', $dpto_tmp2, $muni_tmp2, $idpais2, $idcont2,
			  			$sgd_fun_codigo, $sgd_oem_codigo, $sgd_ciu_codigo, $sgd_esp_codigo, $nurad, 0,'".trim($direccion_us2).
			  			"', '".trim($telefono_us2)."', '$mail_us2', 2, $nextval, '$otro_us2')";
		   	  $dir_codigo_new = $nextval;
		   	  $newId=true;
		    }
			 else
			{
			  $newId = false;
				$isql = "update SGD_DIR_DRECCIONES
						set MUNI_CODI=$muni_tmp2, DPTO_CODI=$dpto_tmp2, id_pais=$idpais2, id_cont=$idcont2
						,SGD_OEM_CODIGO=$sgd_oem_codigo
						,SGD_CIU_CODIGO=$sgd_ciu_codigo
						,SGD_ESP_CODI=$sgd_esp_codigo
						,SGD_DOC_FUN=$sgd_fun_codigo
						,SGD_SEC_CODIGO=0
						,SGD_DIR_DIRECCION='$direccion_us2'
						,SGD_DIR_TELEFONO='$telefono_us2'
						,SGD_DIR_MAIL='$mail_us2'
						,SGD_DIR_NOMBRE='$otro_us2'
						,SGD_DIR_NOMREMDES='$grbNombresUs2'
						,SGD_DIR_DOC='$cc_documento_us2'
						,SGD_TRD_CODIGO='$sgdTrd'
					 	where radi_nume_radi=$nurad and SGD_DIR_TIPO=2 ";
			}
			$rsg=$conexion->query($isql);
			if (!$rsg){
				return "No se ha podido actualizar la informacion de SGD_DIR_DRECCIONES DOS -- $isql --";
			}
		}
		if($documento_us1!='' and $cc!='')
		{
			$sgd_ciu_codigo=0;
			$sgd_oem_codigo=0;
			$sgd_esp_codigo=0;
			$sgd_fun_codigo=0;
			echo "--$sgd_emp_us1--";
			  if($tipo_emp_us1==0){
				$sgd_ciu_codigo=$documento_us1;
				$sgdTrd = "1";
			}
			if($tipo_emp_us1==1){
				$sgd_esp_codigo=$documento_us1;
				$sgdTrd = "3";
			}
			if($tipo_emp_us1==2){
				$sgd_oem_codigo=$documento_us1;
				$sgdTrd = "2";
			}
			if($tipo_emp_us1==6){
				$sgd_fun_codigo=$documento_us1;
				$sgdTrd="4";
			}
			if($newId==true)
				{
				   $nextval=$conexion->nextId("sec_dir_direcciones");
				}
				if ($nextval==-1)
				{
					//$db->conn->RollbackTrans();
					return "No se encontrasena la secuencia sec_dir_direcciones ";
				}
		  $num_anexos=$num_anexos+1;
		  $str_num_anexos = substr("00$num_anexos",-2);
		  $sgd_dir_tipo = "7$str_num_anexos" ;
			$isql = "insert into SGD_DIR_DRECCIONES (SGD_TRD_CODIGO, SGD_DIR_NOMREMDES, SGD_DIR_DOC, MUNI_CODI, DPTO_CODI,
					id_pais, id_cont, SGD_DOC_FUN, SGD_OEM_CODIGO, SGD_CIU_CODIGO, SGD_ESP_CODI, RADI_NUME_RADI, SGD_SEC_CODIGO,
					SGD_DIR_DIRECCION, SGD_DIR_TELEFONO, SGD_DIR_MAIL, SGD_DIR_TIPO, SGD_DIR_CODIGO, SGD_ANEX_CODIGO, SGD_DIR_NOMBRE) ";
			$isql .= "values ('$sgdTrd', '$grbNombresUs1', '$cc_documento_us1', $muni_tmp1, $dpto_tmp1, $idpais1, $idcont1,
								$sgd_fun_codigo, $sgd_oem_codigo, $sgd_ciu_codigo, $sgd_esp_codigo, $nurad, 0, '$direccion_us1',
								'".trim($telefono_us1)."', '$mail_us1', $sgd_dir_tipo, $nextval, '$codigo', '$otro_us7' )";
		  $dir_codigo_new = $nextval;
		  $nextval++;
		  $rsg=$conexion->query($isql);
			if (!$rsg)
			{
				//$conexion->conn->RollbackTrans();
				return "No se ha podido actualizar la informacion de SGD_DIR_DRECCIONES TRES -- $isql --";
			}
		}
		//*********************** FIN INSERTAR DIRECCIONES **********************
		$retval .=$noRad;
		if($filename!=''){
			$ext=explode('.',$filename);
			cambiarImagenRad($retval,$ext[1],$file,$key);
		}
		return $retval;
	}
	else
	{
		return "Error, Llave de consulta no valida";
	}
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
