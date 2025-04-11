<?php
require_once (realpath(dirname(__FILE__) . "/../") . "/include/db/Connection/Connection.php");

class BuscarDestinatario
{

    protected $db;

    public function __construct()
    {
        $this->db = Connection::getCurrentInstance();
    }
/************************/
    public function buscarPersonaNatural($identificacion,$cantidad=30)
    {
        $isqlCodigo = "select * from SGD_CIU_CIUDADANO WHERE  SGD_CIU_CEDULA like '%" . $identificacion ."%'";
        $rs = $this->db->conn->SelectLimit($isqlCodigo, $cantidad, - 1);
        return $rs;
    }

    public function buscarPersonaNaturalXnombre($txtapellido1,$cantidad=30)
    {
        $isqlCodigo = "select * from SGD_CIU_CIUDADANO WHERE  SGD_CIU_NOMBRE like '%" . strtoupper($txtapellido1) ."%'";
        $rs = $this->db->conn->SelectLimit($isqlCodigo, $cantidad, - 1);
        return $rs;
    }
/************************/
    
    public function buscarEmpresasXnits($nit,$cantidad=30)
    {
        $isqlCodigo = "select * from SGD_OEM_OEMPRESAS WHERE SGD_OEM_NIT LIKE '%" . $nit ."%'";
        $rs = $this->db->conn->SelectLimit($isqlCodigo, $cantidad, - 1);
        return $rs;
    }

    public function buscarEmpresas($nit,$catidad=30)
	{
        $isqlCodigo = "select * from SGD_OEM_OEMPRESAS
                        where SGD_OEM_NIT LIKE '%" . $nit ."%'";
        $rs = $this->db->conn->SelectLimit($isqlCodigo, $cantidad, - 1);
        return $rs;
    }

    public function buscarEmpresaXnombres($nomb,$cantidad=30)
    {
        $isqlCodigo = "select * from SGD_OEM_OEMPRESAS WHERE SGD_OEM_OEMPRESA LIKE '%" . strtoupper($nomb) ."%'";
        $rs = $this->db->conn->SelectLimit($isqlCodigo, $cantidad, - 1);
        return $rs;
    }

    public function buscarESP($nombre,$cantidad=30)
    {
        $isqlCodigo = "select NIT_DE_LA_EMPRESA AS SGD_CIU_CEDULA  
                      ,NOMBRE_DE_LA_EMPRESA as SGD_CIU_NOMBRE
                      ,SIGLA_DE_LA_EMPRESA as SGD_CIU_APELL1
                      ,IDENTIFICADOR_EMPRESA AS SGD_CIU_CODIGO
                      ,DIRECCION as SGD_CIU_DIRECCION
                      ,TELEFONO_1 AS SGD_CIU_TELEFONO
                      ,NOMBRE_REP_LEGAL as SGD_CIU_APELL2
                      ,SIGLA_DE_LA_EMPRESA as SGD_CIU_SIGLA
                      ,EMAIL AS SGD_CIU_EMAIL
                      ,CODIGO_DEL_DEPARTAMENTO as DPTO_CODI
                      ,CODIGO_DEL_MUNICIPIO as MUNI_CODI
                      ,ID_PAIS, ID_CONT
                      from BODEGA_EMPRESAS
                      WHERE
                      UPPER(SIGLA_DE_LA_EMPRESA) LIKE '%" . strtoupper($nombre) . "%'
                      OR UPPER(NOMBRE_DE_LA_EMPRESA) LIKE '%" . strtoupper($nombre) . "%'";
        $rs = $this->db->conn->SelectLimit($isqlCodigo,$cantidad , - 1);
        return $rs;
    }

    public function buscarPerJur($documento,$nombre)
    {
        $isqlCodigo = "select SGD_OEM_NIT AS SGD_CIU_CEDULA
                      ,SGD_OEM_OEMPRESA as 	SGD_CIU_NOMBRE
                      ,SGD_OEM_REP_LEGAL as SGD_CIU_APELL2
                      ,SGD_OEM_CODIGO AS SGD_CIU_CODIGO
                      ,SGD_OEM_DIRECCION as SGD_CIU_DIRECCION
                      ,SGD_OEM_TELEFONO AS SGD_CIU_TELEFONO
                      ,SGD_OEM_SIGLA AS SGD_CIU_APELL1
                      ,MUNI_CODI,DPTO_CODI,ID_PAIS,ID_CONT
                      from SGD_OEM_OEMPRESAS
                      where
                     SGD_OEM_NIT = '".strtoupper(trim($documento))."' AND 
                     upper(SGD_OEM_OEMPRESA) = '".strtoupper(trim($nombre))."'";
        $rs = $this->db->conn->Execute($isqlCodigo);
        return $rs;
    }

    public function buscarPerNa($documento,$primerApellido,$nombre)
    {
        $isqlCodigo = "select * from SGD_CIU_CIUDADANO WHERE 
            SGD_CIU_CEDULA = '".strtoupper(trim($documento))."' and
            upper(sgd_ciu_apell1)= '".strtoupper(trim($primerApellido))."' and
            upper(sgd_ciu_nombre)='".strtoupper(trim($nombre))."' ";
        $rs = $this->db->conn->Execute($isqlCodigo);
        return $rs;
    }

    public function buscaESP($idESP)
    {
        $isqlCodigo = "select NIT_DE_LA_EMPRESA AS SGD_CIU_CEDULA  
                      ,NOMBRE_DE_LA_EMPRESA as SGD_CIU_NOMBRE
                      ,SIGLA_DE_LA_EMPRESA as SGD_CIU_APELL1
                      ,IDENTIFICADOR_EMPRESA AS SGD_CIU_CODIGO
                      ,DIRECCION as SGD_CIU_DIRECCION
                      ,TELEFONO_1 AS SGD_CIU_TELEFONO
                      ,NOMBRE_REP_LEGAL as SGD_CIU_APELL2
                      ,SIGLA_DE_LA_EMPRESA as SGD_CIU_SIGLA
                      ,CODIGO_DEL_DEPARTAMENTO as DPTO_CODI
                      ,CODIGO_DEL_MUNICIPIO as MUNI_CODI
                      ,ID_PAIS, ID_CONT
                      from BODEGA_EMPRESAS
                      WHERE IDENTIFICADOR_EMPRESA = ".(($idESP));
        $rs = $this->db->conn->Execute($isqlCodigo);
        return $rs;
    }

    public function guardarCiu($destinatario)
    {
        $nextval = $this->db->conn->nextId("SEC_CIU_CIUDADANO");
        $record['TDID_CODI'] = $destinatario["tdid_codi"];
        $record['SGD_CIU_CODIGO'] = $nextval;
        $record['SGD_CIU_NOMBRE'] = $destinatario["nombre"];
        $record['SGD_CIU_DIRECCION'] = $destinatario["direccion"];
        $record['SGD_CIU_APELL1'] = $destinatario["apellido1"];
        $record['SGD_CIU_APELL2'] = $destinatario["apellido2"];
        $record['SGD_CIU_TELEFONO'] = $destinatario["telefono"];
        $record['SGD_CIU_CELULAR'] = $destinatario["celular"];
        $record['SGD_CIU_EMAIL'] = $destinatario["email"];
        $record['SGD_CIU_CEDULA'] = strtoupper($destinatario["documento"]);
        $record['DPTO_CODI'] = $destinatario["depto"];
        $record['ID_PAIS'] = $destinatario["pais"];
        $record['ID_CONT'] = $destinatario["continente"];
        $record['MUNI_CODI'] = $destinatario["muni"];
        
        $insertSQL = $this->db->conn->Replace("SGD_CIU_CIUDADANO", $record, array('SGD_CIU_CODIGO'), $autoquote = true);
        $destinatario['SGD_CIU_CODIGO'] = $nextval;
        //$insertSQL = $this->db->conn->insert("sgd_ciu_ciudadano", $record);
        return $destinatario;
    }

    public function guardarEmpresa($destinatario)
    {
        $nextval = $this->db->nextId("sec_oem_oempresas");
        $record['TDID_CODI'] = $destinatario["tdid_codi"];
        $record['SGD_OEM_CODIGO'] = $nextval;
        $record['SGD_OEM_OEMPRESA'] = $destinatario["noEmpresa"];
        $record['SGD_OEM_DIRECCION'] = $destinatario["direccion"];
        $record['SGD_OEM_SIGLA'] = $destinatario["apellido1"];
        $record['SGD_OEM_REP_LEGAL'] = $destinatario["apellido2"];
        $record['SGD_OEM_TELEFONO'] = $destinatario["telefono"];
        $record['SGD_OEM_NIT'] = $destinatario["documento"];
        $record['SGD_OEM_EMAIL'] = $destinatario["email"];
        $record['DPTO_CODI'] = $destinatario["depto"];
        $record['ID_PAIS'] = $destinatario["pais"];
        $record['ID_CONT'] = $destinatario["continente"];
        $record['MUNI_CODI'] = $destinatario["muni"];
        $record['SGD_OEM_ESTADO'] = 1;
        
        $insertSQL = $this->db->conn->Replace("SGD_OEM_OEMPRESAS", $record, array(
            'SGD_OEM_CODIGO'
        ), true);
        $destinatario['sgd_ciu_codigo'] = $nextval;
        return $destinatario;
    }

    public function generaDestinatario($array)
    {
        $destinatario = array();
        
        switch ($array["tipoPersona"]) {
            case 2:
                $destinatario = $this->generaCiu($array);
                $rs = $this->buscarPerNa($destinatario["documento"],$destinatario["apellido1"],$destinatario["nombre"]);
                if (empty($rs) || empty($rs->fields["SGD_CIU_CODIGO"])) {
                    $destinatario = $this->guardarCiu($destinatario);
                } else {
                    $destinatario["sgd_ciu_codigo"] = $rs->fields["SGD_CIU_CODIGO"];
                }
                $destinatario["tipo_emp_us"] = 0;
                break;
            case 1:
                $destinatario = $this->generaOEM($array);
                $rs = $this->buscarPerJur($destinatario["documento"],$destinatario["noEmpresa"]);
                if (empty($rs) || empty($rs->fields["SGD_CIU_CODIGO"])) {
                    $destinatario["id_des"] = $this->guardarEmpresa($destinatario);
                } else {
                    $destinatario["id_des"] = $rs->fields["SGD_CIU_CODIGO"];
                    $destinatario["sgd_ciu_codigo"] = $rs->fields["SGD_CIU_CODIGO"];
                }
                $destinatario["tipo_emp_us"] = 2;
                break;
            case 3:
                $destinatario = $this->generaESP($array);
                //$destinatario["sgd_ciu_codigo"]=$array["idEsp"];
                $destinatario["tipo_emp_us"] = 3;
                break;
            default:
                ;
                break;
        }
        return $destinatario;
    }

    protected function genDivipola($pais, $depto, $mncpio)
    {
        $divipola = array();
        $paisTmp = explode("-", $pais);
        $divipola["pais"] = $paisTmp[1];
        $divipola["cont"] = $paisTmp[0];
        $deptoTmp = explode("-", $depto);
        $divipola["depto"] = $deptoTmp[1];
        $mncpioTmp = explode("-", $mncpio);
        if (count($mncpioTmp) > 2) {
            $divipola["mncpio"] = $mncpioTmp[2];
        } else {
            $divipola["mncpio"] = 1;
        }
        
        return $divipola;
    }

    protected function generaCiu($array)
    {
        $divipola = $this->genDivipola($array["pais"], $array["dpto"], $array["mcpio"]);
        $tdidCodi = empty($array["tipoDocumento"]) ? "0" : $array["tipoDocumento"];
        $nombres = "";
        
        if (! empty($array["segNombre"])) {
            $nombres = ", " . $array["segNombre"];
        }
        $destinatario["tdid_codi"] = $tdidCodi;
        $destinatario["documento"] = $array["documento"];
        $destinatario["apellido1"] = $array["primApellido"];
        $destinatario["apellido2"] = $array["segApellido"];
        $destinatario["nombre"] = $array["primNombre"] . $nombres;
        $destinatario["direccion"] = $array["direccion"];
        $destinatario["telefono"] = $array["telefono"];
        $destinatario["email"] = $array["email"];
        $destinatario["celular"] = $array["celular"];
        $destinatario["depto"] = $divipola["depto"];
        $destinatario["pais"] = $divipola["pais"];
        $destinatario["continente"] = $divipola["cont"];
        $destinatario["muni"] = $divipola["mncpio"];
        $destinatario["tipo_emp_us"]=2;
        return $destinatario;
    }

    protected function generaOEM($array)
    {
        $divipola = $this->genDivipola($array["pais"], $array["dpto"], $array["mncpio"]);
        //$destinatario["tdid_codi"] = $array[""];
        $destinatario["tdid_codi"] = 2;
        $destinatario["documento"] = $array["txtnit"];
        $destinatario["apellido2"] = $array["txtrep"];
        $destinatario["apellido1"] = $array["txtnoEmpresa"];
        //$destinatario["apellido2"] = $array["txtnoEmpresa"];
        $destinatario["noEmpresa"] = $array["txtnoEmpresa"];
        $destinatario["direccion"] = $array["txtdirEmpresa"];
        $destinatario["email"] = $array["emailEmpresa"];
        $destinatario["telefono"] = $array["txtcontacto"];
        $destinatario["celular"] = $array["celular"];
        $destinatario["depto"] = $divipola["depto"];
        $destinatario["pais"] = $divipola["pais"];
        $destinatario["continente"] = $divipola["cont"];
        $destinatario["muni"] = $divipola["mncpio"];
        $destinatario["tipo_emp_us"]=1;
        return $destinatario;
    }

    protected function generaESP($array)
    {
        $divipola = $this->genDivipola($array["pais"], $array["dpto"], $array["mncpio"]);
        $destinatario["tdid_codi"] = $array[""];
        $destinatario["documento"] = $array["txtnitESP"];
        //$destinatario["apellido1"] = $array["txtrep"];
        $destinatario["apellido1"] = $array["txtnoESP"];
        //$destinatario["apellido2"] = $array["txtnoEmpresa"];
        $destinatario["noEmpresa"] = $array["txtnoESP"];
        $destinatario["direccion"] = $array["txtDirESP"];
        $destinatario["email"] = $array["txtemailESP"];
        $destinatario["telefono"] = $array["txtnfijoESP"];
        $destinatario["depto"] = $divipola["depto"];
        $destinatario["pais"] = $divipola["pais"];
        $destinatario["continente"] = $divipola["cont"];
        $destinatario["muni"] = $divipola["mncpio"];
        $destinatario["tipo_emp_us"]=3;
        return $destinatario;
    }
    
}

?>
