<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/config.php";




$instance = new ClientManager();
        $this->client = $instance->make([
            'host' => "outlook.office365.com",
            'port' => 993,
            'encryption' => 'ssl',
            'validate_cert' => false,
            'username' => $this->mailboxUsername,
            'password' => $access_token,
            'protocol' => 'imap',
            'authentication' => "oauth",
        ]);
    try {
        //  Connect to the IMAP Server
        $this->client->connect();
    }catch (Exception $e) {
        echo 'Exception : ',  $e->getMessage(), "\n";
    }

function getAccessTokenUsingRefreshToken()
    {
        $CLIENT_ID      = $this->azure_settings['client_id'];
        $CLIENT_SECRET  = $this->azure_settings['secret_value'];
        $TENANT         = $this->azure_settings['tenant_id'];
        $REFRESH_TOKEN  = $this->azure_settings['refresh_token'];

        $url = "https://login.microsoftonline.com/$TENANT/oauth2/v2.0/token";

        $param_post_curl = [
            'client_id'     => $CLIENT_ID,
            'client_secret' => $CLIENT_SECRET,
            'refresh_token' => $REFRESH_TOKEN,
            'grant_type'    => 'refresh_token'
        ];

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($param_post_curl));
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        //  ONLY USE CURLOPT_SSL_VERIFYPEER AT FALSE IF YOU ARE IN LOCALHOST !!!
        //  NOT IN LOCALHOST ? ERASE IT !
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        $access_token = json_decode($result)->access_token;

        return $access_token;
    }


?>


