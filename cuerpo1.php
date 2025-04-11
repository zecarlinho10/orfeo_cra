<?php
/**
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright
 
 SIIM2 Models are the data definition of SIIM2 Information System
 Copyright (C) 2013 Infometrika Ltda.
 
 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Affero General Public License for more details.
 
 You should have received a copy of the GNU Affero General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

session_start();
if ($_POST['radicado_a_buscar']) {
    $radicados_a_buscar = $_POST['radicado_a_buscar'];
}

$ruta_raiz = ".";
if (! $_SESSION['dependencia'])
    header("Location: $ruta_raiz/cerrar_session.php");

foreach ($_GET as $key => $valor)
    ${$key} = $valor;
foreach ($_POST as $key => $valor)
    ${$key} = $valor;

define('ADODB_ASSOC_CASE', 1);
$verrad = "";

$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];
$tip3Nombre = $_SESSION["tip3Nombre"];
$tip3desc = $_SESSION["tip3desc"];
$tip3img = $_SESSION["tip3img"];
$descCarpetasGen = $_SESSION["descCarpetasGen"];
$descCarpetasPer = $_SESSION["descCarpetasPer"];
$verradPermisos = "Full"; // Variable necesaria en tx/txorfeo para mostrar dependencias en transacciones

$entidad = $_SESSION["entidad"];

$_SESSION['numExpedienteSelected'] = null;

include_once ("$ruta_raiz/include/db/ConnectionHandler.php");
include_once ("$ruta_raiz/include/utils/Calendario.php");


if (! $db)
    $db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

$calendario = new Calendar($db);


$sqlFecha = $db->conn->SQLDate("Y-m-d H:i A", "b.RADI_FECH_RADI");
if (strlen($orderNo) == 0) {
    $orderNo = "2";
    $order = 2;
} else {
    $order = $orderNo + 1;
}

if (trim($orderTipo) == "")
    $orderTipo = "DESC";
if ($orden_cambio == 1) {
    if (trim($orderTipo) != "DESC") {
        $orderTipo = "DESC";
    } else {
        $orderTipo = "ASC";
    }
}

if (! $carpeta)
    $carpeta = 9999;
if ($carpeta == 9998)
    $carpeta = 0;
if (! $nomcarpeta)
    $nomcarpeta = "General (Todos los Documentos)";

if (! $tipo_carp)
    $tipo_carp = 0;

/**
 * Este if verifica si se debe buscar en los radicados de todas las carpetas.
 * @$chkCarpeta char Variable que indica si se busca en todas las carpetas.
 */
if ($chkCarpeta) {
    $chkValue = " checked ";
    $whereCarpeta = " ";
} else {
    $chkValue = "";
    if ($carpeta != 9999) {
        $whereCarpeta = " and b.carp_codi=$carpeta ";
        $whereCarpeta = $whereCarpeta . " and b.carp_per=$tipo_carp ";
    }
}

$fecha_hoy = Date("Y-m-d");
$sqlFechaHoy = $db->conn->DBDate($fecha_hoy);

// Filtra el query para documentos agendados
if ($agendado == 1) {
    $sqlAgendado = " and (radi_agend=1 and radi_fech_agend > $sqlFechaHoy) "; // No vencidos
} else 
    if ($agendado == 2) {
        $sqlAgendado = " and (radi_agend=1 and radi_fech_agend <= $sqlFechaHoy)  "; // vencidos
    }

if ($agendado) {
    $colAgendado = "," . $db->conn->SQLDate("Y-m-d H:i A", "b.RADI_FECH_AGEND") . ' as "Fecha Agendado"';
    $whereCarpeta = "";
}

// Filtra teniendo en cienta que se trate de la carpeta Vb.
/*
13/08/2019 CARLOS RICAURTE
ERROR EN VoBo
if ($carpeta == 11 && $codusuario != 1 && $_GET['tipo_carp'] != 1) {
    $whereUsuario = " and  b.radi_usu_ante ='$krd' ";
} else {
    $whereUsuario = " and b.radi_usua_actu='$codusuario' ";
}
*/

$whereUsuario = " and b.radi_usua_actu='$codusuario' ";

$sqlNoRad = "select
                    b.carp_codi as carp, count(1) as COUNT
               from
                    radicado b left outer join SGD_TPR_TPDCUMENTO c on
                    b.tdoc_codi=c.sgd_tpr_codigo left outer join SGD_DIR_DRECCIONES d on
                    b.radi_nume_radi=d.radi_nume_radi
               where
                    b.radi_nume_radi is not null
                    and d.sgd_dir_tipo = 1
                        and b.radi_depe_actu= $dependencia
                    $whereUsuario
                    GROUP BY B.carp_codi";

$rs = $db->conn->Execute($sqlNoRad);

while (! $rs->EOF) {
    $numRad .= empty($numRad) ? $rs->fields["COUNT"] : ", " . $rs->fields["COUNT"];
    $totrad += $rs->fields["COUNT"];
    $rs->MoveNext();
}

$sqlTotalRad = "select count(1) as TOTAL
                  from  radicado b left outer join SGD_TPR_TPDCUMENTO c on
                        b.tdoc_codi=c.sgd_tpr_codigo 
                        left outer join SGD_DIR_DRECCIONES d on b.radi_nume_radi=d.radi_nume_radi
                  where
                        b.radi_nume_radi is not null
                        and d.sgd_dir_tipo = 1";

$rs = $db->conn->Execute($sqlTotalRad);
$numTotal = $rs->fields["TOTAL"];


$codusuario=$_SESSION["codusuario"];
$dependencia = $_SESSION["dependencia"];

$diasParaRestar=0;

//CARLOS RICAURTE 12/2018 DIAS PARA RESTAR DEPENDIENDO EL PERFIL DE USUARIO Y EL TRD
function getDiasParaRestar($codusuario,$dependencia,$trd,$tipo_usu){
    $tiempoArestar = 0;
    
    /********NORMAL**********/
    if($tipo_usu=="Proyectista"){
        //echo '<script language="javascript">alert("'.$trd . "-" . $tipo_usu.'");</script>';
        if($trd==1965){
            $tiempoArestar = 9;
        }
        else if($trd==1746){
            $tiempoArestar = 8;
        }
        //else if($trd==1703 || $trd==1754  || $trd==1941 || $trd==1940 || $trd==1975 || $trd==1753){
        else if($trd==1703 || $trd==1754  || $trd==1941){
            $tiempoArestar = 13;
        }
        else if($trd==1966){
            $tiempoArestar = 5;
        }
        else if($trd==1968){
            $tiempoArestar = 6;
        }
        else if($trd==1987){
            $tiempoArestar = 2;
        }
    }
    /********REVISOR**********/
    else if($tipo_usu=="Revisor"){

        if($trd==1965){
            $tiempoArestar = 7;
        }
        else if($trd==1746){
                $tiempoArestar = 3;
        }
        //else if($trd==1703 || $trd==1754  || $trd==1941 || $trd==1940 || $trd==1975 || $trd==1753){
        else if($trd==1703 || $trd==1754  || $trd==1941){
            $tiempoArestar = 8;
        }
        else if($trd==1966){
            $tiempoArestar = 2;
        }
        else if($trd==1968){
            $tiempoArestar = 1;
        }
    }
    /********JEFE**********/
    else if($tipo_usu=="jefe"){

        if($trd==1965){
            $tiempoArestar = 5;
        }
        //else if($trd==1703 || $trd==1754  || $trd==1941 || $trd==1940 || $trd==1975 || $trd==1753){
        else if($trd==1703 || $trd==1754  || $trd==1941){
            $tiempoArestar = 5;
        }
        else if($trd==1968){
            $tiempoArestar = 4;
        }
    }

    return $tiempoArestar;
}

//CARLOS RICAURTE 1/2019 DETERMINA TIPO DE USUARIO
function getTipoUsuario($radicado,$db1){
    $tipoUsuario="default";
    if($radicado!=""){
        $sql="SELECT * FROM HIST_EVENTOS
          WHERE RADI_NUME_RADI = '$radicado' AND SGD_TTR_CODIGO= 9
          ORDER BY HIST_FECH";

        $rs = $db1->conn->Execute($sql);

        $mensaje="";

        while (! $rs->EOF) {
            $mensaje=$rs->fields["HIST_OBSE"];
            $rs->MoveNext();
        }

        $tipoUsuario="tmp";
        switch ($mensaje) {
            case "Aprobar para firma":
                $tipoUsuario="jefe";
                break;
            
            case "Corregir Proyección":
                $tipoUsuario="Proyectista";
                break;

            case "Corregir Revisión":
                $tipoUsuario="Revisor";
                break;

            case "Proyectar":
                $tipoUsuario="Proyectista";
                break;

            case "Revisar":
                $tipoUsuario="Revisor";
                break;

            default: $tipoUsuario="";
        }
    }
    

    return $tipoUsuario;
}

?>
<html>

<title>Sistema de informaci&oacute;n <?=$entidad_largo?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Bootstrap core CSS -->
  <?php include_once "htmlheader.inc.php"; ?>
</head>

<body>
    <div id=message></div>
    <form name=form1 id=form1 action="./tx/formEnvio.php?<?=$encabezado?>"
        methos=post/  >
        <div id="content" style="opacity: 1;">

            <div class="row">
                <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                    <h1 class="page-title txt-color-blueDark">
                        <i class="glyphicon glyphicon-inbox"></i> Bandeja <span><?=$nomcarpeta?></span>
                    </h1>
                </div>
                <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
                    <ul id="sparks" class="">
                        <li class="sparks-info">
                            <h5>
                                Radicados <span class="txt-color-blue"> <?=$totrad?> </span>
                            </h5>
                            <div
                                class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm">
            <?=$numRad?>
            </div>
                        </li>
                        <li class="sparks-info">
                            <h5>
                                Total Radicados <span class="txt-color-blue"> <?=$numTotal?> </span>
                            </h5>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- widget grid -->
            <section id="widget-grid" class="">

                <!-- row -->
                <div class="row">

                    <!-- NEW WIDGET START -->
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <!-- Widget ID (each widget will need unique ID)-->
                        <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0"
                            data-widget-editbutton="false">

                            <header>
                <?//BOTON PROVISIONAL PARA LA BUSQUEDAS DE RADICADOS ?>
                <div id="Buscar_radicado"
                                    class="btn btn-default txt-color-blueDark">
                                    <strong>Buscar</strong> <i>Radicados</i>
                                </div>
                            </header>


                            <!-- widget div-->
                            <div>
                                <!-- widget content -->
                                <div class="actions smart-form">
                    <?php
                    $controlAgenda = 1;
                    if ($carpeta == 11 and ! $tipo_carp and $codusuario != 1) {} else {
                        ?>
                        <?
                        
include "./tx/txOrfeo.php";
                    }
                    ?>
                </div>
                                <div class="widget-body no-padding container-fluid">
                                    <div class="widget-body-toolbar"></div>
                                    <div class="table-responsive">
                                        <table id="dt_basic" style="width: 100%"
                                            class="table table-bordered table-hover dataTable no-footer smart-form table-reflow">
                                            <thead>

                                                <tr>
                                                    <td
                                                        style="width: 10%; min-width: 15px; white-space: initial">
                                                        <label class="checkbox "> <input type="checkbox"
                                                            onclick="markAll();" value="checkAll" name="checkAll"
                                                            id="checkAll"> <i></i>
                                                    </label>
                                                    </td>

                                                    <td style="width: 22%; white-space: initial">Radicado</td>
                      <?php if ($entidad == 'METROVIVIENDA' or  $entidad == 'CRA' ){ ?><td
                                                        style="">Alerta</td><?php } ?>
                      <td style="white-space: initial">Fecha</td>
                                                    <td style="width: 30%; white-space: initial">Asunto</td>
                                                    <td style="white-space: initial; width: 15%">Remitente /
                                                        Destinatario</td>
                                                    <td style="white-space: initial">Enviado Por</td>
                                                    <td style="">Tipo Documento</td>
                                                    <td style="white-space: initial">Días Hábiles para
                                                        Vencimiento</td>
                                                    <td style="white-space: initial">Fecha de Vencimiento</td>
                                                    <!--<th style="">Ref</th>-->

                                                </tr>
                                            </thead>
                  <?php
                include "$ruta_raiz/include/query/queryCuerpo.php";
                //$db->conn->debug = true;
                //echo $isql; exit;
                $rs = $db->conn->Execute($isql);
                
                include_once "$ruta_raiz/tx/diasHabiles.php";
                $a = new FechaHabil($db);
                $rad_anterior="";
                while (! $rs->EOF) {
                    $numeroRadicado = $rs->fields["HID_RADI_NUME_RADI"];
                    if(strcmp($numeroRadicado, $rad_anterior)<>0){
                        $rad_anterior=$numeroRadicado;
                        $fechaRadicado = $rs->fields["HID_RADI_FECH_RADI"];
                        // $refRadicado = $rs->fields["REFERENCIA"];
                        $asuntoRadicado = $rs->fields["ASUNTO"];
                        $remitenteRadicado = $rs->fields["REMITENTE"];
                        $tipoDocumentoRadicado = $rs->fields["TIPO DOCUMENTO"];
                        //$diasRadicado = $rs->fields["DIAS RESTANTES"];
                        $fech_vcmto = $rs->fields["FECHA_VCMTO"];
                        $enviadoPor = $rs->fields["ENVIADO POR"];
                        $radiPath = $rs->fields["HID_RADI_PATH"];
                        // $radiLeido = $rs->fields["HID_RADI_LEIDO"];
                        $radianulado = $rs->fields["HID_EANU_CODIGO"];
                        
                        // Datos obtenidos para pintar los radicados
                        $anexEstado = $rs->fields["ANEX_ESTADO"];
                        $_radiLeido = $rs->fields["HID_RADI_LEIDO"];
                        $numExpediente = $rs->fields["SGD_EXP_NUMERO"];
                        $diasRadicado = $a->getDiasRestantes($numeroRadicado, $fech_vcmto, $tipoDocumentoRadicado);
                        $tdoc_codi = $rs->fields["TDOC_CODI"];
                        //CARLOS RICAURTE 7/6/2018
                        //UNIFICACION DIAS HABILES
                        $Dha = new FechaHabil($db);
                        $fechaHoy = date("Y-m-d");
                        $tipo_usuario="";
                        if (! empty($fech_vcmto)) {

                            $diasRadicado = $dias = $diasXusuario= $Dha->diasHabiles($fechaHoy,$fech_vcmto);

                            //CARLOS RICAURTE 1/2020 ULTIMA INSTRUCCION PARA IDENTIFICAR USUARIO.
                            $tipo_usuario=getTipoUsuario($numeroRadicado,$db);
                            //CARLOS RICAURTE 1/2020 LLAMADO A FUNCION PARA RESTAR DIAS DEPENDIENDO EL PERFIL Y EL TRD
                            $diasARestar=0;
                            $diasARestar=getDiasParaRestar($codusuario,$dependencia,$tdoc_codi,$tipo_usuario);
                            $diasXusuario-=$diasARestar;
                        } else {
                            $fech_vcmto = $dias = $diasRadicado;
                            $diasXusuario="";
                        }
                        
                        //fin carlos ricaurte
                        unset($TipoAlerta);
                        unset($ColorAlerta);
                        unset($MensajeAlerta);
                        
                        unset($TipoAlerta2);
                        unset($ColorAlerta2);
                        unset($MensajeAlerta2);
                        
                        /**
                         * ***************************** Script que colorea los radicados nuevos, leidos , por vencer y vencidos ******************
                         */
                        switch ($_radiLeido) {
                            case 0:
                                $TipoAlerta = "class='fa fa-circle'";
                                $ColorAlerta = "style='color:#356635;cursor:help'";
                                $MensajeAlerta = "Radicado Nuevo";
                                
                                break;
                            case 1:
                                $TipoAlerta = "class='fa fa-circle'";
                                $ColorAlerta = "style='color:#3276B1;cursor:help'";
                                $MensajeAlerta = "Leido";
                                
                                break;
                        }
                        
                        // Debo calcular los dias del radicado antes
                        

                        if ((string)$diasRadicado != "") {
                            if ((string)$diasRadicado == "-" or (string)$diasRadicado == "N/A ó termino no definido") {
                                // No se pintan.
                            } 
                            else {
                                if ($diasXusuario < 0) {
                                    $TipoAlerta2 = "class='fa fa-circle'";
                                    $ColorAlerta2 = "style='color:#FE2E2E;cursor:help'";
                                    $MensajeAlerta2 = "Vencido";
                                } else if ($diasXusuario >= 0 and $diasXusuario <= 3) {
                                    $TipoAlerta2 = "class='fa fa-circle'";
                                    $ColorAlerta2 = "style='color:#8A2908;cursor:help'";
                                    $MensajeAlerta2 = "Por Vencer";
                                }
                            }
                        }
                        
                        /**
                         * *****************Script que colorea los radicados con anex_estado=4 (envíos)******************
                         */
                        
                        unset($anexEstadoEstilo);
                        unset($anexEstadoEstiloLink);
                        // David
                        $sqlanerad = "select ANEX_ESTADO from ANEXOS t
            where anex_radi_nume=$numeroRadicado";
                        $rsa = $db->conn->Execute($sqlanerad);
                        
                        // /
                        $anexregenv = $rsa->fields["ANEX_ESTADO"];
                        
                        if ($anexregenv == null) {
                            $sqlanerad1 = "SELECT distinct sgd_fenv_codigo FROM sgd_renv_regenvio e
        where radi_nume_sal=$numeroRadicado";
                            $rse = $db->conn->Execute($sqlanerad1);
                            while (! $rse->EOF) {
                                $estatemp = $rse->fields["SGD_FENV_CODIGO"];
                                if ($estatemp > 0) {
                                    $anexEstado = 4;
                                }
                                $rse->MoveNext();
                            }
                        }
                        
                        while (! $rsa->EOF) {
                            $anexEstadtemp = $rsa->fields["ANEX_ESTADO"];
                            // David
                            // echo $anexEstadtemp;
                            
                            if ($anexEstadtemp == 4) {
                                $anexEstado = $anexEstadtemp;
                            }
                            
                            $rsa->MoveNext();
                        }
                        
                        /*if(substr($numeroRadicado, -1)==1){
                            anexEstado= $anexEstadtemp;
                        }*/
                        
                        switch ($anexEstado) {
                            case 3:
                                $TipoAlerta = "class='fa fa-circle'";
                                $ColorAlerta = "style='color:#FF8000;cursor:help'";
                                $MensajeAlerta = "Marcado como Impreso";
                                break;
                            
                            case 4: // (envios)
                                    // @anexEstadoEstilo estilo para el <tr>
                                    // @anexEstadoEstiloLink estilo para enlaces <a>
                                $anexEstadoEstilo = " style='color: #356635'";
                                $anexEstadoEstiloLink = " style='font-weight: bold;color: #356635'";
                                $MensajeAlerta = "Documento Enviado";
                                $ColorAlerta = "style='color:#000000;cursor:help'";
                                break;
                        }
                        
                        if ($linkVerRadicado != '') {
                            // $anexEstado_linkradi = " style='text-decoration: underline'";
                        }
                        
                        /**
                         * **************Mostrar icono (folder) para radicados dentro de Expedientes***************************
                         */
                        
                        unset($radInExpStyle);
                        if (! empty($numExpediente)) {
                            $radInExpStyle = "class='fa fa-folder-open'";
                        }
                        
                        /**
                         * ****************************************************************************************************
                         */
                        
                        $linkVerRadicado = "./verradicado.php?verrad=$numeroRadicado&nomcarpeta=$nomcarpeta#tabs-d";
                        $linkImagen = "$ruta_raiz/bodega/$radiPath";
                        
                        unset($leido);
                        if (! $radiLeido) {
                            $leido = " unread ";
                        }
                        unset($colorAnulado);
                        if ($radianulado == 2) {
                            $colorAnulado = " text-danger ";
                        }
                        ?>
                 <tr <?=$anexEstadoEstilo?> class="<?=$leido?> <?=$colorAnulado?>">
                                                        <td class="inbox-table-icon sorting_1 ">
                                                            <div>

                                                                <label class="checkbox"> <input id="<?=$numeroRadicado?>"
                                                                    name="checkValue[<?=$numeroRadicado?>]"
                                                                    value="CHKANULAR" type="checkbox"> <i></i>
                                                                </label>
                                                            </div>
                                                        </td>

                          <?php
                        $fechasymd = date('ymdhis');
                        if (! empty($radiPath)) {
                            // echo "<td class='inbox-data-from'> <div><small> <a target='_blank' href='$linkImagen'>$numeroRadicado</a></small> </div></td>";
                            echo "<td> <div $radInExpStyle ><small style=\"padding: 5px;\"><a $anexEstado_linkradi  $anexEstadoEstiloLink href='javascript:void(0)' onclick=\"funlinkArchivo('$numeroRadicado','$ruta_raiz');\"> $numeroRadicado</a></small></div></td>";
                        } else {
                            echo "<td> <div $radInExpStyle ><small style=\"padding: 5px;\">" . trim($numeroRadicado) . "</small></div></td>";
                        }
                        ?>

                        <?php if ($entidad == 'METROVIVIENDA'or $entidad == 'CRA' ){ ?>  <td
                                                            align="center"><a <?=$ColorAlerta?>
                                                            title="<?=$MensajeAlerta?>"><div <?=$TipoAlerta?>></div></a>        
                        <?php if ($MensajeAlerta2 != ""){ ?>  <a <?=$ColorAlerta2?>
                                                            title="<?=$MensajeAlerta2?>"><div <?=$TipoAlerta2?>></div></a> <?php } ?></td> <?php } ?>

                          <td>
                                                        <div>
                                                            <small><a <?=$anexEstadoEstiloLink?>
                                                                href="<?=$linkVerRadicado?>" target="mainFrame"><?=$fechaRadicado?></a></small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <span><small><?=$asuntoRadicado?></small></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <small><?=$remitenteRadicado?></small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <small><?=$enviadoPor?></small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <small><?=$tipoDocumentoRadicado?></small>
                                                        </div>
                                                    </td>
                                                    <!--<td > <div> <small><?=$diasRadicado?></small> </div> </td>-->
                                                    <td>
                                                        <div>
                                                            <!--<small><?=$dias . "-" . $diasTipoDocumento . "-" . $diasXusuario ?></small>-->
                                                            <small><?=$tipo_usuario."  ".$diasXusuario?></small>
                                                        </div>
                                                    </td>
                                                    
                                                    <td>
                                                        <div>
                                                            <small><?=$fech_vcmto?></small>
                                                        </div>
                                                    </td>
                                                </tr>
                    <?php
                    //elimina radicados duplicados
                    }
                    $rs->MoveNext();
                }
                ?>
                  </tbody>
                                        </table>
                                    </div>
<?php
$xsql = serialize($isql);
$_SESSION['xsql'] = $xsql;
echo "<a style='border:0px' href='./reportes/adodb-doc.inc.php?" . "' target='_blank'><img src='./imagenes/compfile.png' width='40' heigth='    40' border='0' ></a>";
echo "<a href='./reportes/adodb-xls.inc.php' target='_blank'><img src='./imagenes/spreadsheet.png' width='40' heigth='40' border='0'></a>";
?>
              </div>
                                <!-- end widget content -->

                            </div>
                            <!-- end widget div -->

                        </div>
                        <!-- end widget -->
                    </article>

                </div>
                <!-- end row -->

            </section>
            <!-- end widget grid -->
        </div>
    </form>

    <script type="text/javascript">
    // Muestra las imagenes de los radicados
    function funlinkArchivo(numrad, rutaRaiz){
        var nombreventana = "linkVistArch";
        var url           = rutaRaiz + "/linkArchivo.php?<? echo session_name()."=".session_id()?>"+"&numrad="+numrad;
        var ventana       = window.open(url,nombreventana,'scrollbars=1,height=50,width=250');
        //setTimeout(nombreventana.close, 70);
        return;
    }

    // DO NOT REMOVE : GLOBAL FUNCTIONS!
    pageSetUp();

    // PAGE RELATED SCRIPTS

    loadDataTableScripts();
    function loadDataTableScripts() {

        loadScript("js/plugin/datatables/jquery.dataTables-cust.js", dt_2);

        function dt_2() {
            loadScript("js/plugin/datatables/ColReorder.min.js", dt_3);
        }

        function dt_3() {
            loadScript("js/plugin/datatables/FixedColumns.min.js", dt_4);
        }

        function dt_4() {
            loadScript("js/plugin/datatables/ColVis.min.js", dt_5);
        }

        function dt_5() {
            loadScript("js/plugin/datatables/ZeroClipboard.js", dt_6);
        }

        function dt_6() {
            loadScript("js/plugin/datatables/media/js/TableTools.min.js", dt_7);
        }

        function dt_7() {
            loadScript("js/plugin/datatables/DT_bootstrap.js", runDataTables);
        }

    }

    function runDataTables() {

        /*
         * BASIC
         */
        $('#dt_basic').dataTable({
             "lengthMenu": [[10, 25, 50, -3], [10, 25, 50, "All"]],
             "iDisplayLength" : 25,
             "processing": true,
              "serverSide": true,
        });

        /* END BASIC */

        /* Add the events etc before DataTables hides a column */
        $("#datatable_fixed_column thead input").keyup(function() {
            oTable.fnFilter(this.value, oTable.oApi._fnVisibleToColumnIndex(oTable.fnSettings(), $("thead input").index(this)));
        });

        $("#datatable_fixed_column thead input").each(function(i) {
            this.initVal = this.value;
        });
        $("#datatable_fixed_column thead input").focus(function() {
            if (this.className == "search_init") {
                this.className = "";
                this.value = "";
            }
        });
        $("#datatable_fixed_column thead input").blur(function(i) {
            if (this.value == "") {
                this.className = "search_init";
                this.value = this.initVal;
            }
        });


        var oTable = $('#datatable_fixed_column').dataTable({
            "sDom" : "<'dt-top-row'><'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
            //"sDom" : "t<'row dt-wrapper'<'col-sm-6'i><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'>>",
            "oLanguage" : {
                "sSearch" : "Search all columns:"
            },
            "bSortCellsTop" : true
        });



        /*
         * COL ORDER
         */
        $('#datatable_col_reorder').dataTable({
            "lengthMenu": [[10, 25, 50, -3], [10, 25, 50, "All"]],
            "sDom" : "R<'dt-top-row'Clf>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
            "fnInitComplete" : function(oSettings, json) {
                $('.ColVis_Button').addClass('btn btn-default btn-sm').html('Columns <i class="icon-arrow-down"></i>');
            }
        });

        /* END COL ORDER */

        /* TABLE TOOLS */
        $('#datatable_tabletools').dataTable({
            "sDom" : "<'dt-top-row'Tlf>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
            "oTableTools" : {
                "aButtons" : ["copy", "print", {
                "sExtends" : "collection",
                "sButtonText" : 'Save <span class="caret" />',
                "aButtons" : ["csv", "xls", "pdf"]
                }],
                "sSwfPath" : "js/plugin/datatables/media/swf/copy_csv_xls_pdf.swf"
            },
            "fnInitComplete" : function(oSettings, json) {
                $(this).closest('#dt_table_tools_wrapper').find('.DTTT.btn-group').addClass('table_tools_group').children('a.btn').each(function() {
                    $(this).addClass('btn-sm btn-default');
                });
            }
        });

        // Modal Link
        $('#AccionCaliope').on('change',function(event){
            var optionSelected = $(this).find("option:selected");
            var valueSelected  = optionSelected.val();
            if(valueSelected  == 21 || valueSelected  == 20){
                var text;

                $("input[name^='checkValue']:checked").each(function(index,value){
                    text =  (text === undefined)? $(value).attr('id') : text + ','+$(value).attr('id');
                });

                if(text !== undefined){
                    $('<div>').dialog({
                        modal: true,
                        open: function (){
                            if(valueSelected  == 21){
                                $(this).load('accionesMasivas/masivaAsignarTrd.php?radicados=' + text);
                            }
                            if(valueSelected  == 20){
                                $(this).load('accionesMasivas/masivaIncluirExp.php?radicados=' + text);
                            }
                        },
                        title: 'Acción Masiva',
                        width: "600px"
                    })
                }
            }

        });
        /* END TABLE TOOLS */
    }

//BUSCAR LOS RADICADOS
  $( document ).ready(function() {
    $( "#Buscar_radicado" ).click(function() {
       var value_r = $( "#mi_filtro_personal" ).val();
       $( "#id_radicado_a_buscar" ).val(value_r);
       $( "#form_buscar_radicados" ).submit();
    });
});

</script>
    <form id="form_buscar_radicados" action="cuerpo.php" method="post">
        <input id="id_radicado_a_buscar" type="hidden"
            name="radicado_a_buscar"> <input style="display: none" type="submit"
            value="Recuperar" />
    </form>
</body>
</html>
