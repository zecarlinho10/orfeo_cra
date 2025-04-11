<?php

$ruta_raiz = "..";

session_start();

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once('ActuacionUsuario.php');

if (! isset($db))
    $db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

// incluye la clase Db

	class CrudActuacionUsuario{

		var $db; 
		// constructor de la clase
		public function __construct($db){
			$this->db = $db;
		}
 
		// método para insertar, recibe como parámetro un objeto de tipo Actuacion
		public function insertar(ActuacionUsuario $objActUsu){
			//INSERTAR ACTUACION_USUARIO
			/*
			$isql = "SELECT MAX(IDACTUSU)+1 AS MAXIMO FROM ACTUACION_USUARIO";
			$rs = $this->db->query ( $isql );
			if (! $rs->EOF) {
				$idactuacion = $rs->fields ["MAXIMO"];
				$rs->MoveNext ();
			}
			if($idactuacion==null){
				$idactuacion=1;
			}
			*/
			$van=0;
			/*
			$sql="INSERT INTO ACTUACION_USUARIO (IDACTUSU, EXPEDIENTE, IDUSUARIO, FECHA)
			VALUES (".$idactuacion.",'".$objActUsu->getExpediente()."',".$objActUsu->getIdUsuario().",(SELECT SYSDATE FROM DUAL))";
			*/
			$sql="INSERT INTO ACTUACION_USUARIO (EXPEDIENTE, IDUSUARIO, FECHA)
			VALUES ('".$objActUsu->getExpediente()."',".$objActUsu->getIdUsuario().",(SELECT SYSDATE FROM DUAL))";
			if ($this->db->query($sql) == TRUE) {
			    //echo "New Actuación created successfully";
			    $van=1;
			} 

 			return $van;
		}
		
		public function borrarActuacionUsuario($expediente){
			$van=0;
			$sql="DELETE FROM ACTUACION_USUARIO WHERE EXPEDIENTE = '".$expediente."'" ;

			if ($this->db->query($sql) == TRUE) {
			    //echo "involucrados borrados successfully";
			    $van=1;
			} 

 			return $van;
		}

		public function getLista(){
			$listaActuaciones=[];
			$rs=$this->db->query("SELECT IDACTUSU, EXPEDIENTE, IDUSUARIO, FECHA
								  FROM ACTUACION_USUARIO");

			while (! $rs->EOF) {
				$objActuacionUsuario= new ActuacionUsuario();
				$objActuacionUsuario->setIdActuacionUsuario($rs->fields['IDACTUSU']);
				$objActuacionUsuario->setExpediente($rs->fields['EXPEDIENTE']);
				$objActuacionUsuario->setIdUsuario($rs->fields['IDUSUARIO']);
				$objActuacionUsuario->setFecha($rs->fields['FECHA']);
				$listaActuaciones[]=$objActuacionUsuario;
				$rs->MoveNext ();
			}
			return $listaActuaciones;
		}
 	}
?>