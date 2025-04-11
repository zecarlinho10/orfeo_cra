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
/*  Carlos Ricaurte        cricaurte@cra.gov.co  mayo 2022 unificación reporte 1 y 37*/
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

<form action="ind_gest_new2.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="4" class='titulos2'>SEGUIMIENTO RADICADOS DE ENTRADA
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
	$titulos=array("RADICADO","REMITENTE O DESTINO","UBICACION","FECHA RADICACION","TIPO DOC","DIAS TERMINO","FECHA VTO","RADICADO SALIDA","FECHA RADICADO SALIDA","ASUNTO","CARPETA ACTUAL","DEPENDENCIA","FUNCIONARIO","FECHA DE VENCIMIENTO","DIAS RESTANTES","ASOCIADO","LEY DPDD","TOTAL MOVIMIENTOS","NOMBRE","DIRECCION","TELEFONO","E-MAIL","DEPARTAMENTO","MUNICIPIO","TIPO PQR","TIPO PERSONA","DIAS TRAMITADO");
	

	$cadena=$_POST['txt_radicados'];
	$str = str_replace("\n", ",", $cadena);
	$str = trim($str," ");
	$str = trim($str,",");
	$array = explode(",", $str);
	//$fecha_inicio = new DateTime('2024-01-01');
	//$fecha_fin = new DateTime('2024-01-31');
	//if(!$fecha_fin) $fecha_fin = $fecha_busq;

?>	  
<script src="../actuaciones/reportes/js/tableToExcel.js"></script>
<input type="button" onclick="tableToExcel('testTable', 'Reporte')" value="Exportar a Excel">
<table id="testTable" border=2 align="center" class="table table-bordered table-striped mart-form" width="80%">
        <tr>
                <td class="titulos2" align="center">ENTRADA</td>
                <td class="titulos2" align="center">TIPO DOCUMENTAL</td>
                <td class="titulos2" align="center">FECHA LLEGADA</td>
                <td class="titulos2" align="center">MEDIO RECEPCION</td>
                <td class="titulos2" align="center">FECHA ANEXO/ARCHIVO</td>
                <td class="titulos2"  align="center">NUMERO DE SALIDA</td>
                <td class="titulos2" align="center">ASUNTO</td>
                <td class="titulos2" align="center">MEDIO DE ENVIO</td>
                <td class="titulos2" align="center">TERMINO</td>
                <td class="titulos2" align="center">FECHA VTO</td>
                <td class="titulos2" align="center">FECHA DE ENVIO</td>
				<td class="titulos2" align="center">CUMPLIMIENTO</td>
				<td class="titulos2" align="center">ANEXOS</td>
                <td class="titulos2"  align="center">DEPENDENCIA</td>
                <td class="titulos2"  align="center">NIEGA ACCESO INFO</td>
                <td class="titulos2" align="center">DIAS HABILES EN TRAMITAR</td>
                <td class="titulos2"  align="center">ASOCIADO</td>
                <td class="titulos2" align="center">LEY PDD</td>
                <td class="titulos2" align="center">MOVIMIENTOS</td>
                <td class="titulos2" align="center">NOMBRE</td>
                <td class="titulos2" align="center">DIRECCION</td>
                <td class="titulos2" align="center">TELEFONO</td>
                <td class="titulos2" align="center">E-MAIL</td>
                <td class="titulos2" align="center">DEPARTAMENTO</td>
                <td class="titulos2" align="center">MUNICIPIO</td>
                <td class="titulos2" align="center">TIPO PQR</td>
                <td class="titulos2" align="center">TIPO PERSONA</td>
                <td class="titulos2" align="center">DIAS TRAMITADO</td>
        </tr>
<?php

$i=0;
	
	//$db->conn->debug = true;
	
	/********************************************QUERY REPORTE 2*********************************************************/
/*
	$isql1="SELECT R.RADI_NUME_RADI, SGD_TPR_DESCRIP, R.RADI_FECH_RADI as FECHAE, MEDIO_RECEPCION.MREC_DESC, V1.ANEX_RADI_FECH, V1.RADI_NUME_SALIDA, 
               R.RA_ASUN, V1.SGD_FENV_DESCRIP, SGD_TPR_TPDCUMENTO.SGD_TPR_TERMINO, V1.SGD_RENV_FECH, V1.DEPE_NOMB, FECH_VCMTO, 
               R.RADI_DEPE_ACTU, SENIEGA, R.RADI_NUME_DERI, R.LDPDD
		   FROM RADICADO R
		   INNER JOIN MEDIO_RECEPCION ON MEDIO_RECEPCION.MREC_CODI = R.MREC_CODI 
		     INNER JOIN SGD_TPR_TPDCUMENTO ON R.TDOC_CODI = SGD_TPR_TPDCUMENTO.SGD_TPR_CODIGO 
		     INNER JOIN DEPENDENCIA D ON D.DEPE_CODI=R.RADI_DEPE_RADI 
		     LEFT JOIN ( SELECT RADICADO1.RA_ASUN, SGD_FENV_FRMENVIO.SGD_FENV_DESCRIP, SGD_RENV_REGENVIO.SGD_RENV_FECH, 
		                        SGD_RENV_REGENVIO.SGD_RENV_CODIGO, ANEXOS.ANEX_RADI_NUME, ANEXOS.ANEX_RADI_FECH, 
		                        ANEXOS.RADI_NUME_SALIDA, RADICADO1.RADI_NUME_DERI, D1.DEPE_NOMB 
		                 FROM ANEXOS 
		                      INNER JOIN RADICADO RADICADO1 ON ANEXOS.RADI_NUME_SALIDA = RADICADO1.RADI_NUME_RADI 
		                      INNER JOIN DEPENDENCIA D1 ON RADICADO1.RADI_DEPE_RADI = D1.DEPE_CODI 
		                      INNER JOIN SGD_RENV_REGENVIO ON ANEXOS.RADI_NUME_SALIDA = SGD_RENV_REGENVIO.RADI_NUME_SAL 
		                      INNER JOIN SGD_FENV_FRMENVIO ON SGD_RENV_REGENVIO.SGD_FENV_CODIGO = SGD_FENV_FRMENVIO.SGD_FENV_CODIGO 
		                 WHERE SGD_RENV_REGENVIO.SGD_RENV_CODIGO IN ( 
		                        SELECT MIN(SQ.SGD_RENV_CODIGO) FROM SGD_RENV_REGENVIO SQ GROUP BY SQ.RADI_NUME_SAL ) 
		                ) V1 ON R.RADI_NUME_RADI = V1.ANEX_RADI_NUME 
		     WHERE R.RADI_FECH_RADI BETWEEN (".$db->conn->DBTimeStamp($fecha_ini).") AND (".$db->conn->DBTimeStamp($fecha_fin).") 
		     	   AND R.RADI_NUME_RADI LIKE '%321%2'";
	*/
	$isql1 ="SELECT
			    r.radi_nume_radi,
			    sgd_tpr_descrip,
			    r.radi_fech_radi    AS fechae,
			    medio_recepcion.mrec_desc,
			    v1.anex_radi_fech,
			    v1.radi_nume_salida,
			    r.ra_asun,
			    v1.sgd_fenv_descrip,
			    sgd_tpr_tpdcumento.sgd_tpr_termino,
			    v1.sgd_renv_fech,
			    CASE
			        WHEN v1.depe_nomb IS NULL THEN
			            (
			                SELECT
			                    dd.depe_nomb
			                FROM
			                         radicado rr
			                    INNER JOIN dependencia dd ON dd.depe_codi = rr.radi_depe_actu
			                                                 AND rr.radi_nume_radi = r.radi_nume_radi
			            )
			        ELSE
			            v1.depe_nomb
			    END                 AS depe_nomb_1, 
			    fech_vcmto AS VENCIMIENTO,
			    r.radi_depe_actu,
			    seniega,
			    r.radi_nume_deri,
			    r.ldpdd,
			    v1.sgd_dir_nomremdes,
			    v1.sgd_dir_direccion,
			    v1.sgd_dir_telefono,
			    v1.sgd_dir_mail,
			    v1.dpto_nomb,
			    v1.muni_nomb,
			    TIPO_PQR,
			    v1.SGD_RENV_DIR,
			    case 
		                when SGD_DOC_FUN IS NOT NULL 
		                    then 'FUNCIONARIO' 
		                    else 
		                        case 
		                        when SGD_OEM_CODIGO IS NOT NULL 
		                            then 'EMPRESA'
		                            else 
		                                case 
		                                when SGD_CIU_CODIGO IS NOT NULL 
		                                    then 'CIUDADANO'
		                                    else 'OTRO'
		                                end
		                        end
		            end as TIPO_PERSONA
			FROM
			         radicado r
			    INNER JOIN medio_recepcion ON medio_recepcion.mrec_codi = r.mrec_codi
			    INNER JOIN sgd_tpr_tpdcumento ON r.tdoc_codi = sgd_tpr_tpdcumento.sgd_tpr_codigo
			    INNER JOIN dependencia  d ON d.depe_codi = r.radi_depe_radi
			    INNER JOIN sgd_dir_drecciones d1 ON r.radi_nume_radi = d1.radi_nume_radi
			    LEFT JOIN (
			        SELECT
			            radicado1.ra_asun,
			            sgd_fenv_frmenvio.sgd_fenv_descrip,
			            sgd_renv_regenvio.sgd_renv_fech,
			            sgd_renv_regenvio.sgd_renv_codigo,
			            anexos.anex_radi_nume,
			            anexos.anex_radi_fech,
			            anexos.radi_nume_salida,
			            radicado1.radi_nume_deri,
			            d1.depe_nomb,
			            dir.sgd_dir_nomremdes,
			            dir.sgd_dir_direccion,
			            dir.sgd_dir_telefono,
			            dir.sgd_dir_mail,
			            dpto.dpto_nomb,
			            mun.muni_nomb,
			            SGD_RENV_DIR
			        FROM
			            anexos
			            INNER JOIN radicado     radicado1 ON anexos.radi_nume_salida = radicado1.radi_nume_radi
			            INNER JOIN dependencia         d1 ON radicado1.radi_depe_radi = d1.depe_codi
			            INNER JOIN sgd_renv_regenvio 	  ON anexos.radi_nume_salida = sgd_renv_regenvio.radi_nume_sal
			            INNER JOIN sgd_fenv_frmenvio 	  ON sgd_renv_regenvio.sgd_fenv_codigo = sgd_fenv_frmenvio.sgd_fenv_codigo
			            RIGHT JOIN sgd_dir_drecciones dir ON anexos.radi_nume_salida = dir.radi_nume_radi
			            INNER JOIN departamento      dpto ON dpto.dpto_codi = dir.dpto_codi
			            INNER JOIN municipio          mun ON mun.dpto_codi = dir.dpto_codi
			            AND mun.muni_codi = dir.muni_codi
			        WHERE
			            sgd_renv_regenvio.sgd_renv_codigo IN (
			                SELECT
			                    MIN(sq.sgd_renv_codigo)
			                FROM
			                    sgd_renv_regenvio sq
			                GROUP BY
			                    sq.radi_nume_sal
			            )
			    )            v1 ON r.radi_nume_radi = v1.anex_radi_nume
			WHERE R.RADI_FECH_RADI BETWEEN (".$db->conn->DBTimeStamp($fecha_ini).") AND (".$db->conn->DBTimeStamp($fecha_fin).") 
		     	   AND R.RADI_NUME_RADI LIKE '%321%2'";
	//echo "<br>".$isql1."<br>";
	//die($isql1);
	$rs = $db->query($isql1);
	//if ($rs !== false && !$rs->EOF) {
	//die($isql1); 
	if (!$rs->EOF) {
		$numreginf=0;
		while (!$rs->EOF) {
			$numreginf++;

								 
		  	$Dha = new FechaHabil($db);
		  	$calendario = new Calendar($db);

			$fechaenv=null;

			$radicadoin         =	$rs->fields['RADI_NUME_RADI'];
			$trd         =	$rs->fields['SGD_TPR_DESCRIP'];
			$fecharo      	    =	$rs->fields['FECHAE'];
			$mediorecepcion     =	$rs->fields['MREC_DESC'];
			$fechaAnexado	    =	$rs->fields['ANEX_RADI_FECH'];
			$radicadosal        =	$rs->fields['RADI_NUME_SALIDA'];
			$asunto             =	$rs->fields['RA_ASUN'];
			$medioEnvio         =	$rs->fields['SGD_FENV_DESCRIP'];
			$termino            =	$rs->fields['SGD_TPR_TERMINO'];
			$fechaenv           =	$rs->fields['SGD_RENV_FECH'];
			$depen1		    =	$rs->fields['DEPE_NOMB_1'];
			$fvcmto             =	$rs->fields['VENCIMIENTO'];
			$seniega            = 	$rs->fields['SENIEGA'];
			$depactual          = 	$rs->fields['RADI_DEPE_ACTU'];
			$derivado            = 	$rs->fields['RADI_NUME_DERI'];
			$ldpdd          = 	$rs->fields['LDPDD'];
			
			$dias=$calendario->restaFechasHabiles($fecharo,$fechaenv);
			$fechaenv1=$fechaenv;
			
			$depsalida="";

			if($seniega==null){
				$seniega="No";
			}
			else{
				$seniega="Si";	
			}

			$sqlMov = "SELECT COUNT(1) AS TOTAL_MOVIMIENTOS
						FROM HIST_EVENTOS H
						WHERE H.SGD_TTR_CODIGO IN (9,12,16) AND 
						      H.RADI_NUME_RADI= '" . $radicadoin . "'";

				$rsMov = $db->query($sqlMov);
				$total_mov=0;
				while(!$rsMov->EOF){
					$total_mov =  $rsMov->fields['TOTAL_MOVIMIENTOS'];
					$rsMov->MoveNext();
				}

			$fechaHoy = date("Y-m-d");

			if($fechaenv == null){
				$sqlArch = "SELECT H.HIST_FECH, D.DEPE_NOMB, SGD_TTR_CODIGO
					    FROM DEPENDENCIA D, HIST_EVENTOS H
					    WHERE H.DEPE_CODI = D.DEPE_CODI AND RADI_NUME_RADI = '" . $radicadoin . "' AND 
						  SGD_TTR_CODIGO IN (65,13)";

				$rsArch = $db->query($sqlArch);
				$van=0;
				$dias=null;
				while(!$rsArch->EOF){
					$van=1;
					$fechaArchivado = $rsArch->fields['HIST_FECH'];
					$depen1 = $rsArch->fields['DEPE_NOMB'];
					$ttrcodigo = $rsArch->fields['SGD_TTR_CODIGO'];
					$dias=$calendario->restaFechasHabiles($fecharo,$fechaArchivado);
					$fechaAnexado=$fechaArchivado;
					//65 NRR - 13 ARCHIVADO
					
					//if($ttrcodigo==65){
					//	if ($dias<=$termino){
					//		$ctr="CUMPLE";
					//	} 
					//	else $ctr="NO CUMPLE";
					//}
					//else $ctr = "Archivado sin rta";
					
					//VER SI TIENE ANEXOS

					$sql3="SELECT * FROM ANEXOS WHERE ANEX_DEPE_CREADOR <> 321 AND ANEX_RADI_NUME = " . $radicadoin;
					//echo "sql3:" . $sql3;
					$rs3 = $db->query($sql3);
					$van3=false;
					while(!$rs3->EOF){
					  $van3=true;
					  break;
					}
					if ($dias<=$termino) $ctr="CUMPLE";
					else $ctr="NO CUMPLE";
					if($van3==true){
						$anexo="Tiene anexo";
					}
					else {
						if($ttrcodigo==65){
							$anexo="No tiene anexo, NRR";
						}
						else {
							$anexo="No tiene anexo, Archivado";
						}
					}
					
					$rsArch->MoveNext();
				}
				$fechaAnexado="Sin respuesta";
				if($van==0){
					$depsalida=$depen1;
					$diasHoy=$calendario->restaFechasHabiles($fecharo,$fechaHoy);
					if ($diasHoy<=$termino){
						$ctr="A TIEMPO";
					}
					else{
						$ctr="VENCIDO";	
					}
					
					$dias="No enviado";
					$anexo="No enviado-activo";
				}
				
				$radicadosal="Sin respuesta";
				$medioEnvio="Sin respuesta";
				$fechaenv="Sin respuesta";
			}
			else{
				$dsql="SELECT D.DEPE_NOMB
						FROM ANEXOS A
						RIGHT JOIN SGD_RENV_REGENVIO E ON A.RADI_NUME_SALIDA = E.RADI_NUME_SAL
						INNER JOIN DEPENDENCIA D ON A.ANEX_DEPE_CREADOR = D.DEPE_CODI
						where a.anex_salida = 1 and a.ANEX_RADI_NUME = '$radicadosal'";
					$drs = $db->query($dsql);
					while(!$drs->EOF){
					  $depsalida=$drs->fields['DEPE_NOMB'];
					  $drs->MoveNext();
					}
				$anexo="Enviado";
				if ($dias<=$termino){
					$ctr="CUMPLE";
				} 
				else $ctr="NO CUMPLE";
			}
		
			//if ($depen1=="") $depen1=getDependencia($depactual, $db);
			//else if($depen1=="ASEO" || $depen1=="ACUEDUCTO") $depen1 = "Subdirección de Regulación";
			//else if($depen1=="OFICINA ASESORA JURIDICA SECRETARIA") $depen1 = "Oficina Asesora Jurídica";
			

		?>
	        <tr>
	                <td class="listado2" align="center"><?=$radicadoin?></td>
	                <td class="listado2" align="center"><?=$trd?></td>
	                <td class="listado2" align="center"><?=$fecharo?></td>
	                <td class="listado2" align="center"><?=$mediorecepcion?></td>
	                <td class="listado2" align="center"><?=$fechaAnexado?></td>
	                <td class="listado2" align="center"><?=$radicadosal?></td>
	                <td class="listado2" align="center"><?=$asunto?></td>
	                <td class="listado2" align="center"><?=$medioEnvio?></td>
	                <td class="listado2" align="center"><?=$termino?></td>
	                <td class="listado2" align="center"><?=$fvcmto?></td>
	                <td class="listado2" align="center"><?=$fechaenv?></td>
	                <td class="listado2" align="center"><?=$ctr?></td>
	                <td class="listado2" align="center"><?=$anexo?></td>
	                <td class="listado2" align="center"><?=$depen1?></td>
			<td class="listado2" align="center"><?=$seniega?></td>
			<td class="listado2" align="center"><?=$dias?></td>
			<td class="listado2" align="center"><?=$derivado?></td>
			<td class="listado2" align="center"><?=$ldpdd?></td>
			<td class="listado2" align="center"><?=$total_mov?></td>
			<td class="listado2" align="center"><?=$rs->fields['SGD_DIR_NOMREMDES'];?></td>
			<td class="listado2" align="center"><?=$rs->fields['SGD_DIR_DIRECCION'];?></td>
			<td class="listado2" align="center"><?=$rs->fields['SGD_DIR_TELEFONO'];?></td>
			<td class="listado2" align="center"><?=$rs->fields['SGD_RENV_DIR'];?></td>
			<td class="listado2" align="center"><?=$rs->fields['DPTO_NOMB'];?></td>
			<td class="listado2" align="center"><?=$rs->fields['MUNI_NOMB'];?></td>
			<td class="listado2" align="center"><?=$rs->fields['TIPO_PQR'];?></td>
			<td class="listado2" align="center"><?=$rs->fields['TIPO_PERSONA'];?></td>
			<td class="listado2" align="center"><?=$dias?></td>
			
			</tr>	

	   
			<?php
			$numreginf ++;
			$rs->MoveNext();
		}
		
	}
	else {
		echo "NO HAY REGISTROS";
	    // Manejar el caso cuando no hay resultados
	}



	$ind=round($i/$numreginf*100);
	$ind= "$ind %";

	echo "<p><span class=listado2>Número de Registros: " . $numreginf."</span>";
		
?>
    </tr>
</table>

<?
}
?>
</div></body>
</html>