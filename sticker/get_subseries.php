<?php
$ruta_raiz = "..";
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

$query=$db->query("SELECT DISTINCT SS.SGD_SBRD_CODIGO, SS.SGD_SBRD_DESCRIP
					FROM SGD_MRD_MATRIRD M, SGD_SRD_SERIESRD S , SGD_SBRD_SUBSERIERD SS
					WHERE S.SGD_SRD_CODIGO=M.SGD_SRD_CODIGO AND SS.SGD_SRD_CODIGO=S.SGD_SRD_CODIGO AND S.SGD_SRD_CODIGO='$_GET[serie_id]' 
					ORDER BY SGD_SRD_DESCRIP
					");
/*
AND M.DEPE_CODI='$_GET[dependencia_id]'
*/
$vsseries = array();

while(!empty($query) && !$query->EOF){ 
 	$vsseries[$i]=$query->fields; 
	$i++;
	$query->MoveNext ();
}

if(count($vsseries)>0){
	print "<option value=''>-- SELECCIONE --</option>";
	foreach ($vsseries as $s) {
		print "<option value='".$s["SGD_SBRD_CODIGO"]."'>".$s["SGD_SBRD_DESCRIP"]."</option>";
	}
}else{
	print "<option value=''>-- NO HAY DATOS --</option>";
}
?>
