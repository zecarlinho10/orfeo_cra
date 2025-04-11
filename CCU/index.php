<?php
session_start();
 require_once realpath(dirname(__FILE__) . "/../") . "/atencion/conf_form.php";

/**
 * Modulo de CCU
 * Carlos Ricaurte
 * @fecha 2021/12
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>:: <?=$entidad_largo ?>:: Formulario CCU</title>

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
	<div id="container" class="container-fluid login-box">
		<h1>&nbsp;</h1>
		<form id="contactoOrfeo" class="wufoo topLabel" autocomplete="on"
			enctype="multipart/form-data" method="post" action="formulariotx.php"
			name="quejas">
			<div class="info ">
				<div class="row">
					<div class="col-md-2 text-center">
						<img src='<?php echo $logoEntidad?>' class="img-responsive" />
					</div>
					<div class="col-md-6 text-rigth">
						<span class="h4"><p>
								<strong><?php echo $entidad_largo?></strong>
							</p></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-justify">
					<span>Modelo de contrato de servicios públicos para personas prestadoras de los servicios públicos domiciliarios de acueducto y/o alcantarillado, que cuenten con más de 5.000 suscriptores y/o usuarios en el área rural o urbana. Compilado en el numeral 6.1.6.1. del Título 6 de la Parte 1 del Libro 6. ANEXOS REGULACIÓN GENERAL de la Resolución CRA 943 de 2021.</span>
				</div>
				<div class="col-md-8">
					<span><a class="btn btn-primary" href="formulario768.php"
						role="button" data-toggle="Diligenciar formulario de Solicitudes"> Anexo 1 de la Resolución CRA 768 de 2016</a> </span>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-12 text-justify">
					<span>Modelo de condiciones uniformes del contrato de servicios públicos para personas prestadoras del servicio público de aseo que atiendan en municipios de más 5.000 suscriptores en el área urbana y de expansión urbana. Compilado en el numeral 6.3.3.1. del Título 2 de la Parte 3 del Libro 6. ANEXOS REGULACIÓN GENERAL de la Resolución CRA 943 de 2021.</span>
				</div>
				<div class="col-md-8">
					<span><a class="btn btn-primary" href="formulario778-1.php"
						role="button" data-toggle="Diligenciar formulario de Solicitudes"> Anexo 1 de la Resolución CRA 778 de 2016</a> </span>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-12 text-justify">
					<span>Modelo de condiciones uniformes del contrato de servicios públicos para personas prestadoras de la actividad de aprovechamiento que atiendan en municipios de más de 5.000 suscriptores en el área urbana, de expansión urbana. Compilado en el numeral 6.3.3.1. del Título 2 de la Parte 3 del Libro 6. ANEXOS REGULACIÓN GENERAL de la Resolución CRA 943 de 2021.</span>
				</div>
				<div class="col-md-8">
					<span><a class="btn btn-primary" href="formulario778-2.php"
						role="button" data-toggle="Diligenciar formulario de Solicitudes"> Anexo 2 de la Resolución CRA 778 de 2016</a> </span>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-12 text-justify">
					<span>Modelo de Condiciones Uniformes del Contrato de Servicios Públicos para personas prestadoras de los servicios públicos domiciliarios de Acueducto y/o Alcantarillado que a 31 de diciembre de 2013 atiendan en sus APS hasta 5.000 suscriptores en el área urbana y aquellas que presten el servicio en el área rural independientemente del número de suscriptores que atiendan. Compilado en el numeral 6.1.6.2. del Título 6 de la Parte 1 del Libro 6. ANEXOS REGULACIÓN GENERAL de la Resolución CRA 943 de 2021.</span>
				</div>
				<div class="col-md-8">
					<span><a class="btn btn-primary" href="formulario873AA-1.php"
						role="button" data-toggle="Diligenciar formulario de Solicitudes"> Anexo 1 la Resolución CRA 873 de 2019</a> </span>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-12 text-justify">
					<span>Modelo de Condiciones Uniformes del Contrato de Servicios Públicos para personas prestadoras de los servicios públicos domiciliarios de Acueducto y/o Alcantarillado que apliquen esquemas diferenciales rurales.</span>
				</div>
				<div class="col-md-8">
					<span><a class="btn btn-primary" href="formulario873AA-2.php"
						role="button" data-toggle="Diligenciar formulario de Solicitudes"> RESOLUCION CRA 873 AA - Anexo 2</a> </span>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-12 text-justify">
					<span>Modelo de Condiciones Uniformes del Contrato de Servicios Públicos para personas prestadoras del servicio público de aseo y sus actividades complementarias, excepto la actividad de aprovechamiento, que se encuentren incluidas en el ámbito de aplicación de la Resolución CRA 853 de 2018 o la que la modifique, adicione, sustituya o derogue, en la siguiente clasificación: i) primer segmento, ii) segundo segmento, iii) tercer segmento, iv) esquema de prestación en zonas de difícil acceso y v) esquema de prestación regional en donde todas las APS se encuentran en municipios con hasta 5000 suscriptores. Compilado en el numeral 6.3.3.1. del Título 3 de la Parte 1 del Libro 6. ANEXOS REGULACIÓN GENERAL de la Resolución CRA 943 de 2021.</span>
				</div>
				<div class="col-md-8">
					<span><a class="btn btn-primary" href="formulario894-1.php"
						role="button" data-toggle="Diligenciar formulario de Solicitudes"> Anexo 1 de la Resolución CRA 894 de 2019 </a> </span>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-12 text-justify">
					<span>Modelo de Condiciones Uniformes del Contrato de Servicios Públicos para las personas prestadoras de la actividad de aprovechamiento que se encuentren incluidas en el ámbito de aplicación de la Resolución CRA 853 de 2018 o la que la modifique, adicione, sustituya o derogue. Compilado en el Artículo 6.3.3.2 del Título 3 de la Parte 3 del Libro 6. ANEXOS REGULACIÓN GENERAL de la Resolución CRA 943 de 2021.</span>
				</div>
				<div class="col-md-8">
					<span><a class="btn btn-primary" href="formulario894-2.php"
						role="button" data-toggle="Diligenciar formulario de Solicitudes"> Anexo 2 de la Resolución CRA 894 de 2019</a> </span>
				</div>
			</div>
		</form>
	</div>
	<!--container-->
</body>
</html>
