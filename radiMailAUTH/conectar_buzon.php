<?php

include __DIR__.'/vendor/autoload.php';

$CLIENT_ID="8a6ea2bd-acd7-4b66-b2cb-8db87eff0378";
$CLIENT_SECRET="m8w8Q~BTXlj3K37WUNAM7jHE-wfxtlYZxw5EdbDd";
$TENANT="02f338c2-5dfa-4ce9-9ed1-2e6f5524cc75";
// refresh token de bentanilla
$REFRESH_TOKEN="0.AVkAwjjzAvpd6Uye0S5vVSTMdb2iborXrGZLssuNuH7_A3hZAD4.AgABAAEAAAD--DLA3VO7QrddgJg7WevrAgDs_wUA9P-5TaKeE6eVODrwyVGENjDy6c2KCgCmVwwhCyUtcIiZS7S7v4jG9PTnlP5sf2cBFEC9B85vju0y-gFg9yUEUcj4GdejIC0cE3fStLveFyHUe4ZcCUTSCp1d-2Q8zU66LSXZbeSWx2XCZwJeHUfgy96sjHX79pb1_1nID51dqcd0SqliUwf-48zKBOhpdCbAElz1TWEhzzDbxTTlHgEYm6OWf_K4ZcxmjK1lIchWpDf0lQRYy388Kv6BmVmdfKDbPQuoJsRLLL68RFgGTIoxUfzlckskNPbDf-2OdYf06gmdaDn1JW76Z0mTXQjAuB35sxE2RaMrPpThQBLXJOAbBDpYiim8s4se1wCRxmESF3p8xXmT2vslUMhG6dIaJETW3Fn-S-qb-DEObzZMhowLV7VFxjIUKGhY7pA04bL6XmERdvIPRGg9C73sEBXqxyz7P8EHQCNPEHPF5k03CuBEzQYlFtJwmZt6M6f3-1pJXyN5xI9AOiK4VOO7CpGag-D33wq0PtyNFMugjP11fixIuAXol4uyYkqVKQQ53FWs_mKCNXvxADvK40RpKk3XposH9O-JlcL1_xXwLlFEnmHRrEzNoP-8AnQlDYzM8seEPkaiS6Uqt0feUPE-u7ntm8Aasxc7VTZ49rShztLq26Sl2mOM8b9u5tGV2k5rZp2p64pQCKqb3Li0eoPsbWRzQm6Obq13cOkgVlAlKqnUyQ1sQt2SND1Ksb9bTh35FgP-WbX7ewwsg_dgYcF8";

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

echo("Trying to get the token.... \n");

if(!empty($oResult)){
    
    echo("Connecting to the mail box... \n");
    
    //The token is a JSON object
    $array_php_resul = json_decode($oResult,true);
    $access_token = $array_php_resul["access_token"];
    if( !empty($access_token) ){

        $mailbox = '{outlook.office365.com:993/imap/ssl}';
        $username = 'bentanillaunicaderadicacion@supertransporte.gov.co';

        $inbox = imap2_open($mailbox, $username, $access_token, OP_XOAUTH2);

        if (! $inbox) {
            error_log(imap2_last_error());
            echo("Unable to open the INBOX \n");
        }
        else {
            echo 'Conectado !!! ';

            $emails = imap2_search($inbox,'ALL');

            //var_dump($emails);

            
            if($emails) {
            
                $salida = '';
                
                foreach($emails as $email_number) {    
                    $overview = imap2_fetch_overview($inbox,$email_number,0);
                	//var_dump($overview);
                    $salida.= '<p>'.$email_number.' - Tema: '.$overview[0]->subject;
                    $salida.= ' - De: '.$overview[0]->from;	 
                    $salida.= '</p>';
                    if ($email_number > 9) {
                        break;
                    }
                    
                }
                
                echo $salida;

            }
            

            imap2_close($inbox);

        }
        
    }
    else {
        echo 'No se pudo obtener el Token';
    }
}

?>