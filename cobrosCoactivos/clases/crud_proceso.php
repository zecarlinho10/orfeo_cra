<?php

$ruta_raiz = "../../";

session_start();

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once('proceso.php');

if (! isset($db))
    $db = new ConnectionHandler($ruta_raiz);

	class CrudProceso{

		private $db;
		
		// constructor de la clase
		public function __construct($db){
			$this->db = $db;
		}
 		
		// método para insertar, Proceso
		public function insertar(Proceso $proceso){

			$isql = "SELECT MAX(IDCOBPROCESO)+1 AS MAXIMO FROM COB_PROCESOS";
			$rs = $this->db->query ( $isql );
			if (! $rs->EOF) {
				$IDCOBPROCESO = $rs->fields ["MAXIMO"];
				$rs->MoveNext ();
			}
			if($IDCOBPROCESO==null){
				$IDCOBPROCESO=1;
			}


			$van=0;
			$sql="INSERT INTO COB_PROCESOS (IDCOBPROCESO,
											IDCOB,
											FECHA,
											DESCPRICION,
											RADICADO,
											NOTIFICACION,
											ACTO,
											OBSERVACION)
			VALUES ($IDCOBPROCESO,".$proceso->getIdCobro().",".$proceso->getFechaFormato($this->db).",'".$proceso->getDescripcion()."','".$proceso->getRadicado()."',".$proceso->getNotificacion().",".$proceso->getActo().",'".$proceso->getObservacion()."')";

			if ($this->db->query($sql) == TRUE) {
			    //echo "New Actuación created successfully";
			    $van=1;
			} 
			return $sql;
 			return $van;
		}
 
		// método para enviar el listado de las actividades de una COBRO ordenadas por fecha.
		public function getProcesosOrdenadasxFecha($idCobro){
			
			$sql0="SELECT IDCOBPROCESO,
						IDCOB,
						FECHA,
						DESCPRICION,
						RADICADO,
						NOTIFICACION,
						ACTO,
						OBSERVACION
				   FROM COB_PROCESOS
				   WHERE IDCOB =".$idCobro." ORDER BY FECHA";

			$rs0=$this->db->query($sql0);

			$vectorProcesos = null;
			$cont=0;
			while (! $rs0->EOF) {
				$objProceso = new Proceso();
				$objProceso->setIdCobroProceso($rs0->fields['IDCOBPROCESO']);
				$objProceso->setIdCobro($rs0->fields['IDCOB']);
				$objProceso->setFecha($rs0->fields['FECHA']);
				$objProceso->setDescripcion($rs0->fields['DESCPRICION']);
				$objProceso->setRadicado($rs0->fields['RADICADO']);
				$objProceso->setNotificacion($rs0->fields['NOTIFICACION']);
				$objProceso->setActo($rs0->fields['ACTO']);
				$objProceso->setObservacion($rs0->fields['OBSERVACION']);
				$vectorProcesos[]=$objProceso;	

				$rs0->MoveNext ();
			}

			return $vectorProcesos;
		}
 
		// método para eliminar un COBRO, recibe como parámetro el id del COBRO
		public function eliminar($id){
			$eliminar=$this->db->query("DELETE FROM COB_PROCESOS WHERE IDCOBPROCESO='".$id."'");

			$van=0;

			if ($eliminar == TRUE) {
			    //echo "Eliminada exitosamente";
			    $van=1;
			} 

			return $van;
		}

		public function actualizaEstado($id,$val){
			if($val==1){
				$isql = "UPDATE ACT_ACTUACION_ACTIVIDAD SET FECHA_FINAL_REAL=(SELECT SYSDATE FROM DUAL), ESTADO=".$val." WHERE ID_ACTUACION_ACTIVIDAD=".$id;
			}
			else{
				$isql = "UPDATE ACT_ACTUACION_ACTIVIDAD SET FECHA_FINAL_REAL=null, ESTADO=".$val." WHERE ID_ACTUACION_ACTIVIDAD=".$id;
			}
			
			$van=false;
			if ($this->db->query($isql) == TRUE) {
			    //echo "ESTADO actualizado successfully";
			    $sql1="SELECT ID_ACTUACION_ACTIVIDAD,IDACTUACION, IDACTIVIDAD 
						FROM ACT_ACTUACION_ACTIVIDAD
						WHERE ID_ACTUACION_ACTIVIDAD=".$id;

				$rs=$this->db->query($sql1);

				while (! $rs->EOF) {
					$actuacion=$rs->fields['IDACTUACION'];
					$actividad=$rs->fields['IDACTIVIDAD'];
					$rs->MoveNext ();
				}
				if($actividad==23&&$val==1){
					$sql2 = "UPDATE ACT_ACTUACION SET ESTADO=0,FECHA_FIN_REAL=(SELECT SYSDATE FROM DUAL) WHERE IDACTUACION=".$actuacion;
					$this->db->query($sql2);
				}
			    $van=true;
			} 

 			return $van;
		}		
		
		public function actualizaProceso($id,$fecha,$descripcion,$radicado,$notificacion,$acto,$observacion){
			$isql = "UPDATE COB_PROCESOS 
					SET FECHA='".$fecha."' ,DESCPRICION ='".$descripcion."' , RADICADO ='".$radicado."' ,
					NOTIFICACION ='".$notificacion."' , ACTO ='".$acto."' , OBSERVACION ='".$observacion."' 
					WHERE IDCOBPROCESO=".$id;
			$van=false;
			if ($this->db->query($isql) == TRUE) {
			    $van=true;
			} 

 			return $van;
		}
	}
?>