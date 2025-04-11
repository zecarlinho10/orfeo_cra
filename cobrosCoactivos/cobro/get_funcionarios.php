<?php
$ruta_raiz = "../..";
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

$query=$db->query("SELECT U.ID,DEPE_CODI, U.USUA_NOMB, USUA_LOGIN
					FROM USUARIO U, AUTM_MEMBRESIAS M
					WHERE USUA_ESTA = 1 AND M.AUTG_ID = 534 AND U.ID = M.AUTU_ID AND DEPE_CODI IN (12,30)
					ORDER BY USUA_NOMB"");
$usuarios = array();
//while($r=$query->fetch_object()){ $usuarios[]=$r; }
while(!empty($query) && !$query->EOF){ 
	 	$usuarios[$i]=$query->fields; 
		$i++;
		$query->MoveNext ();
	}
//
if(count($usuarios)>0){
print "<option value=''>-- SELECCIONE --</option>";
foreach ($usuarios as $s) {
	print "<option value='$s[0]'>$s[1]</option>";
}
}else{
print "<option value=''>-- NO HAY DATOS --</option>";
}
?>