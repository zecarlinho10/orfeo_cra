<?php

session_start();
define('ADODB_ASSOC_CASE', 2);

include_once "../include/db/ConnectionHandler.php";
require_once("../class_control/Mensaje.php");
if (!$db) $db = new ConnectionHandler("..");
//$db->conn->debug = true;
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

//recuperamos con defecto.
if($_GET['recover_new'])
{
	/* TRAER LA IMAGEN DEL RADICADO
$recover_new = $_GET['recover_new'];

$sqli= "select radi_nume_radi from sgd_dir_drecciones where radi_nume_radi = '$recover_new'";
		$rs = $db->conn->Execute($sqli);
		while(!empty($rs) && !$rs->EOF){$radi_recover = $rs->fields["radi_nume_radi"]; $rs->MoveNext();}

		if(!$radi_recover==''){
			echo "<br><br>Advertencia -> El radicado que trata de recuperar Ya existe en SGD_DIR_DRECCIONES";
		}else{
			
		echo $sqli = "INSERT INTO SGD_DIR_DRECCIONES ( SGD_DIR_CODIGO,SGD_DIR_TIPO,RADI_NUME_RADI,MUNI_CODI,DPTO_CODI,SGD_DIR_DIRECCION,SGD_DIR_MAIL,SGD_SEC_CODIGO,SGD_DIR_NOMBRE,SGD_DIR_NOMREMDES,SGD_TRD_CODIGO,SGD_DIR_DOC,ID_PAIS,ID_CONT,SGD_CIU_CODIGO) values ( SEC_DIR_DRECCIONES.NEXTVAL,1,$recover_new,1,11,'Por Definir','por definir',0,'Por Definir','Por Definir',6,'',170,1,49690);";
		$rs = $db->conn->Execute($sqli);

			if($rs){
				$varCONTROL2 = 1;
				$mensaje = "El radicado No. $recover_new Fue recuperado con exito";		
			}else{
				$varCONTROL = 3;
				$mensaje = "Ocurrio un Error durante la recuperacion del radicado";		
			}
		}
		*/
}
//Para colocar en el input el radicado que llega por POST
//if (isset($_POST['radicado'])) {if ($_POST['radicado']>1) {	$valueRadicado = $_POST['radicado'];}else{$valueRadicado ="";}}else{$valueRadicado = "";}

?>

<html>
<head>
</head>

<form action="recuperar_radicado.php" method="post">
 <p>Radicado: <input type="text" name="radicado" value = <? echo $valueRadicado ?> ></p>
  <p><input type="submit" value ="Recuperar" /></p>
</form>

<body>
<?
//$isql="insert into autg_grupos (nombre,descripcion) values ('--láááaaa','señor')";
//$isql=utf8_decode(utf8_encode ($isql));
?>
<?
if (!isset($_POST['radicado'])) {
	$Mensaje = "";
}else{
	if (!$_POST['radicado']>1) {
		$Mensaje ="";
	}else{
		$numRadicado = $_POST['radicado'];
		echo "<p>Recuperar el radicado No. $numRadicado </p>";
		
		//Consulta en donde traigo todos los radicados que no aparecen en SGD_DIR_DRECCIONES
		$sqli = "SELECT R.radi_nume_radi FROM  sgd_dir_drecciones S	RIGHT JOIN RADICADO R ON S.radi_nume_radi=R.radi_nume_radi	WHERE S.radi_nume_radi IS NULL AND R.radi_fech_radi >= TO_DATE('241114','DDMMYY') order by R.radi_fech_radi asc"; 


		$rs = $db->conn->Execute($sqli);
		$i=0;
		//Creo un arreglo con los radicados que no aparecen en SGD_DIR_DRECCIONES
		$arreglo_de_radicados = array();

		while(!empty($rs) && !$rs->EOF){
			$i=$i+1;
			$arreglo_de_radicados[$i] = $rs->fields["RADI_NUME_RADI"]; 
			$rs->MoveNext();
		}
		//echo $i;
		if (in_array($numRadicado, $arreglo_de_radicados)) {
			$varCONTROL = 0;
			//echo "El radicado esta perdido";
			//RECUPERO EL RADICADO
			//CONSTRUYO EL SQL PARA BUSCAR EL RADICADO //BUSCO EL RADICADO EN SGD_AUDITORIA CON ALGUNOS PARAMETROS
			$sqli= "select ISQL from sgd_auditoria where isql like '%$numRadicado%' and tipo = 'i' and tabla = 'SGD_DIR_DRECCIONES' AND ROWNUM <= 1 ORDER BY FECHA asc";
			$rs = $db->conn->Execute($sqli);
			
			while(!empty($rs) && !$rs->EOF){$sqli_recover = $rs->fields["ISQL"]; $rs->MoveNext();}
			if ($sqli_recover==''){
				$varCONTROL = 2;
				$mensaje ="No se pudo recuperar el sql de auditoria";	
				?>
				<p>Desea recuperarlo con registros por defecto ?</p>	
				<form action="recuperar_radicado.php?recover_new=<? echo $numRadicado; ?>" method="post">
				<p><input type="submit" value ="Recuperar con registros por defecto" /></p>
				</form>
				<?
			}else{
				//Obtengo solo el insert, le quito la fecha
				$sqli_recover = substr($sqli_recover,21);
				//Reemplazo todas las apariciones de caracteres extraños
				$sqli_recover = str_replace("^","'",$sqli_recover);	
				//echo "------------------<br><br><br>---------------------";
				//CON ESTE FOR, BUSCO LAS POSISIONES DONDE QUEDA EN LA CONSULTA EL CODIGO QUE DEBE IR UNA SECUENCIA
				$auxiliar = 0;
				$auxiliar2 = 0; //despues que llege a 24, es la posicion o la cadena que necesitamos.
				$auxiliar3 = 0;
				$a=0;
				$b=0;
				for ($j=0; $j < 400 ; $j++) { 
					//pos: posiscion de las comas que encuentra despues del value
					$pos = strpos($sqli_recover,',',$auxiliar);
					$auxiliar = $pos+1;
					$auxiliar2 = $auxiliar2 + 1;
 					//Encuentro la posisiòn en donde comienza la varialbe que estamos buscando
					if ($auxiliar2 == 24 or $auxiliar2 == 25 ) {
						if ($auxiliar2 == 24) {$pos_primer=$pos+1;}
						if ($a==0) {
							$a = $pos;
						}else{
							$b= $pos;
							$tamano = ($b-$a)-1;
						}
					}//fin-if
				}//fin-for
				
				$character = substr($sqli_recover, $pos_primer,$tamano);
				$sqli_recover = str_replace(",$character,",",SEC_DIR_DRECCIONES.NEXTVAL,",$sqli_recover);	

				$sqli = $sqli_recover;
				//Inserto en SGD_DIR_DRECCIONES
				$rs = $db->conn->Execute($sqli);

				if($rs){
					$varCONTROL2 = 1;
					$mensaje = "El radicado Fue recuperado con exito";		
				}else{
					$varCONTROL = 3;
					$mensaje = "Ocurrio un Error durante la recuperacion del radicado SGD_DIR_DRECCIONES";		
				}

			}
		}else{
			$varCONTROL = 1;
			$mensaje = "El radicado NO existe o No esta perdido";
		}

	}
}

if($varCONTROL>0){
	if ($varCONTROL==1) {
		$sqli= "select radi_nume_radi from radicado where radi_nume_radi = '$numRadicado'";
		$rs = $db->conn->Execute($sqli);
		while(!empty($rs) && !$rs->EOF){$radi_recover = $rs->fields["radi_nume_radi"]; $rs->MoveNext();}
		if($radi_recover!=''){
			echo "<br><br>Error -> El radicado que trata de recuperar NO existe.";
		}else{
			echo "<br><br>Advertencia -> El radicado ya se recuperò, o no esta perdido";
		}
	}else{
	echo "<br><br><h3 style='color:red' >Error -> ".$mensaje."</h3>";		
	}
 
}

if($varCONTROL2>0){
 echo "<h3 style='color:green' ><br><br>Success -> ".$mensaje."</h3>";	
}
?>

</body>

</html>


