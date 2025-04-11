<?php
if (empty($path_raiz))
    $path_raiz = realpath(dirname(__FILE__) . "/../../");

require_once ($path_raiz . "/include/db/Connection/Connection.php");

class Divipola
{

    protected $db;

    public function __construct()
    {
        $this->db = Connection::getCurrentInstance();
    }

    public function getContinentes()
    {
        $sql_continentes = "SELECT SGD_DEF_CONTINENTES.NOMBRE_CONT
						FROM SGD_DEF_CONTINENTES 
						ORDER BY SGD_DEF_CONTINENTES.NOMBRE_CONT";
        $rs = $this->db->conn->Execute($sql_continentes);
        return $rs;
    }

    public function getPaises($continente = "")
    {
        $cont="";
        if (! empty($continente)) {
            $cont=" where SGD_DEF_PAISES.ID_CONT = $continente";
            
        }
        $id= $this->db->conn->Concat("SGD_DEF_PAISES.ID_CONT","'-'","SGD_DEF_PAISES.ID_PAIS");
        $sql_pais = "SELECT  SGD_DEF_PAISES.NOMBRE_PAIS AS NOMBRE,$id 
			FROM SGD_DEF_PAISES
			$cont 
			ORDER BY SGD_DEF_PAISES.NOMBRE_PAIS";
		$rs = $this->db->conn->Execute($sql_pais);
		return $rs;
            
        }
        public function getDepto($paisCodi){
            $paisCod = explode("-", $paisCodi);
            $continente=$paisCod[0];
            $pais=$paisCod[1];
            $cad = $this->db->conn->Concat("DEPARTAMENTO.ID_PAIS","'-'","DEPARTAMENTO.DPTO_CODI");
            $sql_dpto= "SELECT DEPARTAMENTO.DPTO_NOMB AS NOMBRE , $cad AS ID1
                FROM DEPARTAMENTO
                WHERE DEPARTAMENTO.ID_PAIS = $pais
                ORDER BY DEPARTAMENTO.DPTO_NOMB ";
            $rs = $this->db->conn->Execute($sql_dpto);
            return $rs;
        
        }
        public function getMucipio($deptoCodi){
            $dpCod = explode("-", $deptoCodi);
            $pais=$dpCod[0];
            $depCod=$dpCod[1];
            
            $cad = $this->db->conn->Concat("MUNICIPIO.ID_PAIS", "'-'", "MUNICIPIO.DPTO_CODI", "'-'", "MUNICIPIO.MUNI_CODI");
            $sql_mcpo = "SELECT MUNICIPIO.MUNI_NOMB as NOMBRE,$cad as ID1
            FROM MUNICIPIO, DEPARTAMENTO,SGD_DEF_PAISES, SGD_DEF_CONTINENTES
            WHERE MUNICIPIO.ID_PAIS = SGD_DEF_PAISES.ID_PAIS AND
            MUNICIPIO.ID_CONT = SGD_DEF_CONTINENTES.ID_CONT AND
            MUNICIPIO.DPTO_CODI = DEPARTAMENTO.DPTO_CODI AND
            DEPARTAMENTO.DPTO_CODI = $depCod AND
            MUNICIPIO.ID_PAIS= $pais
            ORDER BY MUNICIPIO.MUNI_NOMB";
            $rs = $this->db->conn->Execute($sql_mcpo);
            return $rs;
            
        }
}
?>
