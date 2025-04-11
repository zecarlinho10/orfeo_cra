<?php
/*Modulo de Edicion de Empresa
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
$txt_esp1=$_POST['txt_esp1']; 
$txt_rep=$_POST['txt_rep']; 
$txt_nit=$_POST['txt_nit']; 
$txt_cargo=$_POST['txt_cargo']; 
$txt_dir=$_POST['txt_dir']; 
$txt_tel1=$_POST['txt_tel1']; 
$txt_email=$_POST['txt_email']; 

// Agregar Variables de la LiSTA.
$cont=$_POST['cont'];	
$muni=$_POST['muni'];	
$dpto=$_POST['dpto'];
$pais=$_POST['pais'];		
$pais_e=$_POST['pais_e'];

if($usModo ==2) $tituloCrear = "Editar Ente";
//$db->conn->debug=true;
$error = 0;
?>
<html>
<head><title>Untitled Document</title>
<link rel="stylesheet" href="../../../estilos/orfeo.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" type="text/JavaScript">
function envio_datos()
{
	if(isWhitespace(document.forms[0].txt_esp.value))
		{	alert("El campo Nombre Empresa no ha sido diligenciado.");
			document.forms[0].txt_esp.focus();
			return false;
		}
	  	else
	{
		document.forms[0].submit();
		return true;
		 
	}
}

</script>
<script>
function limpiar()
		{
	   document.formEsp.elements['txt_esp'].value = "";
	   document.formEsp.elements['txt_esp1'].value = "";
	   document.formEsp.elements['txt_iden'].value = "";
	   document.formEsp.elements['txt_nit'].value = "";
	   document.formEsp.elements['txt_tel1'].value = "";
	  
	   document.formEsp.elements['txt_dir'].value = "";
	   document.formEsp.elements['txt_mail'].value = "";
	   document.formEsp.elements['txt_rep'].value = "";
	   document.formEsp.elements['txt_cargo'].value = "";

 
  }
 </script>
<script>
iden_empresa = new Array();
cc_documento = new Array();
nombre = new Array();
repre = new Array();
cargo = new Array();
direccion = new Array();
tel1 = new Array();
email = new Array();

function pasar(indice)
{
<?
    error_reporting( 0 );
		
    print "document.formEsp.txt_iden.value = iden_empresa[indice];
    	   document.formEsp.txt_nit.value = cc_documento[indice];
	       document.formEsp.txt_esp1.value = nombre[indice];
	       document.formEsp.txt_rep.value = repre[indice];
	       document.formEsp.txt_cargo.value = cargo[indice];
	       document.formEsp.txt_dir.value = direccion[indice];
	       document.formEsp.txt_tel1.value = tel1[indice];
	       document.formEsp.txt_email.value = email[indice];"

?>
 
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
<form action="editarEnte.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formEsp" id="formEsp" onSubmit="return envio_datos();">
<table width="93%"  border="1" align="center">
  	<tr bordercolor="#FFFFFF">
    <td colspan="2" class="titulos4">
	<center>
	<p><B><span class=etexto>ADMINISTRACION DE ENTES</span></B> </p>
	<p><B><span class=etexto> <?=$tituloCrear ?></span></B> </p></center>
	</td>
	</tr>
</table>
<table border=1 width=93% class=t_bordeGris align="center">
<tr bordercolor = "#FFFFFF">
<td width="20%" align="center" class="titulos2">NOMBRE EMPRESA ENTE</td>
<td width="20%" align="center"><input class="tex_area" type="text" name="txt_esp" id="txt_esp" size="60" maxlength="200" value='<?=$txt_esp?>'></td>
<td width="20%" align="center"><input name="Buscar" type="submit"  class="botones" id="envia22"   value="Buscar"></td>
</tr>
</table>
<br>
<TABLE class="borde_tab" width="100%">
<tr class=listado2><td colspan=10>
<center>RESULTADO DE BUSQUEDA</center>
</td></tr></TABLE>
<table class=borde_tab width="100%" cellpadding="0" cellspacing="5">
  <!--DWLayoutTable-->
<tr class="grisCCCCCC" align="center"> 
	<td width="11%" CLASS="titulos5" >ID</td>
	<td width="11%" CLASS="titulos5" >NOMBRE</td>
	<td width="11%" CLASS="titulos5" >REPRESENTANTE</td>
	<td width="14%" CLASS="titulos5" >NIT</td>
	<td width="15%" CLASS="titulos5" >SIGLA EMPRESA</td>
	<td width="14%" CLASS="titulos5">DIRRECCION</td>
	<td width="9%" CLASS="titulos5" >TELEFONO</td>
	<td width="7%" CLASS="titulos5" >EMAIL</td>
	<td colspan="3" CLASS="titulos5" >SELECCION </td>
</tr> 
<?
if(!empty($_POST['Buscar'])&& ($_POST['Buscar']=="Buscar"))	
{
	$sqlesp="select SGD_OEM_CODIGO,SGD_OEM_OEMPRESA, SGD_OEM_REP_LEGAL,SGD_OEM_NIT,SGD_OEM_SIGLA,SGD_OEM_DIRECCION,SGD_OEM_TELEFONO,EMAIL
	from SGD_OEM_OEMPRESAS where SGD_OEM_OEMPRESA like '%".strtoupper($_POST['txt_esp'])."%' and SGD_OEM_ESTADO=1";
	//$db->conn->debug = true;
	$rsBuscar=$db->query($sqlesp);
	if($rsBuscar && !$rsBuscar->EOF)
	{
		$i=0;
		while(!$rsBuscar->EOF)
		{
?>
	<td width="11%" CLASS="titulos5" ><font size="-3"><?=$rsBuscar->fields['SGD_OEM_CODIGO'] ?></font></td>
	<td width="11%" CLASS="titulos5" ><font size="-3"><?=$rsBuscar->fields['SGD_OEM_OEMPRESA']?></font></td>
	<td width="11%" CLASS="titulos5" ><font size="-3"><?=$rsBuscar->fields['SGD_OEM_REP_LEGAL']?></font></td>
	<td width="14%" CLASS="titulos5" ><font size="-3"><?=$rsBuscar->fields['SGD_OEM_NIT'] ?></font></td>
	<td width="15%" CLASS="titulos5" ><font size="-3"><?=$rsBuscar->fields['SGD_OEM_SIGLA'] ?></font></td>
	<td width="14%" CLASS="titulos5"><font size="-3"><?=$rsBuscar->fields['SGD_OEM_DIRECCION'] ?></font></td>
	<td width="9%" CLASS="titulos5" ><font size="-3"><?=$rsBuscar->fields['SGD_OEM_TELEFONO'] ?></font></td>
	<td width="7%" CLASS="titulos5" ><font size="-3"><?=$rsBuscar->fields['EMAIL']?></font></td>
	<td width="7%" CLASS="titulos5" ><input name="Envio" type="submit"  class="botones" id="Envio"   value="..." onClick="pasar('<?=$i ?>');"></td>  
  
	</tr>
  <script>
		<?  
		$iden_empresa =$rsBuscar->fields['SGD_OEM_CODIGO']; 
		$nombre = $rsBuscar->fields['SGD_OEM_OEMPRESA'];
	    $cc_documento = $rsBuscar->fields['SGD_OEM_NIT'];
	    $repre= $rsBuscar->fields['SGD_OEM_REP_LEGAL'];
	    $cargo =	$rsBuscar->fields['SGD_OEM_SIGLA'];
	    $direccion=$rsBuscar->fields['SGD_OEM_DIRECCION'];
	    $tel1 = $rsBuscar->fields['SGD_OEM_TELEFONO'];
	    $email=$rsBuscar->fields['EMAIL'];
			
		?>  iden_empresa[<?=$i?>]= "<?=$iden_empresa?>";
		    nombre[<?=$i?>]= "<?=$nombre?>";
			cc_documento[<?=$i?>]= "<?=$cc_documento?>";
			repre[<?=$i?>]= "<?=$repre?>";
			cargo[<?=$i?>]= "<?=$cargo?>";
			direccion[<?=$i?>]= "<?=$direccion?>";
			tel1[<?=$i?>]= "<?=$tel1?>";
			email[<?=$i?>]= "<?=$email?>";
 </script>	
  <? 	
	$i++;
	$rsBuscar->MoveNext();

	}
	}
	else
	{
?>
	<script>
		 	alert("No Se Encontraron Registros");
	</script>
<?
	}
}
?>
</table>
<br>
<table class=borde_tab width="100%" cellpadding="0" cellspacing="4">
<tr align="center" > 
	<td CLASS=titulos5>ID</td>
	<td CLASS=titulos5>NOMBRE DEL ENTE</td>
	<td CLASS=titulos5>REPRESENTANTE LEGAL</td>
	<td CLASS=titulos5>NIT</td>
	<td CLASS=titulos5>SIGLA</td>
	<td CLASS=titulos5>DIRECCION</td>
	<td CLASS=titulos5>TELEFONO</td>
	<td CLASS=titulos5>EMAIL</td>
	<td CLASS=titulos5>ESTADO</td>
	
</tr>
<tr class='listado5' align="center"> 
<td><input type="text" name="txt_iden" class="e_cajas" size="15" value='<?=$txt_iden?>' readonly></td>
<td><input type="text" name="txt_esp1" class="e_cajas" size="20" value='<?=$txt_esp1?>'></td>
<td><input type="text" name="txt_rep" class="e_cajas" size="20" value='<?=$txt_rep?>'></td>
<td><input type="text" name="txt_nit" class="e_cajas" size="15" value='<?=$txt_nit?>' maxlength="9" onkeypress="return isNumberKey(event)"></td>

<td><input type="text" name="txt_cargo" class="e_cajas" size="20" value='<?=$txt_cargo?>'></td>
<td><input type="text" name="txt_dir" class="e_cajas" size="20" value='<?=$txt_dir?>'></td>
<td><input type="text" name="txt_tel1" class="e_cajas" size="20" value='<?=$txt_tel1?>'></td>
<td><input type="text" name="txt_email" class="e_cajas" size="15" value='<?=$txt_email?>'></td>
<td width="34%" align="center">
<select name="Slc_act" id="Slc_act" class="select" onChange="submit()">
   	<option value=1 selected="selected"> Activa </option>
	<option value=0> Inactiva </option>
</select></td>
</tr>
<tr>
	
<td CLASS=titulos5>CONTINENTE</td>
	<td CLASS=titulos5>PAIS</td>
	<td CLASS=titulos5>DPTO</td>
	<td CLASS=titulos5>MUNICIPIO</td>
	<td colspan="2" rowspan="2" CLASS=grisCCCCCC>
		<center>
			<input name="Modificar" type="submit"  class="botones" id="envia22"   value="Modificar">&nbsp;&nbsp;
			<input class="botones" type="button" value="Limpiar" onClick="limpiar();">
		</center>
	</td>
	</tr>
<tr align="center">
<td width="33%">
<?php

$sqlcont0 ="Select b.ID_CONT, C.nombre_cont from SGD_OEM_OEMPRESAS b, sgd_def_continentes C 
			where b.SGD_OEM_CODIGO=".$_POST['txt_iden']." 
			AND b.ID_CONT=C.id_cont";
			//echo $sqlcont0;
//$db->conn->debug = true;
$rscont0 = $db->conn->Execute($sqlcont0);
$cont_e=$rscont0->fields["ID_CONT"];
$cont_en=$rscont0->fields["NOMBRE_CONT"];

$sqlcont ="Select nombre_cont, id_cont, $cont_e as cont_id,'$cont_en' as cont_nombre 
			from sgd_def_continentes  ";
//$db->conn->debug = true;
$rscont = $db->conn->Execute($sqlcont);

if(!$id_cont) $id_cont= $cont_e;
	print $rscont->GetMenu2("cont",$cont_e,false,0,"onchange= submit() class='select'");
?></td>
<td width="33%">
<?php
$sqlpais0 ="Select b.id_pais, C.nombre_pais from SGD_OEM_OEMPRESAS b, sgd_def_paises C 
			where b.SGD_OEM_CODIGO='".$_POST['txt_iden']."' 
			AND b.id_pais=C.id_pais and b.id_cont='$cont_e'";
//$db->conn->debug = true;
//echo $sqlpais0;
$rspais0 = $db->conn->Execute($sqlpais0);
$pais_e=$rspais0->fields["ID_PAIS"];
$pais_en=$rspais0->fields["NOMBRE_PAIS"];
$sqlpais ="Select nombre_pais, id_pais, $pais_e as pais_id,'$pais_en' as pais_nombre  from sgd_def_paises ";
//echo $sqlpais0;
$rspais = $db->conn->Execute($sqlpais);
if(!$id_pais) $id_pais= $pais_e;
print $rspais->GetMenu2("pais",$pais_e,false,0,"onchange= submit() class='select'");


?></td>
<td width="34%" align="center" valign="bottom">
<?php
$sqldpto0 ="Select b.DPTO_CODI, C.dpto_nomb from SGD_OEM_OEMPRESAS b, departamento C 
			where b.SGD_OEM_CODIGO='".$_POST['txt_iden']."' 
			AND b.DPTO_CODI=C.dpto_codi and b.id_pais='$pais_e'";
//$db->conn->debug = true;
$rsdpto0 = $db->conn->Execute($sqldpto0);
$dep_e=$rsdpto0->fields["DPTO_CODI"];
$dep_en=$rsdpto0->fields["DPTO_NOMB"];

$sqldpto="Select dpto_nomb, dpto_codi, $dep_e as dep, '$dep_en' as nomdep from departamento order by dpto_nomb ";
$rsdpto=$db->conn->Execute($sqldpto);
if(!s_dpto) $s_dpto=$dep_e;
print $rsdpto->GetMenu2("dpto",$dep_e,false,0,"onchange= submit() class='select'");
?></td>
<td width="34%" align="center" valign="bottom">
<?php
$sqlmuni0 ="Select b.DPTO_CODI, b.MUNI_CODI, C.muni_nomb from SGD_OEM_OEMPRESAS b, municipio C 
			where SGD_OEM_CODIGO='".$_POST['txt_iden']."' 
			AND b.MUNI_CODI=C.muni_codi 
			AND b.DPTO_CODI=C.dpto_codi";
//$db->conn->debug = true;
$rsmuni0 = $db->conn->Execute($sqlmuni0);
$muni_e=$rsmuni0->fields["MUNI_CODI"];
$muni_en=$rsmuni0->fields["MUNI_NOMB"];

$sqlmuni="Select muni_nomb, muni_codi,$muni_e as codmuni, '$muni_en' as nommuni  from municipio where dpto_codi='$dep_e' order by muni_nomb";
$rsmuni=$db->conn->Execute($sqlmuni);
if(!s_muni) $s_muni=$muni_e;
print $rsmuni->GetMenu2("muni",$muni_e,false, 0,"onchange= submit() class='select'");
?></td>
<?
if(!empty($_POST['Modificar'])&& ($_POST['Modificar']=="Modificar"))
{
	
	
	$supdate= ("update SGD_OEM_OEMPRESAS set SGD_OEM_NIT='".ltrim($_POST['txt_nit'])."', SGD_OEM_OEMPRESA ='".strtoupper($_POST['txt_esp1'])."',SGD_OEM_REP_LEGAL='".strtoupper($_POST['txt_rep'])."',SGD_OEM_SIGLA='".strtoupper($_POST['txt_cargo'])."',SGD_OEM_DIRECCION='".strtoupper($_POST['txt_dir'])."',SGD_OEM_TELEFONO='".$_POST['txt_tel1']."',EMAIL='".strtolower($_POST['txt_email'])."',id_cont=".$cont.",id_pais=".$pais_e.",DPTO_CODI=".$dpto.",MUNI_CODI=".$muni." where SGD_OEM_CODIGO=".$_POST['txt_iden']);
        //$db->conn->debug = true;
		$rsupdate=$db->conn->Execute($supdate);
		$rsupdate=$db->conn->CompleteTrans();
		
		
?>
    <script>
		 		 alert("Empresa Actualizada Con Exito");
	</script>
<?
}
?>
</table>
</form>
</body>
</html>