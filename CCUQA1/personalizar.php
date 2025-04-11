<?php
require __DIR__ . '/vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 'On');
use Jumbojett\OpenIDConnectClient;
session_start();

    $oidc = new OpenIDConnectClient('https://qaautenticaciondigital.and.gov.co',
    //'https://localhost:44306',
    'phpDev',
    null);
    
$oidc->setRedirectURL('http://localhost:3000/login.php');
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



