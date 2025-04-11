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
 
$guzzle = new \GuzzleHttp\Client();
$url = 'https://login.microsoftonline.com/' . TENANT_ID . '/oauth2/token?api-version=1.0';
$token = json_decode($guzzle->post($url, [
    'form_params' => [
        'client_id' => CLIENT_ID,
        'client_secret' => CLIENT_SECRET,
        'resource' => 'https://graph.microsoft.com/',
        'grant_type' => 'client_credentials',
    ],
])->getBody()->getContents());
echo $accessToken = $token->access_token;

// Create a new Graph client.
$graph = new Graph();
$graph->setAccessToken($accessToken);

// Make a call to /me Graph resource.
$user = $graph->createRequest("GET", "/users/". USER )
              ->setReturnType(Model\User::class)
	      ->execute();

echo "Hello, I am {$user->getGivenName()}.";
echo "\n";
/*
$email = "cricaurte@cra.gov.co";
$name = "Carlis";	
*/
$mailBody= array('subject' => 'Test message',
    'replyTo' => array('name' => 'Carlos Enrique Ricaurte Pardo', 'address' => 'cricaurte@cra.gov.co'),
    'toRecipients' => array( 
        //array('name' => 'carlinho', 'address' => 'carlinhoricaurte@hotmail.com'),
        //array('name' => 'cotes', 'address' => 'ccotes@cra.gov.co')
    ),     // name is optional
    'ccRecipients' => array(),     // name is optional, otherwise array of address=>email@address
    'importance' => 'normal',
    'body' => '<html>Blah blah blah</html>',
    'images' => array(),   //array of arrays so you can have multiple images. These are inline images. Everything else in attachments.
    'attachments' => array()
    );


$graph->createRequest("POST", "/me/sendMail");
$graph->createRequest("POST",  "/users/". USER ."/sendMail");
$graph->attachBody($mailBody);
$graph->execute();

//$graph->sendMail('correo@cra.gov.co', $mailBody);


/*
$mailBody = array( "Message" => array(
                   "subject" => "Test Email",
                   "body" => array(
                       "contentType" => "html",
                       "content" =>  "este es una copia de correo"
                   ),
                 "sender" => array(
                     "emailAddress" => array(
                         "name" => $name,
                         "address" => $email
                     )
                 ),
                 "from" => array(
                     "emailAddress" => array(
                             "name" => $name,
                             "address" => $email
                     )
                 ),
                 "toRecipients" => array(
                     array(
                       "emailAddress" => array(
                           "name" => $name,
                           "address" => $email

                  	)
                 )
              )
          )
   );

//$graph->createRequest("POST", "/me/sendMail")
$graph->createRequest("POST",  "/users/". USER ."/sendMail")
	  ->attachBody($mailBody)
	  ->execute();

*/
?>

<?php
	//	'https://graph.microsoft.com/v1.0/users/' . USER . '/messages',
?>

