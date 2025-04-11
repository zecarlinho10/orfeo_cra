
<?php
$ruta_raiz = "../..";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
include_once ('../../js/funtionImage.php');

$db = new ConnectionHandler( "$ruta_raiz" );

require_once('../clases/crud_actividad.php');
require_once('../clases/actividad.php');
require_once('../clases/terceros.php');

$objTerceros = new Terceros($db);
$objCrudActividades = new CrudActividades($db);

	print "<table><div class='row form-group'>
			<div >
				<label class='desc desc control-label etiqueta control-label label-success form-control' for='txtFechaFinalizacion'>Actividades
				</label>
			</div>
		   </div>
	<tr>
		<td><strong>Actividad</strong></td>
		<td><strong>Radicado</strong></td>
		<td><strong>Usuario Encargado</strong></td>
		<td width='50' style = 'white-space: nowrap'><strong>Inicia</strong></td>
		<td width='10'><strong>Vence</strong></td>
		<td><strong>Observacion Actividad</strong></td>
		<td><strong>Respuesta(s)</strong></td>
		<td><strong>Finalizado</strong></td>
		<td><strong>Actualizar</strong></td>
		<td><strong>Eliminar</strong></td>
	</tr>";
	$idActuacion=$_GET["txtActuacion"];
	$vectorActividaes=$objCrudActividades->getActividadesOrdenadasxFecha($_GET["txtActuacion"]);
        if(!empty($vectorActividaes )){
	foreach($vectorActividaes as $objActividad){
		$n=$objActividad->getIdActuacionActividad();
		if($objActividad->getRadicado()){
			$link="funlinkArchivo('".$objActividad->getRadicado()."','".$ruta_raiz."');";
		}

		print "<tr>
				    <td>".$objActividad->getDescActividad()."</td>
				    <td><a class=\"vinculos\" href=\"#2\" onclick=\" ".$link."\">".$objActividad->getRadicado()."</a></td>
				    
				    <td>".$objActividad->getUsuaLogin()."</td>
				    <td>".$objActividad->getFechaInicio()."</td>
					<td>".$objActividad->getFechaFinal()."</td>
					<td>
						<textarea class='form-control' 
							name='txtObservacion" . $n . "' id='txtObservacion" . $n . "'
							type='textarea' rows='3' cols='200'>" . $objActividad->getDescripcion() . "</textarea>
						</td>
					<td>".$objActividad->getRespuestas()."</td>";
			if ($objActividad->getEstado()==0) {
				$checked="";
			}
			else{
				$checked="checked";	
			}
			
			
			$actualiza="<td><button type='button' class='btn btn-info' data-toggle='modal' data-target='#dataUpdate' data-id='".$n."' data-codigo='".$n."' data-nombre='".$objActividad->getDescActividad()."' data-id_actividad='".$objActividad->getIdActividad()."' data-radicado='".$objActividad->getRadicado()."' data-idEncargado='".$objActividad->getUsuaLogin()."' data-inicio='".$objActividad->getFechaInicio()."' data-fin='".$objActividad->getFechaFinal()."' data-observacion='".$objActividad->getDescripcion()."' ><i class='glyphicon glyphicon-edit'></i> Modificar</button></td>";

			$check="<td><input type='checkbox' name='txtFinalizado".$n."' id='txtFinalizado".$n."' value='".$n."' ".$checked." onclick='checkear(".$n.",".$objActividad->getIdActividad().")'></td>";
			$check.=$actualiza."<td><a type='button' name='btnElimina" . $n . "' id='btnElimina" . $n . "' onclick='eliminaActividad(".$n.")'><center><img src='../images/eliminar.png' /></center></a> </td>
				</tr>";

			
			print $check;
		}
               }

	

	$texto ="</table>
	<table>
	<tr>
		<td><strong>Actividad</strong></td>
		<td colspan='2'><strong>Radicado</strong></td>
		<td colspan='2'><strong>Usuario Encargado</strong></td>
		<td><strong>Inicia</strong></td>
		<td><strong>Vence</strong></td>
		<td colspan='3'><strong>Observacion</strong></td>
	</tr>
	<tr>
	<td class='col-sm-12 col-md-3'>
		<select data-placeholder='Seleccione una opcion' title='Debe Seleccionar Actividad, este campo  es Obligatorio' 
				class='chosen-select form-control'  id='txtActividad' name='txtActividad' required>
				<option value=''>-- SELECCIONE --</option>";
		foreach($objCrudActividades->getActividades() as $d){
			$texto .= "<option value='" .  $d->getIdActividad() . "'>Fase: " . $d->getFase() . " " . $d->getNombreFase() . " - Actividad: " . $d->getDescripcion() . "</option>";
		}			

	$texto .= "</select>
	</td>
		<td colspan='2'>
			<input class='form-control' value='' maxlength='14' tabindex='4' 
				minlenght='13' name='txtRadicado' id='txtRadicado' 
				type='tel' onblur='blurFunction()'/>
			</td>
			<td colspan='2'>
				<select data-placeholder='Seleccione una opcion' id='txtFuncionario' name='txtFuncionario' class='chosen-select form-control'>
					<option value=''>-- SELECCIONE --</option>";

					foreach($objTerceros->getFuncionarios() as $d){
						$texto .= "<option value='" .  $d->getId() . "'>" . $d->getNombre() . "</option>";
					}
					$texto .= "</select>
			</td>
			<td><input class='form-control obligatorio' value='' maxlength='15' minlenght='10' name='txtFechaInicio' tabindex='4' id='txtFechaInicio' type='date'/></td>
			<td><input class='form-control obligatorio' value='' maxlength='15' minlenght='10' name='txtFechaFin' tabindex='4' id='txtFechaFin' type='date'/></td>
			<td colspan='3'>
				<textarea class='form-control' name='txtObservacion' id='txtObservacion' type='textarea' rows='1' cols='100' autofocus></textarea>
			</td>
	</tr></table>";

	print $texto;
?>


