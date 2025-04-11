<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 */ 
echo ".";

/**
* @module index_frame
*
* @author Jairo Losada   <jlosada@gmail.com>
* @author Cesar Gonzalez <aurigadl@gmail.com>
* @license  GNU AFFERO GENERAL PUBLIC LICENSE
* @copyright

SIIM2 Models are the data definition of SIIM2 Information System
Copyright (C) 2013 Infometrika Ltda.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

    function hackerDefense(){  
      // begin hacker defense  
      foreach ($_POST as $secvalue) {  
        if ((preg_match("/]*script.*\"?[^>]*>/i", $secvalue)) ||  
          (preg_match("/]*object.*\"?[^>]*>/i", $secvalue)) ||  
          (preg_match("/]*iframe.*\"?[^>]*>/i", $secvalue)) ||  
          (preg_match("/]*applet.*\"?[^>]*>/i", $secvalue)) ||  
          (preg_match("/]*window.*\"?[^>]*>/i", $secvalue)) ||  
          (preg_match("/]*document.*\"?[^>]*>/i", $secvalue)) ||  
          (preg_match("/]*cookie.*\"?[^>]*>/i", $secvalue)) ||  
          (preg_match("/]*meta.*\"?[^>]*>/i", $secvalue)) ||  
          (preg_match("/]*style.*\"?[^>]*>/i", $secvalue)) ||  
          (preg_match("/]*alert.*\"?[^>]*>/i", $secvalue)) ||  
          (preg_match("/]*form.*\"?[^>]*>/i", $secvalue)) ||  
          (preg_match("/]*php.*\"?[^>]*>/i", $secvalue)) ||  
          (preg_match("/]*]*>/i", $secvalue)) ||  
          (preg_match("/]*img.*\"?[^>]*>/i", $secvalue))) {  
          header('location:'.$site.'login.php');  
          die ();  
        }  
  	  }
	  }


	 hackerDefense();

  $drd = false;
  $krd = false;
  $token = false;
  
  if (isset($_POST["krd"])){
      $krd = strip_tags($_POST["krd"]);
  }
  if (isset($_POST["drd"])){
      $drd = strip_tags($_POST["drd"]);
  }
  if (isset($_POST["token"])){
      $token = strip_tags($_POST["token"]);
  }

  if (isset($_POST["autenticaPorLDAP"]))
      $autenticaPorLDAP = $_POST["autenticaPorLDAP"];

  $fechah        = date("dmy")."_".date("hms");
  $ruta_raiz     = ".";
  $usua_nuevo    = 3;
  $ValidacionKrd  = "";

  include ("config.php");
  $serv = str_replace(".", ".", $_SERVER['REMOTE_ADDR']);

  if ($krd) {
      //session_orfeo retorna mensaje de error
      include "$ruta_raiz/session_orfeo.php";
      require_once ("$ruta_raiz/class_control/Mensaje.php");

      if ($usua_nuevo == 0 &&  !$autenticaPorLDAP) {
          include ($ruta_raiz."/contraxx.php");
          $ValidacionKrd = "NOOOO";
          if ($j = 1)
              die("<center> -- </center>");
      }
  }

  $krd = strtoupper($krd);
  //$datosEnvio = session_name()."=".trim(session_id())."&fechah=$fechah&krd=$krd&swLog=1&orno=1";
  $datosEnvio = "&fechah=$fechah&swLog=1&orno=1";

  if ($ValidacionKrd == "Si") {
      header ("Location: $ruta_raiz/index_frames.php?$datosEnvio");
      exit();
  }

?>

<!DOCTYPE html>
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en"><!--<![endif]--><head>

  
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Gestor Documental Comision de Regulacion de Agua Potable">
  <meta name="keywords" content="">
<!--   <link rel="shortcut icon" href="./img/favicon.png">-->
  <link rel="icon" type="image/x-icon"  href="./img/<?=(file_exists("./img/$entidad.favicon.png"))?$entidad.".":""?>favicon.png" onClick="this.reload();">


  <title>..:: <?=$entidad?> Orfeo  ::..</title>
    <!-- Bootstrap core CSS -->
   <!-- <link href="./estilos/bootstrap.min.css" rel="stylesheet"> -->

<? if ($entidad=="METROVIVIENDA"){ ?>
    <link href="./estilos/bootstrap.min.css" rel="stylesheet">
	<? }else{ ?>
     <link href="./estilos/bootstrap.css" rel="stylesheet">
<? } ?>
    <!-- Custom styles for this template -->
    <link href="./estilos/<?=(file_exists("./estilos/$entidad.login.css"))?$entidad.".":""?>login.css" rel="stylesheet">

  </head>
    <body>
      <!-- start Login box -->
	<?php
	$err_response = array(
	    '400' => 'La petición realizada es inválida.',
	    '401' => 'Acceso a recurso no atuorizado.',
	    '403' => 'No tiene permisos para acceder a este recurso.',
	    '404' => 'La página solicitada no fué encontrada.',
	    '500' => 'Lo sentimos, ha ocurrido un error inesperado.'
	);
	?>
	<?php if (array_key_exists($_GET['code'], $err_response)): ?>
	    <h4 style="color: #fff; ">ERROR: <?=$err_response[$_GET['code']];?></h4>
	<?php endif; ?>
      <div class="container" id="login-block">
        <div id="logoorfeo"></div>
        <div class="row">
          <div class="col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4 col-lg-12">
             <div class="login-box clearfix animated flipInY"><br><br><br>
             <!-- <h3 class="animated bounceInDown"><?=$entidad_largo?></h3>
                <hr> -->
                <div class="login-form">

                  <div class="alert alert-error hide">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <h4>Error!</h4>
                    Los datos suministrados no son correctos.
                  </div>

                  <!--<form action="./login.php" method="post" autocomplete="off">-->
				  
				          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" autocomplete="off">	
                    <input name="krd" placeholder="Usuario" required="" type="text">
                    <input name="drd" placeholder="Password" required="" type="password" min="8">
                    <button type="submit" class="btn btn-login">Entrar</button>
                  </form>
                  <?php if (!empty($qrCodePath)) { ?>
                      <h3>Escanea este código QR con tu aplicación de autenticación</h3>
                      <img src="<?php echo $qrCodePath; ?>" alt="Código QR de Autenticación">
                  <?php } 
                  ?>
                  <?if(!empty($mensajeError)){ ?>
                  <div class="login-links text-error">
                    <?=$mensajeError?>
                  </div>
                  <?}?>
                </div>
                <div>
                <span id="signinButton">
                    <span
                      class="g-signin"
                      data-callback="signinCallback"
                      data-clientid="CLIENT_ID"
                      data-cookiepolicy="single_host_origin"
                      data-requestvisibleactions="http://schemas.google.com/AddActivity"
                      data-scope="https://www.googleapis.com/auth/plus.login">
                    </span>
                  </span>
                </div>
             </div>
          </div>
        </div>

        <div id="logocorrelibre"></div>

      </div>
      <!-- End Login box -->
<div>

</div>

      <footer class="container">
        <p id="footer-text"><small>Copyleft 2014, basado en OrfeoGPL actualizado por la  Fundaci&oacute;n <a href="http://www.correlibre.org/">Correlibre</a></small></p>
      </footer>

      <script src="./js/jquery.min.js"></script>
      <script src="./js/bootstrap.js"></script>

      <script>
        /* Custom JavaScript */
        $(document).ready(function($) {
            $('input, textarea').placeholder();
            if(window.self !== window.top){
                top.location.reload();
            };
        });
      </script>

    <script src="js/placeholder-shim.min.js"></script>
</body>
</html>
