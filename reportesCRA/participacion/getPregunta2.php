<?

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


	require 'vendor/autoload.php';  // Carga PhpSpreadsheet

	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;

	ini_set('memory_limit', '512M');
    ini_set('max_execution_time', '300');

    // 📌 Ruta del archivo original
    $archivoEntrada = BODEGA . "/tmp/participacion/REG-FOR03.xlsx";
    $archivoSalida = BODEGA . "/tmp/participacion/REG-FOR03-NUEVO.xlsx";

    if (!file_exists($archivoEntrada)) {
        die("El archivo de plantilla no existe: $archivoEntrada");
    }

    // 📌 Cargar el archivo original
    $reader = IOFactory::createReader('Xlsx');
    $reader->setIncludeCharts(true);
    $spreadsheet = $reader->load($archivoEntrada);
    $sheet = $spreadsheet->getActiveSheet();

    // 📌 Fijar la fila de inicio en 4 (sin buscar la primera vacía)
    $filaInicio = 4;

    // 📌 Agregar nuevas filas sin afectar los títulos
    include_once ($ruta_raiz .'/reportesCRA/participacion/sqlGetReporte2.php');
    //die ("sql:" . $sql);

    $rs = $db->query($sql);

    if (!$rs) {
        die("Error en la consulta SQL: " );
    }


    while (!$rs->EOF) {
	    $sheet->setCellValue('A' . $filaInicio, $filaInicio - 3); // ID
	    $sheet->setCellValue('B' . $filaInicio, $rs->fields['RADICADO']);
	    $sheet->setCellValue('C' . $filaInicio, $rs->fields['RADI_FECH_RADI']);
	    $sheet->setCellValue('D' . $filaInicio, $rs->fields['SGD_DIR_NOMREMDES']);
	    $sheet->setCellValue('E' . $filaInicio, $rs->fields['MUNI_NOMB']);
	    $sheet->setCellValue('F' . $filaInicio, $rs->fields['SGD_ATC_TIPO']);
	    $sheet->setCellValue('G' . $filaInicio, $rs->fields['PREGUNTA']);
	    $sheet->setCellValue('H' . $filaInicio, 'Formulario Web');
	    $sheet->setCellValue('I' . $filaInicio, $rs->fields['TIPOPREGUNTA']);

	    $filaInicio++;
	    $rs->MoveNext();
	}

	//  Guardar el archivo sin perder formato ni imágenes
	$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save($archivoSalida);

    // 📌 Verificar que el archivo se generó correctamente
    if (!file_exists($archivoSalida) || filesize($archivoSalida) == 0) {
        die("El archivo de salida no se generó correctamente.");
    }

    // 📌 Forzar la descarga del archivo generado
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="REG-FOR03-NUEVO.xlsx"');
    header('Content-Length: ' . filesize($archivoSalida));
    readfile($archivoSalida);
    exit;


?>