<?php

$id_folder_bandeja="AAMkADc5YzAxNTIzLWMwMGUtNDJkMC1hYTIwLWQ0ODE3MGQ4MGM5MAAuAAAAAABYhkKK4gHIS7avtF9IMt6CAQBfHGhAvlUfQYZy9hGHyOddAAAAAAEMAAA=";

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once("vendor/autoload.php");
require_once("config.php");


use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Microsoft\Graph\Exception\ClientException;


//GET https://graph.microsoft.com/v1.0/me/mailFolders/AAMkAGVmMDEzMTM4LTZmYWUtNDdkNC1hMDZiLTU1OGY5OTZhYmY4OAAuAAAAAAAiQ8W967B7TKBjgx9rVEURAQAiIsqMbYjsT5e-T7KzowPTAAAAAAFNAAA=/childFolders?includeHiddenFolders=true

$cadena = "/users/".USER_ID."/mailFolders";
$cadena = "/users/".USER_ID."/mailFolders/" . $id_folder_bandeja . "/childFolders?includeHiddenFolders=true";

$graph = new Graph();
$myAccessToken = getAccessToken();
$graph->setAccessToken($myAccessToken);

$folders = $graph->createRequest("GET", $cadena)
           ->setReturnType(Model\MailFolder::class)
           ->execute();

foreach ($folders as $folder) {
    $newFolderId = $folder->getId();
    
    echo "<br><br>folder:" . $folder->getDisplayName() . "    ID: " . $newFolderId ;
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
 return $token->access_token;
 }


?>
