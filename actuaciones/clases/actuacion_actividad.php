<?php
	class ActuacionActividad{
		
		private $idActuacionActividad;
		private $idActuacion;
		private $idActividad;
		private $descactividad;
		private $descripcion;
		private $radicado;
		private $idencargado;
		private $usualogin;
		private $fecha_inicio;
		private $fecha_final;
		private $estado;
		private $ruta;
		private $respuestas;
 
		function __construct(){}
 		
		public function getIdActuacionActividad(){
			return $this->idActuacionActividad;
		}
 
		public function setIdActuacionActividad($id){
			$this->idActuacionActividad = $id;
		}

 		public function getIdActuacion(){
			return $this->idActuacion;
		}
 
		public function setIdActuacion($id){
			$this->idActuacion = $id;
		}

		public function getIdActividad(){
			return $this->idActividad;
		}
 
		public function setIdActividad($id){
			$this->idActividad = $id;
		}

		public function getDescActividad(){
			return $this->descactividad;
		}

		public function setDescActividad($desc){
			$this->descactividad = $desc;
		}
 
		public function getDescripcion(){
			return $this->descripcion;
		}

		public function setDescripcion($desc){
			$this->descripcion = $desc;
		}

		public function getUsuaLogin(){
			return $this->usualogin;
		}
 
		public function setUsuaLogin($us){
			$this->usualogin = $us;
		}
 
		public function getRadicado(){
			return $this->radicado;
		}
 
		public function setRadicado($rad){
			$this->radicado = $rad;
		}

		public function getIdEncargado(){
			return $this->idencargado;
		}
 
		public function setIdEncargado($id){
			$this->idencargado = $id;
		}

		public function getFechaInicio(){
			return $this->fecha_inicio;
		}
 
		public function setFechaInicio($fecha){
			$this->fecha_inicio = $fecha;
		}

		public function getFechaFinal(){
			return $this->fecha_final;
		}
 
		public function setFechaFinal($fecha){
			$this->fecha_final = $fecha;
		}

		public function getFechaInicioFormato($db){
			return $db->conn->DBTimeStamp($this->fecha_inicio);
		}

		public function getFechaFinalFormato($db){
			return $db->conn->DBTimeStamp($this->fecha_final);
		}

		public function getEstado(){
			return $this->estado;
		}

		public function setEstado($est){
			$this->estado = $est;
		}

		public function getRuta(){
			return $this->ruta;
		}

		public function setRuta($rut){
			$this->ruta = $rut;
		}

		public function getRespuestas(){
			return $this->respuestas;
		}

		public function setRespuestas($res){
			$this->respuestas = $res;
		}

		static function cmp_obj($a, $b){
	        $al = strtolower($a->fecha_inicio);
	        $bl = strtolower($b->fecha_inicio);
	        if ($al == $bl) {
	            return 0;
	        }
	        return ($al > $bl) ? +1 : -1;
	    }
	}
?>