<?php
#echo "hello"; exit;
/**
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright
*
*SIIM2 Models are the data definition of SIIM2 Information System
 * Copyright (C) 2013 Infometrika Ltda.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

session_start();

$ruta_raiz = ".";
if (!$_SESSION['dependencia'])
    header("Location: $ruta_raiz/cerrar_session.php");

foreach ($_GET as $key => $valor)
    ${$key} = $valor;
foreach ($_POST as $key => $valor)
    ${$key} = $valor;

define('ADODB_ASSOC_CASE', 1);

$krd          = $_SESSION["krd"];
$dependencia  = $_SESSION["dependencia"];
$usua_doc     = $_SESSION["usua_doc"];
$codusuario   = $_SESSION["codusuario"];
$tip3Nombre   = $_SESSION["tip3Nombre"];
$tip3desc     = $_SESSION["tip3desc"];
$tip3img      = $_SESSION["tip3img"];
$verrad       = "";
$ruta_raiz    = "..";

$verrad = "";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler($ruta_raiz);

if (!$tipo_archivo)
    $tipo_archivo = 0; //Para la consulta a archivados

//===============================
// prestamo begin
//===============================
// Inicializa, oculta o presenta los parametros de busqueda dependiendo de la opcion del menu de prestamos seleccionada

// prestamo CustomIncludes begin
include("common.php");
// Save Page and File Name available into variables
$sFileName  = "prestamo.php";
// Variables de control
$opcionMenu = strip($_POST["opcionMenu"]); //opción seleccionada del menu
$pageAnt    = strip($_POST["sFileName"]);

?>
<html>
<head>
    <title>Sistema de informaci&oacute;n <?= $entidad_largo ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= $ruta_raiz ?>/img/favicon.png">
    <!-- Bootstrap core CSS -->
    <?php include_once "../htmlheader.inc.php"; ?>
    <!--Necesario para hacer visible el calendario -->
    <script src="<?= $ruta_raiz ?>/js/popcalendar.js"></script>
    <div id="spiffycalendar" class="text"></div>
    <link rel="stylesheet" type="text/css" href="<?= $ruta_raiz ?>/js/spiffyCal/spiffyCal_v2_1.css">
</head>
<body class="PageBODY">
<div align="center">
    <table>
        <tr>
            <td valign="top"><?php Search_Show(); ?></td>
        </tr>
    </table>

    <?php if (isset($_POST['genearreporte'])){ ?>
        <table>
            <tr>
                <td valign="top">
                 <?php  Pedidos_Show(); ?>
                </td>
            </tr>
        </table>
    <?php } ?>
</div>
</body>
</html>
<?php
//===============================
// prestamo end
//===============================


//===============================
// Search_Show begin
//===============================
function Search_Show() {
    // De sesion

    global $db;
    global $ruta_raiz;
    $sFileName = $_POST["sFileName"];
    $opcionMenu = $_POST["opcionMenu"];
    // Valores
    $fechaFinal = $_POST["fechaFinal"];
    $fechaInicial = $_POST["fechaInicial"];
    foreach ($_GET as $key => $valor)
        ${$key} = $valor;
    foreach ($_POST as $key => $valor)
        ${$key} = $valor;
    $krd = $_SESSION["krd"];
    $dependencia = $_SESSION["dependencia"];
    $usua_doc = $_SESSION["usua_doc"];
    // Inicializaci�n de la fecha a partir de la cual se cancelan las solicitudes
    if ($fechaInicial == "") {
        $hastaXDias = strtotime("-30 day");
        $fechaInicial = date("Y-m-d", $hastaXDias);
    }
    if ($fechaFinal == "") {
        if ($opcionMenu == 3) {
            $query = "select PARAM_VALOR,PARAM_NOMB from SGD_PARAMETRO where PARAM_NOMB='PRESTAMO_DIAS_CANC'";
            $rs = $db->conn->query($query);
            if (!$rs->EOF) {
                $x = $rs->fields("PARAM_VALOR"); // d�as por defecto
                $haceXDias = strtotime("-" . $x . " day");
                $fechaFinal = date("Y-m-d", $haceXDias);
            }
            if ($pageAnt != $sFileName) { // inicializaci�n del tiempo
                $v_hora_limite = date("h");
                $v_minuto_limite = date("i");
                $v_meridiano = date("A");
            }
        } else {
            $fechaFinal = date("Y-m-d");
        }
    }
    // Set variables with search parameters
    $flds_PRES_ESTADO = strip($_POST["s_PRES_ESTADO"]);
    $flds_RADI_NUME_RADI = strip($_POST["s_RADI_NUME_RADI"]);
    $flds_numeroExpediente = strip($_POST["s_numeroExpediente"]);
    $flds_USUA_LOGIN = strip($_POST["s_USUA_LOGIN"]);
    if ($opcionMenu == 4) {
        $flds_USUA_LOGIN = $krd;
    } // Inicializa el usuario para el caso en que el ingresa por la opci�n de SOLICITADOS
    $flds_DEPE_NOMB = strip($_POST["s_DEPE_NOMB"]);
    $flds_USUA_NOMB = strip($_POST["s_USUA_NOMB"]);
    $flds_PRES_REQUERIMIENTO = strip($_POST["s_PRES_REQUERIMIENTO"]);
    if ($v_hora_limite == "") {
        $v_hora_limite = strip($_POST["s_hora_limite"]);
    }
    if ($v_minuto_limite == "") {
        $v_minuto_limite = strip($_POST["s_minuto_limite"]);
    }
    if ($v_meridiano == "") {
        $v_meridiano = strip($_POST["s_meridiano"]);
    }
    // Inicializa el titulo y la visibilidad de los criterios de b�squeda
    include_once "inicializarForm.inc";
    // Form display
    ?>
    <form method="post" action="prestamo.php" name="busqueda" class="smart-form">
        <!-- de sesion !-->
        <input type="hidden" value=" " name="radicado">
        <input type="hidden" value="" name="s_sql">
        <!-- control de visualizacion !-->
        <input type="hidden" name="opcionMenu" value="<?= $opcionMenu ?>">
        <input type="hidden" name="sFileName" value="">
        <!-- orden de presentaci�n del resultado !-->
        <input type="hidden" name="FormPedidos_Sorting" value="1">
        <input type="hidden" name="FormPedidos_Sorted" value="0">
        <input type="hidden" name="s_Direction" value=" DESC ">
        <!-- control de paginacion !-->
        <input type="hidden" name="FormPedidos_Page" value="1">
        <input type="hidden" name="FormStarPage" value="1">
        <input type="hidden" name="FormSiguiente" value="0">
        <script>
            //Inicializa el formulario
            function limpiar() {
                document.busqueda.action = "menu_prestamo.php";
                document.busqueda.submit();
            }
            //Presenta los usuarios segun la dependencia seleccionada
            var codUsuaSel = "<?=$flds_USUA_NOMB?>";
        </script>
        <!--Calendario-->
        <script language="JavaScript" src="<?= $ruta_raiz ?>/js/spiffyCal/spiffyCal_v2_1.js"></script>
        <script language="javascript">
            $(document).ready(function() {
                setRutaRaiz('<?=$ruta_raiz?>');
                //Datepicker muestra fecha
                $('#fechaInicial').datepicker({
                    dateFormat : 'yy-mm-dd',
                    onSelect : function(selectedDate) {
                        $('#date').datepicker('option', 'maxDate', selectedDate);
                    }
                });
                $('#fechaFinal').datepicker({
                    dateFormat : 'yy-mm-dd',
                    onSelect : function(selectedDate) {
                        $('#date').datepicker('option', 'maxDate', selectedDate);
                    }
                });
            });
        </script>

        <table class='table table-bordered'>
            <!--<tr>
                <td  colspan="2"><a name="Search"><? /*=$sFormTitle[$opcionMenu]; */ ?> </a></td>
            </tr>-->
            <?php /* */ ?>
            <tr id="b0" style="display:<?= $tipoBusqueda[$opcionMenu][0]; ?>">
                <td><p align="left">Radicado</p></td>
                <td><label class="input"><input type="text" name="s_RADI_NUME_RADI" maxlength="15"
                                                value="<?= $flds_RADI_NUME_RADI; ?>" size="25"></label></td>
            </tr>

            <tr id="b0" style="display:<?= $tipoBusqueda[$opcionMenu][0]; ?>">
                <td><p align="left">Expediente</p></td>
                <td><label class="input"><input type="text" name="s_numeroExpediente" maxlength="22"
                                                value="<?= $flds_numeroExpediente; ?>" size="25"></label></td>
            </tr>

            <tr id="b1" style="display:<?= $tipoBusqueda[$opcionMenu][1]; ?>">
                <td><p align="left">Login de Usuario</p></td>
                <td><label class="input"><input type="text" name="s_USUA_LOGIN" maxlength="15"
                                                value="<?= $flds_USUA_LOGIN; ?>" size="25"></label></td>
            </tr>

            <tr id="b2" style="display:<?= $tipoBusqueda[$opcionMenu][2]; ?>">
                <td><p align="left">Dependencia</p></td>
                <td><label class="select"><select name="s_DEPE_NOMB" class="select"
                                                  onChange=" document.busqueda.s_sql.value='no'; document.busqueda.submit(); ">
                            <option value="">- TODAS LAS DEPENDENCIAS -</option>
                            <?
                            $lookup_s = db_fill_array("select DEPE_CODI,DEPE_NOMB from DEPENDENCIA order by 2");
                            if (is_array($lookup_s)) {
                                reset($lookup_s);
                                while (list($key, $value) = each($lookup_s)) {
                                    if ($key == $flds_DEPE_NOMB) {
                                        $option = "SELECTED";
                                    } else {
                                        $option = "";
                                    }
                                    echo "<option $option value=\"$key\">" . strtoupper($value) . "</option>";
                                }
                            } ?>
                        </select></td>
                </label></tr>
            <tr id="b3" style="display:<?= $tipoBusqueda[$opcionMenu][3]; ?>">
                <td><p align="left">Usuario</p></td>
                <td><label class="select"><select name="s_USUA_NOMB" class=select>
                            <option value="">- TODOS LOS USUARIOS -</option>
                            <?                  $validUsuaActiv = "";
                            // Compatibilidad con PostgreSQL 8.3
                            // Cambio USUA_ESTA=1 por USUA_ESTA='1' para listar los usuarios activos.
                            if ($opcionMenu == 1) {
                                $validUsuaActiv = " USUA_ESTA='1' ";
                            } ELSE {
                                $validUsuaActiv = " USUA_LOGIN IS NOT NULL ";
                            } //Verifica que el usuario se encuentre activo para hacer el prEstamo
                            if ($flds_DEPE_NOMB != "")
                                $tmp = " AND DEPE_CODI= " . $flds_DEPE_NOMB; else $tmp = "";
                            $lookup_s = db_fill_array("select USUA_LOGIN,USUA_NOMB from USUARIO where  " . $validUsuaActiv . $tmp);

                            if (is_array($lookup_s)) {
                                reset($lookup_s);
                                while (list($key, $value) = each($lookup_s)) {
                                    if ($key == $flds_USUA_NOMB) {
                                        $option = "SELECTED";
                                    } else {
                                        $option = "";
                                    }
                                    echo "<option $option value=\"$key\">" . strtoupper($value) . "</option>";
                                }
                            } ?>
                        </select></label></td>
            </tr>
            <?php /**/ ?>
            <tr id="b4" style="display:<?= $tipoBusqueda[$opcionMenu][4]; ?>">
                <td><p align="left">Requerimiento</p></td>
                <td><label class=select><select name="s_PRES_REQUERIMIENTO" class=select>
                            <option value="">- TODOS LOS TIPOS -</option>
                            <?                               $lookup_s = db_fill_array("select PARAM_CODI,PARAM_VALOR from SGD_PARAMETRO where PARAM_NOMB='PRESTAMO_REQUERIMIENTO' order by PARAM_VALOR desc");
                            if (is_array($lookup_s)) {
                                reset($lookup_s);
                                while (list($key, $value) = each($lookup_s)) {
                                    if ($key == $flds_PRES_REQUERIMIENTO)
                                        $option = "<option SELECTED value=\"$key\">" . strtoupper($value) . "</option>";
                                    else
                                        $option = "<option value=\"$key\">" . strtoupper($value) . "</option>";
                                    echo $option;
                                }
                            }    ?>
                        </select></label></td>
            </tr>
            <tr id="b5" style="display:<?= $tipoBusqueda[$opcionMenu][5]; ?>">
                <td><p align="left">Estado</p></td>
                <td><label class=select><select name="s_PRES_ESTADO" class=select>
                            <option value="">- TODOS LOS ESTADOS -</option>
                            <?                               $lookup_s = db_fill_array("select PARAM_CODI,PARAM_VALOR from SGD_PARAMETRO where PARAM_NOMB='PRESTAMO_ESTADO' order by PARAM_VALOR");
                            if (is_array($lookup_s)) {
                                reset($lookup_s);
                                while (list($key, $value) = each($lookup_s)) {
                                    if ($key == $flds_PRES_ESTADO) {
                                        $option = "SELECTED";
                                    } else {
                                        $option = "";
                                    }
                                    echo "<option $option value=\"$key\">" . strtoupper($value) . "</option>";
                                }
                            }
                            if ($flds_PRES_ESTADO == -1) {
                                $option = "SELECTED";
                            } else {
                                $option = "";
                            }
                            echo "<option $option value=\"-1\">VENCIDO</option>"; ?>
                        </select></label></td>
            </tr>
            <tr id="b6" style="display:<?= $tipoBusqueda[$opcionMenu][6]; ?>">
                <td><p align="left">Fecha inicial<br>&nbsp;&nbsp;(aaaa-mm-dd)</p></td>
                <td>
                    <label class="input"> <i class="icon-append fa fa-calendar"></i>
                        <input type="text" id="fechaInicial" name="fechaInicial" placeholder="Fecah inicial"
                               value="<?=$fechaInicial?>">
                    </label>
                </td>
            </tr>
            <tr id="b7" style="display:<?= $tipoBusqueda[$opcionMenu][7]; ?>">
                <td><p align="left">Fecha final<br>&nbsp;&nbsp;(aaaa-mm-dd)</p></td>
                <td>
                    <label class="input"> <i class="icon-append fa fa-calendar"></i>
                        <input type="text" id="fechaFinal" name="fechaFinal" placeholder="fecha Final"
                               value="<?=$fechaFinal?>">
                    </label>
                </td>
            </tr>
            <tr id="b8" style="display:<?= $tipoBusqueda[$opcionMenu][8]; ?>">
                <td><p align="left">Hora l&iacute;mite<br>&nbsp;&nbsp;(hh:mm m)</p></td>
                <td><select name="s_hora_limite" class=select>
                        <?                               for ($i = 1; $i <= 12; $i++) {
                            if ($i <= 9) {
                                $h = "0" . $i;
                            } else {
                                $h = "" . $i;
                            }
                            $seleccion = "";
                            if ($h == $v_hora_limite) {
                                $seleccion = "SELECTED";
                            }     ?>
                            <option <?= $seleccion; ?> value="<?= $h; ?>"><?= $h; ?></option>
                        <? } ?>
                    </select>&nbsp;:&nbsp;
                    <select name="s_minuto_limite" class=select>
                        <?  for ($i = 0; $i <= 59; $i++) {
                            if ($i <= 9) {
                                $h = "0" . $i;
                            } else {
                                $h = "" . $i;
                            }
                            $seleccion = "";
                            if ($h == $v_minuto_limite) {
                                $seleccion = "SELECTED";
                            }     ?>
                            <option <?= $seleccion; ?> value="<?= $h; ?>"> <?= $h; ?></option>
                        <? } ?>
                    </select>&nbsp;:&nbsp;
                    <select name="s_meridiano" class=select>
                        <? if ($v_meridiano == "AM") { ?>
                            <option value="AM" selected>am</option>
                            <option value="PM">pm</option>
                        <?
                        } else {
                            ?>
                            <option value="AM">am</option>
                            <option value="PM" selected>pm</option>
                        <? } ?>
                    </select>
            </tr>
            <tr>
                <td colspan="2">
                    <? if ($opcionMenu == 0 || $opcionMenu == 4) { ?>
                        <footer>
                            <input type="reset" class='btn btn-default' value="Limpiar"
                                   onClick="javascript: limpiar();">
                            <input type="submit" class="btn btn-primary" name="genearreporte" value="Generar">
                        </footer>
                    <?
                    } else {
                        ?>
                     <footer> <input type="hidden" class="btn btn-primary" name="genearreporte" value="noGenerar">
                        <input type="submit" class="btn btn-primary" name="buscarPres" value="Buscar"></footer>
                    <?
                    }?>

                </td>
            </tr>
        </table>
    </form>

<?
} //end function
//===============================
// Search_Show end
//===============================


//===============================
// Pedidos_Show begin
//===============================
function Pedidos_Show() {
    // De sesion
    global $db;
    global $ruta_raiz;
    // Control de visualizaci�n
    global $sFileName;
    global $opcionMenu;
    global $pageAnt; // Pagina de la cual viene
    // Valores

    $sFileName = $_POST["sFileName"];
    $opcionMenu = empty($_POST["opcionMenu"]) ? $_GET["opcionMenu"] : $_POST["opcionMenu"];
    // Valores
    $fechaFinal = $_POST["fechaFinal"];
    $fechaInicial = $_POST["fechaInicial"];

    $krd = $_SESSION["krd"];
    $dependencia = $_SESSION["dependencia"];
    $usua_doc = $_SESSION["usua_doc"];
    $ps_PRES_ESTADO = strip($_POST["s_PRES_ESTADO"]);
    $ps_RADI_NUME_RADI = strip(trim($_POST["s_RADI_NUME_RADI"]));
    $ps_numeroExpediente = strip(trim($_POST["s_numeroExpediente"]));
    $ps_USUA_LOGIN = strip($_POST['s_USUA_LOGIN']);;
    $ps_DEPE_NOMB = strip($_POST["s_DEPE_NOMB"]);
    $ps_USUA_NOMB = strip($_POST["s_USUA_NOMB"]);
    $ps_hora_limite = strip($_POST["s_hora_limite"]);
    $ps_minuto_limite = strip($_POST["s_minuto_limite"]);
    $ps_meridiano = strip($_POST["s_meridiano"]);
    $ps_PRES_REQUERIMIENTO = strip($_POST["s_PRES_REQUERIMIENTO"]);
    if (strlen($pageAnt) == 0) {
        include_once $ruta_raiz . "/include/query/prestamo/builtSQL1.inc";
        include_once $ruta_raiz . "/include/query/prestamo/builtSQL2.inc";
        include_once $ruta_raiz . "/include/query/prestamo/builtSQL3.inc";
        $iSort = strip(get_param("FormPedidos_Sorting"));
        if (!$iSort)
            $iSort = 20;
        $iSorted = strip(get_param("FormPedidos_Sorted"));
        $sDirection = strip(get_param("s_Direction"));
        if ($iSorted != $iSort) {
            $sDirection = " DESC ";
        } else {
            if (strcasecmp($sDirection, " DESC ") == 0) {
                $sDirection = " ASC ";
            } else {
                $sDirection = " DESC ";
            }
        }
        $sOrder = " order by " . $iSort . $sDirection . ",PRESTAMO_ID limit 1000";
        include_once "inicializarRTA.inc";
        #echo "<pre>".$sSQL."</pre>"; exit;
        $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
        $rs = $db->query($sSQL . $sOrder);
        $db->conn->SetFetchMode(ADODB_FETCH_NUM);
        // Process empty recordset
        if (!$rs || $rs->EOF) {
            ?>
            <p align="center" class="titulosError2">NO HAY REGISTROS SELECCIONADOS</p>
            <?          return;
        }
        // Build parameters for order
    /*  */ $form_params_search = "s_RADI_NUME_RADI=" . tourl($ps_RADI_NUME_RADI) . "&s_USUA_LOGIN=" . tourl($ps_USUA_LOGIN) .
            "&s_DEPE_NOMB=" . tourl($ps_DEPE_NOMB) . "&s_USUA_NOMB=" . tourl($ps_USUA_NOMB) . "&s_PRES_REQUERIMIENTO=" .
            tourl($ps_PRES_REQUERIMIENTO) . "&s_PRES_ESTADO=" . tourl($ps_PRES_ESTADO) . "&fechaInicial=" .
            tourl($fechaInicial) . "&fechaFinal=" . tourl($fechaFinal) . "&s_hora_limite=" . tourl($ps_hora_limite) .
            "&s_minuto_limite=" . tourl($ps_minuto_limite) . "&s_meridiano=" . tourl($ps_meridiano); 

 
/**/
        $form_params_page = "&FormPedidos_Page=1&FormStarPage=1&FormSiguiente=0";
        $form_params = $form_params_search . $form_params_page . "&opcionMenu=" . tourl($opcionMenu) . "&krd=" . tourl($krd) .
            "&FormPedidos_Sorted=" . tourl($iSort) . "&s_Direction=" . tourl($sDirection) . "&FormPedidos_Sorting=";

        // HTML column prestamo headers
        ?>
        <form method="post" action="prestamo.php" name="rta">
            <input type="hidden" value='<?= $krd ?>' name="krd">
            <input type="hidden" value=" " name="radicado">
            <input type="hidden" value="" name="prestado">
            <input type="hidden" value="<?= $ps_numeroExpediente ?>" name="ps_numeroExpediente">
            <input type="hidden" name="opcionMenu" value="<?= $opcionMenu ?>">
            <!-- orden de presentacion del resultado en el formulario de envio !-->
            <input type="hidden" name="FormPedidos_Sorting" value="<?= $iSort ?>">
            <input type="hidden" name="FormPedidos_Sorted" value="<?= $iSorted ?>">
            <input type="hidden" name="s_Direction" value="<?= $sDirection ?>">
            <table class='table table-bordered' width="100%">
                <tr>
                    <td colspan="<?= $numCol ?>"><a name="Search"><?= $tituloRespuesta[$opcionMenu] ?></a></td>
                </tr>
                <?PHP // Titulos de las columnas
                include_once "inicializarTabla.inc";

                //----------------------
                // Process page scroller
                //----------------------
                // Initialize records per page
                $iRecordsPerPage = 15;
                // Inicializa el valor de la pagina actual
                $iPage = intval(get_param("FormPedidos_Page"));
                // Inicializa los registros a presentar seg�n la p�gina actual
                $iCounter = 0;
                $ant = "";
                if ($iPage > 1) {
                    do {
                        $new = $rs->fields["PRESTAMO_ID"];
                        if ($new != $ant) {
                            $iCounter++;
                            $ant = $new;
                        }
                        $rs->MoveNext();
                    } while ($iCounter < ($iPage - 1) * $iRecordsPerPage && !$rs->EOF);
                }
                $iCounterIni = $iCounter;
                // Display grid based on recordset
                $y = 1; // Cantidad de registros presentados
                include_once "getRtaSQLAntIn.inc"; //Une en un solo campo los expedientes
                while ($rs && !$rs->EOF) {
                    // Inicializa las variables con los resultados
                    include "getRtaSQL.inc";

                    // Fila de la tabla con los resultados
                    include "getRtaSQLAnt.inc";

                  /* */ if ($fldARCH != 'SI') {
                        $encabARCH = session_name() . "=" . session_id() . "&buscar_exp=" . tourl($fldEXP) . "&krd=$krd&tipo_archivo=&nomcarpeta=";
                        $antfldARCH .= "<a href='" . $ruta_raiz . "/expediente/datos_expediente.php?" . $encabARCH . "&num_expediente=" . tourl($fldEXP) . "&nurad=" . tourl($antfldRADICADO) . "' class='vinculos'>" . $fldARCH . "</a>";
                    }

                    $y++;
                    include "cuerpoTabla.inc";
                    $rs->MoveNext();
                }

                // Fila de la tabla con lso resultados
                $cantRegPorPagina = $y;
                $iCounter = $iCounter + $y;
                ?>
                <script>
                    // Inicializa el arreglo con los radicados a procesar
                    var cantRegPorPagina =<?=$cantRegPorPagina-1?>;
                    // Marca todas las casillas si la del titulo es marcada
                    function seleccionarRta() {
                        for (i = 1; i < document.rta.elements.length; i++) {
                            if (document.rta.elements[i].type == "checkbox")
                                if (document.rta.rta_.checked == 0) {
                                    document.rta.elements[i].checked = 1;
                                    document.rta.rta_.checked = 1;
                                } else {
                                    document.rta.elements[i].checked = 0;
                                    document.rta.rta_.checked = 0;
                                }
                        }
                        valor = document.rta.rta_.checked;
                        <?       for ($j=0; $j<$cantRegPorPagina; $j++) { ?>
                        document.rta.rta_<?=$j?>.checked = valor;
                        <?       } ?>
                    }

                    // Valida y envia el formulario
                    function enviar() {
                        var cant = 0;
                        var kk = 1;

                        for (i = 1; i < document.rta.elements.length; i++) {
                            if (document.rta.elements[i].type == "checkbox") {
                                if (eval(document.rta.elements[i].checked) == true) {
                                    cant = 1;
                                }
                                kk++;
                            }
                        } 
                        if (cant == 0) {
                            alert("Debe seleccionar al menos un radicado");
                        } else {
                            document.rta.prestado.value = cantRegPorPagina;
                            //alert (document.getElementById("use_paswor_dmd5").html);
                            document.rta.action = "formEnvio.php";
                            document.rta.submit();
                        }
                    }

                    // Regresa al menu de prestamos
                    function regresar() {
                        document.rta.opcionMenu.value = "";
                        document.rta.action = "menu_prestamo.php";
                        document.rta.submit();
                    }
                </script>
                <?
                // Build parameters for page
                if (strcasecmp($sDirection, " DESC ") == 0) {
                    $sDirectionPages = " ASC ";
                } else {
                    $sDirectionPages = " DESC ";
                }
                $form_params_page = $form_params_search . "&opcionMenu=" . tourl($opcionMenu) . "&FormPedidos_Sorted=" . tourl($iSort) .
                    "&s_Direction=" . tourl($sDirectionPages) . "&krd=" . tourl($krd) . "&FormPedidos_Sorting=" . tourl($iSort);
                // Numero total de registros
                $ant = $antfldPRESTAMO_ID;
                while ($rs && !$rs->EOF) {
                    $new = $rs->fields["PRESTAMO_ID"]; //para el manejo de expedientes
                    if ($new != $ant) {
                        $ant = $new;
                        $iCounter++;
                    }
                    $rs->MoveNext();
                }
                $iCounter--;
                // Inicializa p�ginas visualizables
                $iNumberOfPages = 10;
                // Inicializa cantidad de p�ginas
                $iHasPages = intval($iCounter / $iRecordsPerPage);
                if ($iCounter % $iRecordsPerPage != 0) {
                    $iHasPages++;
                }
                // Determina la p�gina inicial del intervalo
                $iStartPages = 1;
                $FormSiguiente = get_param("FormSiguiente"); //Indica si (1) el n�mero de p�ginas es mayor al visualizable
                if ($FormSiguiente == 0) {
                    $iStartPages = get_param("FormStarPage");
                } elseif ($FormSiguiente == -1) {
                    $iStartPages = $iPage;
                } else {
                    if ($iPage > $iNumberOfPages) {
                        $iStartPages = $iPage - $iNumberOfPages + 1;
                    }
                }
                // Genera las paginas visualizables
                $sPages = "";
                if ($iHasPages > $iNumberOfPages) {
                    if ($iStartPages == 1) {
                        $sPages .= "|<  <<   ";
                    } else {
                        $sPages .= "<a href=\"$sFileName?$form_params_page&FormPedidos_Page=1&FormStarPage=1&FormSiguiente=0&\">
                                <font class=\"ColumnFONT\" title=\"Ver la primera p&aacute;gina\">|<</font></a>&nbsp;";
                        $sPages .= "&nbsp;<a href=\"$sFileName?$form_params_page&FormPedidos_Page=" . tourl($iStartPages - 1) . "&FormStarPage=" .
                            tourl($iStartPages - 1) . "&FormSiguiente=-1&\"><font class=\"ColumnFONT\" title=\"Ver la p&aacute;gina " .
                            ($iStartPages - 1) . "\"><<</font></a>&nbsp;&nbsp;&nbsp;";
                    }
                }
                for ($iPageCount = $iStartPages; $iPageCount < ($iStartPages + $iNumberOfPages); $iPageCount++) {
                    if ($iPageCount <= $iHasPages) {
                        $sPages .= "<a href=\"$sFileName?$form_params_page&FormPedidos_Page=$iPageCount&FormStarPage=" . tourl($iStartPages) . "&FormSiguiente=0&\">
                                <font class=\"ColumnFONT\" title=\"Ver la p&aacute;gina " . $iPageCount . "\">" . $iPageCount . "</font></a>&nbsp;";
                    } else {
                        break;
                    }
                }
                if ($iHasPages > $iNumberOfPages) {
                    if ($iPageCount - 1 < $iHasPages) {
                        $sPages .= "...&nbsp;&nbsp;<a href=\"$sFileName?$form_params_page&FormPedidos_Page=$iPageCount&FormStarPage=" . tourl($iStartPages) .
                            "&FormSiguiente=1&\"><font class=\"ColumnFONT\" title=\"Ver la p&aacute;gina " . $iPageCount . "\">>></font></a>&nbsp;&nbsp;";
                        $sPages .= "&nbsp;<a href=\"$sFileName?$form_params_page&FormPedidos_Page=$iHasPages&FormStarPage=tourl($iStartPages)
                                         &FormSiguiente=1&\"><font class=\"ColumnFONT\" title=\"Ver la &uacute;ltima p&aacute;gina\">>|</font></a>";
                    } else {
                        $sPages .= " >> |";
                    }
                }
                ?>
                <tr class="titulos5" align="center">
                    <td colspan="<?= ($numCol + 1); ?>">
                        <small>
                            <center><br><?= $sPages ?> (P&aacute;gina <?= $iPage ?>/<?= $iHasPages ?>)</center>
                        </small>
                    </td>
                </tr>
                  <?php  if ($_POST['genearreporte']!="Generar") {  ?>

                    <? if ($opcionMenu == 1) { ?>
                <tr align="center">
                    <td colspan="11" align="center">
                        <input type="button" class="botones" value="Prestar" onclick="enviar();">
                        <input type="button" class="botones" value="Cancelar" title="Regresa al menú de préstamo y control de documentos" onclick="javascript:regresar();"></center>
                    </td>       
                </tr>
            </table>
                     <?php } else { #SI el documento se va a devolver
                if ($opcionMenu == 2) { ?>
                <tr align="center">
                    <td colspan="11" align="center">
                        <input type="button" class="botones" value="Devolver" onclick="enviar();">
                        <input type="button" class="botones" value="Cancelar" title="Regresa al menú de préstamo y control de documentos" onclick="javascript:regresar();"></center>
                    </td>       
                </tr>
                    <?php  } } }  ?>
            <?php  if ($_POST['genearreporte']=="Generar") {  ?>
                <table align="center" class="table table-bordered table-striped"><tr>
                        <td align="center">
                            <?
                            $xsql = serialize ( $sSQL ); // SERIALIZO EL QUERY CON EL QUE SE QUIERE GENERAR EL REPORTE
                            $_SESSION ['xheader'] = "<center><b>$titulo</b></center><br><br>"; // ENCABEZADO DEL REPORTE
                            $_SESSION ['xsql'] = $xsql; // SUBO A SESION EL QUERY// CREO LOS LINKS PARA LOS REPORTES
                            echo "<a href='$ruta_raiz/reportes/adodb-xls.inc.php' target='_blank'><img src='$ruta_raiz/imagenes/spreadsheet.png' width='40' heigth='40' border='0'></a>";
                            ?>
                        </td>
                    </tr>
                </table>
            <?php  }  ?>

        </form>
    <?
    } //fin if
} //fin function
//===============================
// Pedidos_Show end
//===============================
?>
