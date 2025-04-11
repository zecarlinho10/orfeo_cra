<?php

$ruta_raiz = "../../";

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once('actuacion.php');

if (! isset($db))
    $db = new ConnectionHandler($ruta_raiz);
//$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

// incluye la clase Db

	class CrudActuacion{

		var $db; 
		// constructor de la clase
		public function __construct($db){
			$this->db = $db;
		}
 
		// método para insertar, recibe como parámetro un objeto de tipo Actuacion
		public function insertar(Actuacion $actuacion){
			$isql = "SELECT MAX(IDACTUACION)+1 AS MAXIMO FROM ACT_ACTUACION";
			$rs = $this->db->query ( $isql );
			if (!empty($rs) && !$rs->EOF) {
				$idactuacion = $rs->fields ["MAXIMO"];
				$rs->MoveNext ();
			}
			if($idactuacion==null){
				$idactuacion=1;
			}
			//INSERTAR HISTORICOS
			$van=0;
			$sql="INSERT INTO ACT_ACTUACION (IDACTUACION, NOMBRE, FECHA_INICIO, FECHA_FIN, ESTADO, OBJETIVO, EXPEDIENTE, TIPOTRAMITE, OBSERVACION)
			VALUES ($idactuacion,'".$actuacion->getNombre()."',".$actuacion->getFechaInicioFormato($this->db).",".$actuacion->getFechaFinFormato($this->db).",'".$actuacion->getIdEstado()."',
					'".$actuacion->getObjetivo()."','".$actuacion->getExpediente()."', '".$actuacion->getTipoTramite()."', '".$actuacion->getObservacion()."')";

			if ($this->db->query($sql) == TRUE) {
			    //echo "New Actuación created successfully";
			    $van=1;
			} 

 			return $van;
		}
 
		// método para mostrar todos los actuacions
		// id: solo las q tiene permiso; 0: todas; -1=activas
		public function getActuaciones($idUsuario){
			//0 SUPER USUARIO

			if($idUsuario>0){
				$query="SELECT DISTINCT A.IDACTUACION, A.NOMBRE, A.FECHA_INICIO, A.FECHA_FIN, A.ESTADO, A.OBJETIVO, A.EXPEDIENTE, A.TIPOTRAMITE, A.OBSERVACION 
				FROM ACT_ACTUACION A, ACT_INVOLUCRADO I
				WHERE A.ESTADO<>0 AND A.IDACTUACION=I.IDACTUACION  AND IDFUNCIONARIO=".$idUsuario ;
			}
			else if($idUsuario==0){
				$query="SELECT DISTINCT A.IDACTUACION, A.NOMBRE, A.FECHA_INICIO, A.FECHA_FIN, A.ESTADO, A.OBJETIVO, A.EXPEDIENTE, A.TIPOTRAMITE, A.OBSERVACION 
						FROM ACT_ACTUACION A";	
			}
			else{
				$query="SELECT DISTINCT A.IDACTUACION, A.NOMBRE, A.FECHA_INICIO, A.FECHA_FIN, A.ESTADO, A.OBJETIVO, A.EXPEDIENTE, A.TIPOTRAMITE, A.OBSERVACION 
						FROM ACT_ACTUACION A 
						WHERE A.ESTADO=1";	
			}
			
			$rs=$this->db->query($query);

			while (!empty($rs) && !$rs->EOF) {
				$objActuacion= new Actuacion();
				$objActuacion->setId($rs->fields['IDACTUACION']);
				$objActuacion->setNombre($rs->fields['NOMBRE']);
				$objActuacion->setFechaInicio($rs->fields['FECHA_INICIO']);
				$objActuacion->setFechaFin($rs->fields['FECHA_FIN']);
				$objActuacion->setEstado($rs->fields['ESTADO']);
				$objActuacion->setObjetivo($rs->fields['OBJETIVO']);
				$objActuacion->setExpediente($rs->fields['EXPEDIENTE']);
				$objActuacion->setTipoTramite($rs->fields['TIPOTRAMITE']);
				$objActuacion->setObservacion($rs->fields['OBSERVACION']);
				$listaActuaciones[]=$objActuacion;
				$rs->MoveNext ();
			}
			return $listaActuaciones;
		}
 		
 		// método para mostrar todos los actuacions ejecutoriadas en un rango de fechas.
		public function getEjecutoriadas($fini,$fefin){
			
				$rs=$this->db->query("SELECT IDACTUACION, NOMBRE, FECHA_INICIO, FECHA_FIN, FECHA_FIN_REAL, ESTADO, OBJETIVO, EXPEDIENTE, TIPOTRAMITE, OBSERVACION
				FROM ACT_ACTUACION 
				WHERE ESTADO=0 AND FECHA_FIN BETWEEN " . $fini . " AND " . $fefin);
			
			$listaActuaciones=null;
			while (!empty($rs) && !$rs->EOF) {
				$objActuacion= new Actuacion();
				$objActuacion->setId($rs->fields['IDACTUACION']);
				$objActuacion->setNombre($rs->fields['NOMBRE']);
				$objActuacion->setFechaInicio($rs->fields['FECHA_INICIO']);
				$objActuacion->setFechaFin($rs->fields['FECHA_FIN']);
				$objActuacion->setEstado($rs->fields['ESTADO']);
				$objActuacion->setObjetivo($rs->fields['OBJETIVO']);
				$objActuacion->setExpediente($rs->fields['EXPEDIENTE']);
				$objActuacion->setTipoTramite($rs->fields['TIPOTRAMITE']);
				$objActuacion->setObservacion($rs->fields['OBSERVACION']);
				$objActuacion->setFechaFinReal($rs->fields['FECHA_FIN_REAL']);
				$listaActuaciones[]=$objActuacion;
				$rs->MoveNext ();
			}
			return $listaActuaciones;
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
		public function getActuacion($id){
			
			$rs=$this->db->query("SELECT IDACTUACION, NOMBRE, FECHA_INICIO, FECHA_FIN, ESTADO, OBJETIVO, EXPEDIENTE, TIPOTRAMITE, OBSERVACION 
									FROM ACT_ACTUACION
									WHERE IDACTUACION=$id");
			
			$objActuacion= new Actuacion();

			while (!empty($rs) && !$rs->EOF) {
				
				$objActuacion->setId($rs->fields['IDACTUACION']);
				$objActuacion->setNombre($rs->fields['NOMBRE']);
				$objActuacion->setFechaInicio($rs->fields['FECHA_INICIO']);
				$objActuacion->setFechaFin($rs->fields['FECHA_FIN']);
				$objActuacion->setEstado($rs->fields['ESTADO']);
				$objActuacion->setObjetivo($rs->fields['OBJETIVO']);
				$objActuacion->setExpediente($rs->fields['EXPEDIENTE']);
				$objActuacion->setTipoTramite($rs->fields['TIPOTRAMITE']);
				$objActuacion->setObservacion($rs->fields['OBSERVACION']);
				$rs->MoveNext ();
			}

			return $objActuacion;
		}
 
		// método para actualizar un actuacion, recibe como parámetro el actuacion
		public function actualizar($actuacion){
			$sql="UPDATE ACT_ACTUACION SET NOMBRE='".$actuacion->getNombre()."', FECHA_INICIO=".$actuacion->getFechaInicioFormato($this->db).",FECHA_FIN=".$actuacion->getFechaFinFormato($this->db).", 
			ESTADO='".$actuacion->getIdEstado()."', OBJETIVO='".$actuacion->getObjetivo()."',  EXPEDIENTE='".$actuacion->getExpediente()."', TIPOTRAMITE='".$actuacion->getTipoTramite()."',
			OBSERVACION='".$actuacion->getObservacion()."'
			WHERE IDACTUACION=".$actuacion->getId();

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
