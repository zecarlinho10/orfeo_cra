<?php
/**
* @module crearUsuario
*
* @author Jairo Losada   <jlosada@gmail.com>
* @author Cesar Gonzalez <aurigadl@gmail.com>
* @license  GNU AFFERO GENERAL PUBLIC LICENSE
* @copyright

SIIM2 Models are the data definition of SIIM2 Information System
Copyright (C) 2013 Infometrika Ltda.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

session_start();

    $ruta_raiz = "../../";
    if (!$_SESSION['dependencia'])
        header ("Location: $ruta_raiz/cerrar_session.php");

include_once    ("$ruta_raiz/include/db/ConnectionHandler.php");

$db = new ConnectionHandler($ruta_raiz);

if ($db->conn){

	//Creamos un vector con las opciones
	$vec_ppal[0]= array("&lt; Seleccione &gt;","","","");
	$vec_ppal[1]= array("Subtemas","SGD_CAU_CAUSAL","SGD_CAU_CODIGO","SGD_CAU_DESCRIP");
	//$vec_ppal[2]= array("Resoluciones","SGD_TRES_TPRESOLUCION","SGD_TRES_CODIGO","SGD_TRES_DESCRIP");
	$vec_ppal[3]= array("Temas","PAR_SERV_SERVICIOS","PAR_SERV_SECUE","PAR_SERV_NOMBRE");
	$vec_ppal[4]= array("Motivo Devoluci&oacute;n","SGD_DEVE_DEV_ENVIO", "SGD_DEVE_CODIGO", "SGD_DEVE_DESC");
	/* POR IMPLEMENTAR
	$vec_ppal[5]= array("Medio Recepcion","MREC_DESC","MREC_CODI","MEDIO_RECEPCION");
	$vec_ppal[6]= array("Notificacion","SGD_NOT_DESCRIP","SGD_NOT_CODI","SGD_NOT_NOTIFICACION");
	*/
	//Generamos el combo a mostrar
	foreach($vec_ppal as $key => $vlr)
	{
		($_POST['slc_ppal'] == $key) ?  $slc='selected' : $slc='';
		$opc_cmb .= "<option value='$key' $slc>".$vlr[0]."</option>";
	}

	switch ($_POST['slc_ppal'])
	{
		case 1:
		{
			include($ruta_raiz.'/include/class/causales.class.php');
			$obj_tmp = new Causales($db->conn);
		}break;
		case 2:
		{
			include($ruta_raiz.'/include/class/resoluciones.class.php');
			$obj_tmp = new Resoluciones($db->conn);
		}break;
		case 3:
		{
			include($ruta_raiz.'/include/class/sectores.class.php');
			$obj_tmp = new Sectores($db->conn);
		}break;
		case 4:
		{
			include($ruta_raiz.'/include/class/medioDevoluciones.class.php');
			$obj_tmp = new MedDevolucion($db->conn);
		}break;
		default:break;
	}

	if (isset($_POST['btn_accion']))
	{	switch($_POST['btn_accion'])
		{	Case 'Agregar':
			{
				$sql = "insert into ".$vec_ppal[$_POST['slc_ppal']][1]."(".$vec_ppal[$_POST['slc_ppal']][2].",".$vec_ppal[$_POST['slc_ppal']][3].") ";
				$sql.= "values (".$_POST['txtId'].",'".$_POST['txtModelo']."')";
				$db->conn->Execute($sql) ? $error = 3 : $error = 2;
			}break;
			Case 'Modificar':
			{
				$sql = "update ".$vec_ppal[$_POST['slc_ppal']][1]." set ".$vec_ppal[$_POST['slc_ppal']][3]." = '".$_POST['txtModelo']."' ";
				$sql.= "where ".$vec_ppal[$_POST['slc_ppal']][2]."=".$_POST['txtId'];
				$db->conn->Execute($sql) ? $error = 4 : $error = 2;
			}break;
			Case 'Eliminar':
			{
				switch ($_POST['slc_ppal'])
				{
					case 1:
					{	$ok = $obj_tmp->SetDelDatos($_POST['slc_cmb2']);
						($ok == 0) ? $error = 5 : (($ok) ? $error = null : $error = 2);
					}break;
					case 2:
					{	$ok = $obj_tmp->SetDelDatos($_POST['slc_cmb2']);
						($ok == 0) ? $error = 5 : (($ok) ? $error = null : $error = 2);
					}break;
					case 3:
					{	$ok = $obj_tmp->SetDelDatos($_POST['slc_cmb2']);
						($ok == 0) ? $error = 5 : (($ok) ? $error = null : $error = 2);
					}break;
					case 4:
					{	$ok = $obj_tmp->SetDelDatos($_POST['slc_cmb2']);
						($ok == 0) ? $error = 5 : (($ok) ? $error = null : $error = 2);
					}break;
					default:
					{}break;
					}
				}break;
			Default: break;
		}
		unset($record);
	}

	switch ($_POST['slc_ppal'])
	{
		case 1:
		{
			$slc_tmp = $obj_tmp->Get_ComboOpc(true,true);
			$vec_tmp = $obj_tmp->Get_ArrayDatos();
			$ver ='cau';
		}break;
		case 2:
		{
			$slc_tmp = $obj_tmp->Get_ComboOpc(true,true);
			$vec_tmp = $obj_tmp->Get_ArrayDatos();
			$ver ='lcd';
		}break;
		case 3:
		{
			$slc_tmp = $obj_tmp->Get_ComboOpc(true,true);
			$vec_tmp = $obj_tmp->Get_ArrayDatos();
			$ver ='sts';
		}break;
		case 4:
		{
			$slc_tmp = $obj_tmp->Get_ComboOpc(true,true);
			$vec_tmp = $obj_tmp->Get_ArrayDatos();
			$ver ='mdv';
		}break;
		default:
		{
			$slc_tmp="<select name='slc_cmb2' id='slc_cmb2' class='select' ><option value='0' selected>&lt;&lt; Seleccione la Tabla &gt;&gt;</option></select>";
			$ver=false;
		}break;
	}
}
else
{	$error = 1;
}

/*
*	Funcion que convierte un valor de PHP a un valor Javascript.
*/
function valueToJsValue($value, $encoding = false)
{	if (!is_numeric($value))
	{	$value = str_replace('\\', '\\\\', $value);
		$value = str_replace('"', '\"', $value);
		$value = '"'.$value.'"';
	}
	if ($encoding)
	{	switch ($encoding)
		{	case 'utf8' :	return iconv("ISO-8859-2", "UTF-8", $value);
							break;
		}
	}
	else
	{	return $value;	}
	return ;
}

/*
*	Funcion que convierte un vector de PHP a un vector Javascript.
*	Utiliza a su vez la funcion valueToJsValue.
*/
function arrayToJsArray( $array, $name, $nl = "\n", $encoding = false )
{	if (is_array($array))
	{	$jsArray = $name . ' = new Array();'.$nl;
		foreach($array as $key => $value)
		{	switch (gettype($value))
			{	case 'unknown type':
				case 'resource':
				case 'object':	break;
				case 'array':	$jsArray .= arrayToJsArray($value,$name.'['.valueToJsValue($key, $encoding).']', $nl);
								break;
				case 'NULL':	$jsArray .= $name.'['.valueToJsValue($key,$encoding).'] = null;'.$nl;
								break;
				case 'boolean':	$jsArray .= $name.'['.valueToJsValue($key,$encoding).'] = '.($value ? 'true' : 'false').';'.$nl;
								break;
				case 'string':	$jsArray .= $name.'['.valueToJsValue($key,$encoding).'] = '.valueToJsValue($value, $encoding).';'.$nl;
								break;
				case 'double':
				case 'integer':	$jsArray .= $name.'['.valueToJsValue($key,$encoding).'] = '.$value.';'.$nl;
								break;
				default:	trigger_error('Hoppa, egy j ERROR a PHP-ben?'.__CLASS__.'::'.__FUNCTION__.'()!', E_USER_WARNING);
			}
		}
		return $jsArray;
	}
	else
	{	return false;	}
}
if ($error) {
  $msg = '<tr bordercolor="#FFFFFF">
			<td width="3%" align="center" class="titulosError" colspan="3" bgcolor="#FFFFFF">';
	switch ($error)
	{	case 1:	//NO CONECCION A BD
				$msg .= "Error al conectar a BD, comun&iacute;quese con el Administrador de sistema !!";
				break;
		case 2:	//ERROR EJECUCCION SQL
				$msg .=  "Error al gestionar datos, comun&iacute;quese con el Administrador de sistema !!";
				break;
		case 3:	//INSERCION REALIZADA
				$msg .=  "Creaci&oacute;n exitosa!";break;
		case 4:	//MODIFICACION REALIZADA
				$msg .=  "Registro actualizado satisfactoriamente!!";break;
		case 5:	//IMPOSIBILIDAD DE ELIMINAR REGISTRO
				$msg .=  "No se puede eliminar registro, tiene dependencias internas relacionadas.";break;
	}
	$msg .=  '</td></tr>';
}
?>
<html>
<head>
<script language="JavaScript">
<!--
function Actual()
{
var Obj = document.getElementById('slc_cmb2');
var i = Obj.selectedIndex;
document.getElementById('txtModelo').value = Obj.options[i].text;
document.getElementById('txtId').value = Obj.value;
}

function rightTrim(sString)
{	while (sString.substring(sString.length-1, sString.length) == ' ')
	{	sString = sString.substring(0,sString.length-1);  }
	return sString;
}

function ver_listado()
{
<?php
if ($ver)
{
?>

	window.open('listados.php?<?=session_name()."=".session_id()?>&var=<?=$ver?>','','scrollbars=yes,menubar=no,height=600,width=800,resizable=yes,toolbar=no,location=no,status=no');
<?php
}
else {
  echo "alert('Debe seleccionar una Opcion.');";
}
?>
}

<?php
echo arrayToJsArray($vec_tmp, 'vt');
?>
//-->
</script>

<title>Orfeo - Admor de tablas sencillas.</title>
<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>

</head>
<body>
<form name="form1" method="post" action="<?= $_SERVER['PHP_SELF']?>">
<input type='hidden' name='<?=session_name()?>' value='<?=session_id()?>'>
<input type="hidden" name="hdBandera" value="">
  <div class="col-sm-12">
    <!-- widget grid -->
    <section id="widget-grid">
      <!-- row -->
      <div class="row">
        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <!-- Widget ID (each widget will need unique ID)-->
          <div class="jarviswidget jarviswidget-color-darken" id="wid-id-1" data-widget-editbutton="false">

            <header>
              <h2>
                Administrador de tablas sencillas
              </h2>
            </header>

            <!-- widget div-->
            <div>
              <!-- widget content -->
              <div class="widget-body no-padding">

                <table class="table table-bordered table-striped">
                    <tr bordercolor="#FFFFFF">
                      <td colspan="3" height="40" align="center" class="titulos4" valign="middle"><b><span class=etexto></span></b></td>
                    </tr>
                    <tr bordercolor="#FFFFFF">
                      <td width="3%" align="center" class="titulos2"><b>1.</b></td>
                      <td width="25%" align="left" class="titulos2"><b>&nbsp;Seleccione la tabla</b></td>
                      <td width="72%" class="listado2">
                        <SELECT name="slc_ppal" id="slc_ppal" class="select" onchange="this.form.submit();">
                        <?=$opc_cmb ?>
                        </SELECT>
                      </td>
                    </tr>
                    <tr bordercolor="#FFFFFF">
                      <td align="center" class="titulos2"><b>2.</b></td>
                      <td align="left" class="titulos2"><b>&nbsp;Seleccione Registro</b></td>
                        <td align="left" class="listado2">
                        <?=$slc_tmp	?>
                      </td>
                    </tr>
                    <tr bordercolor="#FFFFFF">
                      <td rowspan="2" valign="middle" class="titulos2">3.</td>
                      <td align="left" class="titulos2"><b>&nbsp;Ingrese c&oacute;digo</b></td>
                      <td class="listado2"><input name="txtId" id="txtId" type="text" size="10" maxlength="2"></td>
                    </tr>
                    <tr bordercolor="#FFFFFF">
                      <td align="left" class="titulos2"><b>&nbsp;Ingrese nombre</b></td>
                      <td class="listado2"><input name="txtModelo" id="txtModelo" type="text" size="50" maxlength="30"></td>
                    </tr>
                    <?php
                      echo $msg;
                    ?>
                    </table>
                    <table class="table table-bordered table-striped">
                      <tr bordercolor="#FFFFFF">
                        <td width="10%" class="listado2">&nbsp;</td>
                        <td width="20%"  class="listado2">
                          <span class="celdaGris"> <span class="e_texto1"><center>
                          <input name="btn_accion" type="button" class="botones" id="btn_accion" value="Listado" onClick="ver_listado();">
                          </center></span>
                        </td>
                        <td width="20%" class="listado2">
                          <span class="e_texto1"><center>
                          <input name="btn_accion" type="submit" class="botones" id="btn_accion" value="Agregar" onClick="document.form1.hdBandera.value='A'; return ValidarInformacion();">
                          </center></span>
                        </td>
                        <td width="20%" class="listado2">
                          <span class="e_texto1"><center>
                          <input name="btn_accion" type="submit" class="botones" id="btn_accion" value="Modificar" onClick="document.form1.hdBandera.value='M'; return ValidarInformacion();">
                          </center></span>
                        </td>
                        <td width="20%" class="listado2">
                          <span class="e_texto1"><center>
                          <input name="btn_accion" type="submit" class="botones" id="btn_accion" value="Eliminar" onClick="document.form1.hdBandera.value='E'; return ValidarInformacion();">
                          </center></span>
                        </td>
                        <td width="10%" class="listado2">&nbsp;</td>
                      </tr>
                      </table>
              </div>
            </div>
          </div>
        </article>
      </div>
    </section>
  </div>
</form>
<script ID="clientEventHandlersJS" LANGUAGE="JavaScript">
<!--
function ValidarInformacion()
{	var strMensaje = "Por favor ingrese las datos.";

	if (document.form1.slc_ppal.value == "0")
	{	alert("Debe seleccionar el registro.\n" + strMensaje);
		document.form1.idcont.focus();
		return false;
	}

	if ( rightTrim(document.form1.txtId.value) <= "0")
	{	alert("Debe ingresar el Codigo.\n" + strMensaje);
		document.form1.txtIdPais.focus();
		return false;
	}
	else if(isNaN(document.form1.txtId.value))
	{	alert("El Codigo debe ser numerico.\n" + strMensaje);
		document.form1.txtIdPais.select();
		document.form1.txtIdPais.focus();
		return false;
	}

	if (document.form1.hdBandera.value == "A")
	{	if(rightTrim(document.form1.txtModelo.value) == "")
		{	alert("Debe ingresar descripcion.\n" + strMensaje);
			document.form1.txtModelo.focus();
			return false;
		}
		else
		{	document.form1.submit();
		}
	}
	else if(document.form1.hdBandera.value == "M")
	{	if(rightTrim(document.form1.txtModelo.value) == "")
		{	alert("Primero debe seleccionar el registro a modificar.\n" + strMensaje);
			return false;
		}
		else if(document.form1.txtId.value != document.form1.slc_cmb2.value)
		{
			alert('No se puede modificar el codigo');
			document.form1.txtId.focus();
			return false;
		}
		else
		{	document.form1.submit();
		}
	}
	else if(document.form1.hdBandera.value == "E")
	{	if(confirm("Esta seguro de borrar este registro ?\n"))
		{	document.form1.submit();	}
		else
		{	return false;	}
	}
}
//-->
</script>
</body>
</html>
