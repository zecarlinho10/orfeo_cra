<?php
require_once (realpath(dirname(__FILE__) . "/../") . "/include/db/Connection/Connection.php");

class AtencionCiudadano
{

    public function __construct()
    {
        $this->db = Connection::getCurrentInstance();
    }

    private function selectTargetDoc(&$destinatario)
    {
        $datosDireccionTipo = array();
        switch ($destinatario["tipo_emp_us"]) {
            case 1:
                $destinatario["ciudadano"] = $destinatario["sgd_ciu_codigo"];
                break;
            case 2:
                $destinatario["empresa"] = $destinatario["sgd_ciu_codigo"];
                break;
            case 2:
                $destinatario["esp"] = $destinatario["sgd_ciu_codigo"];
                break;
        }
    }

    public function crearAtencion($atencion)
    {
		$nextval = 0;
        try {
            $this->selectTargetDoc($atencion["destinatario"]);
            $nextval = $this->db->conn->nextId("SEC_ATENCION_CIUDADANO");
            $record['SGD_ATC_ID'] = $nextval;
            $record['SGD_ATC_RADICADO'] = $atencion["radicado"];
            $record['SGD_ATC_FECHA'] = $this->db->conn->OffsetDate(0, $this->db->conn->sysTimeStamp);
            $record['SGD_ATC_TIPO'] = $atencion["tipoPersona"];
            $record['SGD_ATC_DEPTO'] = $atencion["destinatario"]["depto"];
            $record['SGD_ATC_MCPIO'] = $atencion["destinatario"]["mncpio"];
            $record['SGD_ATC_PAIS'] = $atencion["destinatario"]["pais"];
            $record['SGD_ATC_CONT'] = $atencion["destinatario"]["continente"];
            $record['SGD_ATC_COMPONENTE'] = $atencion["componente"];
            $record['SGD_ATC_CIU'] = $atencion["destinatario"]["ciudadano"];
            $record['SGD_ATC_OEM'] = $atencion["destinatario"]["empresa"];
            $record['SGD_ATC_ESP'] = $atencion["destinatario"]["esp"];
            $record['SGD_ATC_MREC'] = $atencion["mrec"];
            $record['SGD_ATC_FORMULARIO'] = $atencion["formulario"];
            $record['SGD_ATC_TIPIDENTIFICA'] = $atencion["tipoDoc"];
            $record['SGD_ATC_TIPOPETICION'] = $atencion["tipoPeticion"];
            $record['NOTIFICA_CORREO'] = $atencion["notifica"];
            $record['SGD_ATC_SEXO'] = $atencion["sexo"];
            $record['SGD_ACT_DISCAPACIDAD'] = $atencion["discapacidad"];
            $record['SGD_ACT_CONFLICTO'] = $atencion["conflicto"];
            $record['SGD_ACT_LGTBI'] = $atencion["lgtbi"];
            $record['SGD_ACT_EDAD'] = $atencion["txtedad"];
            
            $insertSQL = $this->db->conn->Replace("SGD_ATC_ATENCION", $record, array(
                'SGD_ATC_ID'
            ), false);
        } catch (Exception $e) {
            throw new OrfeoException("Error al Actualizar ", $e);
        }
        return $nextval;
    }

    public function actualizaTipoficacion($radicado, $data)
    {
        try {
            $record['SGD_ATC_PROCEDE'] = $data["procede"];
            $record['SGD_ATC_CLASIFICACION'] = $data["det_causal"];
            $record['SGD_ATC_CAUSAL'] = $data["det_causal"];
            $record['SGD_ATC_RADICADO'] = $radicado;
            $insertSQL = $this->db->conn->Replace("SGD_ATC_ATENCION", $record, array(
                'SGD_ATC_RADICADO'
            ), $autoquote = true);
        } catch (Exception $e) {
            throw new OrfeoException("Error al Actualizar ", $e);
        }
    }

    public function getAtencionDetalle($radicado)
    {
        $isql = "select atc.sgd_atc_radicado,sgd_atc_procede,sgd_atc_clasificacion,
                 ds.sgd_ddca_descrip des_desgregado, d.sgd_cau_descrip causal_descp,
                  dg.termino,dg.sgd_ddca_descrip desa_descrp  
                from sgd_atc_atencion  atc left join SGD_DDCA_DDSGRGDO ds on 
                 atc.sgd_atc_clasificacion = ds.SGD_DDCA_CODIGO 
                left join SGD_CAU_CAUSAL d on
                 ds.sgd_dcau_codigo = d.sgd_dcau_codigo  
                left join sgd_ddca_ddsgrgdo dg on dg.sgd_dcau_codigo = d.sgd_dcau_codigo
                where
                atc.sgd_atc_radicado= " . $radicado;
        $rs = $this->db->conn->Execute($isql);
        return $rs;
    }
}
