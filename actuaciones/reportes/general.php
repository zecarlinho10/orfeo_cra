<?php
error_reporting(0);
session_start();
if (empty($_SESSION["krd"])) {
    if (empty($_REQUEST["krd"])) {
        header('Location: ../../login.php');
    }
    include "../../rec_session.php";
}
$ruta_raiz = "../../";
$fecha = date("Ymdhis");

require_once realpath(dirname(__FILE__) . "/../../") . "/atencion/AtencionTipos.php";
require_once realpath(dirname(__FILE__) . "/../../") . "/atencion/conf_form.php";
$_SESSION["idFormulario"] = sha1(microtime(true) . mt_rand(10000, 90000));


include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

$ID = $_SESSION["ID"];
$isql = "SELECT AUTG_ID FROM AUTM_MEMBRESIAS M WHERE AUTU_ID=$ID";

$rs = $db->query($isql);

while (! $rs->EOF) {
    if($rs->fields["AUTG_ID"]==530) {
      $ID=-1;
    }
    $rs->MoveNext();
}
require_once('../clases/crud_actuacion.php');
require_once('../clases/crud_actividad.php');

$objCrudActividades = new CrudActividades($db);
$objCrudActuaciones = new CrudActuacion($db);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>.:.Reporte actuaciones activas.:.</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!--Deshabilitar modo de compatiblidad de Internet Explorer-->

<script src="js/tableToExcel.js"></script>

<link rel="stylesheet" href="../../estilos/bootstrap.min.css"
	type="text/css" />
<link rel="stylesheet"
	href="../../estilos/CRA.bootstrap.min.css?jashd=<?php echo date("ymdhmi")?>"
	type="text/css" />

<!-- CSS -->
<link href="../../estilos/jquery-ui.css" rel="stylesheet">
	
	<style>
		table.negro {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  border: 1px solid #000000;
		}

		table.gris {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  border: 1px solid #999999;
		}

		table.gris td, th {
		  border: 1px solid #dddddd;
		  text-align: left;
		  padding: 8px;
		}

		table.gris tr:nth-child(even) {
		  background-color: #dddddd;
		}

	</style>

<?php
	
?>

</head>
<body>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3>Reporte general</h3>
		</div>
		<form action="ficheroExcel.php" method="post" target="_blank" rel="noopener noreferrer" id="FormularioExportacion">
		  <div class="panel-body">
		  	<input type="button" onclick="tableToExcel('testTable', 'Reporte')" value="Exportar a Excel">
			<table id="testTable" class='negro' border="2">
				<?php
					
					foreach($objCrudActuaciones->getActuaciones(-1) as $d){
						$actuacion="<tr><td><table class='gris' border='2' ><tr><td><strong>Fecha de Radicado (Entrada)</strong></td><td>";
						$actuacion.=$d->getFechaInicio() . "</td></tr><tr><td><strong>Número Expediente</strong></td><td>";
						$actuacion.=$d->getExpediente() . "</td></tr><tr><td><strong>Nombre del Expediente</strong></td><td>";
						$actuacion.=$d->getNombreExpediente($db) . "</td></tr><tr><td><strong>Experto Coordinador</strong></td><td>";
						$actuacion.=$d->getNombreExperto($db) . "</td></tr><tr><td><strong>Profesional o asesor de la OAJ que apoyan la actuación</strong></td><td>";
						$actuacion.=$d->getAsesorOAJ($db) . "</td></tr><tr><td><strong>Profesional o asesor SR que apoyan la actuación</strong></td><td>";
						$actuacion.=$d->getAsesorSR($db) . "</td></tr><tr><td><strong>ADMINISTRADOR</strong></td><td>";
						$actuacion.=$d->getAdministrador($db) . "</td></tr><tr><td><strong>VENCIMIENTO</strong></td><td>";
						$actuacion.=$d->getFechaFin() . "</td></tr><tr><td><strong>EQUIPO DE TRABAJO</strong></td><td>";
						$actuacion.=$d->getEquipoDeTrabajo($db) . "</td></tr></table></td>";

						echo $actuacion;

						$activiades="<td>
										<table class='gris' border='1'>
											<tr>
												<td><strong>Actividad</strong></td>
												<td><strong>Radicado</strong></td>
												<td><strong>Usuario Encargado</strong></td>
												<td><strong>Fecha inicio</strong></td>
												<td><strong>Fecha Vencimiento</strong></td>
												<td><strong>Observacion</strong></td>
												<td><strong>Respuesta(s)</strong></td>
												<td><strong>Finalizado</strong></td>
											</tr>";

						echo $activiades;

						$sql0="SELECT AA.ID_ACTUACION_ACTIVIDAD, AA.IDACTUACION, AA.IDACTIVIDAD, AA.DESCRIPCION, AA.RADICADO, AA.IDENCARGADO, AA.FECHA_INICIO, AA.FECHA_FINAL,
									 A.DESCRIPCION AS DESCACTIVIDAD, USUA_LOGIN, AA.ESTADO
								FROM ACT_ACTUACION_ACTIVIDAD AA
					      			LEFT JOIN USUARIO U ON U.ID=AA.IDENCARGADO,
					      			ACT_ACTIVIDAD A
								WHERE AA.IDACTIVIDAD=A.IDACTIVIDAD AND AA.IDACTUACION ='".$d->getId()."'
								ORDER BY AA.ID_ACTUACION_ACTIVIDAD";

						$rs0=$db->query($sql0);
						while (! $rs0->EOF) {
							$ESTADO=$rs0->fields['ESTADO'];
							if ($ESTADO==0) {
								$checked="<td>No</td>";
								$tr="<tr style='color:red;'>";
							}
							else{
								$checked="<td>Si</td>";
								$tr="<tr>";
							}
							$ultimaColumna=$checked;

							if($rs0->fields['FECHA_INICIO'] && $rs0->fields['FECHA_FINAL']){
								
								echo $tr."  <td>".$rs0->fields['DESCACTIVIDAD']."</td>
										    <td></td>
										    <td>".$rs0->fields['USUA_LOGIN']."</td>
										    <td>".$rs0->fields['FECHA_INICIO']."</td>
											<td>".$rs0->fields['FECHA_FINAL']."</td>
											<td>".$rs0->fields['DESCRIPCION']."</td>
											<td></td>
											" . $ultimaColumna . "
										  </tr>";
							}
							else{
								$sql="SELECT ID_ACTUACION_ACTIVIDAD, IDACTUACION, AA.IDACTIVIDAD, AA.DESCRIPCION AS DESACTACTIVIDAD, A.DESCRIPCION AS DESCACTIVIDAD, RADICADO, R.RADI_FECH_RADI, R.FECH_VCMTO, USUA_LOGIN, AA.ESTADO
										FROM ACT_ACTUACION_ACTIVIDAD AA 
										LEFT JOIN RADICADO R ON AA.RADICADO=R.RADI_NUME_RADI,
										ACT_ACTIVIDAD A, USUARIO U
										WHERE AA.IDACTIVIDAD=A.IDACTIVIDAD AND R.RADI_DEPE_ACTU = U.DEPE_CODI AND R.RADI_USUA_ACTU = U.USUA_CODI AND 
										ID_ACTUACION_ACTIVIDAD ='".$rs0->fields['ID_ACTUACION_ACTIVIDAD']."'";

								$rs=$db->query($sql);
								while (! $rs->EOF) {
									$ID_ACTUACION_ACTIVIDAD=$rs->fields['ID_ACTUACION_ACTIVIDAD'];
									$IDACTUACION=$rs->fields['IDACTUACION'];
									$IDACTIVIDAD=$rs->fields['IDACTIVIDAD'];
									$DESCACTIVIDAD=$rs->fields['DESCACTIVIDAD'];
									$DESACTACTIVIDAD=$rs->fields['DESACTACTIVIDAD'];
									$RADICADO=$rs->fields['RADICADO'];
									$RADI_FECH_RADI=$rs->fields['RADI_FECH_RADI'];
									$FECH_VCMTO=$rs->fields['FECH_VCMTO'];
									$USUA_LOGIN=$rs->fields['USUA_LOGIN'];
									$ESTADO=$rs->fields['ESTADO'];
									
									$sql2="SELECT RADI_NUME_RADI, RADI_FECH_RADI FROM RADICADO
											WHERE RADI_NUME_DERI = $RADICADO";
									$rs2=$db->query($sql2);
									$van=0;
									$sprint="<td><div>";
									while (! $rs2->EOF) {
										$van=1;
										$sprint.="<div>" . $rs2->fields['RADI_NUME_RADI'] . " - " . $rs2->fields['RADI_FECH_RADI'] . "</div>" ;
										$rs2->MoveNext();
									}
									$sprint.="</div></td>";

									if ($ESTADO==0) {
										$checked="<td>No</td>";
										$tr="<tr style='color:red;'>";
									}
									else{
										$checked="<td>Si</td>";	
										$tr="<tr>";
									}
									$ultimaColumna=$checked;
									echo $tr."
										    <td>$DESCACTIVIDAD</td>
										    <td>$RADICADO</td>
										    <td>$USUA_LOGIN</td>
										    <td>$RADI_FECH_RADI</td>
											<td>$FECH_VCMTO</td>
											<td>$DESACTACTIVIDAD</td>
											" . $sprint . "
											" . $ultimaColumna . "
										  </tr>";
									$rs->MoveNext ();
								}
							}
							$rs0->MoveNext ();
						}
						echo "</table><tr><td colspan='10'></td></tr>";
					}
					echo "</table>";
				?>
		  </div>
		</form>
	</div>	
</body>
</html>