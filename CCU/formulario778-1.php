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
 * CARLOS RICAURTE
 * @fecha 2021/12
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
		<form id="contactoOrfeo" class="" autocomplete="off" enctype="multipart/form-data" method="post" action="formulario778-1tx.php" name="quejas">
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
					<span> <strong>Resolución CRA 778 de 2016</strong>
						<br /> 
						<span class="h5">MODELO DE CONDICIONES UNIFORMES DEL CONTRATO DE SERVICIOS PUBLICOS PARA PERSONAS PRESTADORAS DEL SERVICIO PÙBLICO DE ASEO QUE ATIENDAN EN MUNICIPIOS DE MÁS 5.000 SUSCRIPTORES EN EL ÁREA URBANA Y DE EXPANSIÓN URBANA.</span>
						<br /> 
						<span class="h5"> Los campos con (<font color="#FF0000">*</font>) son obligatorios.</span>
					</span>
				</div>
			</div>
			<div class="container-fluid col-sm-offset-1">
				<div class="pub">
					<div>
					<!-- ************************** pagina 1 **********************************-->
						<div class="pagina1 seltipo" id="vpagina1">
							<div class="noanonimo seltipo">
								<div class=" noanonimo seltipo row form-group">
									<div class="col-sm-12  col-md-3">
										<label class=" desc control-label form-control"
											for="tipoPersona">Tipo de Persona *</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<select data-placeholder="Seleccione una opción"
											title="Debe Seleccionaar un tipo de Persona, este campo  es Obligatorio"
											class="chosen-select form-control dropdown seleccion obligatorio"
											id="tipoPersona" name="tipoPersona" >
											<option value=""></option>
											<option value="2">Persona Natural</option>
											<option value="1">Persona Júridica</option>
										</select>
									</div>
								</div>
								<div class=" row col-sm-12  col-md-12 dinamic" id="juridica" style="display: none">
									<div class="row form-group">
										<div class="col-sm-12  col-md-3">
											<label class="desc desc     control-label form-control"
												for="txtnit">Nit*</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control obligatorio digitos" value=""
												maxlength="15"
												title="el campo Nit es obligatorio y solo acepta digitos"
												minlenght="3" name="txtnit" tabindex="4"
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
											<label class="desc control-label form-control"
												for="txtnfijo">Teléfono Contacto*</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control obligatorio" value="" maxlength="15"
												name="txtcontacto"
												title="El campo teléfono de la Empresa es Obligatorio"
												tabindex="4" id="txtcontacto" type="text"  />
										</div>
										<div class="col-sm-12  col-md-3">
											<label class="desc control-label form-control" for="txtemmail">Correo Eléctronico *</label>
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
												title="Debe seleccione un Tipo de Empresa, el campo es obligatorio"
												class="dropdown obligatorio seleccion form-control chosen-select obl"
												id="tipoEmpresa" name="tipoEmpresa">
												<option selected="" value="">Seleccione Tipo de Empresa</option>
											</select>
										</div>
										<div class="col-sm-12  col-md-3">
											<label class="desc  control-label form-control" for="barrioJ">Barrio*</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control obligatorio" value="" maxlength="100"
												name="barrioJ" tabindex="10"
												title="Debe seleccione un barrio, es obligatorio"
												id="barrioJ" type="text"  />
										</div>
									</div>
									<div class="row form-group">
										<div class="col-sm-12  col-md-3">
											<label class="desc  control-label form-control" for="paginaJ">Página Web</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control" value="" maxlength="100"
												name="paginaJ" tabindex="10"
												id="paginaJ" type="text"  />
										</div>
									</div>
								</div>
								<div class=" row col-sm-12  col-md-12 dinamic" id="persona" style="display: none">
									<div class="row form-group">
										<div class="col-sm-12  col-md-3">
											<label class="desc desc     control-label form-control"
												for="tipoDocumento">Tipo de documento *</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<select data-placeholder="tipo de documento"
												title="Debe Seleccionar un Tipo de Documento, el campo es obligatorio "
												name="tipoDocumento"
												class="dropdown obligatorio seleccion chosen-select form-control"
												id="tipoDocumento">
												<option value=""></option>
											</select>
										</div>
										<div class="col-sm-12  col-md-3">
											<label class="desc  control-label form-control"
												for="txtdocumento"><span>Documento de Identidad *</span></label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control obligatorio" value=""
												maxlength="1555555"
												title="Debe Dilígenciar el Documento el campo es obligatorio "
												minlenght="3" name="txtdocumento" tabindex="4"
												onkeypress="return alpha(event,numbers+letters)"
												id="txtdocumento" type="text" required/>

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
												for="txtemail">Correo Eléctronico *</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control email obligatorio" value="" maxlength="50"
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
												for="txtcelular">Móvil Célular *</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control obligatorio" value="" maxlength="14"
												name="celular" tabindex="4"
												title="el campo Móvil Célular es obligatorio "
												
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
												title="el campo Sexo es obligatorio " minlenght="3" name="sexo"
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
											<label class="desc  control-label form-control" for="paginaN">Página Web</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<input class="form-control" value="" maxlength="100"
												name="paginaN" tabindex="10"
												
												id="paginaN" type="text" />
										</div>
									</div>
								</div>
							</div>
							<div class="row form-group noanonimo">
								<div class="row">
									<div class="col-sm-12  col-md-3  align="left">
										<label><input type="hidden" value="1" name="notifica" checked="checked"> </label>
										<label><input type="hidden" value="2" name="notifica"> </label>
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
										<label>Clausula 1. OBJETO. El presente contrato de condiciones uniformes tiene por objeto la prestación de las siguientes actividades que hacen parte del servicio público de aseo:</label>
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-3  col-md-3 " align="left">
										<label><input type="checkbox" id="C01_residuo" value="1" name="C01_residuo"> Recolección y transporte de residuos no aprovechables</label>
										<label><input type="checkbox" id="C01_transfer" value="1" name="C01_transfer"> Transferencia</label>
										<label><input type="checkbox" id="C01_barrido" value="1" name="C01_barrido"> Barrido y Limpieza de vías y áreas públicas</label>
										<label><input type="checkbox" id="C01_cesped" value="1" name="C01_cesped"> Corte de césped</label>
										<label><input type="checkbox" id="C01_poda" value="1" name="C01_poda"> Poda de árboles</label>
										<label><input type="checkbox" id="C01_lavado" value="1" name="C01_lavado"> Lavado de vías y áreas públicas</label>
										<label><input type="checkbox" id="C01_playas" value="1" name="C01_playas"> Limpieza de playas</label>
										<label><input type="checkbox" id="C01_cestar" value="1" name="C01_cestar"> Instalación y mantenimiento de cestas</label>
										<label><input type="checkbox" id="C01_tratam" value="1" name="C01_tratam"> Tratamiento</label>
										<label><input type="checkbox" id="C01_dispfin" value="1" name="C01_dispfin"> Disposición Final</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12" align="left">
										<label>Cláusula 5. VIGENCIA: El contrato de servicios públicos domiciliarios se pacta a término indefinido o fijo, sin embargo, en el evento en que sea fijo este no podrá ser superior a 2 años.</label>
									</div>
									<div class="row">
										<div class="col-sm-12  col-md-3  align=left">
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
								<div class="row">
									<div class="col-md-6" align="left">
										<label>Cláusula 7. ÁREA DE PRESTACIÓN DEL SERVICIO (APS). El área en la cual se prestará el servicio público de aseo y sus actividades complementarias es:</label>
									</div>
									<div class="col-md-6" align="left">
										<input type="text" onkeypress="return alpha(event,)" id="C07_area" name="C07_area"> 
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-9  col-md-9 " align="left">
										<label>Cláusula 16. FACTURACIÓN</label><br>
										<label>La factura será entregada por lo menos con cinco (5) días hábiles de antelación a la fecha de pago señalada en la misma indicando la fecha máxima de entrega de la factura. La periodicidad en la entrega de la factura será:</label><br>
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-4  col-md-4 " align="left">
										<label>•Tipo:</label><br>
										<label><input type="radio" id="C16_mensual" value="1" name="C16_mensual" checked="checked"> Mensual:</label>
										<label><input type="radio" id="C16_bimensual" value="2" name="C16_mensual"> Bimestral</label>
										<div class="row">
											<div class="col-md-6" align="left">
												<label>Fecha máxima de entrega:</label>
											</div>
											<div align="left"> 
												<select name="C16_fecha_maxima_entrega" id="C16_fecha_maxima_entrega">
												<?php
													for($i=1;$i<31;$i++){
														echo "<option value='".$i."'>".$i."</option>";
													}
												?>
												</select>
											</div>
										</div><br><br>
									</div><br>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-15  col-md-2">
										<label class=" desc desc     control-label form-control"
											id="lbl_comentario" for="clausulas_generales">CLÁUSULAS ADICIONALES GENERALES
										</label>
									</div>
									<div class="col-sm-12 col-md-8">
										<textarea id="clausulas_generales" name="clausulas_generales"
											class="form-controlarea textarea large" rows="5" cols="50"
											tabindex="16" 
											placeholder="Escriba ac&aacute; ..." minlength="6"
											title="las clausulas adicionales debe ser de máximo 2000 carácteres y minímo 3"></textarea>
									</div>&nbsp;
								</div>
								<br><br>
								<table border="1">
								   <div align="left">Cláusula 27.  FRECUENCIAS Y HORARIOS PARA LA PRESTACIÓN DEL SERVICIO PÚBLICO DE ASEO PARA RESIDUOS NO APROVECHABLES EN EL APS DECLARADA. La persona prestadora deberá diligenciar las siguientes tablas con la información de frecuencias y horarios en los que prestarán las diferentes actividades del servicio público de aseo, para cada una de las macrorrutas de recolección definidas en el APS declarada.</div>

								   <caption>El prestador podrá incluir datos en el siguiente cuadro con letras y números.</caption>
								   <div>Recolección y Transporte de residuos no aprovechables</div>
								   <thead>
								       <tr>
								           <th>MACRORRUTA</th>
								           <th>FRECUENCIA DE RECOLECCIÓN DE RESIDUOS NO APROVECHABLES</th>
								           <th>HORARIO DE RECOLECCIÓN DE RESIDUOS NO APROVECHABLES</th>
								       </tr>
								   </thead>

								   <tbody> <!-- Cuerpo de la tabla -->
								       <tr>
								           <td><input class="form-control" value="" name="C27_rytdrna_1_1" tabindex="1" id="C27_rytdrna_1_1" type="text" onkeypress="return alpha(event,)" /></td>
								           <td><input class="form-control" value="" name="C27_rytdrna_1_2" tabindex="1" id="C27_rytdrna_1_2" type="text" onkeypress="return alpha(event,)" /></td>
								           <td><input class="form-control" value="" name="C27_rytdrna_1_3" tabindex="1" id="C27_rytdrna_1_3" type="text" onkeypress="return alpha(event,)" /></td>
								      </tr>
								      <tr>
								           <td><input class="form-control" value="" name="C27_rytdrna_2_1" tabindex="1" id="C27_rytdrna_2_1" type="text" onkeypress="return alpha(event,)" /></td>
								           <td><input class="form-control" value="" name="C27_rytdrna_2_2" tabindex="1" id="C27_rytdrna_2_2" type="text" onkeypress="return alpha(event,)" /></td>
								           <td><input class="form-control" value="" name="C27_rytdrna_2_3" tabindex="1" id="C27_rytdrna_2_3" type="text" onkeypress="return alpha(event,)" /></td>
								      </tr>
								   </tbody>
								</table>
								<br><br>
								<table border="1">
								   <div>Recolección y Transporte de residuos no aprovechables</div>

								   <thead>
								       <tr>
								           <th>MACRORRUTA</th>
								           <th>FRECUENCIA DE BARRIDO Y LIMPIEZA</th>
								           <th>HORARIO DE BARRIDO Y LIMPIEZA</th>
								       </tr>
								   </thead>

								   <tbody> <!-- Cuerpo de la tabla -->
								       <tr>
								           <td><input class="form-control" value="" name="C27_byludvyap_1_1" tabindex="1" id="C27_byludvyap_1_1" type="text" onkeypress="return alpha(event,)" /></td>
								           <td><input class="form-control" value="" name="C27_byludvyap_1_2" tabindex="1" id="C27_byludvyap_1_2" type="text" onkeypress="return alpha(event,)" /></td>
								           <td><input class="form-control" value="" name="C27_byludvyap_1_3" tabindex="1" id="C27_byludvyap_1_3" type="text" onkeypress="return alpha(event,)" /></td>
								      </tr>
								      <tr>
								           <td><input class="form-control" value="" name="C27_byludvyap_2_1" tabindex="1" id="C27_byludvyap_2_1" type="text" onkeypress="return alpha(event,)" /></td>
								           <td><input class="form-control" value="" name="C27_byludvyap_2_2" tabindex="1" id="C27_byludvyap_2_2" type="text" onkeypress="return alpha(event,)" /></td>
								           <td><input class="form-control" value="" name="C27_byludvyap_2_3" tabindex="1" id="C27_byludvyap_2_3" type="text" onkeypress="return alpha(event,)" /></td>
								      </tr>
								   </tbody>
								</table>
								<br><br>
								<table border="1">
								   <div>Limpieza de playas </div>
								   <thead>
								       <tr>
								           <th>MACRORRUTA</th>
								           <th>FRECUENCIA DE LIMPIEZA DE PLAYAS</th>
								           <th>HORARIO DE LIMPIEZA DE PLAYAS</th>
								       </tr>
								   </thead>

								   <tbody> <!-- Cuerpo de la tabla -->
								       <tr>
								           <td><input class="form-control" value="" name="C27_lp_1_1" tabindex="1" id="C27_lp_1_1" type="text" onkeypress="return alpha(event,)" /></td>
								           <td><input class="form-control" value="" name="C27_lp_1_2" tabindex="1" id="C27_lp_1_2" type="text" onkeypress="return alpha(event,)" /></td>
								           <td><input class="form-control" value="" name="C27_lp_1_3" tabindex="1" id="C27_lp_1_3" type="text" onkeypress="return alpha(event,)" /></td>
								      </tr>
								      <tr>
								           <td><input class="form-control" value="" name="C27_lp_2_1" tabindex="1" id="C27_lp_2_1" type="text" onkeypress="return alpha(event,)" /></td>
								           <td><input class="form-control" value="" name="C27_lp_2_2" tabindex="1" id="C27_lp_2_2" type="text" onkeypress="return alpha(event,)" /></td>
								           <td><input class="form-control" value="" name="C27_lp_2_3" tabindex="1" id="C27_lp_2_3" type="text" onkeypress="return alpha(event,)" /></td>
								      </tr>
								   </tbody>
								</table>
								<br><br>
								<table border="1">
								   <div>Lavado de áreas públicas</div>
								   <thead>
								       <tr>
								           <th>MACRORRUTA</th>
								           <th>FRECUENCIA DE LAVADO DE ÁREAS PÚBLICAS</th>
								           <th>HORARIO DE LAVADO DE ÁREAS PÚBLICAS</th>
								       </tr>
								   </thead>

								   <tbody> <!-- Cuerpo de la tabla -->
								       <tr>
								           <td><input class="form-control" value="" name="C27_lap_1_1" tabindex="1" id="C27_lap_1_1" type="text" onkeypress="return alpha(event,)" /></td>
								           <td><input class="form-control" value="" name="C27_lap_1_2" tabindex="1" id="C27_lap_1_2" type="text" onkeypress="return alpha(event,)" /></td>
								           <td><input class="form-control" value="" name="C27_lap_1_3" tabindex="1" id="C27_lap_1_3" type="text" onkeypress="return alpha(event,)" /></td>
								      </tr>
								      <tr>
								           <td><input class="form-control" value="" name="C27_lap_2_1" tabindex="1" id="C27_lap_2_1" type="text" onkeypress="return alpha(event,)" /></td>
								           <td><input class="form-control" value="" name="C27_lap_2_2" tabindex="1" id="C27_lap_2_2" type="text" onkeypress="return alpha(event,)" /></td>
								           <td><input class="form-control" value="" name="C27_lap_2_3" tabindex="1" id="C27_lap_2_3" type="text" onkeypress="return alpha(event,)" /></td>
								      </tr>
								   </tbody>
								</table>
								<br><br>
								
								<div class="row">
									<div class="col-md-1" align="left">
										<label>Mapa:</label>
									</div>
									<div class="row">
										<div id="li_upload">
											<div id="filelimit-fine-uploader"></div>
											<div id="availabeForUpload"></div>
											&nbsp;
										</div>
									</div>
								</div>
								<br><br>
								<input type="button" id="previous2" name="previous2" value="Anterior" />
								<br>
								<div class="row">
									<div class="col-sm-8">
										<input id="saveForm" type="submit" value="Enviar"
											class="btn btn-primary" onclick="validaPagina('7781',2);" /> 
										<input name="button" type="button" id="button" onclick="window.close();"
											value="Cancelar" class="btn btn-primary" />
									</div>
								</div>
								
							
						</div>
						
			<!-- ************************** fin pagina 2 ********************************-->

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
	
</body>
</html>