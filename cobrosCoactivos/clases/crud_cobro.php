	<?php
	
$ruta_raiz = "../../";

//session_start();

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once('cobro.php');

if (! isset($db))
    $db = new ConnectionHandler($ruta_raiz);
//$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

// incluye la clase Db

	class CrudCobro{

		var $db; 
		// constructor de la clase
		public function __construct($db){
			$this->db = $db;
		}
 
		// método para insertar, recibe como parámetro un objeto de tipo Cobro
		public function insertar(Cobro $cobro){
			$isql = "SELECT MAX(IDCOBRO)+1 AS MAXIMO FROM COB_COBRO";
			$rs = $this->db->query ( $isql );
			$idCOBRO=1;
			if (! $rs->EOF) {
				$idCOBRO = $rs->fields ["MAXIMO"];
				$rs->MoveNext ();
			}
			if($idCOBRO==null){
				$idCOBRO=1;
			}
			//INSERTAR HISTORICOS
			$van=0;
			$sql="INSERT INTO COB_COBRO (IDCOBRO, EXPEDIENTE, DEUDOR, FUNCIONARIO)
			VALUES ($idCOBRO,'".$cobro->getExpediente()."','".$cobro->getDeudor($this->db)."','".$cobro->getIDfuncionario()."')";

			if ($this->db->query($sql) == TRUE) {
			    //echo "New Actuación created successfully";
			    $van=1;
			} 

 			return $van;
		}
 
		// método para mostrar los cobros de un funcionario a cargo 
		public function getCobrosXusuario($idUsuario){
			$where="";
			if($idUsuario!=0){
				$where = " WHERE FUNCIONARIO = ".$idUsuario;
			}
			$query="SELECT IDCOBRO, EXPEDIENTE, DEUDOR, FUNCIONARIO, MANDAMIENTOPAGO, VALORMANDAMIENTO, FECHAPRESCRIPCION, OBSERVACION, ESTADO
					FROM COB_COBRO ". $where;
			
			$rs=$this->db->query($query);
			
			$i=0;
			while (! $rs->EOF) {
				$objCobro= new Cobro($this->db);
				$objCobro->setId($rs->fields['IDCOBRO']);
				$objCobro->setExpediente($rs->fields['EXPEDIENTE']);
				$objCobro->setDeudor($rs->fields['DEUDOR']);
				$objCobro->setFuncionario($rs->fields['FUNCIONARIO']);
				$objCobro->setMandamiento($rs->fields['MANDAMIENTOPAGO']);
				$objCobro->setValorMandamiento($rs->fields['VALORMANDAMIENTO']);

				$objCobro->setPrescripcion($rs->fields['FECHAPRESCRIPCION']);
				$objCobro->setObservacion($rs->fields['OBSERVACION']);
				$objCobro->setEstado($rs->fields['ESTADO']);
				
				$listaCobros[$i]=$objCobro;
				$i++;
				$rs->MoveNext ();
			}
			return $listaCobros;
		}

		// método para mostrar los cobros activos 
		public function getCobrosActivos(){
			
			$query="SELECT IDCOBRO, EXPEDIENTE, DEUDOR, FUNCIONARIO, MANDAMIENTOPAGO, VALORMANDAMIENTO, FECHAPRESCRIPCION, OBSERVACION, ESTADO
					FROM COB_COBRO 
					WHERE ESTADO = 1";
			
			$rs=$this->db->query($query);
			
			$i=0;
			while (! $rs->EOF) {
				$objCobro= new Cobro($this->db);
				$objCobro->setId($rs->fields['IDCOBRO']);
				$objCobro->setExpediente($rs->fields['EXPEDIENTE']);
				$objCobro->setDeudor($rs->fields['DEUDOR']);
				$objCobro->setFuncionario($rs->fields['FUNCIONARIO']);
				$objCobro->setMandamiento($rs->fields['MANDAMIENTOPAGO']);
				$objCobro->setValorMandamiento($rs->fields['VALORMANDAMIENTO']);

				$objCobro->setPrescripcion($rs->fields['FECHAPRESCRIPCION']);
				$objCobro->setObservacion($rs->fields['OBSERVACION']);
				$objCobro->setEstado($rs->fields['ESTADO']);
				
				$listaCobros[$i]=$objCobro;
				$i++;
				$rs->MoveNext ();
			}
			return $listaCobros;
		}

		// método para mostrar los cobros activos 
		public function getCobrosXestado($est){
			if($est==-1) $estado = "";
			else $estado = "WHERE ESTADO = '$est'";
			$query="SELECT IDCOBRO, EXPEDIENTE, DEUDOR, FUNCIONARIO, MANDAMIENTOPAGO, VALORMANDAMIENTO, FECHAPRESCRIPCION, OBSERVACION, ESTADO
					FROM COB_COBRO " . $estado;
			
			$rs=$this->db->query($query);
			
			$i=0;
			while (! $rs->EOF) {
				$objCobro= new Cobro($this->db);
				$objCobro->setId($rs->fields['IDCOBRO']);
				$objCobro->setExpediente($rs->fields['EXPEDIENTE']);
				$objCobro->setDeudor($rs->fields['DEUDOR']);
				$objCobro->setFuncionario($rs->fields['FUNCIONARIO']);
				$objCobro->setMandamiento($rs->fields['MANDAMIENTOPAGO']);
				$objCobro->setValorMandamiento($rs->fields['VALORMANDAMIENTO']);
				$objCobro->setPrescripcion($rs->fields['FECHAPRESCRIPCION']);
				$objCobro->setObservacion($rs->fields['OBSERVACION']);
				$objCobro->setEstado($rs->fields['ESTADO']);
				
				$listaCobros[$i]=$objCobro;
				$i++;
				$rs->MoveNext ();
			}
			return $listaCobros;
		}

		// método para retornar un cobro por ID
		public function getCobrosXID($idCobro){
			$query="SELECT IDCOBRO, EXPEDIENTE, DEUDOR, FUNCIONARIO, MANDAMIENTOPAGO, VALORMANDAMIENTO, FECHAPRESCRIPCION, OBSERVACION, ESTADO
					FROM COB_COBRO WHERE IDCOBRO = ".$idCobro;
			
			$rs=$this->db->query($query);

			while (! $rs->EOF) {
				$objCobro= new Cobro($this->db);
				$objCobro->setId($rs->fields['IDCOBRO']);
				$objCobro->setExpediente($rs->fields['EXPEDIENTE']);
				$objCobro->setDeudor($rs->fields['DEUDOR']);
				$objCobro->setFuncionario($rs->fields['FUNCIONARIO']);
				$objCobro->setMandamiento($rs->fields['MANDAMIENTOPAGO']);
				$objCobro->setValorMandamiento($rs->fields['VALORMANDAMIENTO']);

				$objCobro->setPrescripcion($rs->fields['FECHAPRESCRIPCION']);
				$objCobro->setObservacion($rs->fields['OBSERVACION']);
				$objCobro->setEstado($rs->fields['ESTADO']);
				$rs->MoveNext ();
			}
			return $objCobro;
		}

		// método para acumular los cobros coactivos 
		public function acumularCobros($cobro_origen,$cobro_destino){
			
			$cactividades=0;
			$cprocesos=0;

			$sql="SELECT COUNT(1) AS TOTAL FROM COB_ACTIVIDAD WHERE IDCOB = '$cobro_origen'";
			$rs_dep=$this->db->query($sql);

			while(!empty($rs_dep) && !$rs_dep->EOF){ 
				$cactividades=$rs_dep->fields['TOTAL'];
		 		$rs_dep->MoveNext ();
			}

			$sql="SELECT COUNT(1) AS TOTAL FROM COB_PROCESOS WHERE IDCOB = '$cobro_origen'";
			$rs_dep=$this->db->query($sql);

			while(!empty($rs_dep) && !$rs_dep->EOF){ 
				$cprocesos=$rs_dep->fields['TOTAL'];
		 		$rs_dep->MoveNext ();
			}
			$texto="";
			if(!empty($cobro_origen)&&!empty($cobro_destino)){
				$sql="UPDATE COB_ACTIVIDAD
					  SET IDCOB = '$cobro_destino'
					  WHERE IDCOB = '$cobro_origen'";

				$sql2="UPDATE COB_PROCESOS
					  SET IDCOB = '$cobro_destino'
					  WHERE IDCOB = '$cobro_origen'";

				if ($this->db->query($sql) == TRUE && $this->db->query($sql2) == TRUE){
					$texto="Se movieron " . $cactividades . "Actividades  y " . $cprocesos . "Procesos, exitosamente";
				}

				$sql="UPDATE COB_COBRO
					  SET ESTADO = '3'
					  WHERE IDCOBRO = '$cobro_origen'";

				if ($this->db->query($sql) == TRUE){
					$texto.=". Cobro " . $cobro_origen . " Inactivado, exitosamente";
				}
			}
			return $texto;
		}
 	
	 		// método para mostrar todos los actuacions ejecutoriadas en un rango de fechas.
		public function getEjecutoriadas($fini,$fefin){
			
				$rs=$this->db->query("SELECT IDACTUACION, NOMBRE, FECHA_INICIO, FECHA_FIN, FECHA_FIN_REAL, ESTADO, OBJETIVO, EXPEDIENTE, TIPOTRAMITE, OBSERVACION
				FROM ACT_ACTUACION 
				WHERE ESTADO=0 AND FECHA_FIN_REAL BETWEEN " . $fini . " AND " . $fefin);
			
			$listaActuaciones=null;
			while (! $rs->EOF) {
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