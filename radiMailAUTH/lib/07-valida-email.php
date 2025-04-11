<?php
require_once __DIR__ . "/vendor/autoload.php";
#require_once __DIR__ . "/config.php";


$CLIENT_ID='04169537-5af9-4e8b-913c-b2576739fec6';
$TENANT_ID='0f25ac5d-820e-433e-8460-6c0c6dd4e7e8';
$CLIENT_SECRET='pBX8Q~pH4NZvP~Gct4sCQpBQRXyXrA1oRi3fAb8m';


use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

$graph = new Graph();
$myAccessToken = getAccessToken();
echo $myAccessToken;
$graph->setAccessToken($myAccessToken);

/*
 "/me". Utilice /users/{userId} en su lugar.
*/

#$messages = $graph->createRequest('GET','/me/messages')->setReturnType(Model\Message::class)->execute();
$messages = $graph->createRequest('GET','/users/81cfdeb1-e5fa-474b-81b6-f22c232a0639/messages')->setReturnType(Model\Message::class)->execute();

foreach ($messages as $message){
	echo $message->getSubject() . "\n";
}

function getAccessToken()
{
	
$CLIENT_ID='04169537-5af9-4e8b-913c-b2576739fec6';
$TENANT_ID='0f25ac5d-820e-433e-8460-6c0c6dd4e7e8';
$CLIENT_SECRET='pBX8Q~pH4NZvP~Gct4sCQpBQRXyXrA1oRi3fAb8m';
	
	
 $tenantId = $TENANT_ID;
 $guzzle = new \GuzzleHttp\Client();
 $url = 'https://login.microsoftonline.com/' . $tenantId .'/oauth2/v2.0/token';
 $token = json_decode($guzzle->post($url,[
	 'form_params' => [
		 'client_id' => $CLIENT_ID,
		 'client_secret' => $CLIENT_SECRET,
		 'scope' => 'https://graph.microsoft.com/.default',
		 'grant_type' => 'client_credentials',
		],
	])->getBody()->getContents());
 $accessToken = $token->access_token;
 //echo ($accessToken);A
 return $accessToken;

}



?>
