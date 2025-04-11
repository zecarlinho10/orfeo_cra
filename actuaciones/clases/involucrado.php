<?php
	class Involucrado{
		
		private $idActuacion;
		private $idFuncionario;
		private $fechaInicio;
		private $fechaFin;
		private $idRol;

 
		function __construct(){}
 		

 		public function getIdActuacion(){
			return $this->idActuacion;
		}
 
		public function setIdActuacion($id){
			$this->idActuacion = $id;
		}

		public function getIdFuncionario(){
			return $this->idFuncionario;
		}
 
		public function setIdFuncionario($id){
			$this->idFuncionario = $id;
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

		public function getIdRol(){
			return $this->idRol;
		}
 
		public function setidRol($id){
			$this->idRol = $id;
		}

	}
?>