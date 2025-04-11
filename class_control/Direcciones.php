<?php
require_once (realpath(dirname(__FILE__) . "/../") . "/include/db/ConnectionHandler.php");
#include_once (realpath(dirname(__FILE__) . "/../") . "/include/db/Connection/Connection.php");
/**
 * @deprecated 
 * unificacion clase en documentos.
 * @author waduartev
 *
 */
class Direcciones
{

    private $db;

    public function __construct($db)
    {
	    $this->db = $db;
	    //$this->db = Connection::getCurrentInstance ();
    }

    public function getDireccion($radicado)
    {
        $isql = "
        SELECT
        SGD_DIR_CODIGO,
        SGD_DIR_TIPO,
        SGD_OEM_CODIGO,
        SGD_CIU_CODIGO,
        RADI_NUME_RADI,
        SGD_ESP_CODI,
        dir.MUNI_CODI,
        dir.DPTO_CODI,
        SGD_DIR_DIRECCION,
        SGD_DIR_TELEFONO,
        SGD_DIR_MAIL,
        SGD_DIR_NOMBRE,
        SGD_DOC_FUN,
        SGD_DIR_NOMREMDES,
        SGD_TRD_CODIGO,
        dir.ID_PAIS,
        dir.ID_CONT,
        DPTO_NOMB,
        MUNI_NOMB
        FROM
	SGD_DIR_DRECCIONES dir
	inner join DEPARTAMENTO d  on (d.id_pais = dir.ID_PAIS AND d.DPTO_CODI = dir.DPTO_CODI)
        inner join MUNICIPIO m on  (m.MUNI_CODI = dir.MUNI_CODI and m.DPTO_CODI = dir.DPTO_CODI and m.ID_PAIS =dir.iD_PAIS)
	where radi_nume_radi = ?"; 
        $rpsql=  $this->db->conn->prepare($isql);
	$rs   = $this->db->conn->Execute($rpsql,array($radicado));


        while (!$rs->EOF) {
            $salida[$rs->fields["SGD_DIR_TIPO"]]["SGD_DIR_CODIGO"] = $rs->fields["SGD_DIR_CODIGO"];
            $salida[$rs->fields["SGD_DIR_TIPO"]]["RADI_NUME_RADI"] = $rs->fields["RADI_NUME_RADI"];
            $salida[$rs->fields["SGD_DIR_TIPO"]]["SGD_DIR_MAIL"] = $rs->fields["SGD_DIR_MAIL"];
            $salida[$rs->fields["SGD_DIR_TIPO"]]["SGD_DIR_NOMBRE"] = $rs->fields["SGD_DIR_NOMBRE"];
	    $salida[$rs->fields["SGD_DIR_TIPO"]]["SGD_DIR_NOMREMDES"] = $rs->fields["SGD_DIR_NOMREMDES"];
	    $salida[$rs->fields["SGD_DIR_TIPO"]]["SGD_DIR_DIRECCION"] = $rs->fields["SGD_DIR_DIRECCION"];
            $salida[$rs->fields["SGD_DIR_TIPO"]]["ID_PAIS"] = $rs->fields["ID_PAIS"];
            $salida[$rs->fields["SGD_DIR_TIPO"]]["ID_CONT"] = $rs->fields["ID_CONT"];
            $salida[$rs->fields["SGD_DIR_TIPO"]]["MUNI_CODI"] = $rs->fields["MUNI_CODI"];
	    $salida[$rs->fields["SGD_DIR_TIPO"]]["DPTO_CODI"] = $rs->fields["DPTO_CODI"];
	    $salida[$rs->fields["SGD_DIR_TIPO"]]["DPTO_NOMBRE"] = $rs->fields["DPTO_NOMB"];
	    $salida [$rs->fields["SGD_DIR_TIPO"]]["MPIO_NOMBRE"] = $rs->fields["MUNI_NOMB"];
            $rs->MoveNext ();
        }
        return $salida;
    }
    public function getDireccionTipo($radicado,$tipo){
        $salida = array();
        $isql = "
        SELECT
        SGD_DIR_CODIGO,
        SGD_DIR_TIPO,
        RADI_NUME_RADI,
        SGD_ESP_CODI,
        MUNI_CODI,
        DPTO_CODI,
        SGD_DIR_DIRECCION,
        SGD_DIR_TELEFONO,
        SGD_DIR_MAIL,
        SGD_SEC_CODIGO,
        SGD_TEMPORAL_NOMBRE,
        SGD_DIR_NOMREMDES,
        ID_PAIS,
        ID_CONT,
        MREC_CODI
        FROM
        SGD_DIR_DRECCIONES
        where radi_nume_radi =" . $radicado." and SGD_DIR_TIPO =".$tipo;
        $rs = $this->db->conn->Execute($isql);
        while (!$rs->EOF) {
            $salida["SGD_DIR_CODIGO"] = $rs->fields["SGD_DIR_CODIGO"];
            $salida["RADI_NUME_RADI"] = $rs->fields["RADI_NUME_RADI"];
            $salida["SGD_DIR_MAIL"] = $rs->fields["SGD_DIR_MAIL"];
            $salida["SGD_DIR_NOMBRE"] = $rs->fields["SGD_DIR_NOMBRE"];
            $salida["SGD_DIR_NOMREMDES"] = $rs->fields["SGD_DIR_NOMREMDES"];
            $salida["ID_PAIS"] = $rs->fields["ID_PAIS"];
            $salida["ID_CONT"] = $rs->fields["ID_CONT"];
            $salida["MUNI_CODI"] = $rs->fields["MUNI_CODI"];
            $salida["DPTO_CODI"] = $rs->fields["DPTO_CODI"];
            $salida["SGD_DIR_TIPO"] = $rs->fields["SGD_DIR_TIPO"];
          $rs->MoveNext ();
        }
        return $salida;
        
    }
    public function getRadicadoDireccionTipo($radicado,$tipo){
        $salida = array();
        $isql = "
        SELECT
        dir.SGD_DIR_CODIGO,
        dir.SGD_DIR_TIPO,
        dir.RADI_NUME_RADI,
        dir.SGD_ESP_CODI,
        dir.MUNI_CODI,
        dir.DPTO_CODI,
        dir.SGD_DIR_DIRECCION,
        dir.SGD_DIR_TELEFONO,
        dir.SGD_DIR_MAIL,
        dir.SGD_SEC_CODIGO,
        dir.SGD_TEMPORAL_NOMBRE,
        dir.SGD_DIR_NOMREMDES,
        dir.ID_PAIS,
        dir.ID_CONT,
        dir.MREC_CODI,
        r.RA_ASUN,
        r.radi_path
        
        FROM
        SGD_DIR_DRECCIONES dir inner join radicado r on dir.radi_nume_radi = r.radi_nume_radi  
        where r.radi_nume_radi =" . $radicado." and SGD_DIR_TIPO =".$tipo;
        $rs = $this->db->conn->Execute($isql);
        while (!empty($rs) && !$rs->EOF) {
            $salida["SGD_DIR_CODIGO"] = $rs->fields["SGD_DIR_CODIGO"];
            $salida["RADI_NUME_RADI"] = $rs->fields["RADI_NUME_RADI"];
            $salida["SGD_DIR_MAIL"] = $rs->fields["SGD_DIR_MAIL"];
            $salida["SGD_DIR_NOMBRE"] = $rs->fields["SGD_DIR_NOMBRE"];
            $salida["SGD_DIR_NOMREMDES"] = $rs->fields["SGD_DIR_NOMREMDES"];
            $salida["ID_PAIS"] = $rs->fields["ID_PAIS"];
            $salida["ID_CONT"] = $rs->fields["ID_CONT"];
            $salida["MUNI_CODI"] = $rs->fields["MUNI_CODI"];
            $salida["DPTO_CODI"] = $rs->fields["DPTO_CODI"];
            $salida["SGD_DIR_TIPO"] = $rs->fields["SGD_DIR_TIPO"];
            $salida["PATH"]=$rs->fields["RADI_PATH"];
            $salida["RA_ASUN"]=$rs->fields["RA_ASUN"];
            $rs->MoveNext ();
        }
        return $salida;
    
    }

}

