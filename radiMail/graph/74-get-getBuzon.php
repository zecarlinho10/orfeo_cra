<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/config.php";


use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

$graph = new Graph();
$myAccessToken = getAccessToken();
//echo $myAccessToken;
$graph->setAccessToken($myAccessToken);

$messages = $graph->createRequest('GET','/users/81cfdeb1-e5fa-474b-81b6-f22c232a0639/messages')->setReturnType(Model\Message::class)->execute();

foreach ($messages as $message){
	echo "Correo : ". $message->getSubject() . "\n";
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
 //echo ($accessToken);A
 return $accessToken;

}



