<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/config.php";


use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Microsoft\Graph\Exception\ClientException;

$graph = new Graph();
$myAccessToken = getAccessToken();
//echo $myAccessToken;
$graph->setAccessToken($myAccessToken);

//$messages = $graph->createRequest('GET','/users/d5ee96dc-d696-4f3e-9fd5-8a2f2c910b91/mailfolders/inbox/messages')->setReturnType(Model\Message::class)->execute();

$request = $graph->createRequest('GET', '/users/d5ee96dc-d696-4f3e-9fd5-8a2f2c910b91/mailfolders/inbox/messages')->setReturnType(Microsoft\Graph\Model\Message::class);

// Enviar la solicitud y obtener el mensaje
$messages = $request->execute();

$attachmentName = "../../bodega/tmp/";
foreach ($messages as $message){
	$subject = $message->getSubject();
	$body = $message->getBody()->getContent();
	$toRecipients = $message->getToRecipients();
	$ccRecipients = $message->getCcRecipients();
	$bccRecipients = $message->getBccRecipients();
	
	$messageId = $message->getId();

	echo "<br>messageId:" . $messageId;

	try{
		$attachments = $graph->createRequest('GET', '/users/d5ee96dc-d696-4f3e-9fd5-8a2f2c910b91/messages/' . $message->getId() . '/attachments')
                         ->setReturnType(Model\Attachment::class)
                         ->execute();
    }catch (ClientException $ex) {
	    echo "Error al obtener los mensajes: " . $ex->getMessage() . "\n";
	}
	echo "<br>Total Adjuntos:" . count($attachments);
	/*
	if (count($attachments) > 0) {
	    // Descargar cada adjunto
	    foreach ($attachments as $attachment) {
	        // Obtener la información del adjunto
	        $attachmentId = $attachment->getId();
	        $attachmentName = $attachment->getName();
	        $attachmentType = $attachment->getContentType();
	        $attachmentContent = $attachment->getContentBytes();

	        // Descargar el adjunto
	        file_put_contents($attachmentName, base64_decode($attachmentContent));
	    }
	} else {
	    // El mensaje no tiene adjuntos
	    echo 'El mensaje no tiene adjuntos.';
	}
	*/


	// Obtener el remitente del mensaje
	$from = $message->getFrom();

	// Obtener la dirección de correo electrónico del remitente
	echo "<br>Remitente:" . $remitente = $from->getEmailAddress()->getAddress();

	echo "<br>subject : ". $message->getSubject() . "<br>";
	echo "body : " . $message->getBody()->getContent(); echo "<br>";

	//echo "toRecipients: "; var_dump($toRecipients);
	foreach ($toRecipients as $recipient) {
	    //echo "<br>   recipient:"; var_dump($recipient);
	    foreach ($recipient as $correo) {
		    //echo "<br>_____correo:"; var_dump($correo);
		    echo "<br>_____name:" . $correo["name"];
		    echo "<br>_____address:" . $correo["address"];
		}
	}

	foreach ($ccRecipients as $recipient) {
	    //echo "<br>   recipient:"; var_dump($recipient);
	    foreach ($recipient as $correo) {
		    //echo "<br>_____correo:"; var_dump($correo);
		    echo "<br>_____name:" . $correo["name"];
		    echo "<br>_____remitente:" . $correo["address"];
		}
	}

	foreach ($bccRecipients as $recipient) {
	    //echo "<br>   recipient:"; var_dump($recipient);
	    foreach ($recipient as $correo) {
		    //echo "<br>_____correo:"; var_dump($correo);
		    echo "<br>_____name:" . $correo["name"];
		    echo "<br>_____remitente:" . $correo["address"];
		}
	}

	/*
	echo "<br>adjuntos: " . $attachments;

	// Descargar cada archivo adjunto
	foreach ($attachments as $attachment) {
		echo "entra <br>"; 
	    // Verificar que el adjunto es un archivo
	    if ($attachment->getContentType() == 'file') {
	        // Obtener el contenido del adjunto y decodificarlo
	        $attachmentContent = base64_decode($attachment->getContentBytes());
	        
	        // Guardar el archivo en el disco
	        file_put_contents($attachment->getName(), $attachmentContent);
	    }
	}
	
	// Procesar los adjuntos (si los hay)
	if (!empty($attachments)) {
		echo "entra <br>"; 
	    foreach ($attachments as $attachment) {
	        $attachmentName = $attachment->getName();
	        $attachmentType = $attachment->getContentType();
	        $attachmentSize = $attachment->getSize();
	        $attachmentContent = $attachment->getContent();
	        
	        $attachmentName .= $attachmentName . "." . $attachmentType;
	        echo "nombre archivo : " . $attachmentName; echo "<br>";
	        // Hacer algo con el archivo adjunto (por ejemplo, guardarlo en el disco)
	        echo "estado guardado: " . file_put_contents($attachmentName, $attachmentContent);
	    }
	}
	*/
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



