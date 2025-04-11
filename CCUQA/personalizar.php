<?php
require './vendor/autoload.php';
use Jumbojett\OpenIDConnectClient;
session_start();

    $oidc = new OpenIDConnectClient('https://qaautenticaciondigital.and.gov.co',
    //'https://localhost:44306',
    'ccucraQA',
    null);
    
$oidc->setRedirectURL('https://gestiondocumental.cra.gov.co/CCUQA/login.php');
$oidc->setCodeChallengeMethod('S256');
$oidc->addScope('co_scope');

if(isset($_GET["type"])){
    if($_GET["type"]=="manage"){
        $oidc->addAuthParam(array('acr_values'=>'action:manage'));
        $oidc->addAuthParam(array('login_hint'=>$_SESSION["userinfo"]->name));
        $oidc->authenticate();
    }
}

?>



