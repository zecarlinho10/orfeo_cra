<?php

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/


ini_set('memory_limit', '256M');

$ruta_raiz = "../../";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once($ruta_raiz . "include/db/ConnectionHandler.php");
include_once "$ruta_raiz/tx/diasHabiles.php";
include_once "$ruta_raiz/include/utils/Calendario.php";

ob_start();
$db = new ConnectionHandler($ruta_raiz);
ob_end_clean();

if (!$db || !$db->conn) {
    die("Error de conexión a la base de datos.");
}
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

if (empty($_SESSION)) include $ruta_raiz . "rec_session.php";


$fecha_ini = isset($_GET['fecha_ini']) ? $_GET['fecha_ini'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

if (empty($fecha_ini) || empty($fecha_fin)) {
    die("Las fechas de inicio y fin son requeridas.");
}

$fecha_ini = $fecha_ini . " 00:00:00";
$fecha_fin = $fecha_fin . " 23:59:59";
$proyecto_id = isset($_GET['proyecto_id']) ? $_GET['proyecto_id'] : '';

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


if (isset($_GET['reporte_1'])) {
    include('getPregunta1.php');
}
elseif (isset($_GET['reporte_2'])) {
    include('getPregunta2.php');
}

?>