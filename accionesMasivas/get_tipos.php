<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


$ruta_raiz = "..";
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

$idDep = $_GET['dependencia_id'];
$idSerie = $_GET['series_id'];
$idSubSerie = $_GET['subseries_id'];

$sql="SELECT M.SGD_MRD_CODIGO, T.SGD_TPR_CODIGO, T.SGD_TPR_DESCRIP
FROM SGD_MRD_MATRIRD M, SGD_TPR_TPDCUMENTO T
WHERE M.SGD_TPR_CODIGO = T.SGD_TPR_CODIGO AND 
	   M.SGD_MRD_FECHFIN > SYSDATE AND M.DEPE_CODI = $idDep AND M.SGD_SRD_CODIGO = $idSerie AND M.SGD_SBRD_CODIGO = $idSubSerie
ORDER BY T.SGD_TPR_DESCRIP";

//print $sql;
//echo '<script language="javascript">alert("'.$sql.'");</script>';
$query=$db->query($sql);

$series = array();
//while($r=$query->fetch_object()){ $usuarios[]=$r; }
while(!empty($query) && !$query->EOF){ 
 	$series[$i]=$query->fields; 
	$i++;
	$query->MoveNext ();
}
//
//print(count($series));
if(count($series)>0){
	print "<option value=''>-- SELECCIONE --</option>";
	foreach ($series as $s) {
		print "<option value='".$s["SGD_TPR_CODIGO"]."-".$s["SGD_MRD_CODIGO"]."'>".$s["SGD_TPR_DESCRIP"]."</option>";
	}
}else{
	print "<option value=''>-- NO HAY DATOS --</option>";
}
?>
