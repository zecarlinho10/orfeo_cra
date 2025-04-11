<?php

include __DIR__.'/vendor/autoload.php';
$CLIENT_ID="8a6ea2bd-acd7-4b66-b2cb-8db87eff0378";
$CLIENT_SECRET="m8w8Q~BTXlj3K37WUNAM7jHE-wfxtlYZxw5EdbDd";
$TENANT="02f338c2-5dfa-4ce9-9ed1-2e6f5524cc75";
// refresh token de bentanilla
$REFRESH_TOKEN="0.AVkAwjjzAvpd6Uye0S5vVSTMdb2iborXrGZLssuNuH7_A3hZAD4.AgABAAEAAAD--DLA3VO7QrddgJg7WevrAgDs_wUA9P-5TaKeE6eVODrwyVGENjDy6c2KCgCmVwwhCyUtcIiZS7S7v4jG9PTnlP5sf2cBFEC9B85vju0y-gFg9yUEUcj4GdejIC0cE3fStLveFyHUe4ZcCUTSCp1d-2Q8zU66LSXZbeSWx2XCZwJeHUfgy96sjHX79pb1_1nID51dqcd0SqliUwf-48zKBOhpdCbAElz1TWEhzzDbxTTlHgEYm6OWf_K4ZcxmjK1lIchWpDf0lQRYy388Kv6BmVmdfKDbPQuoJsRLLL68RFgGTIoxUfzlckskNPbDf-2OdYf06gmdaDn1JW76Z0mTXQjAuB35sxE2RaMrPpThQBLXJOAbBDpYiim8s4se1wCRxmESF3p8xXmT2vslUMhG6dIaJETW3Fn-S-qb-DEObzZMhowLV7VFxjIUKGhY7pA04bL6XmERdvIPRGg9C73sEBXqxyz7P8EHQCNPEHPF5k03CuBEzQYlFtJwmZt6M6f3-1pJXyN5xI9AOiK4VOO7CpGag-D33wq0PtyNFMugjP11fixIuAXol4uyYkqVKQQ53FWs_mKCNXvxADvK40RpKk3XposH9O-JlcL1_xXwLlFEnmHRrEzNoP-8AnQlDYzM8seEPkaiS6Uqt0feUPE-u7ntm8Aasxc7VTZ49rShztLq26Sl2mOM8b9u5tGV2k5rZp2p64pQCKqb3Li0eoPsbWRzQm6Obq13cOkgVlAlKqnUyQ1sQt2SND1Ksb9bTh35FgP-WbX7ewwsg_dgYcF8";

/*
$CLIENT_ID="04169537-5af9-4e8b-913c-b2576739fec6";
$CLIENT_SECRET="pBX8Q~pH4NZvP~Gct4sCQpBQRXyXrA1oRi3fAb8m";
$TENANT="0f25ac5d-820e-433e-8460-6c0c6dd4e7e8";
// refresh token de bentanilla
$REFRESH_TOKEN="eyJ0eXAiOiJKV1QiLCJub25jZSI6IncyaVp3Z1dZSWUxM0JySzJ4eHlPandHVFhIem1xbENwQkZGUVRUMHlIVDAiLCJhbGciOiJSUzI1NiIsIng1dCI6Ii1LSTNROW5OUjdiUm9meG1lWm9YcWJIWkdldyIsImtpZCI6Ii1LSTNROW5OUjdiUm9meG1lWm9YcWJIWkdldyJ9.eyJhdWQiOiIwMDAwMDAwMy0wMDAwLTAwMDAtYzAwMC0wMDAwMDAwMDAwMDAiLCJpc3MiOiJodHRwczovL3N0cy53aW5kb3dzLm5ldC8wZjI1YWM1ZC04MjBlLTQzM2UtODQ2MC02YzBjNmRkNGU3ZTgvIiwiaWF0IjoxNjc3ODU3NDUzLCJuYmYiOjE2Nzc4NTc0NTMsImV4cCI6MTY3Nzg2MjAwMiwiYWNjdCI6MCwiYWNyIjoiMSIsImFpbyI6IkFTUUEyLzhUQUFBQXM2dXFSeFhOd00vczlRUlNwWGxVZm5ycmt3Y3U4NDFBWHlmS1RLU0YyZTQ9IiwiYW1yIjpbInB3ZCJdLCJhcHBfZGlzcGxheW5hbWUiOiJyZXNnaXN0ZXJhcGNvcnJlb2NyYSIsImFwcGlkIjoiMDQxNjk1MzctNWFmOS00ZThiLTkxM2MtYjI1NzY3MzlmZWM2IiwiYXBwaWRhY3IiOiIwIiwiZ2l2ZW5fbmFtZSI6ImNvcnJlbyIsImlkdHlwIjoidXNlciIsImlwYWRkciI6IjIwMC4xMjIuMjI5Ljk4IiwibmFtZSI6ImNvcnJlbyIsIm9pZCI6IjgxY2ZkZWIxLWU1ZmEtNDc0Yi04MWI2LWYyMmMyMzJhMDYzOSIsIm9ucHJlbV9zaWQiOiJTLTEtNS0yMS0zNjYyNjYxMDE4LTI5MTg1OTI1OTMtMzM3MjcxNTQ4Mi0xNDkwIiwicGxhdGYiOiIxNCIsInB1aWQiOiIxMDAzQkZGRDlCQTc3RTM3IiwicmgiOiIwLkFWa0FYYXdsRHc2Q1BrT0VZR3dNYmRUbjZBTUFBQUFBQUFBQXdBQUFBQUFBQUFCWkFOUS4iLCJzY3AiOiJHcm91cC5SZWFkLkFsbCBJTUFQLkFjY2Vzc0FzVXNlci5BbGwgVXNlci5SZWFkIFVzZXIuUmVhZC5BbGwgcHJvZmlsZSBvcGVuaWQgZW1haWwiLCJzaWduaW5fc3RhdGUiOlsiaW5rbm93bm50d2siXSwic3ViIjoiLVVmUXppeEt5OFVRRVI5Qm1uUmVRY1RIN0hlUEdVeVQyd29SVUI2M1VlbyIsInRlbmFudF9yZWdpb25fc2NvcGUiOiJTQSIsInRpZCI6IjBmMjVhYzVkLTgyMGUtNDMzZS04NDYwLTZjMGM2ZGQ0ZTdlOCIsInVuaXF1ZV9uYW1lIjoiY29ycmVvQGNyYS5nb3YuY28iLCJ1cG4iOiJjb3JyZW9AY3JhLmdvdi5jbyIsInV0aSI6InF0MVFUcWoyTGthUHRHWjl4UHNnQUEiLCJ2ZXIiOiIxLjAiLCJ3aWRzIjpbImI3OWZiZjRkLTNlZjktNDY4OS04MTQzLTc2YjE5NGU4NTUwOSJdLCJ4bXNfc3QiOnsic3ViIjoiUlVOcFNfWjdlSVZVam9COHBrSU1tMHN5ZG1rM1NwSTJpNnFkcjVRMDliOCJ9LCJ4bXNfdGNkdCI6MTQ3NjgxOTcyNX0.TatxJOdQHMmyQrSWfGBlSbaChBb86OVpmAWL1SXJBwgBtd2VMnrSGO8310efo5eEL9w1AyxRHeoW1VyRajBVL1lOD4slhoILQoc0fLrQkKeUcG4Mxhj3UglusI2d38eqh1F_M7YV3jgtDotjeZVxHPMAj3TAIfcUYB45BRgv__BAOLWYLA3URbiU8HBStEpFvjB5ckz1bAhVIU3aEwJreXLilhu0mx-GXSvIZAMtvM5f81qM6tGtqpfRMmzI8eKgG9MuCzeAGMWKYUCfuhybjKL1UvlGDUjK1tu7vq8sYDGcPyAn1cn6DQBKwXoBbPR1bvOtJGqZy3RujeQS5dgPoQ";
 */
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

		ini_set('display_errors', 1);
		ini_set('display_startup_errors',1);
		error_reporting(E_ALL);

		$username='bentanillaunicaderadicacion@supertransporte.gov.co';
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
