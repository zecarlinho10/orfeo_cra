<?

error_reporting(E_ALL);
ini_set('display_errors', '1');  



require_once 'envios/vendor/autoload.php'; 
//require_once('lib/config.php');

use Office365\GraphServiceClient;
use Office365\Outlook\Message;
use Office365\Outlook\ItemBody;
use Office365\Outlook\BodyType;
use Office365\Outlook\EmailAddress;
use Office365\Runtime\Auth\AADTokenProvider;
use Office365\Runtime\Auth\UserCredentials;

function acquireToken()
{
	//$username = 'correo@cra.gov.co';
    //$password = 'K0rr3o2018+-';

    $username = USUARIO;
    $password = PASSWORD;

    $resource = "https://graph.microsoft.com";

    //$provider = new AADTokenProvider($tenant);
    $provider = new AADTokenProvider(TENANT_ID);
    return $provider->acquireTokenForPassword($resource, CLIENT_ID,
        new UserCredentials($username, $password));
}

//var_dump(acquireToken());

$client = new GraphServiceClient("acquireToken");
/** @var Message $message */

$message = $client->getMe()->getMessages()->createType();
$message->setSubject($asuntoMailRadicado);
$message->setBody(new ItemBody(BodyType::Text,$cuerpo));
$emailAdress = array();
$cEm=0;
$emailAdress[$cEm]=new EmailAddress($remitenteName,$remitenteEmail);

/*
foreach ($conCopia as $mail) {
    $emailAdress[$cEm]=new EmailAddress(null,$mail["correo"]);
    $cEm=$cEm+1;
    //echo "<br>email destino:<" . $mail["correo"] . ">";
}
*/
$message->setToRecipients($emailAdress);

$client->getMe()->sendEmail($message,true)->executeQuery();

?>
