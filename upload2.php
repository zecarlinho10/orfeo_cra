<?php
session_start();
$ruta_raiz = ".";
if (!$_SESSION['dependencia'])
  header ("Location: $ruta_raiz/cerrar_session.php");

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

$krd         = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc    = $_SESSION["usua_doc"];
$codusuario  = $_SESSION["codusuario"];
$tpNumRad    = $_SESSION["tpNumRad"];
$tpPerRad    = $_SESSION["tpPerRad"];
$tpDescRad   = $_SESSION["tpDescRad"];
$tip3Nombre  = $_SESSION["tip3Nombre"];
$dependencia = $_SESSION["dependencia"];
$ln          = $_SESSION["digitosDependencia"];
$lnr         = 11+$ln;
//agregado david
$radicado_rem=1;
//Comprobamos si llega del post grabar usuario.
//var_dump(is_null($_POST['_tpradicu'])); 
//echo "->".$_POST['_tpradicu']; 
//echo "->".$_GET['tpradicu']; exit;
if ($_POST['_tpradicu'] != "" ){
	$_GET['tpradic'] = $_POST['_tpradicu'];
	$_POST['tpradic'] = $_POST['_tpradicu'];
}else{
	if ($_GET['tpradicu']){
		$_GET['tpradic'] = $_GET['tpradicu'];
		$_POST['tpradic'] = $_GET['tpradicu'];
	}
}

if($_POST['tpradic'] == 0 or $_GET['tpradic'] == 0){
$NO_RADICAR = true;
}

$url_carpeta = $_POST['url_carpeta'];

//echo "POST->".$_POST['tpradic'];
//echo "<br> GET->".$_GET['tpradic']; exit;

    /** * Retorna la cantidad de bytes de una expresion como 7M, 4G u 8K.
     *
     * @param char $var
     * @return numeric
     */
if (!function_exists("return_bytes")){
    // Retorna la cantidad de bytes de una expresion como 7M, 4G u 8K.
    function return_bytes($val){
        $val = trim($val);
        $ultimo = strtolower($val[strlen($val)-1]);
        switch($ultimo){
            // El modificador 'G' se encuentra disponible desde PHP 5.1.0
        case 'g': $val *= 1024;
        case 'm': $val *= 1024;
        case 'k': $val *= 1024;
        }
        return $val;
    }
}
 
    $fechaHoy = Date("Y-m-d");

    include_once("$ruta_raiz/class_control/anexo.php");
    include_once("$ruta_raiz/class_control/anex_tipo.php");

	//incluimos ruta para las transacciones.
    include("$ruta_raiz/include/tx/Tx.php"); 	

    if (empty($db))	$db = new ConnectionHandler($ruta_raiz);

    $hist      = new Historico($db);

    $sqlFechaHoy= $db->conn->OffsetDate(0,$db->conn->sysTimeStamp);
    $anex       =  new Anexo($db);
    $anexTip    =  new Anex_tipo($db);
    if (!$aplinteg)
        $aplinteg='null';
if (!$tpradic)
	$tpradic='null';
	if(!$cc){

		$nuevo = ($codigo)? 'no' : 'si';

		$auxsololect = ($sololect)? 'S' : 'N';
		//$db->conn->BeginTrans();
		if($nuevo_archivo==true){
			$auxnumero=$anex->obtenerMaximoNumeroAnexo($numrad);
			do {
				$auxnumero++;
				$codigo = trim($numrad).trim(str_pad($auxnumero,5,"0",STR_PAD_LEFT));
			}while ($anex->existeAnexo($codigo));
		} else {
			$bien = true;
			$auxnumero=substr($codigo,-4);
			$codigo = trim($numrad).trim(str_pad($auxnumero,5,"0",STR_PAD_LEFT));
		}
		//Esta variable es para actualizar las copias
		$codigo_copias=substr($codigo, 0, -1);


		$anex_salida = empty($radicado_salida)? 0 : 1;
		 //echo "<script type='text/javascript'>alert('$tipo');</script>";
		if($tipo<>24 && $tipo<>14){
			$anex_salida=0;
			$tpradic='null';
		}
		$bien = "si";
		if ($bien and $tipo){	
			$anexTip->anex_tipo_codigo($tipo);

			$ext=$anexTip->get_anex_tipo_ext();
			$ext = strtolower($ext);
			$auxnumero = str_pad($auxnumero,5,"0",STR_PAD_LEFT);
			$archivo=trim($numrad."_".$auxnumero.".".$ext);
			$archivoconversion=trim("1").trim(trim($numrad)."_".trim($auxnumero).".".trim($ext));
		}
		if(!$radicado_rem)
			$radicado_rem=7;

		$radicado_rem_p = 1;
		//CARLOS RICAURTE IF PARA DIFREENCIAR URL DE ADJUNTO
		if($url_carpeta){
			$tamano = 0;
			$archivoconversion=$url_carpeta;
			$tipo=43;
		}
		else{
			$tamano = ($_FILES['userfile1']['size'])? ($_FILES['userfile1']['size']/1000) : 0;	
		}
		
		if ($nuevo_archivo == true) {

			include "$ruta_raiz/include/query/queryUpload2.php";

			$expAnexo = ($expIncluidoAnexo)? $expIncluidoAnexo : null;


			$isql = "insert
				into anexos
				(sgd_rem_destino
				 ,anex_radi_nume
				 ,anex_codigo
				 ,anex_tipo
				 ,anex_tamano   
				 ,anex_solo_lect
				 ,anex_creador
				 ,anex_desc
				 ,anex_numero
				 ,anex_nomb_archivo   
				 ,anex_borrado
				 ,anex_salida 
				 ,sgd_dir_tipo
				 ,anex_depe_creador
				 ,sgd_tpr_codigo
				 ,anex_fech_anex
				 ,SGD_APLI_CODI
				 ,SGD_TRAD_CODIGO
				 ,SGD_EXP_NUMERO)
				values (
						$radicado_rem_p  
						,$numrad         
						,$codigo    
						,$tipo    
						,$tamano     
						,'$auxsololect'
						,'$krd'     
						,'$descr' 
						,$auxnumero 
						,'$archivoconversion'
						,'N'         
						,$anex_salida
						,$radicado_rem
						,$dependencia
						,0
						,$sqlFechaHoy
						,$aplinteg    
						,$tpradic
						,'$expAnexo')";
			$nuevo_archivo = false;

			$subir_archivo = true;
			if($url_carpeta){
				$subir_archivo = false;
			}

			//Personalizo el codigo de transaccion y el comentario
			$TX_CODIGO = 91;
			$TX_COMENTARIO = "Archivo Anexo No. $codigo ";

		}else{
			$nuevo_archivo = false;
			$subir_archivo = ($_FILES['userfile1']['size'])? "   anex_nomb_archivo  ='1$archivo'
				,anex_tamano       = $tamano
				,anex_tipo         = $tipo, " : 0;

			if ($subir_archivo){
				$datosModificar=",anex_nomb_archivo  ='1$archivo',anex_tamano=$tamano,anex_tipo=$tipo";
			}  
			$isql = "update 
				anexos set
				anex_salida=$anex_salida
				, sgd_rem_destino=$radicado_rem_p
				, sgd_dir_tipo=$radicado_rem
				$datosModificar
				, anex_desc='$descr'
				, SGD_TRAD_CODIGO = $tpradic
				, SGD_APLI_CODI = $aplinteg  
				where 
				anex_codigo= '$codigo'";

			//Personalizo el codigo de transaccion y el comentario
			$TX_CODIGO = 92;
			$TX_COMENTARIO = "Modificación del anexo No.$codigo ";
		}

		$_POST['subir_archivo'] = $subir_archivo;
		$_POST['nuevo_archivo'] = $nuevo_archivo;
		$_POST['codigo']        = $codigo;
		$bien = $db->conn->query($isql);
	
	//Si actualizo BD correctamente 
	if ($bien){
             $respUpdate="OK";
             $bien2 = false;
             if ($subir_archivo){	
                 $directorio        = BODEGA."/".substr(trim($archivo),0,4)."/".intval(substr(trim($archivo),4,$ln))."/docs/";
            //     echo "directorio ". $directorio ;
                 $userfile1_Temp    = $_FILES['userfile1']['tmp_name'];
                 $bien2             = move_uploaded_file($userfile1_Temp,$directorio.trim(strtolower($archivoconversion)));
                 
                 //Si intento anexar archivo y Subio correctamente
                 if ($bien2){
		$resp1="OK";
		 $_GET['tpradic'] = $_POST['tpradic'];
		 //$db->conn->CommitTrans();
		 //SE EJECUTO BIEN LA CONSULTA Y SUBIO CORRECTAMENTE EL ARCHIVO, SEA MODIFICADO O NUEVO

		/*CONTROL DE VERSIONES - TRAZABILIDAD  */
		 /*Insertar en el historico cuando se inserta un anexo como nuevo*/
		 //		echo  $numrad." / ".$dependencia ." / ". $codusuario." /  0 /  0 / ".$TX_COMENTARIO." / ".$TX_CODIGO ; exit;
		 $_numrad[0]=$numrad;		
		 $hist->insertarHistorico( $_numrad, $dependencia , $codusuario, $dependencia, $codusuario,$TX_COMENTARIO,$TX_CODIGO);

                 }else{ 
                  $resp1="ERROR";
                   //  $db->conn->RollbackTrans();
                 }
             }else {
                 //$db->conn->CommitTrans();
             }
         }else{
            //$db->conn->RollbackTrans();
         }
/*	$usuarios_informados = explode("-",$_POST['arrayusuario'); 
	//Actualizar las copias del radicado
	for ($i = 2; $i <= 100; $i++) {
	$j=$i-2;
		if($usuarios_informados[$j]!=$radicado_rem){
		$_ANEX_CODIGO = $codigo_copias.$i;

$isql = "UPDATE anexos
 SET 
 sgd_rem_destino = $usuarios_informados[$j], 
 sgd_dir_tipo= = $usuarios_informados[$j] 
 WHERE 
 ANEX_CODIGO = $_ANEX_CODIGO
 and ANEX_TIPO = 20";

 $isql_ = $db->conn->query($isql);

		}

	} */

    }

    include "nuevo_archivo.php";
?>
