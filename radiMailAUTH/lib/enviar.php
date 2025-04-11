<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'vendor/autoload.php';
require_once __DIR__ . "/config.php";

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

// Autenticación
$accessToken = getAccessToken();

$clientId = CLIENT_ID;
$clientSecret = CLIENT_SECRET;
$tenantId = TENANT_ID;


$graph = new Graph();
$graph->setAccessToken($accessToken);

// Crear el cuerpo del correo electrónico
$message = new Model\Message();
$message->setSubject('Asunto del correo electrónico');
$message->setToRecipients([
    new Model\Recipient([
        'emailAddress' => new Model\EmailAddress([
            'address' => 'carlinhoricaurte@hotmail.com'
        ])
    ])
]);
$message->setBody(new Model\ItemBody([
    'contentType' => 'HTML',
    'content' => '<p>Contenido del correo electrónico</p>'
]));

// Enviar el correo electrónico
$graph->createRequest('POST', '/users/d5ee96dc-d696-4f3e-9fd5-8a2f2c910b91/sendMail')
    ->attachBody($message)
    ->execute();

echo 'Correo electrónico enviado correctamente';

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