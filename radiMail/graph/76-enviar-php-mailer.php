<?php

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/config.php";
#require __DIR__ . "/PHPMailer-master/src/PHPMailer.php"
#require __DIR__ . "/PHPMailer-master/src/SMTP.php"
require "/datos/vhosts/htdocs/orfeo/radiMail/graph/PHPMailer-master/src/PHPMailer.php";
require "/datos/vhosts/htdocs/orfeo/radiMail/graph/PHPMailer-master/src/SMTP.php";
require "/datos/vhosts/htdocs/orfeo/radiMail/graph/PHPMailer-master/src/Exception.php";

#use PHPMailer\PHPMailer\PHPMailer;
#use PHPMailer\PHPMailer\SMTP;

//$mail = new PHPMailer();
$mail = new PHPMailer\PHPMailer\PHPMailer(true);

// Set the mailer to use SMTP
$mail->isSMTP();

$mail->SMTPDebug = 1;

// Set the SMTP server
$mail->Host = 'smtp.office365.com';

// Enable SMTP authentication
$mail->SMTPAuth = true;

// Set the SMTP authentication type (Microsoft OAuth 2.0)
$mail->AuthType = 'XOAUTH2';

// Set the OAuth 2.0 access token
$mail->oauthUserEmail = 'correo@cra.gov.co';
$mail->oauthClientId = CLIENT_ID;

$mail->oauthClientSecret = CLIENT_SECRET;
$myAccessToken = getAccessToken();
#echo ("token:\n");
#echo ($myAccessToken);
#exit();

$mail->oauthRefreshToken = $myAccessToken;

// Set the email parameters
$mail->setFrom('correo@correo.cra.gov.co', 'correo@cra.gov.co');
$mail->addAddress('ccotes@cra.gov.co', 'Carlo Cotes');
$mail->Subject = 'Email subject';
$mail->Body = 'Email body';

// Send the email
$mail->send();
//echo $message->getSubject() . "\n";


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

?>


