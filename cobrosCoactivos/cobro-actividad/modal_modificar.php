<?php

$ruta_raiz = "../..";
//include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );

$db = new ConnectionHandler( "$ruta_raiz" );

require_once('../clases/crud_actividad.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>.:.Modifica Obligaciones a Cobrar.:.</title>
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
            <h4 class="modal-title" id="exampleModalLabel">Modificar Obligación:</h4>
          </div>
          <div class="modal-body">
    			<div id="datos_ajax"></div>
              <div class="form-group">
                <input type="hidden" class="form-control" id="id" name="id">
              </div>
    		      <div class="form-group">
                <label for="resolucion" class="control-label">Resolucion:</label>
                <input type="text" class="form-control" id="resolucion" name="resolucion" size="30">
              </div>
              <div class="form-group">
                <label for="valor" class="control-label">Valor:</label>
                <input type="text" class="form-control" id="valor" name="valor" size="30">
              </div>
              <div class="form-group">
                <label for="interes" class="control-label">Interes:</label>
                <input type="text" class="form-control" id="interes" name="interes" size="30">
              </div>
              <div class="form-group">
                <label for="capital" class="control-label">Abono Capital:</label>
                <input type="text" class="form-control" id="capital" name="capital" size="30">
              </div>
              <div class="form-group">
                <label for="abointer" class="control-label">Abono Interes:</label>
                <input type="text" class="form-control" id="abointer" name="abointer" size="30">
              </div>
    		      <div class="form-group">
                <label for="fecha" class="control-label">Fecha Prescripcion:</label>
                <input type="date" class="form-control" id="fecha" name="fecha" maxlength="15">
              </div>
              <div class="form-group">
                <label for="vigencia" class="control-label">Vigencia:</label>
                <input type="text" class="form-control" id="vigencia" name="vigencia" size="30">
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="location.reload(0);">Cerrar</button>
            <button type="submit" class="btn btn-primary">Actualizar datos</button>
          </div>
        </div>
      </div>
    </div>
    </form>
  </body>
</html>