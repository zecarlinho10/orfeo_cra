<?php

/**
 * verLinkArchivo es la clase encargada de
 * validar los permisos de acceso a un documento (imagen informacion)
 * @author Liliana Gomez Velasquez
 * @version     1.0
 * @fecha  09 sep 2009
 */
class verLinkArchivo
{

    /**
     * Variable que se corresponde con su par
     * @db Objeto conexion
     *
     * @access public
     */
    var $db;

    /**
     * Vector que almacena el resultado de la validacion
     *
     * @var string
     * @access public
     */
    var $vecRads;

    /**
     * Vector que almacena el resultado de la validacion
     * de un Anexo
     *
     * @var string
     * @access public
     */
    var $vecRadsA;

    /**
     * Constructor encargado de obtener la conexion
     *
     * @param $db ConnectionHandler
     *            es el objeto conexion
     * @return void
     *
     */
    function __construct($db)
    {
        /**
         * Constructor de la clase
         * @db variable en la cual se recibe el cursor sobre el cual se esta trabajando.
         */
        $this->db = $db;
    }

    /**
     * Retorna el valor correspondiente al
     * resultado de la validacion
     * @numrad Numero del Radicado a validar
     *
     * @return array $vecRads resultado de la operacion de validacion
     */
    function havePermisosRadicado($numeRadicado, $usuarioDoc, $usuarioCodi, $depeCodi, $usuaNivel, $permisosExpediente)
    {
        $imagen["ver"] = false;
        $imagen["path"] = "";
        $imagen["confidencial"] = false;
        $sql = "select r.RADI_PATH, r.SGD_SPUB_CODIGO, r.CODI_NIVEL, u.USUA_NOMB,u.USUA_DOC,
             r.RADI_USU_ANTE, r.RADI_DEPE_ACTU,r.RADI_USUA_ACTU
             from RADICADO r, USUARIO u
             where r.RADI_NUME_RADI=$numeRadicado
             and r.RADI_USUA_ACTU= u.USUA_CODI
             and r.RADI_DEPE_ACTU= u.DEPE_CODI";
        $rs = $this->db->conn->query($sql);
        if (!empty($rs) && $rs != null && !$rs->EOF) {
            $imagen["path"] = $rs->fields["RADI_PATH"];
            if ($rs->fields["SGD_SPUB_CODIGO"] == 0 && $usuaNivel >= $rs->fields["CODI_NIVEL"]) {
                $imagen["ver"] = true;
            }else if($rs->fields["SGD_SPUB_CODIGO"] == 1 && $rs->fields["RADI_USUA_ACTU"] ){ 
                $imagen["ver"] = false;
                $imagen["confidencial"] = true;
            }else if (! empty($permisosExpediente["restriccion"])) {
                    switch ($permisosExpediente["restriccion"]) {
                        case "actual":
                            $imagen["ver"] = ($rs->fields["USUA_DOC"] == $usuarioDoc);
                            break;
                        case "dependencia":
                            $imagen["ver"] = ($rs->fields["RADI_DEPE_ACTU"] == $depeCodi);
                            break;
                        case "responsable":
                            $imagen["ver"] = ($permisosExpediente["documento"] == $usuarioDoc || ($usuarioCodi == 1 && $depeCodi == $rs->fields["RADI_DEPE_ACTU"]));
                            break;
                    }
                } else {
                    $imagen["ver"] = ($usuarioDoc == $rs->fields["USUA_DOC"] || $usuarioCodi == 1);
                }
        }
        $sqlI = "select USUA_DOC  from INFORMADOS
            where RADI_NUME_RADI=$numeRadicado
            and USUA_DOC= '$usuarioDoc'";
        $rsI = $this->db->conn->query($sql);
        if (!empty($rs) && $rs != null && ! $rs->EOF) {
            $imagen["ver"] = true;
        }
        
        if (! $imagen["ver"])
            $imagen["path"] = "";
        return $imagen;
    }

    /*
     *
     * metodo encaergado de verificar si un usuario especifico tiene acceso a un expediente
     * y las restricciones sobfre dicho expediente
     * @$usuarioDoc documento del usuario
     * $usuarioCodi codigo del usuario
     * @param $depeCodi dependencia del usuario
     * @return array si teiene permisos y las restricciones
     *
     */
    function permisosExpediente($usuarioDoc, $usuarioCodi, $depeCodi, $expedienteNumero)
    {
        $permisos = array(
            "permiso" => false,
            "restriccion" => false
        );
        $sql = "Select  SGD_EXP_PRIVADO,USUA_DOC_RESPONSABLE,DEPE_CODI  from  
                SGD_SEXP_SECEXPEDIENTES where SGD_EXP_NUMERO='$expedienteNumero'";
        $rs = $this->db->conn->query($sql);
        if (!empty($rs) && $rs != null && ! $rs->EOF) {
            switch ($rs->fields["SGD_EXP_PRIVADO"]) {
                case 0:
                    $permisos["permiso"] = true;
                    $permisos["restriccion"] = false;
                    break;
                case 1:
                    if ($usuarioCodi == 1) {
                        $permisos = true;
                        $permisos["restriccion"] = "actual";
                    }
                    break;
                case 2:
                    if ($rs->fields["DEPE_CODI"] == $depeCodi) {
                        $permisos["permiso"] = true;
                        $permisos["restriccion"] = "dependencia";
                    }
                    break;
                case 3:
                    if ($usuarioCodi == 1 && $usuarioDoc == $rs->fields["USUA_DOC_RESPONSABLE"]) {
                        $permisos["permiso"] = true;
                        $permisos["restriccion"] = "responsable";
                        $permisos["documento"] = $rs->fields["USUA_DOC_RESPONSABLE"];
                    }
            }
        }
        return $permisos;
    }

    /**
     *
     * Retorna el valor correspondiente al
     * resultado de la validacion
     * @numrad Numero del Radicado a validar
     *
     * @return array $vecRads resultado de la operacion de validacion
     */
    function valPermisoRadi($numradi,$enrutador=0)
    {

        // Busca el Documento del usuario Origen
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $this->db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
        $verImg = "NO";
        $isql = "select r.RADI_PATH, r.SGD_SPUB_CODIGO, r.CODI_NIVEL, u.USUA_NOMB,u.USUA_DOC,
         r.RADI_USU_ANTE, r.RADI_DEPE_ACTU, r.TDOC_CODI
         from RADICADO r, USUARIO u
         where r.RADI_NUME_RADI='$numradi'
         and r.RADI_USUA_ACTU= u.USUA_CODI
         and r.RADI_DEPE_ACTU= u.DEPE_CODI";
        $rs = $this->db->conn->query($isql);
        $consultaExpediente = "SELECT SGD_EXP_NUMERO  FROM SGD_EXP_EXPEDIENTE 
                     WHERE radi_nume_radi= $numradi AND sgd_exp_fech=(SELECT MIN(SGD_EXP_FECH) minFech  
                     from sgd_exp_expediente where radi_nume_radi= $numradi  and sgd_exp_estado<>2)  
                     and sgd_exp_estado<>2";
        
        $rsE = $this->db->conn->query($consultaExpediente);
        
        if (!empty($rs) && !$rsE->EOF) {
            $fldsSGD_EXP_SUBEXPEDIENTE = $rsE->fields["SGD_EXP_NUMERO"];
        } else {
            $fldsSGD_EXP_SUBEXPEDIENTE = "";
        }
        // Consulta Informados
        $usuaInformado = "";
        $isqlI = "select USUA_DOC
         from INFORMADOS
         where RADI_NUME_RADI='$numradi'
         and USUA_DOC= '" . $_SESSION['usua_doc'] . "'";
        
        $rsI = $this->db->conn->query($isqlI);
        if (!empty($rs) && !$rsI->EOF) {
            $usuaInformado = $rsI->fields["USUA_DOC"];
        }
        
        if (!empty($rs) && !$rs->EOF) {
            $_SESSION['seguridadradicado'] = $seguridadRadicado = $rs->fields["SGD_SPUB_CODIGO"];
            $nivelRadicado = $rs->fields["CODI_NIVEL"];
            $USUA_ACTU_R = $rs->fields["USUA_DOC"];
            $USUA_ANTE = $rs->fields["RADI_USU_ANTE"];
            $DEPE_ACTU_R = $rs->fields["RADI_DEPE_ACTU"];
            $pathImagen = $rs->fields['RADI_PATH'];
            $TRD = $rs->fields['TDOC_CODI'];
            
            if ($USUA_ACTU_R == $_SESSION["usua_doc"] || $usuaInformado == $_SESSION["usua_doc"]) {
                $verImg = "SI";
            } elseif (isset($fldsSGD_EXP_SUBEXPEDIENTE)) {
                // Consultamos el documento del usuario responsable del expediente
                $consultaDuenoExp = "SELECT USUA_DOC_RESPONSABLE    FROM SGD_SEXP_SECEXPEDIENTES 
                         WHERE SGD_EXP_NUMERO = '$fldsSGD_EXP_SUBEXPEDIENTE'";
                $rsExpDueno = $this->db->conn->query($consultaDuenoExp);
                $duenoExpediente = $rsExpDueno->fields["USUA_DOC_RESPONSABLE"];
                /*
                 * Modificado el 29092009
                 * para el manejo de seguridad Radicado incluido en mas de un expediente
                 */
                if ($duenoExpediente != $_SESSION['usua_doc']) {
                    $sqlExpR = "SELECT 
                      EXP.SGD_EXP_PRIVADO AS PRIVEXP, USUA_DOC_RESPONSABLE AS RESPONSABLE
                  FROM 
                      RADICADO R, SGD_SEXP_SECEXPEDIENTES SEXP, SGD_EXP_EXPEDIENTE EXP
                  WHERE 
                      R.RADI_NUME_RADI=$numradi 
                      AND R.RADI_NUME_RADI = EXP.RADI_NUME_RADI 
                      AND EXP.SGD_EXP_NUMERO = SEXP.SGD_EXP_NUMERO
                      AND EXP.sgd_exp_estado<>2
                      AND USUA_DOC_RESPONSABLE = " . "'" . $_SESSION['usua_doc'] . "'";
                    $rsER = $this->db->conn->query($sqlExpR);
                    if (!empty($rsER) && !$rsER->EOF) {
                        
                        $responsableExp = $rsER->fields["RESPONSABLE"];
                    }
                }
                // Si el usuario que consulta es: usuario actual o responsable del expediente puede ver el Radicado

                if($enrutador){
                      $verImg = "SI";
                }else if ($duenoExpediente == $_SESSION['usua_doc'] || $responsableExp == $_SESSION['usua_doc']) {
                    $verImg = "SI";
                } elseif ($seguridadRadicado == 1) {
                    if ($DEPE_ACTU_R == '999' && $USUA_ANTE == $_SESSION['krd']) {
                        $verImg = "SI";
                    }
                } elseif ($_SESSION["nivelus"] >= $nivelRadicado) {
                    $verImg = "SI";
                }
            } elseif ($seguridadRadicado == 1) {
                if ($DEPE_ACTU_R == '999' && $USUA_ANTE == $_SESSION['krd']) {
                    $verImg = "SI";
                }else if ($_SESSION["nivelus"] >= $nivelRadicado) {
                    $verImg = "SI";
                }
            }
            
            if ($seguridadRadicado == 0) {
                $verImg = "SI";
            }
            
            /********************************** VALIDA SI PERTENECE A ALGUN COMITÉ (7)*************************************/
            if(substr($numradi, -1)==7){
                $sqlC="SELECT ID_COMITE_FUNCIONARIO, ID_COMITE, ID_FUNCIONARIO 
                        FROM ACTUACIONES.COM_COMITE_FUNCIONARIO
                        WHERE ID_COMITE=".$TRD." AND ID_FUNCIONARIO=".$_SESSION["ID"];
                $rsC = $this->db->conn->query($sqlC);
                if (!!empty($rsC) && $rsC->EOF) {
                    $verImg = "SI";
                }
                
                else{
                    $verImg = "NO";
                }
            }
            /**************************************************************************************************************/
            /********************************** PERMISO VER DOCUMENTOS PRIVADOS*************************************/
            if($_SESSION['verDocsPrivados']){
                $verImg = "SI";
            }
            /**************************************************************************************************************/
            /********************************** REVISAR ACTUACIÓN PRIVADA*************************************/
            //REVISA SI LA ACTUACION ES PRIVADA 
            $sqlE=" SELECT 1
                    FROM SGD_EXP_EXPEDIENTE E, ACTUACION_USUARIO AU
                    WHERE SGD_EXP_NUMERO = EXPEDIENTE AND
                          RADI_NUME_RADI = '".$numradi."'";
            $rsE = $this->db->conn->query($sqlE);
            if (empty($rsE->EOF) ) {
                $verImg = "NO";
                $sqlAU=" SELECT 1
                    FROM SGD_EXP_EXPEDIENTE E, ACTUACION_USUARIO AU
                    WHERE SGD_EXP_NUMERO = EXPEDIENTE AND
                          RADI_NUME_RADI = '".$numradi."' AND 
                          IDUSUARIO = ".$_SESSION["ID"];
                $rsAU = $this->db->conn->query($sqlAU);
                if (empty($rsAU->EOF) ) {
                    $verImg = "SI";
                }
            }
            //REVISA SI EL USUARIO TIENE PERMISOS PARA VER EL RADICADO PRIVADO

            /**************************************************************************************************************/
        } 
        else {
            $verImg = "NO SE ENCONTRO INFORMACION DEL RADICADO";
        }
        $vecRadsD['verImg'] = $verImg;
        $vecRadsD['pathImagen'] = $pathImagen;
        $vecRadsD['numExpe'] = $fldsSGD_EXP_SUBEXPEDIENTE;
        return $vecRadsD;
    }

    /**
     * Retorna el valor correspondiente al
     * resultado de la validacion
     * @numrad Numero del Anexo a validar
     *
     * @return array $vecRadsA resultado de la operacion de validacion
     *        
     */
    function valPermisoAnex($numAnex)
    {
        
        // / Busca el Documento del usuario Origen
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $this->db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
        $verImg = "SI";
        $pathImagen = "";
        $isqlAnex = "select ANEX_NOMB_ARCHIVO
        from ANEXOS
        where ANEX_CODIGO = '$numAnex'";
    $rsAnex = $this->db->conn->query($isqlAnex);
        if (!empty($rsAnex) && !$rsAnex->EOF) {
            $pathImagen = trim($rsAnex->fields["ANEX_NOMB_ARCHIVO"]);
            $numeradi = trim($rsAnex->fields["RADI_NUME_SALIDA"]);
        } else {
            $verImg = "NO SE ENCONTRO INFORMACION DEL RADICADO";
        }
        $vecRadsA['verImg'] = $verImg;
        $vecRadsA['pathImagen'] = $pathImagen;
        
        return $vecRadsA;
    }
}

?>
