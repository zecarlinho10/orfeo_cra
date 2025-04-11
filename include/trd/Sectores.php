<?php
if (empty($path_raiz)) {
    $path_raiz = realpath(dirname(__FILE__) . "/../../");
}
require_once ($path_raiz . "/include/db/Connection/Connection.php");

class Sectores
{

    private $idSector;

    private $codSector;

    private $codDesgaregado;

    private $codDetalle;

    private $estado;

    protected $db;

    public function Sectores()
    {
        $this->db = Connection::getCurrentInstance();
    }

    public function getCompetencia()
    {
        $sql = "select PARAM_VALOR,PARAM_CODI   from SGD_PARAMETRO where PARAM_NOMB ='ATC_PROCEDE'";
        $rs = $this->db->query($sql);
        return $rs;
    }

    public function getSector($sectorCod = "")
    {
        if (! empty($sectorCod)) {
            $where = " where SGD_CAU_CODIGO = $sectorCod";
        } else {
            $where = "";
        }
        $sql = "select SGD_CAU_DESCRIP,SGD_CAU_CODIGO   from SGD_CAU_CAUSAL " . $where;
        $rs = $this->db->query($sql);
        return $rs;
    }

    public function getCausal($sectorCod)
    {
       // $this->db->conn->debug = true;
        // SGD_DCAU_DESCRIP,SGD_DCAU_CODIGO
        $sql = "select SGD_DDCA_DESCRIP,SGD_DDCA_CODIGO from SGD_DCAU_CAUSAL c
				    inner join SGD_DDCA_DDSGRGDO dd on c.SGD_DCAU_CODIGO=dd.SGD_DCAU_CODIGO
					where c.SGD_CAU_CODIGO = $sectorCod order by 1";
        
        $rs = $this->db->query($sql);
        return $rs;
    }

    public function getDetalle($idCausal){
        $sql = "select SGD_DDCA_DESCRIP,SGD_DDCA_CODIGO  from SGD_DDCA_DDSGRGDO where SGD_DCAU_CODIGO = $idCausal";
        $rs = $this->db->query($sql);
        return $rs;
    }

    public function getAllDetalle($detalle)
    {
        $sql = "select ds.* from SGD_DDCA_DDSGRGDO ds left join SGD_CAU_CAUSAL d on
           ds.sgd_dcau_codigo= d.sgd_dcau_codigo 
        where SGD_DDCA_CODIGO = $detalle";
        $this->db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
        $rs = $this->db->conn->Execute($sql);
        return $rs;
    }

    public function persistCausales($radicado, $causal, $detalle, $desagregado, $usuaDoc)
    {
        $sqlBase = $sql = "SELECT * FROM sgd_caux_causales WHERE radi_nume_radi = " . $radicado . " and sgd_ddca_codigo = " . $desagregado;
        $rs = $this->db->conn->Execute($sqlBase);
        if (! empty($rs->fields)) {
            $nextval = $this->db->conn->nextId("sec_caux_causales");
        } else {
            $nextval = $rs->fields["SGD_CAUX_CODIGO"];
        }
        $record["SGD_CAUX_CODIGO"] = $nextval;
        $record["SGD_DCAU_CODIGO"] = $detalle;
        $record["SGD_DDCA_CODIGO"] = $desagregado;
        $record["SGD_CAU_CODIGO"] = $causal;
        $record["RADI_NUME_RADI"] = $radicado;
        $record["USUA_DOC"] = "'" . $usuaDoc . "'";
        $record["SGD_CAUX_FECHA"] = $this->db->conn->OffsetDate(0, $this->db->conn->sysTimeStamp);
        $insertSQL = $this->db->conn->Replace("SGD_CAUX_CAUSALES", $record, array(
            'RADI_NUME_RADI',
            'SGD_CAUX_CODIGO'
        ), false);
    }

    public function removeCausal($radicado, $desagregado)
    {
        if (! empty($radicado) && ! empty($desagregado)) {
            $delete = "delete from sgd_caux_causales where radi_nume_radi =" . $radicado . " and sgd_ddca_codigo = " . $desagregado;
            $this->db->conn->Execute($delete);
        }
    }
}

?>
