<?php
 	session_start();	
	$nomfile="orfeoReport-".date("Y-m-d").".xls"; 	
	header("Content-type: application/vnd.ms-excel; name='excel'");
	header("Content-Disposition: filename=\"$nomfile\";");
	include (realpath(dirname(__FILE__) . "/../")."/include/class/adodb/adodb-basedoc.inc.php"); 
?>
