<?php
require_once (realpath(dirname(__FILE__) . "/../") . "/include/db/Connection/Connection.php");

class AtencionTipos
{

    protected $db;

    public function __construct()
    {
        $this->db = Connection::getCurrentInstance();
       
    }

    public function findActivas()
    {
        $peticiones = array();
        $isql = "select * from sgd_atciu_peticiones where 
            SGD_PETICION_ESTADO = 1";
        $rs = $this->db->conn->Execute($isql);
        while ($rs != null && ! $rs->EOF) {
            $peticiones[] = $this->fillAtencion($rs->fields);
            $rs->MoveNext();
        }
        return $peticiones;
    }

    public function findPeticion($id)
    {
        $peticion = null;
        $stmt = $this->db->conn->Prepare("select * from sgd_atciu_peticiones where
            SGD_PETICION_ID = ?");
        $isql = "select * from sgd_atciu_peticiones where
            SGD_PETICION_ID = $id";
        $rs = $this->db->conn->Execute($isql);
        $ADODB_ASSOC_CASE = 1;
        while ($rs != null && ! $rs->EOF) {
            $peticion = $this->fillAtencion($rs->fields);
            $rs->MoveNext();
        }
        return $peticion;
    }

    public function getRuleFormat($rules)
    {
        $rule = array();
        if (! empty($rules)) {
            $rlTmp = explode(',', $rules);
            foreach ($rlTmp as $value) {
                $tmp = explode('=>', $value);
                if (count($tmp) == 2) {
                    $rule[$tmp[0]] = $tmp[1];
                }
            }
        }
        return $rule;
    }

    public function fillAtencion($record)
    {
        $register = array();
        $register["id"] = $record["SGD_PETICION_ID"];
        $register["nombre"] = $record["SGD_PETICION_NOMBRE"];
        $register["descripcion"] = $record["SGD_PETICION_DESC"];
        $register["rules"] = $this->getRuleFormat($record["SGD_PETICION_RULE"]);
        $register["estado"] = $record["SGD_PETICION_ESTADO"];
        return $register;
    }
}

?>
