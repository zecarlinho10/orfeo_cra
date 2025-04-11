<?php

ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/config.php";


use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Microsoft\Graph\Http\GraphRequest;

$clientId = CLIENT_ID;
$clientSecret = CLIENT_SECRET;
$redirectUri = 'https://localhost/oauth.php';
$tenantId = TENANT_ID;
//$username = 'correo@cra.gov.co';
//$password = 'K0rr3o2018+-';
$username = 'vur@supertransporte.gov.co';
$password = 'V3nt4nill4R4d2022*';
$to = 'cricaurte@cra.gov.co';
$subject = 'Test email';
$body = 'This is a test email.';
$graph = new Graph();
echo $myAccessToken = getAccessToken();
$graph->setAccessToken($myAccessToken);


$url = 'https://graph.microsoft.com/v1.0/me/sendMail';

$headers = array(
    "Authorization: Bearer $myAccessToken",
    "Content-Type: application/json"
);

$body = '{
    "message": {
        "subject": "Subject of the email",
        "body": {
            "contentType": "Text",
            "content": "Body of the email"
        },
        "toRecipients": [
            {
                "emailAddress": {
                    "address": "cricaurte@cra.gov.co"
                }
            }
        ]
    },
    "saveToSentItems": "true"
}';


$curl = curl_init($url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);


if (curl_errno($curl)) {
    $error_msg = curl_error($curl);
}

curl_close($curl);

if (isset($error_msg)) {
    echo "Error sending email: $error_msg";
} else {
    echo "Email sent successfully!";
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

