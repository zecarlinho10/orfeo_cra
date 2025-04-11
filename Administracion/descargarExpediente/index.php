<?php
//ini_set("display_errors", 1);
//error_reporting(E_ALL);

include_once "../../config.php";

session_start();

$ruta_raiz = ".";
if (!$_SESSION['dependencia'] || $_GET['close']) {
    header("Location: " . ABSOL_PATH . "/login.php");
    echo "<script>parent.frames.location.reload();top.location.reload();</script>";
}

if (!$_SESSION['dependencia']) header("Location: " . ABSOL_PATH . "/cerrar_session.php");
foreach ($_GET as $key => $valor) ${$key} = $valor;
foreach ($_POST as $key => $valor) ${$key} = $valor;


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar ZIP</title>
    <script>
        function generarZip() {
            document.getElementById("linkDescarga").innerHTML = "Generando ZIP, por favor espere...";
            
            // Obtener el valor del expediente
            var expediente = document.getElementById("expediente").value;

            // Crear un formulario dinámicamente y enviarlo por POST
            var form = document.createElement("form");
            form.setAttribute("method", "post");
            form.setAttribute("action", "generar_zip.php");

            var input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("name", "expediente");
            input.setAttribute("value", expediente);

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();

            document.getElementById("linkDescarga").innerHTML = "Descarga iniciada.";
        }
    </script>
</head>
<body>
    <h2>Generar Archivo ZIP</h2>
    <label for="expediente">Número de Expediente:</label>
    <input type="text" id="expediente" name="expediente"><br><br>
    <button onclick="generarZip()">Generar ZIP</button>
    <p id="linkDescarga"></p>
</body>
</html>