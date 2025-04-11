<?php
$ruta_raiz = "../";
set_time_limit(0);
include_once("$ruta_raiz/include/db/ConnectionHandler.php");
 $db = new ConnectionHandler("$ruta_raiz");

$query= "select radi_nume_radi,radi_path from radicado ";
$db->conn->debug=true;
$limit = 100;
$pagina =1; 
error_reporting(E_ALL);
do{
$inicio = ($pagina - 1) * $limit;
$rs=$db->conn->SelectLimit($query,$limit,$inicio);
	if(!empty($rs) && !$rs->EOF){
		$radicado = true;
		while( !$rs->EOF){
			$extension = substr($rs->fields['RADI_PATH'],-3);
		if($extension == 'tif'){
			if(file_exists("../bodega/".str_replace('tif','pdf',$rs->fields['RADI_PATH']))){
				$archivifaltantes =fopen ( "../bodega/migracion/faltantes.txt" ,"a+");
				fwrite( $archivifaltantes," falta actualizar : ".$rs->fields['RADI_NUME_RADI']."\r\n");
				fclose($archivifaltantes);
			}else{
				$archivoNoconver=fopen ( "../bodega/migracion/faltantesconvertir.txt" ,"a+");
				fwrite($archivoNoconver," falta convertir : /BodegaCopia/".$rs->fields['RADI_PATH']."\r\n");
				fclose($archivoNoconver);

			}
		}else if ($extension =="pdf"){

var_dump("../bodega/".str_replace('pdf','tif',$rs->fields['RADI_PATH']));

			if(file_exists("../bodega/".str_replace('pdf','tif',$rs->fields['RADI_PATH']))){
				$archivEliminar=fopen ( "../bodega/migracion/eliminar.txt" ,"a+");
			 fwrite($archivEliminar," rm -rf "."/BodegaCopia/".str_replace('pdf','tif',$rs->fields['RADI_PATH']."\r\n"));
			 fclose($archivEliminar);
			}
		}
			$rs->MoveNext();
		}
	}else{
		$radicado =false;
   	
  }
	
		$pagina++;
	
}while($radicado);
		 







?>
