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
/*
 * Modulo de CCU
 * @autor CARLOS RICAURTE
 * @autor cricaurte
 * @fecha 2022/01
 */
$ruta_raiz = "..";
$ADODB_COUNTRECS = false;
require_once ("$ruta_raiz/include/db/ConnectionHandler.php");
require_once realpath(dirname(__FILE__) . "/../") . "/atencion/AtencionTipos.php";
$_SESSION["idFormulario"] = sha1(microtime(true) . mt_rand(10000, 90000));
$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);


// TamaNo mAximo del todos los archivos en bytes 10MB = 10(MB)*1024(KB)*1024(B) = 10485760 bytes
$max_file_size = 10485760;

if (! isset($isFacebook)) {
    $isFacebook = 0;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

	<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>

	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/chosen.jquery.js"></script>
	<script src="../js/jquery-ui.min.js"></script>
	<!-- Just to make our placeholder images work. Don't actually copy the next line! -->
	<script src="../js/holder.min.js"></script>
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="../js/ie10-viewport-bug-workaround.js"></script>
	<!-- jQuery -->
	<!-- FineUploader -->
	<script type="text/javascript" src="scripts/jquery.fineuploader-3.0.js"></script>
	<script type="text/javascript" src="../js/jquery.validate.min.js?sdf=<?php echo date("ymdhis")?>"></script>
	<!--funciones-->
	<script type="text/javascript" src="../js/divipola.js?tes=<?php echo date("Ymdhis")?>"></script>
	<script type="text/javascript" src="../js/bootstrap-dialog.js"></script>

	
	<script type="text/javascript" src="scripts/ajax.js?tes=<?php echo date("Ymdhis")?>"></script>
	<script type="text/javascript">
		var basePath ='<?php echo $ruta_raiz?>';
		 var urlEntidad ='<?php echo  $httpWebOficial ?>';
	</script>


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

	<script src='https://www.google.com/recaptcha/api.js?render=6Lfj3FMeAAAAANvXDM8sUjExBkPkOy0EnK5tH9VU'> 
    </script>
	  <script>
	      grecaptcha.ready(function() {
	      grecaptcha.execute('6Lfj3FMeAAAAANvXDM8sUjExBkPkOy0EnK5tH9VU', {action: 'contactoOrfeo'})
	      .then(function(token) {
	      var recaptchaResponse = document.getElementById('recaptchaResponse');
	      recaptchaResponse.value = token;
	      });});
	  </script>
	
<style>
	#boton{display:none;}
</style>
</head>
<body id="public">
	<div class="container-fluid">
		<h1>&nbsp;</h1>
		<form id="contactoOrfeo" class="" autocomplete="off" enctype="multipart/form-data" method="post" action="formulario873AA1tx.php" name="quejas">
			<input type="hidden" name="recaptcha_response" id="recaptchaResponse">
			<input type="hidden" id="adjuntosSubidos" name="adjuntosSubidos"/>
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
				<div class="col-md-8 h3"
					style="background-color: #dedede; margin-top: 0px;">
					<span> <strong>ANEXO No. 1</strong><br>
					<span> <strong>Resolución CRA 873 AA</strong>
						<br /> 
						<span class="h5">Modelo de Condiciones Uniformes del Contrato de Servicios Públicos para personas prestadoras de los servicios públicos domiciliarios de Acueducto y/o Alcantarillado que a 31 de diciembre de 2013 atiendan en sus APS hasta 5.000 suscriptores en el área urbana y aquellas que presten el servicio en el área rural independientemente del número de suscriptores que atiendan</span>
						<br /> 
						<span class="h5"> Los campos con (<font color="#FF0000">*</font>) son obligatorios.</span>
					</span>
				</div>
			</div>
			<div class="container-fluid col-sm-offset-1">
				<div class="pub">
					<div>
					<!-- ************************** pagina 1 **********************************-->
						<div class="pagina1 seltipo">
							<div class="noanonimo seltipo">
								<div class=" noanonimo seltipo row form-group">
									<div class="col-sm-12  col-md-3">
										<label class=" desc control-label form-control"
											for="tipoPersona">Tipo de Persona *</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<select data-placeholder="Seleccione una opción"
											title="Debe Seleccionaar un tipo de Persona, este campo  es Obligatorio"
											class="chosen-select form-control dropdown seleccion"
											id="tipoPersona" name="tipoPersona"  >
											<option value=""></option>
											<option value="2">Persona Natural</option>
											<option value="1">Persona Júridica</option>
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
												minlenght="3" name="txtnoEmpresa" tabindex="4"
												id="txtnoEmpresa" type="text"  />

										</div>
									</div>
									<div class="row form-group">
										<div class="col-sm-12  col-md-3">
											<label class="desc  control-label form-control" for="txtrep">Representante
												Legal*</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control obligatorio" value=""
												maxlength="80" title="el campo Representante es obligatorio "
												minlenght="3" name="txtrep" tabindex="4" id="txtrep"
												type="text"  />

										</div>
										<div class="col-sm-12  col-md-3">
											<label class="desc control-label form-control"
												for="txtdirEmpresa">Dirección*</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control obligatorio" value=""
												maxlength="150" tabindex="4"
												title="El campo Dirección  de la empresa es obligatorio "
												minlenght="3" name="txtdirEmpresa" id="txtdirEmpresa"
												type="text"  />

										</div>
									</div>
									<div class="row form-group">
										<div class="col-sm-12  col-md-3">
											<label class="desc desc   obligatorio  control-label form-control"
												for="txtnfijo">Teléfono Contacto*</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control " value="" maxlength="15"
												name="txtcontacto"
												title="El campo teléfono de la Empresa es Obligatorio"
												tabindex="4" id="txtcontacto" type="text"  />
										</div>
										<div class="col-sm-12  col-md-3">
											<label class="desc control-label form-control" for="txtemmail">Correo Eléctronico</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control obligatorio email" value=""
												name="emailEmpresa"
												title="el campo email es obligatorio y debe ser válido"
												minlenght="3" tabindex="4" id="txtmailEmpresa" type="text"  />
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
										<div class="col-sm-12  col-md-3">
											<label class="desc  control-label form-control" for="barrioJ">Barrio*</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control" value="" maxlength="100"
												name="barrioJ" tabindex="10"
												id="barrioJ" type="text"  />
										</div>
									</div>
									<div class="row form-group">
										<div class="col-sm-12  col-md-3">
											<label class="desc  control-label form-control" for="paginaJ">Página Web*</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control" value="" maxlength="100"
												name="paginaJ" tabindex="10"
												id="paginaJ" type="text"  />
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
												
												id="txtapellido1" type="text" />

										</div>
										<div class="col-sm-12  col-md-3">
											<label class="desc   control-label form-control"
												for="txtapellido2">Segundo Apellido</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control" value="" maxlength="50"
												name="segApellido" tabindex="4"
												
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
												id="txtnombre1" type="text" />

										</div>
										<div class="col-sm-12  col-md-3">
											<label class="desc desc     control-label form-control"
												for="txtnombre2">Segundo Nombre</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control" value="" maxlength="50"
												name="segNombre" tabindex="4"
												
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
												
												id="txtdir" type="text" />

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
											<label class="desc  control-label form-control" for="txtnfijo">Teléfono Fijo</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control" value="" maxlength="15"
												name="telefono" tabindex="4"
												id="txtnfijo" type="text" />

										</div>
										<div class="col-sm-12  col-md-3">
											<label class="desc  control-label form-control"
												for="txtcelular">Móvil Célular</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control" value="" maxlength="14"
												name="celular" tabindex="4"
												
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
											<label class="desc  control-label form-control" for="barrioN">Barrio*</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control obligatorio" value="" maxlength="100"
												name="barrioN" tabindex="10" title="el campo Barrio es obligatorio "
												
												id="barrioN" type="text" />
										</div>
									</div>
									<div class="row form-group">
										<div class="col-sm-12  col-md-3">
											<label class="desc  control-label form-control" for="paginaN">Página Web*</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control" value="" maxlength="100"
												name="paginaN" tabindex="10"
												
												id="paginaN" type="text" />
										</div>
									</div>
								</div>
							</div>
							<div class="row col-sm-12  col-md-12" id="ubicacion">
								<div class="row form-group">
									
									<div class="col-sm-12  col-md-3 " style="display: none">
										<select data-placeholder="País"
											title="Debe seleccionar un pais el el campo es obligatorio "
											name="pais" class="chosen-select form-control seleccion"
											id="pais1">
											<option value="1-170" selected>COLOMBIA</option>
										</select>
									</div>
									<div class="col-sm-12  col-md-3">
										<label class="desc desc     control-label form-control"
											for="depto">Departamento*</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<select name="dpto" id="dpto" class="chosen-select form-control  obligatorio dropdown seleccion" 
										title="Debe Seleccionar un Departamento, este campo es Obligatorio" required>
											<option value="0">Selecciona Uno...</option>
											<option value="170-91">AMAZONAS</option>
											<option value="170-5">ANTIOQUIA</option>
											<option value="170-81">ARAUCA</option>
											<option value="170-8">ATLANTICO</option>
											<option value="170-11">BOGOTÁ D.C.</option>
											<option value="170-13">BOLIVAR</option>
											<option value="170-15">BOYACA</option>
											<option value="170-17">CALDAS</option>
											<option value="170-18">CAQUETA</option>
											<option value="170-85">CASANARE</option>
											<option value="170-19">CAUCA</option>
											<option value="170-20">CESAR</option>
											<option value="170-27">CHOCO</option>
											<option value="170-23">CORDOBA</option>
											<option value="170-25">CUNDINAMARCA</option>
											<option value="170-94">GUAINIA</option>
											<option value="170-95">GUAVIARE</option>
											<option value="170-41">HUILA</option>
											<option value="170-44">LA GUAJIRA</option>
											<option value="170-47">MAGDALENA</option>
											<option value="170-50">META</option>
											<option value="170-52">NARINO</option>
											<option value="170-54">NORTE DE SANTANDER</option>
											<option value="170-86">PUTUMAYO</option>
											<option value="170-63">QUINDIO</option>
											<option value="170-66">RISARALDA</option>
											<option value="170-88">SAN ANDRES</option>
											<option value="170-68">SANTANDER</option>
											<option value="170-70">SUCRE</option>
											<option value="170-1">TODOS</option>
											<option value="170-73">TOLIMA</option>
											<option value="170-76">VALLE DEL CAUCA</option>
											<option value="170-97">VAUPES</option>
											<option value="170-99">VICHADA</option>
								        </select>
									</div>
									<div class="col-sm-12  col-md-3">
										<label class="desc desc     control-label form-control"
											for="mncpio">Municipio*</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<select data-placeholder="Municipio"
											class="chosen-select form-control dropdown seleccion"
											id="mcpio" name="mncpio" title="Debe diligenciar Municipio, es Obligatorio" required>
								            <option value="0">Selecciona Uno...</option>
								        </select>
									</div>
								</div>
							</div>
							<div class="row" style="display:none">
								<div class="col-md-2">
									<label class=" desc control-label form-control" id="lbl_asunto"
										for="campo_asunto">Asunto:<font color="#FF0000">*</font>
									</label>
								</div>
								<div class="col-md-8">
									<input id="asunto" name="asunto" type="text"
										class="form-control large" value="Asistente para generación de Contratos de Condiciones Uniformes (CCU) " maxlength="80" tabindex="15"
										required minlength="6"
										
										title="el ausnto es obligatorio y debe ser de máximo 80 carácteres y minímo 3"
										placeholder="asunto" /> &nbsp;
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12 col-md-8">
									<textarea id="campo_comentario" name="comentario"
										class="form-controlarea textarea large" style="display:none"
										tabindex="16" onkeyup="countChar(this)"
										
										required minlength="6">Emisión de concepto de legalidad sobre Contratos de Condiciones Uniformes (CCU)
									</textarea>
									&nbsp;
								</div>
							</div>

							<input type="button" id="next1" name="next1" value="Siguiente" />
						</div>
						
			<!-- ************************** fin pagina 1 **********************************-->

			<!-- ************************** pagina 2 **********************************-->
						<div class="pagina2"  style="display: none">
								<div class="seltipo row form-group">
									<div class="col-sm-9  col-md-9 " align="left">
										<label>CLÁUSULA 1. El presente contrato de servicios públicos domiciliarios tiene por objeto la prestación de los servicios públicos domiciliarios de: </label>
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-12  col-md-12 " align="left">
										<label>CLÁUSULA 2. EL SERVICIO. La PERSONA PRESTADORA prestará los servicios de:</label><br>
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-2  col-md-2 form-control" align="left"
										title="Debe Seleccionar el Tipo de Servicio">
										<label><input type="checkbox" id="C02_acueducto" value="1" name="C02_acueducto"> Acueducto</label>
										<label><input type="checkbox" id="C02_alcatarillado" value="1" name="C02_alcatarillado"> Alcantarillado</label>
									</div>
								</div>

								<div class="seltipo row form-group">
									<div class="col-sm-9  col-md-9 " align="left">
										<label>CLÁUSULA 3. INMUEBLE. La PERSONA PRESTADORA prestará el servicio en un inmueble:</label>
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-2  col-md-2 " align="left">
										<label><input type="checkbox" id="C03_urbano" value="1" name="C03_urbano"> Urbano</label>
										<label><input type="checkbox" id="C03_rural" value="1" name="C03_rural"> Rural</label>
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-12  col-md-12 " align="left">
										<label>CLÁUSULA 4. ÁREA DE PRESTACIÓN DEL SERVICIO -APS. La PERSONA PRESTADORA prestará los servicios en la siguiente área:</label><br>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4" align="left">
										<label>Indicar nombre de la APS o hacer una descripción de la misma:</label>
									</div>
									<div class="col-md-6" align="left">
										<input type="text"  id="C04_area" name="C04_area" size="50"> 
									</div>
								</div>
								<div class="row">
									<div class="col-md-2" align="left">
										<label>Municipio:</label>
									</div>
									<div class="col-md-3" align="left">
										<input type="text" id="C04_municipio" name="C04_municipio" > 
									</div>
								</div>
								<div class="row">
									<div class="col-md-2" align="left">
										<label>Nombre vereda(s):</label>
									</div>
									<div class="col-md-3" align="left">
										<input type="text" id="C04_vereda" name="C04_vereda" size="50" > 
									</div>
								</div>
								<div class="row">
									<div class="col-md-2" align="left">
										<label>Centro Poblado Rural (Diligenciar si aplica) :</label>
									</div>
									<div class="col-md-3" align="left">
										<input type="text" id="C04_poblado" name="C04_poblado" size="50"> 
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-2" align="left">
										<label>Departamento:</label>
									</div>
									<div class="col-md-3" align="left">
										<input type="text" id="C04_departamento" name="C04_departamento" size="50" > 
									</div>
								</div>
								<div class="row">
									<div class="col-md-2" align="left">
										<label>Nombre Corregimiento:</label>
									</div>
									<div class="col-md-3" align="left">
										<input type="text" id="C04_corregimiento" name="C04_corregimiento" > 
									</div>
								</div>
								<div class="row">
									<div class="col-md-2" align="left">
										<label>Horario de atención:</label>
									</div>
									<div class="col-md-3" align="left">
										<input type="text" id="C04_horario" name="C04_horario" > 
									</div>
								</div>
								<div class="row">
									<div class="col-md-2" align="left">
										<label>Cargo del funcionario que resuelve:</label>
									</div>
									<div class="col-md-3" align="left">
										<input type="text" id="C04_cargo" name="C04_cargo" > 
									</div>
								</div>
								<div class="seltipo row">
									<div class="col-md-12" align="left">
										<label>Cláusula 5. VIGENCIA: El contrato de servicios públicos domiciliarios se pacta a término indefinido o fijo, sin embargo, en el evento en que sea fijo este no podrá ser superior a 2 años.</label>
									</div>
									<div class="row">
										<div class="col-sm-12  col-md-3"  align="left">
											<label><input type="radio" value="1" id="C05_fijo" name="C05_fijo" checked="checked"> Fija</label>
											<label><input type="radio" value="2" id="C05_indefinida" name="C05_fijo"> Indefinida</label>
										</div>
										<div class="divfijo" style="display" >
											<div class="col-md-3" align="left">
												<label>Duracion (meses):</label>
											</div>
											<div class="col-md-3" align="left">
												<input type="text" id="C05_duracion" name="C05_duracion"> 
											</div>
										</div>
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-4  col-md-4 " align="left">
										<label>CLÁUSULA 16. ENTREGA DE LA FACTURA. La PERSONA PRESTADORA entregará las facturas observando las:</label><br>
										<label><input type="radio" id="C16_mensual" value="1" name="C16_mensual" checked="checked"> Mensual:</label>
										<label><input type="radio" id="C16_bimensual" value="2" name="C16_mensual"> Bimestral</label>
										
										<br><br>
									</div><br>
								</div>
								
								
								<input type="button" id="previous2" name="previous2" value="Anterior" />
								<input type="button" value="Siguiente" onclick="validaPagina('873AAA1',2);" />
							
						</div>
						
			<!-- ************************** fin pagina 2 ********************************-->

			<!-- ************************** inicio pagina 3 ********************************-->
							<div class="pagina3"  style="display: none">
								<div class="seltipo row form-group">
									<div class="col-sm-9  col-md-9 " align="left">
										<label>CONDICIONES TÉCNICAS ACUEDUCTO</label><br>
										<label>A.	Servicio de Acueducto</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4" align="left">
										<label>Diámetro mínimo de la acometida de Acueducto(pulgadas):</label>
									</div>
									<div class="col-md-" align="left">
										<input type="text" id="C35_diametro_minimo_acueducto" name="C35_diametro_minimo_acueducto" > 
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-9  col-md-9 " align="left">
										<label>Medidores de Acueducto</label><br>
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-4  col-md-4 " align="left">
										<label>•Tipo:</label><br>
										<label><input type="checkbox" id="C35_velocidad" value="1" name="C35_velocidad"> Velocidad</label>
										<label><input type="checkbox" id="C35_volumetrico" value="1" name="C35_volumetrico"> Volumétrico</label>
										<label><input type="checkbox" id="C35_electromagnetico" value="1" name="C35_electromagnetico"> Electromagnético</label>
										<label><input type="checkbox" id="C35_concentrico" value="1" name="C35_concentrico"> Concéntrico</label>
										<label><input type="text"  id="C35_otro1" name="C35_otro1"> Otro Cual?</label><br><br>
										<label>•Especificaciones adicionales del tipo de medidor:</label><br>
										<label><input type="checkbox" id="C35_telemetria" value="1" name="C35_telemetria"> Telemetría</label>
										<label><input type="checkbox" id="C35_prepago" value="1" name="C35_prepago"> Prepago</label>
										<label><input type="text"  id="C35_otro2" name="C35_otro2"> Otro Cual?</label><br><br>
										<label>•Diametro:</label>
										<label><input type="text"  id="C35_diametro_tmedidor" name="C35_diametro_tmedidor"> Pulgadas</label><br><br>
										<label>Caudal permanente (Q3):</label>
										<label><input type="text"  id="C35_caudal" name="C35_caudal"> m3/hora</label><br><br>
										<label>Rango de medición (R):</label>
										<input type="text"  id="C35_rango" name="C35_rango"><br>
									</div>
								</div>

								<div class="seltipo row form-group">
									<div class="col-sm-9  col-md-9 " align="left">
										<label>CONDICIONES TÉCNICAS ALCANTARILLADO</label><br>
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-md-4" align="left">
										<label>Diámetro mínimo de la acometida de alcantarillado(pulgadas):</label>
									</div>
									<div class="col-md-" align="left">
										<input type="text" id="C35_alcantarillado" name="C35_alcantarillado" > 
									</div>
								</div>
								
								<input type="button" id="previous3" name="previous3" value="Anterior" />
								<input type="button" value="Siguiente" onclick="validaPagina(873,3);" />
							</div>
			<!-- ************************** fin pagina 3 ********************************-->

			<!-- ************************** inicio pagina 4 ********************************-->
							<div class="pagina4"  style="display: none">
								<div class="seltipo row form-group">
									<div class="col-sm-9  col-md-9 " align="left">
										<label>CLÁUSULA 37. CONDICIONES DE CALIDAD. Para la prestación de los servicios públicos domiciliarios de acueducto y/o alcantarillado, las partes del contrato cumplirán las siguientes condiciones de calidad, continuidad y presión:</label><br>
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-md-6" align="left">
										<label>La PERSONA PRESTADORA prestará el servicio de acueducto con una continuidad de:</label>
									</div>
									<div class="col-md-" align="left">
										<input type="text" id="C37_horas" name="C37_horas" > Horas al día
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-md-4" align="left">
										<label>La PERSONA PRESTADORA prestará el servicio de acueducto con una presión de servicio mínima en la red de distribución de:</label>
									</div>
									<div class="col-md-8" align="left">
										<input type="text" id="C37_metros" name="C37_metros" > metros columna de agua (m.c.a.), definida con base en el artículo 61 de la Resolución 0330 de 2017 del Ministerio de Vivienda, Ciudad y Territorio (MVCT) o la que la modifique, adicione, sustituya o derogue.
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-12  col-md-12 " align="left">
										<label title="Las metas del servicio público domiciliario de acueducto a incluir deben corresponder a los definidas en el estudio de costos en aplicación de la metodología tarifaria contenida en la Resolución CRA 825 de 2017. Según lo dispuesto en el parágrafo 4 de la cláusula 12 o 24 según el segmento que corresponda">CLÁUSULA 38. METAS DEL SERVICIO PÚBLICO DOMICILIARIO DE ACUEDUCTO. La PERSONA PRESTADORA se compromete a prestar el servicio público domiciliario de acueducto, de acuerdo con lo establecido en los artículos 12 y 24 de la Resolución CRA 825 de 2017 o la que la modifique, adicione, sustituya o derogue, para el primero y segundo segmento respectivamente, con las siguientes metas anuales para reducir la diferencia entre el valor del año base y el estándar de servicio:</label><br>
										<label><input type="radio" id="C38_segmento1" value="1" name="C38_segmento1" checked="checked"> Primer Segmento</label>
										<label><input type="radio" id="C38_segmento2" value="0" name="C38_segmento1"> Segundo Segmento</label>
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-12  col-md-12 " align="left">
										<table border="1">
										   <caption>El prestador podrá incluir datos en el siguiente cuadro con números: </caption>

										   <thead> 
										       <tr>
										           <th>Nombre del estándar</th>
										           <th>Unidad</th>
										           <th>Estándar</th>
										           <th>Año base</th>
										           <th>META AÑO 1</th>
										           <th>META AÑO 2</th>
										           <th>META AÑO 3</th>
										           <th>META AÑO 4</th>
										           <th>META AÑO 5</th>
										           <th>META AÑO 6</th>
										           <th>META AÑO 7</th>
										       </tr>
										   </thead>

										   <tbody> <!-- Cuerpo de la tabla -->
										       <tr>
										           <td>Micromedición</td>
										           <td>Número de suscriptores con micromedidor instalado sobre el número de suscriptores facturados promedio en el año base</td>
										           <td>100%</td>
										           <td><input class="form-control" value="" name="C35_micro_base" tabindex="1" id="C35_micro_base" type="text"  /></td>
										           <td><input class="form-control" value="" name="C35_micro_meta1" tabindex="1" id="C35_micro_meta1" type="text"  /></td>
										           <td><input class="form-control" value="" name="C35_micro_meta2" tabindex="1" id="C35_micro_meta2" type="text"  /></td>
										           <td><input class="form-control" value="" name="C35_micro_meta3" tabindex="1" id="C35_micro_meta3" type="text"  /></td>
										           <td><input class="form-control" value="" name="C35_micro_meta4" tabindex="1" id="C35_micro_meta4" type="text"  /></td>
										           <td><input class="form-control" value="" name="C35_micro_meta5" tabindex="1" id="C35_micro_meta5" type="text"  /></td>
										           <td><input class="form-control" value="" name="C35_micro_meta6" tabindex="1" id="C35_micro_meta6" type="text"  /></td>
										           <td><input class="form-control" value="" name="C35_micro_meta7" tabindex="1" id="C35_micro_meta7" type="text"  /></td>
										      </tr>
										      <tr>
										           <td>Continuidad para el servicio público domiciliario de acueducto</td>
										           <td>Número de días por año de prestación del servicio</td>
										           <td>Máximo 10 días sin servicio al año (97,26% de continuidad anual).</td>
										           <td><input class="form-control" value="" name="C35_continuidad_base" tabindex="1" id="C35_continuidad_base" type="text"  /></td>
										           <td><input class="form-control" value="" name="C35_continuidad_meta1" tabindex="1" id="C35_continuidad_meta2" type="text"  /></td>
										           <td><input class="form-control" value="" name="C35_continuidad_meta2" tabindex="1" id="C35_continuidad_meta2" type="text"  /></td>
										           <td><input class="form-control" value="" name="C35_continuidad_meta3" tabindex="1" id="C35_continuidad_meta3" type="text"  /></td>
										           <td><input class="form-control" value="" name="C35_continuidad_meta4" tabindex="1" id="C35_continuidad_meta4" type="text"  /></td>
										           <td><input class="form-control" value="" name="C35_continuidad_meta5" tabindex="1" id="C35_continuidad_meta5" type="text"  /></td>
										           <td><input class="form-control" value="" name="C35_continuidad_meta6" tabindex="1" id="C35_continuidad_meta6" type="text"  /></td>
										           <td><input class="form-control" value="" name="C35_continuidad_meta7" tabindex="1" id="C35_continuidad_meta7" type="text"  /></td>
										       </tr>
										   </tbody>
										</table>
									</div>
								</div>
								<input type="button" id="previous4" name="previous4" value="Anterior" />
								<div class="row">
									<div class="col-sm-8">
										<input id="saveForm" type="submit" value="Enviar"
											class="btn btn-primary" onclick="validaPagina(8731,4);" /> 
										<input
											name="button" type="button" id="button" onclick="window.close();"
											value="Cancelar" class="btn btn-primary" />
									</div>
								</div>
							</div>
			<!-- ************************** fin pagina 4 ********************************-->

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
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	
</body>
</html>