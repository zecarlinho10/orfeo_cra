
<?php
$ruta_raiz = "../..";
include "$ruta_raiz/config.php";
require_once('../clases/crud_actuacion.php');
require_once('../clases/actuacion.php');

include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );
	
	$idactuacion=$_GET["txtActuacion"];

	$objCrudActuaciones = new CrudActuacion($db);
	$objActuacion=$objCrudActuaciones->getActuacion($idactuacion);
		
		$actuacion="<table>
	<tr>
		<td>
			<table>
				<tr>
					<td><strong>Fecha de Radicado (Entrada)</strong></td>
					<td>" . $objActuacion->getFechaInicio() . "</td>
				</tr>
				<tr>
					<td><strong>Número Expediente</strong></td>
					<td>" . $objActuacion->getExpediente() . "</td>
				</tr>
					<td><strong>Nombre del Expediente</strong></td>
					<td>" . $objActuacion->getNombreExpediente($db) . "</td>
				<tr>
					<td><strong>Experto Coordinador</strong></td>
					<td>" . $objActuacion->getNombreExperto($db) . "</td>
				</tr>
				<tr>
					<td><strong>Profesional o asesor de la OAJ que apoyan la actuación</strong></td>
					<td>" . $objActuacion->getAsesorOAJ($db) . "</td>
				</tr>
					<td><strong>Profesional o asesor SR que apoyan la actuación</strong></td>
					<td>" . $objActuacion->getAsesorSR($db) . "</td>
				</tr>
				<tr>
					<td><strong>VENCIMIENTO</strong></td>
					<td>" . $objActuacion->getFechaFin() . "</td>
				</tr>
			</table>
		</td>
		<td>
			<table>
				<tr>
					<td><strong>Nombre de la Actuación</strong></td>
					<td>" . $objActuacion->getNombre() . "</td>
				</tr>
				<tr>
					<td><strong>Estado</strong></td>
					<td>" . $objActuacion->getEstado() . "</td>
				</tr>
				<tr>
					<td><strong>Prestador</strong></td>
					<td>" . $objActuacion->getPrestador($db) . "</td>
				</tr>
				<tr>
					<td><strong>Objetivo</strong></td>
					<td>" . $objActuacion->getObjetivo() . "</td>
				</tr>
				<tr>
					<td><strong>Tipo de trámite</strong></td>
					<td>" . $objActuacion->getNobreTipoTramite($db) . "</td>
				</tr>
				<tr>
					<td><strong>Observación</strong></td>
					<td>" . $objActuacion->getObservacion() . "</td>
				</tr>
				<tr>
					<td><strong>Equipo de trabajo</strong></td>
					<td>" . $objActuacion->getEquipoDeTrabajo($db) . "</td>
				</tr>
				<tr>
					<td><strong>Administrador</strong></td>
					<td>" . $objActuacion->getAdministrador($db) . "</td>
				</tr>
			</table>
		</td>
	</tr>

</table>";
		/*
		$actuacion="<table>";
						$actuacion.="<tr><td><strong>Fecha de Radicado (Entrada)</strong></td><td>";
						$actuacion.=$objActuacion->getFechaInicio() . "</td></tr><tr><td><strong>Número Expediente</strong></td><td>";
						$actuacion.=$objActuacion->getExpediente() . "</td></tr><tr><td><strong>Nombre del Expediente</strong></td><td>";
						$actuacion.=$objActuacion->getNombreExpediente($db) . "</td></tr><tr><td><strong>Experto Coordinador</strong></td><td>";
						$actuacion.=$objActuacion->getNombreExperto($db) . "</td></tr><tr><td><strong>Profesional o asesor de la OAJ que apoyan la actuación</strong></td><td>";
						$actuacion.=$objActuacion->getAsesorOAJ($db) . "</td></tr><tr><td><strong>Profesional o asesor SR que apoyan la actuación</strong></td><td>";
						$actuacion.=$objActuacion->getAsesorSR($db) . "</td></tr><tr><td><strong>VENCIMIENTO</strong></td><td>";
						$actuacion.=$objActuacion->getFechaFin() . "</td></tr></table>";
		*/
		print $actuacion;

?>


