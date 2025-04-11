<?php

//error_reporting(0);
//ini_set('display_errors', 0);

require_once("/datos/vhosts/htdocs/orfeo/config.php");
//$ruta_raiz=ABSOL_PATH; 


//error_reporting(E_ALL);
//ini_set('display_errors', '1');

/**
 * Class Radicar Mail
 *
 * Clase que realiza el proceso para radicar un mail
 *
 */
/*include_once "../seguridad_sql.php";
foreach ($_GET as $key => $value)  { $_GET[$key] = StringClean($value); }
foreach ($_POST as $key => $value) { $_POST[$key] = StringClean($value); }*/
//session_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


if (empty($_SESSION['token'])) {
    if (function_exists('mcrypt_create_iv')) {
        $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    } else {
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}
$token = $_SESSION['token'];


/*

if(isset($_GET['manual']) && $_GET['manual'] ==1){
    $ruta_raiz = '..';
}else{
    
    //Ultizamos este bloque para traer una ruta absoluta del document root
    $currentFile=$_SERVER['PHP_SELF'];
    $arrayAddres=explode('/', $currentFile);
    if(count($arrayAddres)<2){
        $arrayAddres=explode('\\', $currentFile);
    }
    unset($arrayAddres[count($arrayAddres)-1]);
    unset($arrayAddres[count($arrayAddres)-1]);
    
    $document_root=implode('/', $arrayAddres);
    
    $document_root.='/';
    $ruta_raiz='..';
}
 */
//fin de creacion de ruta absoluta
$ruta_raiz='..';
include_once("$ruta_raiz/include/db/ConnectionHandler.php");
include($ruta_raiz."/include/tx/Tx.php") ;
include($ruta_raiz."/include/tx/Radicacion.php") ;
include($ruta_raiz."/class_control/Municipio.php") ;
include ("classSanitize.php");

//Definimos constante necesaia para generacion del pdf
if (!defined('FPDF_FONTPATH'))
{
    define('FPDF_FONTPATH',  $ruta_raiz ."/radiMailAUTH/fpdf182/font/");
}

require_once($ruta_raiz ."/radiMailAUTH/fpdf182/html2pdf.php");

$db = new ConnectionHandler($ruta_raiz);
$sanitize=new classSanitize();
//$db =& $conexion->conn;
$db->conn->SetFetchMode(ADODB_FETCH_NUM);
$db->conn->SetFetchMode(2);
//$db->conn->debug=true;
class RadicarMail
{
    /**
    * @var integer id mail
    */
    public  $mail_id;

    /**
     * @var string correo
     */
    public  $mail;

    /**
     * @var string clave
     */
    public  $mail_pass;

    /**
     * @var string usuario
     */
    public  $mail_user;

    /**
     * @var array subject filter
     */
    public  $mail_subject_filter;

    /**
     * @var string mail destinatario alternativo en caso de que no se encuentre ciu ciudadano
     */
    public  $mail_dest_alt;

    /**
     * @var string mail destinatario alternativo en caso de que no se encuentre ciu ciudadano
     */
    public  $mail_host;    


    /**
     * @var array mailList
     */
    public  $mailList;

    /**
     * @var array number
     */
    public  $ejecucion;
    

    public  $anexState=array();
    

    public $dateMail;

    private $dateRad;

    public $annex_valid=array();

    public $update_annex=false;
     /**
     * Constructor
     *
     * Inicializa los atributos de la clase.
     */
    public function __construct($ejecucion=0)
    {
        $this->mail_id    = '';
        $this->mail       = '';
        $this->mail_pass  = '';
        $this->mail_user  = '';
        $this->mail_dest_alt = '';
        $this->mail_subject_filter  = '';
        $this->mail_host  = '';
        $this->mailList   = array();
        $this->ejecucion  = $ejecucion;
//        date_default_timezone_set("America/Bogota");
    }

    /**
    * Obtenemos correos configurados en base de datos para racicacion
    */
   //*****+
   public function sanitizeData($data){
        global $sanitize;
        $data_return=$sanitize->noSql($data);
        return $data;
   

   }

   public function getDigitoVerificion($radicado)
    {
        //Habilitamos la conexion a base de datos
        global $db;

        //Construimos sentencia
        $sql = "SELECT SGD_RAD_CODIGOVERIFICACION FROM RADICADO
                WHERE RADI_NUME_RADI = '$radicado'";
        $rsql= $db->conn->Execute($sql);

        $digito=null;
        //Iteramos
        while(!$rsql->EOF)
        {
            $digito  = $rsql->fields['SGD_RAD_CODIGOVERIFICACION'];
            $rsql->MoveNext();
        }
        return $digito;
    }

    //***
    public function getListMailRadica()
    {
        //Habilitamos la conexion a base de datos
        global $db;

        //Construimos sentencia
        $sql = "SELECT * FROM RADICADO_MAIL ORDER BY MAIL_ID ASC";
        $rsql= $db->conn->Execute($sql);


        //Iteramos
        while(!$rsql->EOF)
        {
            $obj = new stdClass();
            //$user = explode('@',$rsql->fields['MAIL']);
            $obj->mail_id  = $rsql->fields['MAIL_ID'];
            $obj->mail     = $rsql->fields['MAIL'];
            $obj->mail_pass= $rsql->fields['MAIL_PASS'];
            
            //$obj->mail_user= (isset($user[0]))?$user[0]:'';
            $obj->mail_user= $rsql->fields['MAIL'];
            $obj->mail_host= $rsql->fields['MAIL_HOST'];
            $obj->mail_dest_alt=$this->getDestinatario($obj->mail_id);

            if ($rsql->fields['MAIL_SUBJECT_FILTER']!='' && $rsql->fields['MAIL_SUBJECT_FILTER']!=null)
            {
                $obj->mail_subject_filter = explode(',',$rsql->fields['MAIL_SUBJECT_FILTER']);
            }
            else
            {
                $obj->mail_subject_filter = array();
            }

            $this->mailList[] = $obj;
            unset($obj);
            //Movemos el puntero
            $rsql->MoveNext();
        }
    }


    /**
    * Obtenemos ultimo correo radicado
    */
    public function getLastNumberMailRadicado($mail_id)
    {
        //Habilitamos la conexion a base de datos
        global $db;

    //Construimos sentencia
    //0
    if($db->driver=="oracle"){
        $sql = "SELECT TOP 1 MAIL_NUMBER FROM RADICADO_FROM_MAIL WHERE MAIL_ID = $mail_id ORDER BY MAIL_NUMBER DESC";
    }else{
     $sql= "SELECT MAIL_NUMBER FROM RADICADO_FROM_MAIL WHERE MAIL_ID =$mail_id ORDER BY MAIL_NUMBER DESC LIMIT 1";
    }
    //$sql = "SELECT TOP 1 MAIL_NUMBER FROM RADICADO_FROM_MAIL WHERE MAIL_ID = $mail_id ORDER BY MAIL_NUMBER DESC";
        $rsql= $db->conn->Execute($sql);
        
        
        $lastIdMail = (is_numeric($rsql->fields['MAIL_NUMBER']))?$rsql->fields['MAIL_NUMBER']:0;

        //Retornamos ultimo numero de mail radicado
        return $lastIdMail;
    }


    /**
    * Metodo que consulta informacion basica del usuario
    */
    public function getInfoUsuario($usua_login)
    {
        //Habilitamos la conexion a base de datos
        global $db;

        //inicializamos
        $usuario = array();

        $usua_login = strtoupper($usua_login);

        //Construccion sentencia que obtiene datos del usuario que radica
        $sql="SELECT USUA_LOGIN,USUA_DOC,DEPE_CODI,CODI_NIVEL,USUA_CODI,USUA_NOMB FROM USUARIO WHERE  upper(USUA_LOGIN) ='$usua_login' ";
        $rs=$db->conn->Execute($sql);

        if($rs && !$rs->EOF)
        {

            $usuario['usua_login']=$rs->fields["USUA_LOGIN"];
            $usuario['usua_doc'] =$rs->fields["USUA_DOC"];
            $usuario['usua_depe'] =$rs->fields["DEPE_CODI"];
            $usuario['usua_nivel'] =$rs->fields["CODI_NIVEL"];
            $usuario['usua_codi'] =$rs->fields["USUA_CODI"];
            $usuario['usua_nomb'] =$rs->fields["USUA_NOMB"];
            $rs->MoveNext(); 
       }

        return $usuario;
    }

    /**
    * Metodo que consulta informacion basica del usuario radicador
    */
    public function getInfoRadicador()
    {
      /*  if($this->ejecucion==1){
            $ruta_raiz='../';
        }else{
            //Habilitamos la conexion a base de datos
            //Ultizamos este bloque para traer una ruta absoluta del document root
            $currentFile=$_SERVER['PHP_SELF'];
            $arrayAddres=explode('/', $currentFile);
            if(count($arrayAddres)<2){
                $arrayAddres=explode('\\', $currentFile);
            }
            unset($arrayAddres[count($arrayAddres)-1]);
            unset($arrayAddres[count($arrayAddres)-1]);
            
            $document_root=implode('/', $arrayAddres);
            
            $document_root.='/';
        $ruta_raiz=$document_root;
        $ruta_raiz="../";
            //fin de creacion de ruta absoluta
        }*/
        
        
        $db = new ConnectionHandler($ruta_raiz);
       // $db->conn->debug=true;

        //inicializamos
        $usuario = array();
        
    //$usua_login = strtoupper($radicador_mail);
    //var_dump($usua_login);
        //Construccion sentencia que obtiene datos del usuario que radica
        $sql="SELECT USUA_LOGIN,USUA_DOC,DEPE_CODI,CODI_NIVEL,USUA_CODI,USUA_NOMB FROM USUARIO WHERE  upper(USUA_LOGIN) ='".$_SESSION['krd']."' ";
        
        $rs=$db->conn->query($sql);
    //$usuario=array()
        if(!empty($rs) && !$rs->EOF)
        {

            $usuario['usua_login']=$rs->fields["USUA_LOGIN"];
            $usuario['usua_doc'] =$rs->fields["USUA_DOC"];
            $usuario['usua_depe'] =$rs->fields["DEPE_CODI"];
            $usuario['usua_nivel'] =$rs->fields["CODI_NIVEL"];
            $usuario['usua_codi'] =$rs->fields["USUA_CODI"];
            $usuario['usua_nomb'] =$rs->fields["USUA_NOMB"];
            //echo $usuario['usua_login'];
            //var_dump($rs);
           $rs->MoveNext();
        }

     return $usuario;
    }
    /**
    * Metodo para hallar destinatario de radicacion mail
    */
    ///public function getInfoDestinatario($objMail,$mailDefault='')
    public function getInfoDestinatario($correo,$nombre)
    {
        //Array que almacena todos los destinatarios
        $destinatarios=array();
        /*
        $texto  = $objMail->mail->from;
        $objMail->mail->from='';
        $elementos = imap_mime_header_decode($texto);
        for ($i=0; $i<count($elementos); $i++) {
            $objMail->mail->from.="{$elementos[$i]->text}";
        }
        if(!is_string($objMail->mail->from)){
            $objMail->mail->from=$texto;
        }
        $nombre=$objMail->mail->from;
        $correo=(strlen($mailDefault)>0)?$mailDefault:$objMail->mail->fromMail;
        */
        //$nombre=$correo;
        $destinatarios=$this->buscarEntidades($correo);
        if(count($destinatarios)<1){
            $destinatarios=$this->buscarEmpresas($correo);
            if(count($destinatarios)<1){
                $destinatarios=$this->buscarCiudadano($correo);
                if(count($destinatarios)<1){
                    $destinatarios=$this->buscarFuncionario($correo);
                    if(count($destinatarios)<1){
                        $telefono=0;
                        $continente=1;
                        $pais=170;
                        $depto=11;
                        $municipio=1;
                        //Asignamos resultado de la consulta
                        $destinatarioOrg['SGD_DIR_DOC']    = '115';
                        $destinatarioOrg['SGD_DIR_NOMREMDES']=isset($nombre)?$nombre:'por defecto'; //'Nombre'
                        $destinatarioOrg['SGD_DIR_NOMBRE']          = isset($nombre)?$nombre:'por defecto'; //'Nombre'
                        $destinatarioOrg['SGD_DIR_TELEFONO']        = isset($telefono)?$telefono:''; //'Telefono'
                        $destinatarioOrg['SGD_DIR_DIRECCION']       = isset($correo)?$correo:0; //'Direccion'
                        $destinatarioOrg['SGD_DIR_MAIL']            = isset($correo)?$correo:0; //'Mail'
                        $destinatarioOrg['ID_CONT']          = isset($continente)?$continente:0; //'idcont'
                        $destinatarioOrg['ID_PAIS']          = isset($pais)?$pais:0; //'idpais'
                        $destinatarioOrg['DPTO_CODI']           = isset($depto)?$depto:0; //'codep'
                        $destinatarioOrg['MUNI_CODI']            = isset($municipio)?$municipio:0; //'muni'
                        $destinatarioOrg['SGD_CIU_CODIGO']  = '29';//'sgd_ciu_codigo'
                        $destinatarioOrg['SGD_DIR_TIPO']      = 1;
                        $destinatarioOrg['SGD_SEC_CODIGO']    = 0;
                        $destinatarios[]=$destinatarioOrg;
                    }
                }
            }
        }
        
       

        //Retornamos
        
        return $destinatarios;
    }

    function obtenerSecuecniaPorDependencia($dependencia){
     
        //Habilitamos conexion a base de datos
        global $db;

        //Construimos sentencia que verifica si existe un correo de un ciudadano con el correo que se recibio para radicar
        $sql = "SELECT DEPE_RAD_TP2 AS SEC FROM DEPENDENCIA WHERE DEPE_CODI = $dependencia";
        $rs=$db->conn->Execute($sql);
        $secuencia = (isset($rs->fields['SEC']))?$rs->fields['SEC']:false;

        //Retornamos
        return $secuencia;
    }

    /**
    * Metodo para radicar documento
    */
    public function localSanitize($word){
        $word=preg_replace('/[^\p{L}\p{N}\-\?\¿\#\!\¡]/u',' ',$word);
        return $word; 
    
    }
    
    public function radicarDocumento($destinatario,$asu,$radi_usua_actu,$mail_mensaje,$mail_cc,$emailRadicador)
    {        
        //var_dump($destinatario);
            
            //$asu=(!preg_match('//u',$asu))?utf8_encode($asu):$asu;
            //$tmp=iconv('UTF-8','UTF-8//ignore',$asu); 
            //$asu=$tmp;
            //$asu=$this->localSanitize($asu);
            $asu=substr($asu,0,500);
       //  $asu=$this->sanitizeData($asu);
  // ini_set('display_errors',1);
        //Hacemos disponibles variables globales
        global $db, $ruta_raiz, $ADODB_COUNTRECS;
       // $db->conn->debug=true;
        if (!isset($_SESSION)) {
            session_start();
        }
///
     for ($i = 0; $i < count($destinatario); $i++) {
            $newId = false;
            if($destinatario[$i]['SGD_DIR_DOC']!='')
            {
                //Obtenemos id de la nueva secuencia para dir_direcciones
                $nextval=$db->nextId("SEC_DIR_DRECCIONES");

                if ($nextval==-1)
                {
                    return ["Error: No se encontro la secuencia sec_dir_drecciones ","invalid"];
                }
                
                $ADODB_COUNTRECS = true;
                $sgd_oem_codigo = 0;
                $sgd_fun_codigo = 0;
                $sgd_esp_codigo = 0;
                //$db->conn->debug = true;
                //$destinatario[$i]['SGD_TRD_CODIGO']    = $sgdTrd;
                //$destinatario[$i]['RADI_NUME_RADI']    = $nurad;
                $destinatario[$i]['SGD_DIR_CODIGO']    = $nextval;
                
                foreach ($destinatario[$i] as $key => $value){
                           
                  if($key=="SGD_DIR_NOMREMDES" || $key=="SGD_DIR_NOMBRE"){
                                           
                         $value=(!preg_match('//u',$value))?utf8_encode($value):$value; 
                         $tmp=iconv('UTF-8','UTF-8//ignore',$value);
                         $value=$tmp; 
                         $value=$this->localSanitize($value);
                  }else{        
                   $value=$this->sanitizeData($value);
                  }         
                           $destinatario[$i][$key]=$value;
                }
                


                   
                   $sqlCreaDestinatario="INSERT INTO SGD_DIR_DRECCIONES(
                    SGD_DIR_DOC,
                    SGD_DIR_NOMREMDES,
                    SGD_DIR_NOMBRE,
                    SGD_DIR_DIRECCION,
                    SGD_DIR_TELEFONO,
                    SGD_DIR_MAIL,
                    ID_CONT,
                    ID_PAIS,
                    DPTO_CODI,
                    MUNI_CODI,
                    SGD_SEC_CODIGO,
                    SGD_DIR_TIPO,
            SGD_DIR_CODIGO
                   
                    )
                    VALUES (
                        '{$destinatario[$i]['SGD_DIR_DOC']}',
                        '{$destinatario[$i]['SGD_DIR_NOMREMDES']}',
                        '{$destinatario[$i]['SGD_DIR_NOMBRE']}',
                        '{$destinatario[$i]['SGD_DIR_DIRECCION']}',
                        '{$destinatario[$i]['SGD_DIR_TELEFONO']}',
                        '{$destinatario[$i]['SGD_DIR_MAIL']}',
                        {$destinatario[$i]['ID_CONT']},
                        {$destinatario[$i]['ID_PAIS']},
                        {$destinatario[$i]['DPTO_CODI']},
                        {$destinatario[$i]['MUNI_CODI']},
                        {$destinatario[$i]['SGD_SEC_CODIGO']},
                        {$destinatario[$i]['SGD_DIR_TIPO']},
                        {$destinatario[$i]['SGD_DIR_CODIGO']})";
 
                
                //Validacion de sgd dir
                if(!$db->conn->Execute($sqlCreaDestinatario)){
                    return  ["Error: No se ha podido actualizar la informacion de SGD_DIR_DRECCIONES UNO -- $isql --","invalid"];
                    break;
                }
                
               // unset($record);
                
                $ADODB_COUNTRECS = false;
                
            }
        }

    



   //Definimos valores que permanecerian constantes para este tipo de radicacion
        //o que se obtendrian de otro parametro recibido
        //$file = base64_encode(@file_get_contents('20131000000262_0001.pdf'));
        $file       = '';
        $fileName   = '';
        $med        = '4';
        $ane        = '0';
        $mail_radica    = $radi_usua_actu;
        
        $radi_usua_actu1 = $this->getInfoUsuario($radi_usua_actu);
        
        $coddepe          = $radi_usua_actu1['usua_depe'];
        $usua_doc         = $radi_usua_actu1['usua_doc'];
        $usua_nivel       = $radi_usua_actu1['usua_nivel'];
        $radi_usua_nombre = $radi_usua_actu1['usua_nomb'];
        $radi_usua_actu   = $radi_usua_actu1['usua_codi'];

        $tpRadicado     = '2';
        $cuentai        = '';
        $tip_rem        = '0';
        $tdoc           = '0';
        $tip_doc        = '0';
        $carp_codi      = '0';
        $carp_per       = '0';
        $sgdTrd         = "1";
        $sgd_ciu_codigo = $destinatario['SGD_CIU_CODIGO'];

        $_SESSION['dependencia']    = $coddepe;
        $_SESSION['usua_doc']       = $usua_doc;                
        $_SESSION['codusuario']     = $radi_usua_actu;
        $_SESSION['nivelus']        = $usua_nivel ; 
        $_SESSION['nivelus_mail']   = $usua_nivel ;     
        //Se instancian clases
        $hist = new Historico($db) ;
        $tmp_mun = new Municipio($db) ;
        $rad = new Radicacion($db) ;
        //vamos a obtener los datos del usuario radicador
        $usuarioRadicador=$this->getInfoRadicador();
        $depeRadica=$usuarioRadicador["usua_depe"];
        $usuaRadica=$usuarioRadicador["usua_codi"];
        //Se asignan valores para radicacion
        //$tmp_mun->municipio_codigo($destinatario["DPTO_CODI"],$destinatario["MUNI_CODI"]) ;
        $rad->noDigitosRad  = 6;
        $rad->radiTipoDeri  = $tpRadicado;
        $rad->nivelRad      = 0;
        $rad->radiCuentai   = "'".trim($cuentai)."'";
        $rad->eespCodi      =  '0';
        $rad->mrecCodi      =  $med;
        $rad->radiFechOfic  =  date("Y-m-d");
        if((!isset($radicadopadre) || !$radicadopadre))  $radicadopadre = null;
        $rad->radiNumeDeri  = trim($radicadopadre) ;
        $rad->radiPais      =  $tmp_mun->get_pais_codi() ;
        $rad->descAnex      = $ane ;
        //En este bloque validaremos si hay que codificar o NO
        
        $rad->raAsun        = $this->depurarString($asu);
        $rad->nofolios      = 1; 
        $rad->radiDepeActu  = $coddepe;
        $rad->radiDepeRadi  = $depeRadica;
        $rad->radiUsuaSel  = $radi_usua_actu;
        $rad->radiUsuaActu = $radi_usua_actu;
        $rad->trteCodi      =  $tip_rem ;
        $rad->tdocCodi      = $tdoc ;
        $rad->tdidCodi      = $tip_doc;
        $rad->carpCodi      = $carp_codi ;
        $rad->carPer        = $carp_per ;
        $rad->trteCodi      = $tip_rem ;
        $rad->radiPath      = 'null';
        $secuencia = $this->obtenerSecuecniaPorDependencia($depeRadica);

        if (!isset($aplintegra))
        $aplintegra         = "0" ;
        $rad->sgd_apli_codi = $aplintegra ;
        $codTx              = 2;
        $flag               = 1 ;
        $rad->usuaCodi      = $usuaRadica ;
        $rad->dependencia   = trim($depeRadica) ;
        $noRad              = $rad->newRadicado($tpRadicado,$secuencia) ;

       // var_dump($rad);
        //die();
        $nurad              = trim($noRad) ;
        //$sql_ret            = $rad->updateRadicado($nurad,"/".date("Y")."/".$coddepe."/".$noRad.".pdf");

        if ($noRad==null || strlen($noRad)!=14)
        {
            return ["Error: no genero un Numero de Secuencia o Inserto el radicado","invalid"];
        }
       
       //Actualiza el destinatario
        for ($i = 0; $i < count($destinatario); $i++) {
            $newId = false;
            if($destinatario[$i]['SGD_DIR_DOC']!=''){
                //Obtenemos id de la nueva secuencia para dir_direcciones
                          
                $ADODB_COUNTRECS = true;
                         //$db->conn->debug = true;
                $destinatario[$i]['SGD_TRD_CODIGO']    = $sgdTrd;
                $destinatario[$i]['RADI_NUME_RADI']    = $nurad;
                
                $sqlCreaDestinatario="UPDATE SGD_DIR_DRECCIONES SET SGD_TRD_CODIGO=$sgdTrd,  RADI_NUME_RADI=".$nurad." WHERE";
                $sqlCreaDestinatario.=" SGD_DIR_CODIGO = $nextval"; 
         
                if(!$db->conn->Execute($sqlCreaDestinatario)){
                    return  ["Error: No se ha podido actualizar la informacion de SGD_DIR_DRECCIONES UNO  --","invalid"];
                    break;
                }
                
                unset($record);
                
                $ADODB_COUNTRECS = false;
                
            }
        }

        $radicadosSel[0] = $noRad;
        $observaHist='Radicación de correo electrónico desde la cuenta <b>'.$emailRadicador.'</b>';
        $hist->insertarHistorico($radicadosSel, $depeRadica, $usuaRadica,  $coddepe , $radi_usua_actu, $observaHist, $codTx);
        $sgd_dir_us2=2;
        $retval .=$noRad;

        //retornamos
        return [$retval,$body_mail,$asu,$radi_usua_nombre,$mail_mensaje,$mail_cc];
    }

    /**
    * Metodo que graba relacion radicado
    **/
    public function validFile($Attchment){
          
       $state_anex=true;
       for($i=0;$i<count($Attchment);$i++){
              if(!is_null($Attchment[$i]["name"])){
                  $tmp_file=end(explode('.', $Attchment[$i]["name"]));
                  if($tmp_file=="eml"){
                           $state_anex=false;
                  } 
              }


          }
        return $state_anex;


   }
    public function validRadicado($mail_source, $mail_subject,$mailObjt){
              
             global $ruta_raiz,$db;
             $mail_subject=(!preg_match('//u',$mail_subject))?utf8_encode($mail_subject):$mail_subject;
             $tmp=iconv('UTF-8','UTF-8//ignore',$mail_subject);
             $mail_subject=$tmp; 
             $mail_subject=$this->localSanitize($mail_subject);
             $tmp="recive.eml";
              $state_imap=imap_savebody($mailObjt->getImap(), $tmp, $mailObjt->mail->number);
             $mail_subject=substr($mail_subject,0,500);
             $isql="select radi_nume_radi,mail_body,mail_state,mail_subject from radicado_from_mail where (mail_state>=1) and (mail_source='$mail_source'";
             $isql.=" and mail_subject like '%$mail_subject%') order by log_date limit 6";
             $data=$db->conn->Execute($isql);
             $radicado_tx=""; 
             $estado_mail_tx="";
             //$estado_mail="";
             $eml_str="";
             $eml_size=filesize($tmp); 
             $val_data=false;
              while($data!=null && !$data->EOF){
                 $radicado=$data->fields["RADI_NUME_RADI"];  
                 $path_image=$data->fields["MAIL_BODY"]; 
                 $estado_mail=$data->fields["MAIL_STATE"];   
                 $val_data=$this->compareEML($radicado, $mailObjt->getImap(), $mailObjt->mail->number,$path_image,$tmp);
                 if($val_data){
                    $radicado_tx=$radicado;
                    $val_state==true;
                    $estado_mail_tx=$estado_mail;
                 }
                 $data->MoveNext();
              }
              $eml_str=file_get_contents($tmp);
              unlink($tmp);
              return [$radicado_tx, $estado_mail_tx, $eml_str, $eml_size];
           
            

     }

    public function compareEML($radicado, $imapConection, $mail_number,$mail_path, $source_path){
     
        global $ruta_raiz,$db;
        $radiNumeRadi=($radicado!=null or !is_empty($radicado))?$radicado:"1234";
        $mail_path=($mail_path!=null or !empty($mail_path))?$mail_path:"1234";
        //$headers = imap_fetchheader($imapConection, $mail_number, FT_PREFETCHTEXT);
       // $body = imap_body($imapConection, $mail_number);
        var_dump($mail_path);
        $pathfile = $this->getPath($radiNumeRadi,true);
       // $tmp="recive.eml";
        $tmp=$source_path;
        $state=false;
       // $state_imap=imap_savebody($imapConection, $tmp, $mail_number);
        $tmp_arch=substr($mail_path,0,-3);
        if($tmp_arch[-1]!='.')
             $nameAnnex = $tmp_arch.".eml";
        else
             $nameAnnex=$tmp_arch."eml";    
        var_dump($nameAnnex); 
        $ruta_file=$pathfile.$nameAnnex;       
        var_dump($ruta_file);
        if(file_exists($ruta_file)){

               
                   var_dump($ruta_file);
                    
                 if(sha1_file($ruta_file)==sha1_file($tmp)){

                     $state=true;
                 }else{

                     $state=false;
                 }
                 //$state=($sha1_file($ruta_file)==$sha1_file($tmp))?true:false; 
                 var_dump($state);     

                     
        } 
        //echo "***TAMAÑO EML***********";
        //echo filesize($tmp);
        return $state; 
     }


    public function saveRelationEmailRad($radi_nume_radi,$mail_number,$mail_id,$destinatario,$mail_state,$mail_source,$mail_subject,$type,$img_path)
    {
        /*
        OJO REVISAR
        $radi_nume_radi=0;
        $mail_number=0;
        $mail_id=0;
        */
        //Hacemos disponible la conexion de base de datos
        global $db;

        $date = 'NOW()';   
       $mail_subject=(!preg_match('//u',$mail_subject))?utf8_encode($mail_subject):$mail_subject;      
       $tmp=iconv('UTF-8','UTF-8//ignore',$mail_subject);
       $mail_subject=$tmp; 
       $mail_subject=$this->localSanitize($mail_subject); 
       $mail_subject=substr($mail_subject,0,500);
       if($type == 1){
 
        //Construimos y ejecutamos insert que graba relacion
        $sql = "INSERT INTO RADICADO_FROM_MAIL (RADI_NUME_RADI, MAIL_ID, LOG_DATE,DESTINATARIO, MAIL_STATE, MAIL_SOURCE, MAIL_SUBJECT,MAIL_BODY) VALUES ($radi_nume_radi,$mail_id, $date,'$destinatario',";
        $sql.=$mail_state.", '$mail_source', '$mail_subject', '$img_path')";
        
       }else{

          $sql="UPDATE RADICADO_FROM_MAIL SET MAIL_STATE=".$mail_state." WHERE RADI_NUME_RADI=".$radi_nume_radi;
        }
 
         $rsql = $db->conn->Execute($sql);

        //Validamos y retornamos indicador del proceso
        if ($rsql)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Metodo que obtiene la ruta para almacenamiento de documentos de un radicado,
     * @param integer radi_nume_radi numero del radicado
     * @param bool isAnnex Bandera que indica si se trata de un anexo
     * @return  string pathfile
     */
    
public function convertBodytoPDF($body,$subject,$numrad,$date,$fromMail,$listaAdjuntos,$from){

global $db,$ruta_raiz; 
$path=$this->getPath($numrad,true);
///Aqui va el cargue del documento como anexo///

//$db->conn->debug=true;
 
 $user = $this->getInfoRadicador();
 $numberAnnex = intval($this->getNumberAnnex($numrad))+1;
 $nameAnnex = $numrad.substr('00000'.$numberAnnex,-5);
 $path.= $nameAnnex.'.html';
 $data=fopen($path,'w');
 if($data){

 fwrite($data,$body);
 fclose($data); 
 $description = utf8_decode("Adjunto a correo electronico:$nameAnnex");
        //Obtenemos peso del archivo
 $sizeAnnex = filesize($path);
        //Fecha
 $dateAnnex= $db->conn->OffsetDate(0,$db->conn->sysTimeStamp);
        
        //Tipo de anexo
$extension="html";     
$typeAnnex = $this->getTypeAnnex($extension);
        //Validamos que no se haya generado ningun error
        //Construimos insert que crea el anexo en base de datos
        $sql = "INSERT INTO
                            ANEXOS
                            (ANEX_CODIGO,
                            ANEX_RADI_NUME,
                            ANEX_TIPO,
                            ANEX_TAMANO,
                            ANEX_SOLO_LECT,
                            ANEX_CREADOR,
                            ANEX_DESC,
                            ANEX_NUMERO,
                            ANEX_NOMB_ARCHIVO,
                            ANEX_ESTADO,
                            SGD_REM_DESTINO,
                            ANEX_FECH_ANEX,
                            ANEX_BORRADO)
                        VALUES
                            ('$nameAnnex',
                            $numrad,
                            $typeAnnex,
                            $sizeAnnex,
                            'n',
                            '".$user['usua_login']."',
                            '$description'
                            ,$numberAnnex
                            ,'$nameAnnex.$extension'
                            ,0
                            ,1
                            ,$dateAnnex
                            ,'N')";

                     $rInsertData=$db->conn->Execute($sql);                            


}
// var_dump($path);


}


  public function getPath($radi_nume_radi=0,$isAnnex=false)
    {
        //Hacemos disponible las variables globales
        global $ruta_raiz;

        //Construimos ruta a retornar
        //$pathfile = $ruta_raiz.'/bodega/';
        $pathfile = BODEGA.'/';
        $pathfile.= substr($radi_nume_radi,0,4).'/'; //Extraemos el anio del numero de radicado
        $pathfile.= substr($radi_nume_radi,4,3).'/'; //Extraemos la dependencia

        //Si se trata de un anexo almacenamos en docs
        if ($isAnnex)
        {
            $pathfile.= 'docs/';
        }

        //Retornamos
        return $pathfile;
    }

    /**
     * Metodo que crea la imagen del radicado
     * @param  integer $radi_nume_radi Numero del radicado
     * @param  string  $body           Cuerpo del mensaje de correo
     * @param  string  $subject        Asunto del correo
     * @param  string  $mail_radica    Correo definido para radicacion
     * @param  string  $mail_mensaje   Correo desde donde se envio el mensaje
     * @return Boolean $result         Indicador del proceso
     */
    public function createImageRadi($radi_nume_radi,$body,$subject,$mail_radica,$mail_mensaje,$anexos)
    {
        //$radi_nume_radi=0,$body='',$subject='',$mail_radica='',$mail_mensaje=''
        $mail_radica=utf8_encode($mail_radica);
        $subject=utf8_decode($subject);
        //Hacemos disponible la conexion a base de datos
        global $db;
        global $ruta_raiz;
         date_default_timezone_set("America/Bogota");

        $anexConcat="Sin Anexos";
        //$sql_usua="select usua_nomb from usuario where usua_login=$mail_radica";
        //$rs_us=$db->conn->Execute($sql_usua);
        //if($rs_us!=null && !$rs_us->EOF){
        //    $mail_radica=$rs_us->fields["USUA_NOMB"];

       // }         
        if(!empty($this->anexState['sucess'])){
             
             $anexConcat="";
             for($i=0;$i<count($this->anexState['sucess']);$i++){
                      $anexConcat.=(count($this->anexState['sucess'])-$i>1)?$this->anexState['sucess'][$i].",":$this->anexState['sucess'][$i].".";
                      if($i>=11){

                      $lastDigit=intval($this-anexState['sucess'][$i][-1]);
                      $i=count($this->anexState['sucess']);  
                      //$lastDigit+=$i; 
                      $anexConcat.=' Hasta el ..'.$lastDigit.'.';
                      }
             }
        }
        $anexConcat = $anexos . " Anexos";
        //Definimos ruta donde almacenar el archivo
        $path = $this->getPath($radi_nume_radi);
        $pathfile = $path.$radi_nume_radi.'.pdf';
        //verificamos si existe imagen de un codigo de barras anterior para eliminarlo

        //$date=$this->dateMail;
        //$date_t=reset((explode('+',$date)));
        //$date_t=strtotime('d/m/Y H:i',trim($date_t));
        $date_t=date('d/m/Y H:i');              
        //Damos configuracion del pdf a crear
        $pdf= new PDF();
        $pdf->AddPage();
        /*$pdf->SetFont('Arial','',10);
        $pdf->MultiCell(180,6,'',0,'R');
        $pdf->SetXY(50,45);
        $pdf->Ln(35);*/
        $pdf->SetMargins(20,40,20);
        //$pdf->SetXY(50,195);
        $pdf->SetFont('Arial','B',16);
        //$pdf->Ln(35);
        $pdf->Image('logo1.jpeg',20,25,50,20,'jpeg'); 
        $pdf->Code128(130,25,$radi_nume_radi,55,15);
        $pdf->Ln(35);
        $pdf->MultiCell(180,6,'Radicado No. '.$radi_nume_radi,0,'R');
        $pdf->ln(3);
        $pdf->MultiCell(180,6,'Fecha: '.$date_t,0,'R');

        //$pdf->Ln(30);
        //$pdf->SetFont('Arial','B',16);
        //$pdf->MultiCell(180,6,utf8_decode('Remitente:  '.$mail_radica),0,'L');

        $pdf->Ln(35);
        $pdf->MultiCell(180,6,utf8_decode('Mensaje recibido desde Mail: '.$mail_mensaje),0,'L');

        $pdf->Ln(5);
        $pdf->MultiCell(180,6,'Asunto: '.$subject,0,'L');
        $pdf->Ln(5);
        $pdf->MultiCell(180,6,'Anexos: '.$anexConcat,0,'L');
        //$pdf->WriteHTML($body);
        $pdf->Ln(15);
        $pdf->SetFont('Arial','',15);
        $text = 'Para ver los documentos adjuntos al correo, debe ingresar por el enlace de la "Fecha de radicado" y seleccionar la pestaña de "Documentos anexos"';
        $pdf->MultiCell(170,6,utf8_decode($text),0,'J');
        $text2='Radicación automática enviada al usuario de ORFEO:'.$mail_radica.', para su respectivo direccionamiento'; 
        $pdf->ln(15);    
        $pdf->SetFont('Arial','B',12);
        $pdf->MultiCell(170,6,utf8_decode($text2),0,'J');
        $pdf->ln(100);
        
        $pdf->Image('logo3.jpeg',25,250,60,15,'jpeg'); 
        $pdf->Image('logo2.jpeg',95,250,90,15,'jpeg');
        $result = $pdf->Output($pathfile,'F');

        //Actualizamos ruta de la imagen creada
        
        $path = strstr($pathfile, BODEGA);
        $path = str_replace(BODEGA,'',$path);
        $update="UPDATE RADICADO SET RADI_PATH='$path' where RADI_NUME_RADI='$radi_nume_radi'";
        $db->conn->Execute($update);

        return $result;
    }

    /**
     * Metodo que obtiene el numero de anexos de un radicado
     * @param  int      $radi_nume_radi Numero del radicado
     * @return string   $qtyAnnex Cantidad de anexos hayados
     */
    function getNumberAnnex($radi_nume_radi)
    {
        //Habilitamos base de datos
        global $db;

        //Inicializamos
        $qtyAnnex = 0;

        //Construimos sentencia que obtiene el numero de anexos del radicado
        $sql ="SELECT MAX(ANEX_NUMERO) AS NUM_ANEX FROM ANEXOS WHERE ANEX_RADI_NUME = $radi_nume_radi";

        //Ejecutamos consulta
        $rs = $db->conn->Execute($sql);

        //Verififamos que se hayan generado resultados
        if($rs && isset($rs->fields['NUM_ANEX']))
        {
            $qtyAnnex = $rs->fields['NUM_ANEX'];
        }

        //Retornamos
        return  $qtyAnnex;
    }

    /**
     * Metodo que obtiene el tipo de anexo
     *
     * @param sting $extension extencion del archivo
     * @return int
     *
     */

    function getTypeAnnex($extension=''){

        global $db;

        $sql ="SELECT ANEX_TIPO_CODI FROM ANEXOS_TIPO WHERE ANEX_TIPO_EXT='".strtolower($extension)."'";

        $typeAnnex = 0;

        $rs= $db->conn->Execute($sql);

        if($rs && isset($rs->fields['ANEX_TIPO_CODI']))
        {
            $typeAnnex=$rs->fields['ANEX_TIPO_CODI'];
        }

        return $typeAnnex;
    }

    /**
     *
     * funcion que crea los anexos, de acuerdo al arreglo recibido
     *
     * @param string $radiNume numero del radicado al cual se adiciona el anexo
     * @param $files arreglo que contiene los archivos
     * @param $mailRadica usuario de la persona que radica
     * @return string mensaje de error en caso de fallo o el numero del anexo en caso de exito
     */

    function createAttachmentsAUTH($radiNumeRadi,$files)
    {   
        //Hacemos disponibles las variables globales
        global $ruta_raiz,$db;

        //Obtenemos informacion del usuario que radica
        $user = $this->getInfoRadicador();

        //inicializamos
        $annex['sucess'] = array();
        $annex['fail'] = array();

        //Iteramos los archivos
        foreach ($files as $file)
        {
            
            //Definimos una descripcion para el anexo
            $description = "Adjunto a correo electr&oacute;nico: " . $file["nombre"];

            //--*Definimos ruta--*-//
            $pathfile = $this->getPath($radiNumeRadi,true);
            $path_base=$pathfile;
         
            //Obtenemos numero del anexo a crear
            $numberAnnex = intval(trim($this->getNumberAnnex($radiNumeRadi)))+1;
            //echo $numberAnnex;
             //Extraemos extension
            //$extension= substr($file['filename'],strrpos($file['filename'],".")+1);
            ///$extension=end(explode('.',$file['filename']));
            ///$extension = pathinfo($file->getName(), PATHINFO_EXTENSION);
            $extension = $file["extension"];
            ///$extension="pdf";
            //Se define nombre del anexo
            $nameAnnex = $radiNumeRadi.substr('00000'.$numberAnnex,-5);

            $pathfile.= $nameAnnex.'.'.$extension;

            //Ubicamos el anexo en la bodega
            $flag=true;
            $flag_copy=true;
            
            if($flag==true){
                // Guarda el archivo en el sistema de archivos
                //file_put_contents('RUTA_DE_GUARDADO/'.$attachment->getName(), $file["contenido"]);

                file_put_contents($pathfile, $file["contenido"]);

                  //Obtenemos peso del archivo
                  $sizeAnnex = filesize($pathfile);
      
                  //Fecha
                  $dateAnnex= $db->conn->OffsetDate(0,$db->conn->sysTimeStamp);
      
                  //Tipo de anexo
                  $typeAnnex = $this->getTypeAnnex($extension);
                  if(!$error)
                  {
                     //$db->conn->debug=true; 
                      //Construimos insert que crea el anexo en base de datos
                      $sql = "INSERT INTO
                                      ANEXOS
                                      (ANEX_CODIGO,
                                      ANEX_RADI_NUME,
                                      ANEX_TIPO,
                                      ANEX_TAMANO,
                                      ANEX_SOLO_LECT,
                                      ANEX_CREADOR,
                                      ANEX_DESC,
                                      ANEX_NUMERO,
                                      ANEX_NOMB_ARCHIVO,
                                      ANEX_ESTADO,
                                      SGD_REM_DESTINO,
                                      ANEX_FECH_ANEX,
                                      ANEX_BORRADO)
                                  VALUES
                                      ('$nameAnnex',
                                      $radiNumeRadi,
                                      $typeAnnex,
                                      $sizeAnnex,
                                      'n',
                                      '".$user['usua_login']."',
                                      '$description'
                                      ,$numberAnnex
                                      ,'$nameAnnex.$extension'
                                      ,0
                                      ,1
                                      ,$dateAnnex
                                      ,'N')";
                      
                      //Realizamos insert del anexo                
                      $rsInsert = $db->conn->Execute($sql);      
                      //Validamos si se realizo insert
                      if ($rsInsert)
                      {
                          $annex['sucess'][] = $nameAnnex;
                          $this->annex_valid[$nameAnnex]="";
                      }
                      else
                      {
                          $annex['fail'][] = $nameAnnex;
                          $this->annex_valid[$nameAnnex]=$file['filename'];
                      }
      
                  }

            }    
          

            //Validamos que no se haya generado ningun error
           
          
           usleep(40);
           $numberAnnex=0;
        }
        //Actualizamos el radicado en su campo numero de folios
        $updateRadi='UPDATE RADICADO SET RADI_NUME_ANEXO='.count($annex['sucess']).' WHERE RADI_NUME_RADI='.$radiNumeRadi;
        $db->conn->Execute($updateRadi);
        
        return count($files);
    }
    /**
     * Metodo que imprime mensaje
     */
    public function printMsg($msg='',$type='success')
    {
        if ($type=='success')
        {
            //echo '<div style="color:green;padding:2px;border:1px solid green;">'.$msg.'</div>';
        }
        else
        {
            //echo '<div style="color:red;padding:2px;border:1px solid red;">'.$msg.'</div>';
        }
    }

    /**
     * Metodo que crea las tablas para la radicacion mail
     */

    /**
    * Valida si un correo obtenido existe en el log
    * @param $mail_number
    * @return true or false
    */
    public function checkMailExistInLog($mail_number, $mail_id) {
        
        global $db;
        $sql = "SELECT COUNT(MAIL_NUMBER) as QTY FROM RADICADO_FROM_MAIL WHERE MAIL_NUMBER = {$mail_number} AND MAIL_ID={$mail_id}";
        $result = $db->conn->Execute($sql);

        if (isset($result->fields['QTY']) && $result->fields['QTY'] > 0) {
            return true;
        }
        else if (isset($result->fields['QTY']) && $result->fields['QTY'] == 0) {
            return false;
        }

        return false;
    }
    
    /**
     * Metodo que busca entidades mediante un correo
     * parametro email->string
     * Retorna array
     */
    public function buscarEntidades($correo) {
        global $db;
        $resultado=array();
        $sql="select
              nit_de_la_empresa as SGD_DIR_DOC,
              nombre_de_la_empresa as SGD_DIR_NOMREMDES,
              nombre_de_la_empresa as SGD_DIR_NOMBRE,
              direccion as SGD_DIR_DIRECCION,
              telefono_1 as SGD_DIR_TELEFONO,
              email as SGD_DIR_MAIL,
              id_cont as ID_CONT,
              id_pais AS ID_PAIS,
              codigo_del_departamento as DPTO_CODI,           
              codigo_del_municipio as MUNI_CODI,
              0 as SGD_SEC_CODIGO,
              1 AS SGD_DIR_TIPO
                           from bodega_empresas where email='$correo'";
        $result = $db->conn->Execute($sql);
        while ($result!=null && !$result->EOF) {
            foreach ($result->fields as $key=>$value){
                $result->fields[$key]=utf8_encode($value);
            }
            $resultado[]=$result->fields;
            $result->MoveNext();
        }
        return $resultado;
    }
    
    
    public function buscarEmpresas($correo) {
        global $db;
        $resultado=array();
        $sql="select
              sgd_oem_nit as SGD_DIR_DOC,
              sgd_oem_oempresa as SGD_DIR_NOMREMDES,
              sgd_oem_oempresa as SGD_DIR_NOMBRE,
              sgd_oem_direccion as SGD_DIR_DIRECCION,
              sgd_oem_telefono as SGD_DIR_TELEFONO,
              sgd_oem_email as SGD_DIR_MAIL,
              id_cont as ID_CONT,
              id_pais AS ID_PAIS,
              dpto_codi as DPTO_CODI,
              muni_codi as MUNI_CODI,
              0 as SGD_SEC_CODIGO,
              1 AS SGD_DIR_TIPO,
              tdid_codi as SGD_DIR_TDOC
              from sgd_oem_oempresas where sgd_oem_email='$correo'";
        $result = $db->conn->Execute($sql);
        while ($result!=null && !$result->EOF) {
            foreach ($result->fields as $key=>$value){
                $result->fields[$key]=utf8_encode($value);
            }
            $resultado[]=$result->fields;
            $result->MoveNext();
        }
        return $resultado;
    }
    
    
    public function buscarCiudadano($correo) {
        global $db;
        $resultado=array();
        $sql="select
              sgd_ciu_cedula as SGD_DIR_DOC,
              decode(sgd_ciu_nombre,null,'',sgd_ciu_nombre)||decode(sgd_ciu_apell1,null,'',sgd_ciu_apell1)||decode(sgd_ciu_apell2,null,'',sgd_ciu_apell2)  AS SGD_DIR_NOMREMDES,
              decode(sgd_ciu_nombre,null,'',sgd_ciu_nombre)||decode(sgd_ciu_apell1,null,'',sgd_ciu_apell1)||decode(sgd_ciu_apell2,null,'',sgd_ciu_apell2) AS SGD_DIR_NOMBRE,
              sgd_ciu_direccion as SGD_DIR_DIRECCION,
              sgd_ciu_telefono as SGD_DIR_TELEFONO,
              sgd_ciu_email as SGD_DIR_MAIL,
              id_cont as ID_CONT,
              id_pais AS ID_PAIS,
              dpto_codi as DPTO_CODI,
              muni_codi as MUNI_CODI,
              0 as SGD_SEC_CODIGO,
              1 AS SGD_DIR_TIPO,
              tdid_codi AS SGD_DIR_TDOC
              from sgd_ciu_ciudadano 
                where sgd_ciu_telefono='$correo'";
        $result = $db->conn->Execute($sql);
        while ($result!=null && !$result->EOF) {
            foreach ($result->fields as $key=>$value){
                $result->fields[$key]=utf8_encode($value);
            }
            $resultado[]=$result->fields;
            $result->MoveNext();
        }
        return $resultado;
    }
    
    
    public function buscarFuncionario($correo) {
        global $db;
        $resultado=array();
        $sql="select
              U.USUA_DOC as SGD_DIR_DOC,
              U.USUA_NOMB as SGD_DIR_NOMREMDES,
              U.USUA_NOMB as SGD_DIR_NOMBRE,
              (select d.DEPE_NOMB from DEPENDENCIA d where d.DEPE_CODI=U.DEPE_CODI) as SGD_DIR_DIRECCION,
              U.USU_TELEFONO1 as SGD_DIR_TELEFONO,
              U.USUA_EMAIL as SGD_DIR_MAIL,
              1 as ID_CONT,
              170 as ID_PAIS,
              11 as DPTO_CODI,
              1 as MUNI_CODI,
              0 as SGD_SEC_CODIGO,
              1 as SGD_DIR_TIPO,
              0 as SGD_DIR_TDOC
              from USUARIO U where USUA_EMAIL='$correo' AND USUA_ESTA = 1 AND DEPE_CODI < 100";
        $result = $db->conn->Execute($sql);
        while ($result!=null && !$result->EOF) {
            foreach ($result->fields as $key=>$value){
                $result->fields[$key]=utf8_encode($value);
            }
            $resultado[]=$result->fields;
            $result->MoveNext();
        }
        return $resultado;
    }

    function getAccessToken(){
     $tenantId = TENANT_ID;
     $guzzle = new \GuzzleHttp\Client();

     //$guzzle = new \GuzzleHttp\Client(['verify' => false,]);


     $url = 'https://login.microsoftonline.com/' . $tenantId .'/oauth2/v2.0/token';
     $token = json_decode($guzzle->post($url,[
         'form_params' => [
             'client_id' => CLIENT_ID,
             'client_secret' => CLIENT_SECRET,
             'scope' => 'https://graph.microsoft.com/.default',
             'grant_type' => 'client_credentials',
            ],
        ])->getBody()->getContents());
     $accessToken = $token->access_token;
     
     return $accessToken;

    }
    
    public function createEMLFile($mensaje, $mail_number,$radicado) {
        //Creacion de archivo
        $radiNumeRadi=$radicado;
        
        //Hacemos disponibles las variables globales
        global $ruta_raiz,$db;
        //Obtenemos informacion del usuario que radica
        $user = $this->getInfoRadicador();
        
        //Definimos una descripcion para el anexo
        $description = utf8_decode("Contenido original del correo electr&oacute;nico");
        
        //Definimos ruta
        $pathfile = $this->getPath($radiNumeRadi,true);
        
        //Obtenemos numero del anexo a crear
        $numberAnnex = $this->getNumberAnnex($radiNumeRadi)+1;
        
        //Extraemos extension
        $extension='eml';
        
        //Se define nombre del anexo
        $nameAnnex = $radiNumeRadi.substr('00000'.$numberAnnex,-5);
        
        $pathfile.= $nameAnnex.'.eml';
        
        //Ubicamos el anexo en la bodega
        /*
        if($eml_str==null || $eml_str==""){
        imap_savebody($imapConection, $pathfile, $mail_number);
        }else{

              file_put_contents($pathfile, $eml_str);
        }
        */

        $handle = fopen($pathfile, 'w') or die('Cannot open file: '.$pathfile);
        //fclose($pathfile);

        //$eml_str=file_get_contents($content);

        // Leer el radicado con la librería Graph en PHP y almacenarlo en una variable
//$radicado = graph_leer_radicado();

// Crear un archivo EML vacío
//$archivo_eml = fopen($pathfile, "w");

//$email_date = date("r", strtotime($mensaje->getReceivedDateTime()));
//$email_date = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $mensaje->getReceivedDateTime())->format('r');

date_default_timezone_set('America/New_York'); // Establecer la zona horaria deseada
$email_date = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $mensaje->getReceivedDateTime()->format('Y-m-d\TH:i:s\Z')); // Crear el objeto DateTime con el formato deseado
$email_date_formatted = $email_date->format('r'); // Formatear la fecha y hora


// Escribir el contenido del correo electrónico en el archivo EML
fwrite($handle, "From: " . $mensaje->getFrom()->getEmailAddress()->getAddress() . "\n");
fwrite($handle, "To: " . "correo@cra.gov.co". "\n");
fwrite($handle, "Subject: " . $mensaje->getSubject() . "\n");
fwrite($handle, "Date: " . $email_date_formatted . "\n");
fwrite($handle, "Content-Type: text/html; charset=utf-8\n\n" . $mensaje->getBody()->getContent());
fclose($handle);


        //file_put_contents($pathfile, $content);


        //Obtenemos peso del archivo
        $sizeAnnex = filesize($pathfile);
        
        //Fecha
        $dateAnnex= $db->conn->OffsetDate(0,$db->conn->sysTimeStamp);
        
        //Tipo de anexo
        $typeAnnex = $this->getTypeAnnex($extension);
        
        //Validamos que no se haya generado ningun error
        //Construimos insert que crea el anexo en base de datos
        $sql = "INSERT INTO
                            ANEXOS
                            (ANEX_CODIGO,
                            ANEX_RADI_NUME,
                            ANEX_TIPO,
                            ANEX_TAMANO,
                            ANEX_SOLO_LECT,
                            ANEX_CREADOR,
                            ANEX_DESC,
                            ANEX_NUMERO,
                            ANEX_NOMB_ARCHIVO,
                            ANEX_ESTADO,
                            SGD_REM_DESTINO,
                            ANEX_FECH_ANEX,
                            ANEX_BORRADO)
                        VALUES
                            ('$nameAnnex',
                            $radiNumeRadi,
                            $typeAnnex,
                            $sizeAnnex,
                            'n',
                            '".$user['usua_login']."',
                            '$description'
                            ,$numberAnnex
                            ,'$nameAnnex.$extension'
                            ,0
                            ,1
                            ,$dateAnnex
                            ,'N')";
                            
                            //Realizamos insert del anexo
                            $rsInsert = $db->conn->Execute($sql);
                    return $nameAnnex.'.'.$extension;        
                            
    }

    public function createEMLFile2($radicado,$eml) {
        try {
            //Creacion de archivo
            $radiNumeRadi=$radicado;
            
            //Hacemos disponibles las variables globales
            global $db;
            $ruta_raiz = "..";
            //Obtenemos informacion del usuario que radica
            $user = $this->getInfoRadicador();
            
            //Definimos una descripcion para el anexo
            $description = utf8_decode("Contenido original del correo electr&oacute;nico");
            
            //Definimos ruta
            $pathfile = $this->getPath($radiNumeRadi,true);
            
            //Obtenemos numero del anexo a crear
            $numberAnnex = $this->getNumberAnnex($radiNumeRadi)+1;
            
            //Extraemos extension
            $extension='eml';
            
            //Se define nombre del anexo
            $nameAnnex = $radiNumeRadi.substr('00000'.$numberAnnex,-5);
            
            $pathfile.= $nameAnnex.'.eml';
            
            //Ubicamos el anexo en la bodega
            if (file_put_contents($pathfile, $eml) === false) {
                // El archivo se creó correctamente
                //return "NO se  guardo archivo bien";
                return $eml;
            }
            else {
                //file_put_contents($pathfile, $eml);

                //Obtenemos peso del archivo
                $sizeAnnex = filesize($pathfile);
                
                //Fecha
                $dateAnnex= $db->conn->OffsetDate(0,$db->conn->sysTimeStamp);
                
                //Tipo de anexo
                $typeAnnex = $this->getTypeAnnex($extension);
                
                //Validamos que no se haya generado ningun error
                //Construimos insert que crea el anexo en base de datos
                $sql = "INSERT INTO
                                    ANEXOS
                                    (ANEX_CODIGO,
                                    ANEX_RADI_NUME,
                                    ANEX_TIPO,
                                    ANEX_TAMANO,
                                    ANEX_SOLO_LECT,
                                    ANEX_CREADOR,
                                    ANEX_DESC,
                                    ANEX_NUMERO,
                                    ANEX_NOMB_ARCHIVO,
                                    ANEX_ESTADO,
                                    SGD_REM_DESTINO,
                                    ANEX_FECH_ANEX,
                                    ANEX_BORRADO)
                                VALUES
                                    ('$nameAnnex',
                                    $radiNumeRadi,
                                    $typeAnnex,
                                    $sizeAnnex,
                                    'n',
                                    '".$user['usua_login']."',
                                    '$description'
                                    ,$numberAnnex
                                    ,'$nameAnnex.$extension'
                                    ,0
                                    ,1
                                    ,$dateAnnex
                                    ,'N')";
                                    
                                    //Realizamos insert del anexo
                                    $rsInsert = $db->conn->Execute($sql);
                //return $nameAnnex.'.'.$extension;        
                //return $sql;
                return $pathfile;                
            }
        } catch (Exception $e) {
            // Capturamos cualquier excepción que se pueda lanzar
            //error_log("Error al crear el archivo EML: " . $e->getMessage());
            // Puedes agregar más lógica aquí, como enviar un correo electrónico de alerta,
            // registrar el error en un log, o mostrar un mensaje al usuario.
            //return false; // O cualquier otro valor que indique un error
            return $e->getMessage();
        }
    }

    public function buscarCorreo($body,$metodo=0) {
        
        switch ($metodo) {
            case 0:
                $posicion=strstr($body,'&lt;');
                $texto=substr($posicion,4,100);
                /*Agnadimos validaciones mediante reemplazamientos de caracteres y expresiones regulares*/
                $correo = strstr($texto,'&gt;',true);
            break;
            case 1:
                $posicion=strstr($body,'mailto');
                $texto=substr($posicion,7,100);
                /*Agnadimos validaciones mediante reemplazamientos de caracteres y expresiones regulares*/
                $correo = strstr($texto,'>',true);
                break;
            
            default:
                $posicion=strstr($body,'&lt;');
                $texto=substr($posicion,4,100);
                /*Agnadimos validaciones mediante reemplazamientos de caracteres y expresiones regulares*/
                $correo = strstr($texto,'&gt;',true);
            break;
        }

        $final=str_replace('"','',$correo);
        $final = str_replace("=", "",$final);
        $final=str_replace (array("\r\n", "\n", "\r"), ' ', $final);
        $final = str_replace(array("=",':',' '), "",$final);
        
        return $final;
    } 
    /*
     * Esta funcion sirve para verificar existencia de caracteres especiales en cadenas de texto
     * y codificarlas en caso de que sean necesario. queda pendiente por desarrollar
     * */
    function depurarString($string) {
        
        
        if(strlen(utf8_decode($string))===strlen(utf8_decode(utf8_decode($string))) && strlen($string)>strlen(utf8_decode($string))){
            $string=$string;
        }
        
        
        if(strlen(utf8_decode(utf8_decode($string)))===strlen(utf8_decode(utf8_decode(utf8_decode($string)))) && strlen(utf8_decode($string))>strlen(utf8_decode(utf8_decode($string)))){
            $string=utf8_decode($string);
        }
        
        if(strlen($string)===strlen(utf8_decode($string)) && strlen(utf8_encode($string))>strlen($string)){
            $string=utf8_encode($string);
        }
        
        
        return $string;
    }
    /*
     * Esta funcion sirve para verificar cual fue el
     * ultimo destinatario
     * */
    function ultimoDestinatario($id_email) {
        global $db;
    if($db->driver=="oracle"){
        $sqlLastDest= "SELECT TOP 1 DESTINATARIO 
                       FROM radicado_from_mail where mail_id={$id_email}
               ORDER BY LOG_DATE DESC";
        }else{
    
        $sqlLastDest= "SELECT DESTINATARIO 
               FROM radicado_from_mail where mail_id={$id_email}
                       ORDER BY LOG_DATE DESC LIMIT 1";
    
    }
        $rsLastDest=$db->conn->Execute($sqlLastDest);

        $ultimoDes=(!empty($rsLastDest) && !$rsLastDest->EOF && $rsLastDest->fields['DESTINATARIO']!='')?$rsLastDest->fields['DESTINATARIO']:0;
        
        return $ultimoDes;
    }
    
    /*
     * Esta funcion sirve para verificar cual fue el
     * ultimo destinatario
     * */
    function getDestinatario($mail_id) {
        global $db;
         
        //var_dump($db) ;
        //echo ("select FK_USUA_LOGIN from MAIL_DESTINATARIOS WHERE FK_MAIL_ID={$mail_id} ORDER BY FK_MAIL_ID ASC");


        //Asignaremos los datos destinatarios por correo
        $sqlDestinatarios="select FK_USUA_LOGIN from MAIL_DESTINATARIOS WHERE FK_MAIL_ID={$mail_id} ORDER BY FK_MAIL_ID ASC";
    $db->conn->SetFetchMode(2);
        $rsDestinatarios = $db->conn->Execute($sqlDestinatarios);
        $destinatariosCorreo=array();
        while( !empty($rsDestinatarios) && !$rsDestinatarios->EOF){
            $destinatariosCorreo[]=$rsDestinatarios->fields['FK_USUA_LOGIN'];
            $rsDestinatarios->MoveNext();
        }
        $ultimoDestinatario=$this->ultimoDestinatario($mail_id);//Ultimo destinatario seleccionado a radicar

        $totalDes=count($destinatariosCorreo);//Total destinatarios
        if($ultimoDestinatario === 0){
            $destinatarioRadicar=$destinatariosCorreo[0];
        }else{
            if(!in_array($ultimoDestinatario,$destinatariosCorreo)){
                $destinatarioRadicar=$destinatariosCorreo[0];
            }else{
                $indexActual=array_search($ultimoDestinatario,$destinatariosCorreo);
                $indexActual=(($indexActual+1)>=$totalDes)?0:$indexActual+1;
                $destinatarioRadicar=$destinatariosCorreo[$indexActual];
            }
        }
       
        return $destinatarioRadicar;
        
    }
    
}
?>
