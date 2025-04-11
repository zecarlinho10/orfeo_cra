
<?php

$ruta_raiz = "../..";
//include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
include_once ('../../js/funtionImage.php');

$db = new ConnectionHandler( "$ruta_raiz" );

require_once('../clases/crud_cobro.php');
require_once('../clases/cobro.php');
require_once('../clases/terceros.php');

//echo "IDCOBRO" . $_GET[idCobro];
$objTerceros = new Terceros($db);
$objCrudCobros = new CrudCobro($db);
$idCobro=$_GET["txtCobro"];
$objCobro=$objCrudCobros->getCobrosXID($idCobro);


if($objCobro->getEstado() == 0) {
	$inactivo="value='0' selected";
	$activo="value='1'";
	$suspendido="value='2'";
	$acumulado="value='3'";
}
else if($objCobro->getEstado() == 1) {
	$inactivo="value='0'";
	$activo="value='1' selected";
	$suspendido="value='2'";
	$acumulado="value='3'";
}
else if($objCobro->getEstado() == 2) {
	$inactivo="value='0'";
	$activo="value='1'";
	$suspendido="value='2' selected";
	$acumulado="value='3'";
}
else if($objCobro->getEstado() == 3) {
	$inactivo="value='0'";
	$activo="value='1'";
	$suspendido="value='2' ";
	$acumulado="value='3' selected";
}

$listaFuncionarios="";
foreach($objTerceros->getFuncionariosPersimo(534) as $d){
	if($d->getId()==$objCobro->getIDfuncionario()){
		$listaFuncionarios.="<option value='".$d->getId()."' selected>";
	}
	else{
		$listaFuncionarios.="<option value='".$d->getId()."'>";
	}
	$listaFuncionarios.=$d->getNombre()."</option>";
}
?>
<script type="text/javascript"
		src="js/cobro.js?tes=<?php echo date("Ymdhis")?>">
	</script>
<?
	print "<table><div class='row form-group'>
			<div >
				<label class='desc etiqueta control-label label-success form-control' for='cobro'>Cobro
				</label>
			</div>
		   </div>
	<tr>

		<td><strong>EXPEDIENTE</strong></td>

		<td><input class='form-control' value='".$objCobro->getExpediente()."' name='txtExpediente' id='txtExpediente' type='text' onblur='expedienteFunction()'/><div id='resultadoExp'></div></td>
	</tr>
	<tr>
		<td><strong>DEUDOR</strong></td>
		</td>

		<td>
			<input type='hidden' id='txtid_emp' value='" . $objCobro->getEmpresa()->getId(). "' name='txtid_emp' class='form-control obligatorio'/>
			<input class='form-control obligatorio' value='" . $objCobro->getEmpresa()->getNombre(). "'
				maxlength='150' title='El campo Prestador es obligatorio '
				minlenght='3' name='txtnoEmpresa'
			id='txtnoEmpresa' type='text' required/>
		</td>
	</tr>
	<tr>
		<td><strong>FUNCIONARIO</strong></td>
		<td>
			<select data-placeholder='Seleccione una opción'
				title='Debe Seleccionar Funcionario, este campo  es Obligatorio'
				class='chosen-select form-control dropdown seleccion'
				id='txtFuncionario' name='txtFuncionario' required>
				<option value=''>-- SELECCIONE --</option>".
				$listaFuncionarios
				."
			</select>
		</td>
	</tr>
	<tr>
		<td><strong>MANDAMIENTO DE PAGO</strong></td>
		<td><input class='form-control' value='".$objCobro->getMandamiento()."' name='txtMandamiento' id='txtMandamiento' type='text' /></td>
	</tr>
	<tr>
		<td><strong>VALOR MANDAMIENTO</strong></td>
		<td><input class='form-control' value='".$objCobro->getValorMandamiento()."' name='txtValorMandamiento' id='txtValorMandamiento' type='text' /></td>
	</tr>
	<tr>
		<td><strong>FECHA DE PRESCRIPCION</strong></td>
		<td><input class='form-control' value='".$objCobro->getPrescripcion()."' name='txtFechaPres' id='txtFechaPres' type='date' /></td>
	</tr>
	<tr>
		<td><strong>OBSERVACION</strong></td>
		<td><textarea  name='txtObservacion' id='txtObservacion' rows='5' cols='50'>".$objCobro->getObservacion()."
			</textarea>
		</td>
	</tr>
	<tr>
		<td><strong>ESTADO</strong></td>
		<td>
			<select data-placeholder='Seleccione una opción'
				title='Debe Seleccionar Funcionario, este campo  es Obligatorio'
				class='chosen-select form-control dropdown seleccion'
				id='txtEstado' name='txtEstado' required>
				<option ". $inactivo.">Terminado</option>
				<option ". $activo.">Activo</option>
				<option ". $suspendido.">Suspendido</option>
				<option ". $acumulado.">Acumulado</option>
			</select>
		</td>
	</tr>
	</table>";
?>
