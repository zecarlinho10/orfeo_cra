<?php
session_start();
if (!$_SESSION['dependencia']) header ("Location: ../cerrar_session.php");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>JS Bin</title>
</head>
<body style='background:green'>
  Hola, éste es un test para tomar una screenshot y descargarla.
  <br/><br/>
  <a id="download">Tomar screenshot y descargar</a>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
  <script>
        html2canvas(document.body, {
        onrendered (canvas) {
            var link = document.getElementById('download');;
            var image = canvas.toDataURL();
            link.href = image;
            link.download = 'screenshot.png';
        }
        });

  </script>
</body>
</html>