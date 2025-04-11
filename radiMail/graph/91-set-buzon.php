<?php
//error_reporting(0); 
 
 
// Require our 3rd party libraries such as Oauth2
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . "/config.php";
// Include the Microsoft Graph classes
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
 
 
// Set up a new Oauth2 client that will be talking to Azure
$provider = new TheNetworg\OAuth2\Client\Provider\Azure([
    'clientId'                => CLIENT_ID,
    'clientSecret'            => CLIENT_SECRET,
    'redirectUri'             => REDIRECT_URI,
]);
 
// Change the URLs of our Oauth2 request to the correct endpoint and tenant
$provider->urlAPI = "https://graph.microsoft.com/v1.0/";
$provider->resource = "https://graph.microsoft.com/";
$provider->tenant = 'cra.gov.co';

$options = array();

 
// Try to get an access token using the client credentials grant.
$accessToken = $provider->getAccessToken( 'client_credentials', $options );


// Set access token
//$accessToken = 'INSERT_ACCESS_TOKEN_HERE';

// Set endpoint URL
#$url = 'https://graph.microsoft.com/v1.0/me/sendMail';
#$url = 'https://graph.microsoft.com/v1.0/me/sendMail';
$url = 'https://graph.microsoft.com/v1.0/users/' . USER . '/sendMail';

//print($url); 

// Set request headers
$headers = array(
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json'
);

//print ("\n" . $headers[0] . "\n");

// Set email properties in JSON format
$email = array(
    'message' => array(
        'subject' => 'Test Email',
        'body' => array(
            'contentType' => 'HTML',
            'content' => 'This is a test email sent using JSON format.'
        ),
        'toRecipients' => array(
            array(
                'emailAddress' => array(
                    'address' => 'ccotes@cra.gov.co'
                )
            )
        )
    ),
    'saveToSentItems' => 'true'
);

// Send email using Graph API
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($email));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

// Output response
echo "\n";
echo $response;
echo "\n";


?>


 
