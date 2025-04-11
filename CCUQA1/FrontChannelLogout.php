<?php
session_set_cookie_params(['SameSite'=>'None','Secure'=>true]);
session_start();

$iss = $_GET['iss'];
$sid = $_GET['sid'];
var_dump($_SESSION);
if($sid == $_SESSION['sid'])
{
    //Cierre de sesión local
    unset($_SESSION["id_token"]);
    unset($_SESSION['payload']);
    unset($_SESSION["userinfo"]);
    unset($_SESSION["accesstoken"]);
    unset($_SESSION["sid"]);
}

header("Cache-Control: no-cache, no-store");
header("Pragma: no-cache");
http_response_code(200);


?>


