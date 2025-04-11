<?php
require './vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 'On');
use Jumbojett\OpenIDConnectClient;
session_start() ;
$oidc = new OpenIDConnectClient('https://qaautenticaciondigital.and.gov.co',
                                //'https://localhost:44306',
                                'ccucraQA',
                                null);

$oidc->setRedirectURL('https://gestiondocumental.cra.gov.co/CCUQA/login.php');
$oidc->setCodeChallengeMethod('S256');
$idtoken = $_SESSION["id_token"];

session_destroy();

$oidc->signOut($idtoken,  "https://gestiondocumental.cra.gov.co/CCUQA/index.php");

?>

