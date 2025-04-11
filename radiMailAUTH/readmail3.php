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
   $flagRemove=array(); 
   for($numberMail=1;$numberMail<=$readMailObj->lastNumberMail;$numberMail++)
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
                    //$destinatario = $radiMailObj->getInfoDestinatario($readMailObj,$penultimoRemitente);
                    $asu= $readMailObj->mail->subject;                         
                    //Metodo para buscar siguiente destinatario                
                   // $radi_usua_actu = $radiMailObj->getDestinatario($mailObj->mail_id);//$mailObj->mail;                                                      

//exit;d
                                                                               
                }
		else
		{
			$radiMailObj->printMsg('No hubo correos con coincidencias en el asunto del correo '.$mailObj->mail,'error');
		}
                       
              }
    	      

	//Realizamos for que inicia desde el ultimo correo radicado mas uno al ultimo mensaje en el buzon del correo en iteracion
    }
	
		//Realizamos proceso de radicacion
?>
