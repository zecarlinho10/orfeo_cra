<?php
session_start();
 require_once realpath(dirname(__FILE__) . "/../") . "/atencion/conf_form.php";

/**
 * Modulo de Formularios Web para atencion a Ciudadanos.
 * Modificado RIcardo Perilla
 * Modificado Wilson Hernandez
 * Sebastian Ortiz
 * @fecha 2012/06
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>:: <?=$entidad_largo ?>:: Formulario PQRS</title>

<!-- Meta Tags -->
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



<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/chosen.jquery.js"></script>
<!-- Just to make our placeholder images work. Don't actually copy the next line! -->
<script src="../js/holder.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../js/ie10-viewport-bug-workaround.js"></script>
<!-- Bootstrap core CSS -->
<link href="../estilos/bootstrap.min.css" rel="stylesheet" />
<link href="../estilos/bootstrap-chosen.css" rel="stylesheet" />

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->


<link href="../estilos/ie10-viewport-bug-workaround.css"
	rel="stylesheet" />

<!-- Custom styles for this template -->
<link href="../estilos/dashboard.css" rel="stylesheet" />

<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="../.../js/ie8-responsive-file-warning.js"></script><![endif]-->


<script src="../js/ie-emulation-modes-warning.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="../js3/html5shiv.min.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
<!-- <script type="text/javascript" src="scripts/wufoo.js"></script> -->


<!-- <script type="text/javascript" src="prototype.js"></script></script> -->
<!-- jQuery -->
<script src="scripts/jquery.js"></script>
<!-- FineUploader -->
<script type="text/javascript" src="scripts/jquery.fineuploader-3.0.js"></script>
<!--funciones-->
<script type="text/javascript" src="ajax.js"></script>
<script>
	$(function() {
		$('.chosen-select').chosen();
		$('.chosen-select-deselect').chosen({
			allow_single_deselect : true
		});
		createUploader();
		$('[data-toggle="tooltip"]').tooltip(); 
	});
</script>

</head>
<body id="public">
	<div id="container" class="col-md-12">
		<form id="contactoOrfeo" class="wufoo topLabel" autocomplete="on"
			enctype="multipart/form-data" method="post" action="formulariotx.php"
			name="quejas">

			<div class="row container-md6">
				<div class="col-md-3"></div>
				<div class="col-md-3">
				<div class="card text-center container-index">
					<div class="card-body">
						<p class="card-text">Estimado ciudadano, lo invitamos a encontrar</br> solución a su
											inquietud a través de nuestro</br> banco de preguntas frecuentes.
											&nbsp; Si su </br>búsqueda no es satisfactoria le invitamos a
											</br> plantearla a través del formulario de PQRSD </br>de la siguiente
											sección</p>
						<a class="btn btn-primary button-index" href="<?php echo $urlFaq?>"
											role="button" data-toggle="ir al banco de preguntas">Preguntas
												Frecuentes</a>
					</div>
				</div>
				</div>

				<div class="col-md-3">
				<div class="card text-center container-index">
					<div class="card-body">
						</br>
						<p class="card-text">Estimado ciudadano, si su deseo es realizar</br>
											una solicitud formal ante nuestra entidad,</br>
											diligencie el fomrulario</br></p>
							</br></br>
							<a class="btn btn-primary button-index" href="formulario.php"
								role="button" data-toggle="Diligenciar formulario de Solicitudes">Formulario de PQRSD</a>
					</div>
				</div>
				</div>
				<div class="col-md-3"></div>
			</div>
		</form>
	</div>
	<!--container-->
</body>
</html>
