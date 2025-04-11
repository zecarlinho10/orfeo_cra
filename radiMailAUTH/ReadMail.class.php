<?php

//include_once("functionsNimeshrmr.php");
/**
 * Class Read Mail
 *
 * Clase que permite la lectura de un correo electronico
 *
 */
class ReadMail
{

    /**
     *
     * @var string servidor de correo
     */
    public $host;
    
    
    /**
     *
     * @var string direccion de host sin buzon
     */
    public $hostAddres;
       

    /**
     *
     * @var string username
     */
    public $username;

    /**
     *
     * @var string username
     */
    public $password;

    /**
     *
     * @var resource imap
     */
    private $imap;

    /**
     *
     * @var string message
     */
    public $message;

    /**
     *
     * @var integer lastNumberMail
     */
    public $lastNumberMail;

    /**
     *
     * @var object mail
     */
    public $mail;

    /**
     *
     * @var Validate connect
     */
    public $validate;

    /**
     *
     * @var Todos Destinos separados ;
     */
    public $TodoDestinos;

    /**
     *
     * @var vecCopia
     */
    public $vecCopia;

    /**
     * Constructor
     *
     * Inicializa los atributos de la clase.
     */
    public function __construct()
    {
        $this->host = '';
        $this->hostAddres = '';
        $this->username = '';
        $this->password = '';
        $this->imap = '';
        $this->message = '';
        $this->lastNumberMail = 0;
        $this->validate = '';
        $this->mail = new stdClass();
        $this->mail->subject = '';
        $this->mail->from = '';
        $this->mail->sender = '';
        $this->mail->to = '';
        $this->mail->ccaddress = '';
        $this->mail->cc = '';
        $this->mail->body = '';
        $this->mail->body2 = '';
        $this->mail->number = 0;
        $this->mail->date = '';
        $this->mail->fromMail = '';
        $this->mail->attachments = array();
        $this->mail->charset = 'utf-8';
        $this->mail->body_encoded = 'base64';
        $this->TodoDestinos = "";
        $this->vecCopia = array();
    }

    /**
     * Metodo que realiza la conexion al correo
     *
     * El metodo realiza la conexion de correo de acuerdo a los datos
     * configurados
     */
    public function connectMail()
    {
        // Validamos que los parametros sean validos
        if (! empty($this->host) && ! empty($this->username) && ! empty($this->password)) {
            try {
                  //$this->host='{imap-mail.outlook.com:993/ssl/novalidate-cert}INBOX';           
		    imap_timeout(IMAP_OPENTIMEOUT, 45);
		    $this->imap = imap_open($this->host, $this->username, $this->password/*, OP_READONLY, 1*/);
                // $this->imap = imap_open($this->host, '*', '', OP_READONLY, 1) or die($this->ms('Cannot connect to Mail: ' . imap_last_error()));
            } catch (Exception $e) {
                echo $e->getMesagge();
            } finally{
                $this->validate = (is_resource($this->imap)) ? 1 : 'Error al abrir correo electr&oacute;nico: ' . $this->username . '<br>Error: ' . imap_last_error();
            }

            //
        }
    }

    /**
     * Metodo que obtiene el ultimo correo en inbox
     *
     * El metodo realiza la conexion de correo de acuerdo a los datos
     * configurados
     */
    public function loadNumberLastMail()
    {
        // Validamos que exista un recurso
	imap_timeout(IMAP_READTIMEOUT,60);    
	if (is_resource($this->imap)) {
            // obtenemos numero de mensajes de buzon actual
            $this->lastNumberMail = imap_num_msg($this->imap);
        } else {
            $this->validate = 'Error al abrir correo electr&oacute;nico:' . $this->username;
	    imap_close($this->imap);
	    exit();
	    // die($this->ms('Falla al pasar el recurso imap como parametro'));
        }
    }

    /**
     * Metodo que limpia el objeto mail
     */
    private function clearMail()
    {
        $this->mail->subject = '';
        $this->mail->from = '';
        $this->mail->to = '';
        $this->mail->body = '';
        $this->mail->body2 = '';
        $this->mail->sender = '';
        $this->mail->date = '';
        $this->mail->fromMail = '';
        $this->mail->charset = '';
        $this->mail->body_encoded = '';
    }

    /**
     * Metodo encargado de obtener informacion del numero de correo recibido
     */
    public function loadMail()
    {
        // Limpiamos atributo mail
        $this->clearMail();

        // Validamos que exista un number mail a cargar y que haya un recurso imap
        if (! empty($this->mail->number) && is_resource($this->imap)) {
            // Obtenemos cabeceras y cuerpo del correo
            $this->loadHeader();
            $this->loadAttachments();
            $this->loadBody();
            return true;
        } else {
            return false;
        }
    }

    /* Funcion para decodificar los mensajes */
    function decode_qprint($str)
    {
        $str = preg_replace("/\=([A-F][A-F0-9])/", "%$1", $str);
        $str = urldecode($str);
        $str = utf8_encode($str);
        return $str;
    }

    /**
     * Metodo que carga cabeceras del correo
     */
    private function loadHeader()
    {
        // Obtenemos cabeceras
        $header = imap_header($this->imap, $this->mail->number);
        // $header = imap_fetch_overview($this->imap, $this->mail->number, 0);

        // Asignamos cabeceras del correo
        // $this->mail->subject = $overview[0]->subject;
        // $this->mail->subject = trim(str_replace('"','',strip_tags($this->cs(quoted_printable_decode($header->subject)))));

        $this->mail->subject = $this->decodificarCorreo($header->subject);
        $this->mail->to = $this->decodificarCorreo($header->toaddress);
        $this->mail->cc = (isset($header->cc)) ? $this->decodificarCorreo($header->cc) : '';
        $this->mail->ccaddress = (isset($header->ccaddress)) ? $this->decodificarCorreo($header->ccaddress) : '';

        if (isset($header->senderaddress)) {
            $this->mail->sender = $this->decodificarCorreo($header->senderaddress);
        } else {
            $this->mail->sender = '';
        }

        $this->mail->date = $this->decodificarCorreo($header->date);
        $correoOrigen = $header->from;
        $correoOrigen = $correoOrigen[0];
        $this->mail->from = trim($this->decodificarCorreo($correoOrigen->personal));
        $this->mail->fromMail = trim($this->decodificarCorreo($correoOrigen->mailbox) . '@' . $this->decodificarCorreo($correoOrigen->host));
        //recorre correos from        
        $cont=0;
        foreach($header->to as $posicion=>$dato){
            $correoCopia1 = $dato;
            $this->vecCopia[$cont] = trim($this->decodificarCorreo($correoCopia1->mailbox) . '@' . $this->decodificarCorreo($correoCopia1->host));
            $cont++;
        }
        //recorre correos con copia
        foreach($header->cc as $posicion=>$dato){
            $correoCopia1 = $dato;
            $this->vecCopia[$cont] = trim($this->decodificarCorreo($correoCopia1->mailbox) . '@' . $this->decodificarCorreo($correoCopia1->host));
            $cont++;
        }
        $this->TodoDestinos = $correoOrigen;
        $c=0;
        foreach($this->vecCopia as $posicion=>$dato){
            //echo "<br>copia:" . $dato;
            if($dato <> "ventanillaunicaderadicacion@supertransporte.gov.co" && $dato <> "vur@supertransporte.gov.co"){
                    $this->TodoDestinos .= ";" . $dato;
            }
        }
    }

    /**
     * Metodo que carga cuerpo del correo
     */
    



public function loadMiMeBody(){
      $structure=array();
      $structure["text-plain"] = imap_fetchbody($this->imap, $this->mail->number,1.1);
      $structure["html"]=imap_fetchbody($this->imap, $this->mail->number,1.2);
      $structure["background"]=imap_fetchbody($this->imap,$this->mail->number,2);
       return $structure;
} 
////
function getInline($inbox,$msgNo,$section){
	#retorna array con las imagenes embebidas
	#returns array with the embedded images
	$structure =  imap_fetchstructure($inbox,$msgNo);
        var_dump($structure);	
       if($section=='1.1'){
		$countInline=count($structure->parts[0]->parts);
		for ($i=$section+.1;$i<$section+.1*$countInline;$i=$i+.1){
			$inline[]=imap_fetchbody($inbox,$msgNo,$i);
		}
	}elseif($section=='1.2'){
		if ($structure->parts[1]->disposition=="INLINE" || $structure->parts[1]->disposition=="inline" || $structure->parts[1]->parts[1]->disposition=="inline")
			$countInline=count($structure->parts);
		for ($i=2;$i<=$countInline;$i++){
			$inline[]=imap_fetchbody($inbox,$msgNo,$i);
		}
	}elseif($section=='2.1'){
		$countInline=count($structure->parts[1]->parts);
		for ($i=$section+.1;$i<$section+.1*$countInline;$i=$i+.1){
			$inline[]=imap_fetchbody($inbox,$msgNo,$i);
		}
	}
        var_dump($inline);
	return array_reverse($inline);	
}



//////
function getBody(){
$inbox=$this->imap;
$msgNo=$this->mail->number;	
$section=$this->getSection();
switch ($charset){
		case "UTF-8":
		case "utf-8":
			$body =  utf8_decode(imap_qprint(imap_fetchbody($inbox,$msgNo,$section)));
			break;
		case "ISO-8859-1":
		case "iso-8859-1":
			$body =  utf8_encode(imap_qprint(imap_fetchbody($inbox,$msgNo,$section)));
			break;
		case "Windows-1252":
			$body = imap_qprint(imap_fetchbody($inbox,$msgNo,$section));
			break;
		default:
			$body =  imap_qprint(imap_fetchbody($inbox,$msgNo,$section));

			break;
	}
//	$body =  utf8_decode(imap_qprint(imap_fetchbody($inbox,$msgNo,$section)));
	if($body==''){
		$body =  imap_qprint(imap_fetchbody($inbox,$msgNo,1));
//		$body=base64_decode($body);
	}
	if(!mb_detect_encoding($body, 'UTF-8', TRUE)) $body=utf8_encode($body);
	if(base64_decode($body,true)){
		$body=base64_decode($body,true);
	}
	if($section=='1.1'){
		$body =  strstr ($body,"<div");# Limpia el string de salida.
	}
	//var_dump($section);
	if($section=='1.1' || $section=='1.2' || $section=='2.1' || $section=='1.1.2'){
		if($section =='1.1.2')
			$inline=$this->getInline($inbox,$msgNo,'1.1');
		else
			$inline=$this->getInline($inbox,$msgNo,$section);
		$dom = new DOMDocument();
		$dom->loadHTML($body);

		//Evaluate Anchor tag in HTML
		$xpath = new DOMXPath($dom);
		$imgs = $xpath->evaluate("/html/body//img");
	//var_dump($dom);

		for ($i = 0; $i < $imgs->length; $i++) {
			$img = $imgs->item($i);
			//remove and set target attribute       
			$isCid=explode(':',$img->getAttribute('src'));
			if ($isCid[0]=='cid'){
				$img->removeAttribute('src');
				$img->setAttribute("src", "data:image;base64,".$inline[$i]);
			}
		}
		// save html
		if($imgs->length>0)
			$body=$dom->saveHTML();
	}
//	var_dump($body);
	return $body;
}




///
function getSection(){
	
$structure = imap_fetchstructure($this->imap, $this->mail->number);
if($structure==null || $structure == ""){
   return false;
}
echo "AQUI VA LA SECCION DEL CORREO";
if (isset($structure->parts[0]->parts) || (isset($structure->parts[1]->parts))){
 
         //&& !is_null(strpos($structure->parts[1]->parameters[0]->value,"Apple-Mail"))	//if (isset($structure->parts[0]->parts) || isset($structure->parts[1]->parts)){//		if (isset($structure->parts[0]->parts[0]->parts) || isset($structure->parts[1]->parts[0]->parts)){
//		if (isset($structure->parts[0]->parts[0]->parts) || isset($structure->parts[1]->parts[0]->parts)){
		if (isset($structure->parts[0]->parts[0]->parts) || (isset($structure->parts[1]->parts[0]->parts) && $structure->parts[1]->subtype != "RFC822") || $structure->parts[0]->parts[0]->subtype=="HTML"){
			if(count($structure->parts[0]->parts[0]->parts)>1)
				$section="1.1.".(count($structure->parts[0]->parts[0]->parts));
			else
				$section=1.1;# Si tiene imagenes embebidas  &&  adjuntos
		}
		elseif(isset($structure->parts[0]->parts[1]->parts)){
				$section="1.2.".(count($structure->parts[0]->parts[0]->parts));
				$section="1.2.1";
		}
		else{
			$section=1.2;# Si tiene imagenes embebidas  ||   adjuntos
		}
	}
	elseif($structure->parts[1]->disposition=="ATTACHMENT" || $structure->parts[1]->disposition=="attachment"){
		$section=1;#Solo tiene adjuntos
	}elseif($structure->subtype=="HTML" or $structure->subtype=="PLAIN" or $structure->subtype=="RELATED" or $structure->subtype=="MIXED"){
		$section=1;#Solo tiene adjuntos
	}elseif($structure->parts[1]->parts){
		$section=2.1;	
	}else{
		$section=2;#Si NO tiene adjuntos
	}
var_dump($section);
	return $section;
}





private function loadBody()
    {
        $cuerpo = imap_fetchbody($this->imap, $this->mail->number, 1);
        $this->mail->body2 = base64_decode($cuerpo);

        $structure = imap_fetchstructure($this->imap, $this->mail->number);
        $section = empty($this->mail->attachments) ? 1 : 1.1;

        if ($this->check_type($structure)) {
            // $this->mail->body = utf8_decode(quoted_printable_decode(imap_fetchbody($this->imap,$this->mail->number,1)));
            $this->mail->body = $this->decodificarCorreo(imap_fetchbody($this->imap, $this->mail->number, $section), $this->mail->body_encoded, $this->mail->charset);
        } else {
            $this->mail->body = $this->decodificarCorreo(imap_body($this->imap, $this->mail->number), $this->mail->body_encoded, $this->mail->charset);
        }
        //$this->mail->body = (imap_body($this->imap, $this->mail->number));
        // $this->mail->body = trim(str_replace('"','',$this->mail->body));
    }

    public function ms($text = '')
    {
        return $text . ' en la linea ' . __LINE__ . ' del archivo ' . __FILE__;
    }

    private function check_type($structure) # # CHECK THE TYPE
    {
        if ($structure->type == 1) {
            // Retornamos true si se trata de un multipart
            return (true);
        } else {
            // Retornamos false si no se trata de un multipar
            return (false);
        }
    }

    /**
     * Metodo para limpiar cadenas
     */
    private function cs($text)
    {
        $search = array(
            '=?ISO-8859-1?Q?'
        );
        $replace = array(
            ''
        );

        return trim(str_replace($search, $replace, $text));
    }

    public function loadAttachments()
    {

        // Obtenemos estructura del correo
        $structure = imap_fetchstructure($this->imap, $this->mail->number);

        // Definimos arreglo que se comporta como almacen para los adjuntos
        $attachments = array();

        // Se verifica estructura del correo y se recorren las partes para
        // identificar cuando se trata de un adjunto y obtener los valores
        // correspondientes
        if (isset($structure->parts) && count($structure->parts)) {

            for ($i = 0; $i < count($structure->parts); $i ++) {

                // Se define configuracion del almacen de adjuntos
                // $attachments[$i] = array(
                // 'is_attachment' => false,
                // 'filename' => '',
                // 'name' => '',
                // 'attachment' => '');

                if ($structure->parts[$i]->ifparameters) {
                    if (strtolower($structure->parts[$i]->subtype) == 'plain') {

                        if ($structure->parts[$i]->encoding == 3)
                            $this->mail->body_encoded = 'base64';
                        if ($structure->parts[$i]->encoding == 4)
                            $this->mail->body_encoded = 'quoted-printable';

                        foreach ($structure->parts[$i]->parameters as $object) {
                            if (strtolower($object->attribute) == 'charset') {
                                $this->mail->charset = strtolower($object->value);
                            }
                        }
                    }
                }

                // Se enciende indicador si la parte corresponde a un adjunto
                if ($structure->parts[$i]->ifdparameters) {
                    foreach ($structure->parts[$i]->dparameters as $object) {
                        if (strtolower($object->attribute) == 'filename') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }

                // Se enciende indicador si la parte corresponde a un adjunto
                if ($structure->parts[$i]->ifparameters) {
                    foreach ($structure->parts[$i]->parameters as $object) {
                        if (strtolower($object->attribute) == 'name') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }

                // Se extrae adjunto
                if (isset($attachments[$i]['is_attachment']) && $attachments[$i]['is_attachment']) {
                    $attachments[$i]['attachment'] = imap_fetchbody($this->imap, $this->mail->number, $i + 1);
                    if ($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    } elseif ($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }

        // Asignamos adjuntos a atributo de la clase
        $this->mail->attachments = $attachments;
    }

    /**
     * Decode the string depending on encoding type.
     *
     * @return String the decoded string.
     * @param $encodedString The
     *            string in its original encoded state.
     * @param $encodingType The
     *            encoding type from the Content-Transfer-Encoding header of the part.
     */
    private function decode($encodedString, $encodingType, $charset)
    {
        if (strtolower($encodingType) == 'base64')
            $encodedString = base64_decode($encodedString);
        if (strtolower($encodingType) == 'quoted-printable')
            $encodedString = base64_decode($encodedString);

        $encodedString = strip_tags($encodedString);

        if ($charset == 'utf-8')
            $encodedString = utf8_decode($encodedString);

        return $encodedString;
    }

    /*
     * Funcion para decodificar encabezados del correo
     */
    private function decodificarCorreo($text)
    {
        $original = $text;
        $texto = '';
        $elementos = imap_mime_header_decode($text);
        for ($i = 0; $i < count($elementos); $i ++) {
            $texto .= "{$elementos[$i]->text}";
        }
        if (! is_string($texto) || $texto == '') {
            $texto = $original;
        }

        return $texto;
    }

    public function getImap()
    {
        return $this->imap;
    }

    /*
     * Funcion crear buzon RADICADOS en correo
     */
    public function creaCarpeta()
    {
        $mbox = $this->imap;
        //comando imap para crar carpeta RADICADOS
        imap_createmailbox($mbox, imap_utf7_encode($this->hostAddres."RADICADOS"));
    }

    public function existenciaCarpeta()
    {
        $mbox = $this->imap;
        $lista = imap_getmailboxes($mbox, $this->hostAddres, "*");
        $retornar=false;
        //Se recorre el listado de buzones buscando coincidencias con la palabra RADICADOS
        if (is_array($lista)) {
            foreach ($lista as $clave => $valor) {
                if(strpos(imap_utf7_decode($valor->name), "RADICADOS")){
                    $retornar = true;  
                    continue;
                }
            }
        } else {
            return false;
        }
        
        return $retornar;
    }
}
?>
