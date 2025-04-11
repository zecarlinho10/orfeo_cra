<?php

#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#error_reporting(E_ALL);


$ruta = __dir__;
$carpetaAnterior = dirname($ruta);
$raiz = dirname($carpetaAnterior);

if (php_sapi_name() !== 'cli') {
    die("COMISION DE REGULACION DE AGUA Y SANEAMIENTO BASICO CRA");
}


if (!session_id()) {
    session_start();
}

if (empty($_SESSION['token'])) {
    if (function_exists('mcrypt_create_iv')) {
        $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    } else {
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}

$token = $_SESSION['token'];

$ADODB_COUNTRECS = false;
require_once ($raiz . "/include/db/ConnectionHandler.php");
include_once ($raiz . "/tx/diasHabiles.php");
require_once ($raiz . "/radiMailAUTH/envios/EnvioCorreos.php");


$_SESSION["idFormulario"] = sha1(microtime(true) . mt_rand(10000, 90000));

$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

$Dha = new FechaHabil($db);
$fechaHoy = date("Y-m-d");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tabla de Radicados</title>
</head>
<body>

<h2>Radicados con menos de 3 días para vencer</h2>

<br>
<table>
  <tr>
    <th>Ítem</th>
    <th>Radicado</th>
    <th>Fecha de Radicación</th>
    <th>Fecha de Vencimiento</th>
    <th>Días Hábiles para Vencimiento</th>
    <th>Asunto</th>
    <th>Usuario Actual</th>
  </tr>
  <?
    include ($raiz . "/radiMailAUTH/alertas/usuarios.php");
    $enviador = new EnvioCorreos();
    $correos = array();
    foreach ($usuarios as $usuario) {
        echo ("Entra1 : " . $usuario );
        //include ("sentencia.php");
        include ($raiz . "/radiMailAUTH/alertas/sentencia.php");
	$rsql= $db->conn->Execute($sql);

         $cRad=1;

        //Iteramos
        $asuntoMailRadicado="Informe diario de bandejas por vencer en ORFEO";
        include ($raiz . "/radiMailAUTH/alertas/cuerpoInforme.php");
	while(!$rsql->EOF){
	    
            $RADI_NUME_RADI  = $rsql->fields['RADI_NUME_RADI'];
            $diasrestantes = $Dha->diasHabiles($fechaHoy,$rsql->fields['FECH_VCMTO']);
            if($diasrestantes<=3){
                echo "<tr>";
                echo "<td>" . $cRad . "</td>";
                echo "<td>" . $RADI_NUME_RADI . "</td>";
                echo "<td>" . $rsql->fields['RADI_FECH_RADI'] . "</td>";
                echo "<td>" . $rsql->fields['FECH_VCMTO'] . "</td>";
                echo "<td>" . $diasrestantes . "</td>";
                echo "<td>" . $rsql->fields['RA_ASUN'] . "</td>";
                echo "<td>" . $rsql->fields['USUA_NOMB'] . "</td>";
                echo "</tr>";
                $cuerpo .= "<tr><td>" . $cRad . "</td>
               <td>" . $rsql->fields['RADI_NUME_RADI'] . "</td>
               <td>" . $rsql->fields['RADI_FECH_RADI'] . "</td>
               <td>" . $rsql->fields['FECH_VCMTO'] . "</td>
               <td style='" . ($diasrestantes < 0 ? "color: red;" : "") . "'>" . $diasrestantes . "</td>
               <td>" . $rsql->fields['RA_ASUN'] . "</td>
	       <td>" . $rsql->fields['USUA_NOMB'] . "</td></tr>";
		
		echo ("Entra2 : " . $usuario );

		$correos = $usuario['correo'];

               $cRad++;
            }
            $rsql->MoveNext();
        }
	$cuerpo .= "</tbody></table></body></html>";
	echo ($correos);
        $enviador->enviarCorreo($correos, $asuntoMailRadicado, $cuerpo);
        $conCopia = $usuario['correo'];
        echo "<br>dependencia: " . $usuario['dependencia'] . "<br>correos:" ;
        sleep(10);
        echo "sleeeeeep";
    }
        

?>
</body>
</html>

