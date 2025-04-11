<?php
include_once (realpath(dirname(__FILE__) . "/../")) . "/include/PHPMailer_v5.1/class.phpmailer.php";

class correoAtencion extends PHPMailer
{

    protected $template;

    const RADICADO = 7;

    const INFORMAR = 8;

    const REASIGNAR = 9;

    const INFORMA_ENTRADA = 10;

	const ENVIO_DIGITAL = 11;
	const INFORMA_ATENCION=12;

    protected $mensaje;

    protected $linkEnlaces;

    public function __construct()
    {
        include (realpath(dirname(__FILE__) . "/../")) . "/conf/configPHPMailer.php";
        
        // Si es un exchange esto puede ser necesario
        // $tipoAutenticacion = "NTLM";
        parent::__construct(true);
        $this->CharSet = "UTF-8";
        $this->IsSMTP(); // send via SMTP
        $this->Host = $hostPHPMailer;
        // SMTP server
        $this->SMTPDebug = 1; // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only
        $this->Port = $portPHPMailer;
        $this->SMTPSecure =$SMTPSecure;
        $this->SMTPAuth = $authPHPMailer;
        if (! empty($this->SMTPAuth) && $this->SMTPAuth) {
            // Username to use for SMTP authentication
            $this->Username = $userPHPMailer;
            // Password to use for SMTP authentication
            $this->Password = $passwdPHPMailer;
        }
        $this->From=$userPHPMailer;
        $this->AltBody = 'Para ver correctamente el mensaje, por favor use un visor de mail compatible con HTML!';
        $this->AddReplyTo("correo@cra.gov.co","Administración Documental");
    }

    public function selectTemplate($transactionId)
    {
        if (empty($transactionId)) {
            throw new OrfeoException("No se puede Seleccionar la transacion");
        } else {
            if ($transactionId == self::INFORMAR) {
                $this->mensaje = file_get_contents((realpath(dirname(__FILE__) . "/../")) . "/conf/MailInformado.html");
                $this->Priority=3;
            }
            if ($transactionId == self::REASIGNAR) {
                $this->mensaje = file_get_contents((realpath(dirname(__FILE__) . "/../")) . "/conf/MailReasignado.html");
                $this->Priority=3;
            }
            if ($transactionId == self::INFORMA_ENTRADA) {
                $this->ConfirmReadingTo="correo@cra.gov.co";
                $this->mensaje = file_get_contents((realpath(dirname(__FILE__) . "/../")) . "/conf/MailRadicadoEntrada.html");
                $this->Priority=1;
            }
            if ($transactionId == self::ENVIO_DIGITAL) {
                $this->ConfirmReadingTo="correo@cra.gov.co";
                $this->Priority=1;
                $this->mensaje = file_get_contents((realpath(dirname(__FILE__) . "/../")) . "/conf/envioDigital.html");
			}
			if ($transactionId == self::INFORMA_ATENCION) {
				     $this->ConfirmReadingTo="correo@cra.gov.co";
				     $this->Priority=1;
				     $this->mensaje = file_get_contents((realpath(dirname(__FILE__) . "/../")) . "/conf/MailRadicadoEntradaAtencion.html");
			}
        }
    }
    protected function crearMensaje(array $valores){
        foreach ($valores as $clave=>$valor){
            $this->mensaje = str_replace($clave, $valor, $this->mensaje);
        }
        $this->MsgHTML($this->mensaje);
        
    }
    public function generar($email,$valoresTemplate,$asunto){
        $this->AddAddress($email);
        $this->Subject=empty($asunto)?"Raecepcion Documento":$asunto;
        $this->IsHTML(true);
        $this->crearMensaje($valoresTemplate);
    }
    
}

?>
