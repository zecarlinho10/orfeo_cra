<?php
/**
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright

SIIM2 Models are the data definition of SIIM2 Information System
Copyright (C) 2013 Infometrika Ltda.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class Usuario {
    /*** Attributes:
     * Clase que maneja los usuarios
     */

    var $db;
    var $result;

    function __construct($db){
        $this->db=$db;
    }


    /**
     * crear y editar usuario
     * @param  array son necesarios el id_usuario, el tipo de usuario y datos
     * @return bool
     */
    function usuarioCreaEdita($datos){
        // Usuario ....................................................................
        $idUser = intval($datos['id_table']); //Id del usuario

        switch ( $datos['sgdTrd'] ){
            case 0:
                if(empty($idUser)){
                    $nextval=$this->db->nextId("sec_ciu_ciudadano");

                    if ($nextval==-1){
                        $this->result[] = array( "error"  => 'No se encontr&oacute; la secuencia sec_ciu_ciudadano');
                        return false;
                    }
                } else {
                    $nextval = $idUser;
                }

                $record = array();
                $record['SGD_CIU_CODIGO']     = $nextval;
                $record['SGD_CIU_NOMBRE']     = $datos['nombre'];
                $record['SGD_CIU_APELL1']     = $datos['apellido'];
                $record['SGD_CIU_DIRECCION']  = $datos['direccion'];
                $record['SGD_CIU_TELEFONO']   = $datos['telef'];
                $record['SGD_CIU_EMAIL']      = $datos['email'];
                $record['SGD_CIU_CEDULA']     = $datos['cedula'];
                $record['TDID_CODI']          = $datos['tdid_codi'];
                $record['MUNI_CODI']          = $datos['muni_tmp'];
                $record['DPTO_CODI']          = $datos['dpto_tmp'];
                $record['ID_CONT']            = $datos['cont_tmp'];
                $record['ID_PAIS']            = $datos['pais_tmp'];

                $insertSQL = $this->db->conn->Replace("SGD_CIU_CIUDADANO",$record,'SGD_CIU_CODIGO',$autoquote = true);

                //Regresa 0 si falla, 1 si efectuo el update y 2 si no se
                //encontro el registro y el insert fue con exito
                if($insertSQL){
                    $this->result = $nextval;
                    return true;
                }

                break;

            // Empresas ....................................................................
            case 2:
                if(empty($idUser)){
                    $nextval=$this->db->nextId("sgd_oem_oempresas");

                    if ($nextval==-1){
                        $this->result = array( "error"  => 'No se encontr&oacute; la secuencia sgd_oem_oempresas');
                        return false;
                    }

                } else {
                    $nextval = $idUser;
                }

                $record = array();
                $record['SGD_OEM_CODIGO']     = $nextval;
                $record['TDID_CODI']          = $datos['tdid_codi'];
                $record['SGD_OEM_OEMPRESA']   = $datos['nombre'];
                $record['SGD_OEM_REP_LEGAL']  = $datos['apellido'];
                $record['SGD_OEM_NIT']        = $datos['cedula'];

                $record['SGD_OEM_DIRECCION']  = $datos['direccion'];
                $record['SGD_OEM_TELEFONO']   = $datos['telef'];
                $record['SGD_OEM_EMAIL']      = $datos['email'];

                $record['MUNI_CODI']          = $datos['muni_tmp'];
                $record['DPTO_CODI']          = $datos['dpto_tmp'];
                $record['ID_CONT']            = $datos['cont_tmp'];
                $record['ID_PAIS']            = $datos['pais_tmp'];

                $insertSQL = $this->db->conn->Replace("SGD_OEM_OEMPRESAS",$record,'SGD_CIU_CODIGO',$autoquote = true);

                if($insertSQL){
                    $this->result = $codigo;
                    return true;
                }

                break;

            // Funcionario .................................................................
            case 6:
                $this->result = $idUser;
                return true;
                break;
        }
    }
}
