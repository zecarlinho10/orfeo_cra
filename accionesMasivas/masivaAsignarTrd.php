<?
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$depeActu=$_SESSION['dependencia'];
$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$codusuario = $_SESSION["codusuario"];
$usua_doc = $_SESSION["usua_doc"];

$ruta_raiz = "..";

require_once (realpath(dirname(__FILE__) . "/../") . "/include/db/Connection/Connection.php");
require_once (realpath(dirname(__FILE__) . "/../") . "/include/FuncionesDb.php");

$db = new ConnectionHandler("$ruta_raiz");

//$radicados=$_GET['radicados'];


if (!$_POST['tipos_id']){
  //$radicados=explode(",", $_GET['radicados']);
  
  $sql = "SELECT DEPE_NOMB, DEPE_CODI AS DEPE_CODI FROM DEPENDENCIA
        WHERE DEPENDENCIA_ESTADO=1
        ORDER BY DEPE_NOMB";
  //$db->conn->debug=true;
  $rsDep = $db->conn->Execute($sql);
  

  $rs_dep=$db->query("SELECT DEPE_CODI, DEPE_NOMB FROM dependencia WHERE DEPE_ESTADO=1 ORDER BY DEPE_NOMB");
  $raidDependencias = array();
  $i=0;
  while(!empty($rs_dep) && !$rs_dep->EOF){ 
    $raidDependencias[$i]=$rs_dep->fields; 
    $i++;
    $rs_dep->MoveNext ();
  }
}
else{
  $radicados=explode(",", $_POST['iradicados']);
}


?>

<!DOCTYPE html>
<html>
<head>

<title>.:.Asignar TRD masiva.:.</title>
  
  <!-- <link rel="stylesheet" type="text/css" href="./accionesMasivas/bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="./accionesMasivas/jquery.min.js"></script>

 
-->

<script> 

function confirmation() {
    if(confirm("Realmente desea Tipificar documentos?"))
    {
        return true;
    }
    return false;
}
</script>

</head>
<body>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <div class="container">
    <div class="row">
      <div class="col-md-6">
      <h1>Tipificar radicados masivamente</h1>
      </div>
    </div>
    <div class="row">
    <div class="col-md-6">
    <!--<form method="post" action="./accionesMasivas/requestAjax/masivaIncluirExp.php" onsubmit="return confirmation()">-->
    <?php
      if($depeActu==900 or $depeActu==321){
        if (!$_POST['tipos_id']){
        ?>
          <form method="post" action="accionesMasivas/masivaAsignarTrd.php" onsubmit="return confirmation()">
            <input type="hidden" name="iradicados" id="iradicados" value=<?php echo($_GET['radicados'])  ?> />
            <div class="form-group">
              <label for="name1">Dependencia</label>
              <?php 
              if($depeActu==900 or $depeActu==321) {
              ?>
              <select id="dependencia_id" class="form-control" name="dependencia_id" required>
                <option value="">-- SELECCIONE --</option>
                <?php foreach($raidDependencias as $d):?>
                      <option value="<?php echo $d["DEPE_CODI"] ?>"><?php echo $d["DEPE_NOMB"] ?></option>
                <?php endforeach; ?>
              </select>
              <?
              }
              else{
              ?>
                <select id="dependencia_id" class="form-control" name="dependencia_id" required>
                <?php foreach($raidDependencias as $d):
                  if($d["DEPE_CODI"]==$depeActu){?>
                      <option value="<?php echo $d["DEPE_CODI"] ?>"><?php echo $d["DEPE_NOMB"] ?></option>
                <?php } endforeach; ?>
              </select>
              <?
              echo '<script>
                      $.get("./accionesMasivas/get_series.php", "dependencia_id=" + '. $depeActu . ', function(data) {
                        $("#series_id").html(data);
                      });
                    </script>'; 
                }
              ?>
            </div>

            <div class="form-group">
              <label for="name1">Series</label>
              <select id="series_id" class="form-control" name="series_id" required>
                <option value="">-- SELECCIONE --</option>
             </select>
            </div>

            <div class="form-group">
              <label for="name2">Sub Series</label>
              <select id="subseries_id" class="form-control" name="subseries_id" required>
                <option value="">-- SELECCIONE --</option>
             </select>
            </div>

            <div class="form-group">
              <label for="name2">Tipo Documental</label>
              <select id="tipos_id" class="form-control" name="tipos_id" required>
                <option value="">-- SELECCIONE --</option>
             </select>
            </div>

            <button type="submit" class="btn btn-default">Enviar</button>
          </form>
        <?php
        }
        else{
          include_once "$ruta_raiz/include/tx/Historico.php";
          include_once ("$ruta_raiz/class_control/TipoDocumental.php");

          $trd = new TipoDocumental($db);
          $Historico = new Historico($db);

          foreach ($radicados as $radicado) {
            
            
            $datos = explode('-', $_POST['tipos_id']);
            $codigoMRD=$datos[1];
            $codiTRD=$datos[0];
            $codiTMP[0]=$codigoMRD;
            //echo "<br>codigoMRD:" . $codigoMRD . " actualizado codiTRD" . $codiTRD . " - " . $radicado . " - " . $dependencia . " - " . $codusuario;
            $radicados = $trd->insertarTRD($codiTMP,$codigoMRD,$radicado,$dependencia, $codusuario,$codiTRD);
            $sql = "UPDATE RADICADO SET TDOC_CODI=$codiTRD WHERE RADI_NUME_RADI = '$radicado'";
            //$db->conn->debug=true;
            $rsDep = $db->conn->Execute($sql);
            $observa="Tipificació masiva de Documentos";
            $codiRegH[0]=$radicado;
            $radiModi = $Historico->insertarHistorico($codiRegH, $dependencia, $codusuario, $dependencia, $codusuario, $observa, 32); 
            //$radiUp = $trd->actualizarTRD($codiRegH,$tdoc);
            echo "<br>Radicado $radicado tipidicado exitosamente."; 
          }
        }
      }
      else
      {
        echo "NO tiene permisos para esta acción";
      }
    ?>
    </div>
    </div>
  </div>


<script type="text/javascript">
  $(document).ready(function(){
    $("#dependencia_id").change(function(){
      $.get("./accionesMasivas/get_series.php","dependencia_id="+$("#dependencia_id").val(), function(data){
          $("#series_id").html(data);
      });
    });
  
    $("#series_id").change(function(){
      $.get("./accionesMasivas/get_subseries.php","dependencia_id="+$("#dependencia_id").val()+"&series_id="+$("#series_id").val(), function(data){
        $("#subseries_id").html(data);
      });
    });

    $("#subseries_id").change(function(){
      $.get("./accionesMasivas/get_tipos.php","dependencia_id="+$("#dependencia_id").val()+"&series_id="+$("#series_id").val()+"&subseries_id="+$("#subseries_id").val(), function(data){
        $("#tipos_id").html(data);
      });
    });
  });
</script>
</body>
</html>
