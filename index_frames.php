<?php
/**
 * @module index_frame
 *
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright 
 
 OrfeoGPL Models are the data definition of OrfeoGPL Information System
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
// Esto es para darle al usuario acceso al menu Opciones
$usuarios_admin = array(
    'CAPA2'
);

session_start();

$ruta_raiz = ".";
if (! $_SESSION['dependencia'] || $_GET['close']) {
    header("Location: $ruta_raiz/login.php");
    echo "<script>parent.frames.location.reload();top.location.reload();</script>";
}

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once "$ruta_raiz/config.php";

$db = new ConnectionHandler($ruta_raiz);
$cambioKrd = $_GET["cambioKrd"];
if ($cambioKrd && $krd != $cambioKrd) {
    $_SESSION["krd"] = $_GET["cambioKrd"];
    $krd = $cambioKrd;
    $_SESSION["dependencia"] = $_GET["cambioDepeCodi"];
    $_SESSION["codusuario"] = $_GET["cambioUsuaCodi"];
    
    include "session_orfeo.php";
    session_regenerate_id();
}
$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];
$tip3Nombre = $_SESSION["tip3Nombre"];
$tip3desc = $_SESSION["tip3desc"];
$tpDescRad = $_SESSION["tpDescRad"];
$tip3img = $_SESSION["tip3img"];
$ESTILOS_PATH = $_SESSION["ESTILOS_PATH"];
$nombUser = $_SESSION["usua_nomb"];
$tpNumRad = $_SESSION["tpNumRad"];
$tpPerRad = $_SESSION["tpPerRad"];


$fechah = date("Ymdhms");
$phpsession = "c=$fechah&";
$ruta_raiz = ".";
$enlace = "href=\"cuerpo.php?$phpsession";
$enlace1 = "href=\"cuerpoAgenda.php?$phpsession&agendado=1";
$enlace2 = "href=\"cuerpoAgenda.php?$phpsession&agendado=2";
$enlace3 = "href=\"bandejaInformados.php?mostrar_opc_envio=1&orderNo=2&
                     carpeta=8&nomcarpeta=Informados&orderTipo=desc&adodb_next_page=1\"";
$enlace4 = "href=\"tx/cuerpoInfConjunto.php?$phpsession&mostrar_opc_envio=1&orderNo=2&
                     carpeta=66&nomcarpeta=Informados&orderTipo=desc&adodb_next_page=1\"";
$enlace5 = "href=\"cuerpoTx.php?$phpsession&";
// $enlace6 = "href=\"cuerpoPrioritario.php?$phpsession&";
$enlace7 = "href=\"crear_carpeta.php?$phpsession&";
$enlace8 = "href=\"cuerpo.php?$phpsession&";
$enlace21 = "href=\"busqueda/busquedaPiloto.php?$phpsession&";
$enlace22 = "href=\"./estadisticas/vistaFormConsulta.php?$phpsession&";
$enlace23 = "href=\"./reportesCRA/menu.php?$phpsession&";
#$enlace24 = 'href="http://192.168.100.51:9090/renderer?use=principal&login=admin&key=e0e51412527b257acb143038d41a1b77&name=default"';
$enlace24 =  "href=\"./search/?";
$enlace25 = "href=\"./busqueda/busquedaExp.php?$phpsession&";
$enlace26 = "href=\"./busqueda/consultaESP.php?$phpsession&";

$sqlFechaHoy = $db->conn->DBTimeStamp(time());

$urlInicio = "cuerpo.php?swLog=" . $swLog . "&fechah=" . $fechah . "&tipo_alerta=1";

// Radicacion
foreach ($tpNumRad as $key => $valueTp) {
    
    $valueDesc = $tpDescRad[$key];
    
    $enlace9 = "href=\"radicacion/chequear.php?$phpsession&primera=1&ent=$valueTp&depende=$dependencia\"";
    
    if ($tpPerRad[$valueTp] == 1 or $tpPerRad[$valueTp] == 3) {
        $linkrad .= "<li><a $enlace9 target='mainFrame'> $valueDesc </a></li>";
    }
}
if ($ent == 1) { // Si es vacio, traemos el que esta quiere decir que es entrada
    $codiRegH[0] = $nrobs;
}

if ($_SESSION["usua_masiva"] == 1) {
    $enlace10 = "href=\"#\" onclick=\"return false;\"";
    $enlacea1 = "href=\"./radsalida/masiva/upload2PorExcel.php?$phpsession&fechah=$fechah\"";
    $enlacea2 = "href=\"./radsalida/cuerpo_masiva_recuperar_listado.php?$phpsession&fechah=$fechah\"";
    $linkint = "<li><a tabindex=\"-1\" $enlacea1 target=\"mainFrame\">Masiva externa</a></li>";
    $linkint .= "<li><a tabindex=\"-1\" $enlacea2 target=\"mainFrame\">Recuperar Listado</a></li>";
    $linkrad .= "<li class=\"dropdown-submenu\">
                    <a $enlace10 target=\"mainFrame\">Masiva</a>
                    <ul class=\"dropdown-menu\">
                      $linkint
                    </ul>
                   </li>";
}
if ($_SESSION["usua_perm_owncloud"] >= 1) {
    $enlace13 = "href=\"uploadFiles/owncloud/orfeoCloud.php?$phpsession&primera=1&ent=2&depende=$dependencia\"";
    $linkrad .= "<li><a $enlace13 target=\"mainFrame\">Cargar Archivos Owncloud</a></li>";
}

//if ($_SESSION["perm_radi"] >= 1) {
if ($_SESSION["dependencia"] == 321 || $_SESSION["dependencia"] == 900) {
    $enlace11 = "href=\"uploadFiles/uploadFileRadicado.php?$phpsession&primera=1&ent=2&depende=$dependencia\"";
    $linkrad .= "<li><a $enlace11 target=\"mainFrame\">Asociar Imagenes</a></li>";
}

if ($_SESSION["usuaPermRadEmail"] == 1) {
    //$enlace12 = "href=\"radiMail/lock.php?$phpsession&primera=1&ent=2&depende=$dependencia\"";
    $enlace12 = "href=\"radiMailAUTH/listarCorreosAUTH.php?$phpsession&primera=1&ent=2&depende=$dependencia\"";
    $linkrad .= "<li><a $enlace12 target=\"mainFrame\">e-Mail</a></li>";
}

// Esta consulta selecciona las carpetas como Devueltos, Entrada ,salida
$link1 = $enlace . "$fechah&nomcarpeta=General&carpeta=9999&tipo_carpt=0\"";
$link1show .= "<li><a $link1 target=\"mainFrame\" >General (Todos)</a></li>";
if ($codusuario == 0) {
    $codusuario = $_SESSION["codusuario"];
}
if ($dependencia == 0) {
    $dependencia = $_SESSION["dependencia"];
}
$isql = "SELECT c.CARP_CODI,MIN(c.CARP_DESC ) CARP_DESC, COUNT(*) NRADS
            FROM CARPETA c left outer join radicado r
             on (r.carp_codi=c.carp_codi and r.carp_per=0)
            where radi_usua_actu=$codusuario
              and radi_depe_actu=$dependencia
            group by c.CARP_CODI
                        ORDER BY c.CARP_CODI";
$rs = $db->query($isql);
$auxdevueltos = 0;
while (!empty($rs) &&  !$rs->EOF) {
    $numdata = trim($rs->fields["CARP_CODI"]);
    $rsCarpDesc = $db->query($sqlCarpDep);
    $desccarpt = $rs->fields["CARP_DESC"];
    $nRads = $rs->fields["NRADS"];
    if ($numdata == 0)
        $numdata = 9998;
    $data = (empty($descripcionCarpeta)) ? trim($desccarpt) : $descripcionCarpeta;
    $link1 = $enlace . "$fechah&nomcarpeta=$data&carpeta=$numdata&tipo_carpt=0\"";
    if ($desccarpt == 'Devueltos') {
        $auxdevueltos = 1;
    }
    $link1show .= "<li><a $link1 target=\"mainFrame\" >$desccarpt ($nRads)</a></li>";
    
    $rs->MoveNext();
}
// si no trajo devueltos, se los coloco
if ($auxdevueltos == 0) {
    $link1 = $enlace . "$fechah&nomcarpeta=Devueltos&carpeta=12&tipo_carpt=0\"";
    $link1show .= "<li><a $link1 target=\"mainFrame\" >Devueltos (0)</a></li>";
}

// Se realiza la cuenta de radicados en Visto Bueno VoBo
if ($numdata == 11) {
  if ($codusuario == 1) {
        $isql = "select count(*) as CONTADOR
                    from radicado
                    where carp_per = 0 and
                    carp_codi = $numdata and
                    radi_depe_actu = $dependencia and
                    radi_usua_actu = $codusuario";
    } else {
        $isql = "select count(*) as CONTADOR
                    from radicado
                    where carp_per = 0 and
                    carp_codi = $numdata and
                    radi_depe_actu = $dependencia and
                    (radi_usu_ante = '$krd' or
                    (radi_usua_actu = $codusuario and radi_depe_actu=$dependencia))";
    }
} else {
    $isql = "select count(*) as CONTADOR                                                                                                              
                  from radicado
                  where carp_per = 0 and
                  carp_codi = 11 and
                  radi_depe_actu = $dependencia and
                  radi_usua_actu = $codusuario";
    $addadm = "&adm=0";
}

// Cuenta los numero de radicados por visto bueno
$data = "Documenos para Visto Bueno";
$rs = $db->conn->query($isql);
$numero_radicados = (! $rs->EOF) ? $rs->fields['CONTADOR'] : 0;
$link11 = $enlace . "$fechah&nomcarpeta=$data&carpeta=11&tipo_carpt=0\"";
$link11show .= "<li><a $link11 target=\"mainFrame\" >Visto Bueno ($numero_radicados)</a></li>";

// Agendado
$isql = " SELECT COUNT(1) AS CONTADOR
                  FROM SGD_AGEN_AGENDADOS agen
                  WHERE usua_doc='$usua_doc'
                  and agen.SGD_AGEN_ACTIVO=1
                  and (agen.SGD_AGEN_FECHPLAZO >= $sqlFechaHoy )";

$rs = $db->conn->query($isql);
$num_exp = $rs->fields["CONTADOR"];
$data = "Agendados no vencidos";
$link2 = $enlace1 . "$fechah&nomcarpeta=$data&tipo_carpt=0\"";
$link2show = "<li><a $link2 target=\"mainFrame\" >Agendado($num_exp)</a></li>";

// Agendado Vencido
$isql = "SELECT COUNT(1) AS CONTADOR
          FROM SGD_AGEN_AGENDADOS AGEN
          WHERE  USUA_DOC='$usua_doc'
          and agen.SGD_AGEN_ACTIVO=1
          and (agen.SGD_AGEN_FECHPLAZO <= $sqlFechaHoy)";

$rs = $db->conn->query($isql);

$num_exp = $rs->fields["CONTADOR"];
$data = "Agendados vencidos";
$link3 = $enlace2 . "$fechah&nomcarpeta=$data&&tipo_carpt=0&adodb_next_page=1\"";
$link3show = "<li><a $link3 target=\"mainFrame\" >Agendado Vencido (<font color='#990000'>$num_exp</font>)</a></li>";

// Informados
$isql = " SELECT COUNT(1) AS CONTADOR
               FROM INFORMADOS
             WHERE DEPE_CODI=$dependencia
              and usua_codi=$codusuario
              and info_conjunto=0";

$rs1 = $db->conn->query($isql);
$numerot = ($rs1) ? $rs1->fields["CONTADOR"] : 0;
$link4show = "<li><a $enlace3 target=\"mainFrame\" >Informados ($numerot)</a></li>";

// Tramite conjunto
$isql = "SELECT COUNT(1) AS CONTADOR
         FROM INFORMADOS
         WHERE DEPE_CODI=$dependencia
          and usua_codi=$codusuario
          and info_conjunto>=1 ";
$rs1 = $db->query($isql);

$numerot = ($rs1) ? $rs1->fields["CONTADOR"] : 0;
if ($numerot >= 1) {
    // $link5show= "<li><a $enlace4 target=\"mainFrame\"> Tramite Conjunto ($numerot)</a></li>";
}
// Ultimas transacciones del usuario
$data = "Ultimas Transacciones del Usuario";
$link6 = $enlace5 . "$fechah&nomcarpeta=$data&tipo_carpt=0\"";
$link6show = "<li><a $link6 target=\"mainFrame\">Transacciones</a></li>";

// Prioritarios
/*
 * $numeroP = 0;
 * include ("include/query/queryCuerpoPrioritario.php");
 * $rsP = $db->conn->query($isqlPrioritario);
 * $numeroP = $rsP->fields["NUMEROP"];
 * $clasePrioritarios = ($numeroP >= 1)? "titulosError" : "menu_princ";
 * $link7 = $enlace6."$fechah&nomcarpeta=$data&tipo_carpt=0\"";
 * $link7show = "<li><a $link6 target=\"mainFrame\">Prioritarios ($numeroP)</a></li>";
 */
// Enlace carpetas Personales
$link8 = $enlace7 . "fechah=$fechah&adodb_next_page=1\"";
$link8show = "<a tabindex=\"-1\"  target=\"mainFrame\" > Personales </a>";
$link9show .= "<li><a tabindex=\"-1\" $link8 target=\"mainFrame\"> Nueva Carpeta <i class=\" fa fa-plus-circle\"></i></a></li>";

// Carpetas Personales
$isql = "SELECT
            DISTINCT CODI_CARP,
            DESC_CARP,
            NOMB_CARP
          FROM
            CARPETA_PER
          WHERE
            USUA_CODI=$codusuario AND
            DEPE_CODI=$dependencia ORDER BY CODI_CARP  ";

$rs = $db->query($isql);
while (!empty($rs) &&  !$rs->EOF) {
    
    $data = trim($rs->fields["NOMB_CARP"]);
    $numdata = trim($rs->fields["CODI_CARP"]);
    $detalle = trim($rs->fields["DESC_CARP"]);
    $data = trim($rs->fields["NOMB_CARP"]);
    
    $isql = "SELECT
                    COUNT(1) AS CONTADOR
                  FROM
                    RADICADO
                  WHERE
                    CARP_PER=1 AND
                    CARP_CODI = $numdata AND
                    RADI_DEPE_ACTU = $dependencia AND
                    RADI_USUA_ACTU=$codusuario ";
    
    $rs1 = $db->query($isql);
    $numerot = $rs1->fields["CONTADOR"];
    $datap = "$data(Personal)";
    
    $link9 = $enlace8 . "fechah=$fechah&tipo_carp=1&carpeta=$numdata&nomcarpeta=$data\"";
    $link10show .= "<li><a tabindex=\"-1\" $link9 target=\"mainFrame\" > $data($numerot) </a></li>";
    $rs->MoveNext();
}

// Consultas
$link21 = $enlace21 . "&etapa=1&s_Listado=VerListado&fechah=$fechah\"";
$link24 = $enlace24 . "&etapa=1&s_Listado=VerListado&fechah=$fechah\"";
$link25 = $enlace25 . "&etapa=1&s_Listado=VerListado&fechah=$fechah\"";
$link26 = $enlace26 . "&etapa=1&s_Listado=VerListado&fechah=$fechah\"";
if ($_SESSION['busquedaFullOrfeo'] == true and $_SESSION['entidad'] == "CRA")
    $link21show = '<li class="dropdown-submenu"><a tabindex="-1" target="mainFrame"> Consultas </a>
                <ul class="dropdown-menu">
                    <li><a tabindex="-1"' . $link21 . 'target="mainFrame"> Consultas clasica</a></li>
                    <li><a tabindex="-1"' . $link24 . ' target="mainFrame"> Consulta avanzada</a></li>
                    <li><a tabindex="-1"' . $link25 . 'target="mainFrame"> Consulta Expedientes</a></li>
                    <li><a tabindex="-1"' . $link26 . 'target="mainFrame"> Consulta Historico</a></li>
                </ul>
            </li>';
else
    $link21show = "<li><a tabindex=\"-1\" $link21 target=\"mainFrame\"> Consultas </a></li>";
    // Estadisticas
$link22 = $enlace22 . "&fechah=$fechah\"";
$link22show = "<li><a tabindex=\"-1\" $link22 target=\"mainFrame\"> Estadisticas </a></li>";
// reportesCRA
$link23 = $enlace23 . "&fechah=$fechah\"";
$link23show = "<li><a tabindex=\"-1\" $link23 target=\"mainFrame\"> reportesCRA </a></li>";
$tiene_acceso_admin = in_array($krd, $usuarios_admin);


/* CARLOS RICAURTE 29/06/2018
    SE REALIZA SELECT A MEMBRESIAS PARA VER OPCIONES DEL MENU DEPENDIENDO EL PERMISOS
*/
$isql = "   SELECT DISTINCT AUTG_ID
  FROM USUARIO U, AUTM_MEMBRESIAS M
  WHERE U.ID=M.AUTU_ID AND USUA_CODI=$codusuario AND DEPE_CODI=$dependencia AND AUTG_ID IS NOT NULL";

$rs = $db->query($isql);
$cp=0;
$permisoSticker=0;
$permisoActuanciones=0;
$permisoMenuUtilitarios=0;

while (!empty($rs) &&  !$rs->EOF) {
    $vecPermisos[$cp] = $rs->fields["AUTG_ID"];
    if($rs->fields["AUTG_ID"]==427) {
      $permisoSticker=1;
      $permisoMenuStickers=1;
    }
    if($rs->fields["AUTG_ID"]==449 || $rs->fields["AUTG_ID"]==536 || $rs->fields["AUTG_ID"]==588) {
      $permisoMenuUtilitarios=1;
    }
    if($rs->fields["AUTG_ID"]==449) {
      $permisoMoverRadicados=1;
    }
    if($rs->fields["AUTG_ID"]==489) {
      $atencioCiudadanos=1;
    }
    if($rs->fields["AUTG_ID"]==529) {
      $permisoActuanciones=1;
    }
    if($rs->fields["AUTG_ID"]==530) {
      $permisoCreaActuanciones=1;
    }
    if($rs->fields["AUTG_ID"]==534) {
      $permisoMenuCobros=1;
      $permisoCobros=1;
    }
    if($rs->fields["AUTG_ID"]==535) {
      $permisoMenuEmpresas=1;
    }
    if($rs->fields["AUTG_ID"]==536) {
      $permisoMoverExpedientes=1;
    }

    $cp++;
    $rs->MoveNext();
}

/* FIN MENU POR MENBRESIAS CARLOS RICAURTE*/

?>
<html lang="es">
<head>
    <? $entidad = $_SESSION['entidad']; $nombre_fichero_css = "./estilos/$entidad.bootstrap.min.css"; ?>    

        <meta charset="utf-8">
<title> ..:: <?=$entidad?> ::.</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?=$entidad?>">
<!--Si existe un favicon especifico para la entidad su nombre debe de ser asi <entidad>.favicon.png,
       si no existe se toma el favicon por defecto-->
<link rel="icon" type="image/x-icon"  
    href="./img/<?=(file_exists("./img/$entidad.favicon.png"))?$entidad.".":""?>favicon
       .png"
    onClick="this.reload();">
<!-- Bootstrap core CSS -->
    <? if (file_exists($nombre_fichero_css)) { ?>
        <link href="./estilos/<?=$entidad?>.bootstrap.min.css"
    rel="stylesheet">
    <? }else{ ?>
    <link href="./estilos/correlibre.bootstrap.min.css" rel="stylesheet">
    <? } ?>
        <!-- font-awesome CSS -->
<link href="./estilos/font-awesome.css" rel="stylesheet">
<!-- Bootstrap core CSS -->
<link href="./estilos/siim_temp.css" rel="stylesheet">
<script type="text/javascript" src="<?=$ruta_raiz?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?=$ruta_raiz?>/js/bootstrap.js"></script>
<script>
            function recapagi() {
                location.reload();
            }
        </script>
</head>

<body  style=" overflow: hidden;">
    <div id="wrapper"    style=" overflow: hidden;">
        <!-- Sidebar -->
        <div id="menu" style=" padding-bottom: 40px;">
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target="#navbar-ex1-collapse">
                            <span class="sr-only">Toggle navigation</span> <span
                                class="icon-bar"></span> <span class="icon-bar"></span> <span
                                class="icon-bar"></span>
                        </button>
                        <!--Si existe un logoEntidad especifico para la entidad su nombre debe de ser asi <entidad>.favicon.png, si no existe se toma el favicon por defecto-->
                        <a class="navbar-brand" align="center" onclick="recapagi()"
                            href="#" alt="<?=$entidad_largo?>" title="<?=$entidad_largo?>"><img
                            border=0
                            src="<?=$ruta_raiz?>/img/<?=(file_exists("./img/$entidad.logoEntidad.png"))?$entidad.".":""?>logoEntidad.png"
                            width="25" height="22"></a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="navbar-ex1-collapse">

                        <ul class="nav navbar-nav">

                <?php
                if ($permisoMenuEmpresas == 1) {

                    $isql2 = "select count(1) as CONTADOR2 from SGD_OEM_OEMPRESAS where sgd_oem_estado=1";
                    $rs = $db->conn->Execute($isql2);
                    $num_exp2 = $rs->fields["CONTADOR2"];
                                
                ?>  

                    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Empresas <b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        <li><a href='./Administracion/tbasicas/empresas/adm_esp.php?><?=$phpsession?>&carpeta=8&nomcarpeta=Expedientes&orno=1&adodb_next_page=1'
                        class="vinculos" target='mainFrame'> Empresa Sector (<?=$num_exp?>) </a>
                        </li>
                        <li><a href='./Administracion/tbasicas/otrasempresas/adm_ente.php?<?=$phpsession?>&fechaf=<?=$fechah?>&carpeta=8&nomcarpeta=Expedientes&orno=1&adodb_next_page=1'      target='mainFrame'>Otras Empresas/Gobierno (<?=$num_exp2?>)</a>
                        </li>
                      </ul>      
                    </li>
                <?php
                }
                if ($_SESSION["usua_perm_envios"] >= 1 || $_SESSION["usua_perm_adminflujos"] == 1 || $_SESSION["usua_perm_modifica"] >= 1 || $_SESSION["usua_perm_intergapps"] == 1 || $_SESSION["usua_perm_impresion"] >= 1 || ($_SESSION["usua_perm_anu"] == 3 or $_SESSION["usua_perm_anu"] == 1) || $_SESSION["usua_perm_trd"] == 1 || $_SESSION["usua_admin_archivo"] >= 1 || $_SESSION["usua_perm_prestamo"] == 1 || $_SESSION["usua_perm_dev"] == 1) {
                    ?>
                  <?php if ($_SESSION["USUA_PERM_RECOVER_RAD"]>=1){?><li><a
                                href="instalacion/recuperar_radicado.php" target='mainFrame'>
                                    Recuperar Radicados </a></li><?php } ?>

                  <li class="dropdown"><a href="#"
                                class="dropdown-toggle" data-toggle="dropdown"> Acciones <b
                                    class="caret"></b></a>
                                <ul class="dropdown-menu">

                                    <li><a href='reportes/listado_express.php' alt='Generar planilla de distribucion y entrega' target='mainFrame' class="menu_princ">Reportes Orfeo</a></li>
             <?php if ($_SESSION["usua_perm_anu"]==3 or $_SESSION["usua_perm_anu"]==1){?>
             <li><a href="anulacion/cuerpo_anulacion.php?<?=$phpsession?>&tpAnulacion=1&<? echo "fechah=$fechah";?>" target='mainFrame' class="menu_princ">Anulaci&oacute;n</a></li>
             <?php
                    }
                    if ($_SESSION["usua_perm_adminflujos"] == 1) {
                        ?>
                      <li class="dropdown-submenu"><a href="#" onclick="return false;">Editor Flujos</a> <ul class="dropdown-menu">     <li><a      href='./Administracion/flujos/texto_version2/creaProceso.php?<?=$phpsession ?>&accion=1'        class="vinculos" target='mainFrame'>Crear Proceso</a></li>  <li><a      href='./Administracion/flujos/texto_version2/seleccionaProceso.php?<?=$phpsession ?>&accion=2'      class="vinculos" target='mainFrame'>Editar Flujo</a></li> </ul></li>
                      <?php
                    }
                    
                    if ($_SESSION["usua_perm_envios"] >= 1) {
                        ?>
                      <li><a href="radicacion/formRadEnvios.php?<?=$phpsession ?>&<? echo "fechah=$fechah&usr=".md5($dep)."&primera=1&ent=1"; ?>" target='mainFrame' class="menu_princ">Envios</a></li>
                      <?php } ?>
<? /*   <li><a href="reportes_orfeo.php?<?=$phpsession ?>&<? echo "fechah=$fechah&usr=".md5($dep)."&primera=1&ent=1"; ?>" target='mainFrame' class="menu_princ">Reportes Orfeo</a></li> */ ?>
<?php

                    if ($_SESSION["usua_perm_trd"] >= 1) {
                        ?>
                          <li class="dropdown-submenu"><a href="#" onclick="return false;">Clasificaci&oacute;n Documental</a> <ul class="dropdown-menu">   <li><a      href='./trd/admin_series.php?<?=$phpsession ?>&krd=<?=$krd?>&krd=<?=$krd?>&<? echo "fechah=$fechah"; ?>'        class="vinculos" target='mainFrame'>Series </a></li>    <li><a      href='./trd/admin_subseries.php?<?=$phpsession ?>&krd=<?=$krd?>&krd=<?=$krd?>&<? echo "fechah=$fechah"; ?>'         class="vinculos" target='mainFrame'>Subseries </a></li>     <li><a      href='./trd/cuerpoMatriTRD.php?<?=$phpsession ?>&krd=<?=$krd?>&krd=<?=$krd?>&<? echo "fechah=$fechah"; ?>'      class="vinculos" target='mainFrame'>Matriz Relaci&oacute;n </a></li>    <li><a      href='./trd/admin_tipodoc.php?<?=$phpsession ?>&krd=<?=$krd?>&krd=<?=$krd?>&<? echo "fechah=$fechah"; ?>'       class="vinculos" target='mainFrame'>Tipos Documentales </a></li>    <li><a      href='./trd/procModTrdArea.php?<?=$phpsession ?>&krd=<?=$krd?>&krd=<?=$krd?>&<? echo "fechah=$fechah"; ?>'      class="vinculos" target='mainFrame'>Modificacion TRD Area </a></li>     <li><a      href='./trd/informe_trd.php?<?=$phpsession ?>&krd=<?=$krd?>&krd=<?=$krd?>&<? echo "fechah=$fechah"; ?>'         class="vinculos" target='mainFrame'>Listado Tablas de           Retencion Documental </a></li> </ul></li>
                      <?php
                    }
                    
                    /*
                     * if($_SESSION["usua_perm_intergapps"]==1 ) { ?>
                     * <li><a href="aplintegra/cuerpoApLIntegradas.php?<?=$phpsession?>&<?php echo "fechaf=$fechah&carpeta=8&nomcarpeta=Aplicaciones integradas&orderTipo=desc&orderNo=3"; ?>" target='mainFrame' class="menu_princ">Aplicaciones integradas</a></li>
                     * <?php }
                     */
                    
                    if ($_SESSION["usua_perm_impresion"] >= 1) {
                        if (! isset($usua_perm_impresion)) {
                            $usua_perm_impresion = "";
                        }
                        ?>
                        <li><a href="envios/cuerpoMarcaEnviar.php?<?=$phpsession?>&<?php echo "fechaf=$fechah&usua_perm_impresion=$usua_perm_impresion&carpeta=8&nomcarpeta=Documentos Para Impresion&orderTipo=desc&orderNo=3"; ?>" target='mainFrame' class="menu_princ">Por Enviar</a></li>
                      <?php
                    }
                    
                    if ($_SESSION["usua_perm_modifica"] >= 1) {
                        ?>
                          <li><a href="radicacion/edtradicado.php?<?=$phpsession ?>&fechah=<?=$fechah?>&primera=1&ent=2" target='mainFrame' class="menu_princ">Modificaci&oacute;n</a></li>

                      <?php
                    }
                    
                    if ($_SESSION["usua_perm_prestamo"] == 1) {
                        ?>
                      <li class="dropdown-submenu"><a href="#" onclick="return false;">Prestamo</a> <ul class="dropdown-menu">  <li><a href="./prestamo/prestamo.php?opcionMenu=1"      target='mainFrame'>Prestamo de documentos</a></li>  <li><a href="./prestamo/prestamo.php?opcionMenu=2"      target='mainFrame'>Devolucion de documentos</a></li>    <li><a href="./prestamo/prestamo.php?opcionMenu=0"      target='mainFrame'>Generacion de reportes</a></li>  <li><a href="./prestamo/prestamo.php?opcionMenu=3"      target='mainFrame'>Cancelar solicitudes</a></li> </ul></li>
                     <?php } ?>

                        <?php if($_SESSION["USUA_PERM_RAD_ESPECIAL"]>=1) { ?>
                            <li><a href="reasigna_rad_new.php?<?=$phpsession ?>&<? echo "fechah=$fechah&usr=".md5($dep)."&primera=1&ent=1"; ?>" target='mainFrame' class="menu_princ">Reasignar Radicado    Especial</a></li>
                        <?php } ?>

                        <?php if($_SESSION["USUA_PERM_TRANS_RAD"]>=1) { ?>
                            <li><a href="Administracion/usuario/trasladar_radicados.php?<?=$phpsession ?>&<? echo "fechah=$fechah&usr=".md5($dep)."&primera=1&ent=1"; ?>" target='mainFrame' class="menu_princ">Transladar Radicados</a></li>
                        <?php } ?>

                    <?php if($_SESSION["usua_admin_archivo"]>=1) { ?>

                    <li class="dropdown-submenu"><a tabindex="-1" target="mainFrame">   Archivo </a> <ul class="dropdown-menu">
                            <?php
                        if ($_SESSION["usua_admin_archivo"] >= 1) {
                            $isql = "select count(1) as CONTADOR
                            from SGD_EXP_EXPEDIENTE
                            where
                            sgd_exp_estado=0 ";
                            $rs = $db->conn->Execute($isql);
                            $num_exp = $rs->fields["CONTADOR"];
                            ?>  

                            <li><a      href='./expediente/cuerpo_exp.php?<?=$phpsession?>&fechaf=<?=$fechah?>&carpeta=8&nomcarpeta=Expedientes&orno=1&adodb_next_page=1'       target='mainFrame'>Archivo (<?=$num_exp?>)</a></li>
                        <?php
                        }
                        if ($_SESSION["usua_admin_archivo"] >= 2) {
                            ?>
                            <li><a      href='archivo/archivo.php?<?=$phpsession?>&fechaf=<?=$fechah?>&carpeta=8&nomcarpeta=Expedientes&orno=1&adodb_next_page=1'       target='mainFrame'>Administracion Archivo F&iacute;sico</a></li>
                        <?php
                        }
                        if ($_SESSION["usua_perm_dev"] == 1) {
                            ?>
                            <li><a      href='devolucion/cuerpoDevCorreo.php?<?php echo "fechaf=$fechah&carpeta=8&devolucion=2&estado_sal=4&nomcarpeta=Documentos Para Impresion&orno=1&adodb_next_p    age=1"; ?>'         target='mainFrame' class="menu_princ">Dev Correo</a></li>
                            <?php }?>
                        </ul></li>
                    <?php
                    }
                    
                    // Edicion de Empresas sector y registradas JUAN CARLOS VILLALBA 2016/02/02
                    
                    if ($_SESSION['dependencia'] == 900 or $_SESSION['dependencia'] == 321 or $_SESSION['dependencia'] == 320) {
                        ?>

                    <li class="dropdown-submenu"><a tabindex="-1" target="mainFrame">Empresas</a> <ul class="dropdown-menu">
                            <?php
                        if ($_SESSION['dependencia'] == 900 or $_SESSION['dependencia'] == 321 or $_SESSION['dependencia'] == 320) {
                            $isql = "select count(1) as CONTADOR from bodega_empresas where activa=1";
                            $rs = $db->conn->Execute($isql);
                            $num_exp = $rs->fields["CONTADOR"];
                            
                            $isql2 = "select count(1) as CONTADOR2 from SGD_OEM_OEMPRESAS where sgd_oem_estado=1";
                            $rs = $db->conn->Execute($isql2);
                            $num_exp2 = $rs->fields["CONTADOR2"];
                            
                            ?>  
                            <!--<li><a href='./expediente/cuerpo_exp.php?<?=$phpsession?>&fechaf=<?=$fechah?>&carpeta=8&nomcarpeta=Expedientes&orno=1&adodb_next_page=1' target='mainFrame'>Archivo (<?=$num_exp?>)</a></li>-->     <li><a      href='./Administracion/tbasicas/empresas/adm_esp.php?><?=$phpsession?>&carpeta=8&nomcarpeta=Expedientes&orno=1&adodb_next_page=1'       target='mainFrame'>Empresa Sector (<?=$num_exp?>)</a></li>  <li><a      href='./Administracion/tbasicas/otrasempresas/adm_ente.php?<?=$phpsession?>&fechaf=<?=$fechah?>&carpeta=8&nomcarpeta=Expedientes&orno=1&adodb_next_page=1'      target='mainFrame'>Otras Empresas/Gobierno (<?=$num_exp2?>)</a></li>
                        
                        
                        <?php
                        }
                        {
                            ?>
                            
                        <?php
                        }
                        {
                            ?>
                            
                            <?php }?>
                        </ul></li>

            
                    <?php
                    }
            ?>
            </ul></li>
                            <!--// final-->

          <!-- 29/06/2018 CARLOS RICAURTE - NUEVO MENU STICKERS-->

                  <?php
            if ($permisoMenuStickers == 1) {
            ?> 
                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Stickers <b class="caret"></b></a>
                  <?php
                  if ($permisoSticker == 1) {
                  ?> 
                    <ul class="dropdown-menu">
                      <li><a href="./sticker/imprimeStickerExpediente.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'> Expediente por Radicado 
                          </a>
                      </li>
                      <li>
                        <a href='./sticker/imprimeStickerNuevo.php?krd=JH<?=$sendSession?>' target='mainFrame'>Generar Sticker Nuevo
                        </a>
                      </li>
                      <li>
                        <a href='./sticker/imprimeStickerExpedienteNuevo.php<?=$sendSession?>' target='mainFrame'>Generar Sticker Nuevo por expediente
                        </a>
                      </li>
                      <li>
                        <a href='./sticker/correspondencia.php<?=$sendSession?>' target='mainFrame'>Generar Sticker para correspondencia
                        </a>
                      </li>
                    </ul>
                  <?php 
                  } 
                  ?>      
                </li>
            <?php 
            } 

            if ($_SESSION["usua_admin_sistema"] != 1) {
              if ($_SESSION["USUA_PERM_ONLY_USER"] == 1) {
            ?> 
                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Administraci&oacute;n <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="./Administracion/usuario/index.php?<?=$sendSession?>" class="vinculos" target="mainFrame">Usuarios y Perfiles</a>
                    </li>
                                </ul>
                </li>
            <?php 
              } 
            } 
            ?>

          <?php 
          } 
          if($_SESSION["usua_admin_sistema"]==1 || $tiene_acceso_admin) 
            { 
          ?> <!-- Comienzo de administracion del sistema -->
                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"> Administraci&oacute;n <b class="caret"></b></a>
                                <ul class="dropdown-menu"> 
                  <li><a href="./Administracion/usuario/index.php?<?=$sendSession?>" class="vinculos" target="mainFrame">Usuarios y Perfiles</a></li>
                  <li><a href="./Administracion/comites/index.php?<?=$sendSession?>" class="vinculos" target='mainFrame'> Comités </a></li>
                  <li><a href="./Administracion/tbasicas/adm_tarifas.php?<?=$sendSession?>" class="vinculos" target='mainFrame'> Tarifas </a></li>
                                    <li><a href="./Administracion/tbasicas/adm_dependencias.php?<?=$sendSession?>" class="vinculos" target="mainFrame"> Dependencias </a></li>
                                    <li><a href="./Administracion/tbasicas/adm_nohabiles.php?<?=$sendSession?>" class="vinculos" target='mainFrame'> Dias no habiles </a></li>
                                    <li><a href="./Administracion/tbasicas/adm_fenvios.php?<?=$sendSession?>" class="vinculos" target='mainFrame'> Env&iacute;o de  correspondencia </a></li>
                                    <li><a href="./Administracion/tbasicas/adm_mensajeRapido.php?<?=$sendSession?>" class="vinculos" target='mainFrame'> Mensajes Rapidos </a></li>
                                    <li><a href="./Administracion/tbasicas/adm_tsencillas.php?<?=$sendSession?>" class="vinculos" target='mainFrame'> Tablas sencillas </a></li>
                                    <li><a href="./Administracion/tbasicas/adm_trad.php?<?=$sendSession?>&krd=<?=$krd?>" class="vinculos" target='mainFrame'> Tipos de  radicaci&oacute;n </a></li>
                                    <li><a href="./Administracion/tbasicas/adm_paises.php?<?=$sendSession?>" class="vinculos" target='mainFrame'> Pa&iacute;ses </a></li>
                                    <li><a href="./Administracion/tbasicas/adm_dptos.php?<?=$sendSession?>" class="vinculos" target='mainFrame'> Departamentos </a></li>
                                    <li><a href="./Administracion/tbasicas/adm_mcpios.php?<?=$sendSession?>" class="vinculos" target='mainFrame'> Municipios </a></li>
                                    <li><a href="./Administracion/tbasicas/adm_plantillas.php?<?=$sendSession?>" class="vinculos" target='mainFrame'> Plantillas </a></li>

                                    <!-- Agregado 23/12/2015 Nemesis -->
                                    <li><a href="./Administracion/tbasicas/adm_sec.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'> Reinicializacion de    Consecutivos </a></li>


                                </ul></li>
          <?php 
            } 
          ?> <!-- Fin de administracion del sistema -->

          <!-- 13/08/2021 CARLOS RICAURTE - MENU COBROS-->

          <?php
            if ($permisoMenuCobros == 1) {
            ?> 
                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Cobros Coactivos <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                      <?
                        if($permisoCobros == 1){
                      ?>
                          <li><a href="./cobrosCoactivos/cobro/ingresar.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'>Ingresar Cobro 
                              </a>
                          </li>
                          <li><a href="./cobrosCoactivos/cobro/modificar.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'>Mandamiento de Pago
                              </a>
                          </li>
                          <li><a href="./cobrosCoactivos/cobro-actividad/ingresar.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'>Obligaciones a Cobrar 
                              </a>
                          </li>
                          <li><a href="./cobrosCoactivos/procesos/ingresar.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'> Actividades 
                              </a>
                          </li>
                          <li><a href="./cobrosCoactivos/acumularProcesos/index.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'> Acumular</a>
                          </li>
                      <?
                        }
                      ?>
                      
                      <li><a href="./cobrosCoactivos/reportes/general.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'> Reporte 
                          </a>
                      </li>
                    </ul>
                </li>
            <?php 
            } 
          ?>

          <!-- FIN MENU COBROS-->
           <!-- MENU UTILITARIOS-->
          <?php                          
            if ($permisoMenuUtilitarios == 1 || $_SESSION["Seguridad_Actuaciones"] >= 1) {
            ?> 
                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Utilitarios <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                  <?php
                  if ($permisoMoverRadicados == 1) {
                  ?> 
                    
                    <li><a href="./Administracion/moverRadicados/index.php?krd=JH<?=$sendSession?>"
                        class="vinculos" target='mainFrame'> Mover Radicados 
                        </a>
                    </li>
                    <li><a href="./Administracion/descargarExpediente/index.php?krd=JH<?=$sendSession?>"
                        class="vinculos" target='mainFrame'> Descargar expedientes
                        </a>
                    </li>
                  <?php
                  } 
                  
                  if ($permisoMoverExpedientes == 1) {
                  ?> 

                    <li><a href="./Administracion/moverExpedientes/index.php?krd=JH<?=$sendSession?>"
                        class="vinculos" target='mainFrame'> Mover expedientes Masivos
                        </a>
                    </li>
                    
                  <?php
                  } 
                  if ($_SESSION["Seguridad_Actuaciones"] >= 1) {
                  ?> 

                    <li class="dropdown-submenu"><a href="#" onclick="return false;">Seguridad Actuaciones</a> 
                        <ul class="dropdown-menu">  
                            <li>
                                <a href="./seguridadActuacion/ingresar.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'> Crear Permisos</a>
                            </li>
                            <li>
                                <a href="./seguridadActuacion/listar.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'> Listar Permisos</a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="dropdown-submenu"><a href="#" onclick="return false;">Administrar Participaciones</a> 
                        <ul class="dropdown-menu">  
                            <li>
                                <a href="./formularioWeb/participacion/index.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'> Crear Participación</a>
                            </li>
                            <li>
                                <a href="./seguridadActuacion/listar.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'> Listar Permisos</a>
                            </li>
                        </ul>
                    </li>
                  <?php
                  } 
                  ?>
                </ul>
                </li>
            <?php 
            } 
            ?>            
          <!-- FIN MENU UTILITARIOS-->  
          <!-- 04/02/2019 CARLOS RICAURTE - MENU ACTUACIONES-->
          <?php
            if ($permisoActuanciones == 1 || $permisoCreaActuanciones == 1) {
            ?> 
                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Actuaciones <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                      <?
                        if($permisoCreaActuanciones == 1){
                      ?>
                          <li><a href="./actuaciones/actuacion/ingresar.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'> Ingresar actuación 
                              </a>
                          </li>
                          <li><a href="./actuaciones/actuacion/modificar.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'> Modifica actuación 
                              </a>
                          </li>
                          <li><a href="./actuaciones/actuacion-terceros/ingresar.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'> Asociar terceros a actuación 
                              </a>
                          </li>
                      <?
                        }
                      ?>
                      <li><a href="./actuaciones/actuacion-actividad/ingresar.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'> Actividades 
                          </a>
                      </li>
                      <li><a href="./actuaciones/reportes/general.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'> Reporte General 
                          </a>
                      </li>
                      <li><a href="./actuaciones/reportes/ejecutoriados.php?krd=JH<?=$sendSession?>" class="vinculos" target='mainFrame'> Reporte Ejecutoriados 
                          </a>
                      </li>
                    </ul>
                </li>
            <?php 
            } 
          ?>

          <!-- FIN MENU ACTUACIONES-->

                            <li class="dropdown"><a href="#" class="dropdown-toggle"
                                data-toggle="dropdown"> Bandejas <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                      <?=$link21show?>
                      <?=$link22show?>
                      <?=$entidad=='CRA'?$link23show:""?>
                      <?=$link1show?>
                      <?=$link11show?>
                      <?=$link4show?>
                      <?=$link5show?>
                      <?=$link6show?>
                      <?=$link7show?>
                      <?=$link2show?>
                      <?=$link3show?>
                      <?=$link27show?>
                      <li class="divider"></li>
                                        <li class="dropdown-submenu">
                        <?=$link8show?>
                        <ul class="dropdown-menu">
                          <?=$link9show?>
                          <?=$link10show?>
                        </ul>
                                        </li>
                                </ul></li>

                  <?php if ($_SESSION["USUA_PERM_ENRUTADOR_TRD"]==1) {  ?> 
                  <li class="dropdown"><a href="#"
                                class="dropdown-toggle" data-toggle="dropdown"> Migraciones <b
                                    class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="./Etrd/tipificacion1.php?<?=$sendSession?>" class="vinculos" target="mainFrame">Enrutador TRD </a></li>
                                </ul></li>
                  <?php } ?>

                  <? if(!empty($linkrad))
                  {
                  ?>
                  <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Radicaci&oacute;n<b class="caret"></b></a>
                                  <ul class="dropdown-menu">
                                 <?php
                   
                    //if (!empty($_SESSION["USUA_ATC_CIU"])) {
                   //CARLOS RICAURTE 2018-10-29 PERMISO PARA ATENCION A CIUDADANOS
                   if (!empty($atencioCiudadanos)) {
                        //$urlInicio = "atencion/index.php";

                        echo "<li><a href=\"./atencion/index.php\" class=\"vinculos\" target=\"mainFrame\">
                      Atencion al Ciudadano</a></li>";
                    }
                    ?>
                      <?=$linkrad?>
                    </ul></li>
                  <?}?>

                </ul>

                        <ul class="nav navbar-nav navbar-right navbar-user">

                        <li class="dropdown"><a href="#" onclick="return false;"
                                data-toggle="dropdown" class="dropdown-toggle"> Opciones <b
                                    class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="https://nam02.safelinks.protection.outlook.com/?url=https%3A%2F%2Fcrapsb.sharepoint.com%2Fsites%2Fsvrnas%2FDocumentos%2520compartidos%2FForms%2FAllItems.aspx%3Fid%3D%252Fsites%252Fsvrnas%252FDocumentos%2520compartidos%252FCalidad%252FPlantillas%2520CRA%26viewid%3D1d384d77-79ff-4af8-a3a4-72453c1423d0&data=05%7C02%7Ccricaurte%40cra.gov.co%7Ce083f03916c04132cc1208dc47610242%7C0f25ac5d820e433e84606c0c6dd4e7e8%7C1%7C0%7C638463729235245543%7CUnknown%7CTWFpbGZsb3d8eyJWIjoiMC4wLjAwMDAiLCJQIjoiV2luMzIiLCJBTiI6Ik1haWwiLCJXVCI6Mn0%3D%7C0%7C%7C%7C&sdata=avSCC5CLO0xBGyspoQLBcFV9mvxqrAWps4XPI6RSrsc%3D&reserved=0" target="_blank"> Ayuda y Plantillas </a></li>
                                </ul></li>

                            <li class="dropdown user-dropdown"><a href="#"
                                class="dropdown-toggle" data-toggle="dropdown"><i
                                    class="fa fa-user"></i> <?=$nombUser?> <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="mod_datos.php?<?="&fechah=$fechah&info=false"?>" target="mainFrame"><i class="fa fa-user"></i> Perfil </a></li>

  <?php

$isql = "SELECT u.usua_login,u.USUA_CODI,d.depe_codi, d.depe_nomb, u.usua_nomb, u.USUA_EMAIL 
                          FROM USUARIO u, dependencia d
                            WHERE u.depe_Codi=d.depe_codi and upper(u.usua_email) = '" . strtoupper($_SESSION["usua_email"]) . "' and u.usua_email is not null
                                  and u.usua_esta = '1' ";
 //$db->conn->debug = true;
$rs = $db->conn->query($isql);
while (! $rs->EOF) {
    $cambioKrd = $rs->fields["USUA_LOGIN"];
    $cambioUsuaCodi = $rs->fields["USUA_CODI"];
    $cambioDepeCodi = $rs->fields["DEPE_CODI"];
    $cambioDepeNomb = $rs->fields["DEPE_NOMB"];
    $emailUs = $rs->fields["USUA_EMAIL"];
    ?>
                      <li><a href="index_frames.php?<?="&fechah=$fechah&info=false&cambioKrd=$cambioKrd&cambioUsuaCodi=$cambioUsuaCodi&cambioDepeCodi=$cambioDepeCodi"?>"><i    class="fa fa-user"></i> <?="$cambioDepeCodi - $cambioDepeNomb ($cambioKrd) - $emailUs" ?> </a></li>
                      <?
    $rs->MoveNext();
}
?>
                     <? if ($_SESSION["autentica_por_LDAP"] != 1){?>
                      <li><a href='contraxx.php?<?="&fechah=$fechah"?>' target=mainFrame> Cambio de clave </a></li>
                      <? } ?>
                      <li class="divider"></li>
                                    <li><a href="cerrar_session.php?"><i class="fa fa-power-off"></i>   Salir </a></li>
                                </ul></li>

                        </ul>

                    </div>
                </div>
                <!-- /.navbar-collapse -->
            </nav>
        </div>
        <div class="row">
        <div class="col-xs-12 col-sm-12"">
            <iframe name='mainFrame' id='mainFrame' frameBorder="0" width="100%" style="margin-top: 15px; margin-bottom: 10px;"
                height="90%" src='<?php echo $urlInicio?>' /></iframe>
        </div><
        </div>

    </div>
</body>
</html>
