
<?php

$ruta_raiz = "../..";
//include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
include_once ('../../js/funtionImage.php');

$db = new ConnectionHandler( "$ruta_raiz" );

require_once('../clases/crud_actividad.php');

$objCrudActividades = new CrudActividades($db);

	print "<table><div class='row form-group'>
			<div >
				<label class='desc desc control-label etiqueta control-label label-success form-control' for='txtFechaFinalizacion'>Actividades
				</label>
			</div>
		   </div>
	<tr>

		<td><strong>Resolucion</strong></td>
		<td><strong>Valor</strong></td>
		<td><strong>Interes</strong></td>
		<td><strong>Abono Capital</strong></td>
		<td><strong>Abono Interes</strong></td>
		<td><strong>Fecha prescripcion</strong></td>
		<td><strong>Vigencia</strong></td>
		
		<td><strong>Actualizar</strong></td>
		<td><strong>Eliminar</strong></td>
	</tr>";

	$vidCobro=$_GET[txtCobro];
	$vectorActividades=$objCrudActividades->getActividadesOrdenadasxFecha($vidCobro);

	foreach($vectorActividades as $objActividad){
		$n=$objActividad->getIdCobroActividad();

		print "<tr>
				    <td>".$objActividad->getResolucion()."</td>
				    <td>".$objActividad->getValor()."</td>
				    <td>".$objActividad->getInteres()."</td>
				    <td>".$objActividad->getInteresAbonoCapital()."</td>
					<td>".$objActividad->getAbonoIntereses()."</td>
					<td>".$objActividad->getFechaPrescripcion()."</td>
				    <td>".$objActividad->getVigencia()."</td>

				    <td><button type='button' class='btn btn-info' data-toggle='modal' data-target='#dataUpdate' 
				    	data-id='".$n."' 
				    	data-codigo='".$n."' 
				    	data-resolucion='".$objActividad->getResolucion()."' 
				    	data-valor='".$objActividad->getValor()."' 
				    	data-interes='".$objActividad->getInteres()."' 
				    	data-capital='".$objActividad->getInteresAbonoCapital()."' 
				    	data-abointer='".$objActividad->getAbonoIntereses()."' 
				    	data-fecha='".$objActividad->getFechaPrescripcion()."' 
				    	data-vigencia='".$objActividad->getVigencia()."' >
				    <i class='glyphicon glyphicon-edit'></i> Modificar</button>
				    </td>
				    <td><a type='button' name='btnElimina" . $n . "' id='btnElimina" . $n . "' onclick='eliminaActividad(".$n.")'><center><img src='../images/eliminar.png' /></center></a> 
					</td>
				</tr
				<tr>";
		}

	$texto ="</table>
	<table>
	<tr>
		<td><strong>Resolucion</strong></td>
		<td><strong>Valor</strong></td>
		<td><strong>Interes</strong></td>
		<td><strong>Abono Capital</strong></td>
		<td><strong>Abono Interes</strong></td>
		<td width='50' style = 'white-space: nowrap'><strong>Fecha prescripcion</strong></td>
		<td><strong>VIGENCIA</strong></td>
	</tr>
	<tr>
			<td>
				<input class='form-control' value='' name='txtResolucion' id='txtResolucion' 
				type='text' />
			</td>
			<td>
				<input class='form-control' value='0' name='txtValor' id='txtValor' 
				type='text' />
			</td>
			<td>
				<input class='form-control' value='0' name='txtInteres' id='txtInteres' 
				type='text' />
			</td>
			<td>
				<input class='form-control' value='0' name='txtInteresAbonoCapital' id='txtInteresAbonoCapital' 
				type='text' />
			</td>
			<td>
				<input class='form-control' value='0' name='txtAbonoInteres' id='txtAbonoInteres' 
				type='text' />
			</td>
			<td><input class='form-control obligatorio' name='txtFechaPrescripcion' id='txtFechaPrescripcion' type='date'/></td>
			<td colspan='3'>
				<input class='form-control' value='' name='txtVigencia' id='txtVigencia' 
				type='text' />
			</td>
	</tr></table>";

	print $texto;
?>


