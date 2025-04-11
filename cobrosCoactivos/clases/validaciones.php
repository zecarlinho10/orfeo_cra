<?php

$ruta_raiz = "../../";

include_once "$ruta_raiz/include/db/ConnectionHandler.php";

if (! isset($db))
    $db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

// incluye la clase Db

	class Validaciones{

		private $db; 
		// constructor de la clase
		public function __construct($db){
			$this->db = $db;
		}
 		
		
		//Retorna 1 si el radicado existe o 0 si NO existe.
		public function validarRadicado($radicado){
			
			$rs=$this->db->query("SELECT 1
								  FROM RADICADO 
								  WHERE RADI_NUME_RADI = ". $radicado);
			$van=0;
			while (! $rs->EOF) {
				$van=1;
				$rs->MoveNext ();
			}
			return $van;
		}

		//Retorna 1 si el expediente existe o 0 si NO existe.
		public function validarExpediente($expediente){
			
			$rs=$this->db->query("SELECT 1 
								FROM SGD_SEXP_SECEXPEDIENTES
								WHERE SGD_EXP_NUMERO = '". $expediente . "'");
			$van=0;
			while (! $rs->EOF) {
				$van=1;
				$rs->MoveNext ();
			}
			return $van;
		}

	}
?>