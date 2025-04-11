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
 * Modulo de Formularios Web para CCU CRA.
 * Modificado CARLOS RICAURTE
 * @fecha 2021
 */
$ruta_raiz = "..";
$ADODB_COUNTRECS = false;
require_once ("$ruta_raiz/include/db/ConnectionHandler.php");
require_once realpath(dirname(__FILE__) . "/../") . "/atencion/AtencionTipos.php";
$_SESSION["idFormulario"] = sha1(microtime(true) . mt_rand(10000, 90000));
$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

// TamaNo mAximo del todos los archivos en bytes 10MB = 10(MB)*1024(KB)*1024(B) = 10485760 bytes
$max_file_size = 20971520;

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

	<script src='https://www.google.com/recaptcha/api.js?render=6LfZaAEmAAAAAOU6ibfic0qol7VrSMymGk9TNHeS'> 
    </script>
	  <script>
	      grecaptcha.ready(function() {
	      grecaptcha.execute('6LfZaAEmAAAAAOU6ibfic0qol7VrSMymGk9TNHeS', {action: 'contactoOrfeo'})
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
		<form id="contactoOrfeo" class="" autocomplete="off" enctype="multipart/form-data" method="post" action="formulario768tx.php" name="quejas">
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
					<span> <strong>Resolución CRA 768 de 2016</strong>
						<br /> 
						<span class="h5">MODELO DE CONTRATO DE SERVICIOS PÚBLICOS PARA PERSONAS PRESTADORAS DE LOS SERVICIOS PÚBLICOS DOMICILIARIOS DE ACUEDUCTO Y/O ALCANTARILLADO, QUE CUENTEN CON MAS DE 5.000 SUSCRIPTORES Y/O USUARIOS EN EL ÁREA RURAL O URBANA.</span>
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
									<div class="col-sm-12  col-md-3">
										<label><input type="hidden" value="1" name="notifica" checked="checked"></label>
										<label><input type="hidden" value="2" name="notifica"></label>
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

							<input type="button" id="next1" name="next1" value="Siguiente"/>
						</div>
						
			<!-- ************************** fin pagina 1 **********************************-->

			<!-- ************************** pagina 2 **********************************-->
						<div id="idpagina2" class="pagina2"  style="display: none">
								<div class="seltipo row form-group">
									<div class="col-sm-9  col-md-9 " align="left">
										<label>Clausula 1. OBJETO. El presente contrato de servicios públicos domiciliarios tiene por objeto la prestación de los servicios públicos domiciliarios de: </label>
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-2  col-md-2 form-control" align="left" 
										title="Debe Seleccionar el Tipo de Servicio">
										<label><input type="checkbox" id="C01_acueducto" value="1" name="C01_acueducto"> Acueducto</label>
										<label><input type="checkbox" id="C01_alcatarillado" value="1" name="C01_alcatarillado"> Alcantarillado</label>
									</div>
								</div>

								<div class="seltipo row form-group">
									<div class="col-sm-9  col-md-9 " align="left">
										<label>El(Los) servicio(s) se prestará(n) en un inmueble: </label>
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-2 form-control" align="left" 
										 title="Debe Seleccionar el Tipo de Servicio">
										<label><input type="checkbox" id="C01_urbano" value="1" name="C01_urbano"> Urbano</label>
										<label><input type="checkbox" id="C01_rural" value="1" name="C01_rural"> Rural</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3" align="left">
										<label>Nombre vereda:</label>
									</div>
									<div class="col-md-3" align="left">
										<input type="text" id="C01_vereda" name="C01_vereda" > 
									</div>
								</div>
								<div class="row">
									<div class="col-md-3" align="left">
										<label>Nombre Corregimiento:</label>
									</div>
									<div class="col-md-3" align="left">
										<input type="text" id="C01_corregimiento" name="C01_corregimiento" > 
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
									<div class="col-md-3" align="left">
										<label>Cargo de funcionario encargado de resolver:</label>
									</div>
									<div class="col-md-3" align="left">
										<input type="text" id="C21_cargo" name="C21_cargo"> 
									</div>
								</div>
								<div class="row">
									<div class="col-md-4" align="left">
										<label>Cláusula 6. ÁREA DE PRESTACIÓN DEL SERVICIO. El área en la cual se prestarán los servicios públicos domiciliarios es:</label>
									</div>
									<div class="col-md-3" align="left">
										<input type="text" id="C06_area" name="C06_area"> 
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<div id="li_upload">
											<div id="filelimit-fine-uploader"></div>
											<div id="availabeForUpload"></div>
											&nbsp;
										</div>
									</div>
								</div>
								<br>
								<input type="button" id="previous2" name="previous2" value="Anterior" />
								<input type="button" value="Siguiente" onclick="validaPagina('768',2);"/>
							
						</div>
						
			<!-- ************************** fin pagina 2 ********************************-->

			<!-- ************************** inicio pagina 3 ********************************-->
							<div class="pagina3"  style="display: none">
								<div class="seltipo row form-group">
									<div class="col-sm-9  col-md-9 " align="left">
										<label>Cláusula 13. CONDICIONES TÉCNICAS ACUEDUCTO</label><br>
										<label>Los medidores tendrán las siguientes especificaciones técnicas:</label><br>
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-4  col-md-4 " align="left">
										<label>•Tipo:</label>
										<label><input type="checkbox" id="C13_velocidad" value="1" name="C13_velocidad"> Velocidad</label>
										<label><input type="checkbox" id="C13_volumetrico" value="1" name="C13_volumetrico"> Volumetrico</label>
										<label><input type="checkbox" id="C13_electromagnetico" value="1" name="C13_electromagnetico"> Electromagnético</label>
										<label><input type="checkbox" id="C13_concentrico" value="1" name="C13_concentrico"> Concéntrico</label>
										<label><input type="text"  id="C13_otro1" name="C13_otro1"> Otro Cual?</label><br><br>
										<label>•Especificaciones adicionales del tipo de medidor</label><br>
										<label><input type="checkbox" id="C13_telemetria" value="1" name="C13_telemetria"> Telemetría</label>
										<label><input type="checkbox" id="C13_prepago" value="1" name="C13_prepago"> Prepago</label>
										<label><input type="text"  id="C13_otro2" name="C13_otro2"> Otro Cual?</label><br><br>
										<label>•Diámetro (pulgadas)</label><br>
										<input type="text"  id="C13_diametro" name="C13_diametro"> <br><br>
										<label>•Caudal permanente (Q3) m3/hora:</label><br>
										<input type="text"  id="C13_caudal" name="C13_caudal"> <br><br>
										<label>•Rango de medición (R):</label><br>
										<input type="text"  id="C13_medicion" name="C13_medicion"> 
									</div>
								</div>
								<input type="button" id="previous3" name="previous3" value="Anterior" />
								<input type="button" value="Siguiente"  onclick="validaPagina(768,3);"/>
							</div>
			<!-- ************************** fin pagina 3 ********************************-->

			<!-- ************************** inicio pagina 4 ********************************-->
							<div class="pagina4"  style="display: none">
								<div class="seltipo row form-group">
									<div class="col-sm-9  col-md-9 " align="left">
										<label>Cláusula 14. MEDICIÓN DE ALCANTARILLADO</label><br>
										<label>La medición se realizará mediante medidores o estructuras hidráulicas de medición, de conformidad con las siguientes tecnologías:</label><br>
									</div>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-4  col-md-4 " align="left">
										<label>•Tipo:</label><br>
										<label><input type="checkbox" id="C14_vertederos" value="1" name="C14_vertederos"> Vertederos de placa fina</label>
										<label><input type="checkbox" id="C14_parshall" value="1" name="C14_parshall"> Canaleta Parshall</label>
										<label><input type="checkbox" id="C14_electromagnetico" value="1" name="C14_electromagnetico"> Electromagnético para aguas residuales</label>
										<label><input type="checkbox" id="C14_electronicos_en_contacto" value="1" name="C14_electronicos_en_contacto"> Sistemas electrónicos en contacto con el agua residual de medida de altura de presión y velocidad </label>
										<label><input type="checkbox" id="C14_electronicos_sin_contacto" value="1" 
											name="C14_electronicos_sin_contacto"> Sistemas electrónicos sin contacto con el agua residual de medida de nivel y velocidad </label>
										<label><input type="text"  id="C14_otro1" name="C14_otro1"> Otro Cual?</label><br><br>
										<label>•Especificaciones adicionales del tipo de medidor:</label><br>
										<label><input type="checkbox" id="C14_telemetria" value="1" name="C14_telemetria"> Telemetría</label>
										<label><input type="text"  id="C14_otro2" name="C14_otro2"> Otro Cual?</label><br><br>
									</div>
								</div>
								<input type="button" value="Anterior" onclick="validaPagina(7681,4);"/>
								<input type="button" id="next4" name="next4" value="Siguiente" />
							</div>
			<!-- ************************** fin pagina 4 ********************************-->

			<!-- ************************** inicio pagina 5 ********************************-->
							<div class="pagina5"  style="display: none">
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
										</div>
										<br><br>
									</div><br>
								</div>
								<div class="seltipo row form-group">
									<div class="col-sm-15  col-md-2">
										<label class=" desc desc     control-label form-control"
											id="lbl_comentario" for="clausulas_generales">Cláusula 24. CLÁUSULAS ADICIONALES GENERALES:  Son aquellas que define la persona prestadora aplicables a todos los suscriptores y/o usuarios de forma uniforme
										</label>
									</div>
									<div class="col-sm-12 col-md-8">
										<textarea id="clausulas_generales" name="clausulas_generales"
											class="form-controlarea textarea large" rows="5" cols="50"
											tabindex="16" 
											placeholder="Escriba ac&aacute; ..." required minlength="6"
											title="las clausulas adicionales debe ser de máximo 2000 carácteres y minímo 3"></textarea>
									</div>&nbsp;
								</div>
								<br>
								<div class="row">
									<div class="col-sm-12  col-md-8">
										<input type="button" value="Anterior" onclick="validaPagina(7681,5);"/>
										<input type="button" id="next5" name="next5" value="Siguiente" />
									</div>
								</div>
							</div>
			<!-- ************************** fin pagina 5 ********************************-->
				<!-- ************************** inicio pagina 6 ********************************-->
							<div class="pagina6"  style="display: none">
								<table border="1">
								   <caption title="Las metas proyectadas referente a los estándares de servicio deben corresponder a los incluidas en el estudio de costos en aplicación de la metodología tarifaria contenida en la Resolución CRA 688 de 2014. Según lo dispuesto en el parágrafo 8 de la cláusula 9." >Cláusula 25. ESTÁNDARES DE SERVICIO. La persona prestadora se compromete a prestar el(los) servicio(s) público(s) domiciliario(s) de acueducto y/o alcantarillado con los siguientes estándares de servicio:</caption>

								   <thead> <!-- Pasajeros del vuelo 377 -->
								       <tr>
								           <th>Estandar de servicio</th>
								           <th>UNIDAD</th>
								           <th>META DEL ESTANDAR</th>
								           <th>LINEA BASE AÑO CERO</th>
								           <th>META AÑO 1</th>
								           <th>META AÑO 2</th>
								           <th>META AÑO 3</th>
								           <th>META AÑO 4</th>
								           <th>META AÑO 5</th>
								           <th>META AÑO 6</th>
								           <th>META AÑO 7</th>
								           <th>META AÑO 8</th>
								           <th>META AÑO 9</th>
								           <th>META AÑO 10</th>
								       </tr>
								   </thead>

								   <tbody> <!-- Cuerpo de la tabla -->
								       <tr>
								           <td>Cobertura de acueducto</td>
								           <td>Número de suscriptores incorporados con conexión al servicio de acueducto.</td>
								           <td>100%</td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_AC_LINEA_BASE" tabindex="1" id="C25_COBERTURA_AC_LINEA_BASE" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_AC_META1" tabindex="1" id="C25_COBERTURA_AC_META1" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_AC_META2" tabindex="1" id="C25_COBERTURA_AC_META2" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_AC_META3" tabindex="1" id="C25_COBERTURA_AC_META3" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_AC_META4" tabindex="1" id="C25_COBERTURA_AC_META4" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_AC_META5" tabindex="1" id="C25_COBERTURA_AC_META5" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_AC_META6" tabindex="1" id="C25_COBERTURA_AC_META6" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_AC_META7" tabindex="1" id="C25_COBERTURA_AC_META7" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_AC_META8" tabindex="1" id="C25_COBERTURA_AC_META8" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_AC_META9" tabindex="1" id="C25_COBERTURA_AC_META9" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_AC_META10" tabindex="1" id="C25_COBERTURA_AC_META10" type="text"  /></td>
								      </tr>
								      <tr>
								           <td>Calidad de acueducto</td>
								           <td>Puntaje IRCA (%)</td>
								           <td>IRCA <= 5%</td>
								           <td><input class="form-control" value="" name="C25_CALIDAD_AC_LINEA_BASE" tabindex="1" id="C25_CALIDAD_AC_LINEA_BASE" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALIDAD_AC_META1" tabindex="1" id="C25_CALIDAD_AC_META1" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALIDAD_AC_META2" tabindex="1" id="C25_CALIDAD_AC_META2" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALIDAD_AC_META3" tabindex="1" id="C25_CALIDAD_AC_META3" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALIDAD_AC_META4" tabindex="1" id="C25_CALIDAD_AC_META4" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALIDAD_AC_META5" tabindex="1" id="C25_CALIDAD_AC_META5" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALIDAD_AC_META6" tabindex="1" id="C25_CALIDAD_AC_META6" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALIDAD_AC_META7" tabindex="1" id="C25_CALIDAD_AC_META7" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALIDAD_AC_META8" tabindex="1" id="C25_CALIDAD_AC_META8" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALIDAD_AC_META9" tabindex="1" id="C25_CALIDAD_AC_META9" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALIDAD_AC_META10" tabindex="1" id="C25_CALIDAD_AC_META10" type="text"  /></td>
								       </tr>
								       <tr>
								           <td>Continuidad de acueducto</td>
								           <td>Número de días por año de prestación del servicio</td>
								           <td>>=98,36%</td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ACU_LINEA_BASE" tabindex="1" id="C25_CONTINUIDAD_ACU_LINEA_BASE" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ACU_META1" tabindex="1" id="C25_CONTINUIDAD_ACU_META1" type="text"  /></td>
										   <td><input class="form-control" value="" name="C25_CONTINUIDAD_ACU_META2" tabindex="1" id="C25_CONTINUIDAD_ACU_META2" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ACU_META3" tabindex="1" id="C25_CONTINUIDAD_ACU_META3" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ACU_META4" tabindex="1" id="C25_CONTINUIDAD_ACU_META4" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ACU_META5" tabindex="1" id="C25_CONTINUIDAD_ACU_META5" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ACU_META6" tabindex="1" id="C25_CONTINUIDAD_ACU_META6" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ACU_META7" tabindex="1" id="C25_CONTINUIDAD_ACU_META7" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ACU_META8" tabindex="1" id="C25_CONTINUIDAD_ACU_META8" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ACU_META9" tabindex="1" id="C25_CONTINUIDAD_ACU_META9" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ACU_META10" tabindex="1" id="C25_CONTINUIDAD_ACU_META10" type="text"  /></td>
								       </tr>
								       <tr>
								           <td>Cobertura de alcantarillado</td>
								           <td>Número de suscriptores incorporados con conexión al servicio de alcantarillado</td>
								           <td>100%</td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_ALCAN_LINEA_BASE" tabindex="1" id="C25_COBERTURA_ALCAN_LINEA_BASE" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_ALCAN_META1" tabindex="1" id="C25_COBERTURA_ALCAN_META1" type="text"  /></td>
										   <td><input class="form-control" value="" name="C25_COBERTURA_ALCAN_META2" tabindex="1" id="C25_COBERTURA_ALCAN_META2" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_ALCAN_META3" tabindex="1" id="C25_COBERTURA_ALCAN_META3" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_ALCAN_META4" tabindex="1" id="C25_COBERTURA_ALCAN_META4" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_ALCAN_META5" tabindex="1" id="C25_COBERTURA_ALCAN_META5" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_ALCAN_META6" tabindex="1" id="C25_COBERTURA_ALCAN_META6" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_ALCAN_META7" tabindex="1" id="C25_COBERTURA_ALCAN_META7" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_ALCAN_META8" tabindex="1" id="C25_COBERTURA_ALCAN_META8" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_ALCAN_META9" tabindex="1" id="C25_COBERTURA_ALCAN_META9" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_COBERTURA_ALCAN_META10" tabindex="1" id="C25_COBERTURA_ALCAN_META10" type="text"  /></td>
								       </tr>
								       <tr>
								           <td>Calidad de alcantarillado</td>
								           <td>% de cumplimiento de PSMV</td>
								           <td>100% del cumplimiento de las obras a cargo del prestador estipuladas en el plan de saneamiento y manejo de vertimientos - PSMV</td>
								           <td><input class="form-control" value="" name="C25_CALDAD_ALCAN_LINEA_BASE" tabindex="1" id="C25_CALDAD_ALCAN_LINEA_BASE" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALDAD_ALCAN_META1" tabindex="1" id="C25_CALDAD_ALCAN_META1" type="text"  /></td>
										   <td><input class="form-control" value="" name="C25_CALDAD_ALCAN_META2" tabindex="1" id="C25_CALDAD_ALCAN_META2" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALDAD_ALCAN_META3" tabindex="1" id="C25_CALDAD_ALCAN_META3" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALDAD_ALCAN_META4" tabindex="1" id="C25_CALDAD_ALCAN_META4" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALDAD_ALCAN_META5" tabindex="1" id="C25_CALDAD_ALCAN_META5" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALDAD_ALCAN_META6" tabindex="1" id="C25_CALDAD_ALCAN_META6" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALDAD_ALCAN_META7" tabindex="1" id="C25_CALDAD_ALCAN_META7" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALDAD_ALCAN_META8" tabindex="1" id="C25_CALDAD_ALCAN_META8" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALDAD_ALCAN_META9" tabindex="1" id="C25_CALDAD_ALCAN_META9" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CALDAD_ALCAN_META10" tabindex="1" id="C25_CALDAD_ALCAN_META10" type="text"  /></td>
								       </tr>
								       <tr>
								           <td>Continuidad de alcantarillado</td>
								           <td>Número de días por año de prestación del servicio</td>
								           <td>>=98,36%</td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ALCAN_LINEA_BASE" tabindex="1" id="C25_CONTINUIDAD_ALCAN_LINEA_BASE" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ALCAN_META1" tabindex="1" id="C25_CONTINUIDAD_ALCAN_META1" type="text"  /></td>
										   <td><input class="form-control" value="" name="C25_CONTINUIDAD_ALCAN_META2" tabindex="1" id="C25_CONTINUIDAD_ALCAN_META2" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ALCAN_META3" tabindex="1" id="C25_CONTINUIDAD_ALCAN_META3" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ALCAN_META4" tabindex="1" id="C25_CONTINUIDAD_ALCAN_META4" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ALCAN_META5" tabindex="1" id="C25_CONTINUIDAD_ALCAN_META5" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ALCAN_META6" tabindex="1" id="C25_CONTINUIDAD_ALCAN_META6" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ALCAN_META7" tabindex="1" id="C25_CONTINUIDAD_ALCAN_META7" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ALCAN_META8" tabindex="1" id="C25_CONTINUIDAD_ALCAN_META8" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ALCAN_META9" tabindex="1" id="C25_CONTINUIDAD_ALCAN_META9" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_CONTINUIDAD_ALCAN_META10" tabindex="1" id="C25_CONTINUIDAD_ALCAN_META10" type="text"  /></td>
								       </tr>
								       <tr>
								           <td>IRQ - Indocador de reclamos comerciales</td>
								           <td>(reclamos/1.000 suscriptores/por periodo de tiempo analizado)</td>
								           <td><=4 reclamaciones comerciales por factura resueltas a favor del suscriptor en segunda instancia por cada 1.000 suscriptores por año o <=2 reclamaciones comerciales por facturación resueltas a favor del suscriptor en segunda instancia por cada 1.000 suscriptores por semestre.</td>
								           <td><input class="form-control" value="" name="C25_IQR_LINEA_BASE" tabindex="1" id="C25_IQR_LINEA_BASE" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_IQR_META1" tabindex="1" id="C25_IQR_META1" type="text"  /></td>
										   <td><input class="form-control" value="" name="C25_IQR_META2" tabindex="1" id="C25_IQR_META2" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_IQR_META3" tabindex="1" id="C25_IQR_META3" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_IQR_META4" tabindex="1" id="C25_IQR_META4" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_IQR_META5" tabindex="1" id="C25_IQR_META5" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_IQR_META6" tabindex="1" id="C25_IQR_META6" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_IQR_META7" tabindex="1" id="C25_IQR_META7" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_IQR_META8" tabindex="1" id="C25_IQR_META8" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_IQR_META9" tabindex="1" id="C25_IQR_META9" type="text"  /></td>
								           <td><input class="form-control" value="" name="C25_IQR_META10" tabindex="1" id="C25_IQR_META10" type="text"  /></td>
								       </tr>
								   </tbody>
								</table>
								<div class="row">
									<div class="col-sm-12  col-md-8">
										<input type="button" id="previous6" name="previous6" value="Anterior" />
										<input type="button" id="next6" name="next6" value="Siguiente" />
									</div>
								</div>
							</div>
			<!-- ************************** fin pagina 6 ********************************-->

			<!-- ************************** inicio pagina 7 ********************************-->
							<div class="pagina7"  style="display: none">
								<table border="1">
								   <caption title="Las metas proyectadas referente a los estándares de eficiencia deben corresponder a los incluidas en el estudio de costos en aplicación de la metodología tarifaria contenida en la Resolución CRA 688 de 2014. Según lo dispuesto en el parágrafo 8 de la cláusula 9.">Cláusula 26. ESTÁNDARES DE EFICIENCIA. La persona prestadora se compromete a prestar el(los) servicio(s) público(s) domiciliario(s) de acueducto y/o alcantarillado con los siguientes estándares de eficiencia:</caption>

								   <thead> 
								       <tr>
								           <th>Acueducto</th>
								           <th>Alcantarillado</th>
								           <th>Estandar de Eficiencia</th>
								           <th>Meta y gradualidad (a partir de la entrada en vigencia de la presente resolución)</th>
								           <th>META AÑO 1</th>
								           <th>META AÑO 2</th>
								           <th>META AÑO 3</th>
								           <th>META AÑO 4</th>
								           <th>META AÑO 5</th>
								           <th>META AÑO 6</th>
								           <th>META AÑO 7</th>
								           <th>META AÑO 8</th>
								           <th>META AÑO 9</th>
								           <th>META AÑO 10</th>
								       </tr>
								   </thead>

								   <tbody> <!-- Cuerpo de la tabla -->
								       <tr>
								           <td rowspan="2">Nuevos suscriptores residenciales de acueducto</td>
								           <td rowspan="2">Nuevos suscriptores residenciales de alcantarillado</td>
								           <td>Dimensión de Cobertura POIR - personas prestadoras primer segmento</td>
								           <td>Para el año 5 debe lograrse el 100% de la diferencia, y gradualidad según la ejecución programada para el POIR</td>
								           <td><input class="form-control" value="" name="MAT_1_1" tabindex="1" id="MAT_1_1" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_1_2" tabindex="1" id="MAT_1_2" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_1_3" tabindex="1" id="MAT_1_3" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_1_4" tabindex="1" id="MAT_1_4" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_1_5" tabindex="1" id="MAT_1_5" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_1_6" tabindex="1" id="MAT_1_6" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_1_7" tabindex="1" id="MAT_1_7" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_1_8" tabindex="1" id="MAT_1_8" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_1_9" tabindex="1" id="MAT_1_9" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_1_10" tabindex="1" id="MAT_1_10" type="text"  /></td>
								      </tr>
								      <tr>
								           <td>Disminución de la diferencia entre suscriptores de Acueducto y Alcantarillado</td>
								           <td>Reducir el 100% de la diferencia, y gradualidad según la ejecución programada para el POIR y el plan de incorporación de suscriptores.</td>
								           <td><input class="form-control" value="" name="MAT_2_1" tabindex="1" id="MAT_2_1" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_2_2" tabindex="1" id="MAT_2_2" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_2_3" tabindex="1" id="MAT_2_3" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_2_4" tabindex="1" id="MAT_2_4" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_2_5" tabindex="1" id="MAT_2_5" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_2_6" tabindex="1" id="MAT_2_6" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_2_7" tabindex="1" id="MAT_2_7" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_2_8" tabindex="1" id="MAT_2_8" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_2_9" tabindex="1" id="MAT_2_9" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_2_10" tabindex="1" id="MAT_2_10" type="text"  /></td>
								       </tr>
								       <tr>
								           <td colspan="2">DACAL - Diferencia entre suscriptores de Acueducto y Alcantarillado (suscriptores)</td>
								           <td>Disminución de la diferencia entre suscriptores de Acueducto y Alcantarillado</td>
								           <td>Reducir el 100% de la diferencia, y gradualidad según la ejecución programada para el POIR y el plan de incorporación de suscriptores.</td>
								           <td><input class="form-control" value="" name="MAT_3_1" tabindex="1" id="MAT_3_1" type="text"  /></td>
										   <td><input class="form-control" value="" name="MAT_3_2" tabindex="1" id="MAT_3_2" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_3_3" tabindex="1" id="MAT_3_3" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_3_4" tabindex="1" id="MAT_3_4" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_3_5" tabindex="1" id="MAT_3_5" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_3_6" tabindex="1" id="MAT_3_6" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_3_7" tabindex="1" id="MAT_3_7" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_3_8" tabindex="1" id="MAT_3_8" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_3_9" tabindex="1" id="MAT_3_9" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_3_10" tabindex="1" id="MAT_3_10" type="text"  /></td>
								       </tr>
								       <tr>
								           <td>IPUF* - Indice de perdida de por suscriptor facturado estándar(mᵌ/suscriptor/mes)</td>
								           <td></td>
								           <td><=6mᵌ/suscriptor/mes</td>
								           <td>Para el año 5 debe lograrse el 50% de la diferencia y para el año 10 debe lograrse el 75%. En caso de utilizar NEP, debe lograrse el 100% para el año 5 y debe mantenerlo. La gradualidad es de acuerdo con las metas de la persona prestadora.</td>
								           <td><input class="form-control" value="" name="MAT_4_1" tabindex="1" id="MAT_4_1" type="text"  /></td>
										   <td><input class="form-control" value="" name="MAT_4_2" tabindex="1" id="MAT_4_2" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_4_3" tabindex="1" id="MAT_4_3" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_4_4" tabindex="1" id="MAT_4_4" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_4_5" tabindex="1" id="MAT_4_5" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_4_6" tabindex="1" id="MAT_4_6" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_4_7" tabindex="1" id="MAT_4_7" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_4_8" tabindex="1" id="MAT_4_8" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_4_9" tabindex="1" id="MAT_4_9" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_4_10" tabindex="1" id="MAT_4_10" type="text"  /></td>
								       </tr>
								       <tr>
								           <td>CAU* - Costos Administrativos eficientes estándarpor suscriptor mensual de acueducto ($/suscriptor/mes)</td>
								           <td>CAU* - Costos Administrativos eficientes estándarpor suscriptor mensual de alcantarillado ($/suscriptor/mes)</td>
								           <td>Alcanzar el valor de referencia establecido en el ARTICULO 26 de la presente resolución.</td>
								           <td>Para el año 5 debe lograrse el 100% de la diferencia, con un avance de 1/5 cada año.</td>
								           <td><input class="form-control" value="" name="MAT_5_1" tabindex="1" id="MAT_5_1" type="text"  /></td>
										   <td><input class="form-control" value="" name="MAT_5_2" tabindex="1" id="MAT_5_2" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_5_3" tabindex="1" id="MAT_5_3" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_5_4" tabindex="1" id="MAT_5_4" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_5_5" tabindex="1" id="MAT_5_5" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_5_6" tabindex="1" id="MAT_5_6" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_5_7" tabindex="1" id="MAT_5_7" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_5_8" tabindex="1" id="MAT_5_8" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_5_9" tabindex="1" id="MAT_5_9" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_5_10" tabindex="1" id="MAT_5_10" type="text"  /></td>
								       </tr>
								       <tr>
								           <td>COU* - Costos Operativos eficientes estándarpor suscriptor mensual de acueducto ($/suscriptor/mes)</td>
								           <td>COU* - Costos Operativos eficientes estándarpor suscriptor mensual de alcantarillado ($/suscriptor/mes)</td>
								           <td>Alcanzar el valor de referencia establecido en el ARTICULO 33 de la presente resolución.</td>
								           <td>Para el año 5 debe lograrse el 100% de la diferencia, con un avance de 1/5 cada año.</td>
								           <td><input class="form-control" value="" name="MAT_6_1" tabindex="1" id="MAT_6_1" type="text"  /></td>
										   <td><input class="form-control" value="" name="MAT_6_2" tabindex="1" id="MAT_6_2" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_6_3" tabindex="1" id="MAT_6_3" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_6_4" tabindex="1" id="MAT_6_4" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_6_5" tabindex="1" id="MAT_6_5" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_6_6" tabindex="1" id="MAT_6_6" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_6_7" tabindex="1" id="MAT_6_7" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_6_8" tabindex="1" id="MAT_6_8" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_6_9" tabindex="1" id="MAT_6_9" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_6_10" tabindex="1" id="MAT_6_10" type="text"  /></td>
								       </tr>
								       <tr>
								           <td>CUP - Costos Unitarios de Acueducto ($/mᵌ)</td>
								           <td>CUP - Costos Unitarios de Alcantarillado ($/mᵌ)</td>
								           <td>Costos particulares: Mantener los actuales o reducirlos.</td>
								           <td>No incrementar los costos.</td>
								           <td><input class="form-control" value="" name="MAT_7_1" tabindex="1" id="MAT_7_1" type="text"  /></td>
										   <td><input class="form-control" value="" name="MAT_7_2" tabindex="1" id="MAT_7_2" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_7_3" tabindex="1" id="MAT_7_3" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_7_4" tabindex="1" id="MAT_7_4" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_7_5" tabindex="1" id="MAT_7_5" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_7_6" tabindex="1" id="MAT_7_6" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_7_7" tabindex="1" id="MAT_7_7" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_7_8" tabindex="1" id="MAT_7_8" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_7_9" tabindex="1" id="MAT_7_9" type="text"  /></td>
								           <td><input class="form-control" value="" name="MAT_7_10" tabindex="1" id="MAT_7_10" type="text"  /></td>
								       </tr>
								   </tbody>
								</table>
								<div class="row">
									<div class="col-sm-12  col-md-8">
										<input type="button" id="previous7" name="previous7" value="Anterior" />
									</div>
								</div>
								
								<div class="row">
									<div class="col-sm-8">
										<input id="saveForm" type="submit" value="Enviar"
											class="btn btn-primary" onclick="valida_form();" /> <input
											name="button" type="button" id="button" onclick="window.close();"
											value="Cancelar" class="btn btn-primary" />
									</div>
								</div>
							</div>

							
			<!-- ************************** fin pagina 7 ********************************-->

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