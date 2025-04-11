<?php
//ini_set("display_errors",1);

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of modeloOwncloud
 *
 * @author deimont
 * @author carlinho
 */
if (!$ruta_raiz)
    $ruta_raiz = '../../';
include "$ruta_raiz/config.php";
//include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include "$ruta_raiz/include/tx/Historico.php";

class modeloOwncloud {

    public $link;
    public $hist;
    public $RUTA_BODEGA;

    function __construct($ruta_raiz) {
	$this->RUTA_BODEGA=BODEGA;
        $db = new ConnectionHandler("$ruta_raiz");
        $db->conn->SetFetchMode(ADODB_FETCH_NUM);
        $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
        //if ($_SESSION['usua_debug'] == 1)
            //$db->conn->debug = false;
        //$db->conn->debug = true;
        $this->link = $db;
        $this->hist = new Historico($db);
    }

    /**
     * @return resultado de la operacion obtienw los  datos requerido para el  usuario  en carpetas.
     *  
     */
    function consultar() {
        // Esta consulta selecciona las carpetas Basicas de DocuImage que son extraidas de la tabla Carp_Codi
        /* $query = "SELECT own_id as id,own_login as login ,own_cloudUser  as ownuser
          FROM scan_conf_owncloud where upper(own_login)=upper('$login')"; */
        $query = "SELECT own_id as id ,own_cloudUser  as ownuser FROM scan_conf_owncloud ";
        //$this->link->conn->debug = true;
        $rs = $this->link->conn->Execute($query);
        $varQuery = $query;
        //include "$ruta_raiz/include/tx/ComentarioTx.php";
        $i = 1;
        $own = array();
        $i = 0;
        while (!empty($rs) && !$rs->EOF) {
            // $own['cod'] = trim($rs->fields ["ID"]);
            //$own['login'] = trim($rs->fields ["LOGIN"]);
            $own[$i] = trim($rs->fields ["OWNUSER"]);
            $i++;
            $rs->MoveNext();
        }


        return $own;
    }

    function consultarRadicados($rad) {
        $query = "select radi_nume_Radi radi,radi_path from radicado where radi_nume_Radi in ($rad) ";
        $rs = $this->link->conn->Execute($query);

        $own = array();
        if (!empty($rs) && !$rs->EOF) {
            while (!empty($rs) && !$rs->EOF) {
                $own[trim($rs->fields ["RADI"])]['path'] = trim($rs->fields ["RADI_PATH"]);
                $own[trim($rs->fields ["RADI"])]['radi'] = trim($rs->fields ["RADI"]);
                $rs->MoveNext();
            }
        }
        return $own;
    }

    function validarImagen($rad) {
        // Esta consulta selecciona las carpetas Basicas de DocuImage que son extraidas de la tabla Carp_Codi
        $query = "select radi_nume_Radi radi,radi_path from radicado where radi_nume_Radi=$rad ";
        $rs = $this->link->conn->Execute($query);
        $varQuery = $query;
        //include "$ruta_raiz/include/tx/ComentarioTx.php";
        $i = 1;
        $own = array();
        $own['path'] = trim($rs->fields ["RADI_PATH"]);
        $own['radi'] = trim($rs->fields ["RADI"]);
        return $own;
    }

    /** valida si el radicado existe  validarRad($radi) */
    function validarRad($rad) {
        // Esta consulta selecciona las carpetas Basicas de DocuImage que son extraidas de la tabla Carp_Codi
        $query = "select radi_nume_Radi radi from radicado where radi_nume_Radi=$rad ";
        $rs = $this->link->conn->Execute($query);
        $varQuery = $query;
        $own = 0;
        if (trim($rs->fields ["RADI"]))
            $own = 1;
        return $own;
    }

    function radSinIma($depe, $fechini, $fechfin) {
        $sql = "select  r.ra_asun asu,r.radi_nume_radi radi,r.radi_depe_actu dactu,
                             to_char(r.radi_fech_radi,'YYYY-MM-DD') fech, u.usua_login gin
                             from radicado r, usuario u
                             where  (r.radi_path is null or r.radi_path='null' )
                             and r.radi_usua_radi=u.usua_codi
                             and r.radi_depe_radi=$depe and
                             
                            r.radi_fech_radi between to_timestamp('$fechini 00:00:00','dd/mm/yyyy hh24:mi:ss') and to_timestamp('$fechfin 23:59:59','dd/mm/yyyy hh24:mi:ss')
                  order by r.radi_fech_radi ";
        $rs = $this->link->conn->Execute($sql);
        $i = 0;
        if (!empty($rs) && !$rs->EOF) {
            while (!empty($rs) && !$rs->EOF) {
                $own[$i]['radi'] = $radi = $rs->fields ['RADI'];
                $own[$i]['fech'] = $fech = $rs->fields ['FECH'];
                $own[$i]['asun'] = $asun = $rs->fields ['ASU'];
                $own[$i]['login'] = $rs->fields ['GIN'];
                $own[$i]['depeA'] = $dactu = $rs->fields ['DACTU'];
                $i++;
                $rs->MoveNext();
            }
        }
        return $own;
    }

    function actualizar3chulo($rad) {
        $query = "/*update*/ ANEXOS 
			  set ANEX_ESTADO=3,ANEX_SALIDA=1,ANEX_ORIGEN=0,
                          SGD_FECH_IMPRES= CURRENT_TIMESTAMP,
			  ANEX_FECH_ENVIO=CURRENT_TIMESTAMP,
		           SGD_DEVE_FECH = NULL, 
                           SGD_DEVE_CODIGO=NULL
		             where radi_nume_salida=$rad"; //sgd_dir_tipo=1,
        $this->link->conn->Execute($query);
    }

    function actualizar($rutaOwn, $rad, $arch, $id_rol, $codusuario, $dependencia, $pages, $observacion) {
        $dated = date('dmY');
		if(substr($rad, 4, 1)=='0'){	
            $fileGrb = substr($rad, 0, 4) . "/" . substr($rad, 5, 2) . "/" . strtolower($arch);
            $fileGrb2 = substr($rad, 0, 4) . "/" . substr($rad, 5, 2) . "/$dated" . strtolower($arch);
        }
		else{
			$fileGrb = substr($rad, 0, 4) . "/" . substr($rad, 4, 3) . "/" . strtolower($arch);
            $fileGrb2 = substr($rad, 0, 4) . "/" . substr($rad, 4, 3) . "/$dated" . strtolower($arch);
		}
		
	exec("mv " . $this->RUTA_BODEGA . "/$fileGrb " . $this->RUTA_BODEGA . "/$fileGrb2");
        
	$comando = "cp $rutaOwn$arch " . $this->RUTA_BODEGA . "/$fileGrb";
        
	$res = exec($comando );
        exec("md5sum " . $this->RUTA_BODEGA . "/$fileGrb | awk '{print $1}'", $md5s);
        exec("md5sum $rutaOwn$arch | awk '{print $1}'", $md5s2);
        
        if ($md5s2 == $md5s) {
            exec("rm $rutaOwn$arch ");
            $query = "update radicado 	set radi_path='$fileGrb', radi_nume_hoja=$pages where radi_nume_radi=$rad";
            if ($this->link->conn->Execute($query)) {
                $radicadosSel[] = $rad;
                $codTx = 22; //Codigo de la transaccion
                //$hist = new Historico($db);
                //echo "$radicadosSel, $dependencia, $codusuario, $id_rol, $dependencia, $codusuario, $id_rol, $observacion, $codTx";
                $this->hist->insertarHistorico($radicadosSel, $dependencia, $codusuario, $dependencia, $codusuario, $observacion, $codTx);
            }
            return $rad . '-' . $fileGrb;
        } else {
            // echo "<hr>no paso";
            exec("mv " . $this->RUTA_BODEGA . "/$fileGrb2 " . $this->RUTA_BODEGA . "/$fileGrb ");
            return "error";
        }
    }

    function actualizarf($rutaOwn, $rad, $arch, $id_rol, $codusuario, $dependencia, $pages, $observacion) {
        ini_set('memory_limit', '800M');
        $dated = date('dmY');
        if(substr($rad, 4, 1)=='0'){	
        $fileGrb = substr($rad, 0, 4) . "/" . substr($rad, 5, 2) . "/" . strtolower($arch);
        $fileGrb2 = substr($rad, 0, 4) . "/" . substr($rad, 5, 2) . "/$dated" . strtolower($arch);
        }
		else{
			$fileGrb = substr($rad, 0, 4) . "/" . substr($rad, 4, 3) . "/" . strtolower($arch);
        $fileGrb2 = substr($rad, 0, 4) . "/" . substr($rad, 4, 3) . "/$dated" . strtolower($arch);
		}
        //$fileGrbC = substr($rad, 0, 4) . "/" . substr($rad, 4, 3) . "/c" . strtolower($arch);
        $opciones = "";
        $opciones .= " $rutaOwn$arch ";
        $opciones .= " -kst PKCS12 ";
//        $opciones .= " -ksf ../../core/config/cert/dne/8099.p12  ";   // Fila Certificado
/*        $opciones .= " -ksf ../../core/config/cert/dne/dne.p12  ";
        $opciones .= " -ksp System2013 " ;*/
        $opciones .= " -ksf " .KSF. " ";
        $opciones .= " -ksp " .KSP. " ";
        //$opciones .= " -ksp ideas ";
        $opciones .= " --font-size 7 ";
        $opciones .= ' --l4-text ' . "'" . '${signer}  ' . "'";
        $opciones .= ' --l4-text ' . "'" . '${timestamp}' . "'";
        $opciones .= ' --l4-text ' . "'" . '${location}' . "'";
        $opciones .= ' --l4-text ' . "'" . '${reason}' . "'";
        $opciones .= ' --l4-text ' . "'" . '${contact} ' . "'";
        $opciones .= " -r 'Firmado al Digitalizar en OrfeoSDG' ";
        $opciones .= " -V -v  ";
        $opciones .= " --img-path ../../core/config/cert/firmaOrf.png --render-mode  GRAPHIC_AND_DESCRIPTION -llx 0 -lly 0 -urx 550 -ury 27 ";
        // $opciones .= " --img-path ../config/cert/firmaOrf.png --render-mode  GRAPHIC_AND_DESCRIPTION -llx 614.8665 -lly 558.13043 -urx 564.7026 -ury 351.52173 ";
        // $opciones .= " --bg-path ../cert/firmaOrfeo.png --bg-scale -2";
        $kk = shell_exec("pwd");
        //echo "$kk <hr>";
        $comando = "/usr/bin/java -Xmx1240m -Duser.language=es -jar ../../extra/JSignPdf-1.5.1/JSignPdf.jar $opciones";
        //echo "$comando<hr>";
        $dato = explode("INFO ", $comando);
        $kk = shell_exec($comando);
        $dato = explode("INFO ", $kk);
        $datoFinal = str_replace("Finished: Signature succesfully created", "<font color=green>Firmado Correctamente </font>", end($dato));
        $datoFinal = str_replace("Finished: Creating of signature failed", "<font color=red>Fallo Firma </font>", $datoFinal);
        
        exec("mv " . $this->RUTA_BODEGA . "/$fileGrb " . $this->RUTA_BODEGA . "/$fileGrb2");
        
        exec("mv $rad" . "_signed.pdf " . $this->RUTA_BODEGA . "/$fileGrb");

        $query = "update radicado 	set radi_path='$fileGrb', radi_nume_folio=$pages where radi_nume_radi=$rad";
        if ($this->link->conn->Execute($query)) {
            $radicadosSel[] = $rad;
            $codTx = 22; //Codigo de la transaccion
            $this->hist->insertarHistorico($radicadosSel, $dependencia, $codusuario, $id_rol, $dependencia, $codusuario, $id_rol, $observacion, $codTx);
        }
        return $rad . '-' . $fileGrb;
    }

    function grabarAnexo($anexRadiNume, $codigo, $codigoExtension, $tamano = 0, $auxsololect, $usuaLoginRadica, $anexDesc, $auxnumero, $archivoconversion, $depRadica, $rutaOwn, $arch, $tpdoc, $radSalida = 'NULL', $ANEX_ESTADO = 1, $radtipo = 0, $anexOrigen = 1, $fechV = 0) {
        if($codigoExtension==7){ 
	$script = " pdfinfo $rutaOwn$arch | grep Pages|awk '{print $2}'";
               exec($script, $result);
               $num_pag = $result[0];
	}
	else{
	    $num_pag=0;
	}
	$anex_radi_fech = 'NULL';
        $valoreas = '\'\'';
        $datatipo = '';
        $SGD_DIR_TIPO = 0;
        if ($fechV == 1) {
            $anex_radi_fech = 'current_timestamp';
            $datatipo = ',sgd_fech_impres ,anex_fech_envio';
            $valoreas = ',current_timestamp,current_timestamp';
            $SGD_DIR_TIPO = 1;
	    $num_pag=0;
        }
		//$this->link->conn->debug=true;
        $sqlAnexo = "INSERT INTO anexos  (SGD_REM_DESTINO,   ANEX_RADI_NUME,  ANEX_CODIGO,ANEX_TIPO,ANEX_TAMANO,  ANEX_SOLO_LECT,ANEX_CREADOR, ANEX_DESC,  ANEX_NUMERO,ANEX_NOMB_ARCHIVO,ANEX_BORRADO,ANEX_SALIDA,
                    SGD_DIR_TIPO,ANEX_DEPE_CREADOR,SGD_TPR_CODIGO,anex_radi_fech, ANEX_FECH_ANEX,
                    ANEX_ORIGEN,radi_nume_salida,ANEX_ESTADO)  VALUES ";
        $sqlAnexo .= ' (1,' . $anexRadiNume . ',\'' . $codigo . '\',' . $codigoExtension . ',' . $tamano . ', \''
                . $auxsololect . '\',\'' . $usuaLoginRadica . '\',  \'' . $anexDesc . '\',   ' . $auxnumero . ',   \'' .
                $archivoconversion . '\',\'N\',' . $radtipo . ",$SGD_DIR_TIPO," . $depRadica . ',' . $tpdoc . ',' . $anex_radi_fech .
                ',SYSDATE,' . $anexOrigen . ',' . $radSalida . ',' . $ANEX_ESTADO . ')';

        $this->link->query($sqlAnexo);
        if ($radSalida == 'NULL') {
            if(substr($anexRadiNume, 4, 1)=='0'){    
                $depe_ruta = substr($anexRadiNume, 5, 2);
            }
            else{
                $depe_ruta = substr($anexRadiNume, 4, 3);
            }
            $fileGrb = substr($anexRadiNume, 0, 4) . "/" . $depe_ruta . "/docs/" . $archivoconversion;
            exec("cp $rutaOwn$arch " . $this->RUTA_BODEGA . "/$fileGrb");
        }
        return $codigo;
    }

    /**
     * Actualiza los atributos de la clase con los datos
     * del tipo de formato del documento a anexar correspondiente a la extensión del archivo
     * que recibe como parámetro.
     * @param $extension es la extensión del archivo.
     */
    function anex_tipo_ext($extension) {
        $q = 'SELECT *';
        $q .= ' FROM anexos_tipo';
        $q .= ' WHERE ' . $this->link->conn->upperCase . '( anex_tipo_ext ) = \'' . strtoupper($extension) . '\'';
        // print $q;
        $rs = $this->link->query($q);

        if (!empty($rs) &&  !$rs->EOF && $rs) {
            $resp['codi'] = $rs->fields['ANEX_TIPO_CODI'];
            $resp['ext'] = $rs->fields['ANEX_TIPO_EXT'];
            $resp['desc'] = $rs->fields['ANEX_TIPO_DESC'];
        }else{
	    $resp="No es valido el tipo de archivo";
	}
        return $resp;
    }

    function consultarNumAnex2($radicado) {
        //echo    $sql="select max(anex_codigo )  codigo from anexos where anex_radi_nume=$radicado";
        $sql = "select max(anex_codigo)  codigo  from anexos where cast(anex_codigo as varchar(18))like '$radicado%' ";
        $rs = $this->link->query($sql);
        $resp = $rs->fields['CODIGO'];
        if (!$resp)
            $resp = $radicado . '00001';
        else {
            $auxnumero = substr($resp, -6);
            $resp = $radicado . substr(($auxnumero + 1), -5);
        }

        return $resp;
    }

    function consultarNumAnex($radicado) {

        $a = 'Esta';
        $sql2 = "select anex_codigo codigo  from anexos where  radi_nume_salida=$radicado";
        $rs2 = $this->link->query($sql2);
        $resp = $rs2->fields['CODIGO'];
        if (!$resp) {
            $sql = "select max(anex_codigo)  codigo  from anexos where cast(anex_codigo as varchar(18))like '$radicado%' ";
            $rs = $this->link->query($sql);
            $resp = $rs->fields['CODIGO'];
            $a = 'Nuevo';
            if (!$resp) {
                $resp = $radicado . '00001';
            } else {
                $auxnumero = substr($resp, -6);
                $resp = $radicado . substr(($auxnumero + 1), -5);
            }
        }
        $resp2['code'] = $resp;
        $resp2['status'] = $a;
        return $resp2;
    }

    /** valida si el radicado existe  validarRad($radi) */
    function validarAnex($anexo) {
        // Esta consulta selecciona las carpetas Basicas de DocuImage que son extraidas de la tabla Carp_Codi
        $query = "select anex_codigo codigo,anex_nomb_archivo nomb_arch  from anexos where anex_codigo= '$anexo'";
        $rs = $this->link->conn->Execute($query);
        $varQuery = $query;
        $own = 0;
        if (trim($rs->fields ["CODIGO"])) {
            if(substr($anexRadiNume, 4, 1)=='0'){    
                $depe_ruta = substr($anexRadiNume, 5, 2);
            }
            else{
                $depe_ruta = substr($rad, 4, 3);
            }
            $fileGrb = substr($anexo, 0, 4) . "/" . $depe_ruta . "/docs/";
            //  echo "ls $fileGrb".$rs->fields ["CODIGO"];
            exec("ls $fileGrb" . $rs->fields ["CODIGO"], $result);
            //  print_r($result);
            $own = 1;
        }
        //echo $own;
        return $own;
    }

}

?>
