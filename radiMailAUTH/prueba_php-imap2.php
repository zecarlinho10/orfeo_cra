<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');  

include __DIR__.'/vendor/autoload.php';

$CLIENT_ID="48f2e123-19d0-4bb2-bb7c-8dedb976d778";
$CLIENT_SECRET="HOk8Q~-B-aHeGs5uqFmzcAsYIHrMUhzCdwxWEc2j";
$TENANT="02f338c2-5dfa-4ce9-9ed1-2e6f5524cc75";
// refresh token de bentanilla
$REFRESH_TOKEN="eyJ0eXAiOiJKV1QiLCJub25jZSI6IjREWmk1YjZBMFBmNkw2al9OczQ3ZTVmbHA4N2k5THN6Rnp1RW4xRWlvYUEiLCJhbGciOiJSUzI1NiIsIng1dCI6Ii1LSTNROW5OUjdiUm9meG1lWm9YcWJIWkdldyIsImtpZCI6Ii1LSTNROW5OUjdiUm9meG1lWm9YcWJIWkdldyJ9.eyJhdWQiOiIwMDAwMDAwMy0wMDAwLTAwMDAtYzAwMC0wMDAwMDAwMDAwMDAiLCJpc3MiOiJodHRwczovL3N0cy53aW5kb3dzLm5ldC8wMmYzMzhjMi01ZGZhLTRjZTktOWVkMS0yZTZmNTUyNGNjNzUvIiwiaWF0IjoxNjgwNjE4ODU3LCJuYmYiOjE2ODA2MTg4NTcsImV4cCI6MTY4MDYyNDAyMSwiYWNjdCI6MCwiYWNyIjoiMSIsImFpbyI6IkUyWmdZSmp4N1lvNnR4SHJ5OERsM1VFcUtVbm5FaFUrcnJIMU1jaWJuREhYUVN2Vi9EOEEiLCJhbXIiOlsicHdkIl0sImFwcF9kaXNwbGF5bmFtZSI6IkltYXAvUG9wIE9BdXRoIFNlcnZpY2UiLCJhcHBpZCI6IjQ4ZjJlMTIzLTE5ZDAtNGJiMi1iYjdjLThkZWRiOTc2ZDc3OCIsImFwcGlkYWNyIjoiMCIsImZhbWlseV9uYW1lIjoiUFJVRUJBUyIsImdpdmVuX25hbWUiOiJWVVIiLCJpZHR5cCI6InVzZXIiLCJpcGFkZHIiOiIyMDAuMTIyLjIyOS45OCIsIm5hbWUiOiJWVVIgUFJVRUJBUyIsIm9pZCI6ImQ1ZWU5NmRjLWQ2OTYtNGYzZS05ZmQ1LThhMmYyYzkxMGI5MSIsIm9ucHJlbV9zaWQiOiJTLTEtNS0yMS04OTA1OTUzNTEtMjMyNTA3MDA0NC0xMzQ0OTk5NjYyLTY0NjQwIiwicGxhdGYiOiIxNCIsInB1aWQiOiIxMDAzMjAwMjZBQjcxQkM0IiwicmgiOiIwLkFWa0F3amp6QXZwZDZVeWUwUzV2VlNUTWRRTUFBQUFBQUFBQXdBQUFBQUFBQUFCWkFENC4iLCJzY3AiOiJHcm91cC5SZWFkLkFsbCBJTUFQLkFjY2Vzc0FzVXNlci5BbGwgTWFpbC5SZWFkIE1haWwuU2VuZCBNYWlsYm94U2V0dGluZ3MuUmVhZCBvcGVuaWQgcHJvZmlsZSBVc2VyLlJlYWQgVXNlci5SZWFkLkFsbCBlbWFpbCIsInNpZ25pbl9zdGF0ZSI6WyJrbXNpIl0sInN1YiI6IlIwbF9lblVPc3ZkLXVHQTFzVTVuYzdnMlRkMm1UTGY3bzQ1bXJyZWFXQjAiLCJ0ZW5hbnRfcmVnaW9uX3Njb3BlIjoiU0EiLCJ0aWQiOiIwMmYzMzhjMi01ZGZhLTRjZTktOWVkMS0yZTZmNTUyNGNjNzUiLCJ1bmlxdWVfbmFtZSI6ImJlbnRhbmlsbGF1bmljYWRlcmFkaWNhY2lvbkBzdXBlcnRyYW5zcG9ydGUuZ292LmNvIiwidXBuIjoiYmVudGFuaWxsYXVuaWNhZGVyYWRpY2FjaW9uQHN1cGVydHJhbnNwb3J0ZS5nb3YuY28iLCJ1dGkiOiJocWhXNGQ0cWZFeXFNY0JUQWVyWUFBIiwidmVyIjoiMS4wIiwid2lkcyI6WyJiNzlmYmY0ZC0zZWY5LTQ2ODktODE0My03NmIxOTRlODU1MDkiXSwieG1zX3N0Ijp7InN1YiI6Inl3V0ZrZ08yQjNqWnJTemFuSFRUMkRlRXQ2VlRfZVlwdWtDSW92NGlQX00ifSwieG1zX3RjZHQiOjE0Nzg3MDU3NDZ9.i2TU3_3gwUcBI3fGz0hBYbnJ06ks4Vr1lQsWolT02Bm3R8cQ3dVHVWolzl1fnyfS7r8NwD3eWW_yTCMb3uqOGchyUEGgfMaJcim0CPFUZ72paryvJetbqigRaCM4r-E1kQDhqLaI4ODmlL2m82WewJMYZQx2Z4osn5Mb2d96KN9hU6LQ5BdBGi-i6NJrXtWdh7ffQh2_UC-qWYID8XvLciXPTcsLUWfSJxhFJIrT3o41tGdsA1kWu-6bfBWqOvgRwqs22JibgPviHlFJF3jhOArcJdBerX7RKe4LzVf4S1sX_GHGTG6fQ3yERLQBTRPky8uTYBQ5IOYYozzA-CJr1Q";

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

echo("Trying to get the token.... <br>");

if(!empty($oResult)){
    
    echo("Connecting to the mail box... <br>");
    
    //The token is a JSON object
    $array_php_resul = json_decode($oResult,true);
    $access_token = $array_php_resul["access_token"];
    if( !empty($access_token) ) {

		session_start();
		//if (!$_SESSION['dependencia']) header ("Location: ../cerrar_session.php");

		$username='bentanillaunicaderadicacion@supertransporte.gov.co';
		//$username = 'v@supertransporte.gov.co';
		//$password = 'C0l0mb142020**';Pru3b452023*
		$password = 'Pru3b452023*';
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
		
		echo '<p>-------</p>';
		
		//$inbox = @imap_open($hostname,$username,$password,null,1, array(DISABLE_AUTHENTICATOR=>array('GSSAPI'))) or die('Ha fallado la conexión: ' . imap_last_error());
		//echo $hostname.' '.$username.' '.$password;
		$inbox = imap2_open($hostname, $username, $access_token, OP_XOAUTH2) or die('Cannot connect to Mail: ' . imap2_last_error());

		$emails = imap2_search($inbox,'ALL');

		//var_dump($emails);

		if($emails) {
		
			$salida = '';
			
			foreach($emails as $email_number) {    
				$overview = imap2_fetch_overview($inbox,$email_number,0);
			//	var_dump($overview);
				$salida.= '<p>'.$email_number.' - Tema: '.$overview[0]->subject;
				$salida.= ' - De: '.$overview[0]->from;	 
				$salida.= '</p>';
				/*
				if ($email_number > 19) {
					break;
				}
				*/
			}
			
			echo $salida;

		}

		imap2_close($inbox);
	}
}
?>