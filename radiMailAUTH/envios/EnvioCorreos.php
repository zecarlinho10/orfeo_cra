<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/configCRA.php';

use Office365\GraphServiceClient;
use Office365\Outlook\Message;
use Office365\Outlook\ItemBody;
use Office365\Outlook\BodyType;
use Office365\Outlook\EmailAddress;
use Office365\Runtime\Auth\AADTokenProvider;
use Office365\Runtime\Auth\UserCredentials;

class EnvioCorreos {
  private $client;

  public function __construct() {
    try {
        // Pasar el método acquireToken como un callable
        $this->client = new GraphServiceClient([$this, 'acquireToken']);
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
  }

  public function enviarCorreo(array $destinatarios, string $asunto, string $cuerpo) {
    foreach ($destinatarios as $mail) {
      $message = $this->client->getMe()->getMessages()->createType();
      $message->setSubject($asunto);
      $message->setBody(new ItemBody(BodyType::HTML, $cuerpo));

      $emailAdress = new EmailAddress(null, $mail);
      $message->setToRecipients(array($emailAdress));

      try {
        $this->client->getMe()->sendEmail($message, true)->executeQuery();
        echo "Correo enviado a $mail correctamente.\n";
      } catch (Exception $e) {
        error_log("Error al enviar correo a $mail: " . $e->getMessage());
      }
    }
  }

  // Método acquireToken, pasado como callable
  public function acquireToken() {
    $username = USUARIO;
    $password = PASSWORD;

    $resource = "https://graph.microsoft.com";

    $provider = new AADTokenProvider(TENANT_ID);
    return $provider->acquireTokenForPassword($resource, CLIENT_ID,
      new UserCredentials($username, $password));
  }
}