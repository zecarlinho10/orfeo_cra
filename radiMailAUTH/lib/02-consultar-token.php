<?php
require_once __DIR__ . '/vendor/autoload.php';

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

$tenantId = 'YOUR_TENANT_ID';
$client_id = 'YOUR_CLIENT_ID';
$client_secret = 'YOUR_CLIENT_SECRET';

$guzzle = new \GuzzleHttp\Client();

$tokenEndpoint = 'https://login.microsoftonline.com/' . $tenantId . '/oauth2/v2.0/token';

$response = $guzzle->post($tokenEndpoint, [
    'form_params' => [
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'scope' => 'https://graph.microsoft.com/.default',
        'grant_type' => 'client_credentials',
    ],
]);

$accessToken = json_decode($response->getBody()->getContents())->access_token;

// Use the token to access the Microsoft Graph API
$graph = new Graph();
$graph->setAccessToken($accessToken);

// Example: Get the user's messages
$messages = $graph->createRequest('GET', '/me/messages')
    ->setReturnType(Model\Message::class)
    ->execute();

foreach ($messages as $message) {
    echo $message->getSubject() . "\n";
}

