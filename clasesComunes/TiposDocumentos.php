<?php
if (empty($path_raiz))
    $path_raiz = realpath(dirname(__FILE__) . "/../../");

require_once ($path_raiz . "/include/db/Connection/Connection.php");

class TipoIdentificacion
{

    protected $db;

    public function __construct()
    {
        $this->db = Connection::getCurrentInstance();
    }

    public function getListaTiposCiudano()
    {
        try {
            $select = "  SELECT
                        tdid_desc,tdid_codi
                     FROM tipo_doc_identificacion 
                      where   tdid_tipo = 1 ";
            $rs = $this->db->conn->Execute($select);
            return $rs;
        } catch (Exception $e) {
            throw new Exception("Error al consultar listado de Documentos");
        }
    }

    public function getListaTiposEmpresas()
    {
        try {
            $select = "  SELECT
                        tdid_desc,tdid_codi
                     FROM tipo_doc_identificacion
                      where   tdid_tipo = 2 ";
            $rs = $this->db->conn->Execute($select);
            return $rs;
        } catch (Exception $e) {
            throw new Exception("Error al consultar listado de Documentos");
        }
    }

    public function getListaTiposEsp()
    {
        try {
            $select = "  SELECT
                        tdid_desc,tdid_codi
                     FROM tipo_doc_identificacion
                      where   tdid_tipo = 3 ";
            $rs = $this->db->conn->Execute($select);
            return $rs;
        } catch (Exception $e) {
            throw new Exception("Error al consultar listado de Documentos");
        }
    }
}
?>
