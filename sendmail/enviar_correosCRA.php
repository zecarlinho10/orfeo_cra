<html>
<head>
<title>PHPMailer - SMTP basic test with no authentication</title>
</head>
<body>
<?php
ini_set("display_errors",1);
//error_reporting(E_ALL);
error_reporting (E_STRICT);

date_default_timezone_set('America/Toronto');

require("phpmailer/class.phpmailer.php");
//require_once('class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();

//$body             = file_get_contents('contents.html');
//$body             = eregi_replace("[\]",'',$body);

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "192.168.100.32"; // SMTP server
$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only

$mail->SetFrom('orfeo@crapsb.gov.co', 'Orfeo');

$mail->AddReplyTo("orfeo@crapsb.gov.co","First Last");


$mail->Subject    = "MEMORANDO $record_id";

$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$body  = "<strong>Buen dia</strong><br><br>";

      $body .= "El Memorando <strong><font color='blue'>$record_id </font></strong>ha sido enviado a su bandeja de Orfeo.<br><br>";

      $body .= "Para Revisarlo por favor ingrese al Sistema  o consultelo directamente en el siguiente enlace:<br><br>
      			$ruta <br><br>";
      $body .= "<strong>Cordialmente,</strong>";
      
      $body .= '<div align="left"><img src="'.$ruta_raiz.'/logo_cra.jpg" style="height: 90px; width: 340px"></div>';

$mail->MsgHTML($body);

$mail->AddAddress("cejebuto@gmail.com", "");
//$mail->AddAddress($email2, "");	
//$mail->AddAddress($email3, "");
//$mail->AddAddress($email4, "");	
//$mail->AddBCC("orfeo@cra.gov.co"); 
//$mail->Send();

if($mail->Send()){
    echo ' > Mensaje Numero :'.$numero.'  Enviado <br>';
}else{
    echo ' > Fail N. '.$numero.'>>';
echo  $mail->ErrorInfo;
echo "<br>";
 }
echo "asde"; exit;

?>

</body>
</html>

