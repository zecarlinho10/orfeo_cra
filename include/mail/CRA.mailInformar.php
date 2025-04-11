<?php
//require_once ($ruta_raiz . '/include/CRA_phpmailer/class.phpmailer.php');
require_once ($ruta_raiz.'/clasesComunes/CorreoAtencion.php' ); 

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
		where RADI_NUME_RADI = ($radicadosSelText  0)";
    $rsPath = $db->conn->query($queryPath);
    $linkImagenesTmp = "";
    if (!empty($rsPath)) {
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

$correo = new correoAtencion();

try {
	$transaccionId = $correo::RADICADO;
	$asuntoMail = $asuntoMailInformado;
	$linkImagenes = $linkImagenesTmp;
	if($correo->ValidateAddress($mailDestino)){
		$correo->setHTML(true);
		if ($codTx == 8) {
		 $asuntoMail = $asuntoMailInformado;
		 $transaccionId = $correo::INFORMAR;
		}
		if ($codTx == 9) {
		   $transaccionId = $correo::REASIGNAR;
		}
		if($codTx == 51){
                    if(substr($radicadosSelText,-1)==2){
			    $transaccionId = $correo::INFORMA_ENTRADA;
		    }elseif(!empty($radicadopadre)){
			    if((substr($radicadopadre,-1)==2 || substr(substr($radicadosSelText,-1)==1))){
				    $asuntoMail =$asuntoProntaRpta;
				    $transaccionId = $correo::RADICARPRONTO;
				    $correo->addEmbeddedImage(realpath(dirname(__FILE__) . "/../../")."/imagenes/logo_mail_cra.jpg","logo_ent");
			    }else{
				    $transaccionId = $correo::RADICAINTERNO;
			    }
		    }else {
		       $envio =false;
		    }
		}
		if(!empty($envioDigital) && isset($envioDigital)){
			$transaccionId = $correo::ENVIO_DIGITAL;
			$asuntoMail = empty($asuntoEnvioDigital)?$asuntoMailInformado:$asuntoEnvioDigital;
			$correo->addAtached(BODEGA."/$radicadoPath"); // attachment
			$sql = "select ANEX_NOMB_ARCHIVO from anexos where anex_Radi_nume='$radicadosSelText'";
			$rs = $db->conn->GetAll($sql);
			if(!empty($rs)){
				foreach ($rs as $ll) {
					$anexNomb = $ll["ANEX_NOMB_ARCHIVO"];
					$ano = substr($anexNomb, 1, 4);
					$depe = substr($anexNomb, 5, 3);
					$anexPath = "$ano/$depe/docs/$anexNomb";
					if (! strpos($anexNomb, $radicadosSelText)) {
						$correo->addAtached(BODEGA."/$anexPath"); // attachment
					}
				}
			}
		}	

		if($envio){
			$asuntoMail =  empty($entidad)?"$asuntoMail":"$entidad: $asuntoMail";
			$contenido=array();
			$contenido["*RAD_S*"]= $radicadosSelText;
			$contenido["*COD_VER*"]= $codigoverificaciontext;
			$contenido["*USUARIO*"] = $krd;
			$linkImagenes = str_replace("*SERVIDOR_IMAGEN*", $servidorOrfeoBodega, $linkImagenes);
			$contenido["*IMAGEN*"] = $linkImagenes;
			$contenido["*ASUNTO*"] = $asu;
			$nom_r = $nombre_us1 . " " . $prim_apel_us1 . " " . $seg_apel_us1 . " - " . $otro_us1;
			$contenido["*NOM_R*"] = $nom_r;
			$contenido["*RADICADO_PADRE*"] = $radicadopadre;
			$contenido["*MENSAJE*"] = $observa;
			$contenido["*FECHA_CORTA*"] = $fecha_actual_corta;
			$contenido["*FECHA_LARGA*"] = $fecha_actual_larga;
			$contenido["*NOM_REMITENTE*"] = $nom_remitente;
			$correo->selectTemplate($transaccionId);
			$correo->generar($mailDestino, $contenido,$asuntoMail);
			$salida = $correo->Send();

		}  
	}
}catch (Exception $e){
     		   echo "Notificaci{on de Correo no pudo ser entregada";
		   error_log($e->getMessage());
}
		

    if (!empty($salida)) {
            echo "Enviado correctamente a:  $mailDestino</br>\n";
            $success = true;
    } else {
        echo "<font color=red>No se envio Correo a $mailDestino</font> " . " / " ;
        $success = false;
    }
?>
