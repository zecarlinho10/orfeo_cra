<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

// Recibe el valor messageId enviado desde JavaScript
$messageId = $_POST['messageId'];

require_once "lib/vendor/autoload.php";
require_once "lib/configCRA.php";

require_once('ReadMail.class.php');
require_once ("../class_control/Mensaje.php");
require_once('RadicarMail.class.php');

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Microsoft\Graph\Exception\ClientException;

$graph = new Graph();
$myAccessToken = getAccessToken();
$graph->setAccessToken($myAccessToken);

$orderBy = 'sentDateTime asc';

$messages = $graph->createRequest('GET','/users/'.USER_ID.'/mailfolders/'.PARA_RADICAR.'/messages?$orderby=' . urlencode($orderBy))
                  ->setReturnType(Model\Message::class)
                  ->execute();


$archivos = array();
$conCopia = array();

$to="";
$toCC="";

$emlContent = "";

$encontrado = false; // Variable para indicar si el mensaje fue encontrado

foreach ($messages as $message){

    // Compara el ID del mensaje con el ID buscado
    if ($message->getId() === $messageId) {
        
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
        
        $cc=1;
        
        $contentHTML = "From: " . $remitente . "<br>";
        $contentHTML .= "To: " . "correo@cra.gov.co;" . $to. "<br>";
        $contentHTML .= "Subject: " . $subject . "<br>";
        $contentHTML .= "Body: " . $body . "<br>";

        //CREA EML Y GUARDA ADJUNTOS

        date_default_timezone_set('America/Bogota'); // Establecer la zona horaria deseada
        $email_date = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $message->getReceivedDateTime()->format('Y-m-d\TH:i:s\Z')); // Crear el objeto DateTime con el formato deseado
        $email_date_formatted = $email_date->format('r'); // Formatear la fecha y hora
        $fechaTmp = new DateTime($email_date_formatted);
        $interval = new DateInterval('PT5H');
        $modifiedDateTime = $fechaTmp->sub($interval);
        $email_date2 = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $modifiedDateTime->format('Y-m-d\TH:i:s\Z')); // Crear el objeto 
        $fechaHoraCorreo = $email_date2->format('r');


        $emlContent = "From: " . $remitenteName . " <" . $remitenteEmail . ">\r\n";
        $emlContent .= "To: " . $to . "\r\n";
        $emlContent .= "Cc: " . $toCC . "\r\n";
        $emlContent .= "Subject: " . $message->getSubject() . "\r\n";
        $emlContent .= "Date: " . $fechaHoraCorreo . "\r\n";
        $emlContent .= "MIME-Version: 1.0\r\n";
        $emlContent .= "Content-Type: multipart/related; boundary=boundary1\r\n\r\n";
        $emlContent .= "This is a multi-part message in MIME format.\r\n";

        
        $emlContent .= "--boundary1\r\n";
        $emlContent .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $emlContent .= "<html><body>";
        $emlContent .= "<p>" . $message->getBody()->getContent() . "</p>";
        $emlContent .= "</body></html>\r\n";

        // Adjuntar imágenes en línea
        $message1 = $graph->createRequest('GET', '/users/'.USER_ID.'/messages/' . $message->getId(). '/attachments')
                    ->setReturnType(Model\Attachment::class)
                    ->execute();

        $c=0;
        foreach ($message1 as $attachment) {
            $contenido = $graph->createRequest('GET', '/users/'.USER_ID.'/messages/' . $message->getId() . '/attachments/' . $attachment->getId() . '/$value')->execute();
            $contentString = $contenido->getRawBody();
            
            //var_dump($attachment); 
                $objTMP=$attachment;
                $valor_objeto = var_export($objTMP, true);
                
                $nueva_cadena = str_replace("Microsoft\Graph\Model\Attachment::__set_state(array(", "", $valor_objeto);
                $nueva_cadena = str_replace("'_propDict' =>", "", $nueva_cadena);
                $nueva_cadena = str_replace("array (", "", $nueva_cadena);
                $nueva_cadena = str_replace("),", "", $nueva_cadena);
                $nueva_cadena = str_replace(")", "", $nueva_cadena);
                $nueva_cadena = str_replace(" ", "", $nueva_cadena);
                $nueva_cadena = trim($nueva_cadena);
                $nueva_cadena = substr($nueva_cadena, 0, -1);
                $nueva_cadena = str_replace(array("\r", "\n"), '', $nueva_cadena);
                $nueva_cadena = str_replace("'", "", $nueva_cadena);
                $pares_atributo_valor = explode(",", $nueva_cadena);

                // Creamos un arreglo vacío para guardar los resultados
                $archivo = array();

                // Recorremos los pares atributo-valor y los agregamos al arreglo resultado
                foreach ($pares_atributo_valor as $par) {
                    $atributo_valor = explode("=>", $par);
                    $archivo[$atributo_valor[0]] = $atributo_valor[1];
                }

                if ($archivo["isInline"]=="false") {
                    //EML
                    $imageContent = base64_encode($contentString);
                    //ADJUNTOS
                    $archivos[$c]["contenido"]=$contentString;
                    $archivos[$c]["extension"]=pathinfo($attachment->getName(), PATHINFO_EXTENSION);
                    $archivos[$c]["nombre"]=$attachment->getName();
                    $c=$c+1;
                }
        }

        $emlContent .= "--boundary1--\r\n";


        ///////////////////

        // Reemplazar los valores del atributo src con las rutas absolutas a las imágenes
        $emlContent = preg_replace_callback('/(src=["\'])cid:(.*?)["\']/', function ($matches) use ($graph, $message, $message1) {
            $cid = $matches[2];
            $attachment1 = null;
            foreach ($message1 as $attachment) {
                $contenido = $graph->createRequest('GET', '/users/'.USER_ID.'/messages/' . $message->getId() . '/attachments/' . $attachment->getId() . '/$value')
                             ->execute();
                $contentString = $contenido->getRawBody();
                //var_dump($attachment); 
                $objTMP=$attachment;
                $valor_objeto = var_export($objTMP, true);
                
                $nueva_cadena = str_replace("Microsoft\Graph\Model\Attachment::__set_state(array(", "", $valor_objeto);
                $nueva_cadena = str_replace("'_propDict' =>", "", $nueva_cadena);
                $nueva_cadena = str_replace("array (", "", $nueva_cadena);
                $nueva_cadena = str_replace("),", "", $nueva_cadena);
                $nueva_cadena = str_replace(")", "", $nueva_cadena);
                $nueva_cadena = str_replace(" ", "", $nueva_cadena);
                $nueva_cadena = trim($nueva_cadena);
                $nueva_cadena = substr($nueva_cadena, 0, -1);
                $nueva_cadena = str_replace(array("\r", "\n"), '', $nueva_cadena);
                $nueva_cadena = str_replace("'", "", $nueva_cadena);
                $pares_atributo_valor = explode(",", $nueva_cadena);

                // Creamos un arreglo vacío para guardar los resultados
                $archivo = array();

                // Recorremos los pares atributo-valor y los agregamos al arreglo resultado
                foreach ($pares_atributo_valor as $par) {
                    $atributo_valor = explode("=>", $par);
                    $archivo[$atributo_valor[0]] = $atributo_valor[1];
                }

                if ($archivo["isInline"]=="true") {
                    if ($archivo["contentId"] == $cid) {
                        $attachment1 = $archivo;
                        //echo "\r\n cid: " . $cid;
                        break;
                    }
                }
            }
            if (!$attachment1) {
                //echo "\r\n cid 0: " . $matches[0];
                return $matches[0];
            }

            //$imageContent = $attachment1["contentBytes"];
            $imageContent = $contentString;
            $imageData = base64_encode($imageContent);
            $imageType = $attachment->getContentType();
            //echo "\r\n imageType: " . $imageType;
            return "src=\"data:$imageType;base64,$imageData\"";
        }, $emlContent);
        
        // Agregar el contenido del correo electrónico al archivo EML
        $emlContent .= chunk_split(base64_encode($emlContent));

        $encontrado = true;
        //echo "Mensaje encontrado con ID: " . $mensaje->getId();
        $resultado = "Mensaje encontrado con ID: " . $message->getId();

        break; // Detener el bucle si el mensaje es encontrado
    }
}
$resultado = count($archivos);

if (!$encontrado) {
    $resultado=  "El mensaje con el ID especificado no fue encontrado en el conjunto de mensajes.";
}
else{
    $readMailObj = new ReadMail();
    $ejecucion=(isset($_GET['manual']) && $_GET['manual']==1)?1:0;
    $radiMailObj = new RadicarMail($ejecucion);
    session_start();
    try{
        $asunto=$subject;
        $origin=$remitente;
        $correoRadicador=$readMailObj->username;
        $resultado=$_SESSION["codusuario"];
        
        $destinatario = $radiMailObj->getInfoDestinatario($remitente,$remitenteName);

        $asu=$asunto;
        $radi_usua_actu = $radiMailObj->getDestinatario(1);
        $data_radicado = $radiMailObj->radicarDocumento($destinatario,$subject,$_SESSION["krd"] ,$remitenteName,$_SESSION["usua_doc"],$_SESSION["usua_email"]);
        $codTx=2;           
        $resultado=$data_radicado[0];

        if(count($data_radicado)==2 && $data_radicado[1]=="invalid"){    
            $resultado = "Error al generar radicado";
            //break; // Detener el bucle si el mensaje es encontrado
        }else{
            
            $radi_nume_radi=$data_radicado[0];
            $path_eml=$radiMailObj->createEMLFile2($radi_nume_radi,$emlContent);
            if (!strstr($radi_nume_radi,'Error') && $radi_nume_radi != ''  && $radi_nume_radi != 0){
                //Grabamos relacion radicado mail creado

                $state_errors=false;
                $radiMailObj->update_annex=false;   

                $radiMailObj->dateMail=$readMailObj->mail->date;   
                $radiMailObj->createImageRadi($radi_nume_radi,$body,$subject,$_SESSION["krd"],$remitente, count($archivos)); 
                $mailDestino=$readMailObj->TodoDestinos;
                $asuntoMailRadicado="Radicado N° $radi_nume_radi CRA";
                include "cuerpoCorreo.php";
                $cuerpo = getCuerpo($radi_nume_radi,$radiMailObj->getDigitoVerificion($radi_nume_radi));
                $radicadosSelText=$radi_nume_radi;

                include("envios/notificaAUTH.php");
                $radiMailObj->createAttachmentsAUTH($radi_nume_radi,$archivos);
                include("moverCorreo.php");
                $resultado = $radi_nume_radi;
                
            }
            //$resultado = "" . $path_eml;
            $resultado = "\nRadicado: " . $radi_nume_radi;
        }
        

    }catch(Exception $e){
        $resultado = $e;
    }
    $resultado = array('resultado' => 'Correo radicado exitosamente.'.$resultado); //Simulacion de respuesta
    echo json_encode($resultado);
    die();
}


// Enviamos el resultado como respuesta JSON
header('Content-Type: application/json');
echo json_encode(['resultado' => $resultado]);


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