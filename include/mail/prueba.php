<?php
echo "1\n";
session_start();
echo "2\n";
if (empty($_SESSION['token'])) {
    echo "2 if\n";
    if (function_exists('mcrypt_create_iv')) {
        echo "2-1 if\n";
        $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    } else {
        echo "2-1 else\n";
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}
echo "3\n";
$token = $_SESSION['token'];
echo "4\n";
$ruta_raiz = "../../";
$ADODB_COUNTRECS = false;
require_once ($ruta_raiz . "/include/db/ConnectionHandler.php");
echo $ruta_raiz . "/include/db/ConnectionHandler.php";
echo "5\n";
$_SESSION["idFormulario"] = sha1(microtime(true) . mt_rand(10000, 90000));
echo "6\n";
$db = new ConnectionHandler($ruta_raiz);
echo "7\n";
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
echo "8\n";
?>
