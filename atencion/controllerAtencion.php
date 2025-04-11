<?php
session_start();
$path_raiz = realpath(dirname(__FILE__) . "/../");
require_once ($path_raiz . "/clasesComunes/Divipola.php");
require_once ($path_raiz . "/clasesComunes/BuscarDestinatario.php");
require_once ($path_raiz . "/clasesComunes/Dependencia.php");
require_once ($path_raiz . "/clasesComunes/TiposDocumentos.php");
require_once $path_raiz . '/atencion/CargarArchivo.php';

$divipola = new Divipola();
$destinatario = new BuscarDestinatario();
$dependencia = new Dependencia();
$tipoIdentificacion = new TipoIdentificacion();
switch ($_POST["op"]) {
    case "buscarDepto":
        $paisCodi = $_POST["pais"];
        $salida = $divipola->getDepto($paisCodi);
        $sal = $salida->GetMenu2('depto', "", ":Seleccione un Departamento", false, false, " id='depto' class='chosen-select select required'", false);
        echo $sal;
        break;
    case "buscarPais":
        $salida = $divipola->getPaises();
        $sal = $salida->GetMenu2('pais', "", ":Seleccione un País", false, false, " id='pais' class='chosen-select select' title='campo Obligatorio' required", false);
        echo $sal;
        break;
    case "buscarMunicipio":
        $deptoCodi = $_POST["dpto"];
        $salida = $divipola->getMucipio($deptoCodi);
        $sal = $salida->GetMenu2('mcpio', "", ":Seleccione un Municipio", false, false, " id='mcpio' class='chosen-select select required'", false);
        echo $sal;
        break;
    
    
/*************************/
    case "buscarEmpresaXnit":
        $nit = $_POST["nit"];
        $rs = $destinatario->buscarEmpresasXnits($nit);
        $i = 0;
        while (! $rs->EOF) {
            $salida["data"][$i] = $rs->fields;
            $i ++;
            $rs->MoveNext();
        }

        $salida["success"] = count($salida["data"]) > 0;
        echo json_encode($salida);
        break;

    case "buscarEmpresa":
        $txtnit = $_POST["nit"];
        $rs = $destinatario->buscarEmpresas($txtnit);
        $i = 0;
        while (! $rs->EOF) {
            $salida["data"][$i] = $rs->fields;
            $i ++;
            $rs->MoveNext();
        }

        $salida["success"] = count($salida["data"]) > 0;
        echo json_encode($salida);
        break;

    case "buscarEmpresaXnombre":
        $noEmpresa = $_POST["noEmpresa"];
        $rs = $destinatario->buscarEmpresaXnombres($noEmpresa);
        $i = 0;
        while (! $rs->EOF) {
            $salida["data"][$i] = $rs->fields;
            $i ++;
            $rs->MoveNext();
        }
        $salida["success"] = count($salida["data"]) > 0;
        echo json_encode($salida);
        break;    
/*************************/
    case "buscarPersona":
        $identificacion = $_POST["identificacion"];
        $rs = $destinatario->buscarPersonaNatural($identificacion);
        $i = 0;
        while (! $rs->EOF) {
            $salida["data"][$i] = $rs->fields;
            $i ++;
            $rs->MoveNext();
        }

        $salida["success"] = count($salida["data"]) > 0;
        echo json_encode($salida);
        break;

    case "buscarPersonaXnombre":
        $apellido1 = $_POST["apellido1"];
        $rs = $destinatario->buscarPersonaNaturalXnombre($apellido1);
        $i = 0;
        while (! $rs->EOF) {
            $salida["data"][$i] = $rs->fields;
            $i ++;
            $rs->MoveNext();
        }

        $salida["success"] = count($salida["data"]) > 0;
        echo json_encode($salida);
        break;

/*************************/
    case "buscarEsp":
        $identificacion = $_POST["identificacion"];
        $rs = $destinatario->buscarESP($identificacion);
        $i = 0;
        while (! $rs->EOF) {
            $salida["data"][$i] = $rs->fields;
            
            $i ++;
            $rs->MoveNext();
        }
        $salida["success"] = count($salida["data"]) > 0;
        echo json_encode($salida);
        break;
    case "listaDependencia":
        $salida = $dependencia->getDependenciasBasico();
        $sal = $salida->GetMenu2('dependencia', "", ":Seleccione una Dependencia", false, false, " id='dependencia' class='chosen-select select ' required title='debe seleccionar la dependencia'", false);
        echo $sal;
        break;
    case "radicar":
        try {
            $documento = array(
                "asunto" => $_POST["asunto"],
                "contenido" => $_POST["editor1"],
                "tipoRemitente" => 6
            );
            $radicado = $controlador->radicar($_POST["usuario"], $_POST["firma"], $documento, $_SESSION, 2);
        } catch (OrfeoException $ex) {
            print $ex->getMessage();
        }
        $verrad = $radicado;
        include ($orf->getPathRoot() . "/verradicado.php");
        break;
    case "listaTiposDoc":
        $salida = $tipoIdentificacion->getListaTiposCiudano();
        $sal = $salida->GetMenu2('tipoDocumento', "", ":Seleccione un Tipo de Documento", false, false, " id='tipoDocumento' class='chosen-select select'  title='be Seleccionar un Tipo de Documento el es obligatorio'", false);
        echo $sal;
        break;
    case "listaTiposEmpresas":
        $salida = $tipoIdentificacion->getListaTiposEmpresas();
        $sal = $salida->GetMenu2('tipoEmpresa', "", ":Seleccione un Tipo de Empresa", false, false, " id='tipoDocumento' class='chosen-select select'  title='be Seleccionar un Tipo de Empresa el campo es obligatorio'", false);
        echo $sal;
        break;
    case "listaTiposESP":
        $salida = $tipoIdentificacion->getListaTiposEsp();
        $sal = $salida->GetMenu2('tipoESP', "", ":Seleccione un Tipo de ESP", false, false, " id='tipoDocumento' class='chosen-select select'  title='be Seleccionar un Tipo de ESP el campo es obligatorio'", false);
        echo $sal;
        break;
        
    case "buscarEntidad":
        if (! empty($_POST["tipo"])) {
            $identificacion = $_POST["identificacion"];
            if ($_POST["tipo"] == "esp") {
                $rs = $destinatario->buscarESP($identificacion,10);
            } else 
                if ($_POST["tipo"] == "oem") {
                    $rs = $destinatario->buscarEmpresas($identificacion,10);
                }
            $i = 0;
            while (! $rs->EOF) {
                $salida["data"][$i] = $rs->fields;
                $i ++;
                $rs->MoveNext();
            }
            $salida["success"] = count($salida["data"]) > 0;
            echo json_encode($salida);
        }
        break;
    case "crearEntidad":
        $dest = $destinatario->generaDestinatario($_POST);
        echo json_encode($dest);
        break;

    default:
        break;
}
?>
