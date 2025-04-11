<?php
	class ComiteFuncionario{
		
		private $idComiteFuncionario;
		private $idComite;
		private $idFuncionario;
		 
		function __construct(){}
 		
		public function getIdComiteFuncionario(){
			return $this->idComiteFuncionario;
		}
 
		public function setIdComiteFuncionario($id){
			$this->idComiteFuncionario = $id;
		}
 
		public function getIdComite(){
			return $this->idComite;
		}

		public function setIdComite($id){
			$this->idComite = $id;
		}
 
		public function getIdFuncionario(){
			return $this->idFuncionario;
		}
 
		public function setIdFuncionario($id){
			$this->idFuncionario = $id;
		}
	}
?>