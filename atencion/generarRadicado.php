<?php
session_start();
// error_reporting(E_ALL);
/**
 * @fecha 2009/05
 * @Fundacion CorreLibre.org
 * @licencia GNU/GPL V2
 *
 * Se tiene que modificar el post_max_size, max_file_uploads, upload_max_filesize
 */
array_filter($_POST, 'trim');

$ruta_raiz = "..";
$ADODB_COUNTRECS = false;
date_default_timezone_set("America/Bogota");

require_once (realpath(dirname(__FILE__) . "/../") . "/include/db/ConnectionHandler.php");
require_once realpath(dirname(__FILE__) . "/../") . '/atencion/CargarArchivo.php';
require_once (realpath(dirname(__FILE__) . "/../") . "/clasesComunes/BuscarDestinatario.php");
include_once realpath(dirname(__FILE__) . "/../") . '/atencion/RadicacionAtencion.php';
require_once realpath(dirname(__FILE__) . "/../") . "/atencion/AtencionTipos.php";
include_once realpath(dirname(__FILE__) . "/../") . '/clasesComunes/Usuario.php';
include_once realpath(dirname(__FILE__) . "/../") . '/clasesComunes/AtencionCiudadano.php';
include_once realpath(dirname(__FILE__) . "/") . '/conf_form.php';
require_once (realpath(dirname(__FILE__) . "/../")) . "/clasesComunes/CorreoAtencion.php";

$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
$errorFormulario = 0;

// strcasecmp ($captcha ,$_SESSION['captcha_formulario']['code'] )
if (strcasecmp($_POST["idFormulario"], $_SESSION["idFormulario"]) != 0) {
    $errorFormulario = 1;
    // Deshabilitada mientras se pueban otras cosas
    // return;
}
$errorFormulario = 0;
if (empty($errorFormulario)) {
    $uploader = new CargarArchivo($_FILES, $_SESSION["RUTA_ABSOLUTA"]);
    $paisTmp = explode("-", $_POST["pais"]);
    $pais = $paisTmp[1];
    $cont = $paisTmp[0];
    $deptoTmp = explode("-", $_POST["dpto"]);
    $depto = count($deptoTmp) > 1 ? $deptoTmp[1] : "0";
    $mncpioTmp = explode("-", $_POST["mncpio"]);
    $mncpio = count($mncpioTmp) > 2 ? $mncpioTmp[2] : 0;
    if ($_POST["anonimo"] == 2) {
        // Esto es anónimo
        $destinatario["tdid_codi"] = "0";
        $destinatario["documento"] = "0";
        $destinatario["nombre"] = "Anónimo";
        $destinatario["apellido1"] = "N.N";
        $destinatario["SGD_CIU_CODIGO"] = "0";
        $destinatario["direccion"] = "No registra";
        $destinatario["telefono"] = "No registra";
        $destinatario["apellido2"] = "";
        $destinatario["sigla"] = "";
        $destinatario["depto"] = $depto;
        $destinatario["muni"] = $mncpio;
        $destinatario["pais"] = $pais;
        $destinatario["continente"] = $cont;
        $destinatario["email"] = (!empty($_POST["emai"]))?$_POST["emai"]:""; 
        $primerNombre = "Anónimo";
        $primerApellido = "N.N";
        $cedula = 0;
        $nit = 0;
    } else {
        $des = new BuscarDestinatario();
        $destinatario = $des->generaDestinatario($_POST);
    }
    $radicacionAtencion = new RadicacionAtencion();
    $tipoPeticion = new AtencionTipos();
    $tencionCiudadano = new AtencionCiudadano();
    $usuario = new Usuario();
    $peticion = $tipoPeticion->findPeticion($_POST["tipoPeticion"]);
    $userRadica = $usuario->loadUsuario($_SESSION["krd"]);
	if($_POST["respuesta"]==2){
		$userRecibe = $usuario->findJefe($_POST["dependencia"]);
	}else{	
		$userRecibe = $usuario->loadUsuario($_SESSION["krd"]);
	}
	
	if (! empty($peticion["rules"])) {
        if (! empty($peticion["rules"]["usuario"])) {
            $usuaRule = $usuario->loadUsuario($peticion["rules"]["usuario"]);
        } else 
            if (! empty($peticion["rules"]["depe"])) {
                $usuaRule = $usuario->findJefe($peticion["rules"]["depe"]);
            }
        if (! empty($peticion["rules"]["priv"])) {
            $documento["privado"] = $peticion["rules"]["priv"];
        }
        if (! empty($peticion["rules"]["nivel"])) {
            $documento["seguridad"] = $peticion["rules"]["nivel"];
        }
    } else {
        $usuaRule = $userRecibe;
    }
    $usr = $radicacionAtencion->getInfoUsuario($userRadica, $usuaRule);
    $documento["mrec_cod"] = $_POST["mrec"];
    $documento["asunto"] = $_POST["asunto"];
    $documento["contenido"] = $_POST["contenido"];
    
    $atencion["mrec"] = $documento["mrec_cod"];
    $atencion["destinatario"] = $destinatario;
    $atencion["tipoPersona"] = empty($_POST["tipoPersona"]) ? 0 : $_POST["tipoPersona"];
    
    if (! empty($_POST["grupo"])) {
        $atencion["componente"] = $_POST["grupo"];
    } elseif (! empty($_POST["tipoEmpresa"])) {
        $atencion["componente"] = $_POST["tipoEmpresa"];
    } elseif (! empty($_POST["tipoEmpresa"])) {
        $atencion["componente"] = $_POST["tipoESP"];
    }
    $atencion["formulario"] = $_POST["primero"];
    $atencion["tipoPeticion"] = $_POST["tipoPeticion"];
    $tipoRad = $_POST["ent"];
    if(empty($destinatario["tipoRemitente"])){
        $destinatario["tipoRemitente"]=1;
    }
    $numeroRadicado = $radicacionAtencion->radicar($destinatario, null, $documento, $usr, $tipoRad, null, $uploader);
	$atencion["radicado"] = $numeroRadicado["radicado"];
    $tencionCiudadano->crearAtencion($atencion);
	$_SESSION["idFormulario"] = sha1(microtime(true) . mt_rand(10000, 90000));
	$correo = new correoAtencion();
	$correo->SMTPDebug=3;
	if($_POST["respuesta"]=="1"){
		$documento["asunto"] =  "Respuesta rad:".$atencion["radicado"]." ".$_POST["asunto"];	
		$documento["radicadopadre"]=$atencion["radicado"];
        $documento["contenido"] = $_POST["respuestas"];	
        $respuestaAtencion = new RadicacionAtencion();
        $firma["proyecto"] = $_SESSION["usua_nomb"];
        $firma["depe_codi"] = $_SESSION["dependencia"]." ".$_SESSION["depe_nomb"];
        
        $numeroRadicadoSalida = $respuestaAtencion->radicar($destinatario, $firma, $documento, $usr, 1, null, $uploader);
	}

    try {
        
        if (!empty($destinatario["email"]) && $correo->ValidateAddress($destinatario["email"])) {
            setlocale(LC_TIME, 'es_ES.UTF-8');
            $fecha_actual_larga = strftime("%A, %d de %B de %Y %H:%M");
            $fecha_actual_corta = date("d/m/y H:i:s");
            
            $correo->selectTemplate($correo::INFORMA_ATENCION);
            $valoresTemplate["*RAD_S*"] = $numeroRadicado["radicado"];
            $valoresTemplate["*FECHA_CORTA*"] = $fecha_actual_corta;
			$valoresTemplate["*COD_VER*"] = $numeroRadicado["codVerifica"];
			if($_POST["respuesta"]==1){
		     $valoresTemplate["*RESPUESTA*"]="La solicitud Fue respondida con el con el consecutivo ".$numeroRadicadoSalida["radicado"]." con el codigo de verificacion ".$numeroRadicadoSalida["codVerifica"];
			}else{
				$valoresTemplate["*RESPUESTA*"]='Le informamos que en los pr&oacute;ximos d&iacute;as recibir&aacute; respuesta a su comunicaci&oacute;n por este mismo medio o por correo certificado en caso de que haya incluido una direcci&oacute;n de respuesta. Tambi&ea    cute;n puede consultar el estado del tr&aacute;mite de su comunicaci&oacute;n en el siguiente link <a href="https://gestiondocumental.cra.gov.co/orfeonew/consultaWeb/" target="_blank" rel="noopener noreferrer">https://gestiondocumental.cra.gov.co</a> digitando     el consecutivo del asunto.<br />';
			}

            $bodega = BODEGA . "/" . $numeroRadicado["rutafile"];
            
            $correo->generar($destinatario["email"], $valoresTemplate, " Recepción Peticion " . $numeroRadicado["radicado"]);
            
            $correo->Send();

        }
    } catch (Exception $error) {
        echo "Notificación de Correo no pudo ser entregada ".$error->getMessage();
        error_log($error->getMessage()." ".$error->getTraceAsString(), 0);
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="https://www.w3.org/1999/xhtml">
<head>

<title>:: Formulario PQRS</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!--Deshabilitar modo de compatiblidad de Internet Explorer-->

<link rel="stylesheet" href="../estilos/bootstrap.min.css" type="text/css" />
<link rel="stylesheet"
	href="../estilos/CRA.bootstrap.min.css?jashd=<?php echo date("ymdhmi")?>"
	type="text/css" />

<!-- CSS -->
<link href="../estilos/jquery-ui.css" rel="stylesheet" />
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/chosen.jquery.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<!-- Just to make our placeholder images work. Don't actually copy the next line! -->
<script src="../js/holder.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../js/ie10-viewport-bug-workaround.js"></script>
<!-- Bootstrap core CSS -->
<link href="../estilos/bootstrap.min.css" rel="stylesheet" />
<link
	href="../estilos/bootstrap-chosen.css?sdf=<?php echo date("ymdhis")?>"
	rel="stylesheet" />
<link href="../estilos/bootstrap-dialog.css" rel="stylesheet" />


<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->


<link href="../estilos/ie10-viewport-bug-workaround.css"
	rel="stylesheet" />

<!-- Custom styles for this template -->
<link href="../estilos/dashboard.css" rel="stylesheet" />

<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="../js/ie8-responsive-file-warning.js"></script><![endif]-->


<script src="../js/ie-emulation-modes-warning.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="../js/html5shiv.min.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
<!-- <script type="text/javascript" src="scripts/wufoo.js"></script> -->

<!-- jQuery -->
<!-- FineUploader -->
<script type="text/javascript"
	src="../js/jquery.validate.min.js?sdf=<?php echo date("ymdhis")?>"></script>
<!--funciones-->
<script type="text/javascript" src="../js/bootstrap-dialog.js"></script>
<script type="text/javascript">
var basePath ='<?php echo $ruta_raiz?>';
</script>

</head>
<body>
	<div class="container-fluid">
		<h1>&nbsp;</h1>
	     <div class="row"></div>
		<div class="info col-sm-offset-3 ">
			<div class="row">
				<div class="col-md-2 text-center">
					<img src='<?php echo $logoEntidad?>' class="img-responsive" />
				</div>
				<div class="col-md-6  text-right">
					<p>
						<span class="h4"> <strong><?php echo $entidad_largo?></strong>
						</span>
					</p>
				</div>
			</div>
		</div>
		<br />
		<p>&nbsp;</p>
	<?php if($errorFormulario==0){?>
	<div class="info col-sm-offset-3 ">
			<div class="row">
				<div class="col-md-9  text-center">
					<p>
						<span> Su solicitud ha sido registrada de forma exitosa con el
							radicado No. <b><?php echo $numeroRadicado["radicado"]?></b> y
							código de verificación <b><?php echo $numeroRadicado['codVerifica'] ?></b>
						</span>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-9  text-center">
					<p>
						<span> Pulse continuar para <b>terminar la solicitud</b> y
							visualizar el documento en formato PDF. Si desea almacenelo en su
							disco duro o imprímalo.
						</span>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6  text-right">
					<form name="back" action="descargar.php" method="post">
						<input type="hidden" name="formtk"
							value="<?php echo $_SESSION["idFormulario"]?>" /> <input
							type="hidden" name="rad"
							value="<?php echo $numeroRadicado["radicado"]?>" /> <input
							type="submit" name="Submit" value="Continuar"
							class="btn btn-primary" /> <input type="button" name="Cerrar"
							value="Cerrar" class="btn btn-primary"
							onclick="window.location='./'" />
					</form>
				</div>
			</div>
		</div>
	<?php } else if ($errorFormulario==1){?>
	<div class="info col-sm-offset-3 ">
			<div class="row">
				<div class="col-md-9  text-center">
					<p>
						<span> <font color=red><b>Existe un error en su c&oacute;digo de
									verificaci&oacute;n o est&aacute; intentando enviar una
									petici&oacute;n de nuevo.</b></font>
						</span>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-9  text-center">
					<form name="back" action="index.php"
						method="post">
						<input type="submit" value="Atr&aacute;s" class="btn btn-primary" />

					</form>
				</div>
			</div>
		</div>
		<?php } else if($errorFormulario==2){?>
		<div class="info col-sm-offset-3 ">
			<div class="row">&nbsp;</div>
			<div class="row">&nbsp;</div>
			<div class="row">
				<div class="col-md-9  text-center">
					<p>
						<span> <font color=red><b>Ocurrió un error en al subida de archivo</b></font>
						<?php echo implode($uploader->messages);?>
						</span>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-9  text-center">
					<form name="back" action="index.php"
						method="post">
						<input type="submit" class="btn btn-primary" value="Atr&aacute;s" />

					</form>
				</div>
			</div>
		</div>
		<?php }?>
</div>

</body>
</html>
