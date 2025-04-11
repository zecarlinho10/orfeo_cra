<?php
/********************************************************************************/
/*DESCRIPCION: Reporte que muestra los radicados archivados                     */
/*FECHA: 20 Abril de 2018*/
/*AUTOR: Carlos Ricaurte*/
/*********************************************************************************/
?>
<?php
$krdOld = $krd;
$per=1;
session_start();

if(!$krd) $krd = $krdOld;
if (!$ruta_raiz) $ruta_raiz = "..";
include "$ruta_raiz/rec_session.php";
include_once("$ruta_raiz/include/db/ConnectionHandler.php");
include_once "$ruta_raiz/htmlheader.inc.php";
$db = new ConnectionHandler("$ruta_raiz");

?>
<html>
<head>
<title>REPORTE DE RADICADOS ARCHIVADOS</title>
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="jquery.min.js"></script>
<CENTER>
<body bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
 <link rel="stylesheet" type="text/css" href="../js/spiffyCal/spiffyCal_v2_1.css">
 <script language="JavaScript" src="../js/spiffyCal/spiffyCal_v2_1.js">
 </script><style type="text/css">
<!--
.style1 {font-size: 14px}
-->
</style>

<form name="reporte_archivo" action='reporte_archivo.php' method="post">
<table border="0" width="90%" cellpadding="0"  class="borde_tab">
<??>
<tr>
  <TD height="35" colspan="2" class="titulos2"><center>
    REPORTE DE RADICADOS ARCHIVADOS
      </div></td>
  </tr>
<tr>
  <TD height="30" class="titulos5">
  <?
        if(!$dep_sel) $dep_sel = 0;
        $fechah=date("dmy") . " ". date("h_m_s");
	$fecha_hoy = Date("Y-m-d");
	$sqlFechaHoy=$db->conn->DBDate($fecha_hoy);
	$check=1;
	$fechaf=date("dmy") . "_" . date("hms");
        $numeroa=0;$numero=0;$numeros=0;$numerot=0;$numerop=0;$numeroh=0;
        
        include("$ruta_raiz/include/query/expediente/queryReporte.php");

	$queryUs = "select depe_codi from usuario where USUA_LOGIN='$krd' AND USUA_ESTA=1";
	//$db->debug=true;
	$rsUs = $db->query($queryUs);
	$depe = $rsUs->fields["DEPE_CODI"];
	if ($dependencia_busq != 99999)  {
			$whereDependencia = " AND DEPE_CODI=$depe";
		}
?>
  <div align="right"><b>Dependencia que Archiva </b></div></td>
  <TD class="titulos5">
  <?
  error_reporting(0);
  $query2="SELECT D.DEPE_NOMB, D.DEPE_CODI FROM DEPENDENCIA D ORDER BY D.DEPE_NOMB";
  $rs1=$db->conn->query($query2);
  print $rs1->GetMenu2('dep_sel',$_POST['dep_sel'],"0:--- TODAS---",false,"","  class=select onChange='submit();'");
  ?>
 </td>
<tr>
  <TD height="23" class="titulos5"><div align="right">Tipo de Radicado</div></td>
  <TD class="titulos5">
  <?
	$sql="select sgd_trad_descr,sgd_trad_codigo from sgd_trad_tiporad order by sgd_trad_codigo";
	$rs=$db->query($sql);
	print $rs->GetMenu2("trad", $_POST['trad'], "0:-- Seleccione --", false,"","class='select'" );
  ?>
  </td>
<tr>
  <TD height="26" class="titulos5"><div align="right">Usuario que Archiva </div></td>
  <TD class="titulos5"><select name="codigoUsuario" class="select" onChange="formulario.submit();">
     <option value=0> -- AGRUPAR POR TODOS LOS USUARIOS --</option>
    <?php
		$whereUsSelect = "";
		if(!$usActivos) {
            $whereUsua = " u.USUA_ESTA = 1 ";
		  	$whereUsSelect = " AND u.USUA_ESTA = 1 ";
		  	$whereActivos = " AND b.USUA_ESTA=1";

		}	
 	    if ($_POST['dep_sel'] != ""){
			$isqlus = "SELECT u.USUA_NOMB,u.USUA_CODI FROM USUARIO u, DEPENDENCIA D WHERE u.USUA_ESTA = 1 AND D.DEPE_CODI=U.DEPE_CODI AND U.DEPE_CODI='".$_POST['dep_sel']."' ORDER BY u.USUA_NOMB";
			$rs1=$db->query($isqlus);
			while(!$rs1->EOF){
                if( $_POST['codigoUsuario'] == $rs1->fields["USUA_CODI"] ){
                    $selected = "selected";
                }
                else{
                    $selected = "";
                }
                
				$codigoUsuario = $rs1->fields["USUA_CODI"];
				$vecDeps[]=$codigo;
				$usNombre = $rs1->fields["USUA_NOMB"];
				print "<option value='".$codigoUsuario."' ".$selected.">$usNombre</option>";
				$rs1->MoveNext();
			}
		}
		?>
  </select></td>
<?php
/* Modificado Supersolidaria 05-Dic-2006
 * El rango inicial de fechas se estableci� en 1 mes.
 */
// Fecha inicial
if( $_GET['fechaIniSel'] == "" && $_POST['fechaIni'] == "" )
{
    $fechaIni = date( 'Y-m-d', strtotime( "-1 month" ) );
}
else if( $_POST['fechaIni'] != "" )
{
    $fechaIni = $_POST['fechaIni'];
}
else if(  $_GET['fechaIniSel'] != "" )
{
    $fechaIni = $_GET['fechaIniSel'];
}
// Fecha final
if( $_GET['fechaInifSel'] == "" && $_POST['fechaInif'] == "" )
{
    $fechaInif = date( 'Y-m-d' );
}
else if( $_POST['fechaInif'] != "" )
{
    $fechaInif = $_POST['fechaInif'];
}
else if( $_GET['fechaInifSel'] != "" )
{
    $fechaInif = $_GET['fechaInifSel'];
}
/*	19/04/2018 CARLOS RICAURTE
	NUEVAS PARAMETRO PARA FILTRAR POR FECHA DE RADICACION*/

if( $_GET['fechaIniSelR'] == "" && $_POST['fechaIniR'] == "" )
{
    $fechaIniR = date( 'Y-m-d', strtotime( "-1 month" ) );
}
else if( $_POST['fechaIniR'] != "" )
{
    $fechaIniR = $_POST['fechaIniR'];
}
else if(  $_GET['fechaIniSelR'] != "" )
{
    $fechaIniR = $_GET['fechaIniSelR'];
}
// Fecha final
if( $_GET['fechaInifSelR'] == "" && $_POST['fechaInifR'] == "" )
{
    $fechaInifR = date( 'Y-m-d' );
}
else if( $_POST['fechaInifR'] != "" )
{
    $fechaInifR = $_POST['fechaInifR'];
}
else if( $_GET['fechaInifSelR'] != "" )
{
    $fechaInifR = $_GET['fechaInifSelR'];
}

?>
<tr>
  <TD height="23" class="titulos5"><div align="right">
    <div align="right">Fecha Radicados Creados Desde</div></td>
  <TD class="titulos5">
	<script language="javascript">
   	var dateAvailable01 = new ctlSpiffyCalendarBox("dateAvailable01", "reporte_archivo", "fechaIniR","btnDate1","<?=$fechaIniR?>",scBTNMODE_CUSTOMBLUE);
  				dateAvailable01.date = "<?=date('Y-m-d');?>";
				dateAvailable01.writeControl();
				dateAvailable01.dateFormat="yyyy-MM-dd";
	</script>

  </td>
<tr>
  <TD width="202" height="24" class="titulos5">&nbsp;
    <div align="right">Fecha Radicados Creados Hasta</div></td>
  <TD width="323" class="titulos5">
  	<script language="javascript">
	var dateAvailable02 = new ctlSpiffyCalendarBox("dateAvailable02", "reporte_archivo", "fechaInifR","btnDate1","<?=$fechaInifR?>",scBTNMODE_CUSTOMBLUE);
  				dateAvailable02.date = "<?=date('Y-m-d');?>";
				dateAvailable02.writeControl();
				dateAvailable02.dateFormat="yyyy-MM-dd";
	</script>
</td>
<tr>
  <TD height="23" class="titulos5"><div align="right">
    <div align="right">Fecha Archivados Desde
    </div></td>
  <TD class="titulos5">
	<script language="javascript">
   	var dateAvailable1 = new ctlSpiffyCalendarBox("dateAvailable1", "reporte_archivo", "fechaIni","btnDate1","<?=$fechaIni?>",scBTNMODE_CUSTOMBLUE);
  				dateAvailable1.date = "<?=date('Y-m-d');?>";
				dateAvailable1.writeControl();
				dateAvailable1.dateFormat="yyyy-MM-dd";
	</script>

  </td>
<tr>
  <TD width="202" height="24" class="titulos5">&nbsp;
    <div align="right">Fecha Archivados Hasta</div></td>
  <TD width="323" class="titulos5">
  	<script language="javascript">
	var dateAvailable2 = new ctlSpiffyCalendarBox("dateAvailable2", "reporte_archivo", "fechaInif","btnDate1","<?=$fechaInif?>",scBTNMODE_CUSTOMBLUE);
  				dateAvailable2.date = "<?=date('Y-m-d');?>";
				dateAvailable2.writeControl();
				dateAvailable2.dateFormat="yyyy-MM-dd";
	</script>
</td>
<tr>
	<td align="center" colspan="3" class="titulos5">
		<input type="submit" class="botones" value="Buscar" name="Buscar">
	  	<input type="button" class="botones" value="Cancelar" name="Cancelar" onClick="javascript:history.back()">
	</td>
</tr>
</table>
<p>&nbsp;</p>
<?
$Buscar=$_POST['Buscar'];

if($Buscar)
{
/*	20/04/2018 CARLOS RICAURTE
	NUEVAS PARAMETRO PARA FILTRAR POR FECHA DE RADICACION SE CREA NUEVO SELECT*/

?>
	<div class="form-group">
    	<table class="table table-responsive table-hover">
		    <thead>
		        <tr>
		            <th>Dependencia</th>
		            <th>Usuario</th>
		            <th>Radicado</th>
		            <th>Fecha Radicación</th>
		            <th>Observacion Archivo fisico</th>
		            <th>Fecha Archivo fisico</th>
		        </tr>
		    </thead>
		    <tbody>
		    	<?php
		    		//FORMATO DE FECHAS
		    		$fechaIniR=substr($_POST['fechaIniR'],8,2) . "/" . substr($_POST['fechaIniR'],5,2) . "/" . substr($_POST['fechaIniR'],0,4);
		    		$fechaInifR=substr($_POST['fechaInifR'],8,2) . "/" . substr($_POST['fechaInifR'],5,2) . "/" . substr($_POST['fechaInifR'],0,4);
		    		$fechaIni=substr($_POST['fechaIni'],8,2) . "/" . substr($_POST['fechaIni'],5,2) . "/" . substr($_POST['fechaIni'],0,4);
		    		$fechaInif=substr($_POST['fechaInif'],8,2) . "/" . substr($_POST['fechaInif'],5,2) . "/" . substr($_POST['fechaInif'],0,4);
		    		
		    		//VARIABLES: DEPENDENCIA, TIPO RADICADO, USAURIO QUE ARCHIVA
		    		$idDependencia = $_POST['dep_sel'];
		    		$idTipoDocumento = $_POST['trad'];
		    		$idUsuario = $_POST['codigoUsuario'];

		    		$qDepe = "";
		    		$qTrd = "";
		    		$qUsu = "";

		    		if($idDependencia<>0){
		    			$qDepe = " AND H.DEPE_CODI = $idDependencia ";
		    		}
		    		if($idTipoDocumento<>0){
		    			$qTrd = " AND R.RADI_NUME_RADI LIKE '%" . $idTipoDocumento . "' ";
		    		}
		    		if($idUsuario<>0){
		    			$qUsu = " AND H.USUA_CODI = $idUsuario ";
		    		}

		    		$sqlR="SELECT DEPE_NOMB, USUA_NOMB, R.RADI_NUME_RADI, RADI_FECH_RADI, HIST_OBSE, HIST_FECH
							FROM USUARIO U, HIST_EVENTOS H, RADICADO R, DEPENDENCIA D
							WHERE H.SGD_TTR_CODIGO IN (57) AND R.DEPE_CODI <> 900 AND R.DEPE_CODI <> 905 AND R.DEPE_CODI <> 910 AND 
							      U.USUA_CODI=H.USUA_CODI AND U.DEPE_CODI=H.DEPE_CODI AND R.RADI_NUME_RADI = H.RADI_NUME_RADI AND 
							      D.DEPE_CODI=H.DEPE_CODI AND R.RADI_FECH_RADI >= TO_DATE('$fechaIniR', 'DD/MM/YYYY') AND 
							      R.RADI_FECH_RADI <=  TO_DATE('$fechaInifR', 'DD/MM/YYYY') AND 
							      HIST_FECH >= TO_DATE('$fechaIni', 'DD/MM/YYYY') AND HIST_FECH <=  TO_DATE('$fechaInif', 'DD/MM/YYYY') " . 
							      $qDepe . $qTrd . $qUsu . " ORDER BY HIST_FECH";

					$queryR=$db->query($sqlR);
					
					$i=0;
					while(!$queryR->EOF){ 
						
	 				 	echo "
								<tr>
							        	<td>" . $queryR->fields["DEPE_NOMB"] . "</td>
							            <td>" . $queryR->fields["USUA_NOMB"] . "</td>
							            <td>" . $queryR->fields["RADI_NUME_RADI"] . "</td>
							            <td>" . $queryR->fields["RADI_FECH_RADI"] . "</td>
							            <td>" . $queryR->fields["HIST_OBSE"] . "</td>
							            <td>" . $queryR->fields["HIST_FECH"] . "</td>
							    </tr>
					      	";
						$i++;
						$queryR->MoveNext ();
					}

					//
					if($i==0){
						print "El usuario no tiene radicados para mostrar";
					}
					else{
						print "Total registros:" . $i;
					}

				?>
		    </tbody>
		</table>
	</div>
<?php
}
//$db->conn->Close();
?>
<p>&nbsp;</p>
</form>
</CENTER>
</CENTER>
</script>
</BODY>
</HEAD>
</html>
