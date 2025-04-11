<?php
	class Actividad{
		
		private $idActividad;
		private $descripcion;
		private $orden;
		private $tipodias;
		private $estado;
		private $fase;
		private $nomFase;

		function __construct(){}
 		
		public function getIdActividad(){
			return $this->idActividad;
		}
 
		public function setIdActividad($id){
			$this->idActividad = $id;
		}
 
		public function getDescripcion(){
			return $this->descripcion;
		}

		public function setDescripcion($desc){
			$this->descripcion = $desc;
		}
 
		public function getOrden(){
			return $this->orden;
		}
 
		public function setOrden($or){
			$this->orden = $or;
		}

		public function getTipoDias(){
			return $this->orden;
		}
 
		public function setTipoDias($td){
			$this->tipodias = $td;
		}

		public function getEstado(){
			return $this->estado;
		}
 
		public function setEstado($est){
			$this->estado = $est;
		}

		public function getFase(){
			return $this->fase;
		}
 
		public function setFase($fas){
			$this->fase = $fas;
		}

		public function getNombreFase(){
			return $this->nomFase;
		}
 
		public function setNombreFase($fas){
			$this->nomFase = $fas;
		}
		
	}
?>