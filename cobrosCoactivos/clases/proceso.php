<?php

$ruta_raiz = "../../";

session_start();

include_once "$ruta_raiz/include/db/ConnectionHandler.php";

if (! isset($db))
    $db = new ConnectionHandler($ruta_raiz);

	class Proceso{
		
		private $idCobroProceso;
		private $idCobro;
		private $fecha;
		private $descripcion;
		private $radicado;
		private $notificacion;
		private $acto;
		private $observacion;
		
		function __construct(){}
 		
		public function getIdCobroProceso(){
			return $this->idCobroProceso;
		}
 
		public function setIdCobroProceso($id){
			$this->idCobroProceso = $id;
		}

 		public function getIdCobro(){
			return $this->idCobro;
		}
 
		public function setIdCobro($id){
			$this->idCobro = $id;
		}

		public function getFecha(){
			return $this->fecha;
		}
 
		public function setFecha($fec){
			$this->fecha = $fec;
		}

		public function getDescripcion(){
			return $this->descripcion;
		}

		public function setDescripcion($des){
			$this->descripcion = $des;
		}

		public function getRadicado(){
			return $this->radicado;
		}

		public function setRadicado($rad){
			$this->radicado = $rad;
		}
 
		public function getNotificacion(){
			return $this->notificacion;
		}

		public function setNotificacion($not){
			$this->notificacion = $not;
		}

		public function getActo(){
			return $this->acto;
		}
 
		public function setActo($act){
			$this->acto = $act;
		}
 
		public function getObservacion(){
			return $this->observacion;
		}
 
		public function setObservacion($ob){
			$this->observacion = $ob;
		}

		public function getFechaFormato($db){
			return $db->conn->DBTimeStamp($this->fecha);
		}

		/*
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
	    */
	}
?>