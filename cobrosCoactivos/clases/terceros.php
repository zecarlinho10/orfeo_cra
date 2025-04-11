<?php

$ruta_raiz = "../../";

//session_start();

//include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once('cobro.php');
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
 
		// método para insertar, recibe como parámetro un objeto de tipo Actuacion
		public function insertar(Actuacion $actuacion){
			$isql = "SELECT MAX(IDACTUACION)+1 AS MAXIMO FROM ACT_ACTUACION";
			$rs = $this->db->query ( $isql );
			if (! $rs->EOF) {
				$idactuacion = $rs->fields ["MAXIMO"];
				$rs->MoveNext ();
			}
			if($idactuacion==null){
				$idactuacion=1;
			}
			//INSERTAR HISTORICOS
			$van=0;
			$sql="INSERT INTO ACT_ACTUACION (IDACTUACION, NOMBRE, FECHA_INICIO, FECHA_FIN, ESTADO, OBJETIVO, EXPEDIENTE, TIPOTRAMITE)
			VALUES ($idactuacion,'".$actuacion->getNombre()."',".$actuacion->getFechaInicioFormato($this->db).",".$actuacion->getFechaFinFormato($this->db).",'".$actuacion->getEstado()."',
					'".$actuacion->getObjetivo()."','".$actuacion->getExpediente()."', '".$actuacion->getTipoTramite()."')";

			if ($this->db->query($sql) == TRUE) {
			    //echo "New Actuación created successfully";
			    $van=1;
			} 

 			return $van;
		}
 
		// método para mostrar todos los actuacions
		public function getExpertos(){
			//$listaActuaciones=[];
			$rs=$this->db->query("SELECT U.ID, U.USUA_NOMB
								FROM AUTM_MEMBRESIAS M, USUARIO U
								WHERE AUTG_ID IN (3,21,167) AND U.ID=AUTU_ID AND DEPE_CODI = 10 AND USUA_ESTA = 1");

			while (! $rs->EOF) {
				$objFuncionario= new Funcionario();
				$objFuncionario->setId($rs->fields['ID']);
				$objFuncionario->setNombre($rs->fields['USUA_NOMB']);
				$listaFuncionarios[]=$objFuncionario;
				$rs->MoveNext ();
			}
			return $listaFuncionarios;
		}

		public function getJefe($depe){
			//$listaActuaciones=[];
			$rs=$this->db->query("	SELECT U.ID, U.USUA_NOMB
									FROM USUARIO U
									WHERE DEPE_CODI = $depe");

			while (! $rs->EOF) {
				$objFuncionario= new Funcionario();
				$objFuncionario->setId($rs->fields['ID']);
				$objFuncionario->setNombre($rs->fields['USUA_NOMB']);
				$listaFuncionarios[]=$objFuncionario;
				$rs->MoveNext ();
			}
			return $listaFuncionarios;
		}

		public function getFuncionarios(){
			//$listaActuaciones=[];
			$rs=$this->db->query("	SELECT U.ID,DEPE_CODI, U.USUA_NOMB, USUA_LOGIN, DEPE_CODI
									FROM USUARIO U
									WHERE USUA_ESTA = 1 AND DEPE_CODI NOT IN(321,900,999,330,231,400,905,910,403,77,402,216,301)
										AND DEPE_CODI IN (12,30)
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

		public function getFuncionariosPersimo($per){
			
			$rs=$this->db->query("	SELECT U.ID,DEPE_CODI, U.USUA_NOMB, USUA_LOGIN
					FROM USUARIO U, AUTM_MEMBRESIAS M
					WHERE USUA_ESTA = 1 AND M.AUTG_ID = $per AND U.ID = M.AUTU_ID AND DEPE_CODI IN (12,30)
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
 
		// método para buscar un actuacion, recibe como parámetro el id del actuacion
		public function obteneractuacion($id){
			$db=Db::conectar();
			$select=$db->prepare('SELECT IDACTUACION, NOMBRE, FECHA_INICIO, FECHA_FIN, ESTADO, OBJETIVO, EXPEDIENTE, TIPOTRAMITE 
								  FROM ACT_ACTUACION 
								  WHERE IDACTUACION=$id');
			$select=$this->db->query("SELECT MAX(IDACTUACION)+1 AS MAXIMO FROM ACT_ACTUACION");
 
			foreach($select->fetchAll() as $actuacion){
				$objActuacion= new Actuacion();
				$objActuacion->setId($actuacion['IDACTUACION']);
				$objActuacion->setNombre($actuacion['NOMBRE']);
				$objActuacion->setFechaInicio($actuacion['FECHA_INICIO']);
				$objActuacion->setFechaFin($actuacion['FECHA_FIN']);
				$objActuacion->setEstado($actuacion['ESTADO']);
				$objActuacion->setObjetivo($actuacion['OBJETIVO']);
				$objActuacion->setExpediente($actuacion['EXPEDIENTE']);
				$objActuacion->setTipoTramite($actuacion['TIPOTRAMITE']);
			}
			return $objActuacion;
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