<?php
session_start();

// faltaba parametro CAPTURAR valores 05/01/2016
$dep_sel = $_REQUEST["dep_sel"];
$estado_sal = $_REQUEST["estado_sal"];
//

$verrad = "";
$ruta_raiz = "..";
if (empty($_SESSION["dependencia"]))
    include "$ruta_raiz/rec_session.php";

if (empty($dep_sel)) {
    $dep_sel = $_SESSION["dependencia"];
}
?>
<html>
<head>
<title>Envio de Documentos. Orfeo...</title>
<?php include_once "../htmlheader.inc.php"; ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../estilos/orfeo.css">
<link rel="stylesheet" type="text/css"
	href="../js/spiffyCal/spiffyCal_v2_1.css" />
</head>
<body bgcolor="#FFFFFF" topmargin="0" onLoad="window_onload();">
	<div id="spiffycalendar" class="text"></div>
<?php
$ruta_raiz = "..";
include_once "../include/db/ConnectionHandler.php";
$db = new ConnectionHandler("$ruta_raiz");


if (empty($_REQUEST["carpeta"])) {
    $carpeta = 0;
} else {
    $carpeta = $_REQUEST["carpeta"];
}
if (empty($_REQUEST["estado_sal"])) {
    $estado_sal = 2;
} else {
    $estado_sal = $_REQUEST["estado_sal"];
}
if (empty($_REQUEST["estado_sal_max"])) {
    $estado_sal_max = 3;
} else {
    $estado_sal_max = $_REQUEST["estado_sal_max"];
}

if ($estado_sal == 3) {
    $accion_sal = "Re-Envio de Documentos";
    $pagina_sig = "reenviomass.php";
    $nomcarpeta = "Radicados Para Envio";
    
    if (! empty($dep_sel) && $dep_sel != "9999") {
        $dependencia_busq1 = " and r.radi_depe_radi = $dep_sel ";
        $dependencia_busq2 = " and r.radi_depe_radi = $dep_sel";
    }else{
        $dependencia_busq2 = " ";
    }
}
if (empty($_REQUEST["orden_cambio"])) {
    $orden_cambio = 1;
} else {
    $orden_cambio = $_REQUEST["orden_cambio"];
}
if (empty($_REQUEST["$orderTipo"])) {
    $orderTipo = "";
} else 
    if ($_REQUEST["orderTipo"] == 1) {
        $orderTipo = "desc";
    }

if ($orden_cambio == 1) {
    if (! $orderTipo) {
        $orderTipo = "desc";
    } else {
        $orderTipo = "";
    }
}
$encabezado = "krd=$krd&estado_sal=$estado_sal&estado_sal_max=$estado_sal_max&accion_sal=$accion_sal&dependencia_busq2=$dependencia_busq2&dep_sel=$dep_sel&filtroSelect=$filtroSelect&tpAnulacion=$tpAnulacion&nomcarpeta=$nomcarpeta&orderTipo=$orderTipo";
$linkPagina = $_SERVER["PHP_SELF"] . "?$encabezado&orderNo=$orderNo";
$swBusqDep = "si";
$carpeta = "nada";
include "../envios/paEncabeza.php";
$pagina_actual = "../envios/reenviomass.php";
$varBuscada = "radi_nume_sal";
include "../envios/paBuscar.php";
$pagina_sig = "../envios/enviamass.php";
include "../envios/paOpciones.php";

/*
 * GENERACION LISTADO DE RADICADOS Aqui utilizamos la clase adodb para generar el listado de los radicados Esta clase cuenta con una adaptacion a las clases utiilzadas de orfeo. el archivo original es adodb-pager.inc.php la modificada es adodb-paginacion.inc.php
 */
?>
  <form name="formEnviar"
		action='../envios/enviamass.php?<?=$encabezado?>' method="post">
		<div class="row">
			<div class="col-xs-12 col-sm-5 col-md-12 col-lg-12">
 <?php
if ($orderNo == 98 or $orderNo == 99) {
    $order = 1;
    if ($orderNo == 98)
        $orderTipo = "desc";
    if ($orderNo == 99)
        $orderTipo = "";
} else {
    if (! $orderNo) {
        $orderNo = 3;
        $orderTipo = "desc";
    }
    $order = $orderNo + 1;
}

if(!empty($_REQUEST["busqRadicados"])){
$radicados =" AND d.radi_nume_sal in (".$_REQUEST["busqRadicados"].")";
}else{
    $radicados = "";
}
include "$ruta_raiz/include/query/envios/queryCuerpoReenviomass.php";
$rs = $db->conn->Execute($isql);
// $nregis = $rs->recordcount();
if (! $rs->fields["IMG_RADICADO SALIDA"]) {
    echo "<table class=borde_tab width='100%'><tr><td class=titulosError><center>NO se encontro nada con el criterio de busqueda</center></td></tr></table>";
} else {
    $pager = new ADODB_Pager($db, $isql, 'adodb', true, $orderNo, $orderTipo);
    $pager->toRefLinks = $linkPagina;
    $pager->toRefVars = $encabezado;
    $pager->Render($rows_per_page = 20, $linkPagina, $checkbox = "chkEnviar");
}
?>
 </div>
		</div>
	</form>

</body>

</html>
