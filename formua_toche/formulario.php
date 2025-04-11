<?php
session_start();
if (empty($_SESSION['token'])) {
    if (function_exists('mcrypt_create_iv')) {
        $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    } else {
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}
$token = $_SESSION['token'];
include  realpath(dirname(__FILE__) . "/../") . "/atencion/conf_form.php";
/**
 * Modulo de Formularios Web para atencion a Ciudadanos.
 * Modificado RIcardo Perilla
 * Modificado Wilson Hernandez
 * Modificado por Wduarte - Cra -
 * Sebastian Ortiz
 * @fecha 2012/06
 */
$ruta_raiz = "..";
$ADODB_COUNTRECS = false;
require_once ("$ruta_raiz/include/db/ConnectionHandler.php");
require_once realpath(dirname(__FILE__) . "/../") . "/atencion/AtencionTipos.php";
$_SESSION["idFormulario"] = sha1(microtime(true) . mt_rand(10000, 90000));
$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
include ('./captcha/simple-php-captcha.php');
$_SESSION['captcha_formulario'] = captcha();

// TamaNo mAximo del todos los archivos en bytes 10MB = 10(MB)*1024(KB)*1024(B) = 10485760 bytes
$max_file_size = 10485760;

if (! isset($isFacebook)) {
    $isFacebook = 0;
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

<link rel="stylesheet" href="../estilos/bootstrap.min.css" type="text/css" />
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
      <script src="../js3/html5shiv.min.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
	<!-- <script type="text/javascript" src="scripts/wufoo.js"></script> -->

	<!-- jQuery -->
	<!-- FineUploader -->
	<script type="text/javascript" src="scripts/jquery.fineuploader-3.0.js"></script>
	<script type="text/javascript"
		src="../js/jquery.validate.min.js?sdf=<?php echo date("ymdhis")?>"></script>
	<!--funciones-->
	<script type="text/javascript" src="../js/bootstrap-dialog.js"></script>
	<script type="text/javascript"
		src="../js/divipola.js?tes=<?php echo date("Ymdhis")?>"></script>
	<script type="text/javascript"
		src="scripts/ajax.js?tes=<?php echo date("Ymdhis")?>"></script>
	<script type="text/javascript">
var basePath ='<?php echo $ruta_raiz?>';
 var urlEntidad ='<?php echo  $httpWebOficial ?>';
</script>

</head>
<body id="public">
	<div class="container-fluid">
		<h1>&nbsp;</h1>
		<form id="contactoOrfeo" class="" autocomplete="off"
			enctype="multipart/form-data" method="post" action="formulariotx.php"
			name="quejas">

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
			<div class="row">
				<div class="col-sm-2 col-md-2 text-center"></div>
				<div class="col-md-8 text-justify h4" style="margin-bottom: 0px;">
					<p>
						Apreciado ciudadano: <br /> &nbsp; <br />Al diligenciar el
						formulario, tenga en cuenta lo siguiente: <br /> <br />La Ley 1755
						de 2015 en su articulo 13 establece que toda actuación que inicie
						cualquier persona ante las autoridades implica el ejercicio del
						derecho de petición consagrado en el artículo 23 de la
						Constitución Política, sin que sea necesario invocarlo.

					</p>
					<p style="margin-bottom: 0px;">
						Lo invitamos a encontrar solución a su inquietud a través de
						nuestro banco de <u><strong><a href="<?php echo $urlFaq?>">preguntas
									frecuentes</a></strong></u>.<br /> <br />Si su búsqueda no fue
						satisfactoria, agredecemos diligenciar la siguiente solicitud.
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-2 col-md-2 text-center"></div>
				<div class="col-md-8 h3"
					style="background-color: #dedede; margin-top: 0px;">
					<span> <strong>Peticiones, Quejas Reclamos Sugerencias y Denuncias</strong>
						<br /> <span class="h5"> Los campos con (<font color="#FF0000">*</font>
							) son obligatorios.
					</span>
					</span>
				</div>
			</div>
			<div class="container-fluid col-sm-offset-3">
				<div class="pub">
					<div class="boxcontainercontent">

						<div class="hlineheader">
							<div class="row">
								<div class="col-md-4 h4">
									<strong>GENERAL</strong>
								</div>
								<div class="col-md-8">&nbsp;</div>
							</div>

						</div>
						<?php
    $atencioTipos = new AtencionTipos();
    $atencionTipo = $atencioTipos->findActivas();
    if (! empty($atencionTipo)) {
        foreach ($atencionTipo as $clave => $value) {
            echo '
                                <div class="row">
							<div class="col-md-1  text-right">
								<input type="radio" name="tipoPeticion" value="' . $value["id"] . '" id="' . strtolower($value["nombre"]) . '" />
							</div>
							<div class="col-md-8 text-justify">
								<label for="department_37"><strong> ' . strtoupper($value["nombre"]) . ': </strong> </label>
								' . $value["descripcion"] . '
							</div>
						</div>
                                ';
        }
    }
    ?>
						<label for="tipoPeticion" class="error" style="display: none;">Por
							favor Seleccione Una opción</label>
					</div>

					<div class="row">
						<div class="col-md-3">
							<label class="desc desc desc     control-label form-control"
								id="title_Anonimo" for="anonimo">¿Desea que su petición sea
								anónima?<font color="#FF0000">*</font>
							</label>
						</div>
						<div class="col-md-8 text-left">
							<div class="radio-inline">
								<label class="radio-inline"> <input type="radio" name="anonimo"
									id="identifica" checked="checked" value="1" />No
								</label> <label class="radio-inline"> <input type="radio"
									name="anonimo" id="chkAnonimo" value="2" />Sí
								</label>
							</div>
						</div>
					</div>
					<div>

						<div class="noanonimo seltipo">
							<div class=" noanonimo seltipo row form-group">
								<div class="col-sm-12  col-md-3">
									<label class=" desc control-label form-control"
										for="tipoPersona">Tipo de Persona</label>
								</div>
								<div class="col-sm-12  col-md-3 ">
									<select data-placeholder="Seleccione una opción"
										title="Debe Seleccionaar un tipo de Persona, este campo  es Obligatorio"
										class="chosen-select form-control dropdown seleccion"
										id="tipoPersona" name="tipoPersona">
										<option value=""></option>
										<option value="2">Persona Natural</option>
										<option value="1">Persona Júridica</option>
										<option value="3">ESP</option>
									</select>
								</div>
							</div>
							<div class=" row col-sm-12  col-md-12 dinamic" id="juridica"
								style="display: none">
								<div class="row form-group">
									<div class="col-sm-12  col-md-3">
										<label class="desc desc     control-label form-control"
											for="txtnit">Nit*</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control obligatorio digitos" value=""
											maxlength="15"
											title="el campo Nit es obligatorio y solo acepta digitos"
											minlenght="3" name="nit" tabindex="4"
											onkeypress="return alpha(event,numbers)" id="txtnit"
											type="text" />

									</div>
									<div class="col-sm-12  col-md-3">
										<label class="desc desc     control-label form-control"
											for="txtnoEmpresa">Nombre Empresa*</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control obligatorio" value=""
											maxlength="80" title="el campo Nombre es obligatorio "
											minlenght="3" name="noEmpresa" tabindex="4"
											onkeypress="return alpha(event,numbers+letters)"
											id="txtnoEmpresa" type="text" />

									</div>
								</div>
								<div class="row form-group">
									<div class="col-sm-12  col-md-3">
										<label class="desc  control-label form-control" for="txtnit">Representante
											Legal*</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control obligatorio" value=""
											maxlength="80" title="el campo Representante es obligatorio "
											minlenght="3" name="representante" tabindex="4"
											onkeypress="return alpha(event,numbers+letters)" id="txtrep"
											type="text" />

									</div>
									<div class="col-sm-12  col-md-3">
										<label class="desc control-label form-control"
											for="txtdirEmpresa">Dirección*</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control obligatorio" value=""
											maxlength="150" tabindex="4"
											onkeypress="return alpha(event,numbers+letters)"
											title="El campo Dirección  de la empresa es obligatorio "
											minlenght="3" name="dirEmpresa" id="txtdirEmpresa"
											type="text" />

									</div>
								</div>
								<div class="row form-group">
									<div class="col-sm-12  col-md-3">
										<label class="desc desc     control-label form-control"
											for="txtnfijo">Teléfono Contacto*</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control" value="" maxlength="15"
											name="telEmpresa"
											title="El campo teléfono de la Empresa es Obligatorio"
											tabindex="4" onkeypress="return alpha(event,numbers+letters)"
											id="txtcontacto" type="text" />
									</div>
									<div class="col-sm-12  col-md-3">
										<label class="desc control-label form-control" for="txtemmail">Correo
											Eléctronico</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control obligatorio email" value=""
											name="emailEmpresa"
											title="el campo email es obligatorio y debe ser válido"
											minlenght="3" tabindex="4" id="txtmailEmpresa" type="text" />
									</div>
								</div>
								<div class="row form-group">
									<div class="col-sm-12  col-md-3">
										<label class="desc desc     control-label form-control"
											for="grupo">Tipo de Empresa*</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<select data-placeholder="Tipo de Empresa"
											title="Debe seleccione un tipo de la lista el campo es obligatorio"
											class="dropdown obligatorio seleccion form-control chosen-select "
											id="tipoEmpresa" name="tipoEmpresa">
											<option selected="" value="">Seleccione Tipo de Empresa</option>
										</select>
									</div>
								</div>
							</div>
							<div class=" row col-sm-12  col-md-12 dinamic" id="esp"
								style="display: none">
								<div class="row form-group">
									<div class="col-sm-12  col-md-3">
										<label class="desc  control-label form-control"
											for="txtnoEmpresa">Nombre ESP</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="obligatorio" type="hidden" id="idEsp" value="" />
										<input class="form-control" value="" maxlength="15"
											tabindex="4" onkeypress="return alpha(event,numbers+letters)"
											title="el campo Nombre de la ESP es obligatorio y debe ser seleccionado de la lista"
											minlenght="3" name="noEmpresa" id="txtnoesp" type="text" />
									</div>
									<div class="col-sm-12  col-md-3">
										<label class="desc desc     control-label form-control"
											for="txtNitEsp">Nit*</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control obligatorio" value=""
											maxlength="15" tabindex="4"
											onkeypress="return alpha(event,numbers+letters)"
											title="Debe seleccionar la ESP del Listado que aparece al digitar el nombre"
											name="nitESP" id="txtNitEsp" type="text" readonly="readonly" />
									</div>
								</div>
								<div class="row form-group">
									<div class="col-sm-12  col-md-3">
										<label class="desc control-label form-control" for="txtnit">Representante
											Legal</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control" value="" maxlength="80"
											tabindex="4" onkeypress="return alpha(event,numbers+letters)"
											id="txtRepEsp" type="text" disabled />

									</div>
									<div class="col-sm-12  col-md-3">
										<label class="desc desc     control-label form-control"
											for="txtdirEmpresa">Dirección</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control" value="" maxlength="100"
											tabindex="4" onkeypress="return alpha(event,numbers+letters)"
											id="txtDirESP" type="text" disabled />

									</div>
								</div>
								<div class="row form-group">
									<div class="col-sm-12  col-md-3">
										<label class="desc control-label form-control" for="txtnfijo">Teléfono
											de Contacto</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control" value="" maxlength="15"
											tabindex="4" onkeypress="return alpha(event,numbers+letters)"
											id="txtcontacto" type="text" disabled />

									</div>
									<div class="col-sm-12  col-md-3">
										<label class="desc desc     control-label form-control"
											for="txtemmail">Correo Eléctronico</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control" value="" maxlength="80"
											tabindex="4" onkeypress="return alpha(event,numbers+letters)"
											id="txtemmail" type="text" disabled />
									</div>
								</div>
								<div class="row form-group">
									<div class="col-sm-12  col-md-3">
										<label class="desc desc     control-label form-control"
											for="grupo">Tipo de ESP*</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<select data-placeholder="Tipo de Empresa"
											title="Debe seleccione un tipo de la lista el campo es obligatorio"
											class="dropdown obligatorio seleccion form-control chosen-select "
											id="tipoESP" name="tipoESP">
											<option selected="" value="">Seleccione Tipo de Empresa</option>
										</select>
									</div>
								</div>
							</div>
							<div class=" row col-sm-12  col-md-12 dinamic" id="persona"
								style="display: none">
								<div class="row form-group">
									<div class="col-sm-12  col-md-3">
										<label class="desc desc     control-label form-control"
											for="tipoDocumento">Tipo de documento *</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<select data-placeholder="tipo de documento"
											title="Debe Seleccionar un Tipo de Documento el campo es obligatorio "
											name="tipoDocumento"
											class="dropdown obligatorio seleccion chosen-select form-control"
											id="tipoDocumento">
											<option value=""></option>
										</select>
									</div>
									<div class="col-sm-12  col-md-3">
										<label class="desc  control-label form-control"
											for="txtdocumento"><span>Documento de Identidad</span></label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control obligatorio" value=""
											maxlength="1555555"
											title="Debe Dilígenciar el Documento el campo es obligatorio "
											minlenght="3" name="documento" tabindex="4"
											onkeypress="return alpha(event,numbers+letters)"
											id="txtdocumento" type="text" />

									</div>
								</div>
								<div class="row form-group">
									<div class="col-sm-12  col-md-3">
										<label class="desc control-label form-control"
											for="txtapellido1">Primer Apellido*</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control obligatorio" value=""
											maxlength="50"
											title="el campo Primer Apellido es obligatorio "
											minlenght="3" name="primApellido" tabindex="4"
											onkeypress="return alpha(event,numbers+letters)"
											id="txtapellido1" type="text" />

									</div>
									<div class="col-sm-12  col-md-3">
										<label class="desc   control-label form-control"
											for="txtapellido2">Segundo Apellido</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control" value="" maxlength="50"
											name="segApellido" tabindex="4"
											onkeypress="return alpha(event,numbers+letters)"
											id="txtapellido2" type="text" />

									</div>
								</div>
								<div class="row form-group">
									<div class="col-sm-12  col-md-3">
										<label class="desc desc     control-label form-control"
											for="txtnombre1">Primer Nombre*</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control obligatorio" value=""
											maxlength="50"
											title="el campo tipo de Documento es obligatorio "
											minlenght="3" name="primNombre" tabindex="4"
											onkeypress="return alpha(event,numbers+letters)"
											id="txtnombre1" type="text" />

									</div>
									<div class="col-sm-12  col-md-3">
										<label class="desc desc     control-label form-control"
											for="txtnombre2">Segundo Nombre</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control" value="" maxlength="50"
											name="segNombre" tabindex="4"
											onkeypress="return alpha(event,numbers+letters)"
											id="txtnombre2" type="text" />
									</div>
								</div>
								<div class="row form-group">
									<div class="col-sm-12  col-md-3">
										<label class="desc  control-label form-control" for="txtdir">Dirección*</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control obligatorio" value=""
											maxlength="100" title="el campo Dirección es obligatorio "
											minlenght="3" name="direccion" tabindex="4"
											onkeypress="return alpha(event,numbers+letters)" id="txtdir"
											type="text" />

									</div>
									<div class="col-sm-12  col-md-3">
										<label class="desc desc     control-label form-control"
											for="txtemail">Correo Eléctronico</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control email" value="" maxlength="50"
											title="el campo email es obligatorio y debe ser válido"
											minlenght="3" name="email" tabindex="4" id="txtemail"
											type="text" />
									</div>
								</div>
								<div class="row form-group">
									<div class="col-sm-12  col-md-3">
										<label class="desc  control-label form-control" for="txtnfijo">Teléfono
											Fijo</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control" value="" maxlength="15"
											name="telefono" tabindex="4"
											onkeypress="return alpha(event,numbers+letters)"
											id="txtnfijo" type="text" />
									</div>
									<div class="col-sm-12  col-md-3">
										<label class="desc  control-label form-control"
											for="txtcelular">Móvil Célular</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<input class="form-control" value="" maxlength="14"
											name="celular" tabindex="4"
											onkeypress="return alpha(event,numbers+letters)"
											id="txtcelular" type="text" />

									</div>
								</div>
								<div class="row form-group">
									<div class="col-sm-12  col-md-3">
										<label class="desc  control-label form-control" for="txtnfijo">Sexo*</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<select
											class="form-control obligatorio dropdown chosen-select seleccion"
											title="el campo es obligatorio " minlenght="3" name="sexo"
											id="sexo">
											<option value="2">Masculino</option>
											<option value="1">Femenino</option>
										</select>
									</div>
									<div class="col-sm-12  col-md-3">
										<label class="desc desc     control-label form-control"
											for="grupo">Grupo Poblacional*</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<select data-placeholder="Grupo Poblacional"
											title="Debe seleccionar un grupo de la lista el campo es obligatorio"
											class="dropdown obligatorio seleccion form-control chosen-select "
											id="grupo" name="grupo">
											<option selected="" value="">Seleccione Grupo Poblacional</option>
											<option value="0">NINGUNO, N/A</option>
											<option value="1">NIÑOS, NIÑAS, ADOLESCENTES</option>
											<option value="2">POBLACIÓN EN CONDICIÓN DE DISCAPACIDAD</option>
											<option value="3">POBLACIÓN EN SITUACIÓN DE DESPLAZAMIENTO</option>
											<option value="4">DESMOVILIZADOS</option>
											<option value="5">POBLACIÓN ROM</option>
											<option value="6">POBLACIÓN RAIZAL</option>
											<option value="7">AFROCOLOMBIANO</option>
											<option value="8">MIGRATORIO</option>
											<option value="9">POBLACIÓN RURAL</option>
											<option value="10">VÍCTIMA DE VIOLENCIA ARMADA</option>
											<option value="11">POBLACIÓN LGBTI</option>
											<option value="12">VOCALES DE CONTROL</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row form-group noanonimo">
							<div class="col-sm-12  col-md-3">
								<label class="desc control-label form-control" for="txtnombre2">Autoriza
									ser Notificado por correo electrónico </label>
							</div>
							<div class="col-sm-12  col-md-3 ">
								<label class="checkbox-inline"><input type="checkbox" value="1"
									name="notifica" />Sí</label> <label class="checkbox-inline"><input
									type="checkbox" value="2" name="notifica" />No</label>
							</div>
						</div>
						<div class="row col-sm-12  col-md-12" id="ubicacion">
							<div class="row form-group">
								<div class="col-sm-12  col-md-3">
									<label class="desc desc     control-label form-control"
										for="pais">País*</label>
								</div>
								<div class="col-sm-12  col-md-3 ">
									<select data-placeholder="País"
										title="Debe seleccionar un pais el el campo es obligatorio "
										name="pais" class="chosen-select form-control seleccion"
										id="pais">
										<option value=""></option>
									</select>
								</div>
								<div class="col-sm-12  col-md-3">
									<label for="dpto" class="error" style="display: none;">Seleccione
										un Departamento el campo es Obligatorio</label> <label
										class="desc control-label form-control" for="txtdocumento"><span>Departamento*</span></label>
								</div>
								<div class="col-sm-12  col-md-3 ">
									<select data-placeholder="Departamento"
										class="chosen-select form-control dropdown seleccion"
										id="dpto" title="el campo Departamento es obligatorio "
										name="dpto">
									</select>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-sm-12  col-md-3">
									<label class="desc desc     control-label form-control"
										for="txtapellido1">Municipio*</label>
								</div>
								<div class="col-sm-12  col-md-3 ">
									<select data-placeholder="Municipio"
										class="chosen-select form-control dropdown seleccion"
										id="mcpio" title="el campo tipo de Municipio es obligatorio "
										minlenght="3" name="mncpio">
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label class=" desc control-label form-control" id="lbl_asunto"
							for="campo_asunto">Asunto de la PQRS<font color="#FF0000">*</font>
						</label>
					</div>
					<div class="col-md-8">
						<input id="asunto" name="asunto" type="text"
							class="form-control large" value="" maxlength="80" tabindex="15"
							required minlength="6"
							title="el ausnto es obligatorio y debe ser de máximo 80 carácteres y minímo 3"
							placeholder="asunto" /> &nbsp;
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12  col-md-2">
						<label class=" desc desc     control-label form-control"
							id="lbl_comentario" for="campo_comentario">Descripción de la
							PQRSD <font color="#FF0000">*</font>
						</label>
					</div>
					<div class="col-sm-12 col-md-8">
						<textarea id="campo_comentario" name="comentario"
							class="form-controlarea textarea large" rows="10" cols="50"
							tabindex="16" onkeyup="countChar(this)"
							placeholder="Escriba ac&aacute; ..." required minlength="6"
							title="la descripción de la solicitud es obligatorio y debe ser de máximo 2000 carácteres y minímo 3"></textarea>
						<input type="hidden" id="adjuntosSubidos" name="adjuntosSubidos"
							value="" /> &nbsp;
					</div>
				</div>
				<div align="right" id="charNum"></div>
				<div class="row">
					<div id="li_upload">
						<div id="filelimit-fine-uploader"></div>
						<div id="availabeForUpload"></div>
						&nbsp;
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label class="desc desc desc control-label form-control"
							id="lbl_captcha" for="campo_captcha">Imagen de
							verificaci&oacute;n (Digite en el recuadro las letras o número de
							la imagen). <font color="#FF0000">*</font>
						</label>
					</div>
					<div class="col-md-6">
						<input id="campo_captcha" name="captcha" type="text"
							class="form-control small" value="" maxlength="5" tabindex="20"
							onkeypress="return alpha(event,numbers+letters)"
							title="Digite las letras y n&uacute;meros de la im&aacute;gen, el campo esobligatorio"
							alt="Digite las letras y n&uacute;meros de la im&aacute;gen"
							required /> &nbsp;
						<p><?php
    echo '<img id="imgcaptcha" src="' . $_SESSION['captcha_formulario']['image_src'] . '" alt="CAPTCHA" /><br>';
    echo '<a href="#" onClick="return reloadImg(\'imgcaptcha\');">Cambiar imagen<a>'?></p>
						<input type="hidden" name="pqrsFacebook" value="<?=$isFacebook?>" />
						<input type="hidden" name="idFormulario"
							value="<?=$_SESSION["idFormulario"]?>" />
					</div>
				</div>
				<div class="row">
					<div class="col-sm-8">
						<input id="saveForm" type="submit" value="Enviar"
							class="btn btn-primary" onclick="valida_form();" /> <input
							name="button" type="button" id="button" onclick="window.location='index.php';"
							value="Cancelar" class="btn btn-primary" />
					</div>
				</div>
		</form>
	</div>
	</div>
	<!--container-->
	<div id="errores"></div>
	<div class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">×</button>
					<h4 class="modal-title glyphicon glyphicon-off">Modal title</h4>
				</div>
				<div class="modal-body">
					<p>One fine body…</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<script type="text/javascript"> 
	$("#txtnoesp").autocomplete({
		  select :function( event, ui ){
			  event.preventDefault();
			  $("#txtrepesp").val(ui.item.SGD_CIU_APELL2);
			  $("#txtnoesp").val(ui.item.SGD_CIU_NOMBRE);
			  $("#txtdiresp").val(ui.item.SGD_CIU_DIRECCION);
			  $("#txtemailesp").val(ui.item.SGD_CIU_EMAIL);
			  $("#txtcontactoesp").val(ui.item.SGD_CIU_TELEFONO);
			  $("#idEsp").val(ui.item.SGD_CIU_CODIGO)
			  $("#txtNitEsp").val(ui.item.SGD_CIU_CEDULA);
		  },
		  source: function(request, response) {
		    $.ajax({
		    	type : "POST",
				url : basePath+ '/atencion/controllerAtencion.php',
				 dataType: "json",
				data: {op:"buscarEsp",identificacion: $("#txtnoesp").val()
		      },
		      success: function(data) {
		    	if (data.success) {
		          response($.map(data.data, function(item) {
		            return {
		            	  SGD_CIU_CEDULA:item.SGD_CIU_CEDULA,
		            	  SGD_CIU_APELL2:item.SGD_CIU_APELL2+" "+item.SGD_CIU_APELL1,
		            	  SGD_CIU_NOMBRE:item.SGD_CIU_NOMBRE,
						  SGD_CIU_DIRECCION:item.SGD_CIU_DIRECCION,
						  SGD_CIU_EMAIL:item.SGD_CIU_EMAIL , 
						  SGD_CIU_TELEFONO:item.SGD_CIU_TELEFONO,
						  SGD_CIU_CODIGO:item.SGD_CIU_CODIGO,
		            	  label: item.SGD_CIU_APELL2 + " " +item.SGD_CIU_NOMBRE,
		                 per:item
		            }
		            
		          }));
		        } 
		      }
		    });
		  },
		  minLength: 3
		});
	</script>
</body>
</html>
