<?php
session_start();
$cTab=1;
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
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

// TamaNo mAximo del todos los archivos en bytes 10MB = 10(MB)*1024(KB)*1024(B) = 10485760 bytes
$max_file_size = 20971520;

if (! isset($isFacebook)) {
    $isFacebook = 0;
}
?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <title>:: <?=$entidad_largo ?>:: Formulario PQRS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--Deshabilitar modo de compatiblidad de Internet Explorer-->

    <link rel="stylesheet" href="../estilos/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="css/CRA.bootstrap.min.css?jashd=<?php echo date("ymdhmi")?>" type="text/css" />

    <!-- CSS -->
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <link rel="stylesheet" href="css/fineuploader.css" type="text/css" />
    <link href="../estilos/jquery-ui.css" rel="stylesheet">
    <link rel="stylesheet" href="css/structure2.css" type="text/css" />


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
    <link href="../estilos/bootstrap-chosen.css?sdf=<?php echo date("ymdhis")?>" rel="stylesheet" />
    <link href="../estilos/bootstrap-dialog.css" rel="stylesheet" />


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->


    <link href="../estilos/ie10-viewport-bug-workaround.css" rel="stylesheet" />

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
    <script type="text/javascript" src="../js/jquery.validate.min.js?sdf=<?php echo date("ymdhis")?>"></script>
    <!--funciones-->
    <script type="text/javascript" src="../js/bootstrap-dialog.js"></script>
    <script type="text/javascript" src="../js/divipola.js?tes=<?php echo date("Ymdhis")?>"></script>
    <script type="text/javascript" src="scripts/ajax.js?tes=<?php echo date("Ymdhis")?>"></script>
    <script type="text/javascript">
    var basePath = '<?php echo $ruta_raiz?>';
    var urlEntidad = '<?php echo  $httpWebOficial ?>';
    </script>

    <script src='https://www.google.com/recaptcha/api.js?render=6LfdUcwpAAAAAHP-TH_-B_3wJJP9Lp3spAAV_0rf'> 
    </script>
    <script>
        grecaptcha.ready(function() {
        grecaptcha.execute('6LfdUcwpAAAAAHP-TH_-B_3wJJP9Lp3spAAV_0rf', {action: 'formulario'})
        .then(function(token) {
        var recaptchaResponse = document.getElementById('recaptchaResponse');
        recaptchaResponse.value = token;
        });});
    </script>

</head>

<body id="public">
    <input type="hidden" name="recaptcha_response" id="recaptchaResponse" />
    <div class="container-fluid">
        <form id="contactoOrfeo" class="" autocomplete="off" enctype="multipart/form-data" method="post"
            action="formulariotx.php" name="quejas">
            <br />
            <div class="row">
                <div class="col-sm-2 col-md-2 text-center"></div>
                <div class="col-md-12 text-justify h4" style="margin-bottom: 0px;">
                    <h1>PQRSD (Recepci&oacute;n de solicitudes)</h1><br />
                    <p>
                        Apreciado ciudadano, al diligenciar el formulario, tenga en cuenta lo siguiente:
                    </p>
                    <p>
                        La Ley 1755 de 2015 en su articulo 13 establece que toda actuación que inicie
                        cualquier persona ante las autoridades implica el ejercicio del
                        derecho de petición consagrado en el artículo 23 de la
                        Constitución Política, sin que sea necesario invocarlo.
                    </p>
                    <p style="margin-bottom: 0px;">
                        * Ingresar a este módulo con el navegador Mozilla Firefox actualizado <u> <a
                                href="https://www.mozilla.org/es-ES/firefox/windows/" target="_blank"
                                rel="noopener noreferrer">Descargar Mozilla</a></strong<u></u><br><br />
                    </p>
                    <p style="margin-bottom: 0px;">
                        * Lo invitamos a encontrar solución a su inquietud a través de
                        nuestro banco de <u><a href="<?php echo $urlFaq?>">preguntas
                                frecuentes</a></u>.<br />
                    </p>
                    <br>
                    <p style="margin-bottom: 0px;">
                        * Consulte los <u><a href="https://www.cra.gov.co/seccion/costos-de-reproduccion.html">Costos de
                                reproducción de información</a></u>
                    </p>
                    <p style="margin-bottom: 0px;">
                        * Si desea una solicitud de información pública con identidad reservada <u><a
                                href="https://www.procuraduria.gov.co/portal/pqrsdf_Solicitud_de_informacion_con_identificacion_reservada.page">haga
                                click AQUÍ</a></u>
                    </p>
                    <p style="margin-bottom: 0px;">
                        * Consulte <u><a href="./images/Derechos_peticion.pdf">los plazos de respuesta</a> </u>.<br />
                        <br />Si su búsqueda no fue satisfactoria, agredecemos diligenciar la siguiente solicitud.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2 col-md-2 text-center"></div>
                <div class="col-md-12 ">
                    <h3>Seleccione el tipo de solicitud que desea registrar</h3>
                </div>
            </div>
            <div class="container-fluid">
                <div class="pub">
                    <div class="boxcontainercontent">
                        <?php
                        $atencioTipos = new AtencionTipos();

                        $imagenesTipos = array(
                            1 => 'peticionIcono.png',
                            2 => 'QuejaIcono.png',
                            3 => 'ReclamoIcono.png',
                            4 => 'sugerenciaIcono.png',
                            5 => 'DenunciaContratacionIcono.png',
                            6 => 'ElogioIcono.png',
                            7 => 'denunciaIcono.png',
                            8 => 'SolicitudIcono.png'
                        );

                        $atencionTipo = $atencioTipos->findActivas();
                        if (! empty($atencionTipo)) {
                            
                            foreach ($atencionTipo as $clave => $value) {
                                echo '<div class="row">
                                          <div class="col-md-1 text-center">
                                            <img src="./images/'.$imagenesTipos[$value["id"]].'" class="icon-'.$value["id"].'"></img>
                                          </div>
                                          <div class="col-md-11 text-justify">
                                            <h4> ' . mb_convert_case($value["nombre"], MB_CASE_UPPER, "UTF-8") . '</h4>
                                            <p>' . $value["descripcion"] . '</p>
                                            <label class="radio-inline-2">
                                            <input tabindex="'.$cTab.'" type="radio" name="tipoPeticion" value="' . $value["id"] . '" id="' . strtolower($value["nombre"]) . '" />  
                                             ENVIAR UN(A) '.mb_convert_case($value["nombre"], MB_CASE_UPPER, "UTF-8").'<label>
                                           </div>
                                        </div>';
                            }
                        }
                        $cTab++;
                        ?>
                        <label for="tipoPeticion" class="error" style="display: none;">Por
                            favor Seleccione Una opción</label>
                    </div>

                    <div class="row">

                        <div class="col-md-12 text-left">
                            <h3>Seleccione de que manera desea registrar la solicitud</h3>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-12 text-center">
                            <div class="col-md-6">
                                <label class="radio-inline-1">
                                    <input type="radio" name="anonimo" id="anonimo" checked="checked" value="1" tabindex="<?php echo($cTab++); ?>"/>
                                    A nombre personal
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="radio-inline-1">
                                    <input type="radio" name="anonimo" id="chkAnonimo" value="2" tabindex="<?php echo($cTab++); ?>" />
                                    An&oacute;nima
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-12 text-justify">
                            <p>Se recopilarán datos personales básicos de identificación que son tratados conforme la
                                Política de Datos Personales y Privacidad que puede consultar en el link de Políticas.
                                La respuesta se envía directamente a su correo electrónico.</p>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-12 text-left">
                            <h3>A continuación complete sus datos para dar respuesta a su solicitud</h3>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-12 text-left">
                            <h4>Datos del solicitante</h4>
                            <span>Los campos en asterisco (*) son obligatorios</span>
                        </div>
                    </div>
                    <div>

                        <div class="noanonimo seltipo">
                            <div class=" noanonimo seltipo row form-group">

                                <div class="col-md-10 text-left">
                                    <label class=" desc control-label text-left form-control" for="tipoPersona">Tipo de
                                        Persona *</label>
                                </div>

                                <div class="col-md-6 text-left">
                                    <select data-placeholder="Seleccione una opción"
                                        title="Debe Seleccionaar un tipo de Persona, este campo  es Obligatorio"
                                        class="chosen-select form-control dropdown seleccion" id="tipoPersona"
                                        name="tipoPersona" tabindex="<?php echo($cTab++); ?>">
                                        <option value=""></option>
                                        <option value="2">Persona Natural</option>
                                        <option value="1">Persona Júridica</option>
                                    </select>
                                </div>
                                <div class="col-md-6 text-left">
                                </div>
                            </div>
                        </div>
                        <div class=" row col-sm-12  col-md-12 dinamic" id="juridica" style="display: none">
                            <div class="row form-group">

                                <div class="col-md-6">
                                    <label class="desc desc     control-label text-left form-control"
                                        for="txtnit">Nit*</label>
                                </div>
                                <div class="col-md-6 ">
                                    <label class="desc desc     control-label text-left form-control"
                                        for="txtnoEmpresa">Nombre Empresa*</label>

                                </div>
                            </div>
                            <div class="row form-group">

                                <div class="col-md-6">
                                    <input class="form-control obligatorio digitos" value="" maxlength="15"
                                        title="el campo Nit es obligatorio y solo acepta digitos" minlenght="3"
                                        name="txtnit" tabindex="<?php echo($cTab++); ?>"  onkeypress="return alpha(event,numbers)" id="txtnit"
                                        type="text" />
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control obligatorio" value="" maxlength="80"
                                        title="el campo Nombre es obligatorio " minlenght="3" name="txtnoEmpresa"
                                        tabindex="<?php echo($cTab++); ?>"  onkeypress="return alpha(event,numbers+letters)" id="txtnoEmpresa"
                                        type="text" />

                                </div>
                            </div>
                            <div class="row form-group">

                                <div class="col-md-6">
                                    <label class="desc  control-label text-left form-control" for="txtnit">Representante
                                        Legal*</label>
                                </div>
                                <div class="col-md-6">
                                    <label class="desc control-label text-left form-control"
                                        for="txtdirEmpresa">Dirección*</label>

                                </div>
                                <div class="row form-group">

                                    <div class="col-md-6">
                                        <input class="form-control obligatorio" value="" maxlength="80"
                                            title="el campo Representante es obligatorio " minlenght="3" name="txtrep"
                                            tabindex="<?php echo($cTab++); ?>"  onkeypress="return alpha(event,letters)" id="txtrep"
                                            type="text" />
                                    </div>
                                    <div class="col-md-6 ">
                                        <input class="form-control obligatorio" value="" maxlength="150" tabindex="<?php echo($cTab++); ?>" 
                                            onkeypress="return alpha(event,numbers+letters+custom+signs)"
                                            title="El campo Dirección  de la empresa es obligatorio " minlenght="3"
                                            name="txtdirEmpresa" id="txtdirEmpresa" type="text" />

                                    </div>
                                </div>
                                <div class="row form-group">

                                    <div class="col-md-6">
                                        <label class="desc desc     control-label text-left form-control"
                                            for="txtnfijo">Teléfono
                                            Contacto*</label>
                                    </div>
                                    <div class="col-md-6 ">
                                        <label class="desc control-label text-left form-control" for="txtemmail">Correo
                                            Eléctronico</label>

                                    </div>
                                </div>
                                <div class="row form-group">

                                    <div class="col-md-6">
                                        <input class="form-control" value="" maxlength="15" name="txtcontacto"
                                            title="El campo teléfono de la Empresa es Obligatorio" tabindex="<?php echo($cTab++); ?>" 
                                            onkeypress="return alpha(event,numbers)" id="txtcontacto"
                                            type="text" />
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control obligatorio email" value="" name="emailEmpresa"
                                            title="el campo email es obligatorio y debe ser válido" minlenght="3"
                                            tabindex="<?php echo($cTab++); ?>"  id="txtmailEmpresa" type="text" />
                                    </div>
                                </div>
                                <div class="row form-group">

                                    <div class="col-md-6">
                                        <label class="desc desc     control-label text-left form-control"
                                            for="grupo">Tipo
                                            de
                                            Empresa*</label>
                                    </div>
                                    <div class="col-md-6 ">

                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">

                                <div class="col-md-6">
                                    <select data-placeholder="Tipo de Empresa"
                                        title="Debe seleccione un tipo de la lista el campo es obligatorio"
                                        class="dropdown obligatorio seleccion form-control chosen-select "
                                        id="tipoEmpresa" name="tipoEmpresa">
                                        <option selected="" value="">Seleccione Tipo de Empresa</option>
                                    </select>
                                </div>
                                <div class="col-md-6 ">

                                </div>
                            </div>
                        </div>
                        <div class=" row col-sm-12  col-md-12 dinamic" id="persona" style="display: none">
                            <div class="row form-group">

                                <div class="col-md-6 text-left">
                                    <label class="desc desc     control-label text-left form-control"
                                        for="tipoDocumento">Tipo
                                        de documento *</label>
                                </div>
                                <div class="col-md-6 ">
                                </div>
                            </div>
                            <div class="row form-group">

                                <div class="col-md-6 text-left ">
                                    <select data-placeholder="tipo de documento"
                                        title="Debe Seleccionar un Tipo de Documento el campo es obligatorio "
                                        name="tipoDocumento"
                                        class="dropdown obligatorio seleccion chosen-select form-control"
                                        id="tipoDocumento" tabindex="<?php echo($cTab++); ?>" >
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="col-md-6 text-left ">
                                </div>
                            </div>
                            <div class="row form-group">

                                <div class="col-md-6 text-left">
                                    <label class="desc  control-label text-left form-control"
                                        for="txtdocumento"><span>Documento de Identidad</span></label>
                                </div>
                                <div class="col-md-6 text-left ">
                                </div>
                            </div>
                            <div class="row form-group">

                                <div class="col-md-6 ">
                                    <input class="form-control obligatorio" value="" maxlength="1555555"
                                        title="Debe Dilígenciar el Documento el campo es obligatorio " minlenght="3"
                                        name="documento" tabindex="<?php echo($cTab++); ?>"  onkeypress="return alpha(event,numbers)"
                                        id="txtdocumento" type="text" />

                                </div>
                                <div class="col-md-6 text-left ">
                                </div>
                            </div>
                            <div class="row form-group">

                                <div class="col-md-6 text-left">
                                    <label class="desc control-label text-left form-control" for="txtapellido1">Primer
                                        Apellido*</label>
                                </div>
                                <div class="col-md-6 text-left ">
                                    <label class="desc   control-label text-left form-control"
                                        for="txtapellido2">Segundo
                                        Apellido</label>
                                </div>
                            </div>
                            <div class="row form-group">

                                <div class="col-md-6 text-left">
                                    <input class="form-control obligatorio" value="" maxlength="50"
                                        title="el campo Primer Apellido es obligatorio " minlenght="3"
                                        name="primApellido" tabindex="<?php echo($cTab++); ?>" 
                                        onkeypress="return alpha(event,letters)" id="txtapellido1"
                                        type="text" />
                                </div>
                                <div class="col-md-6 text-left ">
                                    <input class="form-control" value="" maxlength="50" name="segApellido" tabindex="<?php echo($cTab++); ?>" 
                                        onkeypress="return alpha(event,letters)" id="txtapellido2"
                                        type="text" />

                                </div>
                            </div>
                            <div class="row form-group">

                                <div class="col-md-6 text-left">
                                    <label class="desc desc     control-label text-left form-control"
                                        for="txtnombre1">Primer
                                        Nombre*</label>
                                </div>
                                <div class="col-md-6 text-left ">
                                    <label class="desc desc     control-label text-left form-control"
                                        for="txtnombre2">Segundo
                                        Nombre</label>

                                </div>
                            </div>
                            <div class="row form-group">

                                <div class="col-md-6 text-left">
                                    <input class="form-control obligatorio" value="" maxlength="50"
                                        title="el campo tipo de Documento es obligatorio " minlenght="3"
                                        name="primNombre" tabindex="<?php echo($cTab++); ?>"  onkeypress="return alpha(event,letters)"
                                        id="txtnombre1" type="text" />

                                </div>
                                <div class="col-md-6 text-left ">
                                    <input class="form-control" value="" maxlength="50" name="segNombre" tabindex="<?php echo($cTab++); ?>" 
                                        onkeypress="return alpha(event,letters)" id="txtnombre2" type="text" />
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6 text-left">
                                    <label class="desc  control-label text-left form-control"
                                        for="txtdir">Dirección*</label>
                                </div>
                                <div class="col-md-6 text-left ">
                                    <label class="desc desc     control-label text-left form-control"
                                        for="txtemail">Correo
                                        Eléctronico</label>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6 text-left">
                                    <input class="form-control obligatorio" value="" maxlength="100"
                                        title="el campo Dirección es obligatorio " minlenght="3" name="direccion"
                                        tabindex="<?php echo($cTab++); ?>"  onkeypress="return alpha(event,numbers+letters+custom+signs)" id="txtdir"
                                        type="text" />
                                </div>
                                <div class="col-md-6 text-left ">
                                    <input class="form-control email" value="" maxlength="50"
                                        title="el campo email es obligatorio y debe ser válido" minlenght="3"
                                        name="email" tabindex="<?php echo($cTab++); ?>"  id="txtemail" type="text" />
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6 text-left">
                                    <label class="desc  control-label text-left form-control" for="txtnfijo">Teléfono
                                        Fijo</label>
                                </div>
                                <div class="col-md-6 text-left ">
                                    <label class="desc  control-label text-left form-control" for="txtcelular">Móvil
                                        Célular</label>

                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6 text-left">
                                    <input class="form-control" value="" maxlength="15" name="telefono" tabindex="<?php echo($cTab++); ?>" 
                                        onkeypress="return alpha(event,numbers)" id="txtnfijo" type="text" />
                                </div>
                                <div class="col-md-6 text-left ">
                                    <input class="form-control" value="" maxlength="14" name="celular" tabindex="<?php echo($cTab++); ?>" 
                                        onkeypress="return alpha(event,numbers)" id="txtcelular" type="text" />

                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6 text-left">
                                    <label class="desc  control-label text-left form-control"
                                        for="txtnfijo">Sexo*</label>
                                </div>
                                <div class="col-md-6 text-left ">
                                    <label class="desc desc     control-label text-left form-control" for="grupo">Grupo
                                        Poblacional*</label>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6 text-left">
                                    <select class="form-control obligatorio dropdown chosen-select seleccion"
                                        title="el campo es obligatorio " minlenght="3" name="sexo" id="sexo" tabindex="<?php echo($cTab++); ?>" >
                                        <option value="2">Masculino</option>
                                        <option value="1">Femenino</option>
                                    </select>
                                </div>
                                <div class="col-md-6 text-left ">
                                    <select data-placeholder="Grupo Poblacional"
                                        title="Debe seleccionar un grupo de la lista el campo es obligatorio"
                                        class="dropdown obligatorio seleccion form-control chosen-select " id="grupo"
                                        name="grupo" tabindex="<?php echo($cTab++); ?>" >
                                        <option selected="" value="">Seleccione Grupo
                                            Poblacional
                                        </option>
                                        <option value="0">NINGUNO, N/A</option>
                                        <option value="1">NIÑOS, NIÑAS, ADOLESCENTES
                                        </option>
                                        <option value="2">POBLACIÓN EN CONDICIÓN DE
                                            DISCAPACIDAD
                                        </option>
                                        <option value="3">POBLACIÓN EN SITUACIÓN DE
                                            DESPLAZAMIENTO
                                        </option>
                                        <option value="4">DESMOVILIZADOS</option>
                                        <option value="5">POBLACIÓN ROM</option>
                                        <option value="6">POBLACIÓN RAIZAL</option>
                                        <option value="7">AFROCOLOMBIANO</option>
                                        <option value="8">MIGRATORIO</option>
                                        <option value="9">POBLACIÓN RURAL</option>
                                        <option value="10">VÍCTIMA DE VIOLENCIA ARMADA
                                        </option>
                                        <option value="11">POBLACIÓN LGBTI</option>
                                        <option value="12">VOCALES DE CONTROL</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row col-sm-12  col-md-12 noanonimo" id="ubicacion">
                        <div class="row form-group">
                            <div class="col-md-6 text-left">
                                <label class="desc desc     control-label text-left form-control"
                                    for="pais">País*</label>
                            </div>
                            <div class="col-md-6 text-left ">
                                <label for="dpto" class="error" style="display: none;">Seleccione
                                    un Departamento el campo es Obligatorio</label> <label
                                    class="desc control-label text-left form-control"
                                    for="txtdocumento"><span>Departamento*</span></label>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6 text-left">
                                <select data-placeholder="País"
                                    title="Debe seleccionar un pais el el campo es obligatorio " name="pais"
                                    class="chosen-select form-control seleccion" id="pais" tabindex="<?php echo($cTab++); ?>" >
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-md-6 text-left ">
                                <select data-placeholder="Departamento"
                                    class="chosen-select form-control dropdown seleccion" id="dpto"
                                    title="el campo Departamento es obligatorio " name="dpto" tabindex="<?php echo($cTab++); ?>" >
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6 text-left">
                                <label class="desc desc     control-label text-left form-control"
                                    for="txtapellido1">Municipio*</label>
                            </div>
                            <div class="col-md-6 text-left ">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6 text-left ">
                                <select data-placeholder="Municipio"
                                    class="chosen-select form-control dropdown seleccion" id="mcpio"
                                    title="el campo tipo de Municipio es obligatorio " minlenght="3" name="mcpio" tabindex="<?php echo($cTab++); ?>" >
                                </select>
                            </div>
                            <div class="col-md-6 text-left ">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row form-group">

                <div class="col-md-12">
                    <label class=" desc control-label text-left form-control" id="lbl_asunto" for="campo_asunto">
                        <h4>Asunto de la PQRS<font color="#FF0000">*</font>
                        </h4>
                    </label>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-12">
                    <input id="asunto" name="asunto" type="text" class="form-control large" value="" maxlength="80"
                        tabindex="<?php echo($cTab++); ?>"  required minlength="6" 
                        title="el ausnto es obligatorio y debe ser de máximo 80 carácteres y minímo 3"
                        placeholder="asunto" />
                    &nbsp;
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-12">
                    <label class=" desc desc     control-label text-left form-control" id="lbl_comentario"
                        for="campo_comentario">
                        <h4> Descripción de la PQRSD <font color="#FF0000">*</font>
                        </h4>
                    </label>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-12">
                    <textarea id="campo_comentario" name="comentario" class="form-controlarea textarea large" rows="10"
                        cols="50" tabindex="<?php echo($cTab++); ?>"  onkeyup="countChar(this)" placeholder="Escriba ac&aacute; ..." required
                        minlength="6"
                        title="la descripción de la solicitud es obligatorio y debe ser de máximo 2000 carácteres y minímo 3"></textarea>
                    <input type="hidden" id="adjuntosSubidos" name="adjuntosSubidos" value="" />
                    &nbsp;
                </div>
            </div>
            <div class="row form-group noanonimo">
                <div class="col-md-12 text-left">
                    <label class="desc control-label text-left form-control" for="txtnombre2">
                        <h4>Autoriza ser Notificado por correo electrónico </h4>
                    </label>
                </div>
                <div class="col-md-6">
                    <div class="radio-inline-2">
                        <input type="radio" name="notifica" id="notificaSi" checked="checked" value="1" tabindex="<?php echo($cTab++); ?>" />
                        <label class="radio-inline-2"> Si </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="radio-inline-2">
                        <input type="radio" name="notifica" id="notificaNo" value="2" tabindex="<?php echo($cTab++); ?>" />
                        <label class="radio-inline-2"> No </label>
                    </div>
                </div>
            </div>

            <div class="row form-group noanonimo">
                <div class="col-sm-12  col-md-12">
                    <h4>Aviso de privacidad y autorización para el tratamiento de datos personales
                    </h4>
                </div>
            </div>
            <div class="row form-group noanonimo">
                <div class="col-sm-12  col-md-12">
                    <p align="justify">
                        Dando cumplimiento a lo dispuesto en
                        la
                        ley
                        1581
                        de
                        2012,
                        "Por el cual se dictan disposiciones generales para la
                        protección de
                        datos
                        personales" y de conformidad con lo señalado en el decreto
                        1377
                        del
                        2013,
                        con el
                        diligenciamiento de este formulario manifiesto que he sido
                        informado
                        por
                        la
                        Comisión de Regulación de Agua Potable y Saneamiento Básico
                        CRA
                        de
                        lo
                        mencionado
                        en el siguiente enlace: <a href="https://cra.gov.co/seccion/ley-de-proteccion-de-datos.html">Ley
                            de
                            Proteccion de Datos</a>
                    </p>
                </div>
                <div class="col-sm-12  col-md-6">
                    <input type="radio" name="leypdd" id="SiLeypdd" checked="checked" value="1" tabindex="<?php echo($cTab++); ?>" />
                    <label class="radio-inline-2">Si</label>
                </div>
                <div class="col-sm-12  col-md-6">
                    <input type="radio" name="leypdd" id="NoLeypdd" value="2" tabindex="<?php echo($cTab++); ?>" />
                    <label class="radio-inline-2"> No</label>
                </div>
            </div>


            <div align="right" id="charNum"></div>
            <div class="row form-group">
                <div class="col-sm-12  col-md-12 text-center" tabindex="<?php echo($cTab++); ?>" >
                    <div id="li_upload">
                        <div id="filelimit-fine-uploader"></div>
                        <div id="availabeForUpload"></div>
                        &nbsp;
                    </div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-4">
                </div>
                <div class="col-md-2">
                    <input id="saveForm" type="submit" value="Enviar" class="btn btn-primary"
                        onclick="valida_form();" tabindex="<?php echo($cTab++); ?>" />
                </div>
                <div class="col-md-2">
                    <input name="button" type="button" id="button" onclick="window.close();" value="Cancelar"
                        class="btn btn-primary" tabindex="<?php echo($cTab++); ?>" />
                </div>
                <div class="col-md-4">
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
                    <div class="card text-center container-index">
                        <div class="card-body">
                            <div class="card-text">
                                <p class="texto-ayuda-consulta">Puedes hacer seguimiento a la respuesta de la PQRSD
                                    ingresando
                                    el código o
                                    radicado en el siguiente link:</p>
                            </div>
                            <div class="card-text">
                                <a class="btn btn-primary button-index" href="../consultaWeb/" role="button"
                                    data-toggle="ir a hacer seguimiento de la petición" tabindex="<?php echo($cTab++); ?>" >
                                    Hacer Seguimiento
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
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
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
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

    <script>
        window.onload = function() {
            var firstInput = document.querySelector('[tabindex="1"]');
            if (firstInput) {
                firstInput.focus();
            }
        };
    </script>

</body>

</html>