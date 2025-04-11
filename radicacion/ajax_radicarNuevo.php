<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');  

  session_start();
  $ruta_raiz = "..";

//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(-1);

$sendEmail = true; //Enviar correo electronico

  if (!$_SESSION['dependencia'])
      header ("Location: $ruta_raiz/cerrar_session.php");

  header('Content-Type: application/json');
  include_once("$ruta_raiz/include/db/ConnectionHandler.php");
  $db     = new ConnectionHandler("$ruta_raiz");
  $db->conn->debug  = false;
  $ADODB_COUNTRECS  = true;
  $ADODB_FORCE_TYPE = ADODB_FORCE_NULL;

  include("$ruta_raiz/include/tx/Tx.php");
  include("$ruta_raiz/include/tx/Radicacion.php");
  include("$ruta_raiz/include/tx/usuario.php");
  include("$ruta_raiz/class_control/Municipio.php");

  $hist      = new Historico($db);
  $classusua = new Usuario($db);
  $Tx        = new Tx($db);

  $dependencia   = $_SESSION["dependencia"];
  $codusuario    = $_SESSION["codusuario"];
  $usua_doc      = $_SESSION["usua_doc"];
  $tpDepeRad     = $_SESSION["tpDepeRad"];
  $adate=date('Y');

  $tpRadicado    = empty($_POST['radicado_tipo'])? 0 : $_POST['radicado_tipo'];
  $cuentai       = $_POST['cuentai'];
  $guia          = $_POST['guia'];
  $fecha_gen_doc = $_POST['fecha_gen_doc'];
  $usuarios      = $_POST['usuario'];
  $asu           = $_POST['asu'];
  $med           = $_POST['med'];
  $radicadopadre = $_POST["radicadopadre"];

  $nofolios      = $_POST['nofolios'];
  $noanexos      = $_POST['noanexos'];
  $otro_us       = $_POST['otro_us'];

  $ane           = $_POST['ane'];
  $coddepe       = $_POST['coddepe'];
  $tdoc          = $_POST['tdoc'];

  $ent           = $_POST['ent'];

  //Enviados solo si es para modificar
  $modificar     = $_POST['modificar'];
  $nurad         = $_POST['nurad'];
  if(!empty($_POST["rad0"])){
	  $rad0=$_POST["rad0"];
  }else if(!empty($_POST["rad1"])){
	  $rad1=$_POST["rad1"];
    $radicadopadre = "";
  }else if(!empty($_POST["rad2"])){
	  $rad2=$_POST["rad2"];
  }


 //Si el numero el radicado esta en fisico
 if ($_SESSION["entidad"] == 'CRA'){ 
  if (isset($_POST["esta_fisico"])){
  $esta_fisico = 1; 
  }else{
  $esta_fisico = 0;  
  }  
 } 


  /**************************************************/
  /*********** RADICAR DOCUMENTO  *******************/
  /**************************************************/
  $rad               = new Radicacion($db);

  //Si el radicado que se esta realizando es un memorando
  //este debe quedar guardado en la bandeja del usuario que
  //realiza el radicado por esta razon guardamos el radicado
  //con el codigo del usuario que realiza la accion.
  if($ent == 2){
    $carp_codi         = 0;
    $rad->radiUsuaActu = 1;
    $rad->radiDepeActu = $coddepe;
  }else{
    $carp_codi         = $ent;
    $rad->radiUsuaActu = $codusuario;
    $rad->radiDepeActu = $dependencia;
  }

  $rad->radiTipoDeri = $tpRadicado;
  $rad->radiCuentai  = trim($cuentai);
  $rad->guia         = trim(substr($guia,0 ,20));
  $rad->eespCodi     = $documento_us3;
  $rad->mrecCodi     = $med;// "dd/mm/aaaa"
  $rad->radiFechOfic =       substr($fecha_gen_doc,6 ,4)
                       ."-". substr($fecha_gen_doc,3 ,2)
                       ."-". substr($fecha_gen_doc,0 ,2);

  if(empty($radicadopadre)){
    $radicadopadre = null;
  }

  if(!$ent){
    $radicadopadre = null;
  }

  $rad->radiNumeDeri = trim($radicadopadre);
  $rad->descAnex     = substr($ane, 0, 99);
  $rad->radiDepeRadi = "'$coddepe'";
  $rad->trteCodi     = $tip_rem;
  $rad->tdocCodi     = $tdoc;
  $rad->nofolios     = $nofolios;
  $rad->noanexos     = $noanexos;
  $rad->carpCodi     = $carp_codi;
  $rad->carPer       = $carp_per;
  $rad->trteCodi     = $tip_rem;
  $rad->raAsun       = substr(htmlspecialchars(stripcslashes($asu)),0,799);
//  $rad->tdocCodi     = $tdoc;



   //Si el numero el radicado esta en fisico
  if ($_SESSION["entidad"] == 'CRA'){ 
    $rad->esta_fisico = $esta_fisico;
  } 


  if(strlen(trim($aplintegra)) == 0){
    $aplintegra = "0";
  }

  $rad->sgd_apli_codi = $aplintegra;

  if($nurad){
  
    if ($_SESSION["entidad"] == 'CRA'){ 
      $rad->tdocCodi = "noactualizar";
    }else{
      $rad->tdocCodi     = $tdoc;
    } 
    //    if (intval($tdoc) != 0 ){$rad->tdocCodi = $tdoc;}
    if(!$rad->updateRadicado($nurad)){
      $data[] = array( "error"   => 'No se actualiz&oacute; el radicado');
    }
  }else{
    $rad->tdocCodi     = $tdoc;
    $nurad = $rad->newRadicado($ent, $tpDepeRad[$ent]);
/**********************************Incluye un radicado interno automaticamente a una TRD*******************************************************/
/**************************************************Desarrollado para la CRA *******************************************************************/
/*
SE QUITA OPCION DE GUARDAR EN EXPEDIENTE Y TIPIFICAR POR DEFECTO

if($ent==3 && $db->entidad=="CRA")
{
		include_once realpath(dirname(__FILE__) . "/../") . "/class_control/TipoDocumental.php";
		$tipoDoc = new TipoDocumental($db);
		
		$db->conn->Execute( "UPDATE RADICADO
							SET TDOC_CODI=384, SGD_SPUB_CODIGO=1
							WHERE RADI_NUME_RADI='$nurad'");
		$isqlTRD = "select SGD_MRD_CODIGO 
					from SGD_MRD_MATRIRD 
					where DEPE_CODI = '$dependencia'
				 	   and SGD_SRD_CODIGO = 91
				       and SGD_SBRD_CODIGO = 01
					   and SGD_TPR_CODIGO = 993";
		//$db->conn->debug = true;
			$rsTRD = $db->conn->Execute($isqlTRD);
			$codiTRD = $rsTRD->fields['SGD_MRD_CODIGO'];
	    
		$db->conn->Execute( "insert into  sgd_rdf_retdocf (SGD_MRD_CODIGO,RADI_NUME_RADI,DEPE_CODI,USUA_CODI,USUA_DOC,SGD_RDF_FECH) values (".$codiTRD.", ".$nurad.", ".$coddepe.",".$codusuario.", ".$usua_doc.",SYSDATE)");
		$tipoDoc->setFechVenci($nurad, 993);


	if ($dependencia==410||$dependencia==420 ||$dependencia==430){ 
			$sqletq = "select SGD_EXP_NUMERO
					from SGD_SEXP_SECEXPEDIENTES
					where SGD_SEXP_ANO = '$adate'
				 	   and DEPE_CODI =401
				           and SGD_SRD_CODIGO = 91
					   and SGD_SBRD_CODIGO = 01";
			//$db->conn->debug = true;
		$rsetq = $db->conn->Execute($sqletq);
		$rexp = $rsetq->fields['SGD_EXP_NUMERO'];
		$db->conn->Execute( "insert into  SGD_EXP_EXPEDIENTE(SGD_EXP_NUMERO,RADI_NUME_RADI,SGD_EXP_FECH,SGD_EXP_FECH_MOD,DEPE_CODI,USUA_CODI,USUA_DOC,SGD_EXP_ESTADO) values ('$rexp', ".$nurad.", SYSDATE, NULL, ".$coddepe.",".$codusuario.", ".$usua_doc.",0)");
	}else {
				$sqletq = "select SGD_EXP_NUMERO
					from SGD_SEXP_SECEXPEDIENTES
					where SGD_SEXP_ANO = '$adate'
				 	   and DEPE_CODI = '$dependencia'
				           and SGD_SRD_CODIGO = 91
					   and SGD_SBRD_CODIGO = 01";
			//$db->conn->debug = true;
		$rsetq = $db->conn->Execute($sqletq);
		$rexp = $rsetq->fields['SGD_EXP_NUMERO'];
		$db->conn->Execute( "insert into  SGD_EXP_EXPEDIENTE(SGD_EXP_NUMERO,RADI_NUME_RADI,SGD_EXP_FECH,SGD_EXP_FECH_MOD,DEPE_CODI,USUA_CODI,USUA_DOC,SGD_EXP_ESTADO) values ('$rexp', ".$nurad.", SYSDATE, NULL, ".$coddepe.",".$codusuario.", ".$usua_doc.",0)");
			}
	}
  */
/**********************************************************************************************************************************************/
  }

  if ($nurad=="-1"){
    $data[] = array( "error"   => 'No se genero un numero de radicado');
  }else{
    $data[] = array( "answer"  => $nurad);
  }

  $radicadosSel[0] = $nurad;
  $observa="  ";
  if (isset($_POST['modificar'])){$_tipo_tx = 21; $observa="Radicado Modificado";}else{$_tipo_tx = 2;
    $observa=(!empty($_POST["med"]) && $_POST["med"]==4)?" Radicación por email":"   ";
  }

  if(substr($nurad, 13, 1)=="2"){
    $codusuariodes=1;
  }
  else{
    $codusuariodes=$codusuario;
  }
  $hist->insertarHistorico( $radicadosSel,
                            $dependencia ,
                            $codusuario,
                            $coddepe,
                            $codusuariodes,
                            $observa,
                            $_tipo_tx);

    //Borramos todos los usuarios existentes en sgd_dir_drecciones y los
    //grabamos nuevamente con los datos suministrados.
    $select = "delete from sgd_dir_drecciones where radi_nume_radi = $nurad";
    $db->conn->query($select);

    /**********************************************************************
     *********** GRABAR DIRECCIONES ***************************************
     **********************************************************************
     * Existen tres tipos distintos de datos de direccion
     *
     * Descripcion
     * (0_0_XX_XX2)
     * primer campo : consecutivo de los usuarios asignados a un radicado
     *                si es nuevo puede ser 0, o si es el primer registro
     *                de los usuarios asignados al radicado.
     *
     * segundo campo: tipo de usuario {usuario: 0, empresa :2, funcionario: 6}
     *
     * tercer campo: codigo con el cual esta grabado en la tabla SGD_DIR_DRECCIONES
     *
     * Cuarto campo; el codigo de identificacion del usuario de la tabla origen.
     *               esta tabla puede ser la de SGD_OEM_CODIGO, SGD_CIU_CODIGO
     *               o USUARIOS
     *
     *
     * 1) Un usuario nuevo (0_0_XX_XX2)(0_0_XX_XX3)....
     *    El usuario nuevo se puede identificar cuando en los datos
     *    de usuario se muestra el siguiente string (0_0_XX_XX2) el
     *    cual denota que no existe un codigo de almacenamiento unido a un
     *    radicado que son las dos primeras equis, las siguietnes son el
     *    codigo que le corresponde al usuario almacenado en la base de datos
     *    ya sea un usuario, funcionario o entidad y esta representado por
     *    las ultimas equis. Como se pueden crear mas de un usuario nuevo se
     *    genera un cosecutivo que es el ultimo digito
     *    ejemplo: (0_0_XX_XX2) las dos xx significan que no esta asociado
     *              a ningun radicado, las ultimas muestran que es un
     *              usuario nuevo y el 2 que es el segundo registro generado

     * 2) Un usuario existente en el sistema, NO asociado a un radicado (0_0_XX_12)
     *    (0_0_XX_16)...
     *
     *
     * 3) Un usuario existen (0_0_123_17) (0_0_327_123)
     *    Al momento de genear un radicado podemos traer usuario del sistema y a su ves
     *    cambiar la informacion que hace parte de este.
     */

    //Datos de usuarios
    foreach ($usuarios as $clave => $valor) {

        list($consecutivo, $sgdTrd, $id_sgd_dir_dre , $id_table) = explode('_', $valor);

    //OBTENEMOS EL CONTINENTE DINAMICAMENTE
    $_id_pais = $_POST[$valor."_pais_codigo"];
    $query_ = "select p.id_cont from sgd_def_paises p  where id_pais = $_id_pais";
    $rs = $db->conn->query($query_);
    while(!$rs->EOF){
      $id_continente    = $rs->fields["ID_CONT"];
      $rs->MoveNext();
    }
            $usuarios[$clave] = array(
                "cedula"         => $_POST[$valor."_cedula"],
                "nombre"         => $_POST[$valor."_nombre"],
                "apellido"       => $_POST[$valor."_apellido"],
                "dignatario"     => $_POST[$valor."_dignatario"],
                "telef"          => $_POST[$valor."_telefono"],
                "direccion"      => $_POST[$valor."_direccion"],
                "email"          => $_POST[$valor."_email"],
                "muni"           => $_POST[$valor."_muni"],
                "muni_tmp"       => $_POST[$valor."_muni_codigo"],
                "dep"            => $_POST[$valor."_dep"],
                "dpto_tmp"       => $_POST[$valor."_dep_codigo"],
                "pais"           => $_POST[$valor."_pais"],
                "pais_tmp"       => $_POST[$valor."_pais_codigo"],
                "cont_tmp"       => $id_continente,
                "tdid_codi"      => $_POST[$valor."_tdid_codi"],
                "sgdTrd"         => $sgdTrd,
                "id_sgd_dir_dre" => $id_sgd_dir_dre,
                "id_table"       => $id_table
            );

            $classusua->guardarUsuarioRadicado($usuarios[$clave], $nurad);

    
    if($sendEmail && filter_var($_POST[$valor."_email"], FILTER_VALIDATE_EMAIL) && substr($nurad, -1)==2){
      //NUEVO ENVIO DE CORREOS
      $queryVeri = "select SGD_RAD_CODIGOVERIFICACION from RADICADO where RADI_NUME_RADI = $nurad";
      $rsVeri = $db->conn->query($queryVeri);
      $codigoverificaciontext = $rsVeri->fields["SGD_RAD_CODIGOVERIFICACION"];
      //$mensaje = "Hemos recibido su oficio en nuestra Entidad con el radicado No $radicadosSelText del día 06/01/23 08:56:25 y con el código de verificación $codigoverificaciontext.";
      $to=$_POST[$valor."_email"];
      
      require("../include/mail/graph/informar.php");  
      //crearMensaje("llego radicado $nurad","llego el radicado $nurad con codigo de verificacion $codigoverificaciontext al correo: $to ","cricaurte@cra.gov.co"); 
      crearMensaje("Notificación radicación","Buen Día \nHemos recibido su oficio en nuestra entidad con el radicado No $nurad con codigo de verificacion $codigoverificaciontext \nLe informamos que recibirá respuesta a su comunicación por este mismo medio y que puede consultar el estado del trámite de su comunicación digitando el número de radicado en el siguiente link: \n https://gestiondocumental.cra.gov.co/orfeonew/consultaWeb \nPor favor no responda este correo. Esta dirección electrónica es utilizada solamente para envío de información, por lo tanto, sus consultas no podrán ser atendidas. Si tiene alguna inquietud adicional o desea respondernos, lo invitamos a que ingrese nuevamente a nuestra opción de contáctenos de nuestra página web:  https://www.cra.gov.co, o nos llame  desde Colombia (1) 487 3820 - 489 7640 desde el exterior +57(1) 487 3820 +57(1) 489 7640 Línea nacional gratuita: 01 8000 517565 o envíe fax desde Colombia (1) 489 7650 desde el exterior +57(1) 489 7650 o escribanos al mail : correo@cra.gov.co",$to); 
    }//FIN enviar email

    

    
  }

    
    echo json_encode($data);
