<?php
/**
 * Class Administrar radicacion mail
 *
 * Clase que realiza el proceso para parametrizar informacion
 * de buzon del cual se hara lectura
 *
 */
$ruta_raiz = '../';
include_once("$ruta_raiz/config.php");
include_once("$ruta_raiz/include/db/ConnectionHandler.php");

$db = new ConnectionHandler("$ruta_raiz");
$db->conn->SetFetchMode(ADODB_FETCH_NUM);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
//$db->conn->debug=true;
class ManageRadMail {

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
     * @var array mailList
     */
    public  $mailList;    

    /**
     * @var string message
     */
    public  $message;    

    /**
     * @var string mail_host
     */
    public  $mail_host;    

    /**
     * Constructor
     *
     * Inicializa los atributos de la clase.
     */
    public function __construct()
    {
        $this->mail_id    = '';
        $this->mail       = '';
        $this->mail_pass  = '';
        $this->mail_user  = '';
        $this->mail_dest_alt = '';
        $this->mail_subject_filter  = '';
        $this->mailList   = array();
        $this->message    = '';
        $this->mail_host  = '';
    }    

    /**
    * Obtenemos correos configurados en base de datos para radicacion
    */
    public function loadMail()
    {
        //Habilitamos la conexion a base de datos
        global $db;

        //Construimos sentencia
        $sql = "SELECT * FROM radicado_mail WHERE mail_id = {$this->mail_id}";
        $rsql= $db->conn->Execute($sql);                
        
        $this->mail_id  = $rsql->fields['MAIL_ID'];
        $this->mail     = $rsql->fields['MAIL'];
        $this->mail_pass= $rsql->fields['MAIL_PASS'];
        $this->mail_dest_alt = $rsql->fields['MAIL_DEST_ALT'];        
        $this->mail_subject_filter = $rsql->fields['MAIL_SUBJECT_FILTER'];        
        $this->mail_host = $rsql->fields['MAIL_HOST'];        
    }

    /**
    * Obtenemos correos configurados en base de datos para radicacion
    */
    public function getListMailRadica()
    {
        //Habilitamos la conexion a base de datos
        global $db;
        //|$db->conn->debug=true;
        //Construimos sentencia
        $sql = "SELECT * FROM radicado_mail";
        $rsql= $db->conn->Execute($sql);
        $obj = new stdClass();
        //$db->conn->debug=true; 
        //Iteramos
        while(!$rsql->EOF)
        {
            $user = explode('@',$rsql->fields['MAIL']);
            $obj = new stdClass();
            $obj->mail_id  = $rsql->fields['MAIL_ID'];
            $obj->mail     = $rsql->fields['MAIL'];
            $obj->mail_pass= $rsql->fields['MAIL_PASS'];
            //Buscaremos destinatarios seleccionados
            $sqlUsuario="select md.FK_USUA_LOGIN as USUA_LOGIN, u.depe_codi AS DEPE_CODI from MAIL_DESTINATARIOS md,usuario u
                     WHERE FK_MAIL_ID={$rsql->fields['MAIL_ID']} AND
                     md.FK_USUA_LOGIN=u.USUA_LOGIN
                     ORDER BY md.FK_MAIL_ID ASC";
           
            $rsUsuarios = $db->conn->Execute($sqlUsuario);
            if($rsUsuarios->EOF){
                $obj->mail_dest_alt=('<b>No se encontraron usuarios</b>');
            }else{
                $depe='0';
                $dataReturn = "<select name='destinatarios_{$this->mail_id}' class='custom-select' readonly";
                $dataReturn.= "<optgroup label='".$rsUsuarios->fields['DEPE_CODI']."'>";
                while (!$rsUsuarios->EOF) {
                    $auxDepe = $rsUsuarios->fields['DEPE_CODI'];
                    $auxUsua = $rsUsuarios->fields['USUA_LOGIN'];
                    //$auxUsuaNomb = utf8_encode($rsUsuarios->fields['USUA_NOMB']);
		    //echo $auxDepe;
		    if($rsUsuarios->fields['DEPE_CODI'] != $depe){
                        if($depe != $auxDepe && $depe!='0' )$dataReturn.= '</optgroup>';
                        
                        $aux = $rsUsuarios->fields['DEPE_CODI'];
                        $depe= $rsUsuarios->fields['DEPE_CODI'];
                        $dataReturn.= "<optgroup label='$depe'>";
                    }
                    $dataReturn.= "<option value='$auxUsua'>$auxUsua</option>";
                    $rsUsuarios->MoveNext();
                }
                $dataReturn.='</select>';
                $obj->mail_dest_alt = $dataReturn;
            }
           // echo $obj->mail_dest_alt;
            $obj->mail_user= (isset($user[0]))?$user[0]:'';
            $obj->mail_subject_filter = $rsql->fields['MAIL_SUBJECT_FILTER'];
            $obj->mail_host = $rsql->fields['MAIL_HOST'];
	    $this->mailList[] = $obj;
	    
             
            //Movemos el puntero
            $rsql->MoveNext();
	}
	return $this->mailList;
	//  var_dump($this->mailList);
    }

    public function getTableMailsParameterized() {

    	//Cargamos listado de correos
	
        $this->getListMailRadica();
	    
//	var_dump($this->mailList);
      // echo "Buenas Noches";	
	    
	$table = file_get_contents('radmail_manage.tpl');

    	$mails = '';

    	foreach ($this->mailList as $obj) {
    		$mails.= '<tr>';
    		$mails.= '<td class="listado1 center"> <b>'.$obj->mail_id.'<b></td>';
    		$mails.= '<td class="listado1">'.$obj->mail_host.'</td>';
    		$mails.= '<td class="listado1">'.$obj->mail.'</td>';
    		$mails.= '<td class="listado1">*********</td>';
    		$mails.= '<td class="listado1">'.$obj->mail_dest_alt.'</td>';
    		$mails.= '<td class="listado1">'.$this->buildActions($obj->mail_id).'</td>';
    		$mails.= '</tr>';
    	}

    	if ($mails == '') {

    		$mails = '<tr class="msg-info"><td colspan="6">Sin resultados</td></tr>';
    	}
    	
    	$s = array();
    	$s[] = '[!mails]';
    	$s[] = '[!message]';
    	$s[] = '[!mail_id]';
    	$s[] = '[!mail]';
    	$s[] = '[!filter_by_subject]';
    	$s[] = '[!dest_alt]';
    	$s[] = '[!pass]';
        $s[] = '[!mail_host]';

    	$r = array();
    	$r[] =  $mails;
    	$r[] =  $this->message;
    	$r[] =  $this->mail_id;
    	$r[] =  $this->mail;
    	$r[] =  $this->mail_subject_filter;
    	$r[] =  $this->mail_dest_alt;
    	$r[] = $this->mail_pass;
        $r[] =  $this->mail_host;

    	$table = str_replace($s, $r, $table);	
    	
    	return $table;
    }

    public function saveMailParameterized() {

    	$is_new = false;
    	
    	if ($this->mail_id == '') {

    		$this->getIdNewRecord();
    		$is_new = true;
    	}

    	if ($is_new) {
    		
    		if ($this->insertMail()) {
    			$this->loadMsg('Se creo correctamente el registro');
    		}
    		else {
    			$this->loadMsg('Error, No se creo el registro', 'error');	
    		}
    	}
    	else {
    		
    		if ($this->updateMail()) {
    			$this->loadMsg('Se actualizo correctamente el registro');
    		}
    		else {
    			$this->loadMsg('Error, No se actualizo el registro', 'error');	
    		}
    	}
    }

    private function insertMail() {

    	global $db;

    	$sql = "INSERT INTO radicado_mail ";    	
              $sql.= "(mail_id";
               $sql.= ",mail_host";            
               $sql.= ",mail";
               $sql.= ",mail_pass";
               $sql.= ",mail_subject_filter) ";

                $sql.= "VALUES ";
                $sql.= "({$this->mail_id}";
                $sql.= ",'{$this->mail_host}'";            
                $sql.= ",'{$this->mail}'";
                $sql.= ",'{$this->mail_pass}'";
                $sql.= ",'{$this->mail_subject_filter}')";
                
                
                if($db->conn->Execute($sql)){
                    //Obtendremos el id del ultimo correo parametrizado
                    $destinatarios=$this->mail_dest_alt;
                    $sqlMax="select MAX(mail_id) as MAX from radicado_mail";
                    $rsMax=$db->conn->Execute($sqlMax);
                    $maxID=(!$rsMax->EOF && is_numeric($rsMax->fields['MAX']))?$rsMax->fields['MAX']:1;
                    for ($i = 0; $i < count($destinatarios); $i++) {
                        $sqlDes="INSERT INTO mail_destinatarios (fk_usua_login,fk_mail_id) VALUES('{$destinatarios[$i]}',$maxID)";
                        $db->conn->Execute($sqlDes);
                    }
                    return true;
                }else{
                    $this->mail_id='';
                    return false;
                }

    }

    private function updateMail(){
    	
	global $db;
	$retornar=true;


    	    //actualizar cuenta parametrizada
    	    $sql = "UPDATE radicado_mail SET ";
    	    $sql.= "mail = '{$this->mail}'";
    	    $sql.= ",mail_host = '{$this->mail_host}' ";
    	    $sql.= ",mail_pass = '{$this->mail_pass}' ";
    	    $sql.= ",mail_subject_filter = '{$this->mail_subject_filter}' ";
    	    $sql.= "WHERE ";
    	    $sql.= "mail_id = {$this->mail_id}";
    	    
    	    if($db->conn->Execute($sql)){
    	        
    	        //Eliminamos registros de cuentas asociadas
    	        $sqlDelete= "DELETE FROM mail_destinatarios
                     WHERE
                     fk_mail_id = {$this->mail_id}";
    	        if($db->conn->Execute($sqlDelete)){
    	            //Añadir los registros añadidos
    	            $usuarios=$this->mail_dest_alt;
    	            for ($i = 0; $i < count($usuarios); $i++) {
    	                $sqlInsert="INSERT INTO mail_destinatarios 
                                    (fk_usua_login, fk_mail_id) 
                                    VALUES
                                    ('{$usuarios[$i]}',{$this->mail_id})";
    	                $db->conn->Execute($sqlInsert);
    	            }
    	        }else{
    	            $retornar=false;
    	        }
    	    }else{
    	        $retornar=false;
    	    }
    	    
    	    //Ingresar 
    	return $retornar; 

    }    

    private function getIdNewRecord() {

    	global $db;

    	$sql = "SELECT MAX(mail_id) as last_mail_id FROM radicado_mail ";
    	$rsql= $db->conn->Execute($sql);

    	if (isset($rsql->fields['LAST_MAIL_ID']) && $rsql->fields['LAST_MAIL_ID']!=null) {

    		$this->mail_id = $rsql->fields['LAST_MAIL_ID']+1;    		
    	}
    	else {
    		$this->mail_id = 1;
    	}
    }

    private function existMail($mail, $mail_id) {
        if(!is_numeric($mail_id)){
            global $db;
            
            $sql = "SELECT count(mail_id) as QTY FROM radicado_mail WHERE mail = '{$mail}'";
            //$sql = "SELECT count(MAIL_ID) as QTY FROM RADICADO_MAIL WHERE mail = '{$mail}'";
            $rsql= $db->conn->Execute($sql);
            
            if (isset($rsql->fields['QTY']) && $rsql->fields['QTY'] > 0) {
                
                return true;
            }
            else {
                return false;
            }
        }else{
           return false; 
        }

    }

    public function validForm($form) {

    	$valid = true;

    	if (!is_object($form)) {
    		$this->loadMsg("Error, tipo de formulario no valido", 'error');
    		$valid = false;
    	}

    	if (!filter_var($form->mail, FILTER_VALIDATE_EMAIL)) {
    		$this->loadMsg("Error, direccion de correo no valida", 'error');
    		$valid = false;
    	}	

    	if ($this->existMail($form->mail, $form->mail_id)) {
    		$this->loadMsg("Error, direccion de correo existente", 'error');
    		$valid = false;
    	}	    	

    	return $valid;
    }

    public function loadMsg($msg='', $type='success') {

        $this->message.= "<span class='$type textoazul'>$msg</span>";
    }

	public function buildActions($mail_id) {
		$form = "<form action='' name='form_actions$mail_id' method='post'>";
		$form.= "<input type='submit' id='edit'  class='btn-edit'    name='edit'  value='' />";
		$form.= "<input type='submit' id='delete' class='btn-delete' name='delete' value='' />";
		$form.= "<input type='hidden' name='mail_id' value='$mail_id' />";
		$form.= "</form>";

		return $form;
	}    

	public function delete() {

		if ($this->mail_id == '') {
			return false;
		}

		global $db;

		$sql = "DELETE FROM radicado_mail WHERE mail_id = {$this->mail_id}";
		
		if ($db->conn->Execute($sql)){
			$this->loadMsg('Se elimino correctamente el registro');
		}
		else {
			$this->loadMsg('Error. no se elimino el registro', 'error');	
		}
	}
}
?>
