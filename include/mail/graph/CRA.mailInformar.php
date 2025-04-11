<?php
require_once ($ruta_raiz . '/include/CRA_phpmailer/class.phpmailer.php');
include ($ruta_raiz . "/conf/configPHPMailer.php");

setlocale(LC_TIME, 'es_ES.UTF-8');
$fecha_actual_larga = strftime("%A, %d de %B de %Y %H:%M");
$fecha_actual_corta = date("d/m/y H:i:s");
if (! isset($envioDigital)) {
    if ($usuaCodiMail == null or $depeCodiMail == null) {
        $mailDestino = $_emailUser;
    } else {
        $query = "select u.USUA_EMAIL
			from usuario u
			where u.USUA_CODI ='$usuaCodiMail' and  u.depe_codi='$depeCodiMail'";
        
        $rs = $db->conn->query($query);
        $mailDestino = $rs->fields["USUA_EMAIL"];
    }
    $queryPath = "select RADI_NUME_RADI, RADI_PATH
		from RADICADO
		where RADI_NUME_RADI IN($radicadosSelText 0)";
    $rsPath = $db->conn->query($queryPath);
    $linkImagenesTmp = "";
    if ($rsPath) {
        while (! $rsPath->EOF) {
            $radicado = $rsPath->fields["RADI_NUME_RADI"];
            $radicadoPath = $rsPath->fields["RADI_PATH"];
            if (trim($radicadoPath)) {
                $linkImagenesTmp .= "<a href='" . $servidorOrfeoBodega . "$radicadoPath'>Imagen Radicado $radicado </a><br>";
            } else {
                $linkImagenesTmp .= "Radicado $radicado sin documetno Asociado<br>";
            }
            $rsPath->MoveNext();
        }
    }
} else {
    $queryPath = "select RADI_NUME_RADI, RADI_PATH
		from RADICADO
		where RADI_NUME_RADI IN ($radicadosSelText)";
    $rsPath = $db->conn->query($queryPath);
    if ($rsPath) {
        $radicadoPath = $rsPath->fields["RADI_PATH"];
    }
}
$queryVeri = "select SGD_RAD_CODIGOVERIFICACION from RADICADO where RADI_NUME_RADI = $radicadosSelText";
$rsVeri = $db->conn->query($queryVeri);
$codigoverificaciontext = $rsVeri->fields["SGD_RAD_CODIGOVERIFICACION"];
$nom_remitente = "orfeo [orfeo@cra.gov.co]";

$mail = new PHPMailer();
$mail->IsSMTP(); // telling the class to use SMTP
try {
    
    $mail->CharSet = "UTF-8";
    $mail->IsSMTP(); // send via SMTP
    $mail->Host = $hostPHPMailer; // SMTP servers
    $mail->SMTPDebug = $debugPHPMailer; // enables SMTP debug information (for testing)
    $mail->Port = $portPHPMailer;
    $mail->SetFrom = $userPHPMailer;
    $mail->SMTPSecure=$SMTPSecure;
    
    $mail->SMTPAuth = $authPHPMailer;
    if (! empty($mail->SMTPAuth) && $mail->SMTPAuth) {
        // Username to use for SMTP authentication
        $mail->Username = $userPHPMailer;
        // Password to use for SMTP authentication
        $mail->Password = $passwdPHPMailer;
    }
    
    $mail->AddAddress($mailDestino, "$mailDestino");
    // $mail->AddAddress('cejebuto@gmail.com', 'sgdorfeo');
    $mensaje = file_get_contents($ruta_raiz . "/conf/MailRadicado.html");
	$envio = true;
	$asuntoMail = $asuntoMailInformado;
    if ($codTx == 8) {
        $linkImagenes = $linkImagenesTmp;
        $mensaje = file_get_contents($ruta_raiz . "/conf/MailInformado.html");
    }
    if ($codTx == 9) {
        $linkImagenes = $linkImagenesTmp;
        $mensaje = file_get_contents($ruta_raiz . "/conf/MailReasignado.html");
        $asuntoMail = $asuntoMailReasignado;
    }
    if ($codTx == 51) {
		$linkImagenes = $linkImagenesTmp;
		if(substr($radicadosSelText,-1)==2){
			$mensaje = file_get_contents($ruta_raiz . "/conf/MailRadicadoEntrada.html");
		}elseif(!empty($radicadopadre)){
			if((substr($radicadopadre,-1)==2 || substr(substr($radicadosSelText,-1)==1))){
				$asuntoMail =$asuntoProntaRpta;
				$mensaje = file_get_contents($ruta_raiz . "/conf/MailRadicadoProntaRpta.html");
				$logo = realpath(dirname(__FILE__) . "/../../")."/imagenes/logo_mail_cra.jpg";
				if(is_file($logo)){
					$mail->AddEmbeddedImage($logo, 'logo_ent');
				}
			}else{
				$mensaje = file_get_contents($ruta_raiz . "/conf/MailRadicadoInterno.html");
			}
		}else{
			$envio =false;
		}
    }
	if (isset($envioDigital)) {
        $linkImagenes = $linkImagenesTmp;
        $mensaje = file_get_contents($ruta_raiz . "/conf/envioDigital.html");
        $asuntoMail = empty($asuntoEnvioDigital)?$asuntoMailInformado:$asuntoEnvioDigital;
    }
	if($envio){
    $mail->Subject = empty($entidad)?"$asuntoMail":"$entidad: $asuntoMail";
    $mail->AltBody = 'Para ver correctamente el mensaje, por favor use un visor de mail compatible con HTML!'; // optional - MsgHTML will create an alternate automatically
    $mensaje = str_replace("*RAD_S*", $radicadosSelText, $mensaje);
    $mensaje = str_replace("*COD_VER*", $codigoverificaciontext, $mensaje);
    $mensaje = str_replace("*USUARIO*", $krd, $mensaje);
    $linkImagenes = str_replace("*SERVIDOR_IMAGEN*", $servidorOrfeoBodega, $linkImagenes);
    $mensaje = str_replace("*IMAGEN*", $linkImagenes, $mensaje);
    $mensaje = str_replace("*ASUNTO*", $asu, $mensaje);
    $nom_r = $nombre_us1 . " " . $prim_apel_us1 . " " . $seg_apel_us1 . " - " . $otro_us1;
    $mensaje = str_replace("*NOM_R*", $nom_r, $mensaje);
    $mensaje = str_replace("*RADICADO_PADRE*", $radicadopadre, $mensaje);
    $mensaje = str_replace("*MENSAJE*", $observa, $mensaje);
    $mensaje = str_replace("*FECHA_CORTA*", $fecha_actual_corta, $mensaje);
    $mensaje = str_replace("*FECHA_LARGA*", $fecha_actual_larga, $mensaje);
    $mensaje = str_replace("*NOM_REMITENTE*", $nom_remitente, $mensaje);
	
	// $mensaje .= "<hr>$entidad_largo<br>Sistema de Gestion. http://www.correlibre.org";
    $mail->MsgHTML($mensaje);
    // $mail->AddEmbeddedImage("$ruta_raiz/imagenes/logo_mail_cra.jpg", "imagen","$ruta_raiz/imagenes/logo_mail_cra.jpg","base64","image/jpeg");
    if (isset($envioDigital)) {
        $mail->AddAttachment("/var/www/html/orfeonew/bodega/$radicadoPath"); // attachment
        $sql = "select ANEX_NOMB_ARCHIVO from anexos where anex_Radi_nume='$radicadosSelText'";
        $rs = $db->conn->GetAll($sql);
        foreach ($rs as $ll) {
            $anexNomb = $ll["ANEX_NOMB_ARCHIVO"];
            $ano = substr($anexNomb, 1, 4);
            $depe = substr($anexNomb, 5, 3);
            $anexPath = "$ano/$depe/docs/$anexNomb";
            if (! strpos($anexNomb, $radicadosSelText)) {
                $mail->AddAttachment("/var/www/html/orfeonew/bodega/$anexPath"); // attachment
            }
        }
    }
    // $mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
    
    if ($mail->Send()) {
        if ($_show_mensaje != 1) {
            echo "Enviado correctamente a:  $mailDestino</br>\n";
            $success = true;
        }
		}
    } else {
        echo "<font color=red>No se envio Correo a $mailDestino</font> " . " / " . $mail->ErrorInfo;
        var_dump($mail);
        $success = false;
    }
} catch (phpmailerException $e) {
    echo $e->errorMessage() . " " . $mailDestino; // Pretty error messages from PHPMailer
} catch (Exception $e) {
    echo $e->getMessage() . " " . $mailDestino; // Boring error messages from anything else!
}
?>
