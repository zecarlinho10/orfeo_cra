<?php
session_start();
// error_reporting(E_ALL);
/**
 * Modulo de Formularios Web para atencion a Ciudadanos.
 * @autor COGEINSAS Wilson Hernandez
 * @autor Carlos Barrero carlosabc81@gmail.com SuperSolidaria
 *
 * @author Sebastian Ortiz Vasquez 2012
 *         @fecha 2009/05
 *         @Fundacion CorreLibre.org
 *         @licencia GNU/GPL V2
 *        
 *         Se tiene que modificar el post_max_size, max_file_uploads, upload_max_filesize
 */
array_filter($_POST, 'trim');

$ruta_raiz = "..";
$ADODB_COUNTRECS = false;

require_once (realpath(dirname(__FILE__) . "/../") . "/include/db/ConnectionHandler.php");
require_once realpath(dirname(__FILE__) . "/../") . '/atencion/CargarArchivo.php';
require_once (realpath(dirname(__FILE__) . "/../") . "/clasesComunes/BuscarDestinatario.php");
include_once realpath(dirname(__FILE__) . "/../") . '/atencion/RadicacionAtencion.php';
require_once realpath(dirname(__FILE__) . "/../") . "/atencion/AtencionTipos.php";
include_once realpath(dirname(__FILE__) . "/../") . '/clasesComunes/Usuario.php';
include_once realpath(dirname(__FILE__) . "/../") . '/clasesComunes/AtencionCiudadano.php';
include_once realpath(dirname(__FILE__) . "/") . '/conf_form.php';

$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
$errorFormulario = 0;

// strcasecmp ($captcha ,$_SESSION['captcha_formulario']['code'] )
if (strcasecmp($_POST["captcha"], $_SESSION['captcha_formulario']['code']) != 0 || strcasecmp($_POST["idFormulario"], $_SESSION["idFormulario"]) != 0 || empty($_POST["anonimo"])) {
    $errorFormulario = 1;
    // Deshabilitada mientras se pueban otras cosas
    // return;
}
if (empty($errorFormulario)) {
    if (! empty($_FILES) || ! empty($_POST["adjuntosSubidos"])) {
        $uploader = new CargarArchivo($_FILES, $_SESSION["RUTA_ABSOLUTA"]);
        $adjuntosSubidos = json_decode($_POST["adjuntosSubidos"]);
        $uploader->subidos = $adjuntosSubidos;
        $uploader->adjuntarYaSubidos();
    }
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
        $destinatario["mncpio"] = $mncpio;
        $destinatario["pais"] = $pais;
        $destinatario["continente"] = $cont;
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
    $userRadica = $usuario->loadUsuario($usuarioRadica);
    $userRecibe = $usuario->loadUsuario($usuaRecibeWeb);
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
    $documento["mrec_cod"] = ! empty($_POST["facebook"]) ? 3 : 5;
    $documento["asunto"] = $_POST["asunto"];
    $documento["contenido"] = $_POST["comentario"];
    
    $atencion["mrec"] = $documento["mrec_cod"];
    $atencion["destinatario"]=$destinatario;
    $atencion["tipoPersona"]=empty($_POST["tipoPersona"])?0:$_POST["tipoPersona"];
    
    if (! empty($_POST["grupo"])) {
        $atencion["componente"] = $_POST["grupo"];
    } elseif (! empty($_POST["tipoEmpresa"])) {
        $atencion["componente"] = $_POST["tipoEmpresa"];
    } elseif (! empty($_POST["tipoEmpresa"])) {
        $atencion["componente"] = $_POST["tipoESP"];
    }
    $atencion["formulario"]=5;
    $atencion["tipoPeticion"] = $_POST["tipoPeticion"];
    $numeroRadicado = $radicacionAtencion->radicar($destinatario, null, $documento, $usr, $tipoRad, null, $uploader);
    $atencion["radicado"] = $numeroRadicado["radicado"];
    $tencionCiudadano->crearAtencion($atencion);
    $_SESSION["idFormulario"] = sha1(microtime(true) . mt_rand(10000, 90000));
    require_once (realpath(dirname(__FILE__) . "/../") . '/include/CRA_phpmailer/class.phpmailer.php');
    require_once (realpath(dirname(__FILE__) . "/../") . "/conf/configPHPMailer.php");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>:: <?=$entidad_largo ?>:: Formulario PQRS</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!--Deshabilitar modo de compatiblidad de Internet Explorer-->

<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet"
	href="css/CRA.bootstrap.min.css?jashd=<?php echo date("ymdhmi")?>"
	type="text/css" />

<!-- CSS -->
<link rel="stylesheet" href="css/structure2.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />
<link rel="stylesheet" href="css/fineuploader.css" type="text/css" />
<link href="../estilos/jquery-ui.css" rel="stylesheet">



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
	<link href="css/bootstrap-dialog.css" rel="stylesheet" />


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
      <script src="../js3/html5shiv.min.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
	<!-- <script type="text/javascript" src="scripts/wufoo.js"></script> -->

	<!-- jQuery -->
	<!-- FineUploader -->
	<script type="text/javascript"
		src="../js/jquery.validate.min.js?sdf=<?php echo date("ymdhis")?>"></script>
	<!--funciones-->
	<script type="text/javascript" src="scripts/bootstrap-dialog.js"></script>
	<script type="text/javascript">
var basePath ='<?php echo $ruta_raiz?>';
</script>

</head>
<body>
	<div class="container-fluid">
		<h1>&nbsp;</h1>
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
					<form name="back" action="javascript:history.go(-1)()"
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
					<form name="back" action="javascript:history.go(-1)()"
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