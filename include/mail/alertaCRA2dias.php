<?php
//ini_set("display_errors",1);
 $ruta_raiz = "/var/www/html/orfeopruebas";
 include_once    ("$ruta_raiz/include/db/ConnectionHandler.php");
 if (!$db){
	$db = new ConnectionHandler($ruta_raiz);
 } 
require_once    ("$ruta_raiz/class_control/Mensaje.php");
require_once($ruta_raiz.'/include/CRA_phpmailer/class.phpmailer.php');

//Numero de dias habiles para el calculo.
$numero_dias = 2;

//CALCULO DE FECHAS
$hoy = date('Y-m-d');
$anio = (date("Y")-2)."-01-01";

//BUSCO LOS DIAS HABILES Y COLOCO UN ARRAY
  
$_isql = "select NOH_FECHA from sgd_noh_nohabiles where NOH_FECHA >= '$anio'";
$_rs=$db->conn->query($_isql);
$j = 0;
 while (!$_rs->EOF) {//WHILE
   $fecha_noH[$j] = $_rs->fields['NOH_FECHA'];
   $_rs->MoveNext();
  $j++;
  }// /WHILE

$GD = 0;
//recorro un 10 veces
for ($i = 1;$i <= 10; $i++) {
    //Si GD Get_Day es menor que el numero de dias habiles, siga calculando si no , pare el for.
    if ($GD < $numero_dias) {
	$fechaparacalcular = strtotime ( '+1 day' , strtotime ( $hoy ) ) ;
	$hoy = date ( 'Y-m-j' , $fechaparacalcular );

	$tmp_dat = explode("-", $hoy);
        $tmp_day = $tmp_dat[2];
	if (strlen($tmp_day)==1){$tmp_day = "0".$tmp_day;}
	$hoy = $tmp_dat[0]."-".$tmp_dat[1]."-".$tmp_day;

	//Si la fecha NO se encuentra en los dias habiles sume a GD
	if (!in_array("$hoy",$fecha_noH)) {
	    $GD++;
	    $VC_fecha = $hoy;	
	}
    }else{$i=10;}
}
$fechaparacalcular = $VC_fecha;

//OBTENGO LOS RADICADOS QUE ESTAN POR VENCER EN 2 DIAS SI TIENE EL CORREO SU PROPIETARIO.
$isql = "select r.radi_nume_radi, r.RA_ASUN, u.USUA_EMAIL from radicado r
INNER JOIN usuario u
ON r.RADI_USUA_ACTU=u.usua_codi and r.RADI_DEPE_ACTU = u.DEPE_CODI 	
where r.FECH_VCMTO = '$fechaparacalcular' and r.RADI_FECH_RADI > '$anio'";

  $rs=$db->conn->query($isql);

 //RECORRO DATO POR DATO, Y POR CADA UNO, ENVIO UN CORREO ELECTRONICO. 
 while (!$rs->EOF) {//ESTE ES EL WHILE
   $radi_nume_radi = $rs->fields['RADI_NUME_RADI'];
   $usua_email = $rs->fields['USUA_EMAIL'];
   $asu = $rs->fields['RA_ASUN'];

$mailDestino = $usua_email;
//$mailDestino = "cejebuto@gmail.com";

$mail = new PHPMailer();
$mail->IsSMTP(); // telling the class to use SMTP
try {

$mail->CharSet = "UTF-8"; 
$mail->IsSMTP(); // send via SMTP
$mail->Host = "192.168.100.32"; // SMTP servers
$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)

$mail->SetFrom = "orfeo@cra.gov.co";


$mail->AddAddress($mailDestino, "$mailDestino");
//$mail->AddAddress('cejebuto@gmail.com', 'sgdorfeo');
//$mail->AddAddress('giampieruccini@gmail.com', 'sgdorfeo');
  $mensaje = file_get_contents($ruta_raiz."/conf/MailRadicado2dias.html");

  $asuntoMail =  "El radicado $radi_nume_radi  esta por vencer";

  $mail->Subject = "$entidad: $asuntoMail";
  $mail->AltBody = 'Para ver correctamente el mensaje, por favor use un visor de mail compatible con HTML!'; // optional - MsgHTML will create an alternate automatically
  $mensaje = str_replace("*RAD_S*", $radi_nume_radi, $mensaje);
  $mensaje = str_replace("*USUARIO*", $krd, $mensaje);
  $linkImagenes = str_replace("*SERVIDOR_IMAGEN*",$servidorOrfeoBodega,$linkImagenes);
  $mensaje = str_replace("*IMAGEN*", $linkImagenes, $mensaje);
  $mensaje = str_replace("*ASUNTO*", $asu, $mensaje);
  $nom_r = $nombre_us1 ." ". $prim_apel_us1." ". $seg_apel_us1. " - ". $otro_us1;
  $mensaje = str_replace("*NOM_R*", $nom_r, $mensaje);
  $mensaje = str_replace("*RADICADO_PADRE*", $radicadopadre, $mensaje);
  $mensaje = str_replace("*MENSAJE*", $observa, $mensaje);
  // $mensaje .= "<hr>$entidad_largo<br>Sistema de Gestion. http://www.correlibre.org";
  $mail->MsgHTML($mensaje);
  //$mail->AddAttachment('images/phpmailer.gif');      // attachment
  //$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment


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

   $rs->MoveNext();
  }//FIN DEL WHILE

$time = date("G:i:s");
$dia = date('Y-m-d');
if ($error==""){$error = "Correctamente";}
$entry = "************* Se ejecuto el cron el dia $dia  a las $time./ $error *********************\n";
$file = "/var/www/mail.cron.txt";
$open = fopen($file,"a");
if ( $open ) {
    fwrite($open,$entry);
    fclose($open);
} 

?>
