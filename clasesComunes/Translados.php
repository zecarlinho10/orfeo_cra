<?php
require_once (realpath(dirname(__FILE__) . "/../") . "/include/db/Connection/Connection.php");

class Translados
{

    protected $db;

    public function __construct()
    {
        $this->db = Connection::getCurrentInstance();
    }

    public function getTranslados($radicado)
    {
        $translados = array();
        $sql = "select * from sgd_atc_translados where 
                    radi_nume_radi = " . $radicado . " and 
                        sgd_estado = 1";
        $rs = $this->db->conn->Execute($sql);
        while (! empty($rs) && ! $rs->EOF) {
            $translados[$rs->fields["SGD_ATC_IDTRASLADO"]]["anexo"] = $rs->fields["ANEX_CODI"];
            $translados[$rs->fields["SGD_ATC_IDTRASLADO"]]["OEM"] = $rs->fields["SGD_OEM_OEMPRESA"];
            $translados[$rs->fields["SGD_ATC_IDTRASLADO"]]["ESP"] = $rs->fields["SGD_ESP_ESP"];
            $translados[$rs->fields["SGD_ATC_IDTRASLADO"]]["ESP"] = $rs->fields["RADI_NUME_RADI"];
            if (! empty($rs->fields["SGD_OEM_OEMPRESA"])) {
                $translados["empresas"][] = "oem-" . $rs->fields["SGD_OEM_OEMPRESA"];
            } elseif (! empty(! empty($rs->fields["SGD_ESP_ESP"]))) {
                $translados["empresas"][] = "esp-" . $rs->fields["SGD_ESP_ESP"];
            }
        }
        return $translados;
    }
    public function haveTranslados($radicado)
    {
        $trans = $this->getTranslados($radicado);
        return count($trans) > 0;
    }

    public function comparaTranslados($entidades, $translados)
    {
        $translados["nuevos"] = array_diff($entidades, $translados);
        $translados["eliminar"] = array_diff($translados, $entidades);
        
        return $translados;
    }

    public function asignarTranslados($entidades, $radicado)
    {
        foreach ($entidades as $value) {
            $record["RADI_NUME_RADI"] = $radicado;
            $tmp = explode("-", $value);
            if ($tmp[0] == "oem") {
                $record["SGD_OEM_OEMPRESA"] = $tmp[1];
            } else {
                $record["SGD_ESP_ESP"] = $tmp[1];
            }
            $record["sgd_atc_idtraslado"] = $this->db->conn->nextId("SEC_ATC_TRANSLADO");
            $this->db->conn->Replace("sgd_atc_translados", $record, "radi_nume_radi", true);
        }
    }

    public function elimiarTranslado($entidad, $radicado)
    {
        $isql = "delete form sgd_atc_translados where radi_nume_radi=" . $radicado . " and ";
        foreach ($entidad as $value) {
            $tmp = explode("-", $value);
            if (count($tmp) == 2) {
                if ($tmp[0] == "oem") {
                    $delete = $isql . " SGD_OEM_OEMPRESA =" . $tmp[0];
                } elseif ($tmp[0] == "esp") {
                    $delete = $isql . " SGD_ESP_ESP =" . $tmp[0];
                }
                $this->db->conn->Execute($delete);
            }
        }
    }

    public function asignarAnexo($idTrasnlado, $anexCodigo)
    {
        if (! empty($anexCodigo) && ! empty($idTrasnlado)) {
            $record["SGD_ATC_IDTRANSLADO"] = $idTrasnlado;
            $record["ANEX_CODI"] = $anexCodigo;
            $this->db->conn->Replace("sgd_atc_translados", $record, array(
                "SGD_ATC_IDTRANSLADO"
            ), true);
        }
    }

    public function generarEntidades($entidades)
    {
        $tiposEntidades = array();
        foreach ($entidades as $value) {
            $tmp = explode("-", $value);
            if ($tmp[0] == "oem") {
                $tiposEntidades["oem"][] = $tmp[1];
            } else {
                $tiposEntidades["esp"][] = $tmp[1];
            }
        }
        return $tiposEntidades;
    }
}



/*create table sgd_atc_translados (sgd_atc_idtraslado numeric(12,0) not null,
 * radi_nume_radi numeric(15,0),anex_codi numeric(20,0),sgd_oem_oempresa numeric (12,0),
 * sgd_esp_esp numeric (12,0),sgd_estado numeric(3,0) default 0,
 * sgd_usua_creador varchar(200),
 * sgd_usua_modifica varchar(200),
 *  primary key (sgd_atc_idtraslado));
 * 
 * */
