<?
 /* Programa que despliega Radicados de Salidas anulados en un periodo determinado
 * @author  Jully Quicano
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
$fecha_ini=$_POST['fecha_ini'];
$fecha_fin=$_POST['fecha_fin'];  
$dep=(isset($_POST['dep']))?$_POST['dep']:"";

?>
<html>
<head>


<title>Reportes Radicados Anulados </title>


<?include ($ruta_raiz."/htmlheader.inc.php")?>


	<script language="JavaScript" type="text/JavaScript">
/**
* Env�a el formulario de acuerdo a la opci�n seleccionada, que puede ser ver CSV o consultar
*/
function enviar(argumento)
{	document.formRadi.action=argumento+"&"+document.formRadi.params.value;
	document.formRadi.submit();
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
   var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "formRadi", "fecha_ini","btnDate1","<?=$fecha_ini?>",scBTNMODE_CUSTOMBLUE);
   var dateAvailable2 = new ctlSpiffyCalendarBox("dateAvailable2", "formRadi", "fecha_fin","btnDate2","<?=$fecha_fin?>",scBTNMODE_CUSTOMBLUE);

--></script>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>

 <body><div class="table-responsive">
<div id="spiffycalendar" class="text"></div>

<?
$params = session_name()."=".session_id()."&krd=$krd";
?>

<form action="repanulados.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formRadi" id="formRadi" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			LISTADO DE RADICADOS ANULADOS
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>	
	<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>DEPENDENCIA</td>
		<td width="69%" height="30" class='listado2' align="left">
		
		 <?
$sql = $sql = "SELECT 'Todas las dependencias' as DEPE_NOMB, 0 AS DEPE_CODI FROM DEPENDENCIA
                 UNION  SELECT DEPE_NOMB, DEPE_CODI AS DEPE_CODI FROM DEPENDENCIA
                 WHERE DEPE_CODI NOT IN (900,905,999,910,1,321,210)
				 order by DEPE_NOMB DESC";
	$rsDep = $db->conn->Execute($sql);
	if(!$s_DEPE_CODI) $s_DEPE_CODI= 0;
	print $rsDep->GetMenu2("dep","$dep",false, false, 0," class='select'");

	?>
	</td>
</tr>
	 
<tr>
    <td align="center" width="30%" class="titulos2">Desde  fecha (aaaa/mm/dd) </td>
    <td class="listado2">
	<script language="javascript">
	dateAvailable.writeControl();
	dateAvailable.dateFormat="yyyy/MM/dd";
	</script>&nbsp;</td>
  </tr>
  
<tr>
    <td align="center" width="30%" class="titulos2">Hasta  fecha (aaaa/mm/dd) </td>
    <td class="listado2">
	<script language="javascript">
	dateAvailable2.writeControl();
	dateAvailable2.dateFormat="yyyy/MM/dd";
	</script>&nbsp;</td>
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
	
 			
			$where=(!empty($_POST['dep']) && ($_POST['dep'])!="")?"AND a.radi_depe_radi = ".$_POST['dep']:"";		   					   			
	 		
			$where.= " AND  RADI_FECH_RADI>=".$db->conn->DBTimeStamp($fecha_ini)." AND RADI_FECH_RADI<=".$db->conn->DBTimeStamp($fecha_fin);
 		
 			
 			$order=1;  
			    	
		 
$titulos=array("RADICADO","FECHA RADICACION","DEPENDENCIA","DESTINO","UBICACION","JUSTIFICACION","ACTA");
			
			$isql = "select distinct A.RADI_NUME_RADI AS SALIDA,to_char(A.radi_fech_radi,'yyyy/mm/dd hh24:mi:ss') AS FECHAS,B.DEPE_NOMB AS DEPENDENCIA, 
					 sgd_dir_nomremdes,(MUNI_NOMB||' - '||DPTO_NOMB)as UBICACION, N.SGD_ANU_DESC, N.USUA_ANU_ACTA
					 from radicado A
					INNER JOIN SGD_DIR_DRECCIONES DR ON A.RADI_NUME_RADI=DR.RADI_NUME_RADI
     				INNER JOIN SGD_ANU_ANULADOS N ON N.RADI_NUME_RADI=A.RADI_NUME_RADI,
     				MUNICIPIO M, DEPARTAMENTO DP, ANEXOS X,
    				dependencia B
					where A.radi_nume_radi LIKE'%1' and A.SGD_EANU_CODIGO >= 1 and A.SGD_EANU_CODIGO < 9 
					AND RADI_DEPE_RADI NOT IN (905,900,910) AND (A.radi_depe_radi = B.DEPE_CODI)
					AND X.RADI_NUME_SALIDA=A.RADI_NUME_RADI
			    	{$where}
			    	AND DR.MUNI_CODI=M.MUNI_CODI 
					AND DR.DPTO_CODI=M.DPTO_CODI
					AND DR.DPTO_CODI=DP.DPTO_CODI
					AND X.ANEX_TIPO=1
					
					ORDER BY A.RADI_NUME_RADI";
			
			//$db->conn->debug = true;
		
			$paginador= new myPaginador($db,$isql,1);
			$paginador->modoPintado(true);
			$paginador->setImagenASC($ruta_raiz."iconos/flechaasc.gif");
			$paginador->setImagenDESC($ruta_raiz."iconos/flechadesc.gif");
			$paginador->setFuncionFilas("pintarResultados");
			//$paginador->generarPagina($titulos);
error_reporting(0);
	require "../anulacion/class_control_anu.php";
	$btt = new CONTROL_ORFEO($db);
	$campos_align = array("L","L","L","L","L","L","C");
	$campos_tabla = array("SALIDA","FECHAS","DEPENDENCIA","SGD_DIR_NOMREMDES","UBICACION","SGD_ANU_DESC", "USUA_ANU_ACTA");
	$btt->campos_align = $campos_align;
	$btt->campos_tabla = $campos_tabla;
	$btt->campos_vista = $titulos;
	$btt->campos_width = $campos_width;
	
	$btt->tabla_sql($isql);
						
}
?>
</div></body>
</html>
