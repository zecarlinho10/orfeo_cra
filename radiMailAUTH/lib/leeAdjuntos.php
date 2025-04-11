<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/config.php";


use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;


$graph = new Graph();

$myAccessToken = getAccessToken();
//echo $myAccessToken;
$graph->setAccessToken($myAccessToken);

$messages = $graph->createRequest('GET','/users/d5ee96dc-d696-4f3e-9fd5-8a2f2c910b91/mailfolders/inbox/messages')
                  ->setReturnType(Model\Message::class)
                  ->execute();

foreach ($messages as $message){
    $message1 = $graph->createRequest('GET', '/users/d5ee96dc-d696-4f3e-9fd5-8a2f2c910b91/messages/' . $message->getId(). '/attachments')
                ->setReturnType(Model\Attachment::class)
                ->execute();

    //echo "<br> muestra vector:" .  var_dump($message1);
    $attachmentName = "../../bodega/";
    

        foreach ($message1 as $attachment) {
            echo "<br>entra";
            $content = $graph->createRequest('GET', '/users/d5ee96dc-d696-4f3e-9fd5-8a2f2c910b91/messages/' . $message->getId() . '/attachments/' . $attachment->getId() . '/$value')
                            ->execute();

            $contentString = $content->getRawBody();

            // Verificar si el contenido del anexo se ha descargado correctamente
            if ($contentString) {
                // Guardar el archivo en el disco
                file_put_contents($attachmentName.$attachment->getName(), $contentString);
            } else {
                echo 'Error al descargar el anexo.';
            }
        }
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
