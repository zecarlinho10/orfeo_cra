<?php

$ruta_raiz = "../../";

//session_start();

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once('cobro.php');
require_once('funcionario.php');
require_once('empresa.php');

if (! isset($db))
    $db = new ConnectionHandler($ruta_raiz);


	class cobro{


		private $id;
		private $expediente;
		private $deudor;
		private $funcionario;
		private $id_funcionario;
		private $mandamiento;
		private $valor_mandamiento;
		private $prescripcion;
		private $observacion;
		private $estado;

		private $db; 
		// constructor de la clase
		public function __construct($db){
			$this->db = $db;
		}

 		public function getId(){
			return $this->id;
		}
 
		public function setId($id){
			$this->id = $id;
		}

		public function getExpediente(){
			return $this->expediente;
		}
 
		public function setExpediente($exp){
			$this->expediente = $exp;
		}
 
 		public function getDeudor(){
			return $this->deudor;
		}
		
		public function setDeudor($deu){
			$this->deudor = $deu;
		}

		public function setFuncionario($fun){
			$this->id_funcionario = $fun;
			$rs=$this->db->query("SELECT ID, USUA_NOMB, USUA_LOGIN, DEPE_CODI, USUA_CODI
							FROM USUARIO
							WHERE ID = ".$fun );
			$this->funcionario=new Funcionario();
			while (! $rs->EOF) {
				$ID=$rs->fields['ID'];
				$USUA_NOMB=$rs->fields['USUA_NOMB'];
				$USUA_LOGIN=$rs->fields['USUA_LOGIN'];
				$DEPE_CODI=$rs->fields['DEPE_CODI'];
				$USUA_CODI=$rs->fields['USUA_CODI'];
				$this->funcionario->setId($ID);
				$this->funcionario->setNombre($USUA_NOMB);
				$this->funcionario->setLogin($USUA_LOGIN);
				$this->funcionario->setDepeCodi($DEPE_CODI);
				$this->funcionario->setUsuaCodi($USUA_CODI);
				$rs->MoveNext ();
			}
		}

		public function getIDfuncionario(){
			return $this->id_funcionario;
		}

		public function getFuncionario(){
			return $this->funcionario;
		}

		public function getEmpresa(){
			$rs=$this->db->query("SELECT NOMBRE_DE_LA_EMPRESA, NIT_DE_LA_EMPRESA, IDENTIFICADOR_EMPRESA
							FROM BODEGA_EMPRESAS
							WHERE IDENTIFICADOR_EMPRESA = " . $this->deudor);
			
			$id="";
			$nomb="";
			$nit="";
			$empresa = new Empresa();
			while (! $rs->EOF) {
				$id=$rs->fields['IDENTIFICADOR_EMPRESA'];
				$nomb=$rs->fields['NOMBRE_DE_LA_EMPRESA'];
				$nit=$rs->fields['NIT_DE_LA_EMPRESA'];
				$rs->MoveNext ();
				$empresa->setId($id);
				$empresa->setNit($nit);
				$empresa->setNombre($nomb);
			}
			return $empresa;
		}

		public function getMandamiento(){
			return $this->mandamiento;
		}
 
		public function setMandamiento($man){
			$this->mandamiento = $man;
		}

		public function getValorMandamiento(){
			return $this->valor_mandamiento;
		}
 
		public function setValorMandamiento($val){
			$this->valor_mandamiento = $val;
		}

		public function getPrescripcion(){
			return $this->prescripcion;
		}
 
		public function setPrescripcion($pres){
			$this->prescripcion = $pres;
		}

		public function getObservacion(){
			return $this->observacion;
		}
 
		public function setObservacion($obs){
			$this->observacion = $obs;
		}

		public function getEstado(){
			return $this->estado;
		}
 
		public function setEstado($est){
			$this->estado = $est;
		}

		public function actualizaCobro(){
			$fecha = $this->db->conn->DBTimeStamp($this->prescripcion);
			$sql="UPDATE COB_COBRO 
				  SET EXPEDIENTE = '".$this->expediente ."', 
					  DEUDOR = ".$this->deudor .", 
					  FUNCIONARIO = ".$this->id_funcionario .", 
					  MANDAMIENTOPAGO = '".$this->mandamiento ."' , 
					  VALORMANDAMIENTO =  '".$this->valor_mandamiento."', 
					  FECHAPRESCRIPCION =  ".$fecha .", 
					  OBSERVACION =  '".$this->observacion ."', 
					  ESTADO = ".$this->estado ." 
				  WHERE IDCOBRO = ".$this->id;
			$rs=$this->db->query($sql);
			return $sql;
		}

	}
?>