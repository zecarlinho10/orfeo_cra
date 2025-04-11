<?php

	class Actuacion{


		private $id;
		private $nombre;
		private $objetivo;
		private $fechaInicio;
		private $fechaFin;
		private $fechaFinReal;
		private $expediente;
		private $estado;
		private $tipoTramite;
		private $observacion;
		
		function __construct(){}
 		
 		function Actuacion($id=null, $nombre, $objetivo, $fechaInicio, $fechaFin, $expediente, $estado, $tipoTramite, $observacion) {
	    	$this->$id=$id;
			$this->$nombre=$nombre;
			$this->$objetivo=$objetivo;
			$this->$fechaInicio=$fechaInicio;
			$this->$fechaFin=$fechaFin;
			$this->$expediente=$expediente;
			$this->$estado=$estado;
			$this->$tipoTramite=$tipoTramite;
			$this->$observacion=$observacion;
	    }


 		public function getId(){
			return $this->id;
		}
 
		public function setId($id){
			$this->id = $id;
		}

		public function getNombre(){
			return $this->nombre;
		}
 
		public function setNombre($nombre){
			$this->nombre = $nombre;
		}
 
 		public function getObjetivo(){
			return $this->objetivo;
		}
		
		public function setObjetivo($objetivo){
			$this->objetivo = $objetivo;
		}

		public function getFechaInicio(){
			return $this->fechaInicio;
		}

		public function getFechaInicioFormato($db){
			return $db->conn->DBTimeStamp($this->fechaInicio);
		}

		public function setFechaInicio($fechaInicio){
			$this->fechaInicio = $fechaInicio;
		}

		 
		public function getFechaFin(){
			return $this->fechaFin;
		}

		public function getFechaFinFormato($db){
			return $db->conn->DBTimeStamp($this->fechaFin);
		}
		
		public function getFechaFinReal(){
			return $this->fechaFinReal;
		}

		public function setFechaFin($fechaFin){
			$this->fechaFin = $fechaFin;
		}

		public function getExpediente(){
			return $this->expediente;
		}
		
		public function setExpediente($expediente){
			$this->expediente = $expediente;
		}

		public function setFechaFinReal($real){
			$this->fechaFinReal = $real;
		}
		
		public function getIdEstado(){
			return $this->estado;
		}

		public function getEstado(){
			//return $this->estado;
			$Nestado="";
			if($this->estado==1){
				$Nestado="Activo";
			}
			else if($this->estado==2){
				$Nestado="Suspendido";
			}
			else if($this->estado==3){
				$Nestado="Finalizado";
			}
			return $Nestado;
		}
		
		public function setEstado($estado){
			$this->estado = $estado;
		}

		public function getTipoTramite(){
			return $this->tipoTramite;
		}

		public function getNobreTipoTramite($db){
			$rs=$db->query("SELECT NOMBRE FROM ACT_TIPO_TRAMITE
								WHERE IDTRAMITE = '".$this->tipoTramite."'");
			$NOMBREP="";
			while (!empty($rs) && !$rs->EOF) {
				$NOMBRE=$rs->fields['NOMBRE'];
				$rs->MoveNext ();
			}
			return $NOMBRE;
		}
		
		public function setTipoTramite($tipoTramite){
			$this->tipoTramite = $tipoTramite;
		}

		public function getObservacion(){
			return $this->observacion;
		}
		
		public function setObservacion($observacion){
			$this->observacion = $observacion;
		}

		public function getNombreExpediente($db){
			$rs=$db->query("SELECT SGD_SEXP_PAREXP1 || SGD_SEXP_PAREXP2 || SGD_SEXP_PAREXP3 || SGD_SEXP_PAREXP4 || SGD_SEXP_PAREXP5 AS NOMBRE_EXP FROM SGD_SEXP_SECEXPEDIENTES
								WHERE SGD_EXP_NUMERO = '".$this->expediente."'");
			$NOMBRE_EXP="";
			while (!empty($rs) && !$rs->EOF) {
				$NOMBRE_EXP=$rs->fields['NOMBRE_EXP'];
				$rs->MoveNext ();
			}
			return $NOMBRE_EXP;
		}

		public function getNombreExperto($db){
			$rs=$db->query("SELECT USUA_NOMB FROM ACT_INVOLUCRADO, USUARIO WHERE IDROL=1 AND IDFUNCIONARIO=ID AND IDACTUACION='".$this->id."'");
			$NOMBRE_EXP="";
			while (!empty($rs) && !$rs->EOF) {
				$USUA_NOMB=$rs->fields['USUA_NOMB'];
				$rs->MoveNext ();
			}
			return $USUA_NOMB;
		}

		public function getAsesorOAJ($db){
			$rs=$db->query("SELECT USUA_NOMB FROM ACT_INVOLUCRADO, USUARIO WHERE DEPE_CODI=12 AND IDROL=2 AND IDFUNCIONARIO=ID AND IDACTUACION='".$this->id."'");
			$NOMBRE_EXP="";
			while (!empty($rs) && !$rs->EOF) {
				$USUA_NOMB=$rs->fields['USUA_NOMB'];
				$rs->MoveNext ();
			}
			return $USUA_NOMB;
		}

		public function getAsesorSR($db){
			$rs=$db->query("SELECT USUA_NOMB FROM ACT_INVOLUCRADO, USUARIO WHERE DEPE_CODI=30 AND IDROL=2 AND IDFUNCIONARIO=ID AND IDACTUACION='".$this->id."'");
			$NOMBRE_EXP="";
			while (!empty($rs) && !$rs->EOF) {
				$USUA_NOMB=$rs->fields['USUA_NOMB'];
				$rs->MoveNext ();
			}
			return $USUA_NOMB;
		}

		public function getEquipoDeTrabajo($db){
			$rs=$db->query("SELECT U.USUA_NOMB
							FROM USUARIO U, ACT_INVOLUCRADO I
							WHERE U.ID=I.IDFUNCIONARIO AND IDROL=3 AND I.IDACTUACION='".$this->id."' ");
			$USUA_NOMB="";
			while (!empty($rs) && !$rs->EOF) {
				$USUA_NOMB.=$rs->fields['USUA_NOMB']." - ";
				$rs->MoveNext ();
			}
			return $USUA_NOMB;
		}

		public function getAdministrador($db){
			$rs=$db->query("SELECT USUA_NOMB FROM ACT_INVOLUCRADO, USUARIO WHERE IDROL=4 AND IDFUNCIONARIO=ID AND IDACTUACION='".$this->id."'");
			$NOMBRE_EXP="";
			while (!empty($rs) && !$rs->EOF) {
				$USUA_NOMB=$rs->fields['USUA_NOMB'];
				$rs->MoveNext ();
			}
			return $USUA_NOMB;
		}

		public function getPrestador($db){
			$rs=$db->query("SELECT SGD_OEM_OEMPRESA 
							FROM SGD_OEM_OEMPRESAS, ACT_PRESTADOR
							WHERE IDOEM=SGD_OEM_CODIGO AND IDACTUACION='".$this->id."' ");
			$empresa="";
			while (!empty($rs) && !$rs->EOF) {
				$empresa.=$rs->fields['SGD_OEM_OEMPRESA']." - ";
				$rs->MoveNext ();
			}
			return $empresa;
		}
	}
?>
