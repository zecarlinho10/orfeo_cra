<?php

$ruta_raiz = "../../";

session_start();

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once('tipo_tramite.php');

if (! isset($db))
    $db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

// incluye la clase Db

	class CrudTipoTramite{

		var $db; 
		// constructor de la clase
		public function __construct($db){
			$this->db = $db;
		}
 
 
		// método para mostrar todos los tipos tramites
		public function getTiposTramite(){
 			$isql = "SELECT IDTRAMITE, NOMBRE FROM ACT_TIPO_TRAMITE";
			$rs = $this->db->query ( $isql );
			while (!empty($rs) && !$rs->EOF) {
				$objTipoTramite= new TipoTramite();
				$objTipoTramite->setId($rs->fields['IDTRAMITE']);
				$objTipoTramite->setNombre($rs->fields['NOMBRE']);
				$listaTipoTramites[]=$objTipoTramite;
				$rs->MoveNext ();
			}
			return $listaTipoTramites;
		}
 
		// método para eliminar un tipos tramite, recibe como parámetro el id del tipos tramite
		public function eliminar($id){
			$eliminar=$this->db->query("DELETE FROM ACT_tipos tramite WHERE IDtipos tramite=id");

			$van=0;

			if ($this->db->query($sql) == TRUE) {
			    //echo "New Actuación created successfully";
			    $van=1;
			} 

			return $van;
		}
 
		// método para actualizar un tipos tramite, recibe como parámetro el tipos tramite
		/*
		public function actualizar($tipos tramite){
			$sql="UPDATE ACT_tipos tramite SET NOMBRE='$tipos tramite->getNombre()', FECHA_INICIO='$tipos tramite->getFechaInicio()',FECHA_FIN='$tipos tramite->getFechaFin()', 
			ESTADO='$tipos tramite->getEstado()', OBJETIVO='$tipos tramite->getObjetivo()',  EXPEDIENTE='$tipos tramite->getExpediente()', TIPOTRAMITE='$tipos tramite->getTipoTramite()'
			WHERE IDtipos tramite=$tipos tramite->getId()";

			$rs=$this->db->query($sql);
			$van=0;

			if ($this->db->query($sql) == TRUE) {
			    //echo "New Actuación update successfully";
			    $van=1;
			} 

			return $van;
		}
		*/
	}
?>
