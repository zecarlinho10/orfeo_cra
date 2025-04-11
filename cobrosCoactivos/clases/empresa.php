<?php

	class Empresa{

		private $id;
		private $nombre;
		private $nit;
		
		function __construct(){}
 		
 		function Empresa($id, $nit, $nom) {
	    	$this->$id=$id;
			$this->$nombre=$nom;
			$this->$nit=$nit;
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
 
 		public function getNit(){
			return $this->nit;
		}
		
		public function setNit($nit){
			$this->nit = $nit;
		}
	}
?>