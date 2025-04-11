<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "../seguridad_sql.php";
foreach ($_GET as $key => $value)  { $_GET[$key] = StringClean($value); }
foreach ($_POST as $key => $value) { $_POST[$key] = StringClean($value); }

$seconds=180;
//ini_set('max_execution_time',300);
ini_set('memory_limit','512M');
set_time_limit(180);

require_once('ReadMail.class.php');
require_once('RadicarMail.class.php');
$ejecucion=(isset($_GET['manual']) && $_GET['manual']==1)?1:0;

//Instanciamos clase radicar mail
$radiMailObj = new RadicarMail($ejecucion);

//Obtenemos listado de correos configurados para realizar proceso de radicacion
$radiMailObj->getListMailRadica();

///***************************Aqui va la parte que 
function sendMailNotification($mail, $codigo,$password){
                        $mailDestino=$mail;
                        $asuntoMailRadicado="Radicado N° $radi_nume_radi Supertransporte";
                        $codTx=$codigo;
                        $passwordRadicador=$password;
                        $radicadosSelText=$radi_nume_radi;
                        include("../include/mail/GENERAL.mailInformar.php");
}
function saveBody($readMailObj, $radiMailObj){

                                  $bodyHtml=$readMailObj->loadMiMeBody();
//echo "CUERPO PREVIO MODIFICADO";
                                   $body_tmp=$readMailObj->getBody();

                                                                //Actualiza el cuerpo del correo como anexo//
                                 $state_html=false;                              
                                 if(strlen($body_tmp)>0){


                                     $state_html=true;
                                }                              
//unlink('cuerpo_mail.html');
return [$state_html, $body_tmp];

}

function updateHTML($body_tmp,$readMailObj,$radi_nume_radi,$radiMailObj){


      $state_html=$radiMailObj->convertBodytoPDF($body_tmp,$readMailObj->mail->subject, $radi_nume_radi, $readMailObj->mail->date,$readMailObj->mail->fromMail, $readMailObj->mail->attachments, $readMailObj->mail->from);

       return $state_html;
}


function updateData($radiMailObj,$radi_nume_radi,$state,$readMailObj){
   if(intval($state)==1 && count($readMailObj->mail->attachments)>0){
      $radiMailObj->update_annex=true; 
      $annex = $radiMailObj->createAttachments($radi_nume_radi,$readMailObj->mail->attachments,"");
      if (count($annex['sucess'])>0)
      {
          echo "-Para el radicado {$radi_nume_radi} se crearon correctamente los anexos ".implode(',',$annex['sucess'])."\n";
             // $state=;
    }

      //Imprimimos mensaje de error de los anexos que no se pudieron crear
      if (count($annex['fail'])>0)
      {
          echo "-Fallo para el radicado {$radi_nume_radi} al crear los anexos ".implode(',',$annex['fail'])." \n";
             // $state_errors=true; 
      }
 
    }else{

         $state=2;
    }
   if(count($annex['sucess'])>0){

      $state=2;
   };
  return intval($state);
}


include __DIR__.'/vendor/autoload.php';

$CLIENT_ID="8a6ea2bd-acd7-4b66-b2cb-8db87eff0378";
$CLIENT_SECRET="m8w8Q~BTXlj3K37WUNAM7jHE-wfxtlYZxw5EdbDd";
$TENANT="02f338c2-5dfa-4ce9-9ed1-2e6f5524cc75";
// refresh token de bentanilla
$REFRESH_TOKEN="0.AVkAwjjzAvpd6Uye0S5vVSTMdb2iborXrGZLssuNuH7_A3hZAD4.AgABAAEAAAD--DLA3VO7QrddgJg7WevrAgDs_wUA9P-5TaKeE6eVODrwyVGENjDy6c2KCgCmVwwhCyUtcIiZS7S7v4jG9PTnlP5sf2cBFEC9B85vju0y-gFg9yUEUcj4GdejIC0cE3fStLveFyHUe4ZcCUTSCp1d-2Q8zU66LSXZbeSWx2XCZwJeHUfgy96sjHX79pb1_1nID51dqcd0SqliUwf-48zKBOhpdCbAElz1TWEhzzDbxTTlHgEYm6OWf_K4ZcxmjK1lIchWpDf0lQRYy388Kv6BmVmdfKDbPQuoJsRLLL68RFgGTIoxUfzlckskNPbDf-2OdYf06gmdaDn1JW76Z0mTXQjAuB35sxE2RaMrPpThQBLXJOAbBDpYiim8s4se1wCRxmESF3p8xXmT2vslUMhG6dIaJETW3Fn-S-qb-DEObzZMhowLV7VFxjIUKGhY7pA04bL6XmERdvIPRGg9C73sEBXqxyz7P8EHQCNPEHPF5k03CuBEzQYlFtJwmZt6M6f3-1pJXyN5xI9AOiK4VOO7CpGag-D33wq0PtyNFMugjP11fixIuAXol4uyYkqVKQQ53FWs_mKCNXvxADvK40RpKk3XposH9O-JlcL1_xXwLlFEnmHRrEzNoP-8AnQlDYzM8seEPkaiS6Uqt0feUPE-u7ntm8Aasxc7VTZ49rShztLq26Sl2mOM8b9u5tGV2k5rZp2p64pQCKqb3Li0eoPsbWRzQm6Obq13cOkgVlAlKqnUyQ1sQt2SND1Ksb9bTh35FgP-WbX7ewwsg_dgYcF8";

$url= "https://login.microsoftonline.com/$TENANT/oauth2/v2.0/token";

$param_post_curl = [ 
 'client_id'=>$CLIENT_ID,
 'client_secret'=>$CLIENT_SECRET,
 'refresh_token'=>$REFRESH_TOKEN,
 'grant_type'=>'refresh_token' ];

$ch=curl_init();

curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($param_post_curl));
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
//ONLY USE CURLOPT_SSL_VERIFYPEER AT FALSE IF YOU ARE IN LOCALHOST !!!
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);// NOT IN LOCALHOST ? ERASE IT !

$oResult=curl_exec($ch);

echo("Trying to get the token.... \n");

if(!empty($oResult)){
    
    echo("Connecting to the mail box... \n");
    
    //The token is a JSON object
    $array_php_resul = json_decode($oResult,true);
    $access_token = $array_php_resul["access_token"];
    if( !empty($access_token) ){

        $mailbox = '{outlook.office365.com:993/imap/ssl}';
        $username = 'bentanillaunicaderadicacion@supertransporte.gov.co';

        $inbox = imap2_open($mailbox, $username, $access_token, OP_XOAUTH2);

        if (! $inbox) {
            error_log(imap2_last_error());
            echo("Unable to open the INBOX \n");
        }
        else {
            echo 'Conectado !!! ';

            $emails = imap2_search($inbox,'ALL');

            //var_dump($emails);

            
            if($emails) {
            
                $salida = '';
                
                foreach($emails as $email_number) {    
                    $readMailObj = new ReadMail();
                    $mailObj=$email_number;
                    $overview = imap2_fetch_overview($inbox,$email_number,0);
                	//var_dump($overview);
                    $salida.= '<p>'.$email_number.' - Tema: '.$overview[0]->subject;
                    $salida.= ' - De: '.$overview[0]->from;	 
                    $salida.= '</p>';
                    if ($email_number > 9) {
                        break;
                    }
                    $penultimoRemitente=$radiMailObj->buscarCorreo($readMailObj->mail->body2,0);
                    if(trim($penultimoRemitente)==''){
                        $penultimoRemitente=$radiMailObj->buscarCorreo($readMailObj->mail->body,1);
                        //metodo 3
                        if(trim($penultimoRemitente)==''){
                            $penultimoRemitente=$radiMailObj->buscarCorreo($readMailObj->mail->body,0);
                        }
                    }
                    $destinatario = $radiMailObj->getInfoDestinatario($readMailObj,$penultimoRemitente);
                    $asu=$readMailObj->mail->subject;
                    $radi_usua_actu = $radiMailObj->getDestinatario($email_number);//$mailObj->mail;                                                      

                    $correoRadicador=$readMailObj->username;
                    var_dump($correoRadicador);
//exit;                 
                                                                               
                    $data_radicado = $radiMailObj->radicarDocumento($destinatario,$asu,$radi_usua_actu,$readMailObj->mail->body,$readMailObj->mail->fromMail,$readMailObj->mail->ccaddress,$correoRadicador);

                    $radiMailObj->dateMail=$readMailObj->mail->date;    
                    $radiMailObj->createImageRadi($data_radicado[0],$data_radicado[1],$data_radicado[2],$readMailObj->mail->from,$data_radicado[4],$data_radicado[5]);
                    
                }
                
                echo $salida;

            }
            

            imap2_close($inbox);

        }
        
    }
    else {
        echo 'No se pudo obtener el Token';
    }
}

?>