<?php


	class CobroActividad{
		
		private $idCobroActividad;
		private $idCobro;
		private $resolucion;
		private $valor;
		private $interes;
		private $interesAbonoCapital;
		private $abonoIntereses;
		private $fechaPrescripcion;
		private $vigencia;

		function __construct(){}
 		
		public function getIdCobroActividad(){
			return $this->idCobroActividad;
		}
 
		public function setIdCobroActividad($id){
			$this->idCobroActividad = $id;
		}

 		public function getIdCobro(){
			return $this->idCobro;
		}
 
		public function setIdCobro($id){
			$this->idCobro = $id;
		}

		public function getResolucion(){
			return $this->resolucion;
		}
 
		public function setResolucion($res){
			$this->resolucion = $res;
		}

		public function getValor(){
			return $this->valor;
		}

		public function setValor($val){
			$this->valor = $val;
		}

		public function getInteres(){
			return $this->interes;
		}

		public function setInteres($in){
			$this->interes = $in;
		}
 
		public function getInteresAbonoCapital(){
			return $this->interesAbonoCapital;
		}

		public function setInteresAbonoCapital($in){
			$this->interesAbonoCapital = $in;
		}

		public function getAbonoIntereses(){
			return $this->abonoIntereses;
		}
 
		public function setAbonoIntereses($ab){
			$this->abonoIntereses = $ab;
		}
 
		public function getFechaPrescripcion(){
			return $this->fechaPrescripcion;
		}
 
		public function setFechaPrescripcion($fec){
			$this->fechaPrescripcion = $fec;
		}

		public function getVigencia(){
			return $this->vigencia;
		}
 
		public function setVigencia($vig){
			$this->vigencia = $vig;
		}


		public function getFechaPrescripcionFormato($db){
			return $db->conn->DBTimeStamp($this->fechaPrescripcion);
		}

		public function getFechaFinalFormato($db){
			return $db->conn->DBTimeStamp($this->fechaPrescripcion);
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