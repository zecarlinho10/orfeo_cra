<?php
//error_reporting(0); 
// Require our 3rd party libraries such as Oauth2

error_reporting(E_ALL);
ini_set('display_errors', '1');  

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . "/config.php";
// Include the Microsoft Graph classes
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
 
echo "requestAdapter:" . $requestAdapter;
// THIS SNIPPET IS A PREVIEW FOR THE KIOTA BASED SDK. NON-PRODUCTION USE ONLY
$graphServiceClient = new GraphServiceClient($requestAdapter);

$requestBody = new SendMailPostRequestBody();
$message = new Message();
$message->setSubject('Meet for lunch?');

$messageBody = new ItemBody();
$messageBody->setContentType(new BodyType('text'));

$messageBody->setContent('The new cafeteria is open.');


$message->setBody($messageBody);
$toRecipientsRecipient1 = new Recipient();
$toRecipientsRecipient1EmailAddress = new EmailAddress();
$toRecipientsRecipient1EmailAddress->setAddress('cricaurte@cra.gov.co');


$toRecipientsRecipient1->setEmailAddress($toRecipientsRecipient1EmailAddress);

$toRecipientsArray []= $toRecipientsRecipient1;
$message->setToRecipients($toRecipientsArray);

/*
$ccRecipientsRecipient1 = new Recipient();
$ccRecipientsRecipient1EmailAddress = new EmailAddress();
$ccRecipientsRecipient1EmailAddress->setAddress('carlinhoricaurte@hotmail.com');


$ccRecipientsRecipient1->setEmailAddress($ccRecipientsRecipient1EmailAddress);

$ccRecipientsArray []= $ccRecipientsRecipient1;
$message->setCcRecipients($ccRecipientsArray);
*/


$requestBody->setMessage($message);
$requestBody->setSaveToSentItems(false);



$graphServiceClient->me()->sendMail()->post($requestBody);
?>

