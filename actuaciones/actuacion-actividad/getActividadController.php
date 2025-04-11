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

$id_actividad=$_POST['id_actividad'];
	
         $salida= "
        <label for='id_actividad' class='control-label'>Actividad:</label>
                <select data-placeholder='Seleccione una opcion' title='Debe Seleccionar Actividad, este campo  es Obligatorio' 
                    class='chosen-select form-control'  id='id_actividad' name='id_actividad' required>
                    <option value='0' selected>-- SELECCIONE --</option>";
 
                      foreach($vector as $d){
                        $sel = "";
                        if($id_actividad==$d->getIdActividad()) $sel = "selected";
                          $salida .= "<option value=".$d->getIdActividad()." ".$sel. ">Fase: " . $d->getFase() . " " . $d->getNombreFase()  . " - Actividad: " . $d->getDescripcion(); 
                          
                          $salida .= "</option>";

                      }
               $salida .= "</select>";
	
	print $salida;
?>