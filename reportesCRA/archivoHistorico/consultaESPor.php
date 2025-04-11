<?
/**
 * Programa que despliega el formulario de consulta de ESP
 * @author     Lucia Ojeda Acosta
 * @version     1.0
 */
$ruta_raiz = "../..";
session_start();
error_reporting(0);
require_once("$ruta_raiz/include/db/ConnectionHandler.php");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

include_once($ruta_raiz."/radicacion/crea_combos_universales.php");

//En caso de no llegar la dependencia recupera la sesi�n
if(!$_SESSION['dependencia']) include "$ruta_raiz/rec_session.php";

?>
<html>
<head>
<title>Orfeo. Consulta ESP</title>
<link rel="stylesheet" href="../../estilos/orfeo.css">
<script language="JavaScript" src="../../js/crea_combos_2.js"></script>
<script language="JavaScript" type="text/JavaScript">
<?php
//HLP. Convertimos los vectores de los paises, dptos y municipios creados en crea_combos_universales.php a vectores en JavaScript.
echo arrayToJsArray($vpaisesv, 'vp');
echo arrayToJsArray($vdptosv, 'vd');
echo arrayToJsArray($vmcposv, 'vm');
?>

/**
* Env�a el formulario de acuerdo a la opci�n seleccionada, que puede ser ver CSV o consultar
*/
function enviar(argumento)
{	document.formSeleccion.action=argumento+"&"+document.formSeleccion.params.value;
	document.formSeleccion.submit();
}


/*
*	Funcion que se le envia el id del municipio en el formato general c-ppp-ddd-mmm y lo desgloza
*	creando las variables en javascript para su uso individual, p.e. para los combos respectivos.
*/
function crea_var_idlugar_defa(id_mcpio)
{	if (id_mcpio == 0) return;
	var str = id_mcpio.split('-');

	document.formSeleccion.idcont1.value = str[0]*1;
	cambia(formSeleccion,'idpais1','idcont1');
	document.formSeleccion.idpais1.value = str[1]*1;
	cambia(formSeleccion,'codep_us1','idpais1');
	document.formSeleccion.codep_us1.value = str[1]*1+'-'+str[2]*1;
	cambia(formSeleccion,'muni_us1','codep_us1');
	document.formSeleccion.muni_us1.value = str[1]*1+'-'+str[2]*1+'-'+str[3]*1;
}

function activa_chk(forma)
{	//alert(forma.tbusqueda.value);
	//var obj = document.getElementById(chk_desact);
	if (forma.slc_tb.value == 0)
		forma.chk_desact.disabled = false;
	else
		forma.chk_desact.disabled = true;
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body onLoad="crea_var_idlugar_defa(<?php echo "'".($_SESSION['cod_local'])."'"; ?>);">
<?
$params = session_name()."=".session_id()."&krd=$krd";
?>
<form action="resultConsulta.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >
<input type="hidden" name="selected0" value="<?=$selected0?>">
<input type="hidden" name="selected1" value="<?=$selected1?>">
<input type="hidden" name="selected2" value="<?=$selected2?>">
<input type="hidden" name="selectedctt0" value="<?=$selectedctt0?>">
<input type="hidden" name="selectedctt1" value="<?=$selectedctt1?>">
<input type="hidden" name="nombre1" value="<?=$nombre?>">
<input type="hidden" name="tipo_masiva" value="<?=$_POST['masiva']?>">  <!-- Este valor viene cuando se invoca este archivo en selecConsultaESP.php -->
<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="2" class='titulos2'>
			CONSULTA ARCHIVO HISTORICO
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>
	<tr align="center">
		<td width="31%" class='titulos2'>NOMBRE</td>
		<td width="69%" height="30" class='listado2' align="left">
			<input name="nombre" id="nombre" type="input" size="50" class="tex_area">
		</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td class="titulos1" colspan="2">
			<font style="font-family:verdana; font-size:x-small"><b>Nota:
			<font color="Gray">
			Aca puede consultar los radicados anteriores al 14 de Marzo de 2007, fecha en la cual se comenzaron a utilizar las tablas de retenci&oacute;n documental, lo que parti&oacute; en dos la manera de archivar los documentos.
			</b></font></font>		</td>
	</tr>
	<tr align="center">
		<td height="30" colspan="2" class='listado2'>
		<center>
			<input name="Consultar" type="button"  class="botones" id="envia22"  onClick="enviar('resultConsulta.php?x=x');" value="Consultar">&nbsp;&nbsp;
<?
				//Si se ha seleccionado registros previamente se muestran los botones para guardar esta seleccion como CSV o para mostrarla como PDF
				//Cada variable hace referencia al rango de busqueda del select 'slc_tb'
				if (strlen(trim($selected0))>0 or strlen(trim($selected1))>0 or strlen(trim($selected2))>0)
				{
?>
        		<input name="guardar" type="button" class="botones" id="envia22"  value="Guardar CSV" onClick="enviar('printConsultaESP.php?salida=csv');">&nbsp;&nbsp;
        		<input name="ver" type="button" class="botones_mediano" id="envia22"  value="Ver Seleccionados"  onClick="enviar('printConsultaESP.php?salida=pdf');">
<?
				}
?>
		</center>
		</td>
	</tr>
</table>
</form>
</div></body>
</html>
