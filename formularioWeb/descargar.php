<?php
include_once realpath(dirname(__FILE__) . "/../") . '/clasesComunes/Usuario.php';
include_once realpath(dirname(__FILE__) . "/../") . '/config.php';
include_once  realpath(dirname(__FILE__) . "/../") . "/atencion/conf_form.php";

session_start();
if (strcasecmp($_POST["formtk"], $_SESSION["idFormulario"]) != 0 || empty($_POST["rad"])) {
    ?>
    
<script type="text/javascript">
<!--
	window.location='./';
//-->
</script>
<?php
    // Deshabilitada mientras se pueban otras cosas
    // return;
} else {
    session_destroy();
    $usuario = new Usuario();
    $userRadica = $usuario->loadUsuario($usuarioRadica);
    $year = substr($_POST["rad"], 0, 4);
    $fileArchi = BODEGA . "/" . $year . "/" . $userRadica["DEPE_CODI"] . "/" . $_POST["rad"] . ".pdf";
 
    if (file_exists($fileArchi)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileArchi));
        ob_clean();
        // flush();
        ob_end_flush();
        readfile($fileArchi);
        exit();
    }
}
?>
