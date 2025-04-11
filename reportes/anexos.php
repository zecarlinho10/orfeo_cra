<?php
session_start();

//error_reporting(E_ALL);
/**
 * Modulo de inventario de archivos RADICADOS.
 * @autor CRA - Carlos Ricaurte cricaurte@cra.gov.co
 *
 * @author Carlos Ricaurte 2022
 *         @fecha 2022/10
 *         @CRA
 *         @licencia GNU/GPL V2
 *        
 */
array_filter($_POST, 'trim');

$ruta_raiz = "..";
$ADODB_COUNTRECS = false;

require_once (realpath(dirname(__FILE__) . "/../") . "/include/db/ConnectionHandler.php");
include_once  realpath(dirname(__FILE__) . "/../") . "/atencion/conf_form.php";

$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
$errorFormulario = 0;

?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <title>::INVENTARIO ARCHIVOS</title>
    
</head>

<body>
    <table class='table table-bordered'><thead>
        <tr>
            <th>#</th>
            <th>ID</th>
            <th>DEPENDENCIA</th>
            <th>DESCRIPCION DEPENDENCIA</th>
            <th>SERIE</th>
            <th>DESCRIPCION SERIE</th>
            <th>SUBSERIE</th>
            <th>DESCRIPCION SUBSERIE</th>
            <th>RADICADO</th>
            <th>PATH RADICADO</th>
        </tr></thead>

        <tbody>


<?php

$sql="SELECT M.SGD_MRD_CODIGO, M.DEPE_CODI, D.DEPE_NOMB, M.SGD_SRD_CODIGO,SGD_SRD_DESCRIP, 
       M.SGD_SBRD_CODIGO, SS.SGD_SBRD_DESCRIP, T.RADI_NUME_RADI, A.ANEX_CODIGO, A.ANEX_NOMB_ARCHIVO
FROM FLDOC.SGD_MRD_MATRIRD M,  FLDOC.DEPENDENCIA D, FLDOC.SGD_SRD_SERIESRD S, 
     FLDOC.SGD_SBRD_SUBSERIERD SS, FLDOC.SGD_RDF_RETDOCF T, FLDOC.RADICADO R, FLDOC.ANEXOS A
WHERE M.DEPE_CODI=D.DEPE_CODI AND M.SGD_SRD_CODIGO=S.SGD_SRD_CODIGO AND
      M.SGD_SRD_CODIGO=SS.SGD_SRD_CODIGO AND M.SGD_SBRD_CODIGO=SS.SGD_SBRD_CODIGO AND 
      D.DEPE_CODI<900 AND T.SGD_MRD_CODIGO = M.SGD_MRD_CODIGO AND 
      A.ANEX_RADI_NUME=T.RADI_NUME_RADI
ORDER BY M.DEPE_CODI, M.SGD_SRD_CODIGO, M.SGD_SBRD_CODIGO";



$rs = $db->conn->query($sql);
$cont=1;
while(!$rs->EOF){
    $id = $rs->fields["SGD_MRD_CODIGO"];
    $depe_codi = $rs->fields["DEPE_CODI"];
    $depe_nomb = $rs->fields["DEPE_NOMB"];
    $cod_serie = $rs->fields["SGD_SRD_CODIGO"];
    $desc_serie = $rs->fields["SGD_SRD_DESCRIP"];
    $cod_sserie = $rs->fields["SGD_SBRD_CODIGO"];
    $desc_sserie = $rs->fields["SGD_SBRD_DESCRIP"];
    $radicado = $rs->fields["RADI_NUME_RADI"];
    $radi_path = $rs->fields["ANEX_NOMB_ARCHIVO"];
    $anex_codi = $rs->fields["ANEX_CODIGO"];
    

$file = "./bodega/".substr(trim($anex_codi),0,4)."/".substr($anex_codi, 5, 2)."/docs/".trim($radi_path);

    $salida="
        <tr>
            <th scope='row'>".$cont."</th>
            <td>".$id."</td>
            <td>".$depe_codi."</td>
            <td>".$depe_nomb."</td>
            <td>".$cod_serie."</td>
            <td>".$desc_serie."</td>
            <td>".$cod_sserie."</td>
            <td>".$desc_sserie."</td>
            <td>".$radicado."</td>
            <td>".$file."</td>
        </tr>";
    $cont++;
    echo $salida;
    $rs->MoveNext();
}
?>
        </tbody>
    </table>
</body>

</html>