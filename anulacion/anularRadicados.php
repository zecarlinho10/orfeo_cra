<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

session_start();

foreach ($_GET as $key => $valor)
    ${$key} = $valor;
foreach ($_POST as $key => $valor)
    ${$key} = $valor;

$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];

$ruta_raiz = "..";
header('Content-Type: text/html; charset=ISO-8859-1');
if (!$fecha_busq)
    $fecha_busq = date("Y-m-d");
if (!$fecha_busq2)
    $fecha_busq2 = date("Y-m-d");

include('../config.php');
include_once "$ruta_raiz/include/tx/Anulacion.php";
include_once "$ruta_raiz/include/tx/Historico.php";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
/////////////////////////////RADICA CON PLANTILLA///////////////////////////////////
$ADODB_COUNTRECS = false;

require_once realpath(dirname(__FILE__) . "/../") . '/atencion/CargarArchivo.php';
require_once (realpath(dirname(__FILE__) . "/../") . "/clasesComunes/BuscarDestinatario.php");
include_once realpath(dirname(__FILE__) . "/../") . '/atencion/RadicacionAtencion.php';
require_once realpath(dirname(__FILE__) . "/../") . "/atencion/AtencionTipos.php";
include_once realpath(dirname(__FILE__) . "/../") . '/clasesComunes/Usuario.php';
include_once realpath(dirname(__FILE__) . "/../") . '/clasesComunes/AtencionCiudadano.php';
include_once  realpath(dirname(__FILE__) . "/../") . "/atencion/conf_form.php";
require_once (realpath(dirname(__FILE__) . "/../")) ."/clasesComunes/CorreoAtencion.php";
////////////////////////////////////////////////////////////////

$db = new ConnectionHandler("$ruta_raiz");

$generar_informe = $_POST["generar_informe"];
$cancelarAnular = $_POST["cancelarAnular"];
$aceptarAnular = $_POST["aceptarAnular"];

if ($cancelarAnular) {
    $aceptarAnular = "";
    $actaNo = "";
}

$depe_codi_territorial = $_SESSION['depe_codi_territorial'];
if ($generar_informe or $aceptarAnular) {
    if ($depeBuscada and $depeBuscada != 0) {
        $whereDependencia = " b.DEPE_CODI=$depeBuscada AND";
    }
    include_once("../include/query/busqueda/busquedaPiloto1.php");
    include "$ruta_raiz/include/query/anulacion/queryanularRadicados.php";

    $fecha_ini = $fecha_busq;
    $fecha_fin = $fecha_busq2;
    $fecha_ini = mktime(00, 00, 00, (int)substr($fecha_ini, 5, 2), (int)substr($fecha_ini, 8, 2), (int)substr($fecha_ini, 0, 4));
    $fecha_fin = mktime(23, 59, 59, (int)substr($fecha_fin, 5, 2), (int)substr($fecha_fin, 8, 2), (int)substr($fecha_fin, 0, 4));

    $query = "select $radi_nume_radi as radi_nume_radi, r.radi_fech_radi, r.ra_asun, r.radi_usua_actu,
        r.radi_depe_actu, r.radi_usu_ante, c.depe_nomb, b.sgd_anu_sol_fech, b.sgd_anu_desc as sgd_anu_desc , u.usua_login   
        from radicado r, sgd_anu_anulados b, dependencia c, usuario u";
    $fecha_mes = substr($fecha_ini, 0, 7);

    // Si la variable $generar_listado_existente viene entonces este if genera la planilla existente
    $where_isql = " WHERE $whereDependencia b.sgd_anu_sol_fecH BETWEEN " .
        $db->conn->DBTimeStamp($fecha_ini) . " and " . $db->conn->DBTimeStamp($fecha_fin) .
        " and u.usua_doc = b.usua_doc and b.SGD_EANU_CODI = 1 and r.radi_nume_radi=b.radi_nume_radi and b.depe_codi = c.depe_codi and b.depe_codi != 900";
    $order_isql = " ORDER BY  b.depe_codi, b.SGD_ANU_SOL_FECH";
    $query_t = $query . $where_isql . $order_isql;

    $anio = date('Y'); 
    $inicioFecha= $anio."-01-01";
    // Verifica el ultimo numero de acta del tipo de radicado
    /*
    $queryk = "Select max (usua_anu_acta)
        from sgd_anu_anulados
        where sgd_eanu_codi=2 and sgd_trad_codigo = 7 and SUBSTR(SGD_ANU_SOL_FECH,1,4) = $anio";
    */
    $year = date("Y");
     //$inicioFecha = mktime(00, 00, 00, substr($inicioFecha, 5, 2), substr($inicioFecha, 8, 2), substr($inicioFecha, 0, 4));
    $queryk = "Select MAX(usua_anu_acta)+1 as ACTAN
        from sgd_anu_anulados
        where  EXTRACT(YEAR FROM sgd_anu_fech) =  " .$year;

        //echo "<br>sql:" . $queryk;
//$db->conn->debug=true;
    $c = $db->conn->Execute($queryk);
    $rsk = $db->query($queryk);

    if(is_null($rsk->fields["ACTAN"])){
      $actaNo = 1;
    }
    else{
      $actaNo = (int)$rsk->fields["ACTAN"];  
    }

}
?>
<html>
<head>
    <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
</head>
<body>
<p>

<div class="col-sm-12">
<!-- widget grid -->
<h2></h2>
<section id="widget-grid">
<!-- row -->
<div class="row">
<!-- NEW WIDGET START -->
<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<!-- Widget ID (each widget will need unique ID)-->
<div class="jarviswidget jarviswidget-color-darken" id="wid-id-1" data-widget-editbutton="false">

<header>
    <h2>
        Anular Radicados<br>
    </h2>
</header>
<!-- widget div-->
<div>
<!-- widget content -->
<div class="widget-body no-padding">
<div class="table-responsive">
<TABLE width="100%" class='borde_tab' cellspacing="5">
    <tr>
        <td height="30" valign="middle" class='titulos5' align="center">Anulacion de Radicados por Dependencia
        </td>
    </tr>
</table>
<form name="new_product" class="smart-form" action='anularRadicados.php?<?= session_name() . "=" . session_id() .  "&fecha_h=$fechah" ?>' method=post>
<center>

    <table class="table table-bordered table-striped">
        <!--DWLayoutTable-->
        <tr>
            <td width="125" height="21" class='titulos2'> Fecha desde<br>
                <?
                echo "($fecha_busq)";
                ?>
            </TD>
            <td width="500" align="right" valign="top" class='listado2'>
                <label class="input"> <i class="icon-append fa fa-calendar"></i>
                    <input type="text" id="fecha_busq" name="fecha_busq" placeholder="Fecha de inicial" value="<?=$fecha_busq?>">
                </label>
            </TD>
        </TR>
        <tr>
            <td width="125" height="21" class='titulos2'> Fecha Hasta<br>
                <?
                echo "($fecha_busq2)";
                ?>
            </TD>
            <td width="500" align="right" valign="top" class='listado2'>
                <label class="input"> <i class="icon-append fa fa-calendar"></i>
                    <input type="text" id="fecha_busq2" name="fecha_busq2" placeholder="Fecha de inicial" value="<?=$fecha_busq2?>">
                </label>
            </TD>
        </TR>
        
        <tr>
            <td height="26" class='titulos2'>Dependencia</td>
            <td valign="top" align="left" class='listado2'>
                <label class="select">
                    <?
                    $sqlD = "select depe_nomb,depe_codi from dependencia
                       where depe_codi_territorial = $depe_codi_territorial
                                order by depe_codi";
                    $rsD = $db->conn->Execute($sqlD);
                    print $rsD->GetMenu2("depeBuscada", "$depeBuscada", false, false, 0, " class='select'> <option value=0>--- TODAS LAS DEPENDENCIAS --- </OPTION ");
                    //if(!$depeBuscada) $depeBuscada=$dependencia;
                    ?>
                </label>
            </td>
        </tr>
        <tr>
            <td height="26" colspan="2" valign="top" class='titulos2'>
                <center>
                    <INPUT TYPE=submit name=generar_informe Value='Ver Documentos En Solicitud' class='btn btn-sm  btn-success '>
                </center>
            </td>
        </tr>
    </table>

    <HR>
    <?php
    if (!$fecha_busq)
        $fecha_busq = date("Y-m-d");
    if ($aceptar and !$actaNo and !$cancelarAnular)
        die ("<font color=red><span class=etextomenu>Debe colocal el Numero de acta para poder anular los radicados</span></font>");
    if ($aceptar and $dependencia<>20)
        die ("<font color=red><span class=etextomenu>Solo la SAF puede radicar Actas</span></font>");
    if (($generar_informe or $aceptarAnular) and !$cancelarAnular) {

        require "../anulacion/class_control_anu.php";
        $db->conn->SetFetchMode(ADODB_FETCH_NUM);
        $btt = new CONTROL_ORFEO($db);
        $campos_align = array("C", "L", "L", "L", "L", "L", "L", "L", "L", "L", "L", "L");
        $campos_tabla = array("depe_nomb", "radi_nume_radi", "sgd_anu_sol_fech", "sgd_anu_desc");
        $campos_vista = array("Dependencia", "Radicado", "Fecha de Solicitud", "Observacion Solicitante");
        $campos_width = array(200, 100, 280, 300);
        $btt->campos_align = $campos_align;
        $btt->campos_tabla = $campos_tabla;
        $btt->campos_vista = $campos_vista;
        $btt->campos_width = $campos_width;
        ?></center>

        <table width="100%" cellspacing="3" class="table table-bordered table-striped">
            <tr>
                <td height="30" valign="middle" class='titulos5' align="center" colspan="2">Documentos con solicitud de Anulacion
                </td>
            </tr>
            <tr>
                <td width="16%" class='titulos5'>Fecha Inicial</td>
                <td width="84%" class='listado5'><?= $fecha_busq ?> </td>
            </tr>
            <tr>
                <td class='titulos5'>Fecha Final</td>
                <td class='listado5'><?= $fecha_busq2 ?>
            </tr>
            <tr>
                <td class='titulos5'>Fecha Generado</td>
                <td class='listado5'><? echo date("Ymd - H:i:s"); ?></td>
            </tr>
        </table>
        <?

        $btt->tabla_sql($query_t);
        $html = $btt->tabla_html;

    }

if ($generar_informe) {
    ?>
    <span class="listado2">
    <br>Si esta seguro de Anular estos documentos por favor presione aceptar.<br>

    <table class="table table-bordered table-striped" align="center">
      <tr>
          <td>
              <input type="submit" name="aceptarAnular" value="Aceptar" class="btn btn-sm btn-default">
          </td>
          <td>
              <input type="submit" name="cancelarAnular" value="Cancelar" class="btn btn-sm btn-default">
          </td>
      </tr>
    </table>
    </span>
<?
}

//Se le asigna a actaNo el No. de acta que debe seguir

if ($aceptarAnular and $actaNo) {
    include_once "$ruta_raiz/include/db/ConnectionHandler.php";
    $db = new ConnectionHandler("$ruta_raiz");
    //*Inclusion territorial
    $lista_depcod = "";
    if ($depeBuscada == 0) {

        $sqlD = "select depe_nomb,depe_codi from dependencia
                  where depe_codi_territorial = $depe_codi_territorial
                  order by depe_codi";
        $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
        $rsD = $db->conn->Execute($sqlD);
        
        while (!$rsD->EOF) {
            $depcod = $rsD->fields["DEPE_CODI"];
            $lista_depcod .= " $depcod,";
            $rsD->MoveNext();
        }
        $lista_depcod .= "0";
    } else {
        $lista_depcod = $depeBuscada;
    }
    $where_depe = " and (depe_codi) in ($lista_depcod )";
    
    //*fin inclusion
    /*
     * Variables que manejan el tipo de Radicacion
     */
    
    $TituloActam = "Anulados";
    
    $dbSel = new ConnectionHandler("$ruta_raiz");
    $dbSel->conn->SetFetchMode(ADODB_FETCH_ASSOC);
    $rsSel = $dbSel->conn->Execute($query_t);
    $i = 0;
    $radAnularE[-1]="";
    $radObservaE[-1]="";
    $radTipoRadE[-1]="";
    $cuerpo="";
    $contador=68;
    $obserTmp="";
    while (!$rsSel->EOF) {
        $radAnularE[$i] = $rsSel->fields['RADI_NUME_RADI'];
        $radObservaE[$i] = $rsSel->fields['SGD_ANU_DESC'];
        $obserTmp=$rsSel->fields['SGD_ANU_DESC'];
        $i++;
        $tipoR=substr($rsSel->fields['RADI_NUME_RADI'],-1,1);

        switch ($tipoR) {
            case '1': 
                $radTipoRadE[$i]="Salida";
                break;
            case '2': 
                $radTipoRadE[$i]="Entrada";
                break;
            case '3': 
                $radTipoRadE[$i]="Memorando";
                break;
            case '4': 
                $radTipoRadE[$i]="CID";
                break;
            case '5': 
                $radTipoRadE[$i]="Resoluciones";
                break;
            case '6': 
                $radTipoRadE[$i]="Circulares";
                break;
            case '7': 
                $radTipoRadE[$i]="Actas";
                break;
            case '8': 
                $radTipoRadE[$i]="Autos";
                break;
            case '9': 
                $radTipoRadE[$i]="Contratos";
                break;
            default:
                // code...
                break;
        }
        $cuerpo.='<table:table-row table:style-name="ro1">
                    <table:table-cell table:style-name="ce1"><text:p>'.$radTipoRadE[$i].'</text:p></table:table-cell>
                    <table:table-cell table:style-name="ce1"><text:p>'.$rsSel->fields['RADI_NUME_RADI'].'</text:p></table:table-cell>
                    <table:table-cell table:style-name="ce1"><text:p>'.$rsSel->fields['SGD_ANU_DESC'].'</text:p></table:table-cell>
                    <table:table-cell table:style-name="ce1"><text:p>'.$rsSel->fields['USUA_LOGIN'].'</text:p></table:table-cell>
                </table:table-row>';


        $rsSel->MoveNext();
    }
    $radAnularE = array_filter($radAnularE);
    
    
        $Anulacion = new Anulacion($db);
        $observa = "Radicado Anulado. (Acta No $actaNo)";
        $var = md5(date("YMDHis"));

        $noArchivo = "/planillas/tmp/ActaAnul_$dependencia" . "_" . "$actaNo" . "_" . $var . "_.pdf";
        
        $radicados = $Anulacion->genAnulacion($radAnularE,
            $dependencia,
            $usua_doc,
            $obserTmp,
            $codusuario,
            $actaNo,
            $noArchivo,
            $where_depe,
            7,
            $actaNo);


        $Historico = new Historico($db);
        
        $radicados = $Historico->insertarHistorico($radAnularE, $dependencia, $codusuario, $depe_codi_territorial, 1, $observa, 26);
        echo "<br>radicados insertarHistorico:" . var_dump($radicados);

        //die();
        define("FPDF_FONTPATH", '../fpdf/font/');
        $radAnulados = join(",", $radAnularE);
        $radicadosPlantilla="";
        if (!$radAnularE){
            $radicadosPlantilla .= "No existen radicados para anular en este rango de fechas.";
        }

    $cont=57;
    foreach ($radAnularE as $id => $noRadicado) {
         $norad = $radAnularE[$id];
         $txrad = $radObservaE[$id];

         $radicadosPlantilla.='<text:p text:style-name="P' . $cont . '">* Radicado No. '. $norad . ' - ' . $txrad . '</text:p>';

    }
    
    $tabla_anulados.=$radicadosPlantilla;
    $tabla_anulados.=$cuerpo;

    $tabla_anulados = '<table:table table:name="Tabla1" table:style-name="ta1">
                    <table:table-column table:style-name="co1" table:number-columns-repeated="4"/>
                    <table:table-row table:style-name="ro1">
                        <table:table-cell table:style-name="ce1"><text:p>Tipo de documento</text:p></table:table-cell>
                        <table:table-cell table:style-name="ce1"><text:p>Radicado</text:p></table:table-cell>
                        <table:table-cell table:style-name="ce1"><text:p>Observación</text:p></table:table-cell>
                        <table:table-cell table:style-name="ce1"><text:p>Solicitante</text:p></table:table-cell>
                    </table:table-row>
                    '.$cuerpo.'
                    </table:table>';



    $anoActual = date("Y");
     
    $fecha = date("d-m-Y");
    $fecha_hoy_corto = date("d-m-Y");
    include "$ruta_raiz/class_control/class_gen.php";
    $date = date("m/d/Y");
    $b = new CLASS_GEN();
    $fecha_hoy = $b->traducefecha($date);

    $variables["numero_acta"]=$actaNo;
    $variables["ano_actual"]=$anoActual;
    $variables["fecha_inicio"]=$fecha_busq;
    $variables["fecha_fin"]=$fecha_busq2;
    $variables["radicados"]=$radicadosPlantilla;
    $variables["fecha_corto"]=$fecha_hoy;
    $variables["tipo_radicado"]=$TituloActam;
    $variables["tabla_anulados"]=$tabla_anulados;
    $variables["asunto"]="Acta de anulacion de radicados de " . $TituloActam;

    
    /*PLANTILLA*/
    
    $arreglo = array(
        "cont" => "1",
        "pais" => "170",
        "depto" => "11",
        "mncpio" => "1",
        "tipoPersona" => "4",
        "documento" => "52184369",
        "depto" => "11",
        "mncpio" => "1",
    );
    
    $des = new BuscarDestinatario();
    $destinatario = $des->generaDestinatario($arreglo);
    $radicacionAtencion = new RadicacionAtencion();
    $tencionCiudadano = new AtencionCiudadano();
    $usuario = new Usuario();
    $usuarioRadica = array();
    $usuarioRadica["DEPE_CODI"]=$dependencia;
    $usuarioRadica["USUA_DOC"]=$usua_doc;
    $usuarioRadica["USUA_CODI"]=$codusuario;
    
    $usr = $radicacionAtencion->getInfoUsuario($usuarioRadica, $usuarioDestino);
    $documento["mrec_cod"] = 3;

    $documento["asunto"] = "ACTA DE ANULACION " . $TituloActam;
    
    $atencion["mrec"] = $documento["mrec_cod"];
    $atencion["destinatario"]=$destinatario;
    $atencion["tipoPersona"]=empty($_POST["tipoPersona"])?0:$_POST["tipoPersona"];

    
    if (! empty($_POST["grupo"])) {
        $atencion["componente"] = $_POST["grupo"];
    } elseif (! empty($_POST["tipoEmpresa"])) {
        $atencion["componente"] = $_POST["tipoEmpresa"];
    } elseif (! empty($_POST["tipoEmpresa"])) {
        $atencion["componente"] = $_POST["tipoESP"];
    }
    $atencion["formulario"]="Acta de anulacion de radicados";
    
    $numeroRadicado = $radicacionAtencion->radicarActaAnulados($usr, $variables, "plantilla_anulados.odt", $dependencia, $codusuario);
    
    $atencion["radicado"] = $numeroRadicado["radicado"];
    $numR=$numeroRadicado["radicado"];
    
    //$tencionCiudadano->crearAtencion($atencion);
    $_SESSION["idFormulario"] = sha1(microtime(true) . mt_rand(10000, 90000)); 
   
    $radInExpStyle = "class='fa fa-folder-open'";
    echo "<td> <div><small style=\"padding: 5px;\"><a href='javascript:void(0)' onclick=\"funlinkArchivo('$numR','$ruta_raiz');\"> $numR</a></small></div></td>";
    /*FIN PLANTILLA*/
}
?>

</form>
</div>
</div>
</div>
</div>
</article>
</div>
</section>
</div>

</body>
<script>
    //Datepicker muestra fecha
    $('#fecha_busq').datepicker({
        dateFormat : 'yy-mm-dd',
        onSelect : function(selectedDate) {
            $('#date').datepicker('option', 'maxDate', selectedDate);
        }
    });

    //Datepicker muestra fecha
    $('#fecha_busq2').datepicker({
        dateFormat : 'yy-mm-dd',
        onSelect : function(selectedDate) {
            $('#date').datepicker('option', 'maxDate', selectedDate);
        }
    });
</script>

<script type="text/javascript">
    // Muestra las imagenes de los radicados
    function funlinkArchivo(numrad, rutaRaiz){
        var nombreventana = "linkVistArch";
        var url           = rutaRaiz + "/linkArchivo.php?<? echo session_name()."=".session_id()?>"+"&numrad="+numrad;
        var ventana       = window.open(url,nombreventana,'scrollbars=1,height=50,width=250');
        //setTimeout(nombreventana.close, 70);
        return;
    }
</script>
</HTML>
