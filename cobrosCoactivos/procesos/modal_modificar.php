<?php

$ruta_raiz = "../..";
//include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );

$prueba = $_POST['acto'];

$db = new ConnectionHandler( "$ruta_raiz" );

?>

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
            <h4 class="modal-title" id="exampleModalLabel">Modificar Actividad: <?php echo "prueba:" . $prueba; ?></h4>
          </div>
          <div class="modal-body">
    			<div id="datos_ajax"></div>
              <div class="form-group">
                <input type="hidden" class="form-control" id="id" name="id">
              </div>
              <div class="form-group">
                <label for="fecha" class="control-label">Fecha:</label>
                <input type="date" class="form-control" id="fecha" name="fecha" maxlength="15">
              </div>
    		      <div class="form-group">
                <label for="descripcion" class="control-label">Descripción:</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" size="30">
              </div>
              <div class="form-group">
                <label for="radicado" class="control-label">Radicado:</label><div id="resultado"></div>
                <input type="text" class="form-control" id="radicado" name="radicado" size="30" onblur='radicadoFunction()'>
              </div>
    		      <div id="acto_ajax"></div>
              <div class="form-group">
                <label for="observacion" class="control-label">Observación:</label>
                <input type="text" class="form-control" id="observacion" name="observacion" size="30">
              </div>
              
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="history.go(0);">Cerrar</button>
            <button type="submit" class="btn btn-primary">Actualizar datos</button>
          </div>
        </div>
      </div>
    </div>
    </form>
  </body>
</html>