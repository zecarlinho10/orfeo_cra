<?php

error_reporting(0);
ini_set('display_errors', 0);

/**
 * Prueba de lectura de correo, con adjunto. 
 * @author : cricaurte.
 * @date   : 2023/05/26.
 */

$ruta_raiz = "..";
include_once ("$ruta_raiz/config.php");





require_once __DIR__ . "/lib/vendor/autoload.php";

require_once __DIR__ . "/lib/config.php";
require_once __DIR__ . "/vendor/autoload.php";


//die("Hola mundo");

//require_once 'dompdf/autoload.inc.php';


use TCPDF\TCPDF;


use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

$graph = new Graph();

$myAccessToken = getAccessToken();
//die($myAccessToken);

$graph->setAccessToken($myAccessToken);

$orderBy = 'sentDateTime asc';
$messages = $graph->createRequest('GET','/users/'.USER_ID.'/mailfolders/'.PARA_RADICAR.'/messages?$orderby=' . urlencode($orderBy))
			->setReturnType(Model\Message::class)
		    ->execute();

$to="correo@cra.gov.co;";
foreach ($messages as $message) {

	$from = $message->getFrom();
	$remitenteName = $from->getEmailAddress()->getName();
	$remitenteEmail = $from->getEmailAddress()->getAddress();

	$toRecipients = $message->getToRecipients();
	$ccRecipients = $message->getCcRecipients();
	$bccRecipients = $message->getBccRecipients();
	

	$conCopia[0]["correo"]=$remitenteEmail;
	$conCopia[0]["name"]=$remitenteName;
	$cc=1;
	//echo "toRecipients: "; var_dump($toRecipients);
	foreach ($toRecipients as $recipient) {
	    //echo "<br>   recipient:"; var_dump($recipient);
	    foreach ($recipient as $correo) {
		    if(strtolower($correo["address"]) <> "correo@cra.gov.co" && strtolower($correo["address"]) <> "correo@cra.gov.co"){
		    		$conCopia[$cc]["correo"]=$correo["name"];
                    $conCopia[$cc]["correo"]=$correo["address"];
                    $to.= $correo["name"] . "<" . $correo["address"].">;";
		    		$cc=$cc+1;
		    		//echo $correo["name"] . "<" . $correo["address"].">;" . "\r\n";
            }
		}
	}

	foreach ($ccRecipients as $recipient) {
	    //echo "<br>   recipient:"; var_dump($recipient);
	    foreach ($recipient as $correo) {
		    if(strtolower($correo["address"]) <> "correo@cra.gov.co" && strtolower($correo["address"]) <> "correo@cra.gov.co"){
                    $conCopia[$cc]["correo"]=$correo["name"];
                    $conCopia[$cc]["correo"]=$correo["address"];
                    $to.= $correo["name"] . "<" . $correo["address"].">;";
		    		$cc=$cc+1;
		    		echo $correo["name"] . "<" . $correo["address"].">;" . "\r\n";
            }	    
		}
	}

	foreach ($bccRecipients as $recipient) {
	    foreach ($recipient as $correo) {
		    if(strtolower($correo["address"]) <> "correo@cra.gov.co" && strtolower($correo["address"]) <> "correo@cra.gov.co"){
                    $conCopia[$cc]["correo"]=$correo["name"];
                    $conCopia[$cc]["correo"]=$correo["address"];
                    $to.= $correo["name"] . "<" . $correo["address"].">;";
		    		$cc=$cc+1;
		    		echo $correo["name"] . "<" . $correo["address"].">;" . "\r\n";
            }
		}
	}

	//$emlContent .= "--boundary1\r\n";
    //$emlContent .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";

    date_default_timezone_set('America/Bogota'); // Establecer la zona horaria deseada
    $email_date = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $message->getReceivedDateTime()->format('Y-m-d\TH:i:s\Z')); // Crear el objeto DateTime con el formato deseado
    $email_date_formatted = $email_date->format('r'); // Formatear la fecha y hora
	$fechaTmp = new DateTime($email_date_formatted);
    $interval = new DateInterval('PT5H');
    $modifiedDateTime = $fechaTmp->sub($interval);
    $email_date2 = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $modifiedDateTime->format('Y-m-d\TH:i:s\Z')); // Crear el objeto 
    $fechaHoraCorreo = $email_date2->format('r');


    $emlContent = "From: " . $remitenteName . " <" . $remitenteEmail . ">\r\n";
    $emlContent .= "To: " . $to . "\r\n";
    $emlContent .= "Subject: " . $message->getSubject() . "\r\n";
    $emlContent .= "Date: " . $fechaHoraCorreo . "\r\n";
    $emlContent .= "MIME-Version: 1.0\r\n";
	$emlContent .= "Content-Type: multipart/related; boundary=boundary1\r\n\r\n";
	$emlContent .= "This is a multi-part message in MIME format.\r\n";

	
	    $emlContent .= "--boundary1\r\n";
	    
	    $emlContent .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
	    $emlContent .= "<html><body>";
	    $emlContent .= "<p>" . $message->getBody()->getContent() . "</p>";
	    $emlContent .= "</body></html>\r\n";

	    // Adjuntar imágenes en línea
	    $message1 = $graph->createRequest('GET', '/users/'.USER_ID.'/messages/' . $message->getId(). '/attachments')
	                ->setReturnType(Model\Attachment::class)
	                ->execute();

	    //$attachments = $message->getAttachments();
	    foreach ($message1 as $attachment) {
	    	$contenido = $graph->createRequest('GET', '/users/'.USER_ID.'/messages/' . $message->getId() . '/attachments/' . $attachment->getId() . '/$value')
	    				 ->execute();
	    	$contentString = $contenido->getRawBody();
	    	//var_dump($attachment); 
	    	$objTMP=$attachment;
	    	$valor_objeto = var_export($objTMP, true);
	    	
	    	$nueva_cadena = str_replace("Microsoft\Graph\Model\Attachment::__set_state(array(", "", $valor_objeto);
	    	$nueva_cadena = str_replace("'_propDict' =>", "", $nueva_cadena);
	    	$nueva_cadena = str_replace("array (", "", $nueva_cadena);
	    	$nueva_cadena = str_replace("),", "", $nueva_cadena);
	    	$nueva_cadena = str_replace(")", "", $nueva_cadena);
	    	$nueva_cadena = str_replace(" ", "", $nueva_cadena);
	    	$nueva_cadena = trim($nueva_cadena);
	    	$nueva_cadena = substr($nueva_cadena, 0, -1);
	    	$nueva_cadena = str_replace(array("\r", "\n"), '', $nueva_cadena);
	    	$nueva_cadena = str_replace("'", "", $nueva_cadena);
	    	$pares_atributo_valor = explode(",", $nueva_cadena);

			// Creamos un arreglo vacío para guardar los resultados
			$archivo = array();

			// Recorremos los pares atributo-valor y los agregamos al arreglo resultado
			foreach ($pares_atributo_valor as $par) {
			    $atributo_valor = explode("=>", $par);
			    $archivo[$atributo_valor[0]] = $atributo_valor[1];
			}

	       	if ($archivo["isInline"]=="false") {
	            $imageContent = base64_encode($contentString);
	            $emlContent .= "--boundary1\r\n";
	            $emlContent .= "Content-Type: " . $attachment->getContentType() . "\r\n";
	            $emlContent .= "Content-Transfer-Encoding: base64\r\n";
	            $emlContent .= "Content-ID: <" . $attachment->getId() . ">\r\n";
	            $emlContent .= "Content-Disposition: inline; filename=" . $attachment->getName() . "\r\n";
	            $emlContent .= "\r\n";
	            $emlContent .= $imageContent . "\r\n";
	        }
	    }

	$emlContent .= "--boundary1--\r\n";


	///////////////////

	// Reemplazar los valores del atributo src con las rutas absolutas a las imágenes
	$emlContent = preg_replace_callback('/(src=["\'])cid:(.*?)["\']/', function ($matches) use ($graph, $message, $message1) {
	    $cid = $matches[2];
	    $attachment1 = null;
	    foreach ($message1 as $attachment) {
	    	$contenido = $graph->createRequest('GET', '/users/'.USER_ID.'/messages/' . $message->getId() . '/attachments/' . $attachment->getId() . '/$value')
	    				 ->execute();
	    	$contentString = $contenido->getRawBody();
	    	//var_dump($attachment); 
	    	$objTMP=$attachment;
	    	$valor_objeto = var_export($objTMP, true);
	    	
	    	$nueva_cadena = str_replace("Microsoft\Graph\Model\Attachment::__set_state(array(", "", $valor_objeto);
	    	$nueva_cadena = str_replace("'_propDict' =>", "", $nueva_cadena);
	    	$nueva_cadena = str_replace("array (", "", $nueva_cadena);
	    	$nueva_cadena = str_replace("),", "", $nueva_cadena);
	    	$nueva_cadena = str_replace(")", "", $nueva_cadena);
	    	$nueva_cadena = str_replace(" ", "", $nueva_cadena);
	    	$nueva_cadena = trim($nueva_cadena);
	    	$nueva_cadena = substr($nueva_cadena, 0, -1);
	    	$nueva_cadena = str_replace(array("\r", "\n"), '', $nueva_cadena);
	    	$nueva_cadena = str_replace("'", "", $nueva_cadena);
	    	$pares_atributo_valor = explode(",", $nueva_cadena);

			// Creamos un arreglo vacío para guardar los resultados
			$archivo = array();

			// Recorremos los pares atributo-valor y los agregamos al arreglo resultado
			foreach ($pares_atributo_valor as $par) {
			    $atributo_valor = explode("=>", $par);
			    $archivo[$atributo_valor[0]] = $atributo_valor[1];
			}

	       	if ($archivo["isInline"]=="true") {
	            if ($archivo["contentId"] == $cid) {
		            $attachment1 = $archivo;
		            echo "\r\n cid: " . $cid;
		            break;
		        }
	        }
	    }
	    if (!$attachment1) {
	    	echo "\r\n cid 0: " . $matches[0];
	        return $matches[0];
	    }

	    //$imageContent = $attachment1["contentBytes"];
	    $imageContent = $contentString;
	    $imageData = base64_encode($imageContent);
	    $imageType = $attachment->getContentType();
	    echo "\r\n imageType: " . $imageType;
	    return "src=\"data:$imageType;base64,$imageData\"";
	}, $emlContent);

	// Agregar el contenido del correo electrónico al archivo EML
	$emlContent .= chunk_split(base64_encode($emlContent));

	// Guardar el archivo EML
	$fileName = BODEGA.'/correos.eml';
	file_put_contents($fileName, $emlContent);
	echo "archivo " . $fileName . " creado exitosamente";
	exit();
}


function getAccessToken()
{
 $tenantId = TENANT_ID;
 $guzzle = new \GuzzleHttp\Client();
 $url = 'https://login.microsoftonline.com/' . $tenantId .'/oauth2/v2.0/token';
 $token = json_decode($guzzle->post($url,[
	 'form_params' => [
		 'client_id' => CLIENT_ID,
		 'client_secret' => CLIENT_SECRET,
		 'scope' => 'https://graph.microsoft.com/.default',
		 'grant_type' => 'client_credentials',
		],
	])->getBody()->getContents());
 $accessToken = $token->access_token;
 
 return $accessToken;

}

?>

