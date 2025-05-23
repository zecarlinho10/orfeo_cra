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

    $ruta_raiz = "../..";
    if (!$_SESSION['dependencia'])
        header ("Location: $ruta_raiz/cerrar_session.php");

include_once    ("$ruta_raiz/include/db/ConnectionHandler.php");

$db = new ConnectionHandler($ruta_raiz);
if ($db->conn){
    if (isset($_POST['btn_accion'])){
        $record = array();
           $codi_ini = $_POST['idpais'];
		$record['ID_PAIS'] = $_POST['txtIdPais'];
		$record['ID_CONT'] = $_POST['idcont'];
		$record['NOMBRE_PAIS'] = $_POST['txtModelo'];
        switch($_POST['btn_accion']){
            Case 'Agregar':{

			$paisNomb = strtoupper($_POST['txtModelo']);
			$idCont = $_POST['idcont'];
			$idPais = $_POST['txtIdPais'];

			$isql ="INSERT INTO sgd_def_paises (id_pais,id_cont, nombre_pais) VALUES ($idPais,$idCont,'$paisNomb')";

			if (!($db->conn->Execute($isql))){
             $error = 6;
            }else{
             $error = 4 ;
           }
			}break;

            Case 'Modificar':{	if ($codi_ini <> $record['ID_PAIS'])
                                 {
                                   //No se permite modificar el código del país
                                   $error = 6;
                                  }
                                  else
                                  {$res = $db->conn->Replace('SGD_DEF_PAISES',$record,array('ID_PAIS','ID_CONT'),$autoquote = true);
                                ($res) ? ($res == 1 ? $error = 3 : $error = 4 ) : $error = 2;
                                }
                }
                break;
            Case 'Eliminar':
            {
                $sql = "SELECT * FROM SGD_DIR_DRECCIONES WHERE ID_PAIS = ".$record['ID_PAIS'];
                $rs = $db->conn->Execute($sql);
                if ($rs->RecordCount() > 0){
                    $error = 5;
                }else{
                    $ok = $db->conn->Execute("DELETE FROM MUNICIPIO WHERE ID_PAIS=".$record['ID_PAIS']);
                    if ($ok)
                    {	$ok = $db->conn->Execute("DELETE FROM DEPARTAMENTO WHERE ID_PAIS=".$record['ID_PAIS']);
                        if ($ok)
                            $db->conn->Execute("DELETE FROM SGD_DEF_PAISES WHERE ID_PAIS=".$record['ID_PAIS']);
                    }
            }
        }
	}
		unset($record);
}

	$sql_cont = "SELECT nombre_cont,ID_CONT FROM SGD_DEF_CONTINENTES ORDER BY nombre_cont";
    $salida = $db->conn->Execute($sql_cont);
	if (isset($_POST['idcont']) and $_POST['idcont'] >0)
	{	$sql_pais = "SELECT NOMBRE_PAIS,ID_PAIS FROM SGD_DEF_PAISES WHERE ID_CONT=".$_POST['idcont']." ORDER BY NOMBRE_PAIS";
		$Rs_pais = $db->conn->Execute($sql_pais);
		if (!($Rs_pais)) $error = 2;
	}
}else{	$error = 1;}
?>
<html>
<head>
<script language="JavaScript">
<!--
function Actual()
{
var Obj = document.getElementById('idpais');
var i = Obj.selectedIndex;
document.getElementById('txtModelo').value = Obj.options[i].text;
document.getElementById('txtIdPais').value = Obj.value;
}

function rightTrim(sString)
{	while (sString.substring(sString.length-1, sString.length) == ' ')
	{	sString = sString.substring(0,sString.length-1);  }
	return sString;
}

function ver_listado(){
	window.open('listados.php?<?=session_name()."=".session_id()?>&var=pai','','scrollbars=yes,menubar=no,height=600,width=800,resizable=yes,toolbar=no,location=no,status=no');
}
//-->
</script>

<title>Orfeo - Admor de paises.</title>
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
                Administrador de paises
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
                    <td width="25%" align="left" class="titulos2"><b>&nbsp;Seleccione Continente</b></td>
                    <td width="72%" class="listado2">
                      <?php
                          // Listamos los continentes.
                        echo $salida->GetMenu2('idcont',$_POST['idcont'],"0:&lt;&lt;SELECCIONE&gt;&gt;",false,0,"class='select' onChange=\"this.form.submit()\"");
                      ?>
                      </td>
                  </tr>
                  <tr bordercolor="#FFFFFF">
                    <td align="center" class="titulos2"><b>2.</b></td>
                    <td align="left" class="titulos2"><b>&nbsp;Seleccione Pa&iacute;s</b></td>
                      <td align="left" class="listado2">
                      <?php
                      if (isset($_POST['idcont']) and $_POST['idcont'] > 0)
                      {	// Listamos los paises segun continente.
                          echo $Rs_pais->GetMenu2('idpais',$_POST['idpais'],"0:&lt;&lt;SELECCIONE&gt;&gt;",false,0,"class='select' id=\"idpais\" onChange=\"Actual();\" ");
                        }
                      else
                      {	echo "<select name='idpais' id='idpais' class='select' ><option value='0' selected>&lt;&lt; Seleccione Continente &gt;&gt;</option></select>";
                      }
                      ?></td>
                  </tr>
                  <tr bordercolor="#FFFFFF">
                    <td rowspan="2" valign="middle" class="titulos2">3.</td>
                    <td align="left" class="titulos2"><b>&nbsp;Ingrese c&oacute;digo de pa&iacute;s</b></td>
                    <td class="listado2"><input name="txtIdPais" id="txtIdPais" type="text" size="10" maxlength="3"></td>
                  </tr>
                  <tr bordercolor="#FFFFFF">
                    <td align="left" class="titulos2"><b>&nbsp;Ingrese nombre pa&iacute;s</b></td>
                    <td class="listado2"><input name="txtModelo" id="txtModelo" type="text" size="50" maxlength="30"></td>
                  </tr>
                  <tr bordercolor="#FFFFFF">
                    <td width="3%" align="justify" class="info" colspan="3" bgcolor="#FFFFFF"><b>NOTA: </b> Para una estandarizaci&oacute;n en los c&oacute;digos de pa&iacute;ses utilicemos los sugeridos por la ISO.  <a href="http://es.wikipedia.org/wiki/ISO_3166-1" target="_blank" rel="noopener noreferrer" class="vinculos">enlace</a></td>
                  </tr>
                  <?php
                  if ($error)
                  {	echo '<tr bordercolor="#FFFFFF">
                        <td width="3%" align="center" class="titulosError" colspan="3" bgcolor="#FFFFFF">';
                    switch ($error)
                    {	case 1:	//NO CONECCION A BD
                          echo "Error al conectar a BD, comun&iacute;quese con el Administrador de sistema !!";
                          break;
                      case 2:	//ERROR EJECUCCIÓN SQL
                          echo "Error al gestionar datos, comun&iacute;quese con el Administrador de sistema !!";
                          break;
                      case 3:	//ACUTALIZACION REALIZADA
                          echo "Informaci&oacute;n actualizada!!";break;
                      case 4:	//INSERCION REALIZADA
                          echo "Pa&iacute;s creado satisfactoriamente!!";break;
                      case 5:	//IMPOSIBILIDAD DE ELIMINAR PAIS, EST&Aacute; LIGADO CON DIRECCIONES
                          echo "No se puede eliminar pa&iacute;s, se encuentra ligado a direcciones.";break;
                                  case 6:	//IMPOSIBILIDAD MODIFICAR PAIS,
                          echo "No se puede Modificar el Codigo del pa&iacute;s . O intenta crear un pais con un codigo que ya existe";break;
                    }
                    echo '</td></tr>';
                  }
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
                </form>

<script ID="clientEventHandlersJS" LANGUAGE="JavaScript">
<!--
function ValidarInformacion(){

    var strMensaje = "Por favor ingrese las datos.";

	if (document.form1.idcont.value == "0")
	{	alert("Debe seleccionar el continente.\n" + strMensaje);
		document.form1.idcont.focus();
		return false;
	}

	if ( rightTrim(document.form1.txtIdPais.value) <= "0")
	{	alert("Debe ingresar el Codigo de Pais.\n" + strMensaje);
		document.form1.txtIdPais.focus();
		return false;
	}
	else if(isNaN(document.form1.txtIdPais.value))
	{	alert("El Codigo de Pais debe ser numerico.\n" + strMensaje);
		document.form1.txtIdPais.select();
		document.form1.txtIdPais.focus();
		return false;
	}

	if (document.form1.hdBandera.value == "A")
	{	if(rightTrim(document.form1.txtModelo.value) == "")
		{	alert("Debe ingresar nombre del Pais.\n" + strMensaje);
			document.form1.txtModelo.focus();
			return false;
		}
		else
		{	document.form1.submit();
		}
	}
	else if(document.form1.hdBandera.value == "M")
	{	if(rightTrim(document.form1.txtModelo.value) == "")
		{	alert("Primero debe seleccionar el Pais a modificar.\n" + strMensaje);
			return false;
		}
		else
		{	document.form1.submit();
		}
	}
	else if(document.form1.hdBandera.value == "E")
	{	if(confirm("Esta seguro de borrar el registro ?\n La eliminaci\xf3n de este pais incluye sus Dptos y municipios."))
		{	document.form1.submit();	}
		else
		{	return false;	}
	}
}
//-->
</script>
</body>
</html>
