
<?php
$ruta_raiz = "../..";
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

require_once('../clases/crud_actividad.php');
require_once('../clases/actividad.php');
require_once('../clases/terceros.php');

$objTerceros = new Terceros($db);
	
	print "<div class='row form-group'>
			<div >
				<label class='desc desc control-label etiqueta control-label label-success form-control' for='txtFechaFinalizacion'>Actividades
				</label>
			</div>
		   </div>
	<tr>
		<td><strong>Actividad</strong></td>
		<td><strong>Radicado</strong></td>
		<td><strong>Usuario Encargado</strong></td>
		<td><strong>Fecha inicio</strong></td>
		<td><strong>Fecha Vencimiento</strong></td>
		<td><strong>Observacion</strong></td>
		<td><strong>Respuesta(s)</strong></td>
		<td><strong>Link</strong></td>
		<td><strong>Finalizado</strong></td>
		<td><strong>Actualizar</strong></td>
		<td><strong>Eliminar</strong></td>
	</tr>";

	$sql0="SELECT AA.ID_ACTUACION_ACTIVIDAD, AA.IDACTUACION, AA.IDACTIVIDAD, AA.DESCRIPCION, AA.RADICADO, AA.IDENCARGADO, AA.FECHA_INICIO, AA.FECHA_FINAL,
				 A.DESCRIPCION AS DESCACTIVIDAD, USUA_LOGIN, AA.ESTADO
			FROM ACT_ACTUACION_ACTIVIDAD AA
      			LEFT JOIN USUARIO U ON U.ID=AA.IDENCARGADO,
      			ACT_ACTIVIDAD A
			WHERE AA.IDACTIVIDAD=A.IDACTIVIDAD AND AA.IDACTUACION ='".$_GET["txtActuacion"]."'
			ORDER BY AA.ID_ACTUACION_ACTIVIDAD";

	$rs0=$db->query($sql0);

	while (! $rs0->EOF) {
		if($rs0->fields['FECHA_INICIO'] && $rs0->fields['FECHA_FINAL']){


			$n=$rs0->fields['ID_ACTUACION_ACTIVIDAD'];

			print "<tr>
					    <td>".$rs0->fields['DESCACTIVIDAD']."</td>
					    <td></td>
					    <td>".$rs0->fields['USUA_LOGIN']."</td>
					    <td>".$rs0->fields['FECHA_INICIO']."</td>
						<td>".$rs0->fields['FECHA_FINAL']."</td>
						<td>
							<textarea class='form-control' 
							name='txtObservacion" . $n . "' id='txtObservacion" . $n . "'
							type='textarea' rows='1' cols='100'>" . $rs0->fields['DESCRIPCION'] . "</textarea>
						</td>
						<td></td>
						<td><label for='adjuntar archivo'>Adjuntar archivo:</label>
                				<input type='file' name='archivo".$n."' id='archivo".$n."' placeholder='carga tu pdf' >
                				<a type='button' name='btnSubirArchivo" . $n . "' id='btnSubirArchivo" . $n . "' onclick='actualizaAdjunto(".$n.")'>Subir</a>
                		</td>";
			if ($rs0->fields['ESTADO']==0) {
				$checked="";
			}
			else{
				$checked="checked";	
			}
			
			$check="<td><input type='checkbox' name='txtFinalizado".$n."' id='txtFinalizado".$n."' value='".$n."' ".$checked." onclick='checkear(".$n.")'></td>
				<td><a type='button' name='btnActualiza" . $n . "' id='btnActualiza" . $n . "' onclick='actualizaActividad(".$n.")'>Actualizar</a> </td>
				<td><a type='button' name='btnElimina" . $n . "' id='btnElimina" . $n . "' onclick='eliminaActividad(".$n.")'>Eliminar</a> </td>
				</tr>";
			print $check;
		}
		else{
			$sql="SELECT ID_ACTUACION_ACTIVIDAD, IDACTUACION, AA.IDACTIVIDAD, AA.DESCRIPCION AS DESACTACTIVIDAD, 
					A.DESCRIPCION AS DESCACTIVIDAD, RADICADO, R.RADI_FECH_RADI, R.FECH_VCMTO, USUA_LOGIN, AA.ESTADO
					FROM ACT_ACTUACION_ACTIVIDAD AA 
					LEFT JOIN RADICADO R ON AA.RADICADO=R.RADI_NUME_RADI,
					ACT_ACTIVIDAD A, USUARIO U
					WHERE AA.IDACTIVIDAD=A.IDACTIVIDAD AND R.RADI_DEPE_ACTU = U.DEPE_CODI AND R.RADI_USUA_ACTU = U.USUA_CODI AND 
					ID_ACTUACION_ACTIVIDAD ='".$rs0->fields['ID_ACTUACION_ACTIVIDAD']."'";

			$rs=$db->query($sql);
			while (! $rs->EOF) {
				$n1=$rs->fields['ID_ACTUACION_ACTIVIDAD'];
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
					$checked="";
				}
				else{
					$checked="checked";	
				}
				$ultimaColumna="<td><input type='checkbox' name='txtFinalizado".$n1."' id='txtFinalizado".$n1."' value='".$n1."' ".$checked." onclick='checkear(".$n1.")'></td>";
				print "<tr>
					    <td>$DESCACTIVIDAD</td>
					    <td>$RADICADO</td>
					    <td>$USUA_LOGIN</td>
					    <td>$RADI_FECH_RADI</td>
						<td>$FECH_VCMTO</td>
						<td>
							<textarea class='form-control' 
							name='txtObservacion" . $n1 . "' id='txtObservacion" . $n1 . "'
							type='textarea' rows='1' cols='100'>" . $DESACTACTIVIDAD . "</textarea>
						</td>
						" . $sprint . 
						"<td><label for='adjuntar archivo'>Adjuntar archivo:</label>
                				<input type='file' name='archivo".$n1."' id='archivo".$n1."' placeholder='carga tu pdf' >
                				<a type='button' name='btnSubirArchivo" . $n1 . "' id='btnSubirArchivo" . $n1 . "' onclick='actualizaAdjunto(".$n1.")'>Subir</a>
                		</td>" . 
						$ultimaColumna . "

						<td><a type='button' name='btnActualiza" . $n1 . "' id='btnActualiza" . $n1 . "' onclick='actualizaActividad(".$n1.")'>Actualizar</a> </td>
						<td><a type='button' name='btnElimina" . $n1 . "' id='btnElimina" . $n1 . "' onclick='eliminaActividad(".$n1.")'>Eliminar</a> </td>
					  </tr>";

				$rs->MoveNext ();
			}
		}
		$rs0->MoveNext ();
	}

	

	$objCrudActividades = new CrudActividades($db);

	$texto = "<tr>
	<td class='col-sm-12 col-md-3'>
		<select data-placeholder='Seleccione una opcion' title='Debe Seleccionar Actividad, este campo  es Obligatorio' 
				class='chosen-select form-control'  id='txtActividad' name='txtActividad' required>
				<option value=''>-- SELECCIONE --</option>";

	foreach($objCrudActividades->getActividades() as $d){
		$texto .= "<option value='" .  $d->getIdActividad() . "'>" . $d->getDescripcion() . "</option>";
	}			

	$texto .= "</select>
	</td>
		<td>
			<input class='form-control' value='' maxlength='14' tabindex='4' 
				onkeypress='return alpha(event,numbers)' minlenght='3' name='txtRadicado' id='txtRadicado' 
				type='tel' />
			</td>
			<td>
				<select data-placeholder='Seleccione una opcion' id='txtFuncionario' name='txtFuncionario' class='chosen-select form-control'>
					<option value=''>-- SELECCIONE --</option>";

					foreach($objTerceros->getFuncionarios() as $d){
						$texto .= "<option value='" .  $d->getId() . "'>" . $d->getNombre() . "</option>";
					}
					$texto .= "</select>
			</td>
			<td><input class='form-control obligatorio' value='' maxlength='15' minlenght='10' name='txtFechaInicio' tabindex='4' id='txtFechaInicio' type='date'/></td>
			<td><input class='form-control obligatorio' value='' maxlength='15' minlenght='10' name='txtFechaFin' tabindex='4' id='txtFechaFin' type='date'/></td>
			<td colspan='6'>
				<textarea class='form-control' name='txtObservacion' id='txtObservacion' type='textarea' rows='1' cols='100' autofocus></textarea>
			</td>
	</tr>";

	print $texto;
?>


