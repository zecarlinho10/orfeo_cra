<?php
session_start();
/**
 * Modulo de consulta Web para atencion a Ciudadanos.
 * @autor Sebastian Ortiz
 * @fecha 2012/06
 *
 */
$ruta_raiz = "..";
include ("$ruta_raiz/config.php");
include('captcha/simple-php-captcha.php');
$_SESSION['captcha_consulta'] = captcha();
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

define('ADODB_ASSOC_CASE', 1);

$ADODB_COUNTRECS = false;
require_once("$ruta_raiz/include/db/ConnectionHandler.php");
$_SESSION["depeRadicaFormularioWeb"]=$depeRadicaFormularioWeb;  // Es radicado en la Dependencia 900
$_SESSION["usuaRecibeWeb"]=$usuaRecibeWeb; // Usuario que Recibe los Documentos Web
$_SESSION["secRadicaFormularioWeb"]=$secRadicaFormularioWeb; // Osea que usa la Secuencia sec_tp2_900
$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
//Revisar si se envio el formulario
if(isset($numeroRadicado) && isset($codigoverificacion) && isset($captcha)){
	$fechah = date("dmy") . "_" . date("hms");
	$usua_nuevo=3;
	if ($numeroRadicado)
	{
		$numeroRadicado = str_replace("-","",$numeroRadicado);
		$numeroRadicado = str_replace("_","",$numeroRadicado);
		$numeroRadicado = str_replace(".","",$numeroRadicado);
		$numeroRadicado = str_replace(",","",$numeroRadicado);
		$numeroRadicado = str_replace(" ","",$numeroRadicado);
		include "$ruta_raiz/include/tx/ConsultaRad.php";
		$ConsultaRad = new ConsultaRad($db);
		$idWeb = $ConsultaRad->idRadicadoConCodigoVerificacion($numeroRadicado, $codigoverificacion);
		$tpRad=substr($numeroRadicado,-1);
		if($numeroRadicado==$idWeb && ($tpRad=='2'|| $tpRad =='1'))
		{
			$ValidacionWeb="Si";
			$idRadicado = $idWeb;
	$krd = "usWeb";
	$datosEnvio = "$fechah&ard=$krd";
	$ulrPrincipal = "Location: principal.php?fechah=$datosEnvio&pasar=no&verdatos=no&idRadicado=$numeroRadicado&estadosTot=".md5(date('Ymd'));
	header($ulrPrincipal);
		}
		else
		{
			$ValidacionWeb="No";
			$mensaje = "El numero de radicado digitado no existe, el codigo de verificacion no corresponde o esta mal escrito o la imagen de verificacion no fue bien digitada.  Por favor corrijalo e intente de nuevo.";
			echo "<center><font color=red class=tpar><font color=red size=3>$mensaje</font></font>";
			return "<script>alert('$mensaje');</script>";
		}
	}
}


?>

<!DOCTYPE html>
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en"><!--<![endif]--><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="SIIM2">
<meta name="keywords" content="">
<?php $icono = $entidad."."; ?>
<link rel="shortcut icon" href="../img/<?=$icono?>favicon.png">

<title>..:: <?=$entidad?>  ::..</title>
<!-- Bootstrap core CSS -->
<link href="../estilos/bootstrap.css" rel="stylesheet">

<!-- CSS -->
<link rel="stylesheet" href="css/structure2.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />

<!-- Custom styles for this template -->
<link href="../estilos/CRA.login.css" rel="stylesheet">
<!-- JavaScript -->
<script type="text/javascript" src="js/wufoo.js"></script>
<!-- prototype -->
<script type="text/javascript" src="js/prototype.js"></script>
<!--funciones-->
<script type="text/javascript" src="js/orfeo.js"></script>
<!--registro acceso al tramite para propositos estadisticos-->
<script type="text/javascript" src="https://tramitesccu.cra.gov.co/metricra/funtracker.js"></script>

</head>
<body>
<!-- start Login box -->
<div class="container" id="login-block2">
<div class="row">
<div class="col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4 col-lg-12">
<div class="login-box clearfix animated flipInY">
<h3 class="animated bounceInDown"><?=$entidad_largo?></h3>
<div class="login-form">

<div class="alert alert-error hide">
<button type="button" class="close" data-dismiss="alert">×</button>
<h4>Error!</h4>
Los datos suministrados no son correctos.
</div>

<form id="consultaweb" action= "<?=$_SERVER['PHP_SELF']?>" name="consultaweb" enctype="multipart/form-data" method="post" autocomplete="on">
<input id="numeroRadicado" placeholder="N&uacute;mero de Radicado (s&oacute;lo n&uacute;meros)" required="" name="numeroRadicado" type="text" class="field text small" value="" maxlength="15" tabindex="1" onkeypress="return alpha(event,numbers)" />
<input id="codigoverificacion"  placeholder="C&oacute;digo verificaci&oacute;n radicado" required="" name="codigoverificacion" type="text" class="field text small" value="" maxlength="5"	tabindex="2" onkeypress="return alpha(event,numbers+letters)" />
<input id="campo_captcha"  placeholder="Captcha (Sensible a min&uacute;sculas y may&uacute;sculas)" required="" name="captcha" type="text"	class="field text small" value="" maxlength="5" tabindex="3"	onkeypress="return alpha(event,numbers+letters)"	alt="Digite las letras y n&uacute;meros de la im&aacute;gen" autocomplete="off" />
<?php
	echo '<img id="imgcaptcha" src="' . $_SESSION['captcha_consulta']['image_src'] . '" alt="CAPTCHA" /><br>';
	echo '<a  class="btn btn-login" href="#" onClick="return reloadImg(\'imgcaptcha\');">Cambiar im&aacute;gen<a>'
?>

<button id="saveForm" type="submit" class="btn btn-login" onclick="return validar_formulario();">Entrar</button>
</form>
</div>
<div>
Apreciado usuario, los radicados anteriores al 12 de febrero 2015 el <strong>campo código de verificación del radicado será 00000</strong>, con el fin de que pueda consultar los radicados antes de esta fecha.
<br>
<img src="../../orfeonew/imagenes/ForWebRad.png" WIDTH=178 HEIGHT=180>
<br>
Si tiene problemas para consultar por favor comunicarse a la línea a los <br><strong>Teléfono(s):</strong> Desde Colombia (1) 487 3820 - 489 7640 Desde el exterior +57(1) 487 3820 +57(1) 489 7640 <br><strong>Fax:</strong> Desde Colombia (1) 489 7650 Desde el exterior +57(1) 489 7650 <br><strong>Línea nacional gratuita:</strong> 01 8000 517565 <br><strong>Correo electrónico:</strong> correo@cra.gov.co
</div>



<!-- Begin Web-Stat code v 6.2 -->
<span id="wts976271">&nbsp;</span><script type="text/javascript">
var wts=document.createElement('script');wts.type='text/javascript';
wts.async=true;wts.src=('https:'==document.location.protocol?'https://server2':'http://lb')
+'.web-stat.com/5/976271/log6_2.js';
document.getElementById('wts976271').appendChild(wts);
</script><noscript><a href="http://www.web-stat.com">
<img src="//server2.web-stat.com/6/5/976271.gif" 
style="border:0px;" alt=""></a></noscript>
<!-- End Web-Stat code v 6.2 -->
	


</div>
</div>
</div>
</div>
<footer class="container">
<p id="footer-text"><small>Copyleft 2014, basado en Caliope 0.9 de la fundaci&oacute;n <a href="http://www.correlibre.org/">Correlibre</a></small></p>
</footer>
</body>




</html>
