<?php
require __DIR__ . '/vendor/autoload.php';
require 'includes/config.php';
require 'includes/functions.php';
use Jumbojett\OpenIDConnectClient;
session_set_cookie_params(['SameSite'=>'None','Secure'=>true]);
session_start();

$oidc = new OpenIDConnectClient('https://qaautenticaciondigital.and.gov.co',
    //'https://localhost:44306',
    'ccucraQA',
    null);

$oidc->setRedirectURL('https://gestiondocumental.cra.gov.co/CCUQA1/login.php');
$oidc->setCodeChallengeMethod('S256');
$oidc->addScope('co_scope');
$oidc->addScope('openid');
if(isset($_GET["type"])){
    $oidc->addAuthParam(array('acr_values'=>'action:'. $_GET["type"]));
}
$oidc->authenticate();
$userInfo= $oidc->requestUserInfo();
$nameJson = json_encode($userInfo);
$idtoken = $oidc->getIdToken();
$accessToken = $oidc->getAccessToken();
if($_REQUEST['code']){
$_SESSION["code"] = $_REQUEST['code'];
}
if($_REQUEST['scope']){
$_SESSION["scope"] = $_REQUEST['scope'];
}
if($_REQUEST['state']){
$_SESSION["state"] = $_REQUEST['state'];
}
if($_REQUEST['session_state']){
$_SESSION["session_state"] = $_REQUEST['session_state'];
}
$_SESSION["id_token"] = $idtoken;
$_SESSION["accesstoken"] = $accessToken;
$_SESSION["userinfo"] = $userInfo;
$_SESSION["payload"] = json_decode(json_encode($oidc->getIdTokenPayload($oidc->getIdToken())),true);
$_SESSION["sid"] = $_SESSION['payload']['sid'];

Header('Location: '.'index.php' );

?>


