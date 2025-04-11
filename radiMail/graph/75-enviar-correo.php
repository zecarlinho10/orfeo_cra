<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/config.php";


use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

$clientId = CLIENT_ID;
$clientSecret = CLIENT_SECRET;
$redirectUri = 'https://localhost/oauth.php';
$tenantId = TENANT_ID;
$username = 'correo@cra.gov.co';
$password = 'K0rr3o2018+-';
$to = 'ccotes@cra.gov.co';
$subject = 'Test email';
$body = 'This is a test email.';
$graph = new Graph();
$myAccessToken = getAccessToken();
$graph->setAccessToken($myAccessToken);

// Obtener un token de acceso
//$msalClient = new \Microsoft\Graph\OAuth2\Azure\MSALApiClient($clientId, $clientSecret, $redirectUri, $tenantId);
//$accessToken = $msalClient->getAccessToken(['user.read', 'mail.send']);

// Configurar la solicitud de correo electrónico
$graph = new Graph();
$graph->setAccessToken($myAccessToken);
$mail = new Model\Message();

$mail->setSubject($subject);
$mail->setBody(new Model\ItemBody(array('content' => $body)));
$mail->setToRecipients(array(new Model\Recipient(array('emailAddress' => array('address' => $to)))));


// Enviar el correo electrónico
/*
$request = $graph->createRequest("POST", "/me/sendMail")
    ->attachBody($message)
    ->execute();
 *
 *
 */
$graph->createRequest("POST", "/users/81cfdeb1-e5fa-474b-81b6-f22c232a0639/sendMail")
//$graph->createRequest("POST", "/users/81cfdeb1-e5fa-474b-81b6-f22c232a0639/")
    ->attachBody($mail)
    ->execute();

echo 'Correo electrónico enviado';


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
 //echo ($accessToken);A
 return $accessToken;

}

