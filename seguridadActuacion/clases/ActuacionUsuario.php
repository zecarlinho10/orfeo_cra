<?php
	class ActuacionUsuario{
		
		private $idActuacionUsuario;
		private $expediente;
		private $idUsuario;
		private $fecha;
 
		function __construct(){}
 		

 		public function getIdActuacionUsuario(){
			return $this->idActuacionUsuario;
		}
 
		public function setIdActuacionUsuario($id){
			$this->idActuacionUsuario = $id;
		}

		public function getExpediente(){
			return $this->expediente;
		}
 
		public function setExpediente($exp){
			$this->expediente = $exp;
		}
 
		public function getIdUsuario(){
			return $this->idUsuario;
		}

		public function setIdUsuario($id){
			$this->idUsuario = $id;
		}
 
		public function getFecha(){
			return $this->fecha;
		}
		
		public function setFecha($fecha){
			$this->fecha = $fecha;
		}
	}
?>