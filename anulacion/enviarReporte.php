<?
session_start();
/**
 * Se añadio compatibilidad con variables globales en Off
 * @autor 201201 Correlibre.org
 * Liliana G, Ricardo P, Jairo L
 * @licencia GNU/GPL V 3
 */
define('ADODB_ASSOC_CASE', 2);
$nomcarpeta = $_GET["carpeta"];
$tipo_carpt = $_GET["tipo_carpt"];
$adodb_next_page = $_GET["adodb_next_page"];
if ($_GET["orderNo"])
    $orderNo = $_GET["orderNo"];
if ($_GET["orderTipo"])
    $orderTipo = $_GET["orderTipo"];
if ($_GET["busqRadicados"])
    $busqRadicados = $_GET["busqRadicados"];
if ($_GET["busq_radicados"])
    $busq_radicados = $_GET["busq_radicados"];
if ($_GET["depeBuscada"])
    $depeBuscada = $_GET["depeBuscada"];
if ($_GET["filtroSelect"])
    $filtroSelec = $_GET["filtroSelec"];
if ($_GET["checkValue"])
    $checkValue = $_GET["checkValue"];
if ($_GET["radicadosSel"])
    $radicadosSel = $_GET["radicadosSel"];
foreach ($_POST as $key => $valor)
    ${$key} = $valor;

$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];
$usua_nomb = $_SESSION['usua_nomb'];
$depe_nomb = $_SESSION['depe_nomb'];
$checkValue = $_POST['checkValue'];
$depe_codi_territorial = $_SESSION['depe_codi_territorial'];
$ruta_raiz = "..";
// Variable para de vigencia del radicado
$vigente = true;
?>
<html>
<head>
<title>Enviar Datos</title>
<link rel="stylesheet" href="../estilos/orfeo.css">
<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
</head>
<style type="text/css">
<!--
.textoOpcion {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 8pt;
	color: #000000;
	text-decoration: underline
}
-->
</style>

<body bgcolor="#FFFFFF" topmargin="0">
<?
/*
 * RADICADOS SELECCIONADOS
 * @$setFiltroSelect Contiene los valores digitados por el usuario separados por coma.
 * @$filtroSelect Si SetfiltoSelect contiene algun valor la siguiente rutina
 * realiza el arreglo de la condificacion para la consulta a la base de datos y lo almacena en whereFiltro.
 * @$whereFiltro Si filtroSelect trae valor la rutina del where para este filtro es almacenado aqui.
 */
$radicadosXAnular = "";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler("$ruta_raiz");
if ($checkValue) {
    $num = count($checkValue);
    $i = 0;
    while ($i < $num) {
        $estaRad = false;
        $record_id = key($checkValue);
        $estaRad = false;
        // Si esta el radicado entonces verificar vigencia
        if ($estaRad) {
            // Si se encuentra vigente entonces no se puede anular
            if ($vigente) {
                $arregloVigentes[] = $record_id;
            } else {
                $setFiltroSelect .= $record_id;
                $radicadosSel[] = $record_id;
                $radicadosXAnular .= "'" . $record_id . "'";
            }
        } else {
            $setFiltroSelect .= $record_id;
            $radicadosSel[] = $record_id;
        }
        
        if ($i <= ($num - 2)) {
            if (! $vigente || ! $estaRad) {
                $setFiltroSelect .= ",";
            }
            if ($estaRad && ! empty($radicadosXAnular)) {
                $radicadosXAnular .= ",";
            }
        }
        next($checkValue);
        $i ++;
        // Inicializando los valores de comprobacion
        $estaRad = false;
        $vigente = true;
    }
    if ($radicadosSel) {
        $whereFiltro = " and b.radi_nume_radi in($setFiltroSelect)";
    }
}
// ystemDate = $db->conn->OffsetDate(0,$db->conn->sysTimeStamp);
$systemDate = $db->conn->OffsetDate(0, $db->conn->sysTimeStamp);
include ('../config.php');
include_once "$ruta_raiz/include/tx/Anulacion.php";
include_once "$ruta_raiz/include/tx/Historico.php";
// Se vuelve crear el objeto por que saca un error con el anterior

$db = new ConnectionHandler("$ruta_raiz");
$Anulacion = new Anulacion($db);
$observa = "Solicitud Anulacion. $observa";

/*
 * Sentencia para consultar en sancionados el estado en que se encuentra el radicado
 * A = Anulado, V = Vigente, B = Estado temporal
 * Si el estado del radicado en sancionados es diferente de V puede realizar la sancion
 */
// Si por lo menos hay un radicado por anular
if (! empty($radicadosSel[0])) {
    $radicados = $Anulacion->solAnulacion($radicadosSel, $dependencia, $usua_doc, $observa, $codusuario, $systemDate);
    $fecha_hoy = date("Y-m-d");
    $dateReplace = $db->conn->OffsetDate(0);
    // $db->conn->SQLDate("Y-m-d","$fecha_hoy");
    $Historico = new Historico($db);
    /**
     * Funcion Insertar Historico
     * insertarHistorico($radicados,
     * $depeOrigen,
     * $usCodOrigen,
     * $depeDestino,
     * $usCodDestino,
     * $observacion,
     * $tipoTx)
     */
    
    $radicados = $Historico->insertarHistorico($radicadosSel, $dependencia, $codusuario, $depe_codi_territorial, 1, $observa, 25);
    // Se archiva cuando se solicita la anulacion, se guarda en el historico
    if ($_SESSION['entidad'] == 'CRA') {
        $radicados = $Historico->insertarHistorico($radicadosSel, $dependencia, $codusuario, 999, 1, "Se archiva por solicitud de anulacion", 13);
    }
}

?>
	<form action='enviardatos.php<?="&krd=$krd" ?>' method="post"
		name="formulario">
		<div class="row">
			<div class="col-md-6">
				<div class="row">
					<div class="text-center col-md-12">
						<h2>
							<span>ACCION REQUERIDA COMPLETADA</span>
						</h2>
					</div>
				</div>
				<div class="row">
					<div class="text-center col-md-6">
						<h4>
							<span>ACCION REQUERIDA :</span>
						</h4>
					</div>
					<div class="text-center col-md-6">
						<span>Solicitud de Anulacion de Radicados</span>
					</div>
				</div>
				<div class="row">
					<div class="text-center col-md-6">
						<h4>
							<span>RADICADOS INVOLUCRADOS :</span>
						</h4>
					</div>
					<div class="text-center col-md-6">
						<span>
<?php
if (! empty($radicados[0])) {
    foreach ($radicados as $noRadicado) {
        echo "<div class='row'><div class='col-md-4'>" . $noRadicado . "</div></div>";
    }
}
if (! empty($arregloVigentes[0]) && $arregloVigentes[0] != "") {
    echo '<p>
			<font color="red">
			Lista de Radicados que No se pueden Anular ya que se encuentran vigentes en sancionados
			</font>
			</p>';
    echo '<font color="red">';
    foreach ($arregloVigentes as $radicado) {
        echo "<div class='row'>" . "< div class='col-md-4'>" . $radicado . "</div></div>";
    }
    echo '</font>';
}
?>
	</span>
					</div>
				</div>
				<div class="row">
					<div class="text-center col-md-6">
						<h4>
							<span>USUARIO DESTINO :</span>
						</h4>
					</div>
					<div class="text-center col-md-6">
						<span>Usuario Anulador</span>
					</div>
				</div>
				<div class="row">
					<div class="text-center col-md-6">
						<h4>
							<span>FECHA Y HORA:</span>
						</h4>
					</div>
					<div class="text-center col-md-6">
						<span><?php echo date("d-m-Y h:i:s")?></span>
					</div>
				</div>
				<div class="row">
					<div class="text-center col-md-6">
						<h4>
							<span>USUARIO ORIGEN</span>
						</h4>
					</div>
					<div class="text-center col-md-6">
						<span><?php echo $usua_nomb?></span>
					</div>
				</div>
				<div class="row">
					<div class="text-center col-md-6">
						<h4>
							<span>DEPENDENCIA ORIGEN</span>
						</h4>
					</div>
					<div class="text-center col-md-6">
						<span><?php echo $depe_nomb?></span>
					</div>
				</div>
			</div>
		</div>
	</form>
</body>