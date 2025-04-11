<?php
session_start();
if (empty($_SESSION["krd"])) {
    if (empty($_REQUEST["krd"])) {
        header('Location: ../login.php');
    }
    include "../rec_session.php";
}
$ruta_raiz = "../";
$fecha = date("Ymdhis");
require_once realpath(dirname(__FILE__) . "/../") . "/atencion/AtencionTipos.php";
require_once realpath(dirname(__FILE__) . "/../") . "/atencion/conf_form.php";
$_SESSION["idFormulario"] = sha1(microtime(true) . mt_rand(10000, 90000));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>::  Formulario PQRS</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!--Deshabilitar modo de compatiblidad de Internet Explorer-->

<link rel="stylesheet" href="../estilos/bootstrap.min.css"
	type="text/css" />
<link rel="stylesheet"
	href="../estilos/CRA.bootstrap.min.css?jashd=<?php echo date("ymdhmi")?>"
	type="text/css" />

<!-- CSS -->
<link href="../estilos/jquery-ui.css" rel="stylesheet">
	<script src="../js/jquery.min.js"></script>
	<script src="../js/bootstrap/bootstrap.min.js"></script>
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
	<script type="text/javascript"
		src="../js/jquery.validate.min.js?sdf=<?php echo date("ymdhis")?>">
	</script>
	<!--funciones-->
	<script type="text/javascript">
		 var basePath ='<?php echo $ruta_raiz?>';
         var urlEntidad ='<?php echo $httpWebOficial ?>';
 	</script>
	<script type="text/javascript"
		src="../js/bootstrap/bootstrap-dialog.js">
	</script>
	<script type="text/javascript"
		src="js/atencion.js?tes=<?php echo date("Ymdhis")?>">
	</script>
	<script type="text/javascript"
		src="../js/divipola.js?tes=<?php echo date("Ymdhis")?>">
	</script>
</head>
<body id="public">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3>Formulario de Captura</h3>
		</div>
		<div class="panel-body">
			<div class="container-fluid">
				<form id="contactoOrfeo" class="" autocomplete="off"
					enctype="multipart/form-data" method="post" action="radicar.php"
					name="quejas">
					<input type="hidden" name="idFormulario" value="<?php echo $_SESSION["idFormulario"]?>" /> 
					<input type="hidden" name="mrec" value="<?php echo $_GET["med"]?>" />
					<input type="hidden" name="ent" value="<?php echo $_GET["ent"]?>" />
					<input type="hidden" name="primero" value="<?php echo $_GET["primera"]?>" /> 
					<br />
					<div class="row">
						<div class="col-sm-2 col-md-2 text-center"></div>
						<div class="col-md-8 text-justify h4" style="margin-bottom: 0px;">
							<p>
								Tenga en cuenta lo siguiente: <br /> <br />La Ley
								1755 de 2015 en su articulo 13 establece que toda actuación que
								inicie cualquier persona ante las autoridades implica el
								ejercicio del derecho de petición consagrado en el artículo 23
								de la Constitución Política, sin que sea necesario invocarlo.

							</p>
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
							</div>
							<div class="row">
								<div class="col-md-3">
									<label
										class="desc desc desc control-label etiqueta control-label label-success form-control"
										id="title_Anonimo" for="anonimo">¿Desea que su petición sea anónima?
										<font color="#FF0000">*</font>
									</label>
								</div>
								<div class="col-md-4 text-left">
									<div class="radio-inline">
										<label class="radio-inline"> <input type="radio"
											name="anonimo" id="identifica" checked="checked" value="1" />No
										</label> 
										<label class="radio-inline"> 
											<input type="radio" name="anonimo" id="chkAnonimo" value="2" />Sí
										</label>
									</div>
								</div>
								<div class="col-md-4 text-left" style="display: none;" id="ema">
										<label class=" desc control-label etiqueta  control-label label-success  form-control">E-mail</label>
										<input type="text"name="emailA" id="emailA" />
								</div>
							</div>
							<div>

								<div class="noanonimo seltipo">
									<div class=" noanonimo seltipo row form-group">
										<div class="col-sm-12  col-md-3">
											<label
												class=" desc control-label etiqueta  control-label label-success  form-control"
												for="tipoPersona">Tipo de Persona
											</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<select data-placeholder="Seleccione una opción"
												title="Debe Seleccionaar un tipo de Persona, este campo  es Obligatorio"
												class="chosen-select form-control dropdown seleccion"
												id="tipoPersona" name="tipoPersona">
												<option value=""></option>
												<option value="2">Persona Natural</option>
												<option value="1">Persona Júridica</option>
												<!-- <option value="3">ESP</option> -->
											</select>
										</div>
									</div>
									<div class="row col-sm-12 col-md-12 dinamic" id="juridica" style="display: none">
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc desc control-label etiqueta control-label label-success form-control" for="txtnit">Nit*
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input type="hidden" id="id_emp" value="" name="id_emp" /> 
												<input
													class="form-control obligatorio" value="" maxlength="16"
													title="Debe Dilígenciar el Nit el campo es obligatorio"
													onkeypress="return alpha(event,numbers)"
													minlenght="3" name="txtnit" tabindex="4"
													id="txtnit" type="text" />
											</div>
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="txtnoEmpresa">Nombre Empresa*
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input class="form-control obligatorio" value=""
													maxlength="150" title="el campo Nombre es obligatorio "
													minlenght="3" name="txtnoEmpresa" tabindex="4"
													id="txtnoEmpresa" type="text" />
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc  control-label etiqueta  control-label label-success  form-control"
													for="txtrep">Representante Legal*
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input class="form-control obligatorio" value=""
													maxlength="80"
													title="el campo Representante es obligatorio "
													minlenght="3" name="txtrep" tabindex="4"
													onkeypress="return alpha(event,numbers+letters)"
													id="txtrep" type="text" />

											</div>
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="txtdirEmpresa">Dirección*
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input class="form-control obligatorio" value=""
													maxlength="130" tabindex="4"
													onkeypress="return alpha(event,numbers+letters)"
													title="El campo Dirección  de la empresa es obligatorio "
													minlenght="3" name="txtdirEmpresa" id="txtdirEmpresa"
													type="text" />

											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc desc     control-label etiqueta  control-label label-success  form-control"
													for="txtcontacto">Teléfono Contacto*
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input class="form-control" value="" maxlength="15"
													name="txtcontacto"
													title="El campo teléfono de la Empresa es Obligatorio"
													tabindex="4"
													onkeypress="return alpha(event,numbers+letters)"
													id="txtcontacto" type="text" />
											</div>
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="emailEmpresa">Correo Eléctronico
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input class="form-control obligatorio email" value=""
													name="emailEmpresa"
													title="el campo email es obligatorio y debe ser válido"
													minlenght="3" tabindex="4" id="emailEmpresa" type="text" />
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc  control-label etiqueta  control-label label-success  form-control"
													for="grupo">Tipo de Empresa*
												</label>
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
									<div class=" row col-sm-12  col-md-12 dinamic" id="esp" style="display: none">
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc  control-label etiqueta  control-label label-success  form-control"
													for="txtnoESP">Nombre ESP
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input class="obligatorio" type="hidden" id="idEsp" name="idEsp" value="" />
												<input class="form-control" value="" maxlength="200"
													tabindex="4"
													onkeypress="return alpha(event,numbers+letters)"
													title="el campo Nombre de la ESP es obligatorio y debe ser seleccionado de la lista"
													minlenght="3" name="txtnoESP" id="txtnoESP" type="text" />
											</div>
											<div class="col-sm-12  col-md-3">
												<label
													class="desc desc     control-label etiqueta  control-label label-success  form-control"
													for="txtnitESP">Nit*
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input class="form-control obligatorio" value=""
													maxlength="15" name="EspNit" tabindex="4"
													onkeypress="return alpha(event,numbers+letters)"
													title="Debe seleccionar la ESP del Listado que aparece al digitar el nombre"
													name="txtnitESP" id="txtnitESP" type="text"
													readonly="readonly" />
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="txtRepESP">Representante Legal
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input class="form-control" value="" maxlength="80"
													tabindex="4" name="txtRepESP" 
													onkeypress="return alpha(event,numbers+letters)"
													id="txtRepESP" type="text" disabled />

											</div>
											<div class="col-sm-12  col-md-3">
												<label
													class="desc desc     control-label etiqueta  control-label label-success  form-control"
													for="txtDirESP">Dirección
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input class="form-control" value="" maxlength="100"
													tabindex="4" name="txtDirESP" id="txtDirESP" type="text" disabled 
												/>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="txtnfijoESP">Teléfono de Contacto
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input class="form-control" value="" maxlength="15"
													tabindex="4" name="txtnfijoESP" 
													onkeypress="return alpha(event,numbers+letters)"
													id="txtnfijoESP" type="text" disabled />

											</div>
											<div class="col-sm-12  col-md-3">
												<label
													class="desc desc     control-label etiqueta  control-label label-success  form-control"
													for="txtemailESP">Correo Eléctronico
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input class="form-control" value="" maxlength="50"
													tabindex="5" name="txtemailESP" 
													id="txtemailESP" type="text" disabled />

											</div>
											
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc desc     control-label etiqueta  control-label label-success  form-control"
													for="grupo">Tipo de ESP*
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<select data-placeholder="Tipo de Empresa"
													title="Debe seleccione un tipo de la lista el campo es obligatorio"
													class="dropdown  seleccion form-control chosen-select "
													id="tipoESP" name="tipoESP">
													<option selected="" value="">Seleccione Tipo de Empresa</option>
												</select>
											</div>
										</div>
									</div>
									<div class=" row col-sm-12  col-md-12 dinamic" id="persona" style="display: none">
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc desc     control-label etiqueta  control-label label-success  form-control"
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
												<label
													class="desc  control-label etiqueta  control-label label-success  form-control"
													for="txtdocumento"><span>Documento de Identidad</span>
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input type="hidden" id="id_ciu" value="" name="id_ciu" /> 
												<input
													class="form-control obligatorio" value="" maxlength="16"
													title="Debe Dilígenciar el Documento el campo es obligatorio "
													minlenght="3" name="documento" tabindex="4"
													id="documento" type="text" />

											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="txtapellido1">Primer Apellido*
												</label>
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
												<label
													class="desc   control-label etiqueta  control-label label-success  form-control"
													for="txtapellido2">Segundo Apellido
												</label>
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
												<label
													class="desc desc     control-label etiqueta  control-label label-success  form-control"
													for="txtnombre1">Primer Nombre*
												</label>
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
												<label
													class="desc desc     control-label etiqueta  control-label label-success  form-control"
													for="txtnombre2">Segundo Nombre
												</label>
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
												<label
													class="desc  control-label etiqueta  control-label label-success  form-control"
													for="txtdir">Dirección*
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input class="form-control obligatorio" value=""
													maxlength="100" title="el campo Dirección es obligatorio "
													minlenght="3" name="direccion" tabindex="4"
													onkeypress="return alpha(event,numbers+letters)"
													id="txtdir" type="text" />

											</div>
											<div class="col-sm-12  col-md-3">
												<label
													class="desc desc     control-label etiqueta  control-label label-success  form-control"
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
												<label
													class="desc  control-label etiqueta  control-label label-success  form-control"
													for="txtnfijo">Teléfono Fijo
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input class="form-control" value="" maxlength="15"
													name="telefono" tabindex="4"
													onkeypress="return alpha(event,numbers+letters)"
													id="txtnfijo" type="text" />
											</div>
											<div class="col-sm-12  col-md-3">
												<label
													class="desc  control-label etiqueta  control-label label-success  form-control"
													for="txtcelular">Móvil Célular
												</label>
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
												<label
													class="desc  control-label etiqueta  control-label label-success  form-control"
													for="txtnfijo">Sexo*
												</label>
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
												<label
													class="desc desc     control-label etiqueta  control-label label-success  form-control"
													for="grupo">Grupo Poblacional*</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<select data-placeholder="Grupo Poblacional"
													title="Debe seleccionar un grupo de la lista el campo es obligatorio"
													class="dropdown obligatorio seleccion form-control chosen-select "
													id="grupo" name="grupo">
													<option selected="" value="">Seleccione Grupo Poblacional</option>
													<option value="0">NINGUNO,N/A</option>
													<option value="1">NIÑOS, NIÑAS, ADOLESCENTES</option>
													<option value="0">POBLACIÓN DE LA TERCERA EDAD</option>
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
										<label
											class="desc control-label etiqueta  control-label label-success  form-control"
											for="txtnombre2">Autoriza ser Notificado por correo electrónico
										</label>
									</div>
									<div class="col-sm-12  col-md-3 ">
										<label class="checkbox-inline">
											<input type="checkbox" value="1" name="notifica" />Sí
										</label> 
										<label class="checkbox-inline">
											<input type="checkbox" value="2" name="notifica" />No
										</label>
									</div>
								</div>
								<div class="row col-sm-12  col-md-12 noanonimo" id="ubicacion">
									<div class="row form-group">
										<div class="col-sm-12  col-md-3">
											<label
												class="desc desc     control-label etiqueta  control-label label-success  form-control"
												for="pais">País*
											</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<select data-placeholder="País"
												title="Debe seleccionar un pais el el campo es obligatorio "
												name="pais" class="chosen-select form-control seleccion" style="width:100%"
												id="pais">
												<option value=""></option>
											</select>
										</div>
										<div class="col-sm-12  col-md-3">
											<label for="dpto" class="error" style="display: none; width:100%">Seleccione un Departamento el campo es Obligatorio
											</label>
											<label
												class="desc control-label etiqueta  control-label label-success  form-control"
												for="txtdocumento"><span>Departamento*</span>
											</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<select data-placeholder="Departamento"
												class="chosen-select form-control dropdown seleccion"
												id="dpto" title="el campo Departamento es obligatorio " style="width:100%"
												name="dpto">
											</select>
										</div>
									</div>
									<div class="row form-group">
										<div class="col-sm-12  col-md-3">
											<label
												class="desc desc     control-label etiqueta  control-label label-success  form-control"
												for="txtapellido1">Municipio*
											</label>
										</div>
										<div class="col-sm-12  col-md-3 ">
											<select data-placeholder="Municipio"
												class="chosen-select form-control dropdown seleccion"
												id="mcpio"
												title="el campo tipo de Municipio es obligatorio "
												minlenght="3" name="mncpio">
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-8 text-center">
							<!-- 29/10/2018 CARLOS RICAURTE se oculta boton registrar type="button -->
								<!--  <button type="button" name="reg" class="btn btn-primary" onclick="display_mensaje();"  style.display = "none" >Registrar</button>	-->
								<input id="saveForm" type="submit" value="Continuar"
									class="btn btn-primary" onclick="valida_form();" />
							</div>
						</div>
				
				</form>

			</div>
		</div>
	</div>

<?php
 require_once realpath(dirname(__FILE__)) . "/conf_form.php"; ?>
 <script type="text/javascript">
  var basePath ='<?php echo $ruta_raiz?>';
  var urlOf ="<?php echo   $httpWebOficial;?>"; 
  function display_mensaje(){
	console.log(!$("#contactoOrfeo").valid());
	if($("#contactoOrfeo").valid()){
	  BootstrapDialog.alert({
			title : "Registro!!",
			message : "Informacion Registrada"
		});
	 }else{
		 valida_form();
	 }
  }
  </script>

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
</body>
</html>
