<?php
$ruta_raiz = "../..";
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );

$db = new ConnectionHandler( "$ruta_raiz" );


require_once('../clases/crud_actividad.php');
require_once('../clases/actividad.php');
require_once('../clases/terceros.php');


$objTerceros = new Terceros($db);

$objCrudActividades = new CrudActividades($db);
$vector=$objCrudActividades->getActividades();




?>

<script>
    

    function inactivaFunction() {
      // No focus = Changes the background color of input
      x=document.getElementById("radicado").value;
      
      if(x == ""){
          document.getElementById("idEncargado").disabled=false;
        document.getElementById("inicio").disabled=false;
        document.getElementById("fin").disabled=false;
      }
      else{
          document.getElementById("idEncargado").disabled=true;
        document.getElementById("inicio").disabled=true;
        document.getElementById("fin").disabled=true;
        document.getElementById("idEncargado").value = "";
        document.getElementById("inicio").value = "";
        document.getElementById("fin").value = "";
      }
    }
  </script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>.:.Modifica Actividades.:.</title>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <body>
    <form id="actualidarDatos">
    <div class="modal fade" id="dataUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
      <div class="modal-dialog" role="document">
        
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">Modificar Actividad:</h4>
          </div>
          <div class="modal-body">
          <div id="datos_ajax"></div>
              <div class="form-group">
                <input type="hidden" class="form-control" id="id" name="id">
              </div>
              <div id="actividad_ajax"></div>
              <div class="form-group">
                <label for="radicado" class="control-label">Radicado:</label>
                <input type="text" class="form-control" id="radicado" name="radicado" maxlength="14" size="15" 
                onblur='inactivaFunction()'>
              </div>
              
              <div class="form-group">
                <label for="idEncargado" class="control-label">Funcionario encargado:</label>
                <select data-placeholder='Seleccione una opcion'
                    class='form-control'  id='idEncargado' name='idEncargado'>
                    <option value=''>-- SELECCIONE --</option>
                    <?php
                      foreach($objTerceros->getFuncionarios() as $d){
                        ?>
                          <option value="<? echo $d->getId(); ?>" ><? echo $d->getNombre(); ?></option>
                        <?
                      }
                    ?>
               </select>
              </div>
              <div class="form-group">
                <label for="inicio" class="control-label">Fecha Inicio:</label>
                <input type="date" class="form-control" id="inicio" name="inicio" maxlength="15">
              </div>
              <div class="form-group">
                <label for="fin" class="control-label">Fecha Fin:</label>
                <input type="date" class="form-control" id="fin" name="fin" maxlength="15">
              </div>
              <div class="form-group">
                <label for="observacion" class="control-label">Observacion:</label>
                <textarea class='form-control' 
                    name='observacion' id='observacion'
                    type='textarea' rows='3' cols='200' required>
                </textarea>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="location.reload();">Cerrar</button>
            <button type="submit" class="btn btn-primary">Actualizar datos</button>
          </div>
        </div>
      </div>
    </div>
    </form>
  </body>
</html>