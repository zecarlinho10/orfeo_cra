<?php

$ruta_raiz = "../";
include_once $ruta_raiz."config.php";

//$ws_url = 'http://192.168.100.101/orfeopb2013/webServices/servidor.php?wsdl';
//$ws_url = 'http://192.168.100.101/orfeo/webServices/servidor.php?wsdl';
//$ws_url = 'http://localhost:8080/orfeo382p/webServices/servidor.php?wsdl';
//$ws_url = 'http://localhost/orfeo_imprenta/webServices/servidor.php?wsdl';

$client = new SoapClient(
    $ws_url,
    array(
        // Stuff for development.
        'trace' => 1,
        'exceptions' => false,
        'cache_wsdl' => WSDL_CACHE_NONE,
        'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
        // Auth credentials for the SOAP request.
        //'login' => $this->ws_login,
        //'password' => $this->ws_password,
        // Proxy url.
    )
);



//Realizamos test de acuerdo a la variable por get recibida
$method = isset($_GET['run'])?$_GET['run']:'';

switch($method)
{
	case 'UploadFile':
		$bytes = base64_encode(@file_get_contents('20131000000262_0001.pdf'));
		$filename = '20131000000262_0001.pdf';
		$consume = $client->UploadFile($bytes,$filename,$keyWS);
	break;

	case 'crearAnexo':
		$radiNume = '20139980000191';
		$file 	  = base64_encode(@file_get_contents('20131000000262_0001.pdf'));
		$filename = '20131000000262_0001.pdf';
		$correo   = 'cdiaz@ximil.co';
		$descripcion = 'Test radicacion anexo desde ws';
		$consume  = $client->crearAnexo($radiNume,$file,$filename,$correo,$descripcion,$keyWS);
	break;

	case 'crearExpediente':
		$nurad           = '20139980000191';
		$mailresponsable = 'cdiaz@ximil.co';
		$anoExp			 = '2013';
		$fechaExp        = '2013-12-09';
		$codiSRD     	 = '75';
		$codiSBRD     	 = '1';
		$consume         = $client->crearExpediente($nurad,$mailresponsable,$anoExp,$fechaExp,$codiSRD,$codiSBRD,$keyWS);
	break;

	case 'getUsuarioCorreo':
		$correo = 'lfuentes@imprenta.gov.co';
		$consume = $client->getUsuarioCorreo($correo,$keyWS);
	break;

	case 'darUsuario':
		$consume = $client->darUsuario($keyWS);
	break;

	case 'darUsuarioSelect':
		$usuaEmail	= 'lfuentes@imprenta.gov.co';
		$usuaDoc = '1234567890';
		$consume = $client->darUsuarioSelect($usuaEmail,$usuaDoc,$keyWS);
	break;

	case 'getInfoUsuario':
		$usuaLoginMail	= 'lfuentes@imprenta.gov.co';
		$consume = $client->getInfoUsuario($usuaLoginMail,$keyWS);
	break;

	case 'solicitarAnulacion':
 		$nurad = '20139980000191';
 		$descripcion = 'Test WS';
 		$correo = 'cdiaz@ximil.co';
		$consume = $client->solicitarAnulacion($nurad,$descripcion,$correo,$keyWS);
	break;

	case 'radicarDocumento':

	 	$file = base64_encode(@file_get_contents('20131000000262_0001.pdf'));
	    $fileName= '20131000000262_0001.pdf';
	    $correo= 'cdiaz@ximil.co';
	    $destinatarioOrg = array();
		$destinatarioOrg['cc_documento'] = '1032376323'; //'cc_documento'
		$destinatarioOrg['nombre'] = 'Carlos Andres'; //'nombre'
		$destinatarioOrg['prim_apel'] = 'Diaz'; //'prim_apel'
		$destinatarioOrg['seg_apel'] = 'Gomez'; //'seg_apel'
		$destinatarioOrg['telefono'] = '311849982'; //'telefono'
		$destinatarioOrg['direccion'] = 'Calle 12 No. 11-31'; //'direccion'
		$destinatarioOrg['mail'] = 'cdiaz@ximil.co'; //'mail'
		$destinatarioOrg['otro'] = 'Carlos Andres'; //'otro'
		$destinatarioOrg['idcont'] = '1'; //'idcont'
		$destinatarioOrg['idpais'] = '170'; //'idpais'
		$destinatarioOrg['codep'] = '11'; //'codep'
		$destinatarioOrg['muni'] = '1'; //'muni'
	    $asu= 'Test web service';
	    $med= '1';
	    $ane= '3';
	    $coddepe= '998';
	    $tpRadicado= '2';
	    $cuentai= '1234';
	    $radi_usua_actu= 'cdiaz@ximil.co';
	    $tip_rem= '0';
	    $tdoc= '3';
	    $tip_doc= '0';
	    $carp_codi= '0';
	    $carp_per= '0';

		$consume = $client->radicarDocumento($file,
											 $fileName,
										 	 $correo,
										 	 $destinatarioOrg['cc_documento'],
										 	 $destinatarioOrg['nombre'],
										 	 $destinatarioOrg['prim_apel'],
										 	 $destinatarioOrg['seg_apel'],
										 	 $destinatarioOrg['telefono'],
										 	 $destinatarioOrg['direccion'],
										 	 $destinatarioOrg['mail'],
										 	 $destinatarioOrg['otro'],
										 	 $destinatarioOrg['idcont'],
										 	 $destinatarioOrg['idpais'],
										 	 $destinatarioOrg['codep'],
										 	 $destinatarioOrg['muni'],
											 $asu,
											 $med,
											 $ane,
											 $coddepe,
											 $tpRadicado,
											 $cuentai,
											 $radi_usua_actu,
											 $tip_rem,
											 $tdoc,
											 $tip_doc,
											 $carp_codi,
											 $carp_per,
											 $keyWS);

	break;

	case 'anexarExpediente':
		$numRadicado   = '20139980000282';
		$numExpediente = '2013998750100005E';
		$usuaLoginMail = 'cdiaz@ximil.co';
		$observa       = 'Test Ws';
		$consume = $client->anexarExpediente($numRadicado,$numExpediente,$usuaLoginMail,$observa,$keyWS);
	break;

	case 'cambiarImagenCorreo':
		$numRadicado   = '20139980000282';
		$texto 		   = 'Prueba cambio imagen texto desde ws';
		$consume 	   = $client->cambiarImagenCorreo($numRadicado,$texto,$keyWS);
	break;

	case 'getInfoRadicado':
		$numRadicado   = '20139980000282';
		$consume 	   = $client->getInfoRadicado($numRadicado,$keyWS);
	break;
}

//Imprimimos respuesta web service
var_dump($consume);
?>
