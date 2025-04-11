<?
/**
 * Programa que despliega el formulario de consulta de ESP
 * @author  YULLIE QUICANO
 * @mail    yquicano@cra.gov.co
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

?>
<html>
<head>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>ALERTAS DE VENCIDOS</title>


<?include ($ruta_raiz."/htmlheader.inc.php")?>

</head>

 <body><div class="table-responsive">


<?
$params = session_name()."=".session_id()."&krd=$krd";

?>

<form action="alertas.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >

</form>
<table width="100%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="20" class='titulos4'>
			RADICADOS VENCIDOS A LA FECHA PENDIENTE DE FINALIZAR TRAMITE
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>	
<?php 

function pintarResultados($fila,$i,$n){
		global $ruta_raiz;
		switch($n){
				case 0:
				if($fila['PATH']!=NULL)
					$salida="<a href=\"{$ruta_raiz}bodega".$fila['PATH']."\">".$fila['ENTRADA']."</a>";
					
				else
					$salida=$fila['ENTRADA'];
					
 				break;
			case 1:
				$sessionid=session_id();
				$krd2=substr($sessionid,14);
				$linkInfGeneral = "<a class='vinculos' href='../verradicado.php?verrad=".$salida=$fila['ENTRADA']."&".session_name()."=".session_id()."&krd=$krd2'&carpeta=8&nomcarpeta=Busquedas&tipo_carp=0'>";	
				$salida=$linkInfGeneral.$fila['FECHAE'];	
							
				break;
			case 2:
				$salida=$fila['ASUNTO'];
				break;
			case 3:
				$salida=$fila['CARPETA'];
				break;
			case 4:
				$salida=$fila['FVCMTO'];
				break;
			case 5:
				$salida=$fila['DIAS'];
				break;	
					
			Default:$salida="ERROr";
		}
		return $salida;	
	}

   	
$sqldep="SELECT USUA_CODI, DEPE_CODI FROM USUARIO WHERE USUA_LOGIN ='$krd'";
			//$db->conn->debug = true;
$rsdep = $db->query($sqldep);
$dep            = $rsdep->fields['DEPE_CODI'];
$usua            = $rsdep->fields['USUA_CODI'];

	
require_once($ruta_raiz."include/myPaginador.inc.php");			
$order=1; 					 
$titulos=array("1#RADICADO","2#FECHA RADICADO", "3#ASUNTO","4#CARPETA ACTUAL","5#FECHA DE VENCIMIENTO","6#No.DIAS CAL.");
			
$isql = "SELECT distinct R.RADI_NUME_RADI  AS ENTRADA, r.radi_fech_radi as FECHAE, r.radi_path AS PATH,
td.sgd_tpr_termino AS TERMINO, c.carp_desc AS CARPETA, c.carp_codi,td.sgd_tpr_descrip AS TIPO, r.ra_asun AS ASUNTO, d.depe_nomb AS DEPE, u.usua_nomb AS FUNCIO, r.radi_depe_radi, 
sgd_dir_nomremdes REMITENTE, (M.MUNI_NOMB||' - '||DP.DPTO_NOMB)as UBICACION,
to_date((r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) + (select count(*) from SGD_NOH_NOHABILES S where S.NOH_FECHA BETWEEN r.radi_fech_radi AND r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) AND DESCRIP='F')) + NVL((SELECT SUMDIAS FROM SGD_NOH_NOHABILES WHERE NOH_FECHA= to_date(r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) + (select count(*) from SGD_NOH_NOHABILES S where S.NOH_FECHA BETWEEN r.radi_fech_radi AND r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) AND DESCRIP='F'))),0) ) as FVCMTO
,trunc(1 + to_date((r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) + (select count(*) from SGD_NOH_NOHABILES S where S.NOH_FECHA BETWEEN r.radi_fech_radi AND r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) AND DESCRIP='F')) + NVL((SELECT SUMDIAS FROM SGD_NOH_NOHABILES WHERE NOH_FECHA= to_date(r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) + (select count(*) from SGD_NOH_NOHABILES S where S.NOH_FECHA BETWEEN r.radi_fech_radi AND r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) AND DESCRIP='F'))),0))- sysdate) as DIAS
FROM radicado r INNER JOIN carpeta c ON c.carp_codi=r.carp_codi LEFT JOIN sgd_tpr_tpdcumento td ON r.tdoc_codi=td.sgd_tpr_codigo 
INNER JOIN usuario u ON r.radi_usua_actu=u.usua_codi AND u.depe_codi=r.radi_depe_actu INNER JOIN dependencia d ON d.depe_codi=r.radi_depe_actu 
INNER JOIN SGD_DIR_DRECCIONES DR ON DR.RADI_NUME_RADI = R.RADI_NUME_RADI, 
MUNICIPIO M, DEPARTAMENTO DP 
WHERE DR.MUNI_CODI=M.MUNI_CODI AND DR.DPTO_CODI=M.DPTO_CODI AND DR.DPTO_CODI=DP.DPTO_CODI
AND  (r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) + (select count(*) from SGD_NOH_NOHABILES S where S.NOH_FECHA BETWEEN r.radi_fech_radi AND r.radi_fech_radi + (select T.DTOTAL from SGD_DT_TOTALES T WHERE td.sgd_tpr_termino=T.DTERMINO AND T.DSEMANA=to_char(r.radi_fech_radi, 'D')) AND DESCRIP='F'))<= SYSDATE
AND r.radi_depe_actu = '$dep'
AND r.radi_usua_actu = '$usua'

order by ENTRADA";
			
		//	$db->conn->debug = true;

	
	

		
		
			$paginador= new myPaginador($db,$isql,1);
			$paginador->modoPintado(true);
			$paginador->setImagenASC($ruta_raiz."iconos/flechaasc.gif");
			$paginador->setImagenDESC($ruta_raiz."iconos/flechadesc.gif");
			$paginador->setFuncionFilas("pintarResultados");
			$paginador->generarPagina($titulos);

?>
</div></body>
</html>