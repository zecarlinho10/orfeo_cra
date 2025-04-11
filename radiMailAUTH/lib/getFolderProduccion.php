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
$graph->setAccessToken($myAccessToken);

$messages = $graph->createRequest('GET','/users/15c4711a-1b42-4cf7-9d67-5f49dab1d8aa/mailfolders/inbox/messages')->setReturnType(Model\Message::class)->execute();

foreach ($messages as $message){

    $messageId = $message->getId();

    echo "<br>messageId:" . $messageId;

    $folders = $graph->createRequest("GET", "/users/15c4711a-1b42-4cf7-9d67-5f49dab1d8aa/mailFolders")
    ->setReturnType(Model\MailFolder::class)
    ->execute();
    foreach ($folders as $folder) {
            $newFolderId = $folder->getId();

            echo "<br>folder:" . $folder->getDisplayName() . "    ID: " . $newFolderId;
            //break;
    }

    break;
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



