<?php
session_set_cookie_params([
    'SameSite' => 'None',
    'Secure' => true,
    'HttpOnly' => true
]);
session_start();

$iss = $_GET['iss'] ?? null;
$sid = $_GET['sid'] ?? null;

var_dump($_SESSION);

session_unset();
session_destroy();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
http_response_code(200);
exit;
?>