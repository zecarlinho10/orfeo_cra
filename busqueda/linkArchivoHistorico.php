<?php
 /*
  * Invocado por una funcion javascript (funlinkArchivo(ruta_raiz,path))
  * Consulta el path del radicado 
  * @author CARLOS RICAURTE
  * @since 7/12/2021
  * @category imagenes
 */
session_start();
include_once ("../config.php");

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;
$krd                = $_SESSION["krd"];


         $file = BODEGA."/historico". $path;
         //echo $file;

$fileArchi = $file;
$tmpExt = explode('.',$path);
$es_pdf = ($tmpExt[1] == 'pdf')? 'pdf' : null;
$filedatatype = ($es_pdf)? $es_pdf : $path;



  if (file_exists($fileArchi)) {
    header('Content-Description: File Transfer');
    
    switch($filedatatype) {
         case 'odt':
               header('Content-Type: application/vnd.oasis.opendocument.text');
               break;
         case 'doc':
               header('Content-Type: application/msword');
               break;
         case 'tif':
               header('Content-Type: image/TIFF');
               break;
         case 'pdf':
               header('Content-Type: application/pdf');
               break;  
         case 'xls':
               header('Content-Type: application/vnd.ms-excel');
               break;
         case 'csv':
               header('Content-Type: application/vnd.ms-excel');
               break;
         case 'ods':
               header('Content-Type: application/vnd.ms-excel');
               break;  
         case 'html':
               header('Content-Type: text/html');
               break; 
         default :
              header('Content-Type: application/octet-stream');
              break;  
        }
         
        if ($filedatatype == 'html') {
            header('Content-Disposition: inline; filename='.basename($file));
         }else{
            header('Content-Disposition: attachment; filename='.basename($file));
        }

        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        //flush();
        ob_end_flush();
        
        readfile($file);
        exit;
    }else {
       echo '<script language="javascript">alert("'.$file.'");</script>';
       die ("<B><center>  NO se encontro el Archivo  </a><br>");
    }

?>
