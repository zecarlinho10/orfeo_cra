<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$ruta_raiz = "../";

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once('documento.php');

require_once "$ruta_raiz/atencion/CargarArchivo.php";
require_once "$ruta_raiz/clasesComunes/BuscarDestinatario.php";
include_once "$ruta_raiz/atencion/RadicacionAtencion.php";
require_once "$ruta_raiz/atencion/AtencionTipos.php";
include_once "$ruta_raiz/clasesComunes/Usuario.php";
include_once "$ruta_raiz/clasesComunes/AtencionCiudadano.php";
include_once "$ruta_raiz/class_control/anexo.php";

    class ClaseSerializable implements Serializable {
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

	class ExpedienteSerializable extends ClaseSerializable{

		private $numero;
		private $depe_codi;
		private $fecha;
		private $doc_responsable;
		private $nombre;
		private $listaDocumentos=array();
		private $xml;
		private $radicadosPlantilla;
		
		function __construct($numero, $depe_codi, $fecha, $doc_responsable, $nombre, $data){
			parent::__construct($data);
			$this->numero=$numero;
			$this->depe_codi=$depe_codi;
			$this->fecha=$fecha;
			$this->doc_responsable=$doc_responsable;
			$this->nombre=$nombre;
		}

	    public function serialize() {
		    return serialize(
		        array(
		            'numero' => $this->numero,
	                'depe_codi' => $this->depe_codi,
	                'fecha' => $this->fecha,
	                'doc_responsable' => $this->doc_responsable,
	                'nombre' => $this->nombre,
	                'listaDocumentos' => $this->listaDocumentos,
					'xml' => $this->xml,
	                'parentData' => parent::serialize()         
	            )
		    );
		}
		   
		public function unserialize($data) {
		    $data = unserialize($data);
		       
		    $this->numero = $data['numero'];
		    $this->depe_codi = $data['depe_codi'];
		    $this->fecha = $data['fecha'];
		    $this->doc_responsable = $data['doc_responsable'];
		    $this->nombre = $data['nombre'];
		    $this->listaDocumentos = $data['listaDocumentos'];
			$this->xml = $data['xml'];
		    parent::unserialize($data['parentData']);
		}

	    public function getData() {
	        return $this->numero;
	    }

	    public function prueba() {
        	var_dump(get_object_vars($this));
    	}

    	public function getListaDocumentos(){
        	return $this->listaDocumentos;
    	}

    	public function generaCuerpo() {
	    	
    		$this->radicadosPlantilla = "";

    		//return(sizeof($this->listaDocumentos));
		    if (!$this->listaDocumentos){
	           $this->radicadosPlantilla .= "No existen radicados para anular en este rango de fechas.";
	        }

		    $cont=66;
		    foreach ($this->listaDocumentos as $id => $noRadicado) {
		        
		    	$this->radicadosPlantilla.="<table:table-row table:style-name=\"TableRow65\">";$cont++;
		    	$this->radicadosPlantilla.="<table:table-cell table:style-name=\"TableCell65\">";$cont++;
		    	$this->radicadosPlantilla.="<text:p text:style-name=\"P65\">".$noRadicado->getId()."</text:p></table:table-cell>";$cont++;
		    	$this->radicadosPlantilla.="<table:table-cell table:style-name=\"TableCell65\">";$cont++;
		    	$this->radicadosPlantilla.="<text:p text:style-name=\"P65\">".$noRadicado->getNombre()."</text:p></table:table-cell>";$cont++;
		    	$this->radicadosPlantilla.="<table:table-cell table:style-name=\"TableCell65\">";$cont++;
		    	$this->radicadosPlantilla.="<text:p text:style-name=\"P65\">".$noRadicado->getTipologia()."</text:p></table:table-cell>";$cont++;
		    	$this->radicadosPlantilla.="<table:table-cell table:style-name=\"TableCell65\">";$cont++;
		    	$this->radicadosPlantilla.="<text:p text:style-name=\"P65\">".$noRadicado->getFechaDeclaracion()."</text:p></table:table-cell>";$cont++;
		    	$this->radicadosPlantilla.="<table:table-cell table:style-name=\"TableCell65\">";$cont++;
		    	$this->radicadosPlantilla.="<text:p text:style-name=\"P65\">".$noRadicado->getFechaIncorporacion()."</text:p></table:table-cell>";$cont++;
		    	$this->radicadosPlantilla.="<table:table-cell table:style-name=\"TableCell65\">";$cont++;
		    	$this->radicadosPlantilla.="<text:p text:style-name=\"P65\">".$noRadicado->getValorHuella()."</text:p></table:table-cell>";$cont++;
		    	$this->radicadosPlantilla.="<table:table-cell table:style-name=\"TableCell65\">";$cont++;
		    	$this->radicadosPlantilla.="<text:p text:style-name=\"P65\">".$noRadicado->getFuncionResumen()."</text:p></table:table-cell>";$cont++;
		    	$this->radicadosPlantilla.="<table:table-cell table:style-name=\"TableCell65\">";$cont++;
		    	$this->radicadosPlantilla.="<text:p text:style-name=\"P65\">".$noRadicado->getOrden()."</text:p></table:table-cell>";$cont++;
		    	$this->radicadosPlantilla.="<table:table-cell table:style-name=\"TableCell65\">";$cont++;
		    	$this->radicadosPlantilla.="<text:p text:style-name=\"P65\">".$noRadicado->getInicio()."</text:p></table:table-cell>";$cont++;
		    	$this->radicadosPlantilla.="<table:table-cell table:style-name=\"TableCell65\">";$cont++;
		    	$this->radicadosPlantilla.="<text:p text:style-name=\"P65\">".$noRadicado->getFin()."</text:p></table:table-cell>";$cont++;
		    	$this->radicadosPlantilla.="<table:table-cell table:style-name=\"TableCell65\">";$cont++;
		    	$this->radicadosPlantilla.="<text:p text:style-name=\"P65\">".$noRadicado->getFormato()."</text:p></table:table-cell>";$cont++;
		    	$this->radicadosPlantilla.="<table:table-cell table:style-name=\"TableCell65\">";$cont++;
		    	$this->radicadosPlantilla.="<text:p text:style-name=\"P65\">".$noRadicado->getTamano()."</text:p></table:table-cell>";$cont++;
		    	$this->radicadosPlantilla.="<table:table-cell table:style-name=\"TableCell65\">";$cont++;
		    	$this->radicadosPlantilla.="<text:p text:style-name=\"P65\">".$noRadicado->getOrigen()."</text:p></table:table-cell>";$cont++;
		    	$this->radicadosPlantilla.="</table:table-row>";
		    }
		    
		    $this->radicadosPlantilla.="</table:table><text:p text:style-name=\"".$cont."\"/><text:p text:style-name=\"Normal\">";$cont++;
		    $this->radicadosPlantilla.="<text:span text:style-name=\"T".$cont."\">";$cont++;
		}

	    public function addDocument($id, $nombre, $tipologia, $fecha_declaracion, $fecha_incorporacion,$valor_huella, $funcion_resumen, $orden, $inicio, $fin, $formato, $tamano, $origen){
	    	$objDocumento= new Documento($id, $nombre, $tipologia, $fecha_declaracion, $fecha_incorporacion,$valor_huella, $funcion_resumen, $orden, $inicio, $fin, $formato, $tamano, $origen, array());
			
			$this->listaDocumentos[]=$objDocumento;
	    }

 		public function getNumero(){
			return $this->numero;
		}
 
		public function setNumero($numero){
			$this->numero = $numero;
		}

		public function getNombre(){
			return $this->nombre;
		}
 
		public function setNombre($nombre){
			$this->nombre = $nombre;
		}
 
 		public function getDepeCodi(){
			return $this->depe_codi;
		}
		
		public function setDepeCodi($depe_codi){
			$this->depe_codi = $depe_codi;
		}

		public function getFecha(){
			return $this->fecha;
		}

		public function setFecha($fecha){
			$this->fecha = $fecha;
		}
		
		public function getDocResponsable(){
			return $this->doc_responsable;
		}

		public function setDocResponsable($doc_responsable){
			$this->doc_responsable = $doc_responsable;
		}

		public function getRadicadosPlantilla(){
			return $this->radicadosPlantilla;
		}

		public function crearXMLFoliado($radicado){
			$ruta_raiz = "../";
			$db = new ConnectionHandler($ruta_raiz);
		    $anexo = new Anexo($db);
			$mensaje="";
			$archivo = fopen($ruta_raiz."/bodega/" . substr($radicado, 0, 4) . "/" . intval(substr($radicado, 4, 3) ). "/docs/1".$radicado."_00002.xml", "w+b");    // Abrir el archivo, creándolo si no existe
		    if( $archivo == false )
		      $mensaje= "Error al crear el archivo";
		    else{
				$title_size = 1;

				$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
				$xml = "<expediente numero=\"".$this->numero."\" nombre=\"".$this->nombre."\" dependencia=\"".$this->depe_codi."\">";
				foreach ($this->listaDocumentos as $id => $documento) {
					$xml .= "<documento id=\"".$documento->getId()."\">";
					$xml .= "<Nombre_del_documento>".$documento->getNombre()."</Nombre_del_documento>";
					$xml .= "<Tipología_documental>".$documento->getTipologia()."</Tipología_documental>";
					$xml .= "<Fecha_de_declaración_de_documento_archivo>".$documento->getFechaDeclaracion()."</Fecha_de_declaración_de_documento_archivo>";
					$xml .= "<Fecha_incorporación_al_expediente>".$documento->getFechaIncorporacion()."</Fecha_incorporación_al_expediente>";
					$xml .= "<Valor_Huella>".$documento->getValorHuella()."</Valor_Huella>";
					$xml .= "<Función_Resumen>".$documento->getFuncionResumen()."</Función_Resumen>";
					$xml .= "<Orden_documento_expediente>".$documento->getOrden()."</Orden_documento_expediente>";
					$xml .= "<Pagina_Inicio>".$documento->getInicio()."</Pagina_Inicio>";
					$xml .= "<Pagina_Fin>".$documento->getFin()."</Pagina_Fin>";
					$xml .= "<formato>".$documento->getFormato()."</formato>";
					$xml .= "<Origen>".$documento->getOrigen()."</Origen>";
					$xml .= "</documento>";
				}

				$xml .= "</expediente>\n";

				fwrite($archivo, $xml);
		        
		        
		        // Fuerza a que se escriban los datos pendientes en el buffer:
		         fflush($archivo);
		        $mensaje= "XML creado exitosamente";
		    }
		    fclose($archivo);   // Cerrar el archivo
		    $anexo->anex_radi_nume = $radicado;
            $anexo->anex_nomb_archivo = $ruta_raiz."/bodega/". substr($radicado, 0, 4) . "/" . intval(substr($radicado, 4, 3) ). "/docs/1".$radicado."00002.xml";
            $anexo->anexoExtension = "xml";
            $anexo->anex_solo_lect = "'S'";
            $anexo->anex_creador = "'RWEB'";
            $anexo->anex_desc = "Archivo XML de foliado electronico";
            $anexo->anex_borrado = "'N'";
            $anexo->anex_salida = "0";
            $anexo->anex_depe_creador = intval(substr($radiI, 4, 3) );
            $anexo->sgd_tpr_codigo = 0;
            $anexo->usuaDoc = "998877";
            $anexo->anex_estado = 1;
            $anexo->sgd_exp_numero = "";
            $anexo->anexarFilaRadicado();
		    return $mensaje;
		}

		public function radicaActa(){
			$ruta_raiz = "../";
			session_start();
			$dependencia = $_SESSION["dependencia"];
			$usua_doc = $_SESSION["usua_doc"];
			$codusuario = $_SESSION["codusuario"];
			
			$arreglo = array(
		        "cont" => "1",
		        "pais" => "170",
		        "depto" => "11",
		        "mncpio" => "1",
		        "tipoPersona" => "4",
		        "documento" => "$usua_doc",
		    );
		    
		    $usuarioRadica["DEPE_CODI"]=$dependencia;
		    $usuarioRadica["USUA_DOC"]=$usua_doc;
		    $usuarioRadica["USUA_CODI"]=$codusuario;
		    $usuarioRadica["USUA_LOGIN"]=$_SESSION["usua_nomb"];
		    
		    $des = new BuscarDestinatario();
		    $destinatario = $des->generaDestinatario($arreglo);
		    $radicacionAtencion = new RadicacionAtencion();
		    $tencionCiudadano = new AtencionCiudadano();
		    $usuario = new Usuario();

		    $db = new ConnectionHandler($ruta_raiz);
		    $anexo = new Anexo($db);

		    $usr = $radicacionAtencion->getInfoUsuario($usuarioRadica, $usuarioRadica);
		    $documento["mrec_cod"] = 3;

		    $documento["asunto"] = "ACTA DE INDICE Y FOLIADO ELECTRONICO";
		    
		    $atencion["mrec"] = $documento["mrec_cod"];
		    $atencion["destinatario"]=$destinatario;
		    $atencion["tipoPersona"]=0;

		    $anoActual = date("Y");
     
		    $fecha = date("d-m-Y");
		    $fecha_hoy_corto = date("d-m-Y");
		    include "$ruta_raiz/class_control/class_gen.php";
		    $date = date("m/d/Y");
		    $b = new CLASS_GEN();
		    $fecha_hoy = $b->traducefecha($date);

		    $variables["ano_actual"]=$anoActual;
		    $variables["radicados"]="listado de documentos del expediente";
		    $variables["fecha_corto"]=$fecha_hoy;
		    $variables["tipo_radicado"]="trd";
		    $variables["asunto"]="Acta de indice y foliado de exedientes";
		    $variables["expediente"]=$this->getNumero();
		    $variables["fecha_indice"]=$this->getFecha();
		    
		    $atencion["componente"] = "2";
		    $atencion["formulario"]="Acta de anulacion de radicados";
		    
		    $variables["radicados"]=$this->getRadicadosPlantilla();
		    $numeroRadicado = $radicacionAtencion->radicarActaFoliado($usr, $variables, "plantilla_foliado.odt", $dependencia, $codusuario);
		    
		    $atencion["radicado"] = $numeroRadicado["radicado"];
		    $numR=$numeroRadicado["radicado"];
		    
		    $this->crearXMLFoliado($numR);

		    $anexo->anex_radi_nume = $numR;
            $anexo->anex_nomb_archivo = "/BodegaCopia/tmp/datos.xml";
            $anexo->anexoExtension = "xml";
            $anexo->anex_solo_lect = "'S'";
            $anexo->anex_creador = "'1'";
            $anexo->anex_desc = "Archivo XML de foliado electronico";
            $anexo->anex_borrado = "'N'";
            $anexo->anex_salida = "0";
            $anexo->anex_depe_creador = "1";
            $anexo->sgd_tpr_codigo = 0;
            $anexo->usuaDoc = $usua_doc;
            $anexo->anex_estado = 1;
            $anexo->sgd_exp_numero = "";
            $anexo->anexarFilaRadicado();

		    //$tencionCiudadano->crearAtencion($atencion);
		    $_SESSION["idFormulario"] = sha1(microtime(true) . mt_rand(10000, 90000)); 
		   
		    $radInExpStyle = "class='fa fa-folder-open'";
		   
		    return "Acta generada exitosamente: <div><small><a href='../verradicado.php?verrad=$numR&nomcarpeta=$this->numero#tabs-d' target='mainFrame'>$numR</a></small></div>";
		    //return $numR;
		}
	}


?>