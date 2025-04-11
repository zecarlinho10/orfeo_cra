<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$ruta_raiz = "../";
// ini_set ("display_errors",1);
if (! $_SESSION['dependencia'])
    die("<center>Sesion terminada, vuelve a iniciar sesion <a href='../cerrar_session.php'>aqui.<a></center>");
include ("$ruta_raiz/config.php");

?>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  
<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
	<script src="js/plugin/pace/pace.min.js"></script>

	<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
	    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script> if (!window.jQuery) { document.write('<script src="js/libs/jquery-2.0.2.min.js"><\/script>');} </script>

	    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script> if (!window.jQuery.ui) { document.write('<script src="js/libs/jquery-ui-1.10.3.min.js"><\/script>');} </script>



	<!-- JS TOUCH : include this plugin for mobile drag / drop touch events 		
		<script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

	<!-- BOOTSTRAP JS -->
	<script src="js/bootstrap/bootstrap.min.js"></script>

	<!-- CUSTOM NOTIFICATION -->
	<script src="js/notification/SmartNotification.min.js"></script>

	<!-- JARVIS WIDGETS -->
	<script src="js/smartwidgets/jarvis.widget.min.js"></script>

	<!-- EASY PIE CHARTS -->
	<script src="js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

	<!-- SPARKLINES -->
	<script src="js/plugin/sparkline/jquery.sparkline.min.js"></script>

	<!-- JQUERY VALIDATE -->
	<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>

	<!-- JQUERY MASKED INPUT -->
	<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>

	<!-- JQUERY SELECT2 INPUT -->
	<script src="js/plugin/select2/select2.min.js"></script>

	<!-- JQUERY UI + Bootstrap Slider -->
	<script src="js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

	<!-- browser msie issue fix -->
	<script src="js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

	<!-- FastClick: For mobile devices -->
	<script src="js/plugin/fastclick/fastclick.min.js"></script>

	<!--[if IE 7]>
			
			<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
			
		<![endif]-->

	<!-- MAIN APP JS FILE -->
	<script src="js/app.min.js"></script>

<?
/*
if ($_SESSION['usua_email_1'])
    $usua_email = $_SESSION['usua_email_1'];
*/
/**
 * **Configuración para Gmail, (autenticación SSL)***
 */
    /*
 * $hostname = '{'."$servidor_mail:$puerto_mail/$protocolo_mail/ssl".'}';
 * /*$username = $usuaEmail;
 * /*****************************************************
 */

/**
 * *Configuración para Exchange sin autenticación SSL*
 */
/*
$hostname = '{' . "$servidor_mail:$puerto_mail/$protocolo_mail/ssl/novalidate-cert" . '}';

if (empty($fullUser) || $fullUser == false) {
    $usua_email = current(explode("@", $usua_email));
}
*/
/**
 * ***************************************************
 */
/*
$passwd_mail = $_SESSION["passwd_mail"];
$page = ! isset($_GET['page']) ? 1 : $_GET['page'];
*/
/* try to connect */

$inbox = $_SESSION['inbox'];

echo "inbox:" . $inbox;
/*
if (empty($inbox) || ! is_resource($inbox)) { // Instalar primero libreria php5-imap
    $inbox = imap_open($hostname, $usua_email, $passwd_mail) or die(header('Location: lock.php'));
    $_SESSION['inbox'] = $inbox;
}
include_once "htmlheader.inc.php";
*/
?>
<div class="loading"
		style="position: fixed; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.5) none repeat scroll 0% 0%; text-align: center; display: none; z-index: 1000">
		<img src="../img/preload.gif" alt="loading"
			style="vertical-align: middle; padding-top: 10%" /> 
			<div
			class="text-center"><progress>Radicando</progress>
			<span class="alert-success">
				<strong>procesando</strong>
			</span></div>
	</div>
	<!-- RIBBON -->
	<div id="ribbon">

		<span class="ribbon-button-alignment"> <span id="refresh"
			class="btn btn-ribbon" data-action="resetWidgets"
			data-title="refresh" rel="tooltip" data-placement="bottom"
			data-html="true"> <i class="fa fa-refresh"></i>
		</span>
		</span>

		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<li>Home</li>
			<li>Inbox</li>
		</ol>
		<!-- end breadcrumb -->

	</div>
	<!-- END RIBBON -->


	<div class="inbox-nav-bar no-content-padding">

		<h1 class="page-title txt-color-blueDark hidden-tablet">
			<i class="fa fa-fw fa-inbox"></i> Inbox &nbsp;
		</h1>

		<div class="btn-group hidden-desktop visible-tablet">
			<button class="btn btn-default dropdown-toggle"
				data-toggle="dropdown">
				Inbox <i class="fa fa-caret-down"></i>
			</button>
			<ul class="dropdown-menu pull-left">
				<li><a href="javascript:void(0);" class="inbox-load">Inbox <i
						class="fa fa-check"></i></a></li>
			</ul>

		</div>


		<a href="javascript:void(0);" id="compose-mail-mini"
			class="btn btn-primary pull-right hidden-desktop visible-tablet"> <strong><i
				class="fa fa-file fa-lg"></i></strong>
		</a>

		<div class="btn-group pull-right inbox-paging">
			<a href="javascript:void(0);" class="btn btn-default btn-sm"><strong><i
					class="fa fa-chevron-left"></i></strong></a> <a
				href="javascript:void(0);" class="btn btn-default btn-sm"><strong><i
					class="fa fa-chevron-right"></i></strong></a>
		</div>
		<span class="pull-right"><strong>1-50</strong> of <strong>3,452</strong></span>

	</div>

	<div id="inbox-content" class="inbox-body no-content-padding">

		<div class="inbox-side-bar">


			<h6>
				Folder <a href="javascript:void(0);" rel="tooltip" title=""
					data-placement="right" data-original-title="Refresh"
					class="pull-right txt-color-darken"><i class="fa fa-refresh"></i></a>
			</h6>

			<ul class="inbox-menu-lg">
				<li class="active"><a class="inbox-load" href="javascript:void(0);">
						Inbox (0) </a></li>
			</ul>



		</div>
		<div class="table-wrap custom-scroll animated fast fadeInRight">
			<!-- ajax will fill this area -->
		<?
include ("email-list.php");

if (is_resource($inbox))
    //imap_close($inbox);


?>

	</div>
		<div id="radicador">
			<div class="table-wrap custom-scroll animated fast fadeInRight">
				<!-- ajax will fill this area -->

			</div>
		</div>
		

		<script type="text/javascript">
		$(function(){
			$.ajaxSetup({
				beforeSend:function(){
				$('.loading').fadeIn(200);
				},
				complete:function(){
				$('.loading').delay(200).fadeOut(200);
				}});
		});
	/* DO NOT REMOVE : GLOBAL FUNCTIONS!
	 *
	 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
	 *
	 * // activate tooltips
	 * $("[rel=tooltip]").tooltip();
	 *
	 * // activate popovers
	 * $("[rel=popover]").popover();
	 *
	 * // activate popovers with hover states
	 * $("[rel=popover-hover]").popover({ trigger: "hover" });
	 *
	 * // activate inline charts
	 * runAllCharts();
	 *
	 * // setup widgets
	 * setup_widgets_desktop();
	 *
	 * // run form elements
	 * runAllForms();
	 *
	 ********************************
	 *
	 * pageSetUp() is needed whenever you load a page.
	 * It initializes and checks for all basic elements of the page
	 * and makes rendering easier.
	 *
	 */

	pageSetUp();

	// PAGE RELATED SCRIPTS

	// pagefunction
	
	var pagefunction = function() {
		/*
		 * LOAD INBOX MESSAGES
		 */
		loadInbox();

		<?php if (isset($radMail)) echo "loadRadMail();";?>
		
		function loadRadMail() {
			var URI="<?=end(explode("?",$_SERVER['REQUEST_URI']))?>";
			loadURL("../radicacion/NEW.php?"+URI, $('#inbox-content > .table-wrap'));
			$("#radicador").show();
			
		}
		
		function loadInbox() {
			loadURL("email-list.php", $('#inbox-content > .table-wrap'));
			$("#radicador").hide();
		}
	
		/*
		 * Buttons (compose mail and inbox load)
		 */

		$(".inbox-load").click(function() {
			loadInbox();
			$('#radicador').hide();
		});

		$(".fa-refresh").click(function() {
			loadURL("email-list.php?force", $('#inbox-content > .table-wrap'));
			$('#radicador').hide();
		});
		// compose email
		
	};
	
	// end pagefunction
	
	// load delete row plugin and run pagefunction
	pagefunction();
	
</script>
		<!-- IMPORTANT: APP CONFIG -->
		<script src="js/app.config.js"></script>
		<!-- MAIN APP JS FILE -->
		<script src="js/app.min.js"></script>
		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<script data-pace-options='{ "restartOnRequestAfter": true }'
			src="js/plugin/pace/pace.min.js"></script>
		<!--[if IE 8]>
	<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
<![endif]-->
