<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');  

require_once 'vendor/autoload.php'; 
require_once("config.php");


use Office365\GraphServiceClient;
use Office365\Outlook\Message;
use Office365\Outlook\ItemBody;
use Office365\Outlook\BodyType;
use Office365\Outlook\EmailAddress;
use Office365\Runtime\Auth\AADTokenProvider;
use Office365\Runtime\Auth\UserCredentials;

function acquireToken()
{
   //$tenant = "{tenant}.onmicrosoft.com";
   $resource = "https://graph.microsoft.com";

   $username = 'correo@cra.gov.co';
   $password = 'K0rr3o2018+-';
   //$mytenandId = TENANT_ID.".onmicrosoft.com";
   
   $provider = new AADTokenProvider(TENANT_ID) ;
   return $provider->acquireTokenForPassword($resource, CLIENT_ID,
       new UserCredentials($username, $password));
}

$client = new GraphServiceClient("acquireToken");
/** @var Message $message */
$message = $client->getMe()->getMessages()->createType();
$message->setSubject("Meet for lunch?");
$message->setBody(new ItemBody(BodyType::Text,"The new cafeteria is open."));
$message->setToRecipients([new EmailAddress(null,"ccotes@cra.gov.co")]);
$client->getMe()->sendEmail($message,true)->executeQuery();

?>
