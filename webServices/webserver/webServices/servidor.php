<?php

/**********************************************************************************

Diseno de un Web Service que permita la interconexion con el SUI

**********************************************************************************/



/**
 *
 * @author Carlos Ricaurte
 *
 */



//Llamado a la clase nusoap



$ruta_raiz = "./";

define('RUTA_RAIZ','./');



require_once "nusoap/lib/nusoap.php";

//Asignacion del namespace

$ns="webServices/nusoap";


//Creacion del objeto soap_server

$server = new soap_server();



$server->configureWSDL('Interoperabilidad',$ns);



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

		'file' => 'xsd:base64binary',										//archivo en base 64

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

$server->register('consultaSUI',
	array(
		'departamento' => 'xsd:string',
    	'municipio' => 'xsd:string',
    	'razonsocial' => 'xsd:string',
		'estprestador' => 'xsd:string',
    	'servicio'=> 'xsd:string',
		'key'	=> 'xsd:string'
	),
	array(
		'return' => 'xsd:string'
	),
	$ns,
	$ns."#consultaSUI",
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


/******************************************************************************

 Servicios  que se ofrecen

******************************************************************************/





/**
 *
 * Esta funcion pretende almacenar todos los usuarios de orfeo, con la informacion
 *
 * de correo, cedula, dependencia y codigo del usuario
 *
 * @author Carlos Ricaurte
 *
 * @return consulta SUI
 *
 */

function consultaSUI($departamento, $municipio,$razonsocial, $estprestador, $servicio, $key)
{
	//global $keyWS;
	//crea cadena ky
	$semilla = "SARA2020ORFEO";
	$cadena = $radicado . $usuarioOrigen . $usuarioDestino . $semilla;

	$keyWS = md5($cadena);
	//Validamos que la key recibida sea valida
	//if ($key == $keyWS)
	if (1 == 1)
	{
		return "ok";
	}
	else
	{
		return "Error, Llave de consulta no valida";
	}
}



$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';

$server->service($HTTP_RAW_POST_DATA);

?>

