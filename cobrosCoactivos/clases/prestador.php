<?php
	class Prestador{
		
		private $idActuacion;
		private $idOEM;
		private $fechaInicio;
		private $fechaFin;

 
		function __construct(){}
 		
 		public function Prestador($idA, $idOEM, $fechaInicio, $fechaFin) {
	    	$this->$idActuacion=$idA;
	    	$this->$idOEM=$idOEM;
			$this->$fechaInicio=$fechaInicio;
			$this->$fechaFin=$fechaFin;
	   }


 		public function getIdActuacion(){
			return $this->idActuacion;
		}
 
		public function setIdActuacion($id){
			$this->idActuacion = $id;
		}

		public function getIdOEM(){
			return $this->idOEM;
		}
 
		public function setIdOEM($id){
			$this->idOEM = $id;
		}
 
		public function getFechaInicio(){
			return $this->fechaInicio;
		}

		public function setFechaInicio($fechaInicio){
			$this->fechaInicio = $fechaInicio;
		}
 
		public function getFechaFin(){
			return $this->fechaFin;
		}
		
		public function setFechaFin($fechaFin){
			$this->fechaFin = $fechaFin;
		}

	}
?>