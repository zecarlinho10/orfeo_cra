<?php
$ruta_raiz = "..";
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

$query=$db->query("SELECT usua_login, usua_nomb FROM USUARIO WHERE USUA_ESTA=1 AND DEPE_CODI=$_GET[dependencia_id] ORDER BY usua_nomb");
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