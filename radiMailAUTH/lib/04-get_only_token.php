<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/config.php";

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

$graph = new Graph();
$myAccessToken = getAccessToken();
echo "===============================BEGIN=========================================\n";
echo $myAccessToken;
echo "\n================================END===========================================\n";


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



?>
