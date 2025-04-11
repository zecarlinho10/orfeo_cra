<?php

$ruta_raiz = "../../";

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once('actividad.php');
require_once('actuacion_actividad.php');
//include_once ('../js/funtionImage.php');

if (! isset($db))
    $db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
//$db->conn->debug=true;

// incluye la clase Db

	class CrudActividades{

		var $db; 
		// constructor de la clase
		public function __construct($db){
			$this->db = $db;
			//$db->conn->debug=true;
		}
 		
		// método para insertar, recibe como parámetro un objeto de tipo Actuacion
		public function insertar(ActuacionActividad $actividad){
			$isql = "SELECT MAX(ID_ACTUACION_ACTIVIDAD)+1 AS MAXIMO FROM ACT_ACTUACION_ACTIVIDAD";
			$rs = $this->db->query ( $isql );
			if (!empty($rs) && !$rs->EOF) {
				$ID_ACTUACION_ACTIVIDAD = $rs->fields ["MAXIMO"];
				$rs->MoveNext ();
			}
			if($ID_ACTUACION_ACTIVIDAD==null){
				$ID_ACTUACION_ACTIVIDAD=1;
			}
			//INSERTAR HISTORICOS
			$van=0;
			$sql="INSERT INTO ACT_ACTUACION_ACTIVIDAD (ID_ACTUACION_ACTIVIDAD, IDACTUACION, IDACTIVIDAD, DESCRIPCION, RADICADO,IDENCARGADO, FECHA_INICIO, FECHA_FINAL)
			VALUES ($ID_ACTUACION_ACTIVIDAD,'".$actividad->getIdActuacion()."','".$actividad->getIdActividad()."','".$actividad->getDescripcion()."','".$actividad->getRadicado()."',
			'".$actividad->getIdEncargado()."',".$actividad->getFechaInicioFormato($this->db).",".$actividad->getFechaFinalFormato($this->db).")";

			if ($this->db->query($sql) == TRUE) {
			    //echo "New Actuación created successfully";
			    $van=1;
			} 

 			return $van;
		}
 
		// método para mostrar todos los actuacions
		public function getActividades(){
			//$listaActividades=[];
			$rs=$this->db->query("SELECT A.IDACTIVIDAD, A.DESCRIPCION, A.ORDEN, A.TIPO_DIAS, A.ESTADO, A.FASE, DESCFASE
								FROM ACT_ACTIVIDAD A LEFT JOIN ACT_FASE F ON A.FASE=F.IDFASE
								WHERE A.ESTADO = 1 
								ORDER BY A.FASE, A.DESCRIPCION");

			while (!empty($rs) && !$rs->EOF) {
				$objActividad= new Actividad();
				$objActividad->setIdActividad($rs->fields['IDACTIVIDAD']);
				$objActividad->setDescripcion($rs->fields['DESCRIPCION']);
				$objActividad->setOrden($rs->fields['ORDEN']);
				$objActividad->setTipoDias($rs->fields['TIPO_DIAS']);
				$objActividad->setEstado($rs->fields['ESTADO']);
				$objActividad->setFase($rs->fields['FASE']);
				$objActividad->setNombreFase($rs->fields['DESCFASE']);
				$listaActividades[]=$objActividad;
				$rs->MoveNext ();
			}
			return $listaActividades;
		}

		// método para enviar el listado de las actividades de una actuacion ordenadas por fecha.
		public function getActividadesOrdenadasxFecha($idActuacion){

			$sql0="SELECT AA.ID_ACTUACION_ACTIVIDAD, AA.IDACTUACION, AA.IDACTIVIDAD, AA.DESCRIPCION, AA.RADICADO, AA.IDENCARGADO, AA.FECHA_INICIO, AA.FECHA_FINAL,
				 A.DESCRIPCION AS DESCACTIVIDAD, USUA_LOGIN, AA.ESTADO, RUTA_ARCHIVO
			FROM ACT_ACTUACION_ACTIVIDAD AA
      			LEFT JOIN USUARIO U ON U.ID=AA.IDENCARGADO,
      			ACT_ACTIVIDAD A
			WHERE AA.IDACTIVIDAD=A.IDACTIVIDAD AND AA.IDACTUACION =".$idActuacion." ORDER BY AA.ID_ACTUACION_ACTIVIDAD";


			$rs0=$this->db->query($sql0);

			//vector actividades
			$vectorActividades = array();
			$cont=0;
			while (!empty($rs0) &&  !$rs0->EOF) {
				if($rs0->fields['FECHA_INICIO'] && $rs0->fields['FECHA_FINAL']){
					$objActividad = new ActuacionActividad();
					$objActividad->setIdActuacionActividad($rs0->fields['ID_ACTUACION_ACTIVIDAD']);
					$objActividad->setIdActividad($rs0->fields['IDACTIVIDAD']);
					$objActividad->setDescActividad($rs0->fields['DESCACTIVIDAD']);
					$objActividad->setUsuaLogin($rs0->fields['USUA_LOGIN']);
					$objActividad->setFechaInicio($rs0->fields['FECHA_INICIO']);
					$objActividad->setFechaFinal($rs0->fields['FECHA_FINAL']);
					$objActividad->setDescripcion($rs0->fields['DESCRIPCION']);
					$objActividad->setEstado($rs0->fields['ESTADO']);
					$objActividad->setRuta($rs0->fields['RUTA_ARCHIVO']);
					$vectorActividades[]=$objActividad;
				}
				else{
					$sql="SELECT ID_ACTUACION_ACTIVIDAD, IDACTUACION, AA.IDACTIVIDAD, AA.DESCRIPCION AS DESACTACTIVIDAD, 
							A.DESCRIPCION AS DESCACTIVIDAD, RADICADO, R.RADI_FECH_RADI, R.FECH_VCMTO, USUA_LOGIN, AA.ESTADO, RUTA_ARCHIVO
							FROM ACT_ACTUACION_ACTIVIDAD AA 
							LEFT JOIN RADICADO R ON AA.RADICADO=R.RADI_NUME_RADI,
							ACT_ACTIVIDAD A, USUARIO U
							WHERE AA.IDACTIVIDAD=A.IDACTIVIDAD AND R.RADI_DEPE_ACTU = U.DEPE_CODI AND R.RADI_USUA_ACTU = U.USUA_CODI AND 
							ID_ACTUACION_ACTIVIDAD ='".$rs0->fields['ID_ACTUACION_ACTIVIDAD']."'";

					$rs=$this->db->query($sql);
					while (!empty($rs) &&  !$rs->EOF) {
						$objActividad = new ActuacionActividad();
						$objActividad->setIdActuacionActividad($rs->fields['ID_ACTUACION_ACTIVIDAD']);
						$objActividad->setIdActividad($rs->fields['IDACTIVIDAD']);
						$objActividad->setDescActividad($rs0->fields['DESCACTIVIDAD']);
						$objActividad->setUsuaLogin($rs->fields['USUA_LOGIN']);
						$objActividad->setRadicado($rs->fields['RADICADO']);

						$objActividad->setFechaInicio($rs->fields['RADI_FECH_RADI']);
						$objActividad->setFechaFinal($rs->fields['FECH_VCMTO']);
						$objActividad->setDescripcion($rs->fields['DESACTACTIVIDAD']);
						$objActividad->setEstado($rs->fields['ESTADO']);
						$objActividad->setRuta($rs->fields['RUTA_ARCHIVO']);

						

						$sql2="SELECT RADI_NUME_RADI, RADI_FECH_RADI FROM RADICADO
								WHERE RADI_NUME_DERI = ".$objActividad->getRadicado();

						$rs2=$this->db->query($sql2);

						$van=0;
						$sprint="<div>";
						while (!empty($rs2) &&  !$rs2->EOF) {
							$van=1;
							//$sprint.="<div>" . $rs2->fields['RADI_NUME_RADI'] . " - " . $rs2->fields['RADI_FECH_RADI'] . "</div>" ;
							$sprint.="<div><a class=\"vinculos\" href=\"#2\" onclick=\"funlinkArchivo('".$rs2->fields['RADI_NUME_RADI']."','../../');\">".$rs2->fields['RADI_NUME_RADI']."</a> - " . $rs2->fields['RADI_FECH_RADI'] . "</div>" ;
							$rs2->MoveNext();
						}
						$sprint.="</div>";

						$objActividad->setRespuestas($sprint);
						$vectorActividades[]=$objActividad;

						$rs->MoveNext ();
					}
				}
				$rs0->MoveNext ();
			}

			for($i=1;!empty($vectorActividades) && $i<count($vectorActividades);$i++){
		        for($j=0;!empty($vectorActividades) &&  $j<count($vectorActividades)-$i;$j++){
		            if(strcmp($vectorActividades[$j]->getFechaInicio(),$vectorActividades[$j+1]->getFechaInicio())>0){
		                $k=$vectorActividades[$j+1];
		                $vectorActividades[$j+1]=$vectorActividades[$j];
		                $vectorActividades[$j]=$k;
		            }
		        }
		    }

			return $vectorActividades;
		}

		// método para mostrar toda la informacion de las actuaciones.
		public function getActividadesTodo($idActuacion){
			$rs=$this->db->query("SELECT IDACTIVIDAD, DESCRIPCION, ORDEN, TIPO_DIAS, ESTADO, FASE
								  FROM ACT_ACTIVIDAD");

			while (!empty($rs) &&  !$rs->EOF) {
				$objActividad= new Actividad();
				$objActividad->setIdActividad($rs->fields['IDACTIVIDAD']);
				$objActividad->setDescripcion($rs->fields['DESCRIPCION']);
				$objActividad->setOrden($rs->fields['ORDEN']);
				$objActividad->setTipoDias($rs->fields['TIPO_DIAS']);
				$objActividad->setEstado($rs->fields['ESTADO']);
				$objActividad->setFase($rs->fields['FASE']);
				$listaActividades[]=$objActividad;
				$rs->MoveNext ();
			}
			return $listaActividades;
		}
 
		// método para eliminar un actuacion, recibe como parámetro el id del actuacion
		public function eliminar($id){
			$eliminar=$this->db->query("DELETE FROM ACT_ACTUACION_ACTIVIDAD WHERE ID_ACTUACION_ACTIVIDAD='".$id."'");

			$van=0;

			if ($eliminar == TRUE) {
			    //echo "Eliminada exitosamente";
			    $van=1;
			} 

			return $van;
		}

		public function actualizaEstado($idactuacionactividad, $valor, $cierra){
			$isql="";
			if($valor==1){
				$isql = "UPDATE ACT_ACTUACION_ACTIVIDAD 
							SET FECHA_FINAL_REAL=(SELECT SYSDATE FROM DUAL), 
								ESTADO='1' 
						 WHERE ID_ACTUACION_ACTIVIDAD=".$idactuacionactividad;
			}
			else{
				$isql = "UPDATE ACT_ACTUACION_ACTIVIDAD 
						 SET FECHA_FINAL_REAL=null, ESTADO='0' 
						 WHERE ID_ACTUACION_ACTIVIDAD=".$idactuacionactividad;
			}
			
			$van=false;
			
			if ($this->db->query($isql) == TRUE) {
				$van=true;
			    //echo "ESTADO actualizado successfully";
			    
			    $sql1="SELECT ID_ACTUACION_ACTIVIDAD,IDACTUACION, IDACTIVIDAD 
						FROM ACT_ACTUACION_ACTIVIDAD
						WHERE ID_ACTUACION_ACTIVIDAD=".$idactuacionactividad;

				$rs=$this->db->query($sql1);

				while (!empty($rs) && !$rs->EOF) {
					$actuacion=$rs->fields['IDACTUACION'];
					$actividad=$rs->fields['IDACTIVIDAD'];
					$rs->MoveNext ();
				}
				if($actividad==129&&$cierra==1){
					$sql2 = "UPDATE ACT_ACTUACION SET ESTADO=0,FECHA_FIN_REAL=(SELECT SYSDATE FROM DUAL) WHERE IDACTUACION=".$actuacion;
					$this->db->query($sql2);
				}
			    $van=true;
			} 

 			return $van;
		}		

		public function actualizaActividad($id,$idAct,$rad,$funcionario,$fini,$ffin,$obs){
			$isql = "UPDATE ACT_ACTUACION_ACTIVIDAD 
					SET IDACTIVIDAD='".$idAct."' , RADICADO='".$rad."' ,IDENCARGADO='".$funcionario."' ,
						FECHA_INICIO='".$fini."' ,FECHA_FINAL='".$ffin."' , DESCRIPCION='".$obs."' 
					WHERE ID_ACTUACION_ACTIVIDAD=".$id;
			$van=false;
			if ($this->db->query($isql) == TRUE) {
			    $van=true;
			} 

 			return $van;
		}
	}
?>
