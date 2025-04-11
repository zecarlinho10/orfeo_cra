<?php

error_reporting(E_ALL);
ini_set('display_errors', '1'); 

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/config.php";


use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Microsoft\Graph\Http\GraphRequest;

$clientId = CLIENT_ID;
$clientSecret = CLIENT_SECRET;
$redirectUri = 'https://localhost/oauth.php';
$tenantId = TENANT_ID;
$username = 'correo@cra.gov.co';
$password = 'K0rr3o2018+-';
$to = 'cricaurte@cra.gov.co';
$subject = 'Test email';
$body = 'This is a test email.';
$graph = new Graph();
$myAccessToken = getAccessToken();
$graph->setAccessToken($myAccessToken);


// Crea el cuerpo del mensaje de correo electrónico
$message = new Model\Message();
$message->setSubject('Correo electrónico de prueba');
$message->setBody(new Model\ItemBody(array(
    'contentType' => 'HTML',
    'content' => 'Este es un correo electrónico de prueba enviado mediante la API Graph de Office 365.'
)));
$message->setToRecipients(array(
    new Model\Recipient(array(
        'emailAddress' => new Model\EmailAddress(array('address' => 'cricaurte@cra.gov.o'))
    ))
));

$jsonString = json_encode($message->jsonSerialize());

#"/users/81cfdeb1-e5fa-474b-81b6-f22c232a0639/sendMail"

$request = new GraphRequest($myAccessToken,'POST', '/users/81cfdeb1-e5fa-474b-81b6-f22c232a0639/sendMail', array(
    'Content-type' => 'application/json'
),$jsonString );


$response = $graph->executeRequest($request);
if ($response->getStatus() === 202) {
    echo "Message sent successfully.";
} else {
    echo "Error sending message: " . $response->getBody();
}


/*
$accessToken = $myAccessToken;
$requestUrl = '/me/sendMail';
$requestUrl = '/users/81cfdeb1-e5fa-474b-81b6-f22c232a0639/sendMail';
$request = new GraphRequest($accessToken, 'POST', $requestUrl, $jsonString);
$response = $graph->sendRequest($request);

echo 'Correo electrónico enviado exitosamente.';

 */


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

