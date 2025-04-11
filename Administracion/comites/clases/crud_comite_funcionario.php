<?php

$ruta_raiz = "../..";

session_start();

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once('ComiteFuncionario.php');


if (! isset($db))
    $db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

// incluye la clase Db

	class CrudComiteFuncionario{

		var $db; 
		// constructor de la clase
		public function __construct($db){
			$this->db = $db;
		}
 		
		// método para insertar, recibe como parámetro acta y id funcionario
		public function insertar($comite,$funcionario){
			$isql = "SELECT MAX(ID_COMITE_FUNCIONARIO)+1 AS MAXIMO FROM ACTUACIONES.COM_COMITE_FUNCIONARIO";
			$rs = $this->db->query ( $isql );
			if (! $rs->EOF) {
				$ID_COMITE_FUNCIONARIO = $rs->fields ["MAXIMO"];
				$rs->MoveNext ();
			}
			if($ID_COMITE_FUNCIONARIO==null){
				$ID_COMITE_FUNCIONARIO=1;
			}

			$van=0;
			$sql="INSERT INTO ACTUACIONES.COM_COMITE_FUNCIONARIO (ID_COMITE_FUNCIONARIO, ID_COMITE, ID_FUNCIONARIO)
			VALUES ($ID_COMITE_FUNCIONARIO,'".$comite."','".$funcionario."')";

			if ($this->db->query($sql) == TRUE) {
			    //echo "New Actuación created successfully";
			    $van=1;
			} 

 			return $van;
		}
 		

 		// método para eliminar, recibe como parámetro acta y id funcionario
 		public function eliminar($comite,$funcionario){
			$van=0;
			$sql="DELETE FROM ACTUACIONES.COM_COMITE_FUNCIONARIO WHERE ID_COMITE = '".$comite."' AND ID_FUNCIONARIO='".$funcionario."'";

			if ($this->db->query($sql) == TRUE) {
			    $van=1;
			} 

 			return $van;
		}
		
	}
?>