<?php
session_start();
if (!$_SESSION['dependencia']) header ("Location: ../cerrar_session.php");

require_once('RadicarMail.class.php');

//Instanciamos clase radicar mail
$radiMailObj = new RadicarMail();

$radiMailObj->createTableRadiMail();
?>