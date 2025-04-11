<?php
session_start();
//ini_set("display_errors",1);
date_default_timezone_set('America/Bogota');
$ruta_raiz = "../..";
foreach ($_GET as $key => $valor)
    $$key = $valor;
foreach ($_POST as $key => $valor)
    $$key = $valor;
$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];
$id_rol = $_SESSION["id_rol"];
$id_rol = 5;
$ruta_raiz = "../..";
include('validadte.php');
$script = "operOrfeoCloud.php";
include ('orfeoCloud-class.php');
//include_once "tpDocumento.php";
//$ruta_owonclod='/var/www/html/owncloud/data/';
//include "config-inc.php";
include ($ruta_raiz.'/config.php');
//@$ruta_owonclod se importa directamenete del archivo de configuraciones (config.php)
$cloud = new orfeoCloud($ruta_owonclod, $ruta_raiz);
//$cloud->setUserLogin();
$ownUser = $cloud->dataUser();
if (!$fechaIni)
    $fechaIni = date('d/m/y');
if (!$fechaFin)
    $fechaFin = date('d/m/y');
$optionTp = "";
//colocar permiso
$_SESSION['usua_scan'] =1;
if (!$_SESSION['usua_scan'] == 1) {
    die('Notiene permiso de acceder');
}
/* datos a cconfigurar* */
//$userOwncloud='digitalizador5'; 
$userOwncloud = $cloud->getUserCloud();
$ukrd = $cloud->getUserLogin();
$classR = '';
$classA = '';
if (!$tpAxion)
    $tpAxion = 'ENTRADA';
switch ($tpAxion) {
    case 'RADICADOS':
        $dataDD = 'R';
        $classR = ' class="active"';
        break;
    case 'ENTRADA':
        $dataDD = 'F';
        $classE = ' class="active"';
        break;
    case 'info':
        $dataDD = 'I';
        $classI = ' class="active"';
        break;
    default:
        $dataDD = 'A';
        $classA = ' class="active"';
        //inicializa tipos documentales

        //$tpdoc = new tpDocumento($ruta_raiz);
//consulta
        //$tdocumentales = $tpdoc->consultar();
        $temporal = !empty($tdocumentales) ? count($tdocumentales):0; 
	$numtp = $temporal;

        for ($i = 0; $i < $numtp; $i++) {
            $nomTP = $tdocumentales[$i]["DESCRIP"];
            $codTP = $tdocumentales[$i]["CODIGO"];
            $optionTp.="<option value='$codTP'>$nomTP</option>";
        }

        break;
}

//echo "<hr>llego aka <hr>";
$carpS = $tpAxion;
//$ruta_owonclod='/var/www/html/owncloud/data/';
?>
<!DOCTYPE html >
<html lang="es">
    <head>
	<? include_once "../../htmlheader.inc.php";?>
        <title>Scan OwnCloud Orfeo</title>
        <!--<link rel="stylesheet" type="text/css" 	href="<?= $ruta_raiz ?>/js/calendario/calendar.css">-->
        <!--<link rel="stylesheet" href="<?= $ruta_raiz ?>/estilos/orfeo38/orfeo.css">-->
        <script language="JavaScript" type="text/javascript"	src="<?= $ruta_raiz ?>/include/zpcal/lang/calendar-es.js"></script>
        <script language="JavaScript" src="common.js"></script>
        <!--<link rel="stylesheet" href="<?= $ruta_raiz ?>/estilos/Style.css" type="text/css" media="screen">-->
        <script type="text/javascript">
            function Listar() {
                var usA = document.getElementById('user').value;
                var poststr = "action=Listar&usA=" + usA;
                url = "<? echo $script; ?>";
                partes(url, 'listados', poststr, '');
            }

            function subir(div, name, pages, user, peso, acc) {
		// var usA = document.getElementById('user').value;
                if (acc == 1 || acc == 2) {
                    if (acc == 1) {
                        var txt = 'Se modificara el';
                        var r = prompt(txt + " radicado " + name.slice(0, -4) + "\n  Esta Seguro de hacer esta accion \n Por favor Escriba la Obsevacion", "");
                        if (r == false)
                        return false;
                        if (r.length <= 5){
                            alert('Observacion debe ser de mas 5 caracteres' );
                            return false;
                        }

                    }
                    if (acc == 2) {
                        txt = 'Definitivo del';
                        var r = confirm(txt + " radicado " + name.slice(0, -4) + " Esta Seguro de hacer esta accion");
                        if (r == false)
                            return false;
                    }
                }

                //         return false;
                document.getElementById(div).innerHTML = '<center><img  alt="Procesando" src="<?= $ruta_raiz; ?>/imagenes/loading.gif"></center>';
                var poststr = "action=<?php echo $dataDD; ?>subir&name=" + name + "&pages=" + pages + "&userOwn=" + user + "&pesoA=" + peso+'&r='+r;
                url = "<?php echo $script; ?>";

                partes(url, div, poststr, '');
            }

            function subirf(div, name, pages, user, peso, acc) {
            // var usA = document.getElementById('user').value;

            if (acc == 1 || acc == 2) {
                if (acc == 1) {
                    var txt = 'Se modificara el';
                    var r = prompt(txt + " radicado " + name.slice(0, -4) + "\n  Esta Seguro de hacer esta accion \n Por favor Escriba la Obsevacion", "");
                    //alert( );
                    if (r == false)
                    return false;
                    if (r.length <= 10){
                        alert('Observacion debe ser de mas 10 caracteres' );
                        return false;
                    }

                }
                if (acc == 2) {
                    txt = 'Definitivo del';
                    var r = confirm(txt + " radicado " + name.slice(0, -4) + " Esta Seguro de hacer esta accion");

                    if (r == false)
                    return false;
                }
            }
            //         return false;
            document.getElementById(div).innerHTML = '<center><img  alt="Procesando" src="<?= $ruta_raiz; ?>/imagenes/loading.gif"></center>';
            var poststr = "action=<?php echo $dataDD; ?>subirf&name=" + name + "&pages=" + pages + "&userOwn=" + user + "&pesoA=" + peso+'&r='+r;
            url = "<?php echo $script; ?>";
            partes(url, div, poststr, '');
            }
            function subir2(div, name, pages, user, peso, tpdoc) {
            //document.getElementById(div).innerHTML = '<center><img  alt="Procesando" src="<?= $ruta_raiz; ?>/imagenes/loading.gif"></center>';
            var tpdocS = document.getElementById(tpdoc).value;
            //var comentarioS = document.getElementById(comentario).value;
            if (tpdocS == '-') {
            alert('Debe selecionar un tipo de documento');
            return false;
            }
            /*if (comentarioS == '-') {
            alert('Debe selecionar un Comentario');
            return false;
            }*/
	    var txt = 'El anexo al';
            var r = prompt(txt + " radicado " + name.substring(0, 14) + " necesita una observacion \n Por favor Escriba la Obsevacion", "");
            //alert( );
            //if (r == false)
            //return false;
	    document.getElementById(div).innerHTML = '<center><img  alt="Procesando" src="<?= $ruta_raiz; ?>/imagenes/loading.gif"></center>';
            var poststr = "action=<?php echo $dataDD; ?>subir&name=" + name + "&pages=" + pages + "&userOwn=" + user + "&pesoA=" + peso + "&tpdoc=" + tpdocS + "&comentario=" + r;
            url = "<?php echo $script; ?>";
            partes(url, div, poststr, '');

            }
function funlinkArchivo(numrad, rutaRaiz){
	var nombreventana = "linkVistArch";
	var url           = rutaRaiz + "../linkArchivo.php?<? echo session_name()."=".session_id()?>"+"&numrad="+numrad;
	var ventana       = window.open(url,nombreventana,'scrollbars=1,height=50,width=250');
	//setTimeout(nombreventana.close, 70);
	return;
}
        </script>
</head>
    <body>
        <header >
            <title>Scan OwnCloud Orfeo</title>
        </header>
    <nav1><div id="navigation">
            <ul id="apps" class="">
                <li data-id="files_index"><a style="" href="orfeoCloud.php?tpAxion=ENTRADA" title=""  <?php echo $classE; ?>>Entrada</a>
                </li>
                <!-- <li data-id="files_index"><a style="" href="orfeoCloud.php?tpAxion=RADICADOS" title=""  <?php echo $classR; ?>>Radicados</a>
                </li> -->
                <li data-id="gallery_index"><a style="" href="orfeoCloud.php?tpAxion=ANEXOS" <?php echo $classA; ?> title="">Anexos</a>
                </li>
                <li data-id="calendar_index"><a style="" href="orfeoCloud.php?tpAxion=info" <?php echo $classI; ?> title=""><span>Informe</span></a>
                </li>
                <!-- <li data-id="calendar_index"><a style="" href="orfeoCloud.php" title=""><span>Ajustes</span></a>
                </li> -->
            </ul>
        </div></nav1>

    <div id="content" style="height: 244px; width: 80%;">
                            <!--[if IE 8]><style>input[type="checkbox"]{padding:0;}table td{position:static !important;}</style><![endif]-->
        <div id="notification" style="display: none;"></div>
        <?php
        if ($tpAxion == 'info') {
            ?>
            <table>
                <thead>
                <form action='' method="post" name='Informacion' class='form-smart'>
                    <tr>
                        <th id="headerName" style="width: 200px">

                            <span class="name">Fecha Inicio</span>
                        </th>
                        <th id="headerSize"> <input readonly="true" type="text" class="tex_area" name="fechaIni"	id="fechaIni" size="10" value="<?php echo $fechaIni; ?>" /> 
                            <script	language="javascript">
                                var A_CALTPL = {'imgpath': '<?= $ruta_raiz ?>/js/calendario/img/',
                                'months': ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                                'weekdays': ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'], 'yearscroll': true, 'weekstart': 1, 'centyear': 70}
                                new tcal({'formname': 'Informacion', 'controlname': 'fechaIni'}, A_CALTPL);
                            </script></th>
                        <th id="headerDate" style="width: 100px">
                            <span id="modified">Fecha Final</span></th>
                        <th id="headerDate" style="width: 100px">
                            <input readonly="true" type="text" class="tex_area" name="fechaFin"	id="fechaFin" size="10" value="<?php echo $fechaFin; ?>" /> 
                            <script	language="javascript">
                                new tcal({'formname': 'Informacion', 'controlname': 'fechaFin'}, A_CALTPL);
                            </script>	</th>
                        <th id="headerDate" style="width: 100px">
                            <input type="submit" value="Cosultar"></th>
                    </tr>
                </thead></table>
            <input type="hidden" name='tpAxion' value='info'>

            </form>
            <?
            echo $cloud->ListarRadSinImagenes(205, $fechaIni, $fechaFin);
            } else {
            ?>
            <table class='table table-bordered'>

                <?php
                if ($tpAxion == 'ANEXOS'){
						unset($ownUser);
						$ownUser[]=strtolower($krd);
                    echo $cloud->ListarImagenes($ruta_owonclod, $ownUser, $carpS, strtolower($krd), $optionTp);
					}
                else{
					if ($_SESSION['usua_scan'] == 1)
						unset($ownUser);
						$ownUser[]=strtolower($krd);
      		            echo $cloud->ListarImagenesAvanzada($ruta_owonclod, $ownUser, $carpS, strtolower($krd), $optionTp);
                }
                ?>

            </table>
        <?php } 
//echo $ruta_owonclod;
?>
        <!-- config hints for javascript -->
        <input type="hidden" name="allowZipDownload" id="allowZipDownload" value="1" original-title="">
</div>
</body>
</html>

