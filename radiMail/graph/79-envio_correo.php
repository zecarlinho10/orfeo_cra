<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');  

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/config.php";


use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Microsoft\Graph\Http\GraphRequest;
use Microsoft\Graph\Authentication\OAuth2;

$clientId = CLIENT_ID;
//$requestUrl = 'client_id=' . $clientId . '&scope=https%3A%2F%2Fgraph.microsoft.com%2F.default&client_secret=' . $clientSecret . '&grant_type=client_credentials';
$requestUrl = '/users/81cfdeb1-e5fa-474b-81b6-f22c232a0639/sendMail';
$clientSecret = CLIENT_SECRET;
$redirectUri = 'https://localhost/oauth.php';
$tenantId = TENANT_ID;
$username = 'correo@cra.gov.co';
$password = 'K0rr3o2018+-';
$to = 'cricaurte@cra.gov.co';
$subject = 'Test email';
$body = 'This is a test email.';
$graph = new Graph();
echo $myAccessToken = getAccessToken();
$graph->setAccessToken($myAccessToken);





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

