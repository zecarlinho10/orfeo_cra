<?php

class TxValidator
{

    private $db;
    private $mensajeError;
    private $sinError = true;
    private $radicadosError = "";
    private $usuariosInactivos = "";
    private $expedientesError = "";
    private $fileError = "";
    private $archivadoError = "";
    public function __construct($db){
        $this->db = $db;
    }
    /**
     * metodo encargado de verificar que el usuario anterior 
     * se encuentre activo 
     * @param unknown $radicado
     */
    public function usuarioAnterioActivo($radicado)
    {
        $sql = "select case when r.RADI_USU_ANTE is null then 'SIN_ANTERIOR' else 
            r.RADI_USU_ANTE  end as RADI_USU_ANTE  
            from radicado  r  
            join usuario u on r.RADI_USU_ANTE=u.USUA_LOGIN and  u.usua_esta='0'
            where r.radi_nume_radi = " . $radicado;
        $UsuIn = $this->db->query($sql);
        $validaLocal = ! empty($UsuIn->fields["RADI_USU_ANTE"]);
        $this->sinError = $this->sinError && $validaLocal;
        if ($validaLocal) {
            if ($UsuIn->fields["RADI_USU_ANTE"] == "SIN_ANTERIOR") {
                $this->usuariosInactivos .= " el radicado $radicado  no tiene usuario anterior, ";
            } else {
                $this->usuariosInactivos .= " $radicado  el usuario " . $UsuIn->fields["RADI_USU_ANTE"] . " se encuentra inactivo  <br />";
            }
        }
    }
    /**
     * metodo encargado de verificaer que el documento se encuentre asoiciado a un expediente
     * @param unknown $radicado
     */

    public function validarExpediente($radicado)
    {
        $isqlExp = "select SGD_EXP_NUMERO as NumExpediente from SGD_EXP_EXPEDIENTE  where RADI_NUME_RADI = $radicado";
        $rsExp = $this->db->conn->SelectLimit($isqlExp, 1);
        if ($rsExp && ! $rsExp->EOF) {
            $expNumero = $rsExp->fields["NUMEXPEDIENTE"];
            $aux_tiene_exp = ! empty($expNumero);
        }
        $this->sinError = $this->sinError && $aux_tiene_exp;
        if (! $aux_tiene_exp) {
            $this->expedientesError .= $radicado . ", ";
        }
    }

    //CARLOS RICAURTE 27/06/2018
    //VALIDACION SI ES DE SALIDA Y NO TIENE EXPEDIENTE NO PERMITE REENVIAR
    public function validarSalidaEnviara($radicado)
    {
        $isqlExp = "select SGD_EXP_NUMERO as NumExpediente from SGD_EXP_EXPEDIENTE  where RADI_NUME_RADI = $radicado";

        $rs = $this->db->conn->SelectLimit($isqlExp,1);

        if ($rs->EOF && (substr($radicado, -1)<>"3")) {
            $expNumero = $rsExp->fields["NUMEXPEDIENTE"];
            $this->sinError = $this->sinError && $expNumero;
            $this->expedientesError .= $radicado . ", ";
        }
    }

    //CARLOS RICAURTE 18/10/2019
    //VALIDACION SI TIENE TRD
    public function verificaTrdSalida($radicado)
    {
        if (substr($radicado, -1)<>"3") {
            $isqlTRDP = "select radi_nume_radi as RADI_NUME_RADI from SGD_RDF_RETDOCF r 
            where r.RADI_NUME_RADI = $radicado";
            $this->db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
            $rsTRDP = $this->db->conn->Execute($isqlTRDP);
            $radiNumero = ! empty($rsTRDP->fields["RADI_NUME_RADI"]);
            $this->sinError = $this->sinError && $radiNumero;
            if (!$radiNumero) {
                $this->radicadosError .= $radicado . ", ";
            }
        }
    }

    
    //CARLOS RICAURTE 10/04/2019
    //VALIDACION SI ESTÁ ARCHIVADO
    public function verificaArchivado($radicado)
    {
        $ultimo=substr($radicado, -1);
        if($ultimo<>2){
            $isql = "SELECT RADI_NUME_RADI, RADI_NUME_DERI, RADI_USUA_ACTU, RADI_DEPE_ACTU 
                FROM RADICADO 
                WHERE RADI_NUME_RADI = $radicado AND RADI_USUA_ACTU=1 AND RADI_DEPE_ACTU=999 AND (RADI_NUME_DERI IS NULL OR RADI_NUME_DERI=0)
                UNION
                SELECT R2.RADI_NUME_RADI, R2.RADI_NUME_DERI, R2.RADI_USUA_ACTU, R2.RADI_DEPE_ACTU 
                FROM RADICADO R1, RADICADO R2
                WHERE R1.RADI_NUME_RADI = $radicado AND R1.RADI_NUME_DERI= R2.RADI_NUME_RADI AND
                      R2.RADI_USUA_ACTU=1 AND R2.RADI_DEPE_ACTU=999";

            $rs = $this->db->conn->SelectLimit($isql,1);


            if ($rs->EOF) {
                $this->sinError = $this->sinError && false;
                $this->archivadoError .= $radicado . ", ";
            }
        }
        
    }

    /**
     * metodo encargado de verificar que el documentodo 
     * posea una trd asignada 
     * @param unknown $radicado
     */
    public function verificaTrd($radicado)
    {
        $isqlTRDP = "select radi_nume_radi as RADI_NUME_RADI from SGD_RDF_RETDOCF r 
        where r.RADI_NUME_RADI = $radicado";
        $this->db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
        $rsTRDP = $this->db->conn->Execute($isqlTRDP);
        $radiNumero = ! empty($rsTRDP->fields["RADI_NUME_RADI"]);
        $this->sinError = $this->sinError && $radiNumero;
        if (!$radiNumero) {
            $this->radicadosError .= $radicado . ", ";
        }
    }

    /**
     * metodo encargado de verificar la trd en los anexos del radicado
     *
     * @param unknown $radicado
     *            radicado a verificar
     * @return vacio de no tener todos los anexos tipificado o la lista de anexos sin tipificar
     */
    public function validarAnexos($radicado)
    {
        /**
         * $isqlTRDA = "
         *
         * select RADI_NUME_SALIDA from anexos
         * where ANEX_RADI_NUME = $radicado and RADI_NUME_SALIDA != 0
         * and RADI_NUME_SALIDA not in(select RADI_NUME_RADI from SGD_RDF_RETDOCF)";
         * $condicionAnexBorrados = " and anex_borrado = 'N'";
         *
         * @var Ambiguous $isqlTRDA
         */
        $isqlTRDA = "
            select radi_nume_salida from anexos an 
              inner join radicado r on an.anex_radi_nume =r.radi_nume_radi
              left join SGD_RDF_RETDOCF rt on an.radi_nume_salida =rt.radi_nume_radi
            where anex_radi_nume = $radicado and RADI_NUME_SALIDA != 0 
            and RADI_NUME_SALIDA is not null and rt.radi_nume_radi is null 
            and  anex_borrado = 'N' and (sgd_eanu_codigo is null or sgd_eanu_codigo =0) 
           ";
        
        $rsTRDA = $this->db->conn->Execute($isqlTRDA);
        $setFiltroSinTRD = "";
        while ($rsTRDA && ! $rsTRDA->EOF) {
            $radiNumero = ! empty($rsTRDA->fields["RADI_NUME_SALIDA"]);
            $anoRadsal = substr($radiNumero, 0, 4);
            if ($radiNumero && $anoRadsal > "2005") {
                $this->sinError .= $this->sinError && $radiNumero;
                $setFiltroSinTRD .= $radiNumero . ",";
            }
            $rsTRDA->MoveNext();
        }
        if (! empty($setFiltroSinTRD)) {
            $this->radicadosError .= $setFiltroSinTRD;
        }
    }
    

    private function validarImagenPrincipal($radicado)
    {
        $isqlTRDA = "
        select RADI_PATH,SGD_EANU_CODIGO from RADICADO 
        where RADI_NUME_RADI = $radicado ";
        $rsTRDA = $this->db->conn->Execute($isqlTRDA);
        $anulado = $rsTRDA->fields["SGD_EANU_CODIGO"];
        $path = $rsTRDA->fields["RADI_PATH"];
        if (empty($anulado) || $anulado==9) {
            if (empty($path) || (strpos(strtoupper($path), "PDF") === false && strpos(strtoupper($path), "TIF") === false && strpos(strtoupper($path), "HTML") === false)) {
                $this->sinError = $this->sinError && false;
                $this->fileError .= $radicado . ", ";
            }
        }
    }


    /**
     * metodo encargado que el documento se encuentre con un formato 
     * de documento digital valido para su correpondiente trensaccion
     * @param unknown $radicado
     */
    public function validarDigital($radicado)
    {
        
        $this->validarImagenPrincipal($radicado);

        $isqlTRDA = "SELECT RADI_NUME_RADI FROM RADICADO
        WHERE RADI_NUME_DERI = '" .$radicado ."'";
        
        $rsTRDA = $this->db->conn->Execute($isqlTRDA);
        while ($rsTRDA && ! $rsTRDA->EOF) {
            $radiNumero = $rsTRDA->fields["RADI_NUME_RADI"];
            $this->validarImagenPrincipal($radiNumero);
            $rsTRDA->MoveNext();
        }
    }


    /**
     * metodo encargadepo de generar los errortes encontrado al realizar 
     * la o las validadiones respectivas
     * @return string[]
     */
    public function getErrores()
    {
        $mensajeError = array();
        if (! empty($this->radicadosError)) {
            $mensajeError[] = "NO SE PERMITE ESTA OPERACION PARA LOS RADICADOS <BR> <" . $this->radicadosError . "> <BR> FALTA CLASIFICACION TRD PARA ESTOS O PARA SUS ANEXOS <BR> FAVOR APLICAR TRD";
        }
        if (! empty($this->expedientesError)) {
            $mensajeError[] = "<br>NO SE PERMITE ESTA OPERACION PARA LOS RADICADOS <BR> <" . $this->expedientesError . " > <BR> PORQUE NO SE ENCUENTRAN EN NING&Uacute;N EXPEDIENTE";
        }
        if (! empty($this->usuariosInactivos)) {
            $mensajeError[] = "NO SE PERMITE ESTA OPERACION PARA " . $this->usuariosInactivos;
        }
        if (! empty($this->fileError)) {
            $mensajeError[] = "NO SE PERMITE ESTA OPERACION PARA LOS RADICADOS <BR> <" . $this->fileError . "PORQUE NO SE ENCUENTRAN EN UN FORMATO DIGITAL VALIDO";
        }
        if (! empty($this->archivadoError)) {
            $mensajeError[] = "NO SE PERMITE ESTA OPERACION PARA LOS RADICADOS <BR> <" . $this->archivadoError . "PORQUE NO SE ENCUENTRAN ARCHIVADO";
        }
        return $mensajeError;
    }

    public function haveErrores()
    {
        return ! $this->sinError;
    }
}

?>
