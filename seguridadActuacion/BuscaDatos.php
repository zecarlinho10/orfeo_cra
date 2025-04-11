<?php
require_once (realpath(dirname(__FILE__) . "/..") . "/include/db/Connection/Connection.php");

class BuscaDatos
{

    protected $db;

    /*
    public function BuscaDatos()
    {
        $this->db = Connection::getCurrentInstance();
    }*/

    public function __construct() {
        $this->db = Connection::getCurrentInstance();
    }

    /************************/    

    public function buscarExpediente($nomb,$cantidad=30)
    {
        $isqlCodigo = "SELECT * 
                       FROM SGD_SEXP_SECEXPEDIENTES 
                       WHERE SGD_EXP_NUMERO LIKE '%" . strtoupper($nomb) ."%' OR SGD_SEXP_PAREXP1 LIKE '%" . $nomb ."%' 
                       ORDER BY SGD_SEXP_FECH";
        $rs = $this->db->conn->SelectLimit($isqlCodigo, $cantidad, - 1);
        return $rs;
    }


}

?>
