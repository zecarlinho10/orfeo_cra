<?php

/**
 * Membresias es la clase encargada de
 * validar los permisos de un usuario con las membresias
 * @author Carlos Ricaurte
 * @version     1.0
 * @fecha  11 dic 2018
 */
class Membresias{

    /**
     * Variable que se corresponde con su par
     * @db Objeto conexion
     *
     * @access public
     */
    var $db;

    
    function __construct($db)
    {
        /**
         * Constructor de la clase
         * @db variable en la cual se recibe el cursor sobre el cual se esta trabajando.
         */
        $this->db = $db;
    }

    /**
     * Retorna falso en caso que un usuario no tenga un permiso especifico o verdadero en caso contrario
     *
     * @return boolean true or false
     */
    function getMembresia($usuarioCodi, $depeCodi, $permiso)
    {
        //$this->db->conn->debug = true;
        $sql="SELECT AUTG_ID FROM AUTM_MEMBRESIAS 
        WHERE AUTU_ID = (SELECT ID FROM USUARIO WHERE USUA_CODI=" . $usuarioCodi . " AND DEPE_CODI=" .$depeCodi .") AND AUTG_ID=". $permiso;
        $rs1 = $this->db->conn->Execute($sql);

        $permiso = 0;
        if (! $rs1->EOF) {
          $permiso = 1;
        }

        return $permiso;
    }

    function getMembresia1($usuarioCodi, $depeCodi, $permiso)
    {
        //$this->db->conn->debug = true;
        $sql="SELECT AUTG_ID FROM AUTM_MEMBRESIAS 
        WHERE AUTU_ID = (SELECT ID FROM USUARIO WHERE USUA_CODI=" . $usuarioCodi . " AND DEPE_CODI=" .$depeCodi .") AND AUTG_ID=". $permiso;
        $rs1 = $this->db->conn->Execute($sql);

        $permiso = 0;
        if (! $rs1->EOF) {
          $permiso = 1;
        }

        return $sql;
    }


    function getPrueba($tmp)
    {
        return $tmp;
    }
}
    
?>
