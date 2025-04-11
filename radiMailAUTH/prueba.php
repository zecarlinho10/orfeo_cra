<?php 
session_start();
//if (!$_SESSION['dependencia']) header ("Location: ../cerrar_session.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

$username='vur@supertransporte.gov.co';
//$username = 'v@supertransporte.gov.co';
$password = 'C0l0mb142020**';
//$hostname = '{imap.gmail.com:993/ssl/novalidate-cert}INBOX';

 //host para hotmail
$hostname='{outlook.office365.com:993/imap/ssl}INBOX';
//$hostname = '{outlook.office365.com:993/imap/ssl/novalidate-cert/authuser=ventanillaunicaderadicacion@supertransporte.gov.co}INBOX';
//$hostname = "{outlook.office365.com:993/ssl/service=imap}INBOX";
//$hostname = '{correo.minagricultura.gov.co:143/tls/novalidate-cert/norsh/service=imap/user='. $username.'}INBOX';

echo "<br> --> imap php test...<br>";

$i = @fsockopen('outlook.office365.com', 993, $errno, $errstr, 30);
if ($i){
	echo "<br> --> fsockopen test...<br>  ".$i;
} 

else {
	echo $errno;
	echo $errstr;
}

/*
$i = @imap_open("{outlook.office365.com:993}INBOX", $username, $password, OP_READONLY,1);

if ($i){
	echo "<br> --> connection successful....<br>";
	}
else {
	echo "<br> --> connection to server failed...<br>";
	print_r(imap_errors());
	}
*/
?>
<p>-------</p>
<?
//$inbox = @imap_open($hostname,$username,$password,null,1, array(DISABLE_AUTHENTICATOR=>array('GSSAPI'))) or die('Ha fallado la conexión: ' . imap_last_error());
echo $hostname.' '.$username.' '.$password;
$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Mail: ' . imap_last_error());

$emails = imap_search($inbox,'ALL');

var_dump($emails);

if($emails) {
  
  $salida = '';
  
  foreach($emails as $email_number) {    
	$overview = imap_fetch_overview($inbox,$email_number,0);
//	var_dump($overview);
    $salida.= '<p>'.$email_number.' - Tema: '.$overview[0]->subject;
    $salida.= ' - De: '.$overview[0]->from;	 
	$salida.= '</p>';
  }
  
  echo $salida;

}

imap_close($inbox);

?>
