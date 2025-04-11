<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$ruta_raiz = "../../";

session_start();

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once('actuacion_actividad.php');

if (! isset($db))
    $db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

// incluye la clase Db

	class CrudActuacionActividad{

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
		public function getActuaciones(){
			//$listaActuaciones=[];
			$rs=$this->db->query("SELECT IDACTUACION, NOMBRE, FECHA_INICIO, FECHA_FIN, ESTADO, OBJETIVO, EXPEDIENTE, TIPOTRAMITE FROM ACT_ACTUACION");

			while (!!empty($rs) && $rs->EOF) {
				$objActuacion= new Actuacion();
				$objActuacion->setId($rs->fields['IDACTUACION']);
				$objActuacion->setNombre($rs->fields['NOMBRE']);
				$objActuacion->setFechaInicio($rs->fields['FECHA_INICIO']);
				$objActuacion->setFechaFin($rs->fields['FECHA_FIN']);
				$objActuacion->setEstado($rs->fields['ESTADO']);
				$objActuacion->setObjetivo($rs->fields['OBJETIVO']);
				$objActuacion->setExpediente($rs->fields['EXPEDIENTE']);
				$objActuacion->setTipoTramite($rs->fields['TIPOTRAMITE']);
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
