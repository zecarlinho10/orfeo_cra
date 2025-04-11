<?php
require_once ($ruta_raiz . '/include/CRA_phpmailer/class.phpmailer.php');
include ($ruta_raiz . "/conf/configPHPMailer.php");

        $query = "select u.USUA_EMAIL from usuario u '";
        $rs = $db->conn->query($query);
        while (! $rs->EOF) {
            $radicado = $rs->fields["USUA_EMAIL"];
            $rs->MoveNext();
        }

?>
