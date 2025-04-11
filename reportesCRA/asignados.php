<?
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
/*************************************************************************************/
/* Este programa es software libre. usted puede redistribuirlo y/o modificarlo       */
/* bajo los terminos de la licencia GNU General Public publicada por                 */
/* la "Free Software Foundation"; Licencia version 2. 			             */
/*                                                                                   */
/* Copyright (c) 2005 por :	  	  	                                     */
/* C.R.A.  "COMISION DE REGULACION DE AGUA"                                          */
/*  Carlos Ricaurte        cricaurte@cra.gov.co  mayo 2024 reporte de asignaciones	 */
/*  Nombre Desarrollador   Correo                Fecha     Modificacion              */
/*************************************************************************************/

$ruta_raiz = "../";
session_start();
require_once($ruta_raiz."include/db/ConnectionHandler.php");

include_once "$ruta_raiz/tx/diasHabiles.php";
include_once "$ruta_raiz/include/utils/Calendario.php";

if (!$db)	$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

//En caso de no llegar la dependencia recupera la sesi n
if(empty($_SESSION)) include $ruta_raiz."rec_session.php";

include ("common.php");

$fechah = date("ymd") . "_" . date("hms");
//$radicado=(isset($_POST['radicado']))?$_POST['radicado']:"";
$depe_nomb=(isset($_POST['depe_nomb']))?$_POST['depe_nomb']:"";
$fecha_ini=$_REQUEST['fecha_ini'];
$fecha_fin=$_REQUEST['fecha_fin'];
$dependencia_id=$_REQUEST['dependencia_id'];
$cedula=$funcionario_id=$_REQUEST['funcionario_id'];
$asignacion=$_REQUEST['asignacion_id'];

$rs_dep=$db->query("SELECT DEPE_CODI, DEPE_NOMB FROM dependencia WHERE DEPE_ESTADO=1 ORDER BY DEPE_NOMB");
$raidDependencias = array();

$i=0;
while(!empty($rs_dep) && !$rs_dep->EOF){ 
 	$raidDependencias[$i]=$rs_dep->fields; 
	$i++;
	$rs_dep->MoveNext ();
}

include_once('../adodb/adodb-paginacion.inc.php');
?>
<html>
<head>


<title>Reporte PQR's</title>


<?include ($ruta_raiz."/htmlheader.inc.php")?>


<script language="JavaScript" type="text/JavaScript">
	function enviar(argumento){
		document.formSeleccion.action=argumento+"&"+document.formRadi.params.value;
		document.formSeleccion.submit();
	}


</script>


<link rel="stylesheet" type="text/css" href="../js/spiffyCal/spiffyCal_v2_1.css">
    <script language="JavaScript" src="../js/spiffyCal/spiffyCal_v2_1.js"></script>
    <script language="JavaScript" type="text/JavaScript">      
           <?
                $ano_ini = date("Y");
                $mes_ini = substr("00".(date("m")-1),-2);
                if ($mes_ini==0) {$ano_ini=$ano_ini-1; $mes_ini="12";}
                $dia_ini = date("d");
                if(!$fecha_ini) $fecha_ini = "$ano_ini/$mes_ini/$dia_ini";
                    $fecha_busq = date("Y/m/d") ;
                if(!$fecha_fin) $fecha_fin = $fecha_busq;
            ?>
   var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "formSeleccion", "fecha_ini","btnDate1","<?=$fecha_ini?>",scBTNMODE_CUSTOMBLUE);
   var dateAvailable2 = new ctlSpiffyCalendarBox("dateAvailable2", "formSeleccion", "fecha_fin","btnDate2","<?=$fecha_fin?>",scBTNMODE_CUSTOMBLUE);

	</script>

</head>

 <body><div class="table-responsive">
<div id="spiffycalendar" class="text"></div>

<?
$params = session_name()."=".session_id()."&krd=$krd";
?>

<form action="asignados.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>ASIGNACIONES
	        	<input name="accion" type="hidden" id="accion">
	        	<input type="hidden" name="params" value="<?=$params?>">
      		</td>
	</tr>	
	
	<tr align="center">
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
	</tr>
	<div>
		<tr align="center">
			<td align="center" width="30%" class="titulos2">Dependencia</td>
		    <td class="listado2">
		    	<select id="dependencia_id" class="form-control" name="dependencia_id" required>
		      		<option value="">-- SELECCIONE --</option>
					<?php foreach($raidDependencias as $d):?>
				    <option value="<?php echo $d["DEPE_CODI"] ?>"><?php echo $d["DEPE_NOMB"] ?></option>
					<?php endforeach; ?>
		    	</select>
		    </td>
		</tr>
	</div>

	<div class="form-group">
		<tr align="center">
		    <td align="center" width="30%" class="titulos2">Funcionario</td>
		    <td class="listado2">
			    <select id="funcionario_id" class="form-control" name="funcionario_id" required>
			      <option value="">-- SELECCIONE --</option>
			   	</select>
		   </td>
		</tr>
  	</div>

  	<div class="form-group">
		<tr align="center">
		    <td align="center" width="30%" class="titulos2">Asignacion</td>
		    <td class="listado2">
			    <select id="asignacion_id" class="form-control" name="asignacion_id" required>
			      <option value="">-- SELECCIONE --</option>
			       <option value="1">Asignados</option>
			        <option value="2">Reasignados</option>
			   	</select>
		   </td>
		</tr>
  	</div>

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


if(!empty($_POST['Consultar']) && ($_POST['Consultar']=="Consultar")){

	/**************************************************/
	$where=null;

	$encabezado = "dep=$dep&fecha_ini=$fecha_ini&fecha_fin=$fecha_fin";
	$linkPagina = "$PHP_SELF?$encabezado&orderTipo=$orderTipo&orderNo=";

			
	

	
	/********************************************QUERY REPORTE 1*********************************************************/
	require_once($ruta_raiz."include/myPaginador.inc.php");
	$where=null;
	
 	$order=1;      	
	$titulos=array("FECHA ASIGNACION","RADICADO","TRD","OBSERVACION","TRANSACCION");
	


?>	  
<script src="../actuaciones/reportes/js/tableToExcel.js"></script>
<input type="button" onclick="tableToExcel('testTable', 'Reporte')" value="Exportar a Excel">
<table id="testTable" border=2 align="center" class="table table-bordered table-striped mart-form" width="80%">
        <tr>
                <td class="titulos2" align="center">FECHA ASIGNACION</td>
                <td class="titulos2" align="center">RADICADO</td>
                <td class="titulos2" align="center">TRD</td>
                <td class="titulos2" align="center">OBSERVACION</td>
                 <td class="titulos2" align="center">TRANSACCION</td>
        </tr>
<?php

$i=0;
	
	//$db->conn->debug = true;
	
	if($asignacion==1){
		$isql1 ="select r.radi_nume_radi as RRADICADO, h.hist_fech as FECHA, r.ra_asun as ASUNTO, t.sgd_tpr_descrip as TRD, h.hist_obse as OBSERVACION, tt.sgd_ttr_descrip as TRANSACCION
			from hist_eventos h
			right join radicado r on r.radi_nume_radi = h.radi_nume_radi
			right join sgd_tpr_tpdcumento t on r.tdoc_codi = t.sgd_tpr_codigo
			right join FLDOC.sgd_ttr_transaccion tt on h.sgd_ttr_codigo = tt.sgd_ttr_codigo
			where h.sgd_ttr_codigo in (9,12,16) and h.hist_doc_dest in(".$cedula.")
			AND h.hist_fech BETWEEN (".$db->conn->DBTimeStamp($fecha_ini).") AND (".$db->conn->DBTimeStamp($fecha_fin).")";			
	}
	else{
		$isql1 ="select r.radi_nume_radi as RRADICADO, h.hist_fech as FECHA, r.ra_asun as ASUNTO, t.sgd_tpr_descrip as TRD, h.hist_obse as OBSERVACION, tt.sgd_ttr_descrip as TRANSACCION
			from hist_eventos h
			right join radicado r on r.radi_nume_radi = h.radi_nume_radi
			right join sgd_tpr_tpdcumento t on r.tdoc_codi = t.sgd_tpr_codigo
			right join FLDOC.sgd_ttr_transaccion tt on h.sgd_ttr_codigo = tt.sgd_ttr_codigo
			where h.sgd_ttr_codigo in (9,12,16,13,65) and h.usua_doc in(".$cedula.")
			AND h.hist_fech BETWEEN (".$db->conn->DBTimeStamp($fecha_ini).") AND (".$db->conn->DBTimeStamp($fecha_fin).")";
	}
	
	//echo "<br>".$isql1."<br>";
	
	$rs = $db->query($isql1);
	//if ($rs !== false && !$rs->EOF) {
	//die($isql1); 
	if (!$rs->EOF) {
		//echo "entra";
		$numreginf=0;
		while (!$rs->EOF) {
			$numreginf++;
			$radi_nume_radi     =	$rs->fields['RRADICADO'];
			$hist_fech         	=	$rs->fields['FECHA'];
			$hist_obse      	=	$rs->fields['OBSERVACION'];
			$sgd_tpr_descrip    =	$rs->fields['TRD'];
			$transaccion 		=   $rs->fields['TRANSACCION'];

		?>
	        <tr>
	                <td class="listado2" align="center"><?=$hist_fech?></td>
	                <td class="listado2" align="center"><?=$radi_nume_radi?></td>
	                <td class="listado2" align="center"><?=$sgd_tpr_descrip?></td>
	                <td class="listado2" align="center"><?=$hist_obse?></td>
	                <td class="listado2" align="center"><?=$transaccion?></td>
			</tr>

	   
			<?php
			$rs->MoveNext();
		}
		
	}
	else {
		echo "else";
	    // Manejar el caso cuando no hay resultados
	}



	$ind=round($i/$numreginf*100);
	$ind= "$ind %";

	echo "<p><span class=listado2>Numero de Registros: " . $numreginf."</span>";
		
?>
    </tr>
</table>

<?
}
?>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#dependencia_id").change(function(){
			$.get("get_funcionarios.php","dependencia_id="+$("#dependencia_id").val(), function(data){
				$("#funcionario_id").html(data);
			});
		});

	});
</script>
</body>
</html>