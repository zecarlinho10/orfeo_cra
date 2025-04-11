<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

	
	class ClaseSerializable1 implements Serializable {
		    private $data;
		   
		    public function __construct($data) {
		        $this->data = $data;
		    }
		   
		    public function getData() {
		        return $this->data;
		    }
		   
		    public function serialize() {
		        return serialize($this->data);
		    }
		   
		    public function unserialize($data) {
		        $this->data = unserialize($data);
		    }
	}

	class Documento extends ClaseSerializable1{
		private $id;
		private $nombre;
		private $tipologia;
		private $fecha_declaracion;
		private $fecha_incorporacion;
		private $valor_huella;
		private $funcion_resumen;
		private $orden;
		private $inicio;
		private $fin;
		private $formato;
		private $tamano;
		private $origen;
		
		
		function __construct($id, $nombre, $tipologia, $fecha_declaracion, $fecha_incorporacion,$valor_huella, $funcion_resumen, $orden, $inicio, $fin, $formato, $tamano, $origen, $data){
			parent::__construct($data);
 			$this->id=$id;
			$this->nombre=$nombre;
			$this->tipologia=$tipologia;
			$this->fecha_declaracion=$fecha_declaracion;
			$this->fecha_incorporacion=$fecha_incorporacion;
			$this->valor_huella=$valor_huella;
			$this->funcion_resumen=$funcion_resumen;
			$this->orden=$orden;
			$this->inicio=$inicio;
			$this->fin=$fin;
			$this->formato=$formato;
			$this->tamano=$tamano;
			$this->origen=$origen;
	    }

	    public function serialize() {
		    return serialize(
		        array(
		            'id' => $this->id,
	                'nombre' => $this->nombre,
	                'tipologia' => $this->tipologia,
	                'fecha_declaracion' => $this->fecha_declaracion,
	                'fecha_incorporacion' => $this->fecha_incorporacion,
	                'valor_huella' => $this->valor_huella,
					'funcion_resumen' => $this->funcion_resumen,
					'orden' => $this->orden,
	                'inicio' => $this->inicio,
	                'fin' => $this->fin,
	                'formato' => $this->formato,
	                'tamano' => $this->tamano,
	                'origen' => $this->origen,
	                'parentData' => parent::serialize()         
	            )
		    );
		}
		   
		public function unserialize($data) {
		    $data = unserialize($data);
		       
		    $this->id = $data['id'];
		    $this->nombre = $data['nombre'];
		    $this->tipologia = $data['tipologia'];
		    $this->fecha_declaracion = $data['fecha_declaracion'];
		    $this->fecha_incorporacion = $data['fecha_incorporacion'];
		    $this->valor_huella = $data['valor_huella'];
			$this->funcion_resumen = $data['funcion_resumen'];
			$this->orden = $data['orden'];
		    $this->inicio = $data['inicio'];
		    $this->fin = $data['fin'];
		    $this->formato = $data['formato'];
		    $this->tamano = $data['tamano'];
		    $this->origen = $data['origen'];
		    parent::unserialize($data['parentData']);
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
 
 		public function getTipologia(){
			return $this->tipologia;
		}
		
		public function setTipologia($tipologia){
			$this->tipologia = $tipologia;
		}

		public function getFechaDeclaracion(){
			return $this->fecha_declaracion;
		}

		public function setFechaDeclaracion($fecha_declaracion){
			$this->fecha_declaracion = $fecha_declaracion;
		}
		
		public function getFechaIncorporacion(){
			return $this->fecha_incorporacion;
		}

		public function setFechaIncorporacion($fecha_incorporacion){
			$this->fecha_incorporacion = $fecha_incorporacion;
		}

		
		public function getValorHuella(){
			return $this->valor_huella;
		}


		public function setValorHuella($valor_huella){
			return $this->valor_huella=$valor_huella;
		}

		public function getFuncionResumen(){
			return $this->funcion_resumen;
		}

		public function setFuncionResumen($funcion_resumen){
			$this->funcion_resumen = $funcion_resumen;
		}
		
		public function getOrden(){
			return $this->orden;
		}
		
		public function setOrden($orden){
			$this->orden = $orden;
		}

		public function getInicio(){
			return $this->inicio;
		}

		public function setInicio($inicio){
			$this->inicio = $inicio;
		}

		public function getFin(){
			return $this->fin;
		}

		public function setFin($fin){
			$this->fin = $fin;
		}

		public function getFormato(){
			return $this->formato;
		}

		public function setFormato($formato){
			$this->formato = $formato;
		}

		public function getTamano(){
			return $this->tamano;
		}

		public function setTamano($tamano){
			$this->tamano = $tamano;
		}

		public function getOrigen(){
			return $this->origen;
		}

		public function setOrigen($origen){
			$this->origen = $origen;
		}
	}
?>