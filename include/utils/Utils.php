<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 */
$path_raiz = realpath ( dirname ( __FILE__ ) . "/../../" );

include($path_raiz.'/config.php');

include_once $path_raiz . '/include/db/ConnectionHandler.php' ;
require $path_raiz . '/include/utils/ldap2/vendor/autoload.php';

use OTPHP\TOTP;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class Utils {

	// Verificación del token TOTP
	private  function verify_totp($secret, $token) {
	    $totp = TOTP::create($secret);
	    return $totp->verify($token);
	}

	// Guardar el código QR en un archivo
	private  function save_qr_code($url, $username) {
	    //$qrCodeDirectory = '../../ldap2/qrcodes';
	    $qrCodeDirectory = $path_raiz . '/include/utils/ldap2/qrcodes';
	    $qrCodeDirectory = LDAP_QRCODE;

	    if (!is_dir($qrCodeDirectory)) {
	        mkdir($qrCodeDirectory, 0755, true);
	    }

	    $qrCode = QrCode::create($url)
	        ->setSize(300)
	        ->setMargin(10)
	        ->setEncoding(new Encoding('UTF-8'))
	        ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh());

	    $writer = new PngWriter();
	    $result = $writer->write($qrCode);

	    $filePath = $qrCodeDirectory . '/' . strtolower($username) . '.png';
	    echo ($filePath);
	    $result->saveToFile($filePath);
	    return $filePath;
	}


	private  function get_permiso_dobleAutenticacion($username) {
	    $path_raiz = realpath ( dirname ( __FILE__ ) . "/../../" );
		$db    = new ConnectionHandler("$ruta_raiz");
	    //$db->conn->debug = true;
	    $sql="SELECT U.USUA_LOGIN 
		FROM USUARIO U
		INNER JOIN AUTM_MEMBRESIAS M ON U.ID = M.AUTU_ID
		INNER JOIN AUTG_GRUPOS G ON G.ID = M.AUTG_ID
		WHERE G.NOMBRE = 'Doble Autenticacion' AND U.USUA_LOGIN =  '".strtoupper($username)."'";
	    //return $sql;
	    $rs = $db->conn->Execute($sql);
	    while(!empty($rs) && !$rs->EOF){
        	return $rs->fields["USUA_LOGIN"];
        }
	    return null;
	}

	// Obtener el secreto TOTP del usuario
	private  function get_user_secret($username) {
	    $path_raiz = realpath ( dirname ( __FILE__ ) . "/../../" );
		$db    = new ConnectionHandler("$ruta_raiz");
	    //$db->conn->debug = true;
	    $sql="SELECT TTOP_SECRET FROM USUARIO WHERE USUA_LOGIN = '".strtoupper($username)."'";
	    //return $sql;
	    $rs = $db->conn->Execute($sql);
	    while(!empty($rs) && !$rs->EOF){
        	return $rs->fields["TTOP_SECRET"];
        }
	    return null;
	}

	private  function generate_totp_secret($username) {
		$path_raiz = realpath ( dirname ( __FILE__ ) . "/../../" );
		$db    = new ConnectionHandler("$ruta_raiz");

	    $totp = TOTP::create();
	    $totp->setLabel($username);
	    $totp->setIssuer('CRA');
	    $secret = $totp->getSecret();
	    $url = $totp->getProvisioningUri();

	    $sql="UPDATE USUARIO SET TTOP_SECRET = '" . $secret . "' WHERE USUA_LOGIN = '" . strtoupper($username) . "'";

	    $stmt = $db->conn->Execute($sql);
	    return [
	        'secret' => $secret,
	        'url' => $url
	    ];
	}

	
	public static function getIp() {
		$ipCliente = false;
		$ipEncontrada = null;
		$ipEncontrada = null;
		$ipEncontrada = null;
		
		if (! empty ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )) { // buscamos la ip en la vaiable server.
			$ipCliente = (! empty ( $_SERVER ['REMOTE_ADDR'] )) ? $_SERVER ['REMOTE_ADDR'] : ((! empty ( $_ENV ['REMOTE_ADDR'] )) ? $_ENV ['REMOTE_ADDR'] : "Sin Info");
			$ent = explode ( ", ", $_SERVER ['HTTP_X_FORWARDED_FOR'] );
			reset ( $ent );
			foreach ( $ent as $valor ) {
				$valor = trim ( $valor );
				if (preg_match ( "/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $valor, $lista_ips )) {
					$ipsPrivadas = array (
							'/^0\./',
							'/^127\.0\.0\.1/',
							'/^192\.168\..*/',
							'/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
							'/^10\..*/' 
					);
					$ipEncontrada = preg_replace ( $ipPrivadas, $ipCliente, $lista_ips [1] );
					if ($ipCliente != $ipEncontrada) {
						$ipCliente = $ipEncontrada;
					}
				}
			}
		}
		if (! $ipCliente) {
			$headers = getallheaders(); 
			if (! empty ( $headers ["X-Forwarded-For"] )) {
				$ipCliente = $headers ["X-Forwarded-For"];
				$ent = explode ( ", ", $headers ["X-Forwarded-For"] );
				reset ( $ent );
				$ipCliente = $ent [0];
			} else
				$ipCliente = (! empty ( $_SERVER ['REMOTE_ADDR'] )) ? $_SERVER ['REMOTE_ADDR'] : ((! empty ( $_ENV ['REMOTE_ADDR'] )) ? $_ENV ['REMOTE_ADDR'] : "Sin Informacion");
		}
		return $ipCliente;
	}
	public static function getIP2() {
		if (isSet ( $_SERVER )) {
			if (isSet ( $_SERVER ["HTTP_X_FORWARDED_FOR"] )) {
				$realip = $_SERVER ["HTTP_X_FORWARDED_FOR"];
			} elseif (isSet ( $_SERVER ["HTTP_CLIENT_IP"] )) {
				$realip = $_SERVER ["HTTP_CLIENT_IP"];
			} else {
				$realip = $_SERVER ["REMOTE_ADDR"];
			}
		} else {
			if (getenv ( "HTTP_X_FORWARDED_FOR" )) {
				$realip = getenv ( "HTTP_X_FORWARDED_FOR" );
			} elseif (getenv ( "HTTP_CLIENT_IP" )) {
				$realip = getenv ( "HTTP_CLIENT_IP" );
			} else {
				$realip = getenv ( "REMOTE_ADDR" );
			}
		}
		return $realip;
	}
	public static function return_bytes($val) {
		$val = trim ( $val );
		$ultimo = strtolower ( $val [strlen ( $val ) - 1] );
		switch ($ultimo) { // El modificador 'G' se encuentra disponible desde PHP 5.1.0
			case 'g' :
				$val *= 1024;
			case 'm' :
				$val *= 1024;
			case 'k' :
				$val *= 1024;
		}
		return $val;
	}

	// Autenticación LDAP
	public function authenticate_ldap($username, $password) {
	    global $ldap_server, $ldap_dn, $ldap_user, $ldap_password;

	    $ldap_server = LDAP_SERVER;
	    $ldap_dn = LDAP_DN;
	    $ldap_user = LDAP_USER;
	    $ldap_password = LDAP_PASSWORD;

	    $ldap_conn = ldap_connect($ldap_server);
	    ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
	    ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

	    if ($ldap_conn) {
	        $ldap_bind = @ldap_bind($ldap_conn, $ldap_user, $ldap_password);

	        if ($ldap_bind) {
	            $filter = "(sAMAccountName=$username)";
	            $result = @ldap_search($ldap_conn, $ldap_dn, $filter);

	            if ($result) {
	                $entries = ldap_get_entries($ldap_conn, $result);

	                if ($entries['count'] > 0) {
	                    $dn = $entries[0]['dn'];

	                    if (@ldap_bind($ldap_conn, $dn, $password)) {
	                        ldap_unbind($ldap_conn);
	                        //return true;
	                        return $ldap_conn;
	                    }
	                }
	            }
	        }
	        ldap_unbind($ldap_conn);
	    }
	    
	    return false;
	}

	public function checkldapuser($username, $password, $userBind=1,$krd) {
		$path_raiz = realpath ( dirname ( __FILE__ ) . "/../../" );
		require ($path_raiz . '/config.php');
		$username = strtolower ( $username );
		$connect=$this->authenticate_ldap($username, $password);
		if ($connect !== false) {
			session_start();
			//if($this->get_permiso_dobleAutenticacion( $username )==NULL){
			if($this->get_permiso_dobleAutenticacion( $krd )==NULL){
				return 1;
			}
			else{
				$_SESSION['username'] = $username;
		        //$secret = $this->get_user_secret($username);
		        $secret = $this->get_user_secret($krd);

		        if (!$secret) {
		        	// Generar un nuevo secreto y mostrar el código QR si no existe un secreto
				            //$totp_data = $this->generate_totp_secret($username);
		        			$totp_data = $this->generate_totp_secret($krd);
				            $secret = $totp_data['secret'];
				            //return $secret;
				            $url = $totp_data['url'];
				            //$qrCodePath = $this->save_qr_code($url, $username);
				            $qrCodePath = $this->save_qr_code($url, $krd);
				            $timeout = 10; // Tiempo máximo de espera en segundos
							$espera = 0;

							while (!file_exists($qrCodePath) && $espera < $timeout) {
							    sleep(1); // Espera 1 segundo antes de verificar nuevamente
							    $espera++;
							}

							if (file_exists($qrCodePath)) {
							     return 'Por favor, escanea el código QR y usa el token generado.<br><img src="' . $qrCodePath . '" alt="Código QR de Autenticación">';
							} else {
							    return "El archivo no se creó en el tiempo esperado. $qrCodePath  ";
							}
		        }
		        else{
		        	return "requiereTocken";
		        }
		    }
		    if ($connect !== false && is_resource($connect)) {
			    ldap_unbind($connect);
			}
		    //ldap_close ( $connect );
		}
		else {
			$mensajeError = "Clave de correo institucional vencida o clave errada";
			return $mensajeError;
		}
		
		return (false);
	}
	

	public function checkldapuser2($username, $password, $tocken,$krd) {
		$path_raiz = realpath ( dirname ( __FILE__ ) . "/../../" );
		require ($path_raiz . '/config.php');
		$username = strtolower ( $username );
		$connect=$this->authenticate_ldap($username, $password);

		    $secret = $this->get_user_secret($username);
   			if ($this->verify_totp($secret, $tocken)){
	           	//return 'Autenticación completa';
	           	return 1;
	        }
	        else {
	            return 'Token incorrecto.';
	        }				   
	}
	/**
	 * funcion que ajusta los codigos de los anexos con el tama�o de la dependencia
	 */
	public static function carpetaDependencia($numRadicado, $noDigitosDependencia) {
		$dependencia = substr ( $numRadicado, 4, $noDigitosDependencia );
		return $dependencia + 0;
	}
	public static function fechaFormateada($FechaStamp) {
		$ano = date ( 'Y', $FechaStamp ); // <-- A�o
		$mes = date ( 'm', $FechaStamp ); // <-- n�mero de mes (01-31)
		$dia = date ( 'd', $FechaStamp ); // <-- D�a del mes (1-31)
		$dialetra = date ( 'w', $FechaStamp ); // D�a de la semana(0-7)
		switch ($dialetra) {
			case 0 :
				$dialetra = "domingo";
				break;
			case 1 :
				$dialetra = "lunes";
				break;
			case 2 :
				$dialetra = "martes";
				break;
			case 3 :
				$dialetra = "miércoles";
				break;
			case 4 :
				$dialetra = "jueves";
				break;
			case 5 :
				$dialetra = "viernes";
				break;
			case 6 :
				$dialetra = "sábado";
				break;
		}
		switch ($mes) {
			case '01' :
				$mesletra = "Enero";
				break;
			case '02' :
				$mesletra = "Febrero";
				break;
			case '03' :
				$mesletra = "Marzo";
				break;
			case '04' :
				$mesletra = "Abril";
				break;
			case '05' :
				$mesletra = "Mayo";
				break;
			case '06' :
				$mesletra = "Junio";
				break;
			case '07' :
				$mesletra = "Julio";
				break;
			case '08' :
				$mesletra = "Agosto";
				break;
			case '09' :
				$mesletra = "Septiembre";
				break;
			case '10' :
				$mesletra = "Octubre";
				break;
			case '11' :
				$mesletra = "Noviembre";
				break;
			case '12' :
				$mesletra = "Diciembre";
				break;
		}
		return "$dialetra $dia de $mesletra de $ano";
	}
}

?>

