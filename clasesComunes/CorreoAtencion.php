<?php

require_once '../include/mail/graph/vendor/autoload.php';

require_once   (realpath(dirname(__FILE__) . "/")."/OAUTH/GhapClientMS.php");
require_once   (realpath(dirname(__FILE__) . "/")."/OAUTH/AuthMSCredentials.php");
require_once   (realpath(dirname(__FILE__) . "/")."/OAUTH/Provider.php");

use Office365\GraphServiceClient;
use Office365\Outlook\Message;
use Office365\Outlook\ItemBody;
use Office365\Outlook\BodyType;
use Office365\Outlook\EmailAddress;
use Office365\Runtime\Auth\AADTokenProvider;
use Office365\Runtime\Auth\UserCredentials;
use Office365\Outlook\Messages\FileAttachment;
use Argo\OAUTH\AuthMSCredentials;
use Argo\OAUTH\GhapClientMS;
use Argo\OAUTH\Provider;

class correoAtencion
{

    protected $template;

    const RADICADO = 7;

    const INFORMAR = 8;
    const REASIGNAR = 9;

    const INFORMA_ENTRADA = 10;

    const ENVIO_DIGITAL = 11;
    const INFORMA_ATENCION=12;
    const RADICARPRONTO = 13;
    const RADICAINTERNO = 14;

        protected $attached=[];
        protected $images = [];
	protected $size =0;
	protected $linkEnlaces;
	protected $from;
	protected $mensaje;
	protected $recpt=[];
	protected $subject;
	protected $validator="php";
	protected $priority;
	protected $html = false;
	protected $authProvider;
    public function __construct()
    {
        include (realpath(dirname(__FILE__) . "/../")) . "/conf/configPHPMailer.php";
        $this->authProvider = new AuthMSCredentials($clientId,$tenantId, new UserCredentials($usernameOauth, $passwordOauth));
        // Si es un exchange esto puede ser necesario
	// $tipoAutenticacion = "NTLM";
        $this->client = new GhapClientMS($this->authProvider); 

        $this->from=$userPHPMailer;
        $this->altBody = 'Para ver correctamente el mensaje, por favor use un visor de mail compatible con HTML!';
    }

    public function selectTemplate($transactionId)
    {
        if (empty($transactionId)) {
            throw new OrfeoException("No se puede Seleccionar la transacion");
        } else {
		if($transactionId == self::RADICADO){
			$this->mensaje = file_get_contents((realpath(dirname(__FILE__) . "/../")) . "/conf/MailRadicado.html");
                $this->priority=3;

	     }	     
	     if ($transactionId == self::INFORMAR) {
                $this->mensaje = file_get_contents((realpath(dirname(__FILE__) . "/../")) . "/conf/MailInformado.html");
                $this->priority=3;
            }
            if ($transactionId == self::REASIGNAR) {
                $this->mensaje = file_get_contents((realpath(dirname(__FILE__) . "/../")) . "/conf/MailReasignado.html");
                $this->priority=3;
            }
            if ($transactionId == self::INFORMA_ENTRADA) {
                $this->mensaje = file_get_contents((realpath(dirname(__FILE__) . "/../")) . "/conf/MailRadicadoEntrada.html");
                $this->priority=1;
            }
            if ($transactionId == self::ENVIO_DIGITAL) {
                $this->priority=1;
                $this->mensaje = file_get_contents((realpath(dirname(__FILE__) . "/../")) . "/conf/envioDigital.html");
	    }
	    if($transactionId == self::RADICARPRONTO){
	           $this->priority=1;
		   $this->mensaje = file_get_contents((realpath(dirname(__FILE__) . "/../")) . "/conf/MailRadicadoProntaRpta.html");
	    }
	    if($transactionId == self::RADICAINTERNO){
		    $this->priority=1;
                   $this->mensaje = file_get_contents((realpath(dirname(__FILE__) . "/../")) . "/conf/MailRadicadoInterno.html");
	    }

	    if ($transactionId == self::INFORMA_ATENCION) {
				     $this->priority=1;
				     $this->mensaje = file_get_contents((realpath(dirname(__FILE__) . "/../")) . "/conf/MailRadicadoEntradaAtencion.html");
	     }
        }
    }
    protected function crearMensaje(array $valores){
        foreach ($valores as $clave=>$valor){
            $this->mensaje = str_replace($clave, $valor, $this->mensaje);
        }
    }
     public function addAtached(string $attach){
        $this->attached[] =  $attach;
     }
    public function addEmbeddedImage(string $image,string $cid){
        $this->images[$cid]=$image;
    }
    public function generar($email,$valoresTemplate,$asunto){
        $this->addAddress($email);
        $this->subject=empty($asunto)?"Recepcion Documento":$asunto;
        $this->setHTML(true);
        $this->crearMensaje($valoresTemplate);
    }

    public function setHTML(bool $enable){
	    $this->html =$enable;
    }
    public function isHTML(){
	    return $this->html;
    } 
    public function Send(){
	  $message = $this->client->getMe()->getMessages()->createType();
          $message->setSubject($this->subject);
            if($this->isHTML()){
		 $message->setBody(new ItemBody(BodyType::HTML,$this->mensaje));
	 }else{
		 $message->setBody(new ItemBody(BodyType::Text,$this->mensaje));
	 }
	    $message->setToRecipients($this->recpt);
	    if(count($this->images) > 0 ){
	           foreach ($this->images as $key => $value){
			   $tamFile = filesize($value);
			   if(($this->size + $tamFile) < 3194304 ){
			      $this->size +=  $tamFile;
			      $attachment = $message->addAttachment(FileAttachment::class);
			      $attachment->setIsInline($key);
			      $attachment->setName(basename($value));
			      $attachment->setContentType(mime_content_type($value));
			      $fp = fopen($value, "r");
                               $file = fread($fp, filesize($value));
                               $attachment->setContentBytes( chunk_split(base64_encode($file),76,"\r\n"));
			   }
		   }
	    }
	    if( count($this->attached) > 0 ){
		    foreach ($this->attached as $value){
		       $tamFile = filesize($value);
		       if(($this->size + $tamFile) < 3194304 ){
			       $this->size +=  $tamFile;
			       $attachment = $message->addAttachment(FileAttachment::class);
			       $attachment->setIsInline(false);
			       $attachment->setName(basename($value));
			       $attachment->setContentType(mime_content_type(str_replace("'","",$value)));
			       $fp = fopen($value, "r");
			       $file = fread($fp, filesize($value));
			       $attachment->setContentBytes( chunk_split(base64_encode($file),76,"\r\n"));
		       }

		    }
	    
	    }
	 //$message->important($this->priority);
	    $result = $this->client->getMe()->sendEmail($message,true)->executeQuery();
	    
	    return $result;
     
    } 
    public function addAddress($email){
    	$this->recpt[] = new EmailAddress(null,$email); 
    }
    public function setSubject($subject)  {
      $this->subject =$subject ;
    }
    public function getSubject(){
	    return $this->subject;
    }

    public function validateAddress($address, $patternselect = null)
    {
        if (null === $patternselect) {
            $patternselect = $this->validator;
        }
        //Don't allow strings as callables, see SECURITY.md and CVE-2021-3603
        if (is_callable($patternselect) && !is_string($patternselect)) {
            return call_user_func($patternselect, $address);
        }
        //Reject line breaks in addresses; it's valid RFC5322, but not RFC5321
        if (strpos($address, "\n") !== false || strpos($address, "\r") !== false) {
            return false;
        }
        switch ($patternselect) {
            case 'pcre': //Kept for BC
            case 'pcre8':
                /*
                 * A more complex and more permissive version of the RFC5322 regex on which FILTER_VALIDATE_EMAIL
                 * is based.
                 * In addition to the addresses allowed by filter_var, also permits:
                 *  * dotless domains: `a@b`
                 *  * comments: `1234 @ local(blah) .machine .example`
                 *  * quoted elements: `'"test blah"@example.org'`
                 *  * numeric TLDs: `a@b.123`
                 *  * unbracketed IPv4 literals: `a@192.168.0.1`
                 *  * IPv6 literals: 'first.last@[IPv6:a1::]'
                 * Not all of these will necessarily work for sending!
                 *
                 * @see       http://squiloople.com/2009/12/20/email-address-validation/
                 * @copyright 2009-2010 Michael Rushton
                 * Feel free to use and redistribute this code. But please keep this copyright notice.
                 */
                return (bool) preg_match(
                    '/^(?!(?>(?1)"?(?>\\\[ -~]|[^"])"?(?1)){255,})(?!(?>(?1)"?(?>\\\[ -~]|[^"])"?(?1)){65,}@)' .
                    '((?>(?>(?>((?>(?>(?>\x0D\x0A)?[\t ])+|(?>[\t ]*\x0D\x0A)?[\t ]+)?)(\((?>(?2)' .
                    '(?>[\x01-\x08\x0B\x0C\x0E-\'*-\[\]-\x7F]|\\\[\x00-\x7F]|(?3)))*(?2)\)))+(?2))|(?2))?)' .
                    '([!#-\'*+\/-9=?^-~-]+|"(?>(?2)(?>[\x01-\x08\x0B\x0C\x0E-!#-\[\]-\x7F]|\\\[\x00-\x7F]))*' .
                    '(?2)")(?>(?1)\.(?1)(?4))*(?1)@(?!(?1)[a-z0-9-]{64,})(?1)(?>([a-z0-9](?>[a-z0-9-]*[a-z0-9])?)' .
                    '(?>(?1)\.(?!(?1)[a-z0-9-]{64,})(?1)(?5)){0,126}|\[(?:(?>IPv6:(?>([a-f0-9]{1,4})(?>:(?6)){7}' .
                    '|(?!(?:.*[a-f0-9][:\]]){8,})((?6)(?>:(?6)){0,6})?::(?7)?))|(?>(?>IPv6:(?>(?6)(?>:(?6)){5}:' .
                    '|(?!(?:.*[a-f0-9]:){6,})(?8)?::(?>((?6)(?>:(?6)){0,4}):)?))?(25[0-5]|2[0-4][0-9]|1[0-9]{2}' .
                    '|[1-9]?[0-9])(?>\.(?9)){3}))\])(?1)$/isD',
                    $address
                );
            case 'html5':
                /*
                 * This is the pattern used in the HTML5 spec for validation of 'email' type form input elements.
                 *
                 * @see https://html.spec.whatwg.org/#e-mail-state-(type=email)
                 */
                return (bool) preg_match(
                    '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}' .
                    '[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/sD',
                    $address
                );
            case 'php':
            default:
                return filter_var($address, FILTER_VALIDATE_EMAIL) !== false;
		}
	}
}

?>
