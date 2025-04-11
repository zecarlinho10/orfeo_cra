<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
$ruta_raiz = "..";

session_start();

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
//require_once('actuacion.php');
require_once('funcionario.php');

if (! isset($db))
    $db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

// incluye la clase Db

	class Terceros{

		var $db; 
		// constructor de la clase
		public function __construct($db){
			$this->db = $db;
		}
 
		
 
		// método para mostrar todos los funcionarios activos
		public function getFuncionarios(){
			//$listaActuaciones=[];
			$rs=$this->db->query("	SELECT U.ID,DEPE_CODI, U.USUA_NOMB, USUA_LOGIN, DEPE_CODI
									FROM USUARIO U
									WHERE USUA_ESTA = 1 AND DEPE_CODI NOT IN(321,900,999,330,231,400,905,910,403,77,402,216,301)
									ORDER BY USUA_NOMB");

			while (! $rs->EOF) {
				$objFuncionario= new Funcionario();
				$objFuncionario->setId($rs->fields['ID']);
				$objFuncionario->setNombre($rs->fields['USUA_NOMB']);
				$listaFuncionarios[]=$objFuncionario;
				$rs->MoveNext ();
			}
			return $listaFuncionarios;
		}
 
		// método para eliminar un actuacion, recibe como parámetro el id del actuacion
		public function eliminar($id){
			$eliminar=$this->db->query("DELETE FROM ACT_ACTUACION WHERE IDACTUACION=id");

			$van=0;

			if ($this->db->query($sql) == TRUE) {
			    //echo "New Actuación created successfully";
			    $van=1;
			} 

			return $van;
		}
 

		// método para actualizar un actuacion, recibe como parámetro el actuacion
		public function actualizar($actuacion){
			$sql="UPDATE ACT_ACTUACION SET NOMBRE='$actuacion->getNombre()', FECHA_INICIO='$actuacion->getFechaInicio()',FECHA_FIN='$actuacion->getFechaFin()', 
			ESTADO='$actuacion->getEstado()', OBJETIVO='$actuacion->getObjetivo()',  EXPEDIENTE='$actuacion->getExpediente()', TIPOTRAMITE='$actuacion->getTipoTramite()'
			WHERE IDACTUACION=$actuacion->getId()";

			$rs=$this->db->query($sql);
			$van=0;

			if ($this->db->query($sql) == TRUE) {
			    //echo "New Actuación update successfully";
			    $van=1;
			} 

			return $van;
		}
	}
?>