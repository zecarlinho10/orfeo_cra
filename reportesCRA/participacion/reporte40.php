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

ob_start();
$db = new ConnectionHandler($ruta_raiz);
ob_end_clean();

if (!$db || !$db->conn) {
    die("Error de conexión a la base de datos.");
}
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

if (empty($_SESSION)) include $ruta_raiz . "rec_session.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generar Reporte Excel</title>
</head>
<body>
    <h1>Generar Reporte Excel</h1>
    <form method="GET" action="generar_reporte.php">
        <label for="fecha_ini">Fecha de Inicio:</label>
        <input type="date" name="fecha_ini" id="fecha_ini" required><br><br>

        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="date" name="fecha_fin" id="fecha_fin" required><br><br>

        <?
            $sql = "SELECT IDPROYECTO, DESC_PROYECTO FROM FLDOC.PC_PROYECTO_REGULATORIO";

            $rs=$db->conn->query($sql);
            $proyectos = array();
            if ($rs) {
                echo '<select name="proyecto_id">'; // Abre el select

                while (!$rs->EOF) {
                    $idProyecto = $rs->fields['IDPROYECTO'];
                    $descProyecto = $rs->fields['DESC_PROYECTO'];

                    echo '<option value="' . $idProyecto . '">' . $descProyecto . '</option>'; // Genera cada opción

                    $rs->MoveNext();
                }

                echo '</select>'; // Cierra el select
            } else {
                echo "Error en la consulta: " . $db->conn->ErrorMsg();
            }

        ?>
        <br><br>
        <button type="submit" name="reporte_1">Reporte CRA</button>
        <br><br>
        <button type="submit" name="reporte_2">REG-FOR03 Formato registro</button>
    </form>
</body>
</html>