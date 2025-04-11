<?

/**
 * Programa realiza el calculo entre operacion de radicacion y digitalizacion
 * @author  JUAN CARLOS VILLALBA CARDENAS
 * @mail    jvillalba@cra.gov.co
 * @version     1.0
 */
$ruta_raiz = "../";
session_start();
error_reporting(0);
require_once($ruta_raiz."include/db/ConnectionHandler.php");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

//En caso de no llegar la dependencia recupera la sesi�n
if(empty($_SESSION)) include $ruta_raiz."rec_session.php";

include ("common.php");
$fechah = date("ymd") . "_" . date("hms");
$radicado=(isset($_POST['radicado']))?$_POST['radicado']:"";
$depe_nomb=(isset($_POST['depe_nomb']))?$_POST['depe_nomb']:"";

//SE CAPTURA VARIABLE DE SESSION 07/01/2016 JUAN CARLOS VILLALBA
$krd         = $_SESSION["krd"];

$sqlUs = "select usua_login, codi_nivel, depe_codi, USUA_LOGIN,USUA_PASW from USUARIO where usua_login='$krd' ";
$rssqlus=$db->query($sqlUs);
//$usuacod  = $rssqlus->fields['CODI_NIVEL'];
//$depecod  = $rssqlus->fields['DEPE_CODI'];
$LOGIN  = $rssqlus->fields['USUA_LOGIN'];
//$CONTRA  = $rssqlus->fields['USUA_PASW'];


if( ( $LOGIN =='JH' OR $LOGIN =='SYEPESD' or $LOGIN =='PRUEBA2') )

{
//echo "SU DEPENDENCIA ES " .$depecod;
//echo "SU USUARIO ES " .$LOGIN;
//echo "SU USUARIO ES " .$CONTRA;

?>
<html>
<head>


<title>Reportes De Busqueda Radicados</title>


<link rel="stylesheet" href="<?php echo $ruta_raiz?>estilos/orfeo.css">


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>     
     <script src="https://raw.github.com/franz1628/validacionKeyCampo/383dab045bbc992120c4276e9d6f2c3a852f60b9/validCampoFranz.js"></script>
       <script type="text/javascript">
            $(function(){
                //Para escribir solo letras
                $('#miCampo1').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou');

                //Para escribir solo numeros    
                $('#comen').validCampoFranz('0123456789');    
            });
        </script>

	<script language="JavaScript" type="text/JavaScript">
/**
* Env�a el formulario de acuerdo a la opci�n seleccionada, que puede ser ver CSV o consultar
*/
function enviar(argumento)
{	document.formEnvio.action=argumento+"&"+document.formEnvio.params.value;
	document.formEnvio.submit();
}


function activa_chk(forma)
{	//alert(forma.tbusqueda.value);
	//var obj = document.getElementById(chk_desact);
	if (forma.slc_tb.value == 0)
		forma.chk_desact.disabled = false;
	else
		forma.chk_desact.disabled = true;
}

	function pasar_datos(fecha)
   {
    <?
	 echo " opener.document.VincDocu.numRadi.value = fecha\n";
	 echo "opener.focus(); window.close();\n";
	?>
}

function adicionarOp (forma,combo,desc,val,posicion){
	o = new Array;
	o[0]=new Option(desc,val );
	eval(forma.elements[combo].options[posicion]=o[0]);
	//alert ("Adiciona " +val+"-"+desc );
	
}

function esInteger(e) {
var charCode 
charCode = e.keyCode 
status = charCode 
if (charCode > 31 && (charCode < 48 || charCode > 57)) {
return false
}
return true
}

</script>
</script>


	 
<link rel="stylesheet" type="text/css" href="../js/spiffyCal/spiffyCal_v2_1.css">
			<script language="JavaScript" src="../js/spiffyCal/spiffyCal_v2_1.js"></script>
			<script language="JavaScript" type="text/JavaScript">  
				setRutaRaiz('<?php echo $ruta_raiz; ?>')
		 <!--
			<?
				$ano_ini = date("Y");
				$mes_ini = substr("00".(date("m")-1),-2);
				if ($mes_ini==0) {$ano_ini=$ano_ini-1; $mes_ini="12";}
				$dia_ini = date("d");
				if(!$fecha_ini) $fecha_ini = "$ano_ini/$mes_ini/$dia_ini";
					$fecha_busq = date("Y/m/d") ;
				if(!$fecha_fin) $fecha_fin = $fecha_busq;
			?>
   var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "formEnvio", "fecha_ini","btnDate1","<?=$fecha_ini?>",scBTNMODE_CUSTOMBLUE);
   var dateAvailable2 = new ctlSpiffyCalendarBox("dateAvailable2", "formEnvio", "fecha_fin","btnDate2","<?=$fecha_fin?>",scBTNMODE_CUSTOMBLUE);

--></script>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>

<body>
<div id="spiffycalendar" class="text"></div>

<?
$params = session_name()."=".session_id()."&krd=$krd";
?>

<form action="busquedaradicado2.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formEnvio" id="formEnvio" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='borde_tab'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			REPORTE DE BUSQUEDA DE RADICADOS POR USUARIO
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>	
    
	<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>RADICADO
		<td class="listado2" height="1">
		<input type=number name=comen id=comen value='<?=$comen?>' size=50 maxlength="14" ></td>
			
	</td>
</tr>

	
	<tr align="center">
		<td height="30" colspan="4" class='listado2'>
			<center>
			<input name="Consultar" type="submit"  class="botones" id="envia22"   value="Consultar">&nbsp;&nbsp;
		    </center>
		</td>
	</tr>
	
</table>
</form>
<?php 
if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar")){
require_once($ruta_raiz."include/myPaginador.inc.php");
$where=null;

$where.=(!empty($_POST['comen']) && ($_POST['comen'])!="")?" AND re.radicado=".$_POST['comen']:"";

$sql="SELECT USUA_LOGIN,USUA_DOC FROM USUARIO WHERE USUA_CODI='$usua' AND DEPE_CODI='$depe'";
$rs = $db->conn->Execute($sql);
$login	= $rs->fields['USUA_DOC'];
$login1	= $rs->fields['USUA_LOGIN'];	
 	
$order=1;  


$fecha_fin = mktime(23,59,59,substr($fecha_fin,5,2),substr($fecha_fin,8,2),substr($fecha_fin,0,4));

$isqlbusq="select re.radicado RA,r.ra_asun ASU,d.sgd_dir_nomremdes SOL,re.fecha FECH,re.login LOG,u.usua_nomb NOMB,d.depe_nomb CODNOM,re.ip UBI from REGISTRO re , usuario u , radicado r ,DEPENDENCIA D ,SGD_DIR_DRECCIONES d
where d.radi_nume_radi=r.radi_nume_Radi
and d.radi_nume_radi=r.radi_nume_Radi
and r.radi_nume_radi=re.radicado
and u.usua_login=re.login
and D.DEPE_CODI=U.DEPE_CODI
{$where} 
order by FECH
";  			
//$db->conn->debug = true;
//$rs = $db->conn->Execute($isqlbusq);	
}
		    	
$titulos=array("RADICADO CONSULTADO", "ASUNTO DEL RADICADO","NOMBRE SOLICITANTE","FECHA DE CONSULTA","LOGIN","FUNCIONARIO QUE CONSULTA" ,"DEPENDENCIA QUE CONSULTA","IP");
			$paginador= new myPaginador($db,$isqlbusq,1);
			$paginador->modoPintado(true);
			$paginador->setImagenASC($ruta_raiz."iconos/flechaasc.gif");
			$paginador->setImagenDESC($ruta_raiz."iconos/flechadesc.gif");
			$paginador->setFuncionFilas("pintarResultados");
			//$paginador->generarPagina($titulos);
			
		error_reporting(0);
	require "../anulacion/class_control_anu.php";
	$btt = new CONTROL_ORFEO($db);
	$campos_align = array("L","L","L","L","C");
	$campos_tabla = array("RA", "ASU", "SOL", "FECH","LOG","NOMB","CODNOM","UBI");
	$btt->campos_align = $campos_align;
	$btt->campos_tabla = $campos_tabla;
	$btt->campos_vista = $titulos;
	$btt->campos_width = $campos_width;
	$btt->tabla_sql($isqlbusq);
}
ELSE{
echo "<font color='red'><center>Este Reporte solo es visible Para la Direccion Ejecutiva</center></font>";	
echo "<font color='red'><center>Si desea Mirar este reporte debe solicitarlo a la Direccion Ejecutiva</center></font>";	
echo "SU USUARIO ES " .$krd;
echo "SU USUARIO ES " .$CONTRA;
}
?>
</body>
</html>