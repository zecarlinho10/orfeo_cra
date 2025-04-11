<?php 
 

class Anulacion{

    /** Aggregations: */

    /** Compositions: */

    /*** Attributes: ***/


    /**
     * Clase que maneja los documentos anulados 
     *
     * @param int Dependencia Dependencia de Territorial que Anula 
     * @db Objeto conexion
     * @access public
     */
    var $db;
    function __construct($db)
    {
        /**
         * Constructor de la clase anulaci�n
         * @db variable en la cual se recibe el cursor sobre el cual se esta trabajando.
         * 
         */
        $this->db = $db;
    }
    function consultaAnulados( $dependencia )
    {

    } // end of member function consultaAnulados

    /**
     * 
     *
     * @db Cursor de la base de datos que etamos trabajando.
     * @param int dependencia Dependencia que pide la anulacion del documento. 
     * @param int usuadoc Documento de identificación del usuario que pide la anulación 
     * @return void
     * @access public
     */
function solAnulacion($radicados, $dependencia, $usuadoc, $comentario, $codUsuario, $systemDate){
        //Arreglo que almacena los nombres de columna
        #==========================
        # Codigo de prueba para UPDATE
        foreach($radicados as $noRadicado){
            $sql = "SELECT SGD_EANU_CODIGO
                FROM radicado 
                WHERE radi_nume_radi = " . $noRadicado; 
            # Selecciona el registro a actualizar
            $rs = $this->db->conn->Execute($sql); # Executa la busqueda y obtiene el registro a actualizar.

            $record = array(); # Inicializa el arreglo que contiene los datos a modificar

            # Asignar el valor de los campos en el registro
            # Observa que el nombre de los campos pueden ser mayusculas o minusculas

            $record['SGD_EANU_CODIGO'] = "1";

            # Mandar como parametro el recordset y el arreglo conteniendo los datos a actualizar
            # a la funcion GetUpdateSQL. Esta procesara los datos y regresara el enunciado sql del
            # update necesario con clausula WHERE correcta.
            # Si no se modificaron los datos no regresa nada.
            $updateSQL = $this->db->conn->GetUpdateSQL($rs, $record, true);
            $this->db->conn->Execute($updateSQL); # Actualiza el registro en la base de datos
            # Si no se modificaron los datos no regresa nada.
            $updateSQL = $this->db->conn->GetUpdateSQL($rs, $record, true);
        $this->db->conn->Execute($updateSQL); # Actualiza el registro en la base de datos

        $anuId = $this->db->conn->GenID( "sgd_anu_id" );

         //Campo del comentario en base de datos no puede superar mas de 249 caracteres
         $comentario = substr($comentario, 0, 249);

            # Insertamos en la tabla sgd_anu_anulados los registros que entran en solicitud.
            $sql = "insert into sgd_anu_anulados (RADI_NUME_RADI, SGD_EANU_CODI, SGD_ANU_SOL_FECH, DEPE_CODI  , USUA_DOC, SGD_ANU_DESC ,USUA_CODI,SGD_ANU_ID)
                values ($noRadicado   ,1 , $systemDate ,
                    $dependencia,$usuadoc , '$comentario',$codUsuario,$anuId)";
            if ($this->db->conn->Execute($sql) == false) {
                print 'error al insertar: '.$this->db->conn->ErrorMsg().'<BR>';
            }
            //echo "<hr> $sql";
            # Fin insercion en tabla de Anulados

    if ($_SESSION['entidad']=='CRA'){
    //Desarrollo para la CRA, que dicta que cuando se solicita la anulación, se archive.
    $isql = "UPDATE radicado SET radi_depe_actu = 999, radi_usua_actu = 1 WHERE radi_nume_radi = $noRadicado";
             if ($this->db->conn->Execute($isql) == false) {
                 print 'error Archivar: '.$this->db->conn->ErrorMsg().'<BR>';
             }
    }

        }
        return ($radicados);
    } // end of member function solicitudAnulacion

    /**
     * 
     *
     * @db Cursor de la base de datos que etamos trabajando.
     * @param int dependencia Dependencia que pide la anulacion del documento. 
     * @param int usuadoc Documento de identificación del usuario que pide la anulación 
     * @return void
     * @access public
     */
    function genAnulacion($radicados,  $dependencia,  $usuadoc, $comentario, $codUsuario,$actaNo, $pathActa, $where_depe,$where_TipoRadicado,$tipoRadicado){

        if ($radicados) {
            foreach($radicados as $noRadicado)
            {

                $sql = "SELECT
                    SGD_EANU_CODIGO
                    FROM radicado 
                    WHERE radi_nume_radi = " . $noRadicado; 

                # Selecciona el registro a actualizar
                $rs = $this->db->conn->Execute($sql); # Executa la busqueda y obtiene el registro a actualizar.

                $record = array(); # Inicializa el arreglo que contiene los datos a modificar

                # Asignar el valor de los campos en el registro
                # Observa que el nombre de los campos pueden ser mayusculas o minusculas

                $record['SGD_EANU_CODIGO'] = "2";

                # Mandar como parametro el recordset y el arreglo conteniendo los datos a actualizar
                # a la funcion GetUpdateSQL. Esta procesara los datos y regresara el enunciado sql del
                # update necesario con clausula WHERE correcta.
                # Si no se modificaron los datos no regresa nada.
                $updateSQL = $this->db->conn->GetUpdateSQL($rs, $record, true);
                $this->db->conn->Execute($updateSQL); # Actualiza el registro en la base de datos
                # Si no se modificaron los datos no regresa nada.
                $updateSQL = $this->db->conn->GetUpdateSQL($rs, $record, true);
                $this->db->conn->Execute($updateSQL); # Actualiza el registro en la base de datos
                # Insertamos en la tabla sgd_anu_anulados los registros que entran en solicitud.
                $sql = "update sgd_anu_anulados
                    set SGD_EANU_CODI=2,
                    DEPE_CODI_ANU= $dependencia,
                    USUA_DOC_ANU=$usuadoc,
                    USUA_CODI_ANU=$codUsuario,
                    USUA_ANU_ACTA=$actaNo,
                    SGD_TRAD_CODIGO=$tipoRadicado,
                    SGD_ANU_PATH_ACTA='$pathActa',
                    SGD_ANU_FECH = SYSDATE
                    where radi_nume_radi = $noRadicado";
                if ($this->db->conn->Execute($sql) == false) 
                {
                    print 'error al insertar: '.$this->db->conn->ErrorMsg().'<BR>';
                }
                # Fin inserci�n en tabla de Anulados
            }
            return ($radicados);
        }
    } // end of member function solicitudAnulacion



} // end of ANULACION
?>
