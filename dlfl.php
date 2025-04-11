<?php
session_start();
require("config.php");
$dependencia        = $_SESSION["dependencia"];
$krd = $_SESSION["krd"];
echo $krd;
$url = $_GET["ur"];
$fileArchi= openssl_decrypt(base64_decode($url), $ciphering, $salt);
echo $fileArchi;
var_dump(file_exists($fileArchi));

if (file_exists($fileArchi)) {
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename='.basename($fileArchi));
    header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
       ob_end_flush();

        readfile($fileArchi);
        exit;
  }else {
	  die ("<B><center>  NO se encontro el Archivo  </a><br>");
	  exit;
 }
 



?>
