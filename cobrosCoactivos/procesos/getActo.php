<?php


	/*Inicia validacion del lado del servidor*/

	if (empty($_POST['notificacion'])) {
         $salida= "
        <div class='form-group'>
            <label for='notificacion' class='control-label'>notificacion:</label>
                <select data-placeholder='Seleccione una opcion' title='Debe Seleccionar notificacion, este campo  es Obligatorio' 
                 class='chosen-select form-control'  id='notificacion' name='notificacion' required>
		         	<option value='0'>No</option>
		            <option value='1' >Si</option>
        		</select>
        </div>"; 
	} else {
		if($_POST['notificacion'] == 0) {
            $acto= "<option value='0' selected>No</option>
            <option value='1' >Si</option>"; 
        }
		else {
            $acto= "<option value='0' >No</option>
					<option value='1' selected>Si</option>"; 
        }
		$salida = "
			<div class='form-group'>
                <label for='notificacion' class='control-label'>Notificacion:</label>
                <select data-placeholder='Seleccione una opcion' title='Debe Seleccionar notificacion, este campo  es Obligatorio' 
                    class='chosen-select form-control'  id='notificacion' name='notificacion' required>" . 
                    $acto . "
               </select>
            </div>";
	}

	if (empty($_POST['acto'])) {
         $salida.= "
        <div class='form-group'>
            <label for='acto' class='control-label'>Acto:</label>
                <select data-placeholder='Seleccione una opcion' title='Debe Seleccionar Acto, este campo  es Obligatorio' 
                 class='chosen-select form-control'  id='acto' name='acto' required>
		         	<option value='0'>N.A.</option>
		            <option value='1' >Auto</option>
		            <option value='2' >Resolucion</option>
        		</select>
        </div>"; 
	} else {
		if($_POST['acto'] == 0) {
            $acto= "<option value='0' selected>N.A.</option>
            <option value='1' >Auto</option>
            <option value='2' >Resolucion</option>"; 
        }
        else if($_POST['acto'] == 1) {
                      		$acto= "<option value='0' >N.A.</option>
                      				<option value='1' selected>Auto</option>
                      				<option value='2' >Resolucion</option>";           		
        }
		else {
            $acto= "<option value='0' >N.A.</option>
                      				<option value='1' >Auto</option>
                      				<option value='2' selected>Resolucion</option>"; 
        }
		$salida .= "
			<div class='form-group'>
                <label for='acto' class='control-label'>Acto:</label>
                <select data-placeholder='Seleccione una opcion' title='Debe Seleccionar Acto, este campo  es Obligatorio' 
                    class='chosen-select form-control'  id='acto' name='acto' required>" . 
                    $acto . "
               </select>
            </div>";
	}
	print $salida;
?>