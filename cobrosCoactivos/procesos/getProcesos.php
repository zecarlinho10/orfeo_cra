
<?php

$ruta_raiz = "../..";
//include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
include_once ('../../js/funtionImage.php');

$db = new ConnectionHandler( "$ruta_raiz" );

require_once('../clases/crud_proceso.php');
require_once('../clases/terceros.php');

$objTerceros = new Terceros($db);
$objCrudProceso = new CrudProceso($db);


		
	print "
		
		<table><div class='row form-group'>
			<div >
				<label class='desc desc control-label etiqueta control-label label-success form-control' for='txtFechaFinalizacion'>Procesos
				</label>
			</div>
		   </div>
	<tr>
		<td><strong>FECHA</strong></td>
		<td><strong>DESCPRICIÓN</strong></td>
		<td><strong>RADICADO</strong></td>
		<td><strong>REQUIERE NOTIFICACIÓN</strong></td>
		<td><strong>ACTO ADMINISTRATIVO</strong></td>
		<td><strong>OBSERVACION</strong></td>
		
		<td><strong>Actualizar</strong></td>
		<td><strong>Eliminar</strong></td>
	</tr>";

	
	$vidCobro=$_GET["txtCobro"];
	$vectorProcesos=$objCrudProceso->getProcesosOrdenadasxFecha($vidCobro);
	
	foreach($vectorProcesos as $objProceso){
		
		$n=$objProceso->getIdCobroProceso();
		if($objProceso->getActo() == 0) $acto="N.A."; 
		else if($objProceso->getActo() == 1) $acto="Auto";
		else $acto="Resolucion"; 
		if($objProceso->getNotificacion() == 0) $noti="No"; else  $noti="Si";
		
		print "<tr>
				    <td>".$objProceso->getFecha()."</td>
				    <td>".$objProceso->getDescripcion()."</td>
				    <td>".$objProceso->getRadicado()."</td>
					<td>". $noti ."</td>
				    <td>".$acto."</td>
				    <td>".$objProceso->getObservacion()."</td>
				    <td><button type='button' class='btn btn-info' data-toggle='modal' data-target='#dataUpdate' data-id='".$n."' data-codigo='".$n."' data-fecha='".$objProceso->getFecha()."' data-descripcion='".$objProceso->getDescripcion()."' data-radicado='".$objProceso->getRadicado()."' data-notificacion='".$objProceso->getNotificacion()."' data-acto='".$objProceso->getActo()."' data-observacion='".$objProceso->getObservacion()."' ></i> Modificar</button>
				    </td>
				    <td><a type='button' class='btn btn-info' name='btnElimina" . $n . "' id='btnElimina" . $n . "' onclick='eliminaProceso(".$n.")'><center><img src='../images/eliminar.png' /></center></a> 
					</td>
				</tr
				<tr>";
		}
	
	//$texto ="</table>

	$texto ="<table>;
	<tr>
		<td width='50' style = 'white-space: nowrap'><strong>FECHA</strong></td>
		<td><strong>DESCPRICIÓN</strong></td>
		<td><strong>RADICADO</strong></td>
		<td><strong>REQUIERE NOTIFICACIÓN</strong></td>
		<td><strong>ACTO ADMINISTRATIVO</strong></td>
		<td><strong>OBSERVACION</strong></td>
	</tr>
	<tr>	
			<td><input class='form-control obligatorio' name='txtFecha' id='txtFecha' type='date'/></td>
			<td>
				<input class='form-control' value='' name='txtDescripcion' id='txtDescripcion' type='text' />
			</td>
			<td>
				<input class='form-control' value='' name='txtRadicado' id='txtRadicado' type='text' onblur='validaRadicadoJ()'/>
				<div id='resultadoRadicado'></div>
			</td>
			<td>
				<select id='txtNotificacion' name='txtNotificacion' required>
					<option value=''>-- SELECCIONE --</option>
					<option value='1'>Si</option>
					<option value='0'>No</option>
				</select>
			</td>
			<td>
				<select id='txtActo' name='txtActo' required>
					<option value=''>-- SELECCIONE --</option>
					<option value='1'>Auto</option>
					<option value='2'>Resolucion</option>
					<option value='0'>N.A.</option>
				</select>
			</td>
			
			<td colspan='3'>
				<input class='form-control' value='' name='txtObservacion' id='txtObservacion' type='text' />
			</td>
	</tr></table><div id='resultado'></div>";

	print $texto;
?>


