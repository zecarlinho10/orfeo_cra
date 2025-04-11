<?php
if (empty($path_raiz))
    $path_raiz = realpath(dirname(__FILE__) . "/../../");

require_once ($path_raiz . "/include/db/Connection/Connection.php");

class Dependencia
{

    protected $db;

    public function __construct()
    {
        $this->db = Connection::getCurrentInstance();
    }

    public function getDependenciasBasico()
    {
        $value=$this->db->conn->Concat("d.DEPE_CODI", "' - '", "d.DEPE_NOMB");
        $isql = "SELECT $value, d.DEPE_CODI
        FROM
        DEPENDENCIA d inner join usuario u ON (u.depe_codi = d.depe_codi
        and u.usua_codi   = 1
        and u.usua_esta   = '1'
        and d.depe_estado = 1 )
        ORDER BY d.DEPE_CODI, d.DEPE_NOMB";
        $rs = $this->db->conn->Execute($isql, array(
            "1","1","1"
        ));
        return $rs;
    }
}


