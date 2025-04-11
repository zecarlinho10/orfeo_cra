<?php
    ini_set("display_errors",1);

    $ruta_raiz = ".";

    //require_once ($ruta_raiz . '/include/CRA_phpmailer/class.phpmailer.php');
    //include ($ruta_raiz . "/conf/configPHPMailer.php");
    require_once  ("$ruta_raiz/class_control/Mensaje.php");
//    require_once  ("$ruta_raiz/include/db/ConnectionHandler.php");



    //echo realpath(dirname(__FILE__) . "..") ."/include/db/ConnectionHandler.php\n" ;

    $tmpCarlo = realpath(dirname(__FILE__) . "") ."/include/db/ConnectionHandler.php";
    echo "$tmpCarlo\n";
    require_once $tmpCarlo;

    include_once  ("$ruta_raiz/include/utils/Calendario.php");

    include "$ruta_raiz/conf/configPHPMailer1.php";
    include_once ($ruta_raiz.'/include/PHPMailer_v5.1/class.phpmailer1.php');    
    echo "anter termino";
    var_dump($db);
    //if (!$db){
       $db = new ConnectionHandler($ruta_raiz);
    //} 

    echo "Termino";
    var_dump($db);

//FUNCION ENVIAR EMAIL
function enviarEmail($radicados, $mailDestino)
{
  $ruta_raiz = ".";
  
    //echo $mailDestino."\n";
    //echo $radicados."\n";
            $mail = new PHPMailer();
            $mail->IsSMTP(); // telling the class to use SMTP
            try {
                
                //$mail->AddAddress($mailDestino, "$mailDestino");
                $mailDestino="cricaurte@cra.gov.co";
                $mail->AddAddress($mailDestino, "$mailDestino");

                $mensaje = file_get_contents($ruta_raiz."/conf/MailAlertaNdias.html");

                $asuntoMail =  "Alerta! radicados ORFEO proximos a vencer";
                $mail->Subject = "$asuntoMail";
                $mail->AltBody = 'Para ver correctamente el mensaje, por favor use un visor de mail compatible con HTML!'; // optional - MsgHTML will create an alternate automatically
                $mensaje = str_replace("*RAD_S*", $radicados, $mensaje);
                $mail->MsgHTML($mensaje);


                $time = date("G:i:s");
                $dia = date('Y-m-d');

                if($mail->Send()){
                    $entry = "Correo electronico enviado a $mailDestino del No Rad : $radi_nume_radi el dia $dia  a las $time.\n";
                }else{
                    $entry = "** NO se pudo enviar el correo a $mailDestino del No Rad : $radi_nume_radi el dia $dia  a las $time.** \n";
                } 

                //$entry = "TEST ->  $mailDestino del No Rad : $radi_nume_radi el dia $dia  a las $time.\n";
                $file = "/var/www/mail.cron.txt";
                $open = fopen($file,"a");
                 
                if ( $open ) {
                    fwrite($open,$entry);
                    fclose($open);
                } 

            } catch (phpmailerException $e) {
              echo $error = $error . $e->errorMessage() . " " .$mailDestino; //Pretty error messages from PHPMailer
            } catch (Exception $e) {
              echo $error = $error . $e->getMessage() . " " .$mailDestino; //Boring error messages from anything else!
            }

    return true;
}


    //Numero de dias habiles para el calculo.
    $numero_dias = 5;

    //CALCULO DE FECHAS
    $hoy = date('Y-m-d');

    $calendario = new Calendar();
    $fechaparacalcular=$calendario->calcular($hoy,$numero_dias);
    $fechaOK=date("Y-m-d", $fechaparacalcular);

    //OBTENGO LOS RADICADOS QUE ESTAN POR VENCER EN n DIAS SI TIENE EL CORREO SU PROPIETARIO.

    $isql="SELECT RADI_DEPE_ACTU, RADI_USUA_ACTU, RA_ASUN, RADI_NUME_RADI, FECH_VCMTO, USUA_EMAIL, ID
        FROM RADICADO F, USUARIO U
        WHERE RADI_DEPE_ACTU=U.DEPE_CODI AND RADI_USUA_ACTU=U.USUA_CODI AND FECH_VCMTO = '$fechaOK' AND RADI_NUME_RADI LIKE '%2' AND
              RADI_DEPE_ACTU IN(SELECT DEPE_CODI FROM DEPENDENCIA 
                                WHERE DEPE_ESTADO=1 AND DEPENDENCIA_ESTADO=1 AND 
                                DEPE_CODI NOT IN (900,999,330,216,402,403)
                                )
        ORDER BY USUA_EMAIL";

    echo "fecha para calcular:" . $fechaOK . "\n";

      $rs=$db->conn->query($isql);

    $IDanterior=0;
    $EMAILanterior="";
    $totalRadicados="";

    //RECORRO DATO POR DATO, agrupo por usuario y ENVIO UN CORREO ELECTRONICO. 
    while (!$rs->EOF){//ESTE ES EL WHILE
        $radi_nume_radi = $rs->fields['RADI_NUME_RADI'];
        $usua_email = $rs->fields['USUA_EMAIL'];
        $asu = $rs->fields['RA_ASUN'];
        $id=$rs->fields['ID'];
        $mailDestino = $usua_email;

        if($IDanterior == 0){
            $IDanterior=$id;
            $EMAILanterior=$usua_email;
            $totalRadicados=$radi_nume_radi;
        }
        else if($IDanterior == $id){
            $totalRadicados.= " - " . $radi_nume_radi;
        }
        else{
            enviarEmail($totalRadicados,$EMAILanterior);
            echo "Email anterior:" . $EMAILanterior . "   ** Total radicados:" . $totalRadicados . "\n";
            $IDanterior=$id;
            $EMAILanterior=$usua_email;
            $totalRadicados= $radi_nume_radi;
        }
        $rs->MoveNext();
    }
    //FIN DEL WHILE
    enviarEmail($totalRadicados,$EMAILanterior);
     echo "Email anterior:" . $EMAILanterior . "   ** Total radicados:" . $totalRadicados . "\n" ;

    $time = date("G:i:s");
    $dia = date('Y-m-d');
    if ($error==""){$error = "Correctamente";}
    $entry = "************* Se ejecuto el cron el dia $dia a las $time./ $error *********************\n";
    $file = "/var/www/mail.cron.txt";
    $open = fopen($file,"a");
    if ( $open ) {
        fwrite($open,$entry);
        fclose($open);
    } 

?>


