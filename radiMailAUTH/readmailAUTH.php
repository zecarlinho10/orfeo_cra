<?php
#error_reporting(E_ALL);
#error_reporting(0);
#ini_set('display_errors', '1');
#ini_set('display_errors', 0);

require_once("/datos/vhosts/htdocs/orfeo/config.php");
$ruta_raiz=ABSOL_PATH; 


if (!function_exists('getallheaders')) {

    /**
     * Get all HTTP header key/values as an associative array for the current request.
     *
     * @return string[string] The HTTP header key/value pairs.
     */
    function getallheaders()
    {
        $headers = array();

        $copy_server = array(
            'CONTENT_TYPE'   => 'Content-Type',
            'CONTENT_LENGTH' => 'Content-Length',
            'CONTENT_MD5'    => 'Content-Md5',
        );

        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $key = substr($key, 5);
                if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
                    $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                    $headers[$key] = $value;
                }
            } elseif (isset($copy_server[$key])) {
                $headers[$copy_server[$key]] = $value;
            }
        }

        if (!isset($headers['Authorization'])) {
            if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
                $basic_pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
                $headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
            } elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
                $headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
            }
        }

        return $headers;
    }

}


session_start();
if (empty($_SESSION['token'])) {
    if (function_exists('mcrypt_create_iv')) {
        $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    } else {
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}
$token = $_SESSION['token'];
/*
foreach ($_GET as $key => $value)  { $_GET[$key] = StringClean($value); }
foreach ($_POST as $key => $value) { $_POST[$key] = StringClean($value); }
*/
$seconds=180;
//ini_set('max_execution_time',300);
ini_set('memory_limit','512M');
set_time_limit(180);

require_once('ReadMail.class.php');
require_once ("$ruta_raiz/session_orfeo.php");
require_once ("$ruta_raiz/class_control/Mensaje.php");
require_once('RadicarMail.class.php');
$ejecucion=(isset($_GET['manual']) && $_GET['manual']==1)?1:0;
//ini_set('display_errors', 1);
//Instanciamos clase radicar mail
$radiMailObj = new RadicarMail($ejecucion);

//Obtenemos listado de correos configurados para realizar proceso de radicacion
//$radiMailObj->getListMailRadica();

function updateHTML($body_tmp,$readMailObj,$radi_nume_radi,$radiMailObj){

    // SE REEMPLAZA fromMail POR EL NUEVO ATRIBUTO TodoDestinos (variable que trae los destinos CC)
    $state_html=$radiMailObj->convertBodytoPDF($body_tmp,$readMailObj->mail->subject, $radi_nume_radi, $readMailObj->mail->date,$readMailObj->TodoDestinos, $readMailObj->mail->attachments, $readMailObj->mail->from);

       return $state_html;
}

//----------------------------//

include_once "leeCorreosAUTH.php";
//echo "\r\n cuenta mensajes:" . count($messages); 
if (!empty($messages)  && count($messages)>0){
    echo "\n cuenta mensajes:" . count($messages); 
}else{
    echo "\n cuenta mensajes: cero o vacia.\n";
}


if( !empty($messages)  && count($messages)>0){
 //echo "\r\n cuenta mensajes:" . count(   $messages);   
 $readMailObj = new ReadMail();

    //Asignamos datos de conexion
    //$readMailObj->host           = trim($mailObj->mail_host);
    $readMailObj->username       = $mailObj->mail_user;
    //$readMailObj->password       = $mailObj->mail_pass;

        try{
            
            $asunto=$subject;
            echo "<br>***********Aqui va el asunto************";  
            echo "<br>" . $asunto;
            echo "<br>*******************";
            $origin=$remitente;
            echo "<br>********Aqui va la verificacion de repitencia*************";
            //var_dump(strlen($eml_str));

            echo "<br>";

                $correoRadicador=$readMailObj->username;
                echo "<br>";                  
                $destinatario = $radiMailObj->getInfoDestinatario($remitente,$remitenteName);
                echo "<br>: remitente:" . $remitente;
                $asu=$asunto;
                echo "<br>destinatario:<br>";
                //var_dump($destinatario);
                $radi_usua_actu = $radiMailObj->getDestinatario(1);
                var_dump($destinatario);
                echo "<br>variables radi_usua_actu: " . $radi_usua_actu;
                echo "<br>variables remitenteName: " . $remitenteName;
                echo "<br>variables cc: " . $cc;
                echo "<br>variables correoRadicador: " . $correoRadicador;

                $data_radicado = $radiMailObj->radicarDocumento($destinatario,$subject,$radi_usua_actu,$remitenteName,$cc,$correoRadicador);
                $codTx=43;           
                
                //echo "<br>****INSERT SQL***:" . $data_radicado ."<br>";

                echo "<br>****HIZO UN RADICADO***:" . $data_radicado[0] ."<br>";

                //var_dump($data_radicado);

                echo "<br>";
                if(count($data_radicado)==2 && $data_radicado[1]=="invalid"){    
                    echo "<br>****ERROR AL INTENTAR GENERAR RADICADO***:";
                }else{
                    
                    $radi_nume_radi=$data_radicado[0];
                                        
                    //$time = time();
                    //echo "<br>" . date("d-m-Y (H:i:s)", $time);
                    
                    $path_eml=$radiMailObj->createEMLFile2($radi_nume_radi,$emlContent);
                    
                    echo "<br>***********ACTUALIZANDO PERSISTENCIA EML*************"; 
                    //usleep(45000);
                       
                    if (!strstr($radi_nume_radi,'Error') && $radi_nume_radi != ''  && $radi_nume_radi != 0){
                        //Grabamos relacion radicado mail creado

                        echo "<br>parametros relacion:" . $radi_nume_radi . "---" . $readMailObj->mail->number. "---1---" . $radi_usua_actu. "---1---" . $remitente. "---" . $asu. "---1---" . $path_eml;
                        //if ($radiMailObj->saveRelationEmailRad($radi_nume_radi,$readMailObj->mail->number,1,$radi_usua_actu,"1",$remitente,$asu,1,$path_eml)){
                            //Imprimimos notificacion
                            echo "ENTRA.....";
                            //$time = time();
                            //echo "<br>" . date("d-m-Y (H:i:s)", $time);
                            
                            //echo $readMailObj->lastNumberMail;
                            //Finalizamos movimientos de correos
                            $state_errors=false;
                            //$state_body=updateHTML($contentHTML,$readMailObj, $radi_nume_radi,$radiMailObj);
                            ///echo "<br>cuenta anexos" . count($readMailObj->mail->attachments);      
                            echo "<br>cuenta anexos:" . count($archivos);     
                            echo "<br>extension:" . $archivos[0]["extension"] . "<br>";
                            //Creamos los anexos del correo         
                            $radiMailObj->update_annex=false;   

                            
                            //Imprimimos mensaje de exito de los anexos creados correctamente
                    
                            $radiMailObj->dateMail=$readMailObj->mail->date;   

                            echo "<br>createImageRadi:" . $radi_nume_radi . "---" . $body . "---" . $subject . "---" . $remitente . "---" . $body;
                            $radiMailObj->createImageRadi($radi_nume_radi,$body,$subject,"RADICADOR WEB",$remitente, count($archivos)); 
                            echo "<br>lo crea...";
                            
                            echo "<br>todos los destinos:" . $readMailObj->TodoDestinos;
                            $mailDestino=$readMailObj->TodoDestinos;
                            $asuntoMailRadicado="Radicado N° $radi_nume_radi CRA";
                            
                            include "cuerpoCorreo.php";
                            $cuerpo = getCuerpo($radi_nume_radi,$radiMailObj->getDigitoVerificion($radi_nume_radi));
                            //echo "<br>cuerpo: " . $cuerpo;
                            //$passwordRadicador=$readMailObj->password;
                            $radicadosSelText=$radi_nume_radi;
                            echo "<br>****envia correo***"; 
                            include("envios/notificaAUTH.php");
                            
                            echo "<br>cuena anexos funcion:" . $annex = $radiMailObj->createAttachmentsAUTH($radi_nume_radi,$archivos);
                            include("moverCorreo.php");

                        //}
                    }
                }
//////////////////////////////////
            ///}                 
        }catch(Exception $e){
            ///continue;
            echo $e;
        }
    //}                               

    echo "<br>";
    unset($radiMailObj);
    $time = time();
    echo "<br>date:" . date("d-m-Y (H:i:s)", $time);
  
    
    unset($readMailObj);   
   // break;
//}
echo "<br>*****SALIDAD DE LA ITERACION*********";
ini_set('memory_limit','512M');
exit(); 
}
?>
