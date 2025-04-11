<?php
include_once (realpath(dirname(__FILE__) . "/../") . "/include/db/Connection/Connection.php");
class Direcciones {
	protected $db;
	public function __construct() {
		$this->db = Connection::getCurrentInstance ();
		$this->db->debug=true;
	}
	public function saveDireccion($data, $dirNivel, $nueva = false) {
		$datosTipo = $this->selectTargetDoc ( $data ["tipo_emp_us"], $data ["sgd_ciu_codigo"] );
		$ADODB_FORCE_TYPE = 1;
		$record = array (); 
		$salida=null; 
		if ($nueva) {
			$nextval = $this->db->conn->nextId ( "sec_dir_drecciones" );
			$record ['SGD_DIR_CODIGO'] = $nextval;
			$salida =$nextval;
		}
		if (is_array ( $datosTipo )) {
			foreach ( $datosTipo ["target"] as $key => $value )
				$record [$key] = $value;
			$record ['SGD_TRD_CODIGO'] = $datosTipo ["tipo"];
		}
		$record ['SGD_DIR_NOMREMDES'] = $data ["grbNombresUs"];
		$record ['SGD_DIR_DOC'] = $data ["cc_documento_us"];
		$record ['MUNI_CODI'] = $data ["muni_us"];
		$record ['DPTO_CODI'] = $data ["dpto_us"];
		$record ['ID_PAIS'] = $data ["idpais"];
		$record ['ID_CONT'] = $data ["idcont"];
		$record ['RADI_NUME_RADI'] = $data ["nurad"];
		$record ['SGD_SEC_CODIGO'] = 0;
		$record ['SGD_DIR_DIRECCION'] = $data ["direccion_us"];
		$record ['SGD_DIR_TELEFONO'] = trim ( $data ["telefono_us"] );
		$record ['SGD_DIR_MAIL'] = $data ["mail_us"];
		$record ['SGD_DIR_TIPO'] = $dirNivel;
	        $record ['SGD_DIR_NOMBRE']=$data["sgd_dir_nombre"];
	        $record ['SGD_DIR_APELLIDO']=$data["sgd_dir_apellido"];
		if(!empty($data["otro_us"]))
			$record ['SGD_DIR_NOMBRE'] = $data["otro_us"];
		try{
		$insertSQL = $this->db->conn->Replace ( "SGD_DIR_DRECCIONES", $record, array (
				'RADI_NUME_RADI',
				'SGD_DIR_TIPO' 
		), $autoquote = true );
		}catch (Exception $e){
			throw new OrfeoException ( "No se ha podido actualizar la informaci\u00F3n de SGD_DIR_DRECCIONES ",$e );
	      }
		return $salida;
	}
	public function saveDireccionCopias($numRadicado, $data, $nueva = false) {
		$isql = "select count(1) as anexos from sgd_dir_drecciones
			where radi_nume_radi=$numRadicado
			and sgd_dir_tipo>=700 ";
		$rsg = $this->db->query ( $isql );
		if (! empty ( $rsg ) && ! $rsg->EOF) {
			$num_anexos = $rsg->fields ["ANEXOS"];
		}
		
		$num_anexos = $num_anexos + 1;
		$str_num_anexos = substr ( "00" . $num_anexos, - 2 );
		$sgd_dir_tipo = "7" . $str_num_anexos;
		if (empty ( $data ["grbNombresUs"] ))
			$data ["grbNombresUs"] = $data ["nombre_us"] . " " . $data ["prim_apel_us"] . " " . $data ["seg_apel_us"];
		$data ["nurad"] = $numRadicado;
		$this->saveDireccion ( $data, $sgd_dir_tipo );
	}
	private function selectTargetDoc($tipo, $documento) {
		$datosDireccionTipo = array ();
		switch ($tipo) {
			case 0 :
				$datosDireccionTipo ["target"] ["SGD_CIU_CODIGO"] = $documento;
				$datosDireccionTipo ["tipo"] = 1;
				break;
			case 1 :
				$datosDireccionTipo ["target"] ["SGD_ESP_CODIGO"] = $documento;
				$datosDireccionTipo ["tipo"] = 3;
				break;
			case 2 :
				$datosDireccionTipo ["target"] ["SGD_OEM_CODIGO"] = $documento;
				$datosDireccionTipo ["tipo"] = 2;
				break;
			case 6 :
				$datosDireccionTipo ["target"] ["SGD_DOC_FUN"] = $documento;
				$datosDireccionTipo ["tipo"] = 4;
				break;
			default :
				$datosDireccionTipo ["target"] ["SGD_CIU_CODIGO"] = $documento;
				$datosDireccionTipo ["tipo"] = 1;
				break;
		}
		
		return $datosDireccionTipo;
	}
	public function getDireccionesRadicado($radicado) {
		$salida = array ();
		$consulta = "SELECT *
					FROM
						SGD_DIR_DRECCIONES d WHERE
						d.RADI_NUME_RADI={$radicado}
						AND D.SGD_DIR_TIPO < 4";
		$rs = $this->db->query ( $consulta );
		
		 while(!empty($rs) && !$rs->EOF){ 
		 	$cod=$rs->fields["SGD_DIR_TIPO"];
			$salida[$cod]=$rs->fields; 
			$rs->MoveNext ();
		}
		
		return $salida;
	}
	public function getDireccionesCopias() {
		$consulta = "SELECT *
		FROM
		SGD_DIR_DRECCIONES WHERE
		RADI_NUME_RADI={$numRadicado}
		AND D.SGD_DIR_TIPO >= 700";
		$rs = $this->db->query ( $consulta );
		return $rs->fields;
	}
	public function getDB(){
	    return $this->db;
	}
}
?>
