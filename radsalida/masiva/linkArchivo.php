<?php
session_start();
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

  echo "<br> numrad:" . $numrad;

  $fileArchi=$numrad;
  echo "<br> fileArchi:" . $fileArchi;
  if (file_exists($fileArchi)) {
    echo "<br> entra:";
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename='.basename($fileArchi));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($fileArchi));
    ob_clean();
    flush();
    ob_end_flush();
    readfile($fileArchi);
    exit;
  }
  else{
    echo "<br> pailas";
  }
?>
