<?php
include_once "../seguridad_sql.php";
foreach ($_GET as $key => $value)  { $_GET[$key] = StringClean($value); }
foreach ($_POST as $key => $value) { $_POST[$key] = StringClean($value); }


ini_set('max_execution_time',600);
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
//var_dump($radiMailObj->mailList;

//Iteramos
foreach($radiMailObj->mailList as $mailObj)
{
    if(!$mailObj->mail_dest_alt){
        continue;
    }
	//Instanciamos clase
	$readMailObj = new ReadMail();

	//Asignamos datos de conexion
	//$readMailObj->host           = '{imap.gmail.com:993/imap/ssl}INBOX'; //Gmail;
	//$readMailObj->host           = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX'; //Gmail;
	//$readMailObj->host           = '{192.168.100.33:143/novalidate-cert}INBOX'; //Outlook
	$readMailObj->host           = trim($mailObj->mail_host);
	$readMailObj->username       = $mailObj->mail_user;
	$readMailObj->password       = $mailObj->mail_pass;
	
       	
	$pos = strpos($readMailObj->host, '}');
	$pos++;
	$readMailObj->hostAddres=(substr($readMailObj->host,0,$pos))?substr($readMailObj->host,0,$pos):'{imap-mail.outlook.com:993/ssl/novalidate-cert}';
    
//	echo $readMailObj->hostAddres;
	$readMailObj->connectMail();
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
   $numberMail=1;
   $flagRemove=array(); 
   while($numberMail<$readMailObj->lastNumberMail)
    {
    	$chequeoMails = imap_mailboxmsginfo($readMailObj->getImap());
	echo $cheoqueoMails;
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

              if($flagRadicar){
                        $correoRadicador=$readMailObj->username;                    
                    $destinatario = $radiMailObj->getInfoDestinatario($readMailObj,$penultimoRemitente);
                    $asu= $readMailObj->mail->subject;                         
                    //Metodo para buscar siguiente destinatario                
                    $radi_usua_actu = $radiMailObj->getDestinatario($mailObj->mail_id);//$mailObj->mail;                                                      

//exit;d
                                                                               
                        $radi_nume_radi = $radiMailObj->radicarDocumento($destinatario,$asu,$radi_usua_actu,$readMailObj->mail->body,$readMailObj->mail->fromMail,$readMailObj->mail->ccaddress,$correoRadicador);
$codTx=43;
                        $mailDestino=$readMailObj->mail->fromMail;
                        $asuntoMailRadicado="Radicado N° $radi_nume_radi Supertransporte";
                        $radicadosSelText=$radi_nume_radi;
                        include("../include/mail/GENERAL.mailInformar.php");
                       // Procedemos a crear el archivo eml del correo
                        $radiMailObj->createEMLFile($readMailObj->getImap(),$numberMail,$radi_nume_radi);
                       usleep(1000);
                       
                       if (!strstr($radi_nume_radi,'Error') && $radi_nume_radi != ''  && $radi_nume_radi != 0)
			{
				//Grabamos relacion radicado mail creado
			    if ($radiMailObj->saveRelationEmailRad($radi_nume_radi,$readMailObj->mail->number,$mailObj->mail_id,$radi_usua_actu))
				{
					//Imprimimos notificacion
					$time = time();
                    echo date("d-m-Y (H:i:s)", $time);
					echo " Éxito: Se creó correctamente el radicado {$radi_nume_radi} del correo {$mailObj->mail} mensaje numero {$readMailObj->mail->number}\n";
				
                                        $flagRemove[]=$readMailObj->mail->number;                    
                                       
					//MOVIDO->operaciones
				                                	/*
					 * AGNADIMOS VALIDACION PARA MOVER CORREOS DE BUZON DE ENTRADA A BUZON RADICADOS
					 */
					//COPIADO->operaciones

					
				                      					
                                      echo $readMailObj->lastNumberMail;
                                      //Finalizamos movimientos de correos
					
					
					//Creamos los anexos del correo
					$annex = $radiMailObj->createAttachments($radi_nume_radi,$readMailObj->mail->attachments,$mailObj->mail);
					//Imprimimos mensaje de exito de los anexos creados correctamente
					if (count($annex['sucess'])>0)
					{
						echo "-Para el radicado {$radi_nume_radi} se crearon correctamente los anexos ".implode(',',$annex['sucess'])."\n";
					}

					//Imprimimos mensaje de error de los anexos que no se pudieron crear
					if (count($annex['fail'])>0)
					{
						echo "-Fallo para el radicado {$radi_nume_radi} al crear los anexos ".implode(',',$annex['fail'])." \n";
					}
					
				}
			}
			else
			{
				$time = time();
                echo date("d-m-Y (H:i:s)", $time);
				//echo " Error: Inconvenientes al crear radicado del correo {$mailObj->mail} mensaje numero {$readMailObj->mail->number}\n";
			}
		}
		else
		{
			$radiMailObj->printMsg('No hubo correos con coincidencias en el asunto del correo '.$mailObj->mail,'error');
		}
                       
                 $numberMail++; 
              }
    	      for($i=0;$i<count($flagRemove);$i++){ 
                                        $correoCopiado=false;
					$intentosCopiar=0;
	
					do {
					    if(imap_mail_move($readMailObj->getImap(),"{$flagRemove[$i]}",'RADICADOS')){
					        $correoCopiado=true;
					    }
					    $intentosCopia++;
					} while ($correoCopiado == false || $intentosCopia < 5);
					
                                        $intentosMover=0;
	                                $corridoMovido=false;

                        					
					//MOVIDO->operaciones
									
					do {
					    if(imap_expunge($readMailObj->getImap())){
					        $chequeoMailsPost = imap_mailboxmsginfo($readMailObj->getImap());
					        $nroCorreosPost = $chequeoMailsPost->Nmsgs;
					        if($nroCorreosPost<$nroCorreosInicial){
					            $corridoMovido=true;
					        }
					        
					    }
					    $intentosMover++;
					} while ($corridoMovido == false || $intentosMover < 5);
					
					/*Si el correo fué copiado al buzon de RADICADOS pero no se movio del buzon de entrada
					 * entonces forzamos la eliminacion
					 * */
					if($nroCorreosInicial==$nroCorreosPost && $correoCopiado==true){
					    
                                            $buzon = $readMailObj->getImap();
					    imap_delete($buzon, $flagRemove[$i]);					    
					    imap_expunge($buzon);
					    imap_close($buzon);
					}
				
                                    }


	//Realizamos for que inicia desde el ultimo correo radicado mas uno al ultimo mensaje en el buzon del correo en iteracion
    }
	
		//Realizamos proceso de radicacion
?>
