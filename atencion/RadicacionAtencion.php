<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include_once realpath(dirname(__FILE__) . "/../") . '/include/OrfeoException.php';
include_once realpath(dirname(__FILE__) . "/") . '/CargarArchivo.php';
include_once realpath(dirname(__FILE__) . "/../") . '/include/tx/Radicacion.php';
include_once realpath(dirname(__FILE__) . "/../") . '/include/tx/Tx.php';
include_once realpath(dirname(__FILE__) . "/../") . '/include/Orfeo2Pdf.php';
include_once realpath(dirname(__FILE__) . "/../") . '/include/trd/Matriz.php';
include_once realpath(dirname(__FILE__) . "/../") . '/clasesComunes/Direcciones.php';
include_once realpath(dirname(__FILE__) . "/../") . '/clasesComunes/Usuario.php';
include_once realpath(dirname(__FILE__) . "/../") . '/class_control/TipoDocumental.php';
include_once realpath(dirname(__FILE__) . "/../") . '/class_control/anexo.php';
include_once realpath(dirname(__FILE__) . "/../") . '/class_control/Departamento.php';
include_once realpath(dirname(__FILE__) . "/../") . '/class_control/Municipio.php';

include_once realpath(dirname(__FILE__) . "/../") . '/include/class/Unoconv.php';

use Unoconv\Unoconv;


class RadicacionAtencion
{

    private $usuario;

    private $pdf;

    private $conf;

    protected $proyecto;

    protected $historico;

    protected $matriz;

    public function __construct()
    {
        // parent::__construct();
        $this->usuario = new Usuario();
        $this->pdf = new Orfeo2Pdf();
    }

    public function searchUSer($usuario)
    {
        $usuarios = $this->usuario->findUsuario($usuario);
        $this->generarRespuesta($usuarios, "No se encontron usuarios activos");
    }

    private function generarRespuesta($respuesta, $mensaje)
    {
        $resp = false;
        $resp = (count($respuesta) > 0);
        $detalle = ($resp) ? $respuesta : $mensaje;
        
        $accion = array(
            'respuesta' => $resp,
            'mensaje' => $detalle
        );
        print_r(json_encode($accion));
    }

    public function crearPdf($usr, $radicado, $asunto, $destinatario, $contenido, $arhivos, $path, $codVerifica, $firma, $mncpioNombre = "Bogota")
    {
        include realpath(dirname(__FILE__) . "/../") . '/config.php';
        $imagen = realpath(dirname(__FILE__) . "/../") . "/" . $imagenHeader;
        $this->pdf->setFooterText($textoPie);
        if (! empty($imagenPie)) {
            $this->pdf->setImagenesPie($imagenPie);
        }
        
        $this->pdf->SetSubject('Documento Atencion ciudadano');
        $this->pdf->SetKeywords($radicado . ', pqrsd, ' . $entidad);
        $this->pdf->setRadicado($radicado);
        $this->pdf->setCodigoVeficacion($codVerifica);
        $this->pdf->setFechaRadicado(date('Y-m-d'));
        $this->pdf->setHeaderData($imagen, 180, "", "");
        $this->pdf->setData($radicado . ' - ' . date('Y-m-d h:m:s') . "- " . $usr["usuaRadica"] . " - " . $usr["dependenciaRadica"]);
        
        $this->pdf->SetFont('arial', '', 11);
        // add a page
        $this->pdf->AddPage();
        // $this->pdf->Image($this->conf->getImagenFirma(), 200, 20, 15, 10, 'GIF');
        // set font
        $this->pdf->SetFont('arial', '', 11);
        $this->pdf->Write($h = 0, "\n\r\n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
        $this->pdf->Write($h = 0, "\n\r\n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
        $this->pdf->Write($h = 0, $mncpioNombre . " " . Utils::fechaFormateada(time()) . "\n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
        $this->pdf->Write($h = 0, "\n\r\n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
        $this->pdf->SetFont('arial', '', 14);
        $this->pdf->Write($h = 0, "\n\r\n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
        $this->pdf->SetFont('arial', '', 11);
        $this->pdf->Write($h = 0, "Asunto  : " . $asunto . "\n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
        $this->pdf->Write($h = 0, "\n\r\n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
        $this->pdf->writeHTML($contenido, $ln = true, false, false, false, $align = 'J');
        $this->pdf->Write($h = 0, "\n\r\n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
        $ormargins = $this->pdf->getOriginalMargins();
        // $ormargins ['right']
        $dimensiones = $this->pdf->getPageDimensions();
        $curr_y = $this->pdf->GetY();
        if ($curr_y < $dimensiones['h'] - 200);
        $this->pdf->Write($h = 0, "\n\r\n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
        
        if (!is_array($firma) ) {
        $this->pdf->Write($h = 0, "Cordialmente  : " . "\n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
        $this->pdf->Write($h = 0, "\n\r\n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
        $this->pdf->Write($h = 0, "\n\r\n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
        $this->pdf->Write($h = 0, $firma->NOMBRE . "\n\r" . $firma->TIPO_DOC . " " . $firma->DOCUMENTO . "\n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
        }else{
            $this->pdf->SetFont('arial', '', 8);
            $this->pdf->Write($h = 0, "proyecto  : " . "\n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
            $this->pdf->Write($h = 0, $firma["proyecto"]. "\n\r" . $firma["depe_codi"]. " \n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
        }
        $this->pdf->Write($h = 0, "\n\r\n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
        if (! empty($arhivos)) {
            $this->pdf->Write($h = 0, "\n\r\n\r", $link = '', $fill = 0, $align = 'L', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
            $this->pdf->SetFont('arial', '', 8);
            $this->pdf->Write($h = 0, $arhivos, $link = '', $fill = 0, $align = 'L', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
        }
        
        $certificate = 'file://' . $certificadoPath;
        
        // set additional information
        $info = array(
            'Name' => utf8_encode('Sistema de Gestión Documental Orfeo'),
            'Location' => 'Bogota ' . $entidad,
            'Reason' => 'Atencion al ciudadno',
            'ContactInfo' => $httpWebOficial
        );
        // Close and output PDF document
        $numPages = $this->pdf->getNumPages();
        $this->pdf->Close();
        $this->pdf->Output($path . $radicado . ".pdf", 'F');
        return $numPages;
    }

    public function radicar($destinatario, $firma, $documento, $user, $tipoRad, $secuencia, $filesAnexos, $trd = "", $expediente = "", $ldpdd = 0)
    {
        include realpath(dirname(__FILE__) . "/../") . '/config.php';
        
        $direcciones = new Direcciones();
        $rad = new Radicacion($direcciones->getDB());
        $tr = new Tx($direcciones->getDB());
        $anexo = new Anexo($direcciones->getDB());
        $tipDoc = new TipoDocumental($direcciones->getDB());
        $matriz = new Matriz();
        
        $tipo = (! empty($trd)) ? $trd["tipo"] : 0;
        $apellidos = (! empty($destinatario["apellido2"])) ? " " . $destinatario["apellido2"] : "";
        $data["grbNombresUs"] = $destinatario["nombre"] . " " . $destinatario["apellido1"] . $apellidos;
        
        $data["sgd_ciu_codigo"] = $destinatario["sgd_ciu_codigo"];
        $data["tipo_emp_us"] = $destinatario["tipo_emp_us"];
        $data["telefono_us"] = $destinatario["telefono"];
        $data["direccion_us"] = $destinatario["direccion"];
        $data["mail_us"] = $destinatario["email"];
        $data["muni_us"] = $destinatario["muni"];
        $data["idpais"] = $destinatario["pais"];
        $data["idcont"] = $destinatario["continente"];
        $data["cc_documento_us"] = $destinatario["documento"];
        $data["dpto_us"] = $destinatario["depto"];
        $data["tipo_emp_us"] = $destinatario["tipo_emp_us"];
    if (empty($firma)) {
        $firma=new stdClass();
            $firma->NOMBRE = $data["grbNombresUs"];
            $firma->TIPO_DOC = "C.C ";
            $firma->DOCUMENTO = $destinatario["documento"];
        }else if(is_string($firma)) {
            $firma = json_decode($firma);
        }
        $rad->radiFechOfic = date("Y-m-d");
        $rad->radiPais = $destinatario["pais"];
        $rad->radiDepeActu = $user["dependenciaDestino"];
        $rad->radiDepeRadi = $user["dependenciaRadica"];
        $rad->radiUsuaActu = $user["usuaDestinoCod"];
        $rad->trteCodi = $destinatario["tipoRemitente"];
        $rad->tdocCodi = $tipo;
        $rad->noanexos = count($filesAnexos->subidos);
        $rad->tdidCodi = $tipoRad;
        $rad->mrecCodi = $documento["mrec_cod"];
        if (! empty($documento["radicadopadre"])) {
            $rad->radiNumeDeri = trim($documento["radicadopadre"]);
        }
        $rad->carpCodi = $tipoRad;
        $rad->carPer = 0;
        $rad->usuaCodi = $user["usuaRadicaCod"];
        $rad->raAsun = substr(htmlspecialchars(stripcslashes($documento["asunto"])), 0, 349);
        $rad->sgd_apli_codi = "0";
        $rad->noDigitosDep = intval($digitosDependencia);
        $rad->dependencia = $user["dependenciaRadica"];
        $secuencia = $rad->getSecuencia($tipoRad, $user["dependenciaRadica"]);
        $noRad = $rad->newRadicado($tipoRad, $secuencia);
        $data["nurad"] = $noRad;
        $codDir = $direcciones->saveDireccion($data, 1, true);
        $codTx = 2;
        $flag = 1;
        $radicadosSel[0] = $noRad;
        $this->actualizaLdpdd($noRad, $ldpdd);
        $tr->insertarHistorico($radicadosSel, $user["dependenciaRadica"], $user["usuaRadicaCod"], $user["dependenciaDestino"], $user["usuaDestinoCod"], "Radicar Documento.", $codTx);
        if (! empty($expediente)) {
            $expe = new Expediente();
            $expe->insertar_expediente($expediente["SGD_EXP_NUMERO"], $radicado, $user["dependenciaRadica"], $user["usuaRadicaCod"], $user["docUsuaRadica"], $expediente["SGD_CARPETA_ID"]);
        }
        $documento["archivos"] = null;
        if (! empty($filesAnexos) && $filesAnexos->tieneArchivos()) {
            $documento["archivos"] = $filesAnexos->getListadoImprimible();
            $filesAnexos->moverArchivoCarpetaBodegaYaSubidos($noRad, $user["dependenciaRadica"]);
            foreach ($filesAnexos->getNombreOrfeo() as $clave => $value) {
                $anexo->anex_radi_nume = $noRad;
                $anexo->anex_nomb_archivo = $value["nombreArchivo"];
                $anexo->anexoExtension = $value["extension"];
                $anexo->anex_solo_lect = "'S'";
                $anexo->anex_creador = "'" . $user["usuaRadica"] . "'";
                $anexo->anex_desc = $value["descr"];
                $anexo->anex_borrado = "'N'";
                $anexo->anex_salida = "0";
                $anexo->anex_depe_creador = $user["dependenciaRadica"];
                $anexo->sgd_tpr_codigo = 0;
                $anexo->usuaDoc = "'" . $user["docUsuaRadica"] . "'";
                $anexo->anex_estado = 1;
                $anexo->sgd_exp_numero = "";
                $anexo->anexarFilaRadicado();
            }
        }
        $direcciones->getDB()->conn->debug = false;
        $ruta = $filesAnexos->getBodegaDir();
        $ruta .= "/" . substr($noRad, 0, 4) . "/" . $user["dependenciaRadica"] . "/";
        $pathDB = "/" . substr($noRad, 0, 4) . "/" . $user["dependenciaRadica"] . "/";
        $numPages = $this->crearPdf($user, $noRad, $documento["asunto"], $destinatario, $documento["contenido"], $documento["archivos"], $ruta, $rad->codigoverificacion, $firma);
        $rad->updatePath("'" . $pathDB . $noRad . ".pdf'", $noRad, $numPages);
        
        if (! empty($trd)) {

            $codMatrix = $matriz->getMatrixId($user["dependencia"], $trd["serie"], $trd["subserie"], $trd["tipo"]);
            $tipDoc->insertarTRD($codMatrix, $codMatrix, $noRad, $user["dependenciaRadica"], $user["usuaRadicaCod"]);
        }
        $salida["radicado"] = $noRad;
        $salida["codVerifica"] = $rad->codigoverificacion;
        $salida["rutafile"] = $pathDB . $noRad . ".pdf";
        return $salida;
    }

    private function actualizaLdpdd($radicado, $ldpdd){

        $ruta_raiz = "../";
        require_once($ruta_raiz."include/db/ConnectionHandler.php");

        include realpath(dirname(__FILE__) . "/../") . '/config.php';
        
        if (!$dbL)   $dbL = new ConnectionHandler($ruta_raiz);
        $dbL->conn->SetFetchMode(ADODB_FETCH_ASSOC);
        
       
        $sql="UPDATE RADICADO
            SET LDPDD = '". $ldpdd . "'
            WHERE RADI_NUME_RADI = '".$radicado."'";

        $rsL = $dbL->query($sql);

        return $salida;
    }


    public function radicarActaAnulados($user, $variables, $plantilla, $dependenciaRadica, $usuaRadicaCod){
        $ruta_raiz = "../";
        require_once($ruta_raiz."include/db/ConnectionHandler.php");

        include realpath(dirname(__FILE__) . "/../") . '/config.php';
        $direcciones = new Direcciones();
        $rad = new Radicacion($direcciones->getDB());
        $tr = new Tx($direcciones->getDB());
        $anexo = new Anexo($direcciones->getDB());
        $tipDoc = new TipoDocumental($direcciones->getDB());
        $matriz = new Matriz();

        if (!$db1)   $db1 = new ConnectionHandler($ruta_raiz);
        $db1->conn->SetFetchMode(ADODB_FETCH_ASSOC);
        
        $tipoRad=7;
        /****************************************/
        $sql="SELECT USUA_NOMB, USUA_DOC, USUA_EMAIL, ID, DEPE_NOMB
                FROM USUARIO U, DEPENDENCIA D
                WHERE USUA_CODI=1 AND U.DEPE_CODI = 20 AND D.DEPE_CODI = 20";

        $rs1 = $db1->query($sql);

        while(!$rs1->EOF){ 
            $nombre=$rs1->fields['USUA_NOMB'];
            $documento=$rs1->fields['USUA_DOC'];
            $email=$rs1->fields['USUA_EMAIL'];
            $id=$rs1->fields['ID'];
            $dependencia=$rs1->fields['DEPE_NOMB'];
            $rs1->MoveNext();
        }

        /****************************************/
        $sql="SELECT USUA_LOGIN, USUA_DOC
                FROM USUARIO
                WHERE USUA_CODI=".$usuaRadicaCod." AND DEPE_CODI = ".$dependenciaRadica;

        $rs1 = $db1->query($sql);

        while(!$rs1->EOF){ 
            $USUA_LOGIN=$rs1->fields['USUA_LOGIN'];
            $USUA_DOC=$rs1->fields['USUA_DOC'];
            $rs1->MoveNext();
        }

        $tipo = 0;
        
        $rad->radiFechOfic = date("Y-m-d");
        $rad->radiPais = "170";
        $rad->radiDepeActu = 20;
        
        $rad->radiDepeRadi = $dependenciaRadica;

        $rad->radiUsuaActu = 1;
        $rad->trteCodi = NULL;
        $rad->tdocCodi = $tipo;
        $rad->noanexos = 1;
        $rad->tdidCodi = 7;
        $rad->mrecCodi = 1;
        
        $rad->carpCodi = 7;
        $rad->carPer = 0;
        $rad->usuaCodi = $user["usuaRadicaCod"];
        $rad->raAsun = substr(htmlspecialchars(stripcslashes($variables["asunto"])), 0, 349);
        $rad->sgd_apli_codi = "0";
        $rad->noDigitosDep = intval($digitosDependencia);
        $rad->dependencia = $dependenciaRadica;
        $secuencia = $rad->getSecuencia(7, $dependenciaRadica);
        $noRad = $rad->newRadicado(7, $secuencia);
        
        $data["nurad"] = $noRad;
        // DIRECCION DATA
        $data ["sgd_ciu_codigo"] = NULL; //DOCUMENTO
        $data ["grbNombresUs"] = $nombre;
        $data ["cc_documento_us"] = $documento;
        $data ["muni_us"]=1;
        $data ["dpto_us"]=11;
        $data ["idpais"]=170;
        $data ["idcont"]=1;
        $data ["nurad"]=$noRad;
        $data ["direccion_us"]=$dependencia;
        $data ["telefono_us"] = '4873820';
        $data ["mail_us"]=$email;
        $record ['SGD_DIR_TIPO'] = $dirNivel;
        $data["otro_us"]=$nombre;
        //
        $codDir = $direcciones->saveDireccion($data, 1, true);
        $codTx = 2;
        $flag = 1;
        $radicadosSel[0] = $noRad;
        $tr->insertarHistorico($radicadosSel, $dependenciaRadica, $usuaRadicaCod,$rad->radiDepeActu , $rad->radiUsuaActu, "'Radicacion Acta Anulado.'", $codTx);
        //$documento["archivos"] = null;
        
        $direcciones->getDB()->conn->debug = false;
        
        $ruta .= "/" . substr($noRad, 0, 4) . "/" . $dependenciaRadica . "/docs/";
        
        $pathDB = "/" . substr($noRad, 0, 4) . "/" . $dependenciaRadica . "/docs/";
        
        $variables["RAD_S"]=$noRad;
        $variables["F_RAD_S"]=$rad->radiFechOfic;
        $numPages = $this->crearPdfActaAnulado($db,$user, $noRad, $rad->codigoverificacion, $variables,$plantilla);
        
        $anio = substr($noRad,0,3);
        $rad->updatePath("'$pathDB". $noRad . ".pdf'", $noRad, $numPages);
        //INSERTAR ANEXO
        $anexo->anex_radi_nume = $noRad;
        $anexo->anex_nomb_archivo = $noRad . ".pdf";
        $anexo->anexoExtension = "pdf";
        $anexo->anex_solo_lect = "'S'";
        $anexo->anex_creador = "'" . $USUA_LOGIN . "'";
        $anexo->anex_desc = "ACTA ANULADOS";
        $anexo->anex_borrado = "'N'";
        $anexo->anex_salida = "0";
        $anexo->anex_depe_creador = $dependenciaRadica;
        $anexo->sgd_tpr_codigo = 0;
        $anexo->usuaDoc = $USUA_DOC;
        $anexo->anex_estado = 1;
        $anexo->sgd_exp_numero = "";
        $anexo->anexarFilaRadicado();

         /****************************************/
        $sql="UPDATE ANEXOS
            SET ANEX_NOMB_ARCHIVO = '". $noRad . ".pdf'
            WHERE ANEX_RADI_NUME = '".$noRad."' AND ANEX_NUMERO=1";

        $rs1 = $db1->query($sql);

        //FIN INSERTAR ANEXO

         //INSERTAR TRD
        $usuar["dependenciaRadica"] = $usuarioRadica["DEPE_CODI"];
        $usuar["dependenciaDestino"] = $usuarioDestino["DEPE_CODI"];
        $usuar["docUsuaRadica"] = $usuarioDestino["USUA_DOC"];
        $usuar["docUsuaDestino"] = $usuarioRadica["USUA_DOC"];
        $usuar["usuaDestinoCod"] = $usuarioDestino["USUA_CODI"];
        $usuar["usuaRadicaCod"] = $usuarioRadica["USUA_CODI"];
        $usuar["usuaDestino"] = $usuarioDestino["USUA_LOGIN"];
        $usuar["usuaRadica"] = $usuarioRadica["USUA_LOGIN"];
        $codMatrix = $matriz->getMatrixId(20, 145, 4, 1612);
        $tipDoc->insertarTRD($codMatrix, $codMatrix, $noRad, 20, 1);
        
        $salida["radicado"] = $noRad;
        $salida["codVerifica"] = $rad->codigoverificacion;
        $salida["rutafile"] = $pathDB . $noRad . ".pdf";
        return $salida;
    }

    public function radicarCCU($db,$destinatario, $documento, $user, $tipoRad, $filesAnexos, $variables,$plantilla)
    {
        include realpath(dirname(__FILE__) . "/../") . '/config.php';
        $direcciones = new Direcciones();
        $rad = new Radicacion($direcciones->getDB());
        $tr = new Tx($direcciones->getDB());
        $anexo = new Anexo($direcciones->getDB());
        $tipDoc = new TipoDocumental($direcciones->getDB());
        $matriz = new Matriz();
        
        $tipo = 0;
        $apellidos = (! empty($destinatario["apellido2"])) ? " " . $destinatario["apellido2"] : "";
        $data["grbNombresUs"] = $destinatario["nombre"] . " " . $destinatario["apellido1"] . $apellidos;
        
        $data["sgd_ciu_codigo"] = $destinatario["sgd_ciu_codigo"];
        $data["tipo_emp_us"] = $destinatario["tipo_emp_us"];
        $data["telefono_us"] = $destinatario["telefono"];
        $data["direccion_us"] = $destinatario["direccion"];
        $data["mail_us"] = $destinatario["email"];
        $data["muni_us"] = $destinatario["muni"];
        $data["idpais"] = $destinatario["pais"];
        $data["idcont"] = $destinatario["continente"];
        $data["cc_documento_us"] = $destinatario["documento"];
        $data["dpto_us"] = $destinatario["depto"];
    $data["tipo_emp_us"] = $destinatario["tipo_emp_us"];
    $data["sgd_dir_nombre"] =  $destinatario["nombre"];
    $data["sgd_dir_apellido"]=$destinatario["apellido1"] . $apellidos;
        $rad->radiFechOfic = date("Y-m-d");
        $rad->radiPais = $destinatario["pais"];
        $rad->radiDepeActu = $user["dependenciaDestino"];
        $user["dependenciaRadica"]=321;
        $rad->radiDepeRadi = $user["dependenciaRadica"];
        $numAnexos = (is_array($filesAnexos->subidos)?count($filesAnexos->subidos):0);
        $rad->radiUsuaActu = $user["usuaDestinoCod"];
        $rad->trteCodi = $destinatario["tipoRemitente"];
        $rad->tdocCodi = $tipo;
        $rad->noanexos = $numAnexos;
        $rad->tdidCodi = $tipoRad;
        $rad->mrecCodi = $documento["mrec_cod"];
        if (! empty($documento["radicadopadre"])) {
            $rad->radiNumeDeri = trim($documento["radicadopadre"]);
        }
        $rad->carpCodi = $tipoRad;
        $rad->carPer = 0;
        $rad->usuaCodi = $user["usuaRadicaCod"];
        $rad->raAsun = substr(htmlspecialchars(stripcslashes($documento["asunto"])), 0, 349);
        $rad->sgd_apli_codi = "0";
        $rad->noDigitosDep = intval($digitosDependencia);
        $rad->dependencia = $user["dependenciaRadica"];
        $secuencia = $rad->getSecuencia($tipoRad, $user["dependenciaRadica"]);
        $noRad = $rad->newRadicado($tipoRad, $secuencia);
        $data["nurad"] = $noRad;
        $codDir = $direcciones->saveDireccion($data, 1, true);
        $codTx = 2;
        $flag = 1;
        $radicadosSel[0] = $noRad;
        $tr->insertarHistorico($radicadosSel, $user["dependenciaRadica"], $user["usuaRadicaCod"], $user["dependenciaDestino"], $user["usuaDestinoCod"], "Radicar Documento.", $codTx);
        $documento["archivos"] = null;
        if (! empty($filesAnexos) && $filesAnexos->tieneArchivos()) {
            $documento["archivos"] = $filesAnexos->getListadoImprimible();
            $filesAnexos->moverArchivoCarpetaBodegaYaSubidos($noRad, $user["dependenciaRadica"]);
            foreach ($filesAnexos->getNombreOrfeo() as $clave => $value) {
                $anexo->anex_radi_nume = $noRad;
                $anexo->anex_nomb_archivo = $value["nombreArchivo"];
                $anexo->anexoExtension = $value["extension"];
                ;
                $anexo->anex_solo_lect = "'S'";
                $anexo->anex_creador = "'" . $user["usuaRadica"] . "'";
                $anexo->anex_desc = $value["descr"];
                $anexo->anex_borrado = "'N'";
                $anexo->anex_salida = "0";
                $anexo->anex_depe_creador = $user["dependenciaRadica"];
                $anexo->sgd_tpr_codigo = 0;
                $anexo->usuaDoc = "'" . $user["docUsuaRadica"] . "'";
                $anexo->anex_estado = 1;
                $anexo->sgd_exp_numero = "";
                $anexo->anexarFilaRadicado();
            }
        }
        $direcciones->getDB()->conn->debug = false;
        //$ruta = $filesAnexos->getBodegaDir();
        $ruta .= "/" . substr($noRad, 0, 4) . "/" . $user["dependenciaRadica"] . "/";
        $pathDB = "/" . substr($noRad, 0, 4) . "/" . $user["dependenciaRadica"] . "/";

        $variables["RAD_S"]=$noRad;
        $variables["F_RAD_S"]=$rad->radiFechOfic;

        $numPages = $this->crearPdfCCU($direcciones->getDB(),$user, $noRad, $documento["asunto"], $destinatario, $documento["contenido"], $documento["archivos"], $ruta, $rad->codigoverificacion, $variables,$plantilla);
        
        $anio = substr($noRad,0,3);
        $rad->updatePath("'$pathDB". $noRad . ".pdf'", $noRad, $numPages);
        
        $salida["radicado"] = $noRad;
        $salida["codVerifica"] = $rad->codigoverificacion;
        $salida["rutafile"] = $pathDB . $noRad . ".pdf";
        return $salida;
    }

   
    private function reemplaza($xml,$variable,$valor,$comparacion,$respuestaSi,$respuestaNo){
        if($valor==$comparacion){
            $xml = str_replace ( $variable , $respuestaSi , $xml);
        }
        else{
            $xml = str_replace ( $variable , $respuestaNo , $xml);
        }
        
        return $xml;
    }

    public function radicarActaFoliado($user, $variables, $plantilla, $dependenciaRadica, $usuaRadicaCod){

        $ruta_raiz = "../";
        require_once($ruta_raiz."include/db/ConnectionHandler.php");

        include realpath(dirname(__FILE__) . "/../") . '/config.php';
        $direcciones = new Direcciones();
        $rad = new Radicacion($direcciones->getDB());
        $tr = new Tx($direcciones->getDB());
        $anexo = new Anexo($direcciones->getDB());
        $tipDoc = new TipoDocumental($direcciones->getDB());
        $matriz = new Matriz();

        if (!$db1)   $db1 = new ConnectionHandler($ruta_raiz);
        $db1->conn->SetFetchMode(ADODB_FETCH_ASSOC);
        
        $tipoRad=7;
        /****************************************/
        $sql="SELECT USUA_NOMB, USUA_DOC, USUA_EMAIL, ID, DEPE_NOMB
                FROM USUARIO U, DEPENDENCIA D
                WHERE USUA_CODI=1 AND U.DEPE_CODI = 20 AND D.DEPE_CODI = 20";

        $rs1 = $db1->query($sql);

        while(!$rs1->EOF){ 
            $nombre=$rs1->fields['USUA_NOMB'];
            $documento=$rs1->fields['USUA_DOC'];
            $email=$rs1->fields['USUA_EMAIL'];
            $id=$rs1->fields['ID'];
            $dependencia=$rs1->fields['DEPE_NOMB'];
            $rs1->MoveNext();
        }

        /****************************************/
        $sql="SELECT USUA_LOGIN, USUA_DOC
                FROM USUARIO
                WHERE USUA_CODI=".$usuaRadicaCod." AND DEPE_CODI = ".$dependenciaRadica;

        $rs1 = $db1->query($sql);

        while(!$rs1->EOF){ 
            $USUA_LOGIN=$rs1->fields['USUA_LOGIN'];
            $USUA_DOC=$rs1->fields['USUA_DOC'];
            $rs1->MoveNext();
        }

        $tipo = 0;
        
        $rad->radiFechOfic = date("Y-m-d");
        $rad->radiPais = "170";
        $rad->radiDepeActu = 20;
        
        $rad->radiDepeRadi = $dependenciaRadica;

        $rad->radiUsuaActu = 1;
        $rad->trteCodi = NULL;
        $rad->tdocCodi = $tipo;
        $rad->noanexos = 1;
        $rad->tdidCodi = 7;
        $rad->mrecCodi = $documento["mrec_cod"];
        
        $rad->carpCodi = 7;
        $rad->carPer = 0;
        $rad->usuaCodi = $user["usuaRadicaCod"];
        $rad->raAsun = substr(htmlspecialchars(stripcslashes($variables["asunto"])), 0, 349);
        $rad->sgd_apli_codi = "0";
        $rad->noDigitosDep = intval($digitosDependencia);
        $rad->dependencia = $dependenciaRadica;
        $secuencia = $rad->getSecuencia(7, $dependenciaRadica);
        $noRad = $rad->newRadicado(7, $secuencia);
        
        $data["nurad"] = $noRad;
        // DIRECCION DATA
        $data ["sgd_ciu_codigo"] = NULL; //DOCUMENTO
        $data ["grbNombresUs"] = $nombre;
        $data ["cc_documento_us"] = $documento;
        $data ["muni_us"]=1;
        $data ["dpto_us"]=11;
        $data ["idpais"]=170;
        $data ["idcont"]=1;
        $data ["nurad"]=$noRad;
        $data ["direccion_us"]=$dependencia;
        $data ["telefono_us"] = '4873820';
        $data ["mail_us"]=$email;
        $record ['SGD_DIR_TIPO'] = $dirNivel;
        $data["otro_us"]=$nombre;
        //
        $codDir = $direcciones->saveDireccion($data, 1, true);
        $codTx = 2;
        $flag = 1;
        $radicadosSel[0] = $noRad;
        $tr->insertarHistorico($radicadosSel, $dependenciaRadica, $usuaRadicaCod, $user["dependenciaDestino"], $user["usuaDestinoCod"], "'Radicacion Acta Indice Electronico.'", $codTx);
        $documento["archivos"] = null;
        
        $direcciones->getDB()->conn->debug = false;

        
        $pathDB = "/" . substr($noRad, 0, 4) . "/" . $dependenciaRadica . "/docs/";
        $rad->updatePath("'$pathDB". $noRad . ".pdf'", $noRad, $numPages);
        $variables["RAD_S"]=$noRad;
        $variables["F_RAD_S"]=$rad->radiFechOfic;
        $numPages = $this->crearPdfActaFoliado($direcciones->getDB(),$user, $noRad, $rad->codigoverificacion, $variables,$plantilla);
        
        $anio = substr($noRad,0,3);
        //$rad->updatePath("'$pathDB". $noRad . ".xml'", $noRad, $numPages);
        //INSERTAR ANEXO
        $anexo->anex_radi_nume = $noRad;
        $anexo->anex_nomb_archivo = $noRad . ".pdf";
        $anexo->anexoExtension = "pdf";
        $anexo->anex_solo_lect = "'S'";
        $anexo->anex_creador = "'" . $USUA_LOGIN . "'";
        $anexo->anex_desc = "ACTA XML FOLIADO ELECTRÓNICO";
        $anexo->anex_borrado = "'N'";
        $anexo->anex_salida = "0";
        $anexo->anex_depe_creador = $dependenciaRadica;
        $anexo->sgd_tpr_codigo = 0;
        $anexo->usuaDoc = $USUA_DOC;
        $anexo->anex_estado = 1;
        $anexo->sgd_exp_numero = "";
        $anexo->anexarFilaRadicado();

         /****************************************/
        $sql="UPDATE ANEXOS
            SET ANEX_NOMB_ARCHIVO = '". $noRad . ".pdf'
            WHERE ANEX_RADI_NUME = '".$noRad."' AND ANEX_NUMERO=1";

        $rs1 = $db1->query($sql);

        //FIN INSERTAR ANEXO

         //INSERTAR TRD
        $usuar["dependenciaRadica"] = $usuarioRadica["DEPE_CODI"];
        $usuar["dependenciaDestino"] = $usuarioDestino["DEPE_CODI"];
        $usuar["docUsuaRadica"] = $usuarioDestino["USUA_DOC"];
        $usuar["docUsuaDestino"] = $usuarioRadica["USUA_DOC"];
        $usuar["usuaDestinoCod"] = $usuarioDestino["USUA_CODI"];
        $usuar["usuaRadicaCod"] = $usuarioRadica["USUA_CODI"];
        $usuar["usuaDestino"] = $usuarioDestino["USUA_LOGIN"];
        $usuar["usuaRadica"] = $usuarioRadica["USUA_LOGIN"];
        $codMatrix = $matriz->getMatrixId(20, 145, 4, 1612);
        $tipDoc->insertarTRD($codMatrix, $codMatrix, $noRad, 20, 1);
        
        $salida["radicado"] = $noRad;
        $salida["codVerifica"] = $rad->codigoverificacion;
        $salida["rutafile"] = $pathDB . $noRad . ".pdf";
        return $salida;
    }

    public function crearXMLFoliado($db, $usr, $radicado, $codVerifica, $variables,$plantilla)
    {
        include realpath(dirname(__FILE__) . "/../") . '/config.php';

        $imagen = realpath(dirname(__FILE__) . "/../") . "/" . $imagenHeader;
        
        //municipio-dpto
        $direcciones = new Direcciones();
        $departamento = new Departamento($direcciones->getDB());
        $ciudad = new Municipio($direcciones->getDB());
        
        /********************************DOCX_EDITOR*************************************************/
        // Create the Object.
        $zip = new ZipArchive();
        //use Unoconv\Unoconv;
        
        $anio = substr($radicado,0,4);
        $carpeta = intval(substr($radicado,4,3));

        // Use same filename for "save" and different filename for "save as".
        
        $ruta = realpath(dirname(__FILE__) . "/../");
        //$inputFilename = $ruta . $plantilla;
        $inputFilename = BODEGA . '/plantillas/' . $plantilla;
        //$nuevo_fichero = $ruta . $radicado . '.odt';

        $nuevo_fichero = BODEGA . '/' . $anio . '/' . $carpeta . '/docs/' . $radicado .  '.odt';

        if (!copy($inputFilename, $nuevo_fichero)) {
            echo "Error al copiar $fichero...\n";
        }

        // Open the Microsoft Word .docx file as if it were a zip file... because it is.
        if ($zip->open($nuevo_fichero, ZipArchive::CREATE)!==TRUE) {
            echo "Cannot open $filename :( "; die;
        }

        // Fetch the document.xml file from the word subdirectory in the archive.
        $xml = $zip->getFromName('content.xml');
        
        $xml = $this->reemplaza($xml,"*fecha_indice*","1","1",$variables["fecha_indice"],"__");
        $xml = $this->reemplaza($xml,"*expediente*","1","1",$variables["expediente"],"__");

        $xml = $this->reemplaza($xml,"*radicados*","1","1",$variables["radicados"],"___");
        $xml = $this->reemplaza($xml,"*fecha_corto*","1","1",$variables["fecha_corto"],"__");
        $xml = $this->reemplaza($xml,"*tipo_radicado*","1","1",$variables["tipo_radicado"],"__");
        $xml = $this->reemplaza($xml,"*RAD_S*","1","1",$variables["RAD_S"],"___");
        $xml = $this->reemplaza($xml,"*F_RAD_S*","1","1",$variables["F_RAD_S"],"__");        


        if ($zip->addFromString('content.xml', $xml)) {
            //echo 'Archivo de anulacion de radicado generado exitosamente: '; 
        }
        else { 
            //echo 'File not written.  Go back and add write permissions to this folder!l'; 
        }

        $zip->close();

        /********************************FIN DOCX_EDITOR*************************************************/

        /********************************CONVIERTE ODT A PDF ***************************************/

        //$originFilePath = $radicado . '.odt';
        
        $originFilePath = BODEGA ."/".$anio . "/" . $carpeta . "/docs/" . $radicado;
        
        $outputDirPath  = BODEGA ."/". $anio . "/" . $carpeta . "/docs/";

        Unoconv::convertToPdf($originFilePath, $outputDirPath);

        return 3;
    }

    public function crearPdfActaFoliado($db, $usr, $radicado, $codVerifica, $variables,$plantilla)
    {
        include realpath(dirname(__FILE__) . "/../") . '/config.php';

        $imagen = realpath(dirname(__FILE__) . "/../") . "/" . $imagenHeader;
        
        //municipio-dpto
        $direcciones = new Direcciones();
        $departamento = new Departamento($direcciones->getDB());
        $ciudad = new Municipio($direcciones->getDB());
        
        /********************************DOCX_EDITOR*************************************************/
        // Create the Object.
        $zip = new ZipArchive();
        //use Unoconv\Unoconv;
        
        $anio = substr($radicado,0,4);
        $carpeta = intval(substr($radicado,4,3));

        // Use same filename for "save" and different filename for "save as".
        
        $ruta = realpath(dirname(__FILE__) . "/../");
        //$inputFilename = $ruta . $plantilla;
        $inputFilename = BODEGA. '/plantillas/' . $plantilla;
        //$nuevo_fichero = $ruta . $radicado . '.odt';

        $nuevo_fichero = BODEGA ."/". $anio . '/' . $carpeta . '/docs/' . $radicado .  '.odt';

        if (!copy($inputFilename, $nuevo_fichero)) {
            echo "Error al copiar $fichero...\n";
        }

        // Open the Microsoft Word .docx file as if it were a zip file... because it is.
        if ($zip->open($nuevo_fichero, ZipArchive::CREATE)!==TRUE) {
            echo "Cannot open $filename :( "; die;
        }

        // Fetch the document.xml file from the word subdirectory in the archive.
        $xml = $zip->getFromName('content.xml');
        
        $xml = $this->reemplaza($xml,"*fecha_indice*","1","1",$variables["fecha_indice"],"__");
        $xml = $this->reemplaza($xml,"*expediente*","1","1",$variables["expediente"],"__");

        $xml = $this->reemplaza($xml,"*radicados*","1","1",$variables["radicados"],"___");
        $xml = $this->reemplaza($xml,"*fecha_corto*","1","1",$variables["fecha_corto"],"__");
        $xml = $this->reemplaza($xml,"*tipo_radicado*","1","1",$variables["tipo_radicado"],"__");
        $xml = $this->reemplaza($xml,"*RAD_S*","1","1",$variables["RAD_S"],"___");
        $xml = $this->reemplaza($xml,"*F_RAD_S*","1","1",$variables["F_RAD_S"],"__");        


        if ($zip->addFromString('content.xml', $xml)) {
            //echo 'Archivo de anulacion de radicado generado exitosamente: '; 
        }
        else { 
            //echo 'File not written.  Go back and add write permissions to this folder!l'; 
        }

        $zip->close();

        /********************************FIN DOCX_EDITOR*************************************************/

        /********************************CONVIERTE ODT A PDF ***************************************/

        //$originFilePath = $radicado . '.odt';
        
        $originFilePath = BODEGA ."/". $anio . "/" . $carpeta . "/docs/" . $radicado;
        
        $outputDirPath  = BODEGA . "/".$anio . "/" . $carpeta . "/docs/";

        Unoconv::convertToPdf($originFilePath, $outputDirPath);

        return 3;
    }

    public function crearPdfActaAnulado($db, $usr, $radicado, $codVerifica, $variables,$plantilla)
    {
        include realpath(dirname(__FILE__) . "/../") . '/config.php';

        $imagen = realpath(dirname(__FILE__) . "/../") . "/" . $imagenHeader;
        
        //municipio-dpto
        $direcciones = new Direcciones();
        $departamento = new Departamento($direcciones->getDB());
        $ciudad = new Municipio($direcciones->getDB());
        
        /********************************DOCX_EDITOR*************************************************/
        // Create the Object.
        $zip = new ZipArchive();
        //use Unoconv\Unoconv;
        
        $anio = substr($radicado,0,4);
        $carpeta = intval(substr($radicado,4,3));

        // Use same filename for "save" and different filename for "save as".
        
        $ruta = realpath(dirname(__FILE__) . "/../");
        $inputFilename = BODEGA .'/plantillas/' . $plantilla;
        
        $nuevo_fichero = BODEGA ."/".$anio . '/' . $carpeta . '/docs/' . $radicado .  '.odt';

        if (!copy($inputFilename, $nuevo_fichero)) {
            echo "Error al copiar $fichero...\n";
        }

        // Open the Microsoft Word .docx file as if it were a zip file... because it is.
        if ($zip->open($nuevo_fichero, ZipArchive::CREATE)!==TRUE) {
            echo "Cannot open $filename :( "; die;
        }

        // Fetch the document.xml file from the word subdirectory in the archive.
        $xml = $zip->getFromName('content.xml');
        
        $xml = $this->reemplaza($xml,"*numero_acta*","1","1",$variables["numero_acta"],"__");
        $xml = $this->reemplaza($xml,"*ano_actual*","1","1",$variables["ano_actual"],"__");
        $xml = $this->reemplaza($xml,"*fecha_inicio*","1","1",$variables["fecha_inicio"],"__");
        $xml = $this->reemplaza($xml,"*fecha_fin*","1","1",$variables["fecha_fin"],"__");
        $xml = $this->reemplaza($xml,"*radicados*","1","1",$variables["radicados"],"___");
        $xml = $this->reemplaza($xml,"*fecha_corto*","1","1",$variables["fecha_corto"],"__");
        $xml = $this->reemplaza($xml,"*tipo_radicado*","1","1",$variables["tipo_radicado"],"__");
        $xml = $this->reemplaza($xml,"*RAD_S*","1","1",$variables["RAD_S"],"___");
        $xml = $this->reemplaza($xml,"*F_RAD_S*","1","1",$variables["F_RAD_S"],"__");        
        $xml = $this->reemplaza($xml,"*tabla_anulados*","1","1",$variables["tabla_anulados"],"___");

        if ($zip->addFromString('content.xml', $xml)) {
            echo 'Archivo de anulacion de radicado generado exitosamente: '; 
        }
        else { echo 'File not written.  Go back and add write permissions to this folder!l'; }

        $zip->close();

        /********************************FIN DOCX_EDITOR*************************************************/

        /********************************CONVIERTE ODT A PDF ***************************************/

        $originFilePath = BODEGA."/" . $anio . "/" . $carpeta . "/docs/" . $radicado;
        
        $outputDirPath  = BODEGA."/" . $anio . "/" . $carpeta . "/docs/";

        Unoconv::convertToPdf($originFilePath, $outputDirPath);

        return 3;
    }


    public function crearPdfCCU($db, $usr, $radicado, $asunto, $destinatario, $contenido, $arhivos, $path, $codVerifica, $variables,$plantilla)
    {   
        foreach($variables as $nombre_campo => $valor){
           $asignacion = "\$" . $nombre_campo . "='" . $valor . "';";
           if($nombre_campo<>"recaptcha_response"){
               $valor = htmlspecialchars($valor, ENT_QUOTES);
               $variables[$nombre_campo] = $valor;
               $asignacion = "\$" . $nombre_campo . "='" . $variables[$nombre_campo] . "';";
           }
        }
        
        include realpath(dirname(__FILE__) . "/../") . '/config.php';

        $imagen = realpath(dirname(__FILE__) . "/../") . "/" . $imagenHeader;
        
        //municipio-dpto
        $direcciones = new Direcciones();
        $departamento = new Departamento($direcciones->getDB());
        $ciudad = new Municipio($direcciones->getDB());
        
        /********************************DOCX_EDITOR*************************************************/
        // Create the Object.
        $zip = new ZipArchive();
        //use Unoconv\Unoconv;
        
        $anio = substr($radicado,0,4);
        $carpeta = substr($radicado,4,3);

        // Use same filename for "save" and different filename for "save as".
        $ruta = realpath(dirname(__FILE__) . "/../");
        
        $inputFilename = BODEGA . '/plantillas/' . $plantilla;
        

        $nuevo_fichero = BODEGA . '/' . $anio . '/' . $carpeta . '/' . $radicado .  '.odt';

        if (!copy($inputFilename, $nuevo_fichero)) {
            echo "Error al copiar $fichero...\n";
        }

        // Open the Microsoft Word .docx file as if it were a zip file... because it is.
        if ($zip->open($nuevo_fichero, ZipArchive::CREATE)!==TRUE) {
            echo "Cannot open $filename :( "; die;
        }

        // Fetch the document.xml file from the word subdirectory in the archive.
        $xml = $zip->getFromName('content.xml');
        
        $xml = $this->reemplaza($xml,"*titulonit*",$variables["txtnit"].$variables["txtdocumento"],"",$variables["txtnit"].$variables["txtdocumento"],$variables["txtnit"].$variables["txtdocumento"]);
        $nombre=$variables["primNombre"] . " " . $variables["primApellido"];
        $xml = $this->reemplaza($xml,"*tituloempresa*",$variables["txtnoEmpresa"].$nombre,"",$variables["txtnoEmpresa"].$nombre,$variables["txtnoEmpresa"].$nombre);

        $xml = $this->reemplaza($xml,"*RAD_S*","1","1",$variables["RAD_S"],"___");
        $xml = $this->reemplaza($xml,"*F_RAD_S*","1","1",$variables["F_RAD_S"],"__"); 
       
        $xml = $this->reemplaza($xml,"*C01_acueducto*",$variables["C01_acueducto"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C01_alcatarillado*",$variables["C01_alcatarillado"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C01_urbano*",$variables["C01_urbano"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C01_rural*",$variables["C01_rural"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C01_vereda*",$variables["C01_vereda"],"","______________",$variables["C01_vereda"]);
        $xml = $this->reemplaza($xml,"*C01_corregimiento*",$variables["C01_corregimiento"],"","______________",$variables["C01_corregimiento"]);

        $xml = $this->reemplaza($xml,"*C05_fijo*",$variables["C05_fijo"],"1","_X_","___ Indefinido _X_");
        $xml = $this->reemplaza($xml,"*C05_duracion*",$variables["C05_duracion"],"","_______",$variables["C05_duracion"]);
        $xml = $this->reemplaza($xml,"*C06_area*",$variables["C06_area"],"","_______",$variables["C06_area"]);
        $xml = $this->reemplaza($xml,"*C05_vigencia1*",$variables["C05_vigencia"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C05_vigencia2*",$variables["C05_vigencia"],"2","_X_","__");
        $xml = $this->reemplaza($xml,"*C05_vigencia3*",$variables["C05_vigencia"],"3","_X_","__");
        $xml = $this->reemplaza($xml,"*C06_area*",$variables["C06_area"],"","___________",$variables["C06_area"]);
        
        $xml = $this->reemplaza($xml,"*C13_velocidad*",$variables["C13_velocidad"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C13_volumetrico*",$variables["C13_volumetrico"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C13_electromagnetico*",$variables["C13_electromagnetico"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C13_concentrico*",$variables["C13_concentrico"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C13_otro1*",$variables["C13_otro1"],"","______________",$variables["C13_otro1"]);
        $xml = $this->reemplaza($xml,"*C13_telemetria*",$variables["C13_telemetria"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C13_prepago*",$variables["C13_prepago"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C13_otro2*",$variables["C13_otro2"],"","______________",$variables["C13_otro2"]);
        $xml = $this->reemplaza($xml,"*C13_diametro*",$variables["C13_diametro"],"","______________",$variables["C13_diametro"]);
        $xml = $this->reemplaza($xml,"*C13_caudal*",$variables["C13_caudal"],"","______________",$variables["C13_caudal"]);
        $xml = $this->reemplaza($xml,"*C13_medicion*",$variables["C13_medicion"],"","______________",$variables["C13_medicion"]);

        $xml = $this->reemplaza($xml,"*C14_vertederos*",$variables["C14_vertederos"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C14_parshall*",$variables["C14_parshall"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C14_electromagnetico*",$variables["C14_electromagnetico"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C14_electronicos_en_contacto*",$variables["C14_electronicos_en_contacto"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C14_electronicos_sin_contacto*",$variables["C14_electronicos_sin_contacto"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C14_otro1*",$variables["C14_otro1"],"","______________",$variables["C14_otro1"]);
        $xml = $this->reemplaza($xml,"*C14_telemetria*",$variables["C14_telemetria"],"1","_X_","__");
        $xml = $this->reemplaza($xml,"*C14_otro2*",$variables["C14_otro2"],"","______________",$variables["C14_otro2"]);

        /**************************todos************************************/
        if($variables["C16_mensual"]==1){
            $xml = $this->reemplaza($xml,"*C16_mensual*",$variables["C16_mensual"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C16_bimensual*",$variables["C16_mensual"],"1","__","__");
        }
        if($variables["C16_mensual"]==2){
            $xml = $this->reemplaza($xml,"*C16_mensual*",$variables["C16_mensual"],"1","__","__");
            $xml = $this->reemplaza($xml,"*C16_bimensual*",$variables["C16_mensual"],"2","_X_","__");
        }
        /**************************************************************************************************************/

        $xml = $this->reemplaza($xml,"*C16_fecha_maxima_entrega*",$variables["C16_fecha_maxima_entrega"],"","______________",$variables["C16_fecha_maxima_entrega"]);

        $departamento->departamento_codigo($variables["dpto"]);
        $nombre_departamento=$departamento->get_dpto_nomb();

        $ciudad->municipio_codigo($variables["dpto"],$variables["mncpio"]);
        $nombre_ciudad=$ciudad->get_muni_nomb();

        $xml = $this->reemplaza($xml,"*direccion*",$variables["direccion"].$variables["txtdirEmpresa"],"",$variables["txtdirEmpresa"].$variables["direccion"],$variables["txtdirEmpresa"].$variables["direccion"]);

        $xml = $this->reemplaza($xml,"*ciudad*",$nombre_ciudad,"","______________",$nombre_ciudad);

        $xml = $this->reemplaza($xml,"*departamento*",$nombre_departamento,"","______________",$nombre_departamento);
        $xml = $this->reemplaza($xml,"*telefono*",$variables["telEmpresa"].$variables["telefono"].$variables["txtcontacto"],"","______________",$variables["telEmpresa"].$variables["telefono"].$variables["txtcontacto"]);
        $xml = $this->reemplaza($xml,"*celular*",$variables["celular"],"","______________",$variables["celular"]);
        $xml = $this->reemplaza($xml,"*nombre*",$variables["representante"].$variables["primApellido"],"","______________",$variables["representante"].$variables["primNombre"]." ".$variables["segNombre"]." ".$variables["primApellido"]." ".$variables["segApellido"]);
        $xml = $this->reemplaza($xml,"*barrio*",$variables["barrioN"].$variables["barrioJ"],"",$variables["barrioN"].$variables["barrioJ"],$variables["barrioN"].$variables["barrioJ"]);
        $xml = $this->reemplaza($xml,"*pagina*",$variables["paginaN"].$variables["paginaJ"],"",$variables["paginaN"].$variables["paginaJ"],$variables["paginaN"].$variables["paginaJ"]);

        $xml = $this->reemplaza($xml,"*C24_generales*",$variables["clausulas_generales"],"","_______________",$variables["clausulas_generales"]);
        $xml = $this->reemplaza($xml,"*C24_permanencia*",$variables["C24_permanencia"],"1","Si_X_ No___   ","Si___ No_X_   ");
        $xml = $this->reemplaza($xml,"*C25_denuncia*",$variables["C25_denuncia"],"Si","Si_X_ No___   ","Si___ No_X_   ");

        $xml = $this->reemplaza($xml,"*C25_COBERTURA_AC_LINEA_BASE*",$variables["C25_COBERTURA_AC_LINEA_BASE"],"","",$variables["C25_COBERTURA_AC_LINEA_BASE"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_AC_META1*",$variables["C25_COBERTURA_AC_META1"],"","",$variables["C25_COBERTURA_AC_META1"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_AC_META2*",$variables["C25_COBERTURA_AC_META2"],"","",$variables["C25_COBERTURA_AC_META2"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_AC_META3*",$variables["C25_COBERTURA_AC_META3"],"","",$variables["C25_COBERTURA_AC_META3"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_AC_META4*",$variables["C25_COBERTURA_AC_META4"],"","",$variables["C25_COBERTURA_AC_META4"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_AC_META5*",$variables["C25_COBERTURA_AC_META5"],"","",$variables["C25_COBERTURA_AC_META5"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_AC_META6*",$variables["C25_COBERTURA_AC_META6"],"","",$variables["C25_COBERTURA_AC_META6"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_AC_META7*",$variables["C25_COBERTURA_AC_META7"],"","",$variables["C25_COBERTURA_AC_META7"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_AC_META8*",$variables["C25_COBERTURA_AC_META8"],"","",$variables["C25_COBERTURA_AC_META8"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_AC_META9*",$variables["C25_COBERTURA_AC_META9"],"","",$variables["C25_COBERTURA_AC_META9"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_AC_META10*",$variables["C25_COBERTURA_AC_META10"],"","",$variables["C25_COBERTURA_AC_META10"]);
        $xml = $this->reemplaza($xml,"*C25_CALIDAD_AC_LINEA_BASE*",$variables["C25_CALIDAD_AC_LINEA_BASE"],"","",$variables["C25_CALIDAD_AC_LINEA_BASE"]);
        $xml = $this->reemplaza($xml,"*C25_CALIDAD_AC_META1*",$variables["C25_CALIDAD_AC_META1"],"","",$variables["C25_CALIDAD_AC_META1"]);
        $xml = $this->reemplaza($xml,"*C25_CALIDAD_AC_META2*",$variables["C25_CALIDAD_AC_META2"],"","",$variables["C25_CALIDAD_AC_META2"]);
        $xml = $this->reemplaza($xml,"*C25_CALIDAD_AC_META3*",$variables["C25_CALIDAD_AC_META3"],"","",$variables["C25_CALIDAD_AC_META3"]);
        $xml = $this->reemplaza($xml,"*C25_CALIDAD_AC_META4*",$variables["C25_CALIDAD_AC_META4"],"","",$variables["C25_CALIDAD_AC_META4"]);
        $xml = $this->reemplaza($xml,"*C25_CALIDAD_AC_META5*",$variables["C25_CALIDAD_AC_META5"],"","",$variables["C25_CALIDAD_AC_META5"]);
        $xml = $this->reemplaza($xml,"*C25_CALIDAD_AC_META6*",$variables["C25_CALIDAD_AC_META6"],"","",$variables["C25_CALIDAD_AC_META6"]);
        $xml = $this->reemplaza($xml,"*C25_CALIDAD_AC_META7*",$variables["C25_CALIDAD_AC_META7"],"","",$variables["C25_CALIDAD_AC_META7"]);
        $xml = $this->reemplaza($xml,"*C25_CALIDAD_AC_META8*",$variables["C25_CALIDAD_AC_META8"],"","",$variables["C25_CALIDAD_AC_META8"]);
        $xml = $this->reemplaza($xml,"*C25_CALIDAD_AC_META9*",$variables["C25_CALIDAD_AC_META9"],"","",$variables["C25_CALIDAD_AC_META9"]);
        $xml = $this->reemplaza($xml,"*C25_CALIDAD_AC_META10*",$variables["C25_CALIDAD_AC_META10"],"","",$variables["C25_CALIDAD_AC_META10"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ACU_LINEA_BASE*",$variables["C25_CONTINUIDAD_ACU_LINEA_BASE"],"","",$variables["C25_CONTINUIDAD_ACU_LINEA_BASE"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ACU_META1*",$variables["C25_CONTINUIDAD_ACU_META1"],"","",$variables["C25_CONTINUIDAD_ACU_META1"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ACU_META2*",$variables["C25_CONTINUIDAD_ACU_META2"],"","",$variables["C25_CONTINUIDAD_ACU_META2"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ACU_META3*",$variables["C25_CONTINUIDAD_ACU_META3"],"","",$variables["C25_CONTINUIDAD_ACU_META3"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ACU_META4*",$variables["C25_CONTINUIDAD_ACU_META4"],"","",$variables["C25_CONTINUIDAD_ACU_META4"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ACU_META5*",$variables["C25_CONTINUIDAD_ACU_META5"],"","",$variables["C25_CONTINUIDAD_ACU_META5"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ACU_META6*",$variables["C25_CONTINUIDAD_ACU_META6"],"","",$variables["C25_CONTINUIDAD_ACU_META6"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ACU_META7*",$variables["C25_CONTINUIDAD_ACU_META7"],"","",$variables["C25_CONTINUIDAD_ACU_META7"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ACU_META8*",$variables["C25_CONTINUIDAD_ACU_META8"],"","",$variables["C25_CONTINUIDAD_ACU_META8"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ACU_META9*",$variables["C25_CONTINUIDAD_ACU_META9"],"","",$variables["C25_CONTINUIDAD_ACU_META9"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ACU_META10*",$variables["C25_CONTINUIDAD_ACU_META10"],"","",$variables["C25_CONTINUIDAD_ACU_META10"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_ALCAN_LINEA_BASE*",$variables["C25_COBERTURA_ALCAN_LINEA_BASE"],"","",$variables["C25_COBERTURA_ALCAN_LINEA_BASE"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_ALCAN_META1*",$variables["C25_COBERTURA_ALCAN_META1"],"","",$variables["C25_COBERTURA_ALCAN_META1"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_ALCAN_META2*",$variables["C25_COBERTURA_ALCAN_META2"],"","",$variables["C25_COBERTURA_ALCAN_META2"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_ALCAN_META3*",$variables["C25_COBERTURA_ALCAN_META3"],"","",$variables["C25_COBERTURA_ALCAN_META3"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_ALCAN_META4*",$variables["C25_COBERTURA_ALCAN_META4"],"","",$variables["C25_COBERTURA_ALCAN_META4"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_ALCAN_META5*",$variables["C25_COBERTURA_ALCAN_META5"],"","",$variables["C25_COBERTURA_ALCAN_META5"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_ALCAN_META6*",$variables["C25_COBERTURA_ALCAN_META6"],"","",$variables["C25_COBERTURA_ALCAN_META6"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_ALCAN_META7*",$variables["C25_COBERTURA_ALCAN_META7"],"","",$variables["C25_COBERTURA_ALCAN_META7"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_ALCAN_META8*",$variables["C25_COBERTURA_ALCAN_META8"],"","",$variables["C25_COBERTURA_ALCAN_META8"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_ALCAN_META9*",$variables["C25_COBERTURA_ALCAN_META9"],"","",$variables["C25_COBERTURA_ALCAN_META9"]);
        $xml = $this->reemplaza($xml,"*C25_COBERTURA_ALCAN_META10*",$variables["C25_COBERTURA_ALCAN_META10"],"","",$variables["C25_COBERTURA_ALCAN_META10"]);
        $xml = $this->reemplaza($xml,"*C25_CALDAD_ALCAN_LINEA_BASE*",$variables["C25_CALDAD_ALCAN_LINEA_BASE"],"","",$variables["C25_CALDAD_ALCAN_LINEA_BASE"]);
        $xml = $this->reemplaza($xml,"*C25_CALDAD_ALCAN_META1*",$variables["C25_CALDAD_ALCAN_META1"],"","",$variables["C25_CALDAD_ALCAN_META1"]);
        $xml = $this->reemplaza($xml,"*C25_CALDAD_ALCAN_META2*",$variables["C25_CALDAD_ALCAN_META2"],"","",$variables["C25_CALDAD_ALCAN_META2"]);
        $xml = $this->reemplaza($xml,"*C25_CALDAD_ALCAN_META3*",$variables["C25_CALDAD_ALCAN_META3"],"","",$variables["C25_CALDAD_ALCAN_META3"]);
        $xml = $this->reemplaza($xml,"*C25_CALDAD_ALCAN_META4*",$variables["C25_CALDAD_ALCAN_META4"],"","",$variables["C25_CALDAD_ALCAN_META4"]);
        $xml = $this->reemplaza($xml,"*C25_CALDAD_ALCAN_META5*",$variables["C25_CALDAD_ALCAN_META5"],"","",$variables["C25_CALDAD_ALCAN_META5"]);
        $xml = $this->reemplaza($xml,"*C25_CALDAD_ALCAN_META6*",$variables["C25_CALDAD_ALCAN_META6"],"","",$variables["C25_CALDAD_ALCAN_META6"]);
        $xml = $this->reemplaza($xml,"*C25_CALDAD_ALCAN_META7*",$variables["C25_CALDAD_ALCAN_META7"],"","",$variables["C25_CALDAD_ALCAN_META7"]);
        $xml = $this->reemplaza($xml,"*C25_CALDAD_ALCAN_META8*",$variables["C25_CALDAD_ALCAN_META8"],"","",$variables["C25_CALDAD_ALCAN_META8"]);
        $xml = $this->reemplaza($xml,"*C25_CALDAD_ALCAN_META9*",$variables["C25_CALDAD_ALCAN_META9"],"","",$variables["C25_CALDAD_ALCAN_META9"]);
        $xml = $this->reemplaza($xml,"*C25_CALDAD_ALCAN_META10*",$variables["C25_CALDAD_ALCAN_META10"],"","",$variables["C25_CALDAD_ALCAN_META10"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ALCAN_LINEA_BASE*",$variables["C25_CONTINUIDAD_ALCAN_LINEA_BASE"],"","",$variables["C25_CONTINUIDAD_ALCAN_LINEA_BASE"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ALCAN_META1*",$variables["C25_CONTINUIDAD_ALCAN_META1"],"","",$variables["C25_CONTINUIDAD_ALCAN_META1"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ALCAN_META2*",$variables["C25_CONTINUIDAD_ALCAN_META2"],"","",$variables["C25_CONTINUIDAD_ALCAN_META2"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ALCAN_META3*",$variables["C25_CONTINUIDAD_ALCAN_META3"],"","",$variables["C25_CONTINUIDAD_ALCAN_META3"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ALCAN_META4*",$variables["C25_CONTINUIDAD_ALCAN_META4"],"","",$variables["C25_CONTINUIDAD_ALCAN_META4"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ALCAN_META5*",$variables["C25_CONTINUIDAD_ALCAN_META5"],"","",$variables["C25_CONTINUIDAD_ALCAN_META5"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ALCAN_META6*",$variables["C25_CONTINUIDAD_ALCAN_META6"],"","",$variables["C25_CONTINUIDAD_ALCAN_META6"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ALCAN_META7*",$variables["C25_CONTINUIDAD_ALCAN_META7"],"","",$variables["C25_CONTINUIDAD_ALCAN_META7"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ALCAN_META8*",$variables["C25_CONTINUIDAD_ALCAN_META8"],"","",$variables["C25_CONTINUIDAD_ALCAN_META8"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ALCAN_META9*",$variables["C25_CONTINUIDAD_ALCAN_META9"],"","",$variables["C25_CONTINUIDAD_ALCAN_META9"]);
        $xml = $this->reemplaza($xml,"*C25_CONTINUIDAD_ALCAN_META10*",$variables["C25_CONTINUIDAD_ALCAN_META10"],"","",$variables["C25_CONTINUIDAD_ALCAN_META10"]);
        $xml = $this->reemplaza($xml,"*C25_IQR_LINEA_BASE*",$variables["C25_IQR_LINEA_BASE"],"","",$variables["C25_IQR_LINEA_BASE"]);
        $xml = $this->reemplaza($xml,"*C25_IQR_META1*",$variables["C25_IQR_META1"],"","",$variables["C25_IQR_META1"]);
        $xml = $this->reemplaza($xml,"*C25_IQR_META2*",$variables["C25_IQR_META2"],"","",$variables["C25_IQR_META2"]);
        $xml = $this->reemplaza($xml,"*C25_IQR_META3*",$variables["C25_IQR_META3"],"","",$variables["C25_IQR_META3"]);
        $xml = $this->reemplaza($xml,"*C25_IQR_META4*",$variables["C25_IQR_META4"],"","",$variables["C25_IQR_META4"]);
        $xml = $this->reemplaza($xml,"*C25_IQR_META5*",$variables["C25_IQR_META5"],"","",$variables["C25_IQR_META5"]);
        $xml = $this->reemplaza($xml,"*C25_IQR_META6*",$variables["C25_IQR_META6"],"","",$variables["C25_IQR_META6"]);
        $xml = $this->reemplaza($xml,"*C25_IQR_META7*",$variables["C25_IQR_META7"],"","",$variables["C25_IQR_META7"]);
        $xml = $this->reemplaza($xml,"*C25_IQR_META8*",$variables["C25_IQR_META8"],"","",$variables["C25_IQR_META8"]);
        $xml = $this->reemplaza($xml,"*C25_IQR_META9*",$variables["C25_IQR_META9"],"","",$variables["C25_IQR_META9"]);
        $xml = $this->reemplaza($xml,"*C25_IQR_META10*",$variables["C25_IQR_META10"],"","",$variables["C25_IQR_META10"]);

        $xml = $this->reemplaza($xml,"*MAT_1_1*",$variables["MAT_1_1"],"","",$variables["MAT_1_1"]);
        $xml = $this->reemplaza($xml,"*MAT_1_2*",$variables["MAT_1_2"],"","",$variables["MAT_1_2"]);
        $xml = $this->reemplaza($xml,"*MAT_1_3*",$variables["MAT_1_3"],"","",$variables["MAT_1_3"]);
        $xml = $this->reemplaza($xml,"*MAT_1_4*",$variables["MAT_1_4"],"","",$variables["MAT_1_4"]);
        $xml = $this->reemplaza($xml,"*MAT_1_5*",$variables["MAT_1_5"],"","",$variables["MAT_1_5"]);
        $xml = $this->reemplaza($xml,"*MAT_1_6*",$variables["MAT_1_6"],"","",$variables["MAT_1_6"]);
        $xml = $this->reemplaza($xml,"*MAT_1_7*",$variables["MAT_1_7"],"","",$variables["MAT_1_7"]);
        $xml = $this->reemplaza($xml,"*MAT_1_8*",$variables["MAT_1_8"],"","",$variables["MAT_1_8"]);
        $xml = $this->reemplaza($xml,"*MAT_1_9*",$variables["MAT_1_9"],"","",$variables["MAT_1_9"]);
        $xml = $this->reemplaza($xml,"*MAT_1_10*",$variables["MAT_1_10"],"","",$variables["MAT_1_10"]);
        $xml = $this->reemplaza($xml,"*MAT_2_1*",$variables["MAT_2_1"],"","",$variables["MAT_2_1"]);
        $xml = $this->reemplaza($xml,"*MAT_2_2*",$variables["MAT_2_2"],"","",$variables["MAT_2_2"]);
        $xml = $this->reemplaza($xml,"*MAT_2_3*",$variables["MAT_2_3"],"","",$variables["MAT_2_3"]);
        $xml = $this->reemplaza($xml,"*MAT_2_4*",$variables["MAT_2_4"],"","",$variables["MAT_2_4"]);
        $xml = $this->reemplaza($xml,"*MAT_2_5*",$variables["MAT_2_5"],"","",$variables["MAT_2_5"]);
        $xml = $this->reemplaza($xml,"*MAT_2_6*",$variables["MAT_2_6"],"","",$variables["MAT_2_6"]);
        $xml = $this->reemplaza($xml,"*MAT_2_7*",$variables["MAT_2_7"],"","",$variables["MAT_2_7"]);
        $xml = $this->reemplaza($xml,"*MAT_2_8*",$variables["MAT_2_8"],"","",$variables["MAT_2_8"]);
        $xml = $this->reemplaza($xml,"*MAT_2_9*",$variables["MAT_2_9"],"","",$variables["MAT_2_9"]);
        $xml = $this->reemplaza($xml,"*MAT_2_10*",$variables["MAT_2_10"],"","",$variables["MAT_2_10"]);
        $xml = $this->reemplaza($xml,"*MAT_3_1*",$variables["MAT_3_1"],"","",$variables["MAT_3_1"]);
        $xml = $this->reemplaza($xml,"*MAT_3_2*",$variables["MAT_3_2"],"","",$variables["MAT_3_2"]);
        $xml = $this->reemplaza($xml,"*MAT_3_3*",$variables["MAT_3_3"],"","",$variables["MAT_3_3"]);
        $xml = $this->reemplaza($xml,"*MAT_3_4*",$variables["MAT_3_4"],"","",$variables["MAT_3_4"]);
        $xml = $this->reemplaza($xml,"*MAT_3_5*",$variables["MAT_3_5"],"","",$variables["MAT_3_5"]);
        $xml = $this->reemplaza($xml,"*MAT_3_6*",$variables["MAT_3_6"],"","",$variables["MAT_3_6"]);
        $xml = $this->reemplaza($xml,"*MAT_3_7*",$variables["MAT_3_7"],"","",$variables["MAT_3_7"]);
        $xml = $this->reemplaza($xml,"*MAT_3_8*",$variables["MAT_3_8"],"","",$variables["MAT_3_8"]);
        $xml = $this->reemplaza($xml,"*MAT_3_9*",$variables["MAT_3_9"],"","",$variables["MAT_3_9"]);
        $xml = $this->reemplaza($xml,"*MAT_3_10*",$variables["MAT_3_10"],"","",$variables["MAT_3_10"]);
        $xml = $this->reemplaza($xml,"*MAT_4_1*",$variables["MAT_4_1"],"","",$variables["MAT_4_1"]);
        $xml = $this->reemplaza($xml,"*MAT_4_2*",$variables["MAT_4_2"],"","",$variables["MAT_4_2"]);
        $xml = $this->reemplaza($xml,"*MAT_4_3*",$variables["MAT_4_3"],"","",$variables["MAT_4_3"]);
        $xml = $this->reemplaza($xml,"*MAT_4_4*",$variables["MAT_4_4"],"","",$variables["MAT_4_4"]);
        $xml = $this->reemplaza($xml,"*MAT_4_5*",$variables["MAT_4_5"],"","",$variables["MAT_4_5"]);
        $xml = $this->reemplaza($xml,"*MAT_4_6*",$variables["MAT_4_6"],"","",$variables["MAT_4_6"]);
        $xml = $this->reemplaza($xml,"*MAT_4_7*",$variables["MAT_4_7"],"","",$variables["MAT_4_7"]);
        $xml = $this->reemplaza($xml,"*MAT_4_8*",$variables["MAT_4_8"],"","",$variables["MAT_4_8"]);
        $xml = $this->reemplaza($xml,"*MAT_4_9*",$variables["MAT_4_9"],"","",$variables["MAT_4_9"]);
        $xml = $this->reemplaza($xml,"*MAT_4_10*",$variables["MAT_4_10"],"","",$variables["MAT_4_10"]);
        $xml = $this->reemplaza($xml,"*MAT_5_1*",$variables["MAT_5_1"],"","",$variables["MAT_5_1"]);
        $xml = $this->reemplaza($xml,"*MAT_5_2*",$variables["MAT_5_2"],"","",$variables["MAT_5_2"]);
        $xml = $this->reemplaza($xml,"*MAT_5_3*",$variables["MAT_5_3"],"","",$variables["MAT_5_3"]);
        $xml = $this->reemplaza($xml,"*MAT_5_4*",$variables["MAT_5_4"],"","",$variables["MAT_5_4"]);
        $xml = $this->reemplaza($xml,"*MAT_5_5*",$variables["MAT_5_5"],"","",$variables["MAT_5_5"]);
        $xml = $this->reemplaza($xml,"*MAT_5_6*",$variables["MAT_5_6"],"","",$variables["MAT_5_6"]);
        $xml = $this->reemplaza($xml,"*MAT_5_7*",$variables["MAT_5_7"],"","",$variables["MAT_5_7"]);
        $xml = $this->reemplaza($xml,"*MAT_5_8*",$variables["MAT_5_8"],"","",$variables["MAT_5_8"]);
        $xml = $this->reemplaza($xml,"*MAT_5_9*",$variables["MAT_5_9"],"","",$variables["MAT_5_9"]);
        $xml = $this->reemplaza($xml,"*MAT_5_10*",$variables["MAT_5_10"],"","",$variables["MAT_5_10"]);
        $xml = $this->reemplaza($xml,"*MAT_6_1*",$variables["MAT_6_1"],"","",$variables["MAT_6_1"]);
        $xml = $this->reemplaza($xml,"*MAT_6_2*",$variables["MAT_6_2"],"","",$variables["MAT_6_2"]);
        $xml = $this->reemplaza($xml,"*MAT_6_3*",$variables["MAT_6_3"],"","",$variables["MAT_6_3"]);
        $xml = $this->reemplaza($xml,"*MAT_6_4*",$variables["MAT_6_4"],"","",$variables["MAT_6_4"]);
        $xml = $this->reemplaza($xml,"*MAT_6_5*",$variables["MAT_6_5"],"","",$variables["MAT_6_5"]);
        $xml = $this->reemplaza($xml,"*MAT_6_6*",$variables["MAT_6_6"],"","",$variables["MAT_6_6"]);
        $xml = $this->reemplaza($xml,"*MAT_6_7*",$variables["MAT_6_7"],"","",$variables["MAT_6_7"]);
        $xml = $this->reemplaza($xml,"*MAT_6_8*",$variables["MAT_6_8"],"","",$variables["MAT_6_8"]);
        $xml = $this->reemplaza($xml,"*MAT_6_9*",$variables["MAT_6_9"],"","",$variables["MAT_6_9"]);
        $xml = $this->reemplaza($xml,"*MAT_6_10*",$variables["MAT_6_10"],"","",$variables["MAT_6_10"]);
        $xml = $this->reemplaza($xml,"*MAT_7_1*",$variables["MAT_7_1"],"","",$variables["MAT_7_1"]);
        $xml = $this->reemplaza($xml,"*MAT_7_2*",$variables["MAT_7_2"],"","",$variables["MAT_7_2"]);
        $xml = $this->reemplaza($xml,"*MAT_7_3*",$variables["MAT_7_3"],"","",$variables["MAT_7_3"]);
        $xml = $this->reemplaza($xml,"*MAT_7_4*",$variables["MAT_7_4"],"","",$variables["MAT_7_4"]);
        $xml = $this->reemplaza($xml,"*MAT_7_5*",$variables["MAT_7_5"],"","",$variables["MAT_7_5"]);
        $xml = $this->reemplaza($xml,"*MAT_7_6*",$variables["MAT_7_6"],"","",$variables["MAT_7_6"]);
        $xml = $this->reemplaza($xml,"*MAT_7_7*",$variables["MAT_7_7"],"","",$variables["MAT_7_7"]);
        $xml = $this->reemplaza($xml,"*MAT_7_8*",$variables["MAT_7_8"],"","",$variables["MAT_7_8"]);
        $xml = $this->reemplaza($xml,"*MAT_7_9*",$variables["MAT_7_9"],"","",$variables["MAT_7_9"]);
        $xml = $this->reemplaza($xml,"*MAT_7_10*",$variables["MAT_7_10"],"","",$variables["MAT_7_10"]);

        /*********************************** 778 ******************************************************/
            $xml = $this->reemplaza($xml,"*C01_residuo*",$variables["C01_residuo"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C01_transfer*",$variables["C01_transfer"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C01_barrido*",$variables["C01_barrido"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C01_cesped*",$variables["C01_cesped"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C01_poda*",$variables["C01_poda"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C01_lavado*",$variables["C01_lavado"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C01_playas*",$variables["C01_playas"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C01_cestar*",$variables["C01_cestar"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C01_tratam*",$variables["C01_tratam"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C01_dispfin*",$variables["C01_dispfin"],"1","_X_","__");

            $xml = $this->reemplaza($xml,"*C07_area*",$variables["C07_area"],"","______________________________",$variables["C07_area"]);

            $xml = $this->reemplaza($xml,"*email*",$variables["email"].$variables["emailEmpresa"],"",$variables["email"].$variables["emailEmpresa"],$variables["email"].$variables["emailEmpresa"]);

            $xml = $this->reemplaza($xml,"*C27_rytdrna_1_1*",$variables["C27_rytdrna_1_1"],"","",$variables["C27_rytdrna_1_1"]);
            $xml = $this->reemplaza($xml,"*C27_rytdrna_1_2*",$variables["C27_rytdrna_1_2"],"","",$variables["C27_rytdrna_1_2"]);
            $xml = $this->reemplaza($xml,"*C27_rytdrna_1_3*",$variables["C27_rytdrna_1_3"],"","",$variables["C27_rytdrna_1_3"]);
            $xml = $this->reemplaza($xml,"*C27_rytdrna_2_1*",$variables["C27_rytdrna_2_1"],"","",$variables["C27_rytdrna_2_1"]);
            $xml = $this->reemplaza($xml,"*C27_rytdrna_2_2*",$variables["C27_rytdrna_2_2"],"","",$variables["C27_rytdrna_2_2"]);
            $xml = $this->reemplaza($xml,"*C27_rytdrna_2_3*",$variables["C27_rytdrna_2_3"],"","",$variables["C27_rytdrna_2_3"]);

            $xml = $this->reemplaza($xml,"*C27_byludvyap_1_1*",$variables["C27_byludvyap_1_1"],"","",$variables["C27_byludvyap_1_1"]);
            $xml = $this->reemplaza($xml,"*C27_byludvyap_1_2*",$variables["C27_byludvyap_1_2"],"","",$variables["C27_byludvyap_1_2"]);
            $xml = $this->reemplaza($xml,"*C27_byludvyap_1_3*",$variables["C27_byludvyap_1_3"],"","",$variables["C27_byludvyap_1_3"]);
            $xml = $this->reemplaza($xml,"*C27_byludvyap_2_1*",$variables["C27_byludvyap_2_1"],"","",$variables["C27_byludvyap_2_1"]);
            $xml = $this->reemplaza($xml,"*C27_byludvyap_2_2*",$variables["C27_byludvyap_2_2"],"","",$variables["C27_byludvyap_2_2"]);
            $xml = $this->reemplaza($xml,"*C27_byludvyap_2_3*",$variables["C27_byludvyap_2_3"],"","",$variables["C27_byludvyap_2_3"]);

            $xml = $this->reemplaza($xml,"*C27_lp_1_1*",$variables["C27_lp_1_1"],"","",$variables["C27_lp_1_1"]);
            $xml = $this->reemplaza($xml,"*C27_lp_1_2*",$variables["C27_lp_1_2"],"","",$variables["C27_lp_1_2"]);
            $xml = $this->reemplaza($xml,"*C27_lp_1_3*",$variables["C27_lp_1_3"],"","",$variables["C27_lp_1_3"]);
            $xml = $this->reemplaza($xml,"*C27_lp_2_1*",$variables["C27_lp_2_1"],"","",$variables["C27_lp_2_1"]);
            $xml = $this->reemplaza($xml,"*C27_lp_2_2*",$variables["C27_lp_2_2"],"","",$variables["C27_lp_2_2"]);
            $xml = $this->reemplaza($xml,"*C27_lp_2_3*",$variables["C27_lp_2_3"],"","",$variables["C27_lp_2_3"]);

            $xml = $this->reemplaza($xml,"*C27_lap_1_1*",$variables["C27_lap_1_1"],"","",$variables["C27_lap_1_1"]);
            $xml = $this->reemplaza($xml,"*C27_lap_1_2*",$variables["C27_lap_1_2"],"","",$variables["C27_lap_1_2"]);
            $xml = $this->reemplaza($xml,"*C27_lap_1_3*",$variables["C27_lap_1_3"],"","",$variables["C27_lap_1_3"]);
            $xml = $this->reemplaza($xml,"*C27_lap_2_1*",$variables["C27_lap_2_1"],"","",$variables["C27_lap_2_1"]);
            $xml = $this->reemplaza($xml,"*C27_lap_2_2*",$variables["C27_lap_2_2"],"","",$variables["C27_lap_2_2"]);
            $xml = $this->reemplaza($xml,"*C27_lap_2_3*",$variables["C27_lap_2_3"],"","",$variables["C27_lap_2_3"]);

            /******************************873AA1**************************/
            $xml = $this->reemplaza($xml,"*C04_municipio*",$variables["C04_municipio"],"","",$variables["C04_municipio"]);
            $xml = $this->reemplaza($xml,"*telefono*",$variables["telefono"].$variables["txtcontacto"],"","",$variables["telefono"].$variables["txtcontacto"]);
            $xml = $this->reemplaza($xml,"*celular*",$variables["celular"],"","",$variables["celular"]);
            $xml = $this->reemplaza($xml,"*C04_area*",$variables["C04_area"],"","",$variables["C04_area"]);
            $xml = $this->reemplaza($xml,"*C03_urbano*",$variables["C03_urbano"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C03_rural*",$variables["C03_rural"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*nit*",$variables["nit"].$variables["txtdocumento"].$variables["documento"],"","",$variables["nit"].$variables["txtdocumento"].$variables["documento"]);
            $xml = $this->reemplaza($xml,"*txtdirEmp*",$variables["txtdirEmp"],"","",$variables["txtdirEmp"]);
            $xml = $this->reemplaza($xml,"*C04_vereda*",$variables["C04_vereda"],"","",$variables["C04_vereda"]);
            $xml = $this->reemplaza($xml,"*C04_poblado*",$variables["C04_poblado"],"","",$variables["C04_poblado"]);
            $xml = $this->reemplaza($xml,"*C02_acueducto*",$variables["C02_acueducto"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C02_alcatarillado*",$variables["C02_alcatarillado"],"1","_X_","__");            
            $xml = $this->reemplaza($xml,"*C04_departamento*",$variables["C04_departamento"],"","",$variables["C04_departamento"]);
            $xml = $this->reemplaza($xml,"*C04_corregimiento*",$variables["C04_corregimiento"],"","",$variables["C04_corregimiento"]);
            //$xml = $this->reemplaza($xml,"*C05_indefinida*",$variables["C05_indefinida"],"1","_X_","__");
            //$xml = $this->reemplaza($xml,"*C05_duracion*",$variables["C05_duracion"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C04_horario*",$variables["C04_horario"],"","",$variables["C04_horario"]);
            $xml = $this->reemplaza($xml,"*C04_cargo*",$variables["C04_cargo"],"","",$variables["C04_cargo"]);
            $xml = $this->reemplaza($xml,"*C35_diametro*",$variables["C35_diametro"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C35_diametro_minimo_acueducto*",$variables["C35_diametro_minimo_acueducto"],"","",$variables["C35_diametro_minimo_acueducto"]);
            $xml = $this->reemplaza($xml,"*C35_diametro_tmedidor*",$variables["C35_diametro_tmedidor"],"","",$variables["C35_diametro_tmedidor"]);
            $xml = $this->reemplaza($xml,"*C35_velocidad*",$variables["C35_velocidad"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C35_volumetrico*",$variables["C35_volumetrico"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C35_electromagnetico*",$variables["C35_electromagnetico"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C35_concentrico*",$variables["C35_concentrico"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C35_otro1*",$variables["C35_otro1"],"","",$variables["C35_otro1"]);
            $xml = $this->reemplaza($xml,"*C35_telemetria*",$variables["C35_telemetria"],"1","_X_","__");            
            $xml = $this->reemplaza($xml,"*C35_prepago*",$variables["C35_prepago"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C35_otro2*",$variables["C35_otro2"],"","",$variables["C35_otro2"]);
            $xml = $this->reemplaza($xml,"*C35_caudal*",$variables["C35_caudal"],"","",$variables["C35_caudal"]);
            $xml = $this->reemplaza($xml,"*C35_rango*",$variables["C35_rango"],"","",$variables["C35_rango"]);
            $xml = $this->reemplaza($xml,"*C35_alcantarillado*",$variables["C35_alcantarillado"],"","",$variables["C35_alcantarillado"]);
            $xml = $this->reemplaza($xml,"*C37_horas*",$variables["C37_horas"],"","",$variables["C37_horas"]);
            $xml = $this->reemplaza($xml,"*C37_metros*",$variables["C37_metros"],"","",$variables["C37_metros"]); 
            //($xml,$variable,$valor,$comparacion,$respuestaSi,$respuestaNo)          
            $xml = $this->reemplaza($xml,"*C38_segmento1*",$variables["C38_segmento1"],"1","_X_","___");
            $xml = $this->reemplaza($xml,"*C38_segmento2*",$variables["C38_segmento1"],"1","___","_X_");
            $xml = $this->reemplaza($xml,"*C35_micro_base*",$variables["C35_micro_base"],"","",$variables["C35_micro_base"]);
            $xml = $this->reemplaza($xml,"*C35_micro_meta*",$variables["C35_micro_meta"],"","",$variables["C35_micro_meta"]);
            $xml = $this->reemplaza($xml,"*C35_micro_meta1*",$variables["C35_micro_meta1"],"","",$variables["C35_micro_meta1"]);
            $xml = $this->reemplaza($xml,"*C35_micro_meta2*",$variables["C35_micro_meta2"],"","",$variables["C35_micro_meta2"]);
            $xml = $this->reemplaza($xml,"*C35_micro_meta3*",$variables["C35_micro_meta3"],"","",$variables["C35_micro_meta3"]);
            $xml = $this->reemplaza($xml,"*C35_micro_meta4*",$variables["C35_micro_meta4"],"","",$variables["C35_micro_meta4"]);
            $xml = $this->reemplaza($xml,"*C35_micro_meta5*",$variables["C35_micro_meta5"],"","",$variables["C35_micro_meta5"]);
            $xml = $this->reemplaza($xml,"*C35_micro_meta6*",$variables["C35_micro_meta6"],"","",$variables["C35_micro_meta6"]);
            $xml = $this->reemplaza($xml,"*C35_micro_meta7*",$variables["C35_micro_meta7"],"","",$variables["C35_micro_meta7"]);
            $xml = $this->reemplaza($xml,"*C35_continuidad_base*",$variables["C35_continuidad_base"],"","",$variables["C35_continuidad_base"]);
            $xml = $this->reemplaza($xml,"*C35_continuidad_meta1*",$variables["C35_continuidad_meta1"],"","",$variables["C35_continuidad_meta1"]);
            $xml = $this->reemplaza($xml,"*C35_continuidad_meta2*",$variables["C35_continuidad_meta2"],"","",$variables["C35_continuidad_meta2"]);
            $xml = $this->reemplaza($xml,"*C35_continuidad_meta3*",$variables["C35_continuidad_meta3"],"","",$variables["C35_continuidad_meta3"]);
            $xml = $this->reemplaza($xml,"*C35_continuidad_meta4*",$variables["C35_continuidad_meta4"],"","",$variables["C35_continuidad_meta4"]);
            $xml = $this->reemplaza($xml,"*C35_continuidad_meta5*",$variables["C35_continuidad_meta5"],"","",$variables["C35_continuidad_meta5"]);
            $xml = $this->reemplaza($xml,"*C35_continuidad_meta6*",$variables["C35_continuidad_meta6"],"","",$variables["C35_continuidad_meta6"]);
            $xml = $this->reemplaza($xml,"*C35_continuidad_meta7*",$variables["C35_continuidad_meta7"],"","",$variables["C35_continuidad_meta7"]);
            /******************************873AA2**************************/
            $xml = $this->reemplaza($xml,"*C00_persona_prestadora*",$variables["C00_persona_prestadora"],"","",$variables["C00_persona_prestadora"]);
            $xml = $this->reemplaza($xml,"*C00_nit*",$variables["C00_nit"],"","",$variables["C00_nit"]);
            $xml = $this->reemplaza($xml,"*C00_direccion*",$variables["C00_direccion"],"","",$variables["C00_direccion"]);
            $xml = $this->reemplaza($xml,"*C00_barrio*",$variables["C00_barrio"],"","",$variables["C00_barrio"]);
            $xml = $this->reemplaza($xml,"*C00_municipio*",$variables["C00_municipio"],"","",$variables["C00_municipio"]);
            $xml = $this->reemplaza($xml,"*C00_vereda*",$variables["C00_vereda"],"","",$variables["C00_vereda"]);
            $xml = $this->reemplaza($xml,"*C00_centro_poblado*",$variables["C00_centro_poblado"],"","",$variables["C00_centro_poblado"]);
            $xml = $this->reemplaza($xml,"*C00_departamento*",$variables["C00_departamento"],"","",$variables["C00_departamento"]);
            $xml = $this->reemplaza($xml,"*C00_linea*",$variables["C00_linea"],"","",$variables["C00_linea"]);
            $xml = $this->reemplaza($xml,"*C00_corregimiento*",$variables["C00_corregimiento"],"","",$variables["C00_corregimiento"]);
            $xml = $this->reemplaza($xml,"*C00_celular*",$variables["C00_celular"],"","",$variables["C00_celular"]);
            $xml = $this->reemplaza($xml,"*C00_pagina*",$variables["C00_pagina"],"","",$variables["C00_pagina"]);
            $xml = $this->reemplaza($xml,"*C00_correo*",$variables["C00_correo"],"","",$variables["C00_correo"]);
            $xml = $this->reemplaza($xml,"*C00_horario*",$variables["C00_horario"],"","",$variables["C00_horario"]);
            $xml = $this->reemplaza($xml,"*C00_cargo*",$variables["C00_cargo"],"","",$variables["C00_cargo"]);
            $xml = $this->reemplaza($xml,"*C00_interes*",$variables["C00_interes"],"","",$variables["C00_interes"]);
            $xml = $this->reemplaza($xml,"*C00_medicion*",$variables["C00_medicion"],"","",$variables["C00_medicion"]);
            $xml = $this->reemplaza($xml,"*C35_diametro1*",$variables["C35_diametro1"],"","",$variables["C35_diametro1"]);
            $xml = $this->reemplaza($xml,"*diametro_min_ac*",$variables["diametro_min_ac"],"","",$variables["diametro_min_ac"]);
            $xml = $this->reemplaza($xml,"*diametro_min_alc*",$variables["diametro_min_alc"],"","",$variables["diametro_min_alc"]);
            $xml = $this->reemplaza($xml,"*diametro8732*",$variables["diametro8732"],"","",$variables["diametro8732"]);
            $xml = $this->reemplaza($xml,"*C35_rango_med*",$variables["C35_rango_med"],"","",$variables["C35_rango_med"]);

            /******************************894-1**************************/

            $xml = $this->reemplaza($xml,"*C02_primer_segmento*",$variables["C02_primer_segmento"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C02_segundo_segmento*",$variables["C02_segundo_segmento"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C02_tercer_segmento*",$variables["C02_tercer_segmento"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C02_dif_acceso*",$variables["C02_dif_acceso"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C02_dif_aps*",$variables["C02_dif_aps"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C03_rec_trans*",$variables["C03_rec_trans"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C03_transferencia*",$variables["C03_transferencia"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C03_barrylimp*",$variables["C03_barrylimp"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C03_corte*",$variables["C03_corte"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C03_poda*",$variables["C03_poda"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C03_lavado*",$variables["C03_lavado"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C03_playas*",$variables["C03_playas"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C03_cestas*",$variables["C03_cestas"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C03_tratamiento*",$variables["C03_tratamiento"],"1","_X_","__");
            $xml = $this->reemplaza($xml,"*C03_disposicion*",$variables["C03_disposicion"],"1","_X_","__");

            $xml = $this->reemplaza($xml,"*C17-1_macro1_1*",$variables["C17-1_macro1_1"],"","",$variables["C17-1_macro1_1"]);
            $xml = $this->reemplaza($xml,"*C17-1_macro1_2*",$variables["C17-1_macro1_2"],"","",$variables["C17-1_macro1_2"]);
            $xml = $this->reemplaza($xml,"*C17-1_macro1_3*",$variables["C17-1_macro1_3"],"","",$variables["C17-1_macro1_3"]);
            $xml = $this->reemplaza($xml,"*C17-1_macro2_1*",$variables["C17-1_macro2_1"],"","",$variables["C17-1_macro2_1"]);
            $xml = $this->reemplaza($xml,"*C17-1_macro2_2*",$variables["C17-1_macro2_2"],"","",$variables["C17-1_macro2_2"]);
            $xml = $this->reemplaza($xml,"*C17-1_macro2_3*",$variables["C17-1_macro2_3"],"","",$variables["C17-1_macro2_3"]);
            
            $xml = $this->reemplaza($xml,"*C17-2_macro1_1*",$variables["C17-2_macro1_1"],"","",$variables["C17-2_macro1_1"]);
            $xml = $this->reemplaza($xml,"*C17-2_macro1_2*",$variables["C17-2_macro1_2"],"","",$variables["C17-2_macro1_2"]);
            $xml = $this->reemplaza($xml,"*C17-2_macro1_3*",$variables["C17-2_macro1_3"],"","",$variables["C17-2_macro1_3"]);
            $xml = $this->reemplaza($xml,"*C17-2_macro2_1*",$variables["C17-2_macro2_1"],"","",$variables["C17-2_macro2_1"]);
            $xml = $this->reemplaza($xml,"*C17-2_macro2_2*",$variables["C17-2_macro2_2"],"","",$variables["C17-2_macro2_2"]);
            $xml = $this->reemplaza($xml,"*C17-2_macro2_3*",$variables["C17-2_macro2_3"],"","",$variables["C17-2_macro2_3"]);

            $xml = $this->reemplaza($xml,"*C17-3_macro1_1*",$variables["C17-3_macro1_1"],"","",$variables["C17-3_macro1_1"]);
            $xml = $this->reemplaza($xml,"*C17-3_macro1_2*",$variables["C17-3_macro1_2"],"","",$variables["C17-3_macro1_2"]);
            $xml = $this->reemplaza($xml,"*C17-3_macro1_3*",$variables["C17-3_macro1_3"],"","",$variables["C17-3_macro1_3"]);
            $xml = $this->reemplaza($xml,"*C17-3_macro2_1*",$variables["C17-3_macro2_1"],"","",$variables["C17-3_macro2_1"]);
            $xml = $this->reemplaza($xml,"*C17-3_macro2_2*",$variables["C17-3_macro2_2"],"","",$variables["C17-3_macro2_2"]);
            $xml = $this->reemplaza($xml,"*C17-3_macro2_3*",$variables["C17-3_macro2_3"],"","",$variables["C17-3_macro2_3"]);

            $xml = $this->reemplaza($xml,"*C17-4_macro1_1*",$variables["C17-4_macro1_1"],"","",$variables["C17-4_macro1_1"]);
            $xml = $this->reemplaza($xml,"*C17-4_macro1_2*",$variables["C17-4_macro1_2"],"","",$variables["C17-4_macro1_2"]);
            $xml = $this->reemplaza($xml,"*C17-4_macro1_3*",$variables["C17-4_macro1_3"],"","",$variables["C17-4_macro1_3"]);
            $xml = $this->reemplaza($xml,"*C17-4_macro2_1*",$variables["C17-4_macro2_1"],"","",$variables["C17-4_macro2_1"]);
            $xml = $this->reemplaza($xml,"*C17-4_macro2_2*",$variables["C17-4_macro2_2"],"","",$variables["C17-4_macro2_2"]);
            $xml = $this->reemplaza($xml,"*C17-4_macro2_3*",$variables["C17-4_macro2_3"],"","",$variables["C17-4_macro2_3"]);
             $xml = $this->reemplaza($xml,"*C02_aps*",$variables["C02_aps"],"","",$variables["C02_aps"]);

            /******************************894-2**************************/
            $xml = $this->reemplaza($xml,"*C00_area*",$variables["C00_area"],"","",$variables["C00_area"]);
            $xml = $this->reemplaza($xml,"*tecnicas_tratamiento*",$variables["tecnicas_tratamiento"],"","",$variables["tecnicas_tratamiento"]);
            $xml = $this->reemplaza($xml,"*medios_alternos*",$variables["medios_alternos"],"","",$variables["medios_alternos"]);
            $xml = $this->reemplaza($xml,"*acueducto_continuidad*",$variables["acueducto_continuidad"],"","",$variables["acueducto_continuidad"]);
            $xml = $this->reemplaza($xml,"*calidad_agua*",$variables["calidad_agua"],"","",$variables["calidad_agua"]);
            $xml = $this->reemplaza($xml,"*continuidad*",$variables["continuidad"],"","",$variables["continuidad"]);
            $xml = $this->reemplaza($xml,"*C15_macro1_1*",$variables["C15_macro1_1"],"","",$variables["C15_macro1_1"]);
            $xml = $this->reemplaza($xml,"*C15_macro1_2*",$variables["C15_macro1_2"],"","",$variables["C15_macro1_2"]);
            $xml = $this->reemplaza($xml,"*C15_macro1_3*",$variables["C15_macro1_3"],"","",$variables["C15_macro1_3"]);
            
        if ($zip->addFromString('content.xml', $xml)) {
            echo 'File written!'; 
        }
        else { echo 'File not written.  Go back and add write permissions to this folder!l'; }

        $zip->close();

        /********************************FIN DOCX_EDITOR*************************************************/

        /********************************CONVIERTE ODT A PDF ***************************************/

        $originFilePath = BODEGA."/" . $anio . "/" . $carpeta . "/" . $radicado;
        
        $outputDirPath  = BODEGA."/" . $anio . "/" . $carpeta . "/";

        Unoconv::convertToPdf($originFilePath, $outputDirPath);

        // Assume all went well; write the PDF output to a file
        // Another example might stream it to the user
        
        /********************************FIN CONVIERTE DOCX A PDF ***************************************/

        return 3;
    }

    public function getInfoUsuario($usuarioRadica, $usuarioDestino)
    {
        $usuar = array();
        $usuar["dependenciaRadica"] = $usuarioRadica["DEPE_CODI"];
        $usuar["dependenciaDestino"] = $usuarioDestino["DEPE_CODI"];
        $usuar["docUsuaRadica"] = $usuarioDestino["USUA_DOC"];
        $usuar["docUsuaDestino"] = $usuarioRadica["USUA_DOC"];
        $usuar["usuaDestinoCod"] = $usuarioDestino["USUA_CODI"];
        $usuar["usuaRadicaCod"] = $usuarioRadica["USUA_CODI"];
        $usuar["usuaDestino"] = $usuarioDestino["USUA_LOGIN"];
        $usuar["usuaRadica"] = $usuarioRadica["USUA_LOGIN"];
        
        return $usuar;
    }
}

?>
