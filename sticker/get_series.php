<?php
$ruta_raiz = "..";
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

$query=$db->query("SELECT DISTINCT S.SGD_SRD_CODIGO, SGD_SRD_DESCRIP FROM SGD_MRD_MATRIRD M, SGD_SRD_SERIESRD S 
					WHERE S.SGD_SRD_CODIGO=M.SGD_SRD_CODIGO AND M.DEPE_CODI=$_GET[dependencia_id] ORDER BY SGD_SRD_DESCRIP");
$vseries = array();

while(!empty($query) && !$query->EOF){ 
 	$vseries[$i]=$query->fields; 
	$i++;
	$query->MoveNext ();
}

if(count($vseries)>0){
	print "<option value=''>-- SELECCIONE --</option>";
	foreach ($vseries as $s) {
		print "<option value='".$s["SGD_SRD_CODIGO"]."'>".$s["SGD_SRD_DESCRIP"]."</option>";
	}
}else{
	print "<option value=''>-- NO HAY DATOS --</option>";
}
?>
