<?php
include_once "../seguridad_sql.php";
foreach ($_GET as $key => $value)  { $_GET[$key] = StringClean($value); }
foreach ($_POST as $key => $value) { $_POST[$key] = StringClean($value); }


ini_set('max_execution_time',600);
ini_set('memory_limit','512M');
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



//----------------------------//
//Iteramos
foreach($radiMailObj->mailList as $mailObj)
{
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
    
//	echo $readMailObj->hostAddres;
	$readMailObj->connectMail();
        echo $readMailObj->connectMail();	
        if($readMailObj->validate != 1){
		$time = time();
        echo date("d-m-Y (H:i:s)", $time);
	    echo " Error al abrir correo ".$mailObj->mail_user."\n";
		continue;
             		
	}
	//echo $readMailObj->hostAddres;
	//Cargamos id del ultimo correo recibido
	$readMailObj->loadNumberLastMail();

        //echo $readMailObj->lastNumberMail;	
	
    $contador=0;
    echo $readMailObj->lastNumberMail;	
	if($readMailObj->lastNumberMail>0){
        //Verificamos existencia de buzon RADICADOS para crearlo
        if(!$readMailObj->existenciaCarpeta()){
            $readMailObj->creaCarpeta();//Creamos buzon RADICADOS
        }
    }
   $flagRemove=array(); 
   $countEvent=0;
   $countBase=0;
   for($numberMail=1;$numberMail<=$readMailObj->lastNumberMail && $countBase<=20;$numberMail++)
    {
    	$chequeoMails = imap_mailboxmsginfo($readMailObj->getImap());
	echo $cheoqueoMails;
           $countEvent++;
           $countBase++;
           $nroCorreosInicial = $chequeoMails->Nmsgs;
		if($nroCorreosInicial<0){
			break;
		}
		//Definimos badera de si se debe radicar en false
		$flagRadicar = false;

                echo $readMailObj->mail->fromMail;
		//Asignamos correo a obtener informacion
		$readMailObj->mail->number = $numberMail;
 	        if(!$readMailObj->loadMail()){
		    $time = time();
		    echo date("d-m-Y (H:i:s)", $time);
		    echo " Error al contenido de correo en ".$mailObj->mail_user."\n";
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
                echo $penultimoRemitente;
		$subject_words = (count($mailObj->mail_subject_filter)!=0)?explode(' ',$readMailObj->mail->subject):array();
	        if (count($mailObj->mail_subject_filter)==0) $flagRadicar = true;

         	//echo $readMailObj->mail->subject;
               // ec$subject_words);
                foreach ($subject_words as $sw)
		{
			//Validamos si alguna de las palabras del filtro se encuentra en el asunto si es asi,
			//rompemos bucle de palabras del asunto y encendemos bandera para realizar radicacion del mensaje en iteracion
			if(in_array($sw, $mailObj->mail_subject_filter))
			{
				$flagRadicar = true;
				break;
			}
		}

            try{
               ///Validamos remitente y ausnto
               $asunto=mb_convert_encoding($readMailObj->mail->subject, 'UTF-8','UTF-8');
               //$asunto=trim($asunto);

               var_dump($readMailObj->mail);

               $origin=$readMailObj->mail->fromMail;
    //           $radCheck=$radiMailObj->validRadicado($origin,$asunto,$readMailObj); 
             var_dump($radCheck);
             if($radCheck[0]!="" && strlen($radCheck[0])==14){
                    $flag_rad=false;
                    $flag_state=updateData($radiMailObj, $radCheck[0],$radCheck[1],$readMailObj);
                    $radiMailObj->update_annex=false;
                   if($flag_state==2){
                       $var_alt=$radiMailObj->saveRelationEmailRad($radCheck[0],"","","","2",$origin,$asunto,2,"");

                   }
                                     
                 $flagRemove[]=["id"=>$readMailObj->mail->number,"radicado" =>$radCheck[0]];                                     

                  // if(imap_delete($readMailObj->getImap(),$readMailObj->mail->number)){
                       echo "Bien Radicando";
           //  }
                    
              }else if($radCheck[0]=="" && $radCheck[1]=="1"){
                   $flag_rad=false;
              }else{
                    $flag_rad=true;     

              }
              //Aqui se validad el tipo de anexo
             $flag_anex_valid=$radiMailObj->validFile($readMailObj->mail->attachments);
             // 
              
            //$flagRadicar=false;
             if($flagRadicar && $flag_rad && $flag_anex_valid){
                        $correoRadicador=$readMailObj->username;
                    var_dump($correoRadicador);                     
                    $destinatario = $radiMailObj->getInfoDestinatario($readMailObj,$penultimoRemitente);
                    $asu=mb_convert_encoding($readMailObj->mail->subject, 'UTF-8','UTF-8');
                    //$asu=trim($asu);
                    //$asu=html_entity_decode($readMailObj->mail->subject);
                    //$asu=utf8_encode($asu);                         
                    //Metodo para buscar siguiente destinatario                
                    $radi_usua_actu = $radiMailObj->getDestinatario($mailObj->mail_id);//$mailObj->mail;                                                      

//exit;                 
                        $db->conn->debug=true;
                                                                               
                        $data_radicado = $radiMailObj->radicarDocumento($destinatario,$asu,$radi_usua_actu,$readMailObj->mail->body,$readMailObj->mail->fromMail,$readMailObj->mail->ccaddress,$correoRadicador);
$codTx=43;             
                       if(count($data_radicado)==2 && $data_radicado[1]=="invalid"){


}else{
                                                        
                        $radi_nume_radi=$data_radicado[0];
                        $mailDestino=$readMailObj->mail->fromMail;
                        $asuntoMailRadicado="Radicado N° $radi_nume_radi Supertransporte";
                        $passwordRadicador=$readMailObj->password;
                        $radicadosSelText=$radi_nume_radi;
                        include("../include/mail/GENERAL.mailInformar.php");
                       // Procedemos a crear el archivo eml del correo
                        $path_eml=$radiMailObj->createEMLFile($readMailObj->getImap(),$readMailObj->mail->number,$radi_nume_radi);
                       usleep(1000);
                       
                       if (!strstr($radi_nume_radi,'Error') && $radi_nume_radi != ''  && $radi_nume_radi != 0)
			{
				//Grabamos relacion radicado mail creado
			    if ($radiMailObj->saveRelationEmailRad($radi_nume_radi,$readMailObj->mail->number,$mailObj->mail_id,$radi_usua_actu,"1",$readMailObj->mail->fromMail,$asu,1,$path_eml))
				{
					//Imprimimos notificacion
					$time = time();
                                         echo date("d-m-Y (H:i:s)", $time);
					echo " Éxito: Se creó correctamente el radicado {$radi_nume_radi} del correo {$mailObj->mail} mensaje numero {$readMailObj->mail->number}\n";
				
                                        $flagRemove[]=["id"=>$readMailObj->mail->number,"radicado" => $radi_nume_radi];                  
                                       
				                                      echo $readMailObj->lastNumberMail;
                                      //Finalizamos movimientos de correos
//$db->conn->debug=true;			        
                                 
                                  $state_errors=false;

                                   $bodyHtml=$readMailObj->loadMiMeBody();
//echo "CUERPO PREVIO MODIFICADO";
                                   $body_tmp=$readMailObj->getBody();

                                   $data=fopen('cuerpo_mail.html','w');
                                   if($data){
                                         fwrite($data,$body_tmp);
                                         fclose($data);


                                  }
                                 //Actualiza el cuerpo del correo como anexo//
                                 $radiMailObj->convertBodytoPDF($body_tmp,$readMailObj->mail->subject, $radi_nume_radi, $readMailObj->mail->date,$readMailObj->mail->fromMail, $readMailObj->mail->attachments, $readMailObj->mail->from);


			              echo count($readMailObj->mail->attachments);		
				     
//Creamos los anexos del correo         
                    $radiMailObj->update_annex=false;                  
					$annex = $radiMailObj->createAttachments($radi_nume_radi,$readMailObj->mail->attachments,$mailObj->mail);
					//Imprimimos mensaje de exito de los anexos creados correctamente
					
                                      if (count($annex['sucess'])>0)
					{
						echo "-Para el radicado {$radi_nume_radi} se crearon correctamente los anexos ".implode(',',$annex['sucess'])."\n";
				            $state_errors=false;
                                   	}

					//Imprimimos mensaje de error de los anexos que no se pudieron crear
					if (count($annex['fail'])>0)
					{
						echo "-Fallo para el radicado {$radi_nume_radi} al crear los anexos ".implode(',',$annex['fail'])." \n";
				            $state_errors=true;	
                    }
			
                     $radiMailObj->dateMail=$readMailObj->mail->date; 	
                     $radiMailObj->createImageRadi($data_radicado[0],$data_radicado[1],$data_radicado[2],$readMailObj->mail->from,$data_radicado[4],$data_radicado[5]);	
			
                      $correoCopiado=false;
				      $intentosCopiar=0;
	                                        
					
                                        
                        if(!$state_errors){
                                                    
                             $var_alt=$radiMailObj->saveRelationEmailRad($radi_nume_radi,"","","","2",$origin,$asunto,2,"");
                        }
	
                 }
                                    
            }
        }
     }                 
    }catch(Exception $e){
        continue;

    }
  }                               
                    
 
 for($i=0;$i<count($flagRemove);$i++){

    do {
        if(imap_mail_move($readMailObj->getImap(),"{$flagRemove[$i]['id']}",'RADICADOS')){
                                $correoCopiado=true;
                                 imap_delete($readMailObj->getImap(), $flagRemove[$i]["id"]);
                                echo "BORRANDO RADICADOS";                            
        }
        $intentosCopia++;
                            
    } while ($correoCopiado == false && $intentosCopia < 3);
  }


             $buzon = $readMailObj->getImap();
             if(imap_expunge($buzon)){
                        echo "BUENAS PRUEBAS MANO";
              }
		     
             imap_close($buzon);
    
}
	
?>
