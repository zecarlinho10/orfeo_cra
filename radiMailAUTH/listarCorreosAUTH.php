<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

session_start();

$ruta_raiz = "..";
if (! $_SESSION['dependencia'])
    header("Location: $ruta_raiz/cerrar_session.php");


require_once "lib/vendor/autoload.php";
require_once "lib/configCRA.php";


use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Microsoft\Graph\Exception\ClientException;

$graph = new Graph();
$myAccessToken = getAccessToken();
$graph->setAccessToken($myAccessToken);

$orderBy = 'sentDateTime asc';

//$messages = $graph->createRequest('GET','/users/'.USER_ID.'/mailfolders/inbox/messages?$orderby=' . urlencode($orderBy))->setReturnType(Model\Message::class)->execute();

$messages = $graph->createRequest('GET','/users/'.USER_ID.'/mailfolders/'.PARA_RADICAR.'/messages?$orderby=' . urlencode($orderBy))
				  ->setReturnType(Model\Message::class)
				  ->execute();

$archivos = array();
$conCopia = array();
$to="";
$toCC="";

?>

<!DOCTYPE html>
<html>
<head>
  <title>Mi página</title>
  <link rel="stylesheet" href="./lib/styles.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">   
  

</head>
<body>

  <table>
    <thead>
      <tr>
        <th>Remitente</th>
        <th>Asunto</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($messages as $message) {
        // Obtener el remitente del mensaje
        $from = $message->getFrom();
        $subject = $message->getSubject();
        $body = $message->getBody()->getContent();

        $remitenteName = $from->getEmailAddress()->getName();
        $remitenteEmail = $from->getEmailAddress()->getAddress();

        $messageId = $message->getId();
        $mensajeActual = $message;

        date_default_timezone_set('America/Bogota'); // Establecer la zona horaria deseada
        $email_date = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $message->getReceivedDateTime()->format('Y-m-d\TH:i:s\Z')); // Crear el objeto DateTime con el formato deseado
        $email_date_formatted = $email_date->format('r'); // Formatear la fecha y hora
        $fechaTmp = new DateTime($email_date_formatted);
        $interval = new DateInterval('PT5H');
        $modifiedDateTime = $fechaTmp->sub($interval);
        $email_date2 = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $modifiedDateTime->format('Y-m-d\TH:i:s\Z')); // Crear el objeto 
        $fechaHoraCorreo = $email_date2->format('r');
        ?>
        <tr data-message-id="<?php echo $message->getId(); ?>">
          <td><?php echo $remitenteEmail; ?></td>
          <td><?php echo $subject; ?></td>
          <td><?php echo $fechaHoraCorreo; ?></td>
        </tr>
      <?php
      }
      ?>
    </tbody>
  </table>
  <div id="modal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <p id="email-body"></p>
    </div>
  </div>

  <div id="loading" style="display: none; position: fixed; top: 50%; left: 50%; 
       transform: translate(-50%, -50%); background-color: rgba(0, 0, 0, 0.5); 
       color: white; padding: 20px; border-radius: 5px;">
       Procesando...
  </div>
    <script>
      function verificarVentanasEmergentes() {
        let ventanaPrueba = window.open('', '_blank'); // Intenta abrir una ventana emergente en blanco

        if (ventanaPrueba) {
          return true;
        } else {
          alert('Las ventanas emergentes están bloqueadas.');
          return false;
        }
      }

      verificarVentanasEmergentes();

      $(document).ready(function() {
        $('tbody tr').click(function(event) {
          event.preventDefault();

          var messageId = $(this).data('messageId');

          if (confirmation()) {
              $("#loading").show(); // Muestra el indicador de carga
              $.ajax({
                  url: 'radicadorMail.php',
                  type: 'POST',
                  data: { messageId: messageId },
                  dataType: 'json', // Especifica que esperas una respuesta JSON
                  success: function(response) {
                      $("#loading").hide(); // Oculta el indicador de carga
                      if (response.resultado) { // Verifica si la respuesta contiene un 'resultado'
                          //alert(response.resultado); // Muestra el mensaje de éxito
                          var mensaje = response.resultado;
                          alert(mensaje + "\nRemitente:" + "<?php echo $remitenteEmail; ?>")
                          //$remitenteEmail
                          location.reload(); // Recarga la página
                      } else if (response.error) { // Verifica si la respuesta contiene un 'error'
                          alert(response.error); // Muestra el mensaje de error
                      } else {
                        alert("Respuesta inesperada del servidor"); // Maneja otras posibles respuestas
                        console.error("Respuesta del servidor:", response); // Imprime la respuesta completa en la consola para depuración
                      }
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                      $("#loading").hide(); // Oculta el indicador de carga (incluso en caso de error)
                      alert("Ocurrió un error al procesar la solicitud: " + textStatus + " - " + errorThrown); //Muestra detalles del error
                      console.error("Error en la solicitud AJAX:", textStatus, errorThrown, jqXHR); // Imprime detalles en la consola para depuración
                  },
                  complete: function(){
                      $("#loading").hide(); // Oculta el indicador de carga (independientemente si hubo error o exito)
                  }
              });
          } 
          
        });
      });

      function confirmation() {
        if(confirm("Confirma la radicacion del correo?")){
            return true;
        }
        return false;
      }
    </script>
</body>
</html>

    <?php
    /*
    // Procesamiento de los datos del formulario (si es necesario)
    foreach ($messages as $message) {
            $subject = $message->getSubject();
			$body = $message->getBody()->getContent();
			
			// Obtener el remitente del mensaje
			$from = $message->getFrom();
			$remitenteName = $from->getEmailAddress()->getName();
			$remitenteEmail = $from->getEmailAddress()->getAddress();

			$messageId = $message->getId();
			$mensajeActual = $message;
			 

		// Obtener la dirección de correo electrónico del remitente
			$remitente = $from->getEmailAddress()->getAddress();
			$toRecipients = $message->getToRecipients();
			$ccRecipients = $message->getCcRecipients();
			$bccRecipients = $message->getBccRecipients();
			
			$fromHTHML = $remitente . ";";
			$conCopia[0]["correo"]=$remitenteEmail;
			$conCopia[0]["nombre"]=$remitenteName;
            $archivos = []; // Modifica según la variable que almacena los archivos            
    }
    */
    ?>
</body>
</html>

   
<?

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
