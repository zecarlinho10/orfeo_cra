<?
//$krdOld = $krd;
error_reporting(1);
session_start();

$ruta_raiz = "../..";


if (! $_SESSION['dependencia'] || $_GET['close']) {
    header("Location: $ruta_raiz/login.php");
    echo "<script>parent.frames.location.reload();top.location.reload();</script>";
}

	include "$ruta_raiz/config.php";
	include_once "$ruta_raiz/include/db/ConnectionHandler.php";
    $db = new ConnectionHandler( "$ruta_raiz" );
    if (!defined('ADODB_FETCH_ASSOC'))define('ADODB_FETCH_ASSOC',2);
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    

	
		function reinicia1($db){
			$db->conn->Execute( "DROP SEQUENCE SECR_TP1_900");
			$db->conn->Execute( "CREATE SEQUENCE SECR_TP1_900
								MINVALUE 1
								MAXVALUE 999999
								START WITH 1
								INCREMENT BY 1
								NOCACHE ");

			
			//$db->conn->debug=true;
			
			$db->conn->Execute( "DROP SEQUENCE SECR_TP1_10");
			$db->conn->Execute( "CREATE SEQUENCE SECR_TP1_10
								MINVALUE 1
								MAXVALUE 999999
								START WITH 1
								INCREMENT BY 1
								NOCACHE ");
			$db->conn->Execute( "DROP SEQUENCE SECR_TP1_10");
			//$db->conn->debug=true;
			
			echo "Secuencia de salida (1) reiniciado exitosamente. <br>";
		}

		function reinicia2($db){

			$db->conn->Execute( "DROP SEQUENCE SECR_TP2_321");
			//$db->conn->debug=true;

			$db->conn->Execute( "CREATE SEQUENCE SECR_TP2_321
								MINVALUE 1
								MAXVALUE 999999
								START WITH 1
								INCREMENT BY 1
								NOCACHE ");

			echo "Secuencia de entrada (2) reiniciado exitosamente. <br>";
		}

		function reinicia3($db){
			$db->conn->Execute( "DROP SEQUENCE SECR_TP3_10");
			//$db->conn->debug=true;

			$db->conn->Execute( "CREATE SEQUENCE SECR_TP3_10
								MINVALUE 1
								MAXVALUE 999999
								START WITH 1
								INCREMENT BY 1
								NOCACHE ");
			echo "Secuencia de memos (3) reiniciado exitosamente. <br>";
		}

		function reinicia4($db){
			$db->conn->Execute( "DROP SEQUENCE SECR_TP4_20");
			//$db->conn->debug=true;
			$db->conn->Execute( "CREATE SEQUENCE SECR_TP4_20
								MINVALUE 1
								MAXVALUE 999999
								START WITH 1
								INCREMENT BY 1
								NOCACHE ");
			echo "Secuencia de Contro Interno (4) reiniciado exitosamente. <br>";
		}

		function reinicia5($db){
			$db->conn->Execute( "DROP SEQUENCE SECR_TP5_10");
			//$db->conn->debug=true;

			$db->conn->Execute( "CREATE SEQUENCE SECR_TP5_10
								MINVALUE 1
								MAXVALUE 999999
								START WITH 1
								INCREMENT BY 1
								NOCACHE ");
			echo "Secuencia de Resoluciones (5) reiniciado exitosamente. <br>";
		}

		function reinicia6($db){
			$db->conn->Execute( "DROP SEQUENCE SECR_TP6_10");
			//$db->conn->debug=true;

			$db->conn->Execute( "CREATE SEQUENCE SECR_TP6_10
								MINVALUE 1
								MAXVALUE 999999
								START WITH 1
								INCREMENT BY 1
								NOCACHE ");
			echo "Secuencia de Circulares (6) reiniciado exitosamente. <br>";
		}

		function reinicia7($db){
			$db->conn->Execute( "DROP SEQUENCE SECR_TP7_20");
			//$db->conn->debug=true;

			$db->conn->Execute( "CREATE SEQUENCE SECR_TP7_20
								MINVALUE 1
								MAXVALUE 999999
								START WITH 1
								INCREMENT BY 1
								NOCACHE ");
			echo "Secuencia de Actas (7) reiniciado exitosamente. <br>";
		}

		function reinicia8($db){
			$db->conn->Execute( "DROP SEQUENCE SECR_TP8_10");
			//$db->conn->debug=true;

			$db->conn->Execute( "CREATE SEQUENCE SECR_TP8_10
								MINVALUE 1
								MAXVALUE 999999
								START WITH 1
								INCREMENT BY 1
								NOCACHE ");
			echo "Secuencia de Autos (8) reiniciado exitosamente. <br>";
		}

		function reinicia9($db){
			$db->conn->Execute( "DROP SEQUENCE SECR_TP9_10");
			//$db->conn->debug=true;

			$db->conn->Execute( "CREATE SEQUENCE SECR_TP9_10
								MINVALUE 1
								MAXVALUE 999999
								START WITH 1
								INCREMENT BY 1
								NOCACHE ");
			echo "Secuencia de Contratos (9) reiniciado exitosamente. <br>";
		}

		function reiniciaCarpetas(){
			$errores="";
			if(!mkdir('/BodegaCopia/2020', 0777, true)) {
				$errores.="Fallo al crear la carpeta 2020 <br>";
			}

			if(!mkdir('/BodegaCopia/2020/321', 0777, true)) {
			    $errores.="Fallo al crear la carpeta 321<br>";
			}

			if(!mkdir('/BodegaCopia/2020/321/docs', 0777, true)) {
			    $errores.="Fallo al crear la carpeta 321/docs<br>";
			}

			if(!mkdir('/BodegaCopia/2020/10', 0777, true)) {
			    $errores.="Fallo al crear la carpeta 10<br>";
			}

			if(!mkdir('/BodegaCopia/2020/10/docs', 0777, true)) {
			    $errores.="Fallo al crear la carpeta 10/docs<br>";
			}

			if(!mkdir('/BodegaCopia/2020/11', 0777, true)) {
			    $errores.="Fallo al crear la carpeta 11<br>";
			}

			if(!mkdir('/BodegaCopia/2020/11/docs', 0777, true)) {
			    $errores.="Fallo al crear la carpeta 11/docs<br>";
			}

			if(!mkdir('/BodegaCopia/2020/12', 0777, true)) {
			    $errores.="Fallo al crear la carpeta 12<br>";
			}

			if(!mkdir('/BodegaCopia/2020/12/docs', 0777, true)) {
			    $errores.="Fallo al crear la carpeta 12/docs<br>";
			}

			if(!mkdir('/BodegaCopia/2020/20', 0777, true)) {
			    $errores.="Fallo al crear la carpeta 20<br>";
			}

			if(!mkdir('/BodegaCopia/2020/20/docs', 0777, true)) {
			    $errores.="Fallo al crear la carpeta 20/docs<br>";
			}

			if(!mkdir('/BodegaCopia/2020/30', 0777, true)) {
			    $errores.="Fallo al crear la carpeta 30<br>";
			}

			if(!mkdir('/BodegaCopia/2020/30/docs', 0777, true)) {
			    $errores.="Fallo al crear la carpeta 30/docs<br>";
			}

			if(!mkdir('/BodegaCopia/2020/999', 0777, true)) {
			    $errores.="Fallo al crear la carpeta 999<br>";
			}

			if(!mkdir('/BodegaCopia/2020/999/docs', 0777, true)) {
			    $errores.="Fallo al crear la carpeta 999/docs<br>";
			}

			if(!mkdir('/BodegaCopia/2020/900', 0777, true)) {
			    $errores.="Fallo al crear la carpeta 900<br>";
			}

			if(!mkdir('/BodegaCopia/2020/900/docs', 0777, true)) {
			    $errores.="Fallo al crear la carpeta 900/docs<br>";
			}


			if($errores==""){
				echo "Expedientes creados exitosamente.";
			}
			else{
				echo $errores;
			}
			
		}

	$menu=$_POST['menu'];

	if($menu!=11){
		$hoy = getdate();
		$y = $hoy[year];
		
		$sql="SELECT * FROM RADICADO WHERE RADI_NUME_RADI LIKE '".$y."%'";

		$res=$db->conn->Execute( $sql);
		
		if(!$res->EOF){
			?>
			<script type="text/javascript">  
				alert ("Existen radicados de este año, NO es posible reiniciar secuencias." );
			</script>
			<?
			echo ("Existen radicados de este año, NO es posible reiniciar secuencias.");
		}
		else{
			
			switch ($menu){
				case 1:	reinicia1($db);
						break;
				case 2: reinicia2($db);
						break;
				case 3: reinicia3($db);
						break;
				case 4: reinicia4($db);
						break;
				case 5: reinicia5($db);
						break;
				case 6: reinicia6($db);
						break;
				case 7: reinicia7($db);
						break;
				case 8: reinicia8($db);
						break;
				case 9: reinicia9($db);
						break;
				case 10: reinicia1($db);
						 reinicia2($db);
						 reinicia3($db);
						 reinicia4($db);
						 reinicia5($db);
						 reinicia6($db);
						 reinicia7($db);
						 reinicia8($db);
						 reinicia9($db);
						 break;
			}
		}
	}
	else{
		reiniciaCarpetas();
	}
	
?>