<?php

	class TipoTramite{


		private $idtramite;
		private $nombre;

		function __construct(){}
 		
 		function TipoTramite($id=null, $nombre) {
	    	$this->$idtramite=$id;
			$this->$nombre=$nombre;
	    }


 		public function getId(){
			return $this->idtramite;
		}
 
		public function setId($id){
			$this->idtramite = $id;
		}

		public function getNombre(){
			return $this->nombre;
		}
 
		public function setNombre($nombre){
			$this->nombre = $nombre;
		}
 
	}
?>