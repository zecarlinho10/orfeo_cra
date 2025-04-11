<?
/*************************************************************************************/
/* ORFEO GPL:Sistema de Gestion Documental		http://www.orfeogpl.org	     */
/*	Idea Original de la SUPERINTENDENCIA DE SERVICIOS PUBLICOS DOMICILIARIOS     */
/*				COLOMBIA TEL. (57) (1) 6913005  orfeogpl@gmail.com   */
/* ===========================                                                       */
/*                                                                                   */
/* Este programa es software libre. usted puede redistribuirlo y/o modificarlo       */
/* bajo los terminos de la licencia GNU General Public publicada por                 */
/* la "Free Software Foundation"; Licencia version 2. 			                     */
/*                                                                                   */
/* Copyright (c) 2005 por :	  	  	                                                 */
/* C.R.A.  "COMISION DE REGULACION DE AGUA"                                          */
/*   Lucia Ojeda          lojedaster@gmail.com             Desarrolladora            */
/*																					 */
/* Colocar desde esta lInea las Modificaciones Realizadas Luego de la Version 3.5    */
/*  Nombre Desarrollador   Correo     Fecha   Modificacion                           */
/*************************************************************************************/
/**
 * Programa que despliega Radicados de entrada en un periodo determinado
 * @author  Mario Manotas Duran
 * @mail    mmanotas@cra.gov.co
 * @author  modify by Mario Manotas Duran
 * @mail    mmanotas@cra.gov.co    
 * @version     1.0
 */
$ruta_raiz = "../";
session_start();
error_reporting(0);
require_once($ruta_raiz."include/db/ConnectionHandler.php");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);

//$db->conn->debug=true;
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

//En caso de no llegar la dependencia recupera la sesi�n
if(empty($_SESSION)) include $ruta_raiz."rec_session.php";

include ("common.php");
$fechah = date("ymd") . "_" . date("hms");
$radicado=(isset($_POST['radicado']))?$_POST['radicado']:"";
$depe_nomb=(isset($_POST['depe_nomb']))?$_POST['depe_nomb']:"";
$fecha_ini=$_POST['fecha_ini'];
$fecha_fin=$_POST['fecha_fin'];

?>
<html>
<head>


<title>Reportes De Transaciones Con Radicados</title>


<?include ($ruta_raiz."/htmlheader.inc.php")?>


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

 <body><div class="table-responsive">
<div id="spiffycalendar" class="text"></div>

<?
$params = session_name()."=".session_id()."&krd=$krd";
?>

<form action="repAsignacionRadicados.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formEnvio" id="formEnvio" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>
			LISTADO DE TRANSACCIONES CON RADICADOS
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>	
	<tr align="center" colspan="2">
		<td width="31%" class='titulos2'>DEPENDENCIA</td>
		<td width="69%" height="30" class='listado2' align="left">
		
		 <?
$sql =  "SELECT 'Todas las dependencias' as DEPE_NOMB, 0 AS DEPE_CODI FROM DEPENDENCIA
   				 UNION SELECT 'SUBDIRECCION TECNICA CONSOLIDADO' AS DEPE_NOMB, 2 AS DEPE_CODI FROM DEPENDENCIA
				 UNION SELECT 'SUBDIRECCION ADMINISTRATIVA CONSOLIDADO' AS DEPE_NOMB, 3 AS DEPE_CODI FROM DEPENDENCIA
				 UNION SELECT 'OFICINA ASESORA JURÍDICA CONSOLIDADO' AS DEPE_NOMB, 4 AS DEPE_CODI FROM DEPENDENCIA
                 UNION  SELECT DEPE_NOMB, DEPE_CODI AS DEPE_CODI FROM DEPENDENCIA
                 WHERE DEPE_CODI NOT IN (900,905,999,910,1,321,210)
				 order by DEPE_NOMB DESC";

	$rsDep = $db->conn->Execute($sql);
	if(!$s_DEPE_CODI) $s_DEPE_CODI= 0;
	print $rsDep->GetMenu2("dep","$dep","0:-- Seleccione --", false, 0," class='select'");

	?>
	</td>
</tr>
<tr align="center" colspan="2">
	<td width="31%" class='titulos2'>TIPO DE RADICADO</td>
	 <td width="69%" height="30" class='listado2' align="left">
	 	<?
			$sqlrad = "SELECT sgd_trad_descr, sgd_trad_codigo FROM sgd_trad_tiporad";
			$rsrad = $db->conn->Execute($sqlrad);
			print $rsrad->Getmenu2("trad","$trad",false,0,"onChange = 'submit' class='select'");
		?>
	</td>
	</tr>
<tr align="center" colspan= "2">
		<td width="31%" class='titulos2'>TRANSACCION</td>
	  <td width="69%" height="30" class='listado2' align="left">
	  <?
	    $sqlt = "SELECT sgd_ttr_descrip, sgd_ttr_codigo FROM  sgd_ttr_transaccion
		         WHERE sgd_ttr_codigo in (13,65,25,71)
				 order by sgd_ttr_descrip";
				 $rsTran = $db->conn->Execute($sqlt);
		print $rsTran->Getmenu2("tran", "$tran", false, 0, "onChange = 'submit' class='select'");
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

   	
    	
function pintarResultados($fila,$i,$n){
		global $ruta_raiz;
		switch($n){
			case 0:
				$salida=$fila['RADICADO'];
 				break;
 			case 1:
				$salida=$fila['ASUNTO'];
				break;	
			case 2:
				$salida=$fila['FECH_RADI'];
				break;
			case 3:
				$salida=$fila['SGD_DIR_NOMREMDES'];
				break;	
			case 4:
				$salida=$fila['MUNI_NOMB'];
				break;
			case 5:
				$salida=$fila['DPTO_NOMB'];
				break;					
			case 6:
				$salida=$fila['OBSERVACION'];
				break;
			case 7:
				$salida=$fila['FECH_OBV'];
				break;		
		    case 8:
				$salida=$fila['DEPENDENCIA'];
				break;		
			case 9:
				$salida=$fila['USUARIO'];
				break;		

			default:$salida="ERROR";
		}
		return $salida;	
	}

if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar")){
	
	
			
			require_once($ruta_raiz."include/myPaginador.inc.php");

			


$where=null;
	
 			
			$where=(!empty($_POST['dep']) && ($_POST['dep'])!="")?"AND R.RADI_DEPE_RADI = ".$_POST['dep']:"";		  if ($dep==2)
{
  $where=" AND R.RADI_DEPE_RADI in (401,410,420,430) ";	
}	
if ($dep==3)
{
  $where=" AND R.RADI_DEPE_RADI in (301,310,320,341,350) ";	
}
if ($dep==4)
{
  $where=" AND R.radi_depe_radi in (211,212,213,214,215) ";	
}						 					   			 		
			$where.= " AND  TRUNC(R.RADI_FECH_RADI) BETWEEN ".$db->conn->DBTimeStamp($fecha_ini)." AND ".$db->conn->DBTimeStamp($fecha_fin);

		
			$where.= " AND  H.SGD_TTR_CODIGO= ".$_POST['tran'];
			
			$where.= " AND  R.RADI_NUME_RADI LIKE '%".$_POST['trad']."'";
 			$order=1;  
			    	
		 
$titulos=array("1#RADICADO","2#ASUNTO","3#FECHA RADICACION","4#REMITENTE O DESTINO","5#MUNICIPIO","6#DEPARTAMENTO","7#OBSERVACION","8#FECHA_TRANSACCION", "9#DEPENDENCIA","10#USUARIO");
			
			$isql = "SELECT DISTINCT R.RADI_NUME_RADI AS RADICADO, R.RA_ASUN AS ASUNTO, 
					to_date(R.RADI_FECH_RADI,'yyyy/mm/dd hh24:mi:ss') AS FECH_RADI, 
					H.HIST_OBSE AS OBSERVACION, to_date(H.HIST_FECH,'yyyy/mm/dd hh24:mi:ss')AS FECH_OBV, 
					D.DEPE_NOMB AS DEPENDENCIA,U.USUA_NOMB AS USUARIO,
					sgd_dir_nomremdes, DPTO_NOMB, MUNI_NOMB 
					FROM RADICADO R
     				INNER JOIN HIST_EVENTOS H ON H.RADI_NUME_RADI = R.RADI_NUME_RADI
     				INNER JOIN DEPENDENCIA D ON D.DEPE_CODI = R.RADI_DEPE_RADI
     				INNER JOIN SGD_DIR_DRECCIONES DR ON DR.RADI_NUME_RADI = R.RADI_NUME_RADI,
     				MUNICIPIO M, DEPARTAMENTO DP, USUARIO U
					WHERE R.RADI_DEPE_RADI NOT IN (900, 905, 910, 999)
					AND DR.MUNI_CODI=M.MUNI_CODI 
					AND DR.DPTO_CODI=M.DPTO_CODI
					AND DR.DPTO_CODI=DP.DPTO_CODI
					AND DR.SGD_DIR_TIPO = 1
					AND U.USUA_DOC = H.USUA_DOC
					{$where}";
			
			//$db->conn->debug = true;
		
			$paginador= new myPaginador($db,$isql,1);
			$paginador->modoPintado(true);
			$paginador->setImagenASC($ruta_raiz."iconos/flechaasc.gif");
			$paginador->setImagenDESC($ruta_raiz."iconos/flechadesc.gif");
			$paginador->setFuncionFilas("pintarResultados");
			$paginador->generarPagina($titulos);
}
?>
</div></body>
</html>
