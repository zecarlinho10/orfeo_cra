<?php
include_once "../seguridad_sql.php";
foreach ($_GET as $key => $value)  { $_GET[$key] = StringClean($value); }
foreach ($_POST as $key => $value) { $_POST[$key] = StringClean($value); }

$seconds=180;
//ini_set('max_execution_time',300);
ini_set('memory_limit','512M');
set_time_limit(180);
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require_once('ReadMail.class.php');
require_once('RadicarMail.class.php');
$ejecucion=(isset($_GET['manual']) && $_GET['manual']==1)?1:0;
//ini_set('display_errors', 1);
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

    // SE REEMPLAZA fromMail POR EL NUEVO ATRIBUTO TodoDestinos (variable que trae los destinos CC)
      //$state_html=$radiMailObj->convertBodytoPDF($body_tmp,$readMailObj->mail->subject, $radi_nume_radi, $readMailObj->mail->date,$readMailObj->mail->fromMail, $readMailObj->mail->attachments, $readMailObj->mail->from);
    $state_html=$radiMailObj->convertBodytoPDF($body_tmp,$readMailObj->mail->subject, $radi_nume_radi, $readMailObj->mail->date,$readMailObj->TodoDestinos, $readMailObj->mail->attachments, $readMailObj->mail->from);

       return $state_html;
}


function updateData($radiMailObj,$radi_nume_radi,$state,$readMailObj){
    if(intval($state)==1 && count($readMailObj->mail->attachments)>0){
        $radiMailObj->update_annex=true; 
        $annex = $radiMailObj->createAttachments($radi_nume_radi,$readMailObj->mail->attachments,"");
        if (count($annex['sucess'])>0){
            echo "<br>-Para el radicado {$radi_nume_radi} se crearon correctamente los anexos ".implode(',',$annex['sucess'])."\n";
                 // $state=;
        }

        //Imprimimos mensaje de error de los anexos que no se pudieron crear
        if (count($annex['fail'])>0){
            echo "<br>-Fallo para el radicado {$radi_nume_radi} al crear los anexos ".implode(',',$annex['fail'])." \n";
            // $state_errors=true; 
        }
 
    }else{
        $state=2;
    }
    if(count($annex['sucess'])>0){
        $state=2;
    }
    return intval($state);
}



//----------------------------//
//Iteramos
foreach($radiMailObj->mailList as $mailObj){
    if(!$mailObj->mail_dest_alt){
        continue;
    }
    //Instanciamos clase
    $readMailObj = new ReadMail();

    //Asignamos datos de conexion
    $readMailObj->host           = trim($mailObj->mail_host);
    $readMailObj->username       = $mailObj->mail_user;
    $readMailObj->password       = $mailObj->mail_pass;
    
        
    $pos = strpos($readMailObj->host, '}');
    $pos++;
    $readMailObj->hostAddres=(substr($readMailObj->host,0,$pos))?substr($readMailObj->host,0,$pos):'{imap-mail.outlook.com:993/ssl/novalidate-cert}';
//  echo $readMailObj->hostAddres;
    $readMailObj->connectMail();
    echo $readMailObj->connectMail();   
    if($readMailObj->validate != 1){
        $time = time();
        echo date("d-m-Y (H:i:s)", $time);
        echo "<br> Error al abrir correo ".$mailObj->mail_user."\n";
        exit();                
    }

    $time = time();
    echo date("d-m-Y (H:i:s)", $time);
    echo "<br>*********************LECTURA DE TIMEOUT****";
    echo imap_timeout(IMAP_OPENTIMEOUT);
    //echo $readMailObj->hostAddres;
    //Cargamos id del ultimo correo recibido
    $readMailObj->loadNumberLastMail();
    imap_timeout(IMAP_READTIMEOUT,90);
    echo "<br>********tiempo actualizado de lectura*****";
    echo imap_timeout(IMAP_READTIMEOUT);
    $contador=0;
    echo "<br>" . $readMailObj->lastNumberMail;   
    if($readMailObj->lastNumberMail>0){
        //Verificamos existencia de buzon RADICADOS para crearlo
        if(!$readMailObj->existenciaCarpeta()){
            $readMailObj->creaCarpeta();//Creamos buzon RADICADOS
        }
    }
    $time = time();
    echo "<br>" . date("d-m-Y (H:i:s)", $time);

    $flagRemove=array(); 
    $countEvent=0;
    $countBase=0;
   
    for($numberMail=1;$numberMail<=$readMailObj->lastNumberMail && $countBase<1;$numberMail++){
        $chequeoMails = imap_mailboxmsginfo($readMailObj->getImap());
        if(!$chequeoMails || $chequeoMails==null){
            $br=$readMailObj->getImap();
            imap_close($br);    
            unset($readMailObj);
            exit();
        }
        $nroCorreosInicial = $chequeoMails->Nmsgs;
        if($nroCorreosInicial<0){
            exit();
        }
        $readMailObj->mail->number = $numberMail;
        if(!$readMailObj->loadMail()){
            echo "<br>" . date("d-m-Y (H:i:s)", $time);
            echo "<br> Error al contenido de correo en ".$mailObj->mail_user."\n";
            exit();
        }
        $time = time();
        echo "<br>" . date("d-m-Y (H:i:s)", $time);

        $countBase++;
                //Definimos badera de si se debe radicar en false
        $flagRadicar = false;

        echo "<br>readMailObj->mail->fromMail:" . $readMailObj->mail->fromMail;
        //Asignamos correo a obtener informacion

                
        $penultimoRemitente=$radiMailObj->buscarCorreo($readMailObj->mail->body2,0);
        if(trim($penultimoRemitente)==''){
            $penultimoRemitente=$radiMailObj->buscarCorreo($readMailObj->mail->body,1);
            //metodo 3
            if(trim($penultimoRemitente)==''){
                $penultimoRemitente=$radiMailObj->buscarCorreo($readMailObj->mail->body,0);
            }
        }
        echo "<br>****penultimo remitente****"; 
        echo $penultimoRemitente;
        $subject_words = (count($mailObj->mail_subject_filter)!=0)?explode(' ',$readMailObj->mail->subject):array();
        if (count($mailObj->mail_subject_filter)==0) $flagRadicar = true;
            //echo $readMailObj->mail->subject;
            // ec$subject_words);
        foreach ($subject_words as $sw){
            //Validamos si alguna de las palabras del filtro se encuentra en el asunto si es asi,
            //rompemos bucle de palabras del asunto y encendemos bandera para realizar radicacion del mensaje en iteracion
            if(in_array($sw, $mailObj->mail_subject_filter)){
                $flagRadicar = true;
                break;
            }
        }

        try{
            ///Validamos remitente y ausnto
            $html_val=saveBody($readMailObj, $radiMailObj);
            $asunto=$readMailObj->mail->subject;
            echo "<br>***********Aqui va el asunto************";  
            var_dump($asunto);
            echo "<br>*******************";
            // var_dump($html_val[0]);
            //$asunto=trim($asunto);
            $origin=$readMailObj->mail->fromMail;
            $radCheck=$radiMailObj->validRadicado($origin,$asunto,$readMailObj); 
            echo "<br>********Aqui va la verificacion de repitencia*************";
            var_dump($radCheck[0]);
            echo "<br>";
            // $eml_str=$radCheck[2];
            var_dump(strlen($eml_str));
            echo "<br>";
            if($radCheck[0]!="" && strlen($radCheck[0])>=14){
                $flag_rad=false;
                $flag_state=updateData($radiMailObj, $radCheck[0],$radCheck[1],$readMailObj);
                $radiMailObj->update_annex=false;
                if($flag_state==2){
                    $var_alt=$radiMailObj->saveRelationEmailRad($radCheck[0],"","","","2",$origin,$asunto,2,"");
                }
                $flagRemove[]=["id"=>$readMailObj->mail->number,"radicado" =>$radCheck[0],"bandeja" => "RADICADOS"];                                     

                  // if(imap_delete($readMailObj->getImap(),$readMailObj->mail->number)){
                //  }
                    
            }else if($radCheck[0]=="" && $radCheck[1]=="1"){
                $flag_rad=false;
            }else{
                $flag_rad=true;     

            }
              
            //Aqui se validad el tipo de anexo
            // $flag_anex_valid=$radiMailObj->validFile($readMailObj->mail->attachments);
            // $uid=imap_uid($readMailObj->mail->number) 
            //$flagRadicar=false;
             
            $body_html=$html_val[1];
            var_dump($origin);
            echo "<br>";
            if(!$html_val[0] || strlen($radCheck[2])<=1){
                echo "<br>**************AQUI VAN LOS CORREOS QUE FALLARON EN SU HTML O EML************";
                echo "<br>*ASUNTO*****************";
                var_dump($readMailObj->mail->subject);
                echo "<br>****************REMITENTE****************";
                var_dump($readMailObj->mail->fromMail);
                echo "<br>****************to****************<br>";
                var_dump($readMailObj->mail->to);
                echo "<br>****************ccaddress****************<br>";
                var_dump($readMailObj->mail->ccaddress);
                echo "<br>****************cc****************<br>";
                var_dump($readMailObj->mail->cc);
                echo "<br>";
                $flagRemove[]=["id"=>$readMailObj->mail->number,"radicado" => "", "bandeja" => "correoerrorRADICADOS"];                  
            } 
            echo "<br>antes de if-- flagRadicar:" . $flagRadicar . "---flag_rad:" . $flag_rad . $flagRadicar . "---html_val[0]:" .  $html_val[0] . "-strlen(radCheck[2]:" . strlen($radCheck[2]);
            //if($flagRadicar && $flag_rad && $html_val[0] && strlen($radCheck[2])>1){
            if($flagRadicar && $flag_rad && $html_val[0]){
                $flagRemove[$countEvent]=["id"=>$readMailObj->mail->number,"radicado" => "", "bandeja" => "correoerrorRADICADOS"];                  
                $correoRadicador=$readMailObj->username;
                echo "<br>correoRadicador:";
                var_dump($correoRadicador);   
                echo "<br>";                  
                $destinatario = $radiMailObj->getInfoDestinatario($readMailObj,$penultimoRemitente);
                $asu=$readMailObj->mail->subject;
                $radi_usua_actu = $radiMailObj->getDestinatario($mailObj->mail_id);//$mailObj->mail;                                                      
                //exit;                 
                $data_radicado = $radiMailObj->radicarDocumento($destinatario,$asu,$radi_usua_actu,$readMailObj->mail->body,$readMailObj->mail->fromMail,$readMailObj->mail->ccaddress,$correoRadicador);
                $codTx=43;            
                echo "<br>****HIZO UN RADICADO***"; 

                echo "<br>data_radicado:"; //. count($data_radicado) . " --- data_radicado[1]:" . $data_radicado[1];
                var_dump($data_radicado);   
                echo "<br>";
                if(count($data_radicado)==2 && $data_radicado[1]=="invalid"){    
         
                }else{
                    $radi_nume_radi=$data_radicado[0];
                    //$mailDestino=$readMailObj->mail->fromMail;
                    echo "<br>todos los destinos:" . $readMailObj->TodoDestinos;
                    $mailDestino=$readMailObj->TodoDestinos;
                    $asuntoMailRadicado="Radicado N° $radi_nume_radi Supertransporte";
                    $passwordRadicador=$readMailObj->password;
                    $radicadosSelText=$radi_nume_radi;                                    
                    echo "<br>****AVANZO OTRO POCO***"; 
                    //  sendMailNotification($readMailObj->mail->fromMail, $codTx,$readMailObj->password);
                    // Procedemos a crear el archivo eml del correo
                    $time = time();
                    echo "<br>" . date("d-m-Y (H:i:s)", $time);
                    $path_eml=$radiMailObj->createEMLFile($readMailObj->getImap(),$readMailObj->mail->number,$radi_nume_radi,$radCheck[2]);
                    echo "<br>***********ACTUALIZANDO PERSISTENCIA EML*************"; 
                    usleep(45000);
                       
                    if (!strstr($radi_nume_radi,'Error') && $radi_nume_radi != ''  && $radi_nume_radi != 0){
                        //Grabamos relacion radicado mail creado
                        if ($radiMailObj->saveRelationEmailRad($radi_nume_radi,$readMailObj->mail->number,$mailObj->mail_id,$radi_usua_actu,"1",$readMailObj->mail->fromMail,$asu,1,$path_eml)){
                            //Imprimimos notificacion
                            $time = time();
                            echo "<br>" . date("d-m-Y (H:i:s)", $time);
                            echo "<br> Éxito: Se creó correctamente el radicado {$radi_nume_radi} del correo {$mailObj->mail} mensaje numero {$readMailObj->mail->number}\n";
                            $flagRemove[$countEvent]=["id"=>$readMailObj->mail->number,"radicado" => $radi_nume_radi, "bandeja" => "RADICADOS"];
                            $countEvent++;
                            echo $readMailObj->lastNumberMail;
                            //Finalizamos movimientos de correos
                            //$db->conn->debug=true;                    
                            $state_errors=false;
                            $state_body=updateHTML($body_html,$readMailObj, $radi_nume_radi,$radiMailObj);
                            echo "<br>cuenta anexos" . count($readMailObj->mail->attachments);      
                             
                            //Creamos los anexos del correo         
                            $radiMailObj->update_annex=false;                  
                            $annex = $radiMailObj->createAttachments($radi_nume_radi,$readMailObj->mail->attachments,$mailObj->mail);
                            //Imprimimos mensaje de exito de los anexos creados correctamente
                            
                            if (count($annex['sucess'])>0){
                                echo "<br>-Para el radicado {$radi_nume_radi} se crearon correctamente los anexos ".implode(',',$annex['sucess'])."\n";
                                $state_errors=false;
                            }

                            //Imprimimos mensaje de error de los anexos que no se pudieron crear
                            if (count($annex['fail'])>0){
                                echo "<br>-Fallo para el radicado {$radi_nume_radi} al crear los anexos ".implode(',',$annex['fail'])." \n";
                                    $state_errors=true; 
                            }
                    
                            $radiMailObj->dateMail=$readMailObj->mail->date;   
                            $radiMailObj->createImageRadi($data_radicado[0],$data_radicado[1],$data_radicado[2],$readMailObj->mail->from,$data_radicado[4],$data_radicado[5]); 
                            $state_errors|=!$state_body;  
                            $correoCopiado=false;
                            $intentosCopiar=0;
                                
                            if(!$state_errors){
                                $var_alt=$radiMailObj->saveRelationEmailRad($radi_nume_radi,"","","","2",$origin,$asunto,2,"");
                            }
                            include("../include/mail/GENERAL.mailInformar.php");
                        }
                    }
                }
            }                 
        }catch(Exception $e){
            continue;
        }
    }                               

    //$db->close();
    var_dump($flagRemove);
    echo "<br>";
    unset($countBase);
    unset($countEvent); 
    //unset($db);
    unset($radiMailObj);
    unset($body_html);
    unset($radCheck);
    $time = time();
    echo "<br>date:" . date("d-m-Y (H:i:s)", $time);
  
    echo "<br>***********POR AQUI VA EL CODIGO**********";
    for($i=0;$i<count($flagRemove);$i++){
        do {
            if($flagRemove[$i]["bandeja"]!=null && $flagRemove[$i]["bandeja"]!=""){
                $target=$flagRemove[$i]["bandeja"];
            }else{
                $target= "correoerrorRADICADOS"; 
                // break;
            }
            if(imap_mail_move($readMailObj->getImap(),"{$flagRemove[$i]['id']}",$target)){
                $correoCopiado=true;
                imap_delete($readMailObj->getImap(), $flagRemove[$i]["id"]);
                echo "<br>SE MUEVE EL RADICADO A LA BANDEJA $target";                            
            }
            $intentosCopia++;                    
        } while ($correoCopiado == false && $intentosCopia < 2);
    }

    $buzon = $readMailObj->getImap();
    if(imap_expunge($buzon)){
        echo "<br>AQUI SE ELIMINA DEFINITIVAMENTE******************SE CIERRA LA CONEXION";
    }
    
    imap_close($buzon);
    //imap_close($readMailObj->getImap());
    unset($buzon);
    unset($readMailObj);   
    break;
}
echo "<br>*****SALIDAD DE LA ITERACION*********";
unset($flagRemove);
ini_set('memory_limit','1M');
exit(); 

//set_time_limit(int $seconds);
?>
