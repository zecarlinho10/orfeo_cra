<?php
 $admPHPMailer = "orfeo@cra.gov.co";
 $userPHPMailer = "orfeo@cra.gov.co";
 $passwdPHPMailer = '%gabriela%';
 //$passwdPHPMailer = '%gabriela%'; 
$hostPHPMailer = gethostbyname("smtp.office365.com"); //"smtp.office365.com";//gethostbyname("smtp.office365.com");      // Para fuera Gmail es "ssl://smtp.gmail.com"
//$portPHPMailer = "587"; 
//$hostPHPMailer = "192.168.100.243";      // Para fuera Gmail es "ssl://smtp.gmail.com"
//$portPHPMailer = "25";                         // Para Gmail el Puerto es "465"       
// $portPHPMailer = "587";                         // Para Gmail el Puerto es "465"       
 $debugPHPMailer = false;  // Si esta en 2 mostrara una depuracion al enviar correo.  En 1 los evita.
 $authPHPMailer = true;
 //uthPHPMailer = false;
 $asuntoMailReasignado = "Llegada de Radicado $radicadosSelText";
 $asuntoMailRadicado = "Llegada de Radicado nuevo $radicadosSelText";
 $asuntoMailInformado = "Lo han informado de un radicado $radicadosSelText";
 $asuntoMailNotificaTipoDoc = "Radicado  Vencido $radicadosSelText";
 $servidorOrfeoBodega = "http://gestiondocumental.cra.gov.co/orfeonew/bodega/";
 $usuarioEmailPQRS     = "atencionpqrs";
 $emailPQRS            = "atencionpqrs@entidad.gov.co";
 $passwordEmailPQRS    = "contraseñacorreo";
 #Si es un exchange esto puede ser necesario
 #$tipoAutenticacion   = "NTLM";
 $SMTPSecure = 'tls';
//$SMTPAuth = true;

?>
