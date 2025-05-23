<?php
/**
 * Esta clase realiza todos las transacciones relacionadas con los Flujos de Trabajo.
 * Para esto se crean funciones que encuentra cuales son las aristas adyacentes 
 * a un nodo determinado.
 * Ademas actualiza y cosulta el estado actual de un expediente.
 * @author Jairo Losada 
 * @version 3.5.1 2006
 * 
 */
class Flujo{
	var $codProceso;
	var $db;
	var $nodosSig; // Array de Nodos siguientes
	var $aristasNombresSig; // Array de Nombres de aristas Siguientes
	var $aristaTipoDoc;
	var $aristaSRD;
	var $aristaSBRD;   // Indica la Serie y subserie a la cual esta fijada dicha arista.
	var $aristaAutomatico;  // Array Indica si el Flujo por dicha arista es automatico (0) o manual (1). 
	var $aristasSig;  // Array Indica si el Flujo por dicha arista es automatico (0) o manual (1). 
		
	// VARIABLES DE USUARIO QUE INTANCIO LA LASE
	var $usuaDoc; // Documento de Usuario que esta realizando el proceso
	var $usuaCodi; // Codigo de usuario que realiza el proceso
	var $depeCodi; // Dependencia en la cual se encuentra el usuario que realiza el proceso.
	
	var $codNodoProx; // Siguiente Nodo.
	var $codArista; // Codigo de Atista 
	var $descArista;  // Descripcion arista
	var $frmNomvre;  // Nombre del Formulario en la Arista.
	var $frmLink; // Nombre del link al formulario en la Arista.
	var $frmLinkSelect; // Nombre del link al listado de formularios seleccionados en la arista.
	var $frmLinkSql; // Nombre del link al listado de formularios seleccionados en la arista.
	
	/**
	 * Constructor.  La clase se instancia con el codigo del proceso y la conexion a la base de datos.
	 *
	 * @param object $db   // Objeto con la conexion.
	 * @param object $codProceso  // Codigo de Proceso del Flujo documental.
	 * @return Flujos
	 */
	/*function Flujo($db,$codProceso){
		$this->codProceso = $codProceso;
		$this->db = $db;
	}
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $db
	 * @param unknown_type $codProceso
	 * @param unknown_type $usuaDoc
	 * @return Flujo
	 */
	function __construct($db,$codProceso, $usuaDoc){
	  $this->codProceso = $codProceso;
	  $this->db = $db;

	  $query = "SELECT * FROM USUARIO WHERE USUA_DOC='$usuaDoc'";
	  $rs = $this->db->conn->query($query);
	  $this->usuaDoc = $usuaDoc;
	  if($rs)
	  {
	    $this->usuaCodi = $rs->fields["USUA_CODI"];
	    $this->depeCodi = $rs->fields["DEPE_CODI"];
	  }
	}
	/**
	 * 
	 * Enter description here...
	 *
	 * @param number $aristaActual  //Codigo de la arista actual
	 * @return array   // retorna el arreglo de aristas posibles.
	 */
	function aristasSiguiente($estadoActualExp){
		if($estadoActualExp==1 or !$estadoActualExp){
			$query = "SELECT SGD_FEXP_CODIGO FROM SGD_FEXP_FLUJOEXPEDIENTES 
			WHERE SGD_FEXP_ORDEN=1 AND SGD_PEXP_CODIGO=$this->codProceso";
			$rs = $this->db->conn->query($query);
			$estadoActualExp = $rs->fields["SGD_FEXP_CODIGO"];
			$estadoActualExp = empty($estadoActualExp)? 0 : $estadoActualExp;
		}
		$query = "SELECT * FROM SGD_FARS_FARISTAS 
		WHERE SGD_FEXP_CODIGOINI=$estadoActualExp 
		ORDER BY SGD_FEXP_CODIGOINI,SGD_FEXP_CODIGOFIN";	
		$rs = $this->db->conn->query($query);
		$aristasArray = "";
		if($rs){
			while(!$rs->EOF){
				$aristasArray[] = $rs->fields["SGD_FARS_CODIGO"];
				$nodosSig[] = $rs->fields["SGD_FEXP_CODIGOFIN"];
				$aristasNombresSig[] = $rs->fields["SGD_FARS_DESC"];
				$aristaTRad[] = $rs->fields["SGD_TRAD_CODIGO"];
				$aristaTDoc[] = $rs->fields["SGD_TPR_CODIGO"];
				$aristaSRD[] = $rs->fields["SGD_SRD_CODIGO"];
				$aristaSBRD[] = $rs->fields["SGD_SBRD_CODIGO"];
				$aristaAutomatico[] = $rs->fields["SGD_FARS_AUTOMATICO"];
				$aristaFrmNombre[] = $rs->fields["SGD_FARS_FRMNOMBRE"];
				$aristaFrmLink[] = $rs->fields["SGD_FARS_FRMLINK"];
				$rs->MoveNext();
			}
			$this->nodosSig = $nodosSig;
			$this->aristasNombresSig = $aristasNombresSig;
			$this->aristaAutomatico = $aristaAutomatico;
			$this->aristaSBRD = $aristaSBRD;
			$this->aristaSRD = $aristaSRD;
			$this->aristaTDoc = $aristaTDoc;
			$this->aristaTRad = $aristaTRad;
		}
		return $aristasArray;
	}
	
	/**
	 * METODO devuelve los nodos de un proceso
	 *
	 * @param number $tProc  Codigo del Proceso
	 * @return array retorna los nodos pertenecientes a un proceso.
	 */
	function getNodos($tProc){
		$query = "SELECT * 
					FROM SGD_FEXP_FLUJOEXPEDIENTES 
					WHERE SGD_PEXP_CODIGO=$tProc 
					ORDER BY SGD_FEXP_ORDEN";
		//$this->db->conn->debug=true;
		$rs = $this->db->conn->query($query);
		
		$nodos = array();
		if($rs)
		{
		 $i=0;
		  while(!$rs->EOF){
		    $iNodo =  $rs->fields["SGD_FEXP_CODIGO"];
				$nodos[$iNodo]["DESCRIP"] = $rs->fields["SGD_FEXP_DESCRIP"];
				$nodos[$iNodo]["CODIGO"] = $rs->fields["SGD_FEXP_CODIGO"];
				$nodos[$iNodo]["POSLEFT"] = $rs->fields["SGD_FLD_POSLEFT"];
				$nodos[$iNodo]["POSTOP"] = $rs->fields["SGD_FLD_POSTOP"];
				$i++;
				$rs->MoveNext();
			}
		}
		return $nodos;
	}	
	/**
	 * METODO devuelve el nombre de un nodo
	 *
	 * @param number $nodo
	 * @param number $tProc
	 * @return varchar retorna el nombre del nodo buscado.
	 */
	function getNombreNodo($nodo,$tProc){
	$query = "SELECT * FROM SGD_FROL_ROLUS rus, SGD_FROLA_FROLARISTA fra,
			  SGD_FARS_FARISTAS fars
			 where
			   rus.sgd_frol_codigo=fra.sgd_frol_codigo
			   and fars.SGD_FARS_CODIGO=fra.SGD_FARS_CODIGO
   			   and rus.usua_doc='".$this->usuaDoc."'";
		$query = "SELECT * 
					FROM SGD_FEXP_FLUJOEXPEDIENTES 
					WHERE SGD_FEXP_CODIGO=$nodo";// and SGD_PEXP_CODIGO=$tProc";
				//Se pone comentario a esta parte porque ese valor no estaba llegando, además no es necesario para obtener el nombre de la etapa
				//porque el codigo de la etapa es único.
		$rs = $this->db->conn->query($query);
		if($rs)
		{
				$nodoNombre = $rs->fields["SGD_FEXP_DESCRIP"];
		}
		return $nodoNombre;
	}	
	function aristasPrevia($aristaActual){
		return $aristasArray;
	}
	/**
	 * Encuentra el nodo en el cual se encuentra un Expediente.
	 *
	 * @param varcahr $expNume Numero de expediente que busca.
	 * @nodoActual number  retorna el numero del nodo en el que se encuentra el expediente buscado.
	 * @rs object RecordSet con consulta realizada.
	 * @return number  retorna el numero del nodo en el que se encuentra el expediente buscado.
	 */
	function actualNodoExpediente($expNume){
		$query = "SELECT * FROM SGD_SEXP_SECEXPEDIENTES WHERE SGD_EXP_NUMERO='$expNume' and SGD_PEXP_CODIGO = " . $this->codProceso;
		//$this->db->conn->debug = true;
		$rs = $this->db->conn->query($query);
		if($rs){
			$nodoActual = $rs->fields["SGD_FEXP_CODIGO"];
		}
		if(!$nodoActual){
			$query = "SELECT * FROM SGD_FEXP_FLUJOEXPEDIENTES 
					   where sgd_pexp_codigo = ".$this->codProceso."
					  ORDER BY SGD_FEXP_ORDEN";
			$rs = $this->db->conn->query($query);				
			$nodoActual = $rs->fields["SGD_FEXP_CODIGO"];
			
		}
		if($nodoActual==0) $nodoActual=1;
		return $nodoActual;
	}
	/**
	 * Metodo que cambia el estado (Nodo) de un expediente. 
	 * Ademas genera un historico en el expediente.
	 *
	 * @param varchar $expNume  Numero de expediente a modificar
	 * @param number $radiNume  Numero de radicado en el cual se realiza el cambio de estado
	 * @param number $nodoNuevo Nodo al cual pasa el proceso
	 * @param number $nodoActual Nodo Actual del Proceso
	 * @param number $aristaCodi Arista utilizada para realizar el cambio de Estado o Nodo.
	 * @param number $fldAutomatico Indica con 1 si el flujo fue automatico.
	 * @param number $tProc Codigo del Proceso a utilizar
	 * @param number $usuaDoc Documento del usuario que realiza el movimiento
	 * @param number $usuaCodi Codigo del usuario
	 * @param Varchar $observa Observacion escrita por el usuario para el cambio de estado
   * @param string $proc proceso del al cual se reailza el cambio 
	 * @return number  retorna o si fallo y 1 si ejecuto bien la operacion.
	 */
	function cambioNodoExpediente($expNume,$radiNume,$nodoNuevo,$aristaCodi,$fldAutomatico,$observa,$proc = null){
		$recordSet2["SGD_FEXP_CODIGO"] = $nodoNuevo;		
		$recordWhere2["SGD_EXP_NUMERO"] = "'".$expNume."'";					
		$recordWhere2["SGD_PEXP_CODIGO"] = $proc;
		$rs=$this->db->update("SGD_SEXP_SECEXPEDIENTES", $recordSet2,$recordWhere2);	
		$realizado = '0';
		if($rs){
			//$observa = "*Cambio Estado*  ";
			$flujo_nombre = $this->getNombreNodo($nodoNuevo,$this->codProceso);
			$observa .= " ($flujo_nombre)";
			$arrayRad = array();
			$arrayRad[]=$verradEntra;
			$codusdp = str_pad($dependencia, 3, "0", STR_PAD_LEFT).str_pad($codusuario, 3, "0", STR_PAD_LEFT);
			$record["SGD_FEXP_CODIGO"] = $nodoNuevo;
			$record["SGD_EXP_FECHFLUJOANT"] = $this->db->conn->OffsetDate(0,$this->db->conn->sysTimeStamp);
			$record["SGD_HFLD_FECH"] = $this->db->conn->OffsetDate(0,$this->db->conn->sysTimeStamp);
			$record["SGD_EXP_NUMERO"] = "'".$expNume."'";
			$record["RADI_NUME_RADI"] = $radiNume;
			//$record["USUA_DOC"] = "'".$this->usuaDoc."'";
			$record["DEPE_CODI"] = $this->depeCodi;
			$record["USUA_CODI"] = $this->usuaCodi;
			$record["SGD_TTR_CODIGO"] = 50;
			$record["SGD_HFLD_OBSERVA"] = "'".$observa."'";
			if(!$aristaCodi) $aristaCodi = "0";
			$record["SGD_FARS_CODIGO"] = $aristaCodi;
			$record["SGD_HFLD_AUTOMATICO"] = $fldAutomatico;
			$insertSQL = $this->db->insert("SGD_HFLD_HISTFLUJODOC", $record, "true");
			$realizado='1';
		}
		return $realizado;
	}
		function getAristaTipoDoc($tipoDoc, $codProceso,$codSerie,$codSbrd){

            $query = "SELECT
                        *
                      FROM
                        SGD_FARS_FARISTAS
                      WHERE
                        SGD_TPR_CODIGO=$tipoDoc
                        AND SGD_PEXP_CODIGO=$codProceso
                        AND SGD_SRD_CODIGO=$codSerie
                        AND SGD_SBRD_CODIGO=$codSbrd
                        AND SGD_FARS_AUTOMATICO=1
                        ORDER BY SGD_FEXP_CODIGOINI,SGD_FEXP_CODIGOFIN";

            $rs = $this->db->conn->query($query);

            if($rs){
                $this->codArista = $rs->fields["SGD_FARS_CODIGO"];
                $this->descArista = $rs->fields["SGD_FARS_DESC"];
                $this->codNodoSiguiente =  $rs->fields["SGD_FEXP_CODIGOFIN"];
                $this->frmNombre = $rs->fields["SGD_FEXP_FRMNOMBRE"];
                $this->frmLink = $rs->fields["SGD_FEXP_FRMLINK"];
            }

            return $this->codArista;
	    }
		
	function getArista($codProceso, $codFld){
	  if($codFld==0) $coFld=1; 
		$query = "SELECT * FROM SGD_FARS_FARISTAS 
		WHERE 
		SGD_PEXP_CODIGO=$codProceso
		AND SGD_FEXP_CODIGOINI=$codFld
		ORDER BY SGD_FEXP_CODIGOINI,SGD_FEXP_CODIGOFIN";	
		//$this->db->conn->debug = true;
		$rs = $this->db->conn->query($query);
		$aristasArray = "";
		if($rs){
		 $iA = 0;
		 while(!$rs->EOF){
			
			$this->codArista = $rs->fields["SGD_FARS_CODIGO"];
			$this->descArista = $rs->fields["SGD_FARS_DESC"];
			$nodoInicio =  $rs->fields["SGD_FEXP_CODIGOFIN"];
			$this->codNodoSiguiente =  $rs->fields["SGD_FEXP_CODIGOFIN"];
			$this->frmNombre = $rs->fields["SGD_FARS_FRMNOMBRE"];
			$this->frmLink = $rs->fields["SGD_FARS_FRMLINK"];
			$this->frmLinkSelect = $rs->fields["SGD_FARS_FRMLINKSELECT"];
			$this->frmLinkSql = $rs->fields["SGD_FARS_FRMSQL"];
			$aristas[$iA]["CODIGO"] = $this->codArista; 
			$aristas[$iA]["DESCRIP"] = $descArista;
			$aristas[$iA]["NODO_INICIO"] = $nodoInicio;
			$aristas[$iA]["NODO_FIN"] = $this->codNodoSiguiente;
			$aristas[$iA]["FRM_NOMBRE"] = $this->frmNombre;
			$aristas[$iA]["FRM_LINK"] = $this->frmLink;
			$aristas[$iA]["FRM_LINKSELECT"] = $this->frmLinkSelect;
			$aristas[$iA]["FRM_SQL"] = $this->frmLinkSql;
			$iA++;
			$rs->MoveNext();
		 }
		}else{
		 return -1;
		}
		$this->aristasSig = $aristas;
		return $this->codArista;
	}
 /**
   * Metodo que trae las aristas de un proceso.
 	 * @param number codProceso Codigo de proceso. 
	 * @return array Retorna todas las aristas pertenecientes a un proceso indicado en $codProceso.
	 */

	function getAristas($codProceso){
	  if($codFld==0) $coFld=1; 
		$query = "SELECT * FROM SGD_FARS_FARISTAS 
		WHERE 
		SGD_PEXP_CODIGO=$codProceso
		ORDER BY SGD_FEXP_CODIGOINI,SGD_FEXP_CODIGOFIN";	
		
		$rs = $this->db->conn->query($query);
		$aristas = array();
		if($rs){
		$iA=0;
	   while(!$rs->EOF){		
			$codigoArista = $rs->fields["SGD_FARS_CODIGO"];
			$descripArista = $rs->fields["SGD_FARS_DESC"];
			$nodoInicio =  $rs->fields["SGD_FEXP_CODIGOINI"];
			$nodoFin =  $rs->fields["SGD_FEXP_CODIGOFIN"];
			$frmNombre = $rs->fields["SGD_FARS_FRMNOMBRE"];
			$frmLink = $rs->fields["SGD_FARS_FRMLINK"];
			$frmLinkSelect = $rs->fields["SGD_FARS_FRMLINKSELECT"];
			$frmLinkSql = $rs->fields["SGD_FARS_FRMSQL"];
			$aristas[$iA]["CODIGO"] = $codigoArista; 
			$aristas[$iA]["DESCRIP"] = $descripArista;
			$aristas[$iA]["NODO_INICIO"] = $nodoInicio;
			$aristas[$iA]["NODO_FIN"] = $nodoFin;
			$aristas[$iA]["FRM_NOMBRE"] = $frmNombre;
			$aristas[$iA]["FRM_LINK"] = $frmLink;
			$aristas[$iA]["FRM_LINKSELECT"] = $frmLinkSelect;
			$aristas[$iA]["FRM_SQL"] = $frmLinkSql;
			$iA++;
			$rs->MoveNext();
			}
		}
		return $aristas;
	}	

	 /**
   * Metodo que trae las aristas de un proceso.
 	 * @param number codProceso Codigo de proceso. 
	 * @return array Retorna todas las aristas pertenecientes a un proceso indicado en $codProceso.
	 */

	function getAristasProximas($codProceso, $nodo=0){
	if($codFld==0) $coFld=1; 
		$query = "SELECT * FROM SGD_FARS_FARISTAS 
		WHERE 
		SGD_PEXP_CODIGO=$codProceso
		AND SGD_FEXP_CODIGOINI=$nodo
		ORDER BY SGD_FEXP_CODIGOINI,SGD_FEXP_CODIGOFIN";	
		
		$rs = $this->db->conn->query($query);
		$aristas = array();
		if($rs){
		$iA=0;
	   while(!$rs->EOF){		
			$codigoArista = $rs->fields["SGD_FARS_CODIGO"];
			$descripArista = $rs->fields["SGD_FARS_DESC"];
			$nodoInicio =  $rs->fields["SGD_FEXP_CODIGOINI"];
			$nodoFin =  $rs->fields["SGD_FEXP_CODIGOFIN"];
			$frmNombre = $rs->fields["SGD_FARS_FRMNOMBRE"];
			$frmLink = $rs->fields["SGD_FARS_FRMLINK"];
			$frmLinkSelect = $rs->fields["SGD_FARS_FRMLINKSELECT"];
			$frmLinkSql = $rs->fields["SGD_FARS_FRMSQL"];
			$aristas[$iA]["CODIGO"] = $codigoArista; 
			$aristas[$iA]["DESCRIP"] = $descripArista;
			$aristas[$iA]["NODO_INICIO"] = $nodoInicio;
			$aristas[$iA]["NODO_FIN"] = $nodoFin;
			$aristas[$iA]["FRM_NOMBRE"] = $frmNombre;
			$aristas[$iA]["FRM_LINK"] = $frmLink;
			$aristas[$iA]["FRM_LINKSELECT"] = $frmLinkSelect;
			$aristas[$iA]["FRM_SQL"] = $frmLinkSql;
			$iA++;
			$rs->MoveNext();
			}
		}
		return $aristas;
}
		function getMenuProximaArista($tipoDoc, $codProceso,$codSerie,$codSbrd,$tRad,$name,$default=null,$atributos=''){

            $query = "
                SELECT
                  *
                FROM
                  SGD_FARS_FARISTAS
                WHERE
                  SGD_TPR_CODIGO=$tipoDoc
                  AND SGD_PEXP_CODIGO=$codProceso
                  AND SGD_SRD_CODIGO=$codSerie
                  AND SGD_SBRD_CODIGO=$codSbrd
                  AND SGD_TRAD_CODIGO=$tRad
                  AND SGD_FARS_AUTOMATICO=1
                ORDER BY SGD_FEXP_CODIGOINI,SGD_FEXP_CODIGOFIN";

            $rs = $this->db->conn->query($query);

            $menu  = "<select name='$name' $atributos>";
            $menu .= "<option value='0'> - Ningun Estado -</option>";
            if($rs){
                while(!$rs->EOF){
                    $this->codArista = $rs->fields["SGD_FARS_CODIGO"];
                    $this->descArista = $rs->fields["SGD_FARS_DESC"];
                    $this->codNodoSiguiente =  $rs->fields["SGD_FEXP_CODIGOFIN"];
                    $valor = "".$this->codNodoSiguiente."-".$this->codArista ."";
                    if($default==$valor) $datoss = " selected "; else $datoss = " ";
                    $menu .=  "<option value='$valor' $datoss>".$this->descArista."</option>";
                    $rs->MoveNext();
                }
            }
            return "<label class='select'>$menu</select></label>";
		}
	
}
?>
