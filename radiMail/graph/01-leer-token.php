<?php
require_once __DIR__ . '/vendor/autoload.php';

use Microsoft\Graph\Graph;
use Microsoft\Graph\Authentication\OAuth2;

$clientId = '04169537-5af9-4e8b-913c-b2576739fec6i';
$clientSecret = 'pBX8Q~pH4NZvP~Gct4sCQpBQRXyXrA1oRi3fAb8m';
$redirectUri = 'http://localhost/';
$scopes = array('https://graph.microsoft.com/.default');
$authorityUrl = 'https://login.microsoftonline.com/common';

$oauth2 = new OAuth2($clientId, $clientSecret, $authorityUrl, $redirectUri);
$accessToken = $oauth2->getAccessToken($scopes)->getToken();

?>
