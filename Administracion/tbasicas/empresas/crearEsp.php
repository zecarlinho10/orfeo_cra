<?php
/*Modulo de Creacion de Empresa
  Creado por: Ing. Mario Manotas Duran
*/
//session_start();
if (!isset($krd)) $krd = $_POST['krd']; else $krd = $_GET['krd'];
$ruta_raiz="../../..";
if(!isset($_SESSION['dependencia']))	include "$ruta_raiz/rec_session.php";
define('ADODB_ASSOC_CASE', 1);
require_once("$ruta_raiz/include/db/ConnectionHandler.php");
$db = new ConnectionHandler($ruta_raiz);


//Agregar Variables de Post Juan Carlos Villalba 2016/02/02
$txt_iden=$_POST['txt_iden']; 
$txt_esp=$_POST['txt_esp']; 
$txt_rep=$_POST['txt_rep']; 
$txt_nit=$_POST['txt_nit']; 
$txt_cargo=$_POST['txt_cargo']; 
$txt_dir=$_POST['txt_dir']; 
$txt_tel1=$_POST['txt_tel1']; 
$txt_tel2=$_POST['txt_tel2']; 
$txt_mail=$_POST['txt_mail']; 
$txt_sigla=$_POST['txt_sigla']; 


// Agregar Variables de la LiSTA.
$cont=$_POST['cont'];	
$muni=$_POST['muni'];	
$dpto=$_POST['dpto'];
$pais1=$_POST['pais'];		
$Slc_act=$_POST['Slc_act'];
//

//REVISAR EL ONCHANGE JAVA PAIS Y ACTIVO/INACTIVO

if($usModo ==1) $tituloCrear = "Creacion Empresas";
//$db->conn->debug=true;
$error = 0;
?>
<html>
<head><title>Untitled Document</title>
<link rel="stylesheet" href="../../estilos/orfeo.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="<?=$ruta_raiz?>/js/formchek.js"></script>
<script language="JavaScript" type="text/JavaScript">
function envio_datos()
{
	if(isWhitespace(document.forms[0].txt_esp.value))
		{	alert("El campo Nombre Empresa no ha sido diligenciado.");
			document.forms[0].txt_esp.focus();
			return false;
		}
	if(isWhitespace(document.forms[0].txt_dir.value))
		{	alert("El campo Dirección no ha sido diligenciado.");
			document.forms[0].txt_dir.focus();
			return false;
		}
		
if(isWhitespace(document.forms[0].txt_nit.value))
		{	alert("El campo nit no ha sido diligenciado y recuerde debe tener 9 numeros sin digito de verificacion.");
			document.forms[0].txt_dir.focus();
			return false;
		}	
		
		
		if((document.forms[0].txt_nit.value.length<9))
		{	alert("Debe tener 9 Numeros el nit");
			document.forms[0].txt_dir.focus();
			return false;
		}	
		
  	else
	{
		document.forms[0].submit();
		return true;
		 
	}

}
	
	
	
	
	function limpiar()
		{
	   document.formEsp.elements['txt_esp'].value = "";
	   document.formEsp.elements['txt_nuir'].value = "";
	   document.formEsp.elements['txt_nit'].value = "";
	   document.formEsp.elements['txt_sigla'].value = "";
	   document.formEsp.elements['txt_tel1'].value = "";
	   document.formEsp.elements['txt_tel2'].value = "";
	   document.formEsp.elements['txt_dir'].value = "";
	   document.formEsp.elements['txt_mail'].value = "";
	   document.formEsp.elements['txt_rep'].value = "";
	   document.formEsp.elements['txt_cargo'].value = "";
	   document.formEsp.elements['cont'].value = 0;
	   document.formEsp.elements['pais'].value = 0;
	   document.formEsp.elements['dpto'].value = 0;
	   document.formEsp.elements['muni'].value = 0;
 
  }
</script>
 <SCRIPT language=Javascript>
      
      function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
      
   </SCRIPT>



</head>
<?
$params = session_name()."=".session_id()."&krd=$krd";
?>
<body>
<?
    include "$ruta_raiz/config.php";
	include_once "$ruta_raiz/include/db/ConnectionHandler.php";
    $db = new ConnectionHandler("$ruta_raiz");
    if (!defined('ADODB_FETCH_ASSOC'))define('ADODB_FETCH_ASSOC',2);
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	//$db->conn->debug = true;
?>
<form action="crearEsp.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formEsp" id="formEsp" onSubmit="return envio_datos();">
<table width="93%"  border="1" align="center">
  	<tr bordercolor="#FFFFFF">
    <td colspan="2" class="titulos4">
	<center>
	<p><B><span class=etexto>ADMINISTRACION DE EMPRESAS E.S.P.</span></B> </p>
	<p><B><span class=etexto> <?=$tituloCrear ?></span></B> </p></center>
	</td>
	</tr>
</table>
<table border=1 width=93% class=t_bordeGris align="center">
<tr bordercolor = "#FFFFFF">
<td width="20%" align="center" class="titulos2">NOMBRE EMPRESA E.S.P.</td>
<td width="20%" colspan="2" align="center"><input class="tex_area" type="text" name="txt_esp" id="txt_esp" size="80" maxlength="200" value='<?=$txt_esp?>'></td>
</tr>
<tr>
<td width="30%" align="center" valign="middle" class="titulos2">NUIR</td>
<td width="30%" align="center" valign="middle" class="titulos2">NIT</td>
<td width="30%" align="center" valign="middle" class="titulos2">SIGLA</td>
</tr>
<tr align="center">
<td width="33%"><input class="tex_area" type="text" name="txt_nuir" id="txt_nuir" maxlength="15" value='<?=$txt_nuir?>'></td>
<td width="33%"><input class="tex_area" type="text" name="txt_nit" id="txt_nit" maxlength="9" value='<?=$txt_nit?>' onkeypress="return isNumberKey(event)"></td>
<td width="34%"><input class="tex_area" type="text" name="txt_sigla" id="txt_sigla" maxlength="50" value='<?=$txt_sigla?>'></td>
</tr>
<tr>
<td width="30%" align="center" valign="middle" class="titulos2">TELEFONO_1</td>
<td width="30%" align="center" valign="middle" class="titulos2">TELEFONO_2</td>
<td width="30%" align="center" valign="middle" class="titulos2">EMAIL</td>
</tr>
<tr align="center">
<td width="33%"><input class="tex_area" type="text" name="txt_tel1" id="txt_tel1" maxlength="15" value='<?=$txt_tel1?>'></td>
<td width="33%"><input class="tex_area" type="text" name="txt_tel2" id="txt_tel2" maxlength="15" value='<?=$txt_tel2?>'></td>
<td width="34%" align="center" valign="bottom"><input class="tex_area" type="text" name="txt_mail" id="txt_mail" maxlength="50" size="30" value='<?=$txt_mail?>'></td>
</tr>
<tr>
<td colspan="3" align="center" valign="middle" class="titulos2">DIRECCION COMPLETA</td>

</tr>
<tr>
<td colspan="3" align="center" valign="middle"><input class="tex_area" type="text" name="txt_dir" id="txt_dir" size="90" maxlength="160" value='<?=$txt_dir?>'></td>
</tr>
<tr>
<td width="30%" align="center" valign="middle" class="titulos2">CONTINENTE</td>
<td width="30%" align="center" valign="middle" class="titulos2">PAIS</td>
<td width="30%" align="center" valign="middle" class="titulos2">DEPARTAMERNTO</td>
</tr>
<tr align="center">
<td width="33%">
<?php
print $_POST['Slc_act'];
$sqlcont ="Select nombre_cont, id_cont  from sgd_def_continentes order by id_cont";
$rscont = $db->conn->Execute($sqlcont);
if(!$id_cont) $id_cont= 0;
$valor="";
	print $rscont->GetMenu2("cont",$cont,"0:-- Seleccione --", false,0,"onchange= submit() class='select'");
?></td>

<td width="33%">
<?php
$sqlpais ="Select nombre_pais, id_pais from sgd_def_paises where id_cont= '$cont'";
$rspais = $db->conn->Execute($sqlpais);
if(!$id_pais) $id_pais= 0;
//print  "PAIS: $pais1";
print $rspais->GetMenu2("pais",$pais1,"0:-- Seleccione --",false, 0,"onchange= submit() class='select'");
?></td>
<td width="34%" align="center" valign="bottom">
<?php
$sqldpto="Select dpto_nomb, dpto_codi from departamento
          where id_cont='$cont'
		  and id_pais='$pais1'
		  and dpto_codi <> 1
		  order by dpto_nomb";
$rsdpto=$db->conn->Execute($sqldpto);
if(!$s_dpto) $s_dpto=0;
print $rsdpto->GetMenu2("dpto",$dpto,"0:-- Seleccione --",false, 0,"onchange= submit() class='select'");
?></td>
</tr>
<tr>
<td width="30%" align="center" valign="middle" class="titulos2">MUNICIPIO</td>
<td width="30%" align="center" valign="middle" class="titulos2">REPRESENTANTE</td>
<td width="30%" align="center" valign="middle" class="titulos2">CARGO</td>
</tr>
<tr align="center">
<td width="33%">
<?php
$sqlmuni="Select muni_nomb, muni_codi from municipio
          where id_cont='$cont'
		  and id_pais='$pais1'
		  and dpto_codi = '$dpto'
		  order by muni_nomb";
$rsdpto=$db->conn->Execute($sqlmuni);
if(!$s_muni) $s_muni=0;
print $rsdpto->GetMenu2("muni",$muni,"0:-- Seleccione --",false, 0,"onchange= submit() class='select'");
?></td>
<td width="33%"><input class="tex_area" type="text" name="txt_rep" id="txt_rep" size="40" maxlength="60" value='<?=$txt_rep?>'></td>
<td width="34%" align="center" valign="bottom"><input class="tex_area" type="text" name="txt_cargo" id="txt_cargo" maxlength="50" size="30" value='<?=$txt_cargo?>'></td>
</table>
<center>
	<input name="Incluir" type="submit"  class="botones" id="envia22"   value="Incluir">&nbsp;&nbsp;
	<input class="botones" type="button" value="Limpiar" onClick="limpiar();">
</center>
<?
if(!empty($_POST['Incluir'])&& ($_POST['Incluir']=="Incluir"))
{
	$sql2="select nit_de_la_empresa, nombre_de_la_empresa FROM BODEGA_EMPRESAS WHERE nit_de_la_empresa= '".ltrim($_POST['txt_nit'])."' OR nombre_de_la_empresa like '%".strtoupper($_POST['txt_esp'])."%'";

 	$rscod = $db->conn->Execute($sql2);
 //$db->conn->debug = true;
	$carnit=$rscod->fields['NIT_DE_LA_EMPRESA'];
	$caresp=$rscod->fields['NOMBRE_DE_LA_EMPRESA'];
	if ($carnit=="" || $caresp==""){
		$sql="select max(identificador_empresa) as ID from bodega_empresas";
		$rsconse=$db->conn->Execute($sql);
		$conse=$rsconse->fields['ID'];
		$conse=$conse+1;
		$sinsert=("INSERT INTO BODEGA_EMPRESAS(IDENTIFICADOR_EMPRESA, NUIR, NOMBRE_DE_LA_EMPRESA, 	NIT_DE_LA_EMPRESA, SIGLA_DE_LA_EMPRESA, DIRECCION, CODIGO_DEL_DEPARTAMENTO, CODIGO_DEL_MUNICIPIO, TELEFONO_1, 	TELEFONO_2, EMAIL, NOMBRE_REP_LEGAL, CARGO_REP_LEGAL, ARE_ESP_SECUE, BOD_EMP_FECH_CREA, ID_PAIS, ID_CONT, ACTIVA) VALUES (".$conse.", '".$_POST['txt_nuir']."', '".strtoupper($_POST['txt_esp'])."', '".ltrim($_POST['txt_nit'])."', '".strtoupper($_POST['txt_sigla'])."', '".strtoupper($_POST['txt_dir'])."', ".$dpto.", ".$muni.", '".$_POST['txt_tel1']."', '".$_POST['txt_tel2']."', '".strtolower($_POST['txt_mail'])."', '".strtoupper($_POST['txt_rep'])."', '".strtoupper($_POST['txt_cargo'])."', ".$conse.", SYSDATE, ".$pais1.", ".$cont.", 1)");
		$rsinser=$db->conn->Execute($sinsert);
		$rsinser=$db->conn->CompleteTrans();		
		 //$db->conn->debug = true;
?>
 		<script>
		 		 alert("Empresa Creada Con Exito");
		</script>
<?
}
	else{
?>
		<script>
	   resp=confirm("Esta Empresa ya Existe, no es Posible su Creación , desea modificarla , recuerde volver a buscarla para la edicion");
       if (resp==true)		
	   location.href='editar.php?usModo=2&<?=$phpsession ?>&krd=<?=$krd?>'; 
		</script>
<?
}
}
?>
</form>

</body>
</html>
