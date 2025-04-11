<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors',1);
error_reporting('E_ALL');
session_start();
if (!$_SESSION['dependencia']) header ("Location: ../cerrar_session.php");

include_once "../seguridad_sql.php";
foreach ($_GET as $key => $value)  { $_GET[$key] = StringClean($value); }
foreach ($_POST as $key => $value) { $_POST[$key] = StringClean($value); }

$ruta_raiz='../';
    
require_once('ManageRadMail.class.php');

//Instanciamos clase
$manageObj = new ManageRadMail();

$form = new stdClass();
$form->process_save   = (isset($_POST['save']))?true:false;
$form->process_delete = (isset($_POST['delete']))?true:false;
$form->process_edit   =  (isset($_POST['edit']))?true:false;

$form->mail_id		= (isset($_POST['mail_id']))?$_POST['mail_id']:'';
$form->mail 		= (isset($_POST['mail']))?$_POST['mail']:'';
$form->pass 		= (isset($_POST['pass']))?$_POST['pass']:'';
$form->dest_alt 	= (isset($_POST['usuaSelect']))?$_POST['usuaSelect']:'';
$form->filter_by_subject = (isset($_POST['filter_by_subject']))?$_POST['filter_by_subject']:'';
$form->mail_host = (isset($_POST['mail_host']))?$_POST['mail_host']:'';

//Check process form
if ($form->process_save && $manageObj->validForm($form)) {

	$manageObj->mail_id 		= $form->mail_id;
	$manageObj->mail 			= $form->mail;
	$manageObj->mail_pass 		= $form->pass;
	$manageObj->mail_dest_alt	= $form->dest_alt;
	$manageObj->mail_subject_filter = $form->filter_by_subject;
	$manageObj->mail_host	= $form->mail_host;
	$manageObj->saveMailParameterized();
}

if ($form->process_edit) {
	$manageObj->mail_id 		= $form->mail_id;
	$manageObj->loadMail();
}

if ($form->process_delete) {
	$manageObj->mail_id 		= $form->mail_id;
	$manageObj->delete();
	$manageObj->mail_id='';
}

//$manageObj->mailList=$manageObj->getListMailRadica();
//$manageObj->getTableMailsParametrized($manageObj->mailList);
//var_dump($manageObj->mailList);

print $manageObj->getTableMailsParameterized();

?>

