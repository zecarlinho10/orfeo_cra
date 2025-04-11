<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include_once "expediente.php";

  $unserializado=unserialize($_POST['objXML']);
  $unserializado->generaCuerpo();
  echo $unserializado->radicaActa();
  
?>

