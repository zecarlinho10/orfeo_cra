<?php

require_once (realpath(dirname(__FILE__) . "/../../") . "/include/db/Connection/Connection.php");
include_once realpath(dirname(__FILE__) . "/../../") . "/include/tx/Historico.php";
include_once realpath(dirname(__FILE__) . "/../../") . "/class_control/TipoDocumental.php";
include_once realpath(dirname(__FILE__) . "/../../") . "/include/tx/Expediente.php";
include_once realpath(dirname(__FILE__) . "/../../") . "/include/utils/Calendario.php";
include_once realpath(dirname(__FILE__) . "/") . "/Sectores.php";

class Matriz
{

    private $idMatiz;

    private $codSDerie;

    private $codSubserie;

    private $codTipoDocumental;

    private $codDependencia;

    private $estado;

    protected $db;

    protected $calendario;

    protected $atencion = null;

    public function __construct()
    {
        $this->db = Connection::getCurrentInstance();
        $this->calendario = new Calendar($this->db);
        if (is_dir(realpath(dirname(__FILE__) . "/../../") . "/atencion/")) {
            include_once realpath(dirname(__FILE__) . "/../../") . "/clasesComunes/AtencionCiudadano.php";
            $this->atencion = new AtencionCiudadano();
        }
    }

    public function getSeriesDependencia($dependencia, $depAplica = "")
    {
        $fecha_hoy = date("Y-m-d");
        $sqlFechaHoy = $this->db->conn->DBDate($fecha_hoy);
        $sql = "SELECT
	    DISTINCT " . $this->db->conn->Concat("S.SGD_SRD_CODIGO", "' - '", "S.SGD_SRD_DESCRIP") . " AS DETALLE
			    ,S.SGD_SRD_CODIGO AS CODIGO
			    FROM
			    SGD_MRD_MATRIRD M
			    , SGD_SRD_SERIESRD S
			    WHERE
			    (M.DEPE_CODI 			= $dependencia 
			     or m.depe_codi_aplica like '%$dependencia%')
			    AND S.SGD_SRD_CODIGO 	= M.SGD_SRD_CODIGO
			    AND M.SGD_MRD_ESTA 		= '1'
			    AND $sqlFechaHoy BETWEEN S.SGD_SRD_FECHINI AND S.SGD_SRD_FECHFIN
			   	ORDER BY DETALLE";
        $rs = $this->db->query($sql);
        return $rs;
    }

    public function getSubSerie($dependencia, $serie)
    {
        $fecha_hoy = date("Y-m-d");
        $sqlFechaHoy = $this->db->conn->DBDate($fecha_hoy);
        $sql = "select  DISTINCT
		" . $this->db->conn->Concat("su.sgd_sbrd_codigo", "'-'", "su.sgd_sbrd_descrip") . " AS DETALLE
		,su.sgd_sbrd_codigo AS CODIGO
		from
		sgd_mrd_matrird m
		, sgd_sbrd_subserierd su
		where
		m.depe_codi 			= $dependencia
		and m.sgd_srd_codigo 	= $serie
		and su.sgd_srd_codigo 	= $serie
		and m.sgd_mrd_esta 		= '1'
		and su.sgd_sbrd_codigo 	= m.sgd_sbrd_codigo
		and $sqlFechaHoy between su.sgd_sbrd_fechini
		and su.sgd_sbrd_fechfin
		order by detalle";
        $rs = $this->db->query($sql);
        return $rs;
    }

    public function getTipoDocumental($dependencia, $serie, $subserie)
    {
        $fecha_hoy = date("Y-m-d");
        $sqlFechaHoy = $this->db->conn->DBDate($fecha_hoy);
        $sql = "SELECT DISTINCT
					" . $this->db->conn->Concat("T.SGD_TPR_CODIGO", "'-'", "T.SGD_TPR_DESCRIP") . " AS DETALLE
							,T.SGD_TPR_CODIGO AS CODIGO,T.detalle_causal 
		
							FROM
							SGD_MRD_MATRIRD M
							, SGD_TPR_TPDCUMENTO T
							WHERE
							M.DEPE_CODI 			= $dependencia
							AND M.SGD_MRD_ESTA 	= '1'
							AND M.SGD_SRD_CODIGO 	= $serie
							AND M.SGD_SBRD_CODIGO 	= $subserie
							AND T.SGD_TPR_CODIGO 	= M.SGD_TPR_CODIGO
							AND T.SGD_TPR_ESTADO		='1'
		
							ORDER BY DETALLE";
        $rs = $this->db->conn->query($sql);
        return $rs;
    }

    public function getMatrixId($dependencia, $serie, $subserie, $tipo)
    {
        $sql = "Select sgd_mrd_codigo as codigo from SGD_MRD_MATRIRD 
				where DEPE_CODI =$dependencia 
				and SGD_SRD_CODIGO = $serie
				and SGD_SBRD_CODIGO = $subserie
				and SGD_TPR_CODIGO = $tipo";
        $rs = $this->db->conn->query($sql);
        if (! empty($rs) && ! $rs->EOF)
            return $rs->fields["CODIGO"];
        return null;
    }

    public function getTrd($serie, $subserie, $tipoDocto)
    {
        $sql = "SELECT s.SGD_SRD_DESCRIP,su.sgd_sbrd_descrip,T.SGD_TPR_DESCRIP,detalle
				from SGD_SRD_SERIESRD S inner join 
				sgd_sbrd_subserierd su on s.SGD_SRD_CODIGO =su.SGD_SRD_CODIGO
				,SGD_TPR_TPDCUMENTO T 
				where 
				s.SGD_SRD_CODIGO =$serie and 
				su.SGD_SBRD_CODIGO = $subserie and 
				t.SGD_TPR_CODIGO = $tipoDocto";
        $rs = $this->db->conn->query($sql);
        if (! empty($rs) && ! $rs->EOF) {
            return $rs->fields["SGD_SRD_DESCRIP"] . "/" . $rs->fields["SGD_SBRD_DESCRIP"] . "/" . $rs->fields["SGD_TPR_DESCRIP"];
        }
        return null;
    }

    public function getDetalleTipoDocumental($tipoDocumental)
    {
        $arrayResultado = array();
        $sql = "Select SGD_TPR_TERMINO, detalle_causal from SGD_TPR_TPDCUMENTO where SGD_TPR_CODIGO = " . $tipoDocumental;
        $rs = $this->db->conn->query($sql);
        if (! empty($rs) && ! $rs->EOF) {
            $arrayResultado["termin"] = $rs->fields["SGD_TPR_TERMINO"];
            $arrayResultado["seccau"] = $rs->fields["DETALLE_CAUSAL"];
            return $arrayResultado;
        }
        return null;
    }

    public function haveTrd($radicado)
    {
        $sql = "SELECT radi_nume_radi AS RADI_NUME_RADI
        FROM SGD_RDF_RETDOCF r
        WHERE RADI_NUME_RADI = $radicado";
        $rs = $this->db->conn->query($sql);
        $radiNumero = $rs->fields["RADI_NUME_RADI"];
        return ! empty($radiNumero);
    }

    public function insertarTrd($dependencia, $serie, $subserie, $tipo, $radicado, $usuario, $observa, $detalle = null, $compete = null)
    {
        $trd = new TipoDocumental($this->db);
        $Historico = new Historico($this->db);
        if ($this->haveTrd($radicado)) {
            throw new Exception("El documento Tiene una TRD asignada");
        } else {
            $rad[0] = $radicado;
            $codiTRD = $this->getMatrixId($dependencia, $serie, $subserie, $tipo);
            $radicados = $trd->insertarTRD($codiTRD, $codiTRD, $radicado, $dependencia, $usuario);
            $observa = "Tipificar Documento";
            $radiModi = $Historico->insertarHistorico($rad, $usuario["DEPE_CODI"], $usuario["USUA_CODI"], $dependencia, $usuario["USUA_CODI"], $observa, 32);
            $radiUp = $trd->actualizarTRD($rad, $tipo);
            $this->asignarSectorCausal($radicado, $usuario, $detalle, $compete);
        }
    }

    public function asignarSectorCausal($radicado, $usuario, $detalle, $compete = null)
    {
        $sqlFechaDocto = $this->db->conn->SQLDate("Y-m-d", "r.radi_fech_radi");
        $sqlRadicado = "select " . $sqlFechaDocto . " radi_fech_radi from radicado r
            where r.radi_nume_radi= " . $radicado;
        
        $rsUp = $this->db->conn->query($sqlRadicado);
        $fecha = $rsUp->fields["RADI_FECH_RADI"];
        $cambiaTermino = false;
        
        if (! empty($detalle)) {
            $sector = new Sectores();
            $detalleDocumento = $sector->getAllDetalle($detalle);
            $dias = $detalleDocumento->field["TERMINO"];
            $causal = $detalleDocumento->field["SGD_CAU_CODIGO"];
            $detalleCausal = $detalle;
            $sector->persistCausales($radicado, $detalleDocumento->field["SGD_CAU_CODIGO"], $detalleDocumento->field["SGD_DCAU_CODIGO"], $detalleDocumento->field["SGD_DDCA_CODIGO"], $usuario["USUA_DOC"]);
            $vencimiento = $this->calendario->calcular($fecha, $dias);
            $cambiaTermino = true;
        }
        if (! empty($compete)) {
            $vencimiento = $this->calendario->calcular($fecha, 5);
            $cambiaTermino = true;
        }
        if ($cambiaTermino) {
            $record["RADI_NUME_RADI"] = $radicado;
            $record["FECH_VCMTO"] = $this->db->conn->DBDate($vencimiento);
            $insertSQL = $this->db->conn->Replace("RADICADO", $record, array(
                'RADI_NUME_RADI'
            ), false);
            if ($this->atencion != null) {
                $data["RADI_NUME_RADI"] = $radicado;
                $data["procede"] = $compete;
                $data["det_causal"] = $causal;
                $data["det_causal"] = $detalleCausal;
                $this->atencion->actualizaTipoficacion($radicado, $data);
            }
        }
    }

    public function modificarTrd($dependencia, $serie, $subserie, $tipo, $radicado, $usuario, $observa, $detalle = null, $compete = null)
    {
        $trd = new TipoDocumental($this->db);
        $detalleTrd = $this->getDetalleRadicado($radicado);
        $observa = "*Modificado TRD* " . $detalleTrd["serie"] . "/" . $detalleTrd["subserie"] . "/" . $detalleTrd["tipoDocto"] . " " . $observa;
        $Historico = new Historico($this->db);
        $rad[0] = $radicado;
        $radiModi = $Historico->insertarHistorico($rad, $usuario["DEPE_CODI"], $usuario["USUA_CODI"], $usuario["DEPE_CODI"], $usuario["DEPE_CODI"], $observa, 32);
        /*
         * Actualiza el campo tdoc_codi de la tabla Radicados
         */
        $radiUp = $trd->actualizarTRD($rad, $tipo);
        $idTMRD = $this->getMatrixId($dependencia, $serie, $subserie, $tipo);
        $sqlUA = "UPDATE SGD_RDF_RETDOCF SET SGD_MRD_CODIGO = " . $idTMRD . ",
	    USUA_CODI = " . $usuario["USUA_CODI"] . ",
	    DEPE_CODI = " . $usuario["DEPE_CODI"] . "
	    WHERE RADI_NUME_RADI = " . $radicado;
        $rsUp = $this->db->conn->query($sqlUA);
        $this->asignarSectorCausal($radicado, $usuario, $detalle, $compete);
    }

    public function getDetalleRadicado($radicado)
    {
        $detalle = null;
        $sqlH = "SELECT  RADI_NUME_RADI,
	    SGD_MRD_CODIGO
	    FROM SGD_RDF_RETDOCF r
	    WHERE RADI_NUME_RADI = {$radicado}";
        $rsUp = $this->db->conn->query($sqlH);
        if (! empty($rsUp)) {
            $codiActu = $rsUp->fields['SGD_MRD_CODIGO'];
            $detalle = $this->getDetalle($codiActu);
            $detalle["radicado"] = $radicado;
            $detalle["idMat"] = $codiActu;
        }
        return $detalle;
    }

    public function getDetalle($idMatiz)
    {
        $detalleRetencion = array();
        $sqlFechaDocto = $this->db->conn->SQLDate("Y-m-D H:i:s A", "mf.sgd_rdf_fech");
        $sqlSubstDescS = $this->db->conn->substr . "(s.sgd_srd_descrip, 0, 30)";
        $sqlSubstDescSu = $this->db->conn->substr . "(su.sgd_sbrd_descrip, 0, 30)";
        $sqlSubstDescT = $this->db->conn->substr . "(t.sgd_tpr_descrip, 0, 30)";
        
        $isqlCD = "select
	    $sqlSubstDescS   AS SERIE,
	    $sqlSubstDescSu  AS SUBSERIE,
	    $sqlSubstDescT   AS TIPO_DOCUMENTO
	    from
	    SGD_MRD_MATRIRD m,
	    SGD_SRD_SERIESRD s,
	    SGD_SBRD_SUBSERIERD su,
	    SGD_TPR_TPDCUMENTO t
	    where m.sgd_mrd_codigo = {$idMatiz}
	    and m.sgd_srd_codigo = s.sgd_srd_codigo
	    and m.sgd_srd_codigo   = su.sgd_srd_codigo
	    and m.sgd_sbrd_codigo = su.sgd_sbrd_codigo
	    and m.sgd_tpr_codigo  = t.sgd_tpr_codigo
	    ";
        $rsTRD = $this->db->conn->Execute($isqlCD);
        if (! empty($rsTRD)) {
            $detalleRetencion["serie"] = $rsTRD->fields['SERIE'];
            $detalleRetencion["subserie"] = $rsTRD->fields['SUBSERIE'];
            $detalleRetencion["tipoDocto"] = $rsTRD->fields['TIPO_DOCUMENTO'];
        }
        return $detalleRetencion;
    }
}

?>
