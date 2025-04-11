<?php

$ruta_raiz = "../../";

session_start();

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once('cobro_actividad.php');
//include_once ('../js/funtionImage.php');

if (! isset($db))
    $db = new ConnectionHandler($ruta_raiz);
//$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

// incluye la clase Db

	class CrudActividades{

		private $db;
		
		// constructor de la clase
		public function __construct($db){
			$this->db = $db;
		}
 		
		// método para insertar, recibe como parámetro un objeto de tipo Actuacion
		public function insertar(CobroActividad $actividad){

			$isql = "SELECT MAX(IDCOBACTIVIDAD)+1 AS MAXIMO FROM COB_ACTIVIDAD";
			$rs = $this->db->query ( $isql );
			if (! $rs->EOF) {
				$ID_COBRO_ACTIVIDAD = $rs->fields ["MAXIMO"];
				$rs->MoveNext ();
			}
			if($ID_COBRO_ACTIVIDAD==null){
				$ID_COBRO_ACTIVIDAD=1;
			}


			//INSERTAR HISTORICOS
			$van=0;
			$sql="INSERT INTO COB_ACTIVIDAD (IDCOBACTIVIDAD, IDCOB, RESOLUCION, VALOR, INTERES, INTERESES_ABONO_CAPITAL, ABONO_INTERESES, FECHA_PRESCRIPCION, VIGENCIA)
			VALUES ($ID_COBRO_ACTIVIDAD,".$actividad->getIdCobro().",'".$actividad->getResolucion()."',".$actividad->getValor().",".$actividad->getInteres().",".$actividad->getInteresAbonoCapital().",".$actividad->getAbonoIntereses().",".$actividad->getFechaPrescripcionFormato($this->db).",'".$actividad->getVigencia()."')";

			if ($this->db->query($sql) == TRUE) {
			    //echo "New Actuación created successfully";
			    $van=1;
			} 
			return $sql;
 			return $van;
		}
 
		// método para enviar el listado de las actividades de una COBRO ordenadas por fecha.
		public function getActividadesOrdenadasxFecha($idCobro){
			
			$sql0="SELECT IDCOBACTIVIDAD, IDCOB, RESOLUCION, VALOR,  
						  INTERESES_ABONO_CAPITAL, ABONO_INTERESES, FECHA_PRESCRIPCION, VIGENCIA, INTERES
				   FROM COB_ACTIVIDAD
				   WHERE IDCOB =".$idCobro." ORDER BY FECHA_PRESCRIPCION";

			$rs0=$this->db->query($sql0);

			//vector actividades
			$vectorActividades = null;
			$cont=0;
			while (! $rs0->EOF) {
				$objActividad = new CobroActividad();
				$objActividad->setIdCobroActividad($rs0->fields['IDCOBACTIVIDAD']);
				$objActividad->setIdCobro($rs0->fields['IDCOB']);
				$objActividad->setResolucion($rs0->fields['RESOLUCION']);
				$objActividad->setValor($rs0->fields['VALOR']);
				$objActividad->setInteresAbonoCapital($rs0->fields['INTERESES_ABONO_CAPITAL']);
				$objActividad->setAbonoIntereses($rs0->fields['ABONO_INTERESES']);
				$objActividad->setFechaPrescripcion($rs0->fields['FECHA_PRESCRIPCION']);
				$objActividad->setVigencia($rs0->fields['VIGENCIA']);
				$objActividad->setInteres($rs0->fields['INTERES']);
				$vectorActividades[]=$objActividad;	

				$rs0->MoveNext ();
			}

			return $vectorActividades;
		}
 
		// método para eliminar un COBRO, recibe como parámetro el id del COBRO
		public function eliminar($id){
			$eliminar=$this->db->query("DELETE FROM COB_ACTIVIDAD WHERE IDCOBACTIVIDAD='".$id."'");

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
		
		public function actualizaActividad($id,$resolucion,$valor,$interes,$abonoC,$abonoI,$fecha,$vigencia){
			$isql = "UPDATE COB_ACTIVIDAD 
					SET RESOLUCION='".$resolucion."' ,VALOR='".$valor."' , INTERES ='".$interes."' ,
						INTERESES_ABONO_CAPITAL='".$abonoC."' ,ABONO_INTERESES='".$abonoI."' , FECHA_PRESCRIPCION='".$fecha."' , VIGENCIA='".$vigencia."' WHERE IDCOBACTIVIDAD=".$id;
			$van=false;
			if ($this->db->query($isql) == TRUE) {
			    $van=true;
			} 

 			return $van;
		}
	}
?>