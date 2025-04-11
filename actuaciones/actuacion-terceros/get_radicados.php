
<?php
$ruta_raiz = "../..";
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

$query=$db->query("SELECT R.RADI_NUME_RADI, R.radi_fech_radi, R.RA_ASUN  FROM RADICADO R, USUARIO U 
	WHERE U.USUA_LOGIN='".$_GET["funcionario_id"]."' AND U.USUA_CODI=R.RADI_USUA_ACTU AND R.RADI_DEPE_ACTU=U.DEPE_CODI ORDER BY R.radi_fech_radi");

$radicados = array();

$i=0;
while(!empty($query) && !$query->EOF){ 
 	$radicados[$i]=$query->fields; 
	$i++;
	$query->MoveNext ();
}
	echo $i;
//
if(count($radicados)>0){
	//print "<form action='checkbox.php' method='post'>";
	//foreach ($radicados as $s) { print "<option value='$s[0]'>$s[0] - $s[1] - $s[2]</option>"; }

	foreach($radicados as $s){
		//print "<option value='$s[0]'>$s[0] - $s[1] - $s[2]</option>";	
		//print "<input type='checkbox' name='radicados[]' value='$s[0]'> $s[0] - $s[1] - $s[2]><br>";
		print "
			<tr>
		        	<td><input type='checkbox' name='radicados[]'' value='$s[0]'/></td>
		        	<td>$s[0]</td>
		            <td>$s[1]</td>
		            <td>$s[2]</td>
		    </tr>
      	";
		//print "<input type='checkbox'>ojo<br>";
	}
	//print "<input type='submit'></form>";
}else{
	print "El usuario no tiene radicados para trasladar";
}
//foreach($_POST['colores'] as $color){	echo $color."<br>";};
?>



