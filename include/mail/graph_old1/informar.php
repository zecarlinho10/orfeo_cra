<?

//error_reporting(E_ALL);
//ini_set('display_errors', '1');  

require_once './vendor/autoload.php';

use Office365\GraphServiceClient;
use Office365\Outlook\Message;
use Office365\Outlook\ItemBody;
use Office365\Outlook\BodyType;
use Office365\Outlook\EmailAddress;
use Office365\Runtime\Auth\AADTokenProvider;
use Office365\Runtime\Auth\UserCredentials;
    

    function inicio($db, $radicado, $correo)
    {
        $this->db = $db;
        //$this->db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->radicado=$radicado;
        $this->correo=$correo;


    }

    function acquireToken()
    {   
        
        define('CLIENT_ID','04169537-5af9-4e8b-913c-b2576739fec6');
        define('TENANT_ID','0f25ac5d-820e-433e-8460-6c0c6dd4e7e8');
        
        $username = 'correo@cra.gov.co';
        $password = 'K0rr3o2018+-';

        $resource = "https://graph.microsoft.com";

        //$provider = new AADTokenProvider($tenant);
        $provider = new AADTokenProvider(TENANT_ID);
        return $provider->acquireTokenForPassword($resource, CLIENT_ID,
            new UserCredentials($username, $password));
    }

    //var_dump(acquireToken());

    function crearMensaje($asunto, $mensaje, $correo){
        

        $client = new GraphServiceClient("acquireToken");
        /** @var Message $message */

        $message = $client->getMe()->getMessages()->createType();
        $message->setSubject($asunto);
        $message->setBody(new ItemBody(BodyType::Text,$mensaje));
        $message->setToRecipients([new EmailAddress(null,$correo)]);
        $client->getMe()->sendEmail($message,true)->executeQuery();

    }

    function enviarMensaje(){
        //crearMensaje(crearAsunto(), crearBody(), $_email);
        $this->crearMensaje("asunto de prueba", "radicado No 2023321000022 con codigo 0u7y6t", "cricaurte@cra.gov.co");
    }

?>
