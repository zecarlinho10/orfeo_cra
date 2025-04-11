
<?php
$ruta_raiz = "../..";
//include "$ruta_raiz/config.php";
require_once('../clases/crud_cobro.php');
require_once('../clases/cobro.php');

include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );
	
	$idcobro=$_GET[txtCobro];

	$objCrudCobros = new CrudCobro($db);
	$objCobro=$objCrudCobros->getCobro($idcobro);
		
		$cobro="<table>
	<tr>
		<td>
			<table>
				<tr>
					<td><strong>Fecha de Radicado (Entrada)</strong></td>
					<td>" . $objCobro->getId()"</td>
				</tr>
				<tr>
					<td><strong>Número Expediente</strong></td>
					<td>" . $objCobro->getExpediente() . "</td>
				</tr>
					<td><strong>Nombre del Expediente</strong></td>
					<td>" . $objCobro->getEmpresa()->getNombre() . "</td>
				<tr>
					<td><strong>Experto Coordinador</strong></td>
					<td>" . $objCobro->getEmpresa()->getNombre() . "</td>
				</tr>
				<tr>
					<td><strong>Profesional o asesor de la OAJ que apoyan la actuación</strong></td>
					<td>" . $objCobro->getEmpresa()->getNombre() . "</td>
				</tr>
					<td><strong>Profesional o asesor SR que apoyan la actuación</strong></td>
					<td>" . $objCobro->getEmpresa()->getNombre() . "</td>
				</tr>
				<tr>
					<td><strong>VENCIMIENTO</strong></td>
					<td>" . $objCobro->getEmpresa()->getNombre() . "</td>
				</tr>
			</table>
		</td>
		<td>
			<table>
				<tr>
					<td><strong>Nombre de la Actuación</strong></td>
					<td>" . $objCobro->getEmpresa()->getNombre() . "</td>
				</tr>
				<tr>
					<td><strong>Estado</strong></td>
					<td>" . $objCobro->getExpediente() . "</td>
				</tr>
				<tr>
					<td><strong>Prestador</strong></td>
					<td>" . $objCobro->getExpediente() . "</td>
				</tr>
				<tr>
					<td><strong>Objetivo</strong></td>
					<td>" . $objCobro->getExpediente() . "</td>
				</tr>
				<tr>
					<td><strong>Tipo de trámite</strong></td>
					<td>" . $objCobro->getExpediente() . "</td>
				</tr>
				<tr>
					<td><strong>Observación</strong></td>
					<td>" . $objCobro->getExpediente() . "</td>
				</tr>
				<tr>
					<td><strong>Equipo de trabajo</strong></td>
					<td>" . $objCobro->getExpediente() . "</td>
				</tr>
				<tr>
					<td><strong>Administrador</strong></td>
					<td>" . $objCobro->getExpediente() . "</td>
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
		print $cobro;

?>


