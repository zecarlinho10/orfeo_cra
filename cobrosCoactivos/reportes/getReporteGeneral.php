
<?php

$ruta_raiz = "../..";
//include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
include_once ('../../js/funtionImage.php');

$db = new ConnectionHandler( "$ruta_raiz" );


require_once('../clases/cobro.php');
require_once('../clases/crud_cobro.php');
require_once('../clases/crud_actividad.php');
//require_once('../clases/terceros.php');

//$objTerceros = new Terceros($db);
$objCrudActividades = new CrudActividades($db);
$objCrudCobro = new CrudCobro($db);

$estadoR=$_POST["estado"];
	$salida= "<table border='1'><div class='row form-group'>
			<div >
				<label class='desc desc control-label etiqueta control-label label-success form-control' for='txtFechaFinalizacion'>Actividades
				</label>
			</div>
		   </div>
	<tr>
		<td><strong>No</strong></td>
		<td><strong>Expediente</strong></td>
		<td><strong>Prestador</strong></td>
		<td><strong>Ident.</strong></td>
		<td><strong>Vigencia</strong></td>
		<td><strong>Resolucion</strong></td>
		<td><strong>Valor</strong></td>
		<td><strong>Interes</strong></td>
		<td><strong>Abono Capital</strong></td>
		<td><strong>Abono Interes</strong></td>
		<td><strong>Fecha prescripcion</strong></td>
		<td><strong>Estado</strong></td>
		
	</tr><tr>";
	$cCobros = 0;
	foreach($objCrudCobro->getCobrosXestado($estadoR) as $d){
		$cCobros ++;
		if($d->getEstado()==0) $salidaEstado="Inctivo";
		else if ($d->getEstado()==1) $salidaEstado="Activo";
		else if ($d->getEstado()==2) $salidaEstado="Suspendido";
		else if ($d->getEstado()==3) $salidaEstado="Acumulado";
		else $salidaEstado="Error";

	  	$vectorActividades=$objCrudActividades->getActividadesOrdenadasxFecha($d->getId());

	  	if(empty($vectorActividades)){
	  		$salida .= "<td>" . $cCobros . "</td>
							<td>" . $d->getExpediente() . "</td>
							<td>" . $d->getEmpresa()->getNombre() . "</td>
							<td>" . $d->getEmpresa()->getNit() . "</td><td></td><td></td><td></td>
						    <td></td><td></td><td></td><td></td>
						    <td>" . $salidaEstado . "</td>
						</tr>
						<tr>";
	  	}
	  	else {
			foreach($vectorActividades as $objActividad){
				//$objActividad->getIdCobroActividad();
				
				$salida .= "<td>" . $cCobros . "</td>
							<td>" . $d->getExpediente() . "</td>
							<td>" . $d->getEmpresa()->getNombre() . "</td>
							<td>" . $d->getEmpresa()->getNit() . "</td>
							<td>".$objActividad->getVigencia()."</td>
						    <td>".$objActividad->getResolucion()."</td>
						    <td>".$objActividad->getValor()."</td>
						    <td>".$objActividad->getInteres()."</td>
						    <td>".$objActividad->getInteresAbonoCapital()."</td>
							<td>".$objActividad->getAbonoIntereses()."</td>
							<td>".$objActividad->getFechaPrescripcion()."</td>
						    <td>" . $salidaEstado . "</td>
						</tr>
						<tr>";
			}
		}
	}	
	$salida .="</table>";
	

	print $salida;
?>


