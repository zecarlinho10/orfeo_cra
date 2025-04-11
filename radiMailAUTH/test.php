<?php

require_once("/var/www/html/orfeo/config.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
if (empty($_SESSION['token'])) {
    if (function_exists('mcrypt_create_iv')) {
        $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    } else {
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}
$token = $_SESSION['token'];
/*
foreach ($_GET as $key => $value)  { $_GET[$key] = StringClean($value); }
foreach ($_POST as $key => $value) { $_POST[$key] = StringClean($value); }
*/
$seconds=180;
//ini_set('max_execution_time',300);
ini_set('memory_limit','512M');
set_time_limit(180);
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require_once('ReadMail.class.php');
require_once('RadicarMail.class.php');
?>
