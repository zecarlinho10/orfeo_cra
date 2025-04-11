<?php

	class Funcionario{


		private $id;
		private $nombre;
		private $login;
		private $depe_codi;
		private $usua_codi;
		
		function __construct(){}
 		
 		function Funcionario($id, $nom, $log, $depe, $usua) {
	    	$this->$id=$id;
			$this->$nombre=$nom;
			$this->$login=$log;
			$this->$depe_codi=$depe;
			$this->$usua_codi=$usua;
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
 
 		public function getLogin(){
			return $this->login;
		}
		
		public function setLogin($login){
			$this->login = $login;
		}

		public function getDepeCodi(){
			return $this->depe_codi;
		}

		 
		public function setDepeCodi($depe_codi){
			$this->depe_codi = $depe_codi;
		}

		public function getUsuaCodi(){
			return $this->usua_codi;
		}
		
		public function setUsuaCodi($usua_codi){
			$this->usua_codi = $usua_codi;
		}
	}
?>