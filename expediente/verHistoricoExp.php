<?
session_start();
/** Modulo de Expedientes o Carpetas Virtuales
  * Modificacion Variables
  *@autor Jairo Losada 2009/06
  *Licencia GNU/GPL 
  */
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];
$tip3Nombre=$_SESSION["tip3Nombre"];
$tip3desc = $_SESSION["tip3desc"];
$tip3img =$_SESSION["tip3img"];
$tpNumRad = $_SESSION["tpNumRad"];
$tpPerRad = $_SESSION["tpPerRad"];
$tpDescRad = $_SESSION["tpDescRad"];
$tip3Nombre = $_SESSION["tip3Nombre"];
$tpDepeRad = $_SESSION["tpDepeRad"];
$usuaPermExpediente = $_SESSION["usuaPermExpediente"];

$ruta_raiz = ".."; 	include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler($ruta_raiz);
/** PAGINA QUE DESPLIEGA EL HISTORICO DE DE UN EXPEDIENTE
	* Esta pagina necesita que llegue el Numero de expediente en la variable $numeroExpediente
	* @version ORFEO 3.5
	* @autor JAIRO LOSADA - SUPERSERVICIOS
	* @fecha Marzo de 2006
	* @licencia GPL. Software Libre.
	* @param $numeroExpediente Integer Numero de Expediente
	*
	**/

/** TRAER DTOS DE EXPEDIENTE
	* @param $trdExp objecto Objeto que trae funciones del expediente
	* @param $tSub  int Almacena Codigo de la Subserie.
  * @param $codSerie int Almacena Codigo de la Serie Documental.
	* @param $descFldExp String Guarda Descripcion del estado del Flujo del Expediente Actual.
	* @param $expTerminos Int   Guarda los terminos o Dias Habiles del proceso Actual.
	* @param $expFechaCreacion date Fecha de Creacion del expediete.
	* 
	**/
	include_once ("$ruta_raiz/include/tx/Expediente.php");
	error_reporting(0);
	$trdExp = new Expediente($db);
	$mrdCodigo = $trdExp->consultaTipoExpediente($numeroExpediente);
	$trdExpediente= $trdExp->descSerie." / ".$trdExp->descSubSerie;
	$descPExpediente = $trdExp->descTipoExp;
	$codSerie = $trdExp->codigoSerie;
	$cosSub = $trdExp->codigoSubSerie;
	$tdoc = $trdExp->codigoTipoDoc;
	$codigoTipoExp = $trdExp->codigoTipoExp;
	$descFldExp = $trdExp->descFldExp;
	$codigoFldExp = $trdExp->codigoFldExp;
  $expTerminos = $trdExp->expTerminos;
	$expFechaCrea = $trdExp->expFechaCrea;
	$expTerminosP = $trdExp->expTerminosP;
	$descTipoExp = $trdExp->descTipoExp;
	$no_tipo = false;
	
	include_once "$ruta_raiz/tx/diasHabiles.php";
	$a = new FechaHabil($db);
	$tReal = $a->diasHabiles($expFechaCrea,date("Y-m-d"));
?>
<html>
<head>

<title>HISTORICO EXPEDIENTE <?=$numeroExpediente?></title>
<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
</head>
<body >
<div class="well well-sm well-light" style="opacity: 1; width=100%; overflow-y: scroll;">
<div class="widget-body" style="opacity: 1; width=90%;">
<div id="wid-id-0" class="jarviswidget jarviswidget-color-orange jarviswidget-sortable" style="opacity: 2; width=80%;" role="widget" data-widget-editbutton="false">
<header style="opacity: 1; width=90%;" role="heading">
<span class="widget-icon">
<h2>Historico del Expediente <?=$numeroExpediente?> <?=$descTipoExp?> </h2>
<span class="jarviswidget-loader"> </span>
</span>
</header>
<?php
	require_once("$ruta_raiz/class_control/Transaccion.php");
	require_once("$ruta_raiz/class_control/Dependencia.php");
	require_once("$ruta_raiz/class_control/usuario.php");
	error_reporting(0);
	$trans = new Transaccion($db);
	$objDep = new Dependencia($db);
	$objUs = new Usuario($db);
	$isql = "select USUA_NOMB from usuario where depe_codi=$dependencia and usua_codi=$codusuario";
	$rs = $db->query($isql);	      	   
	$usuario_actual = $rs->fields["USUA_NOMB"];
//include_once "$ruta_raiz/flujoGrafico.php";
?>
<center>
<table  width="100%"  align="center"  class="table-bordered table-striped table-condensed table-hover smart-form has-tickbox"  >
  <tr   >
    <td width=25% class="titulos2" height="24">ESTADO ACTUAL</td>
    <td  width=25%  height="24" align="left"><?=$descFldExp?></td>
    <td width=25% class="titulos2" height="24">DIAS ESPERADOS PARA ESTE ESTADO</td>
    <td  width=25%  height="24"><?=$expTerminosP?> Dias Habiles</td>
  </tr>
    <tr  class='etextomenu' >
    <td  class="titulos2" height="24">DIAS HABILES DE PROCESO </td>
    <td   height="24"><?=$tReal?></td>
    <td  class="titulos2" height="24">DIAS HABILES LEGALES </td> 
    <td   height="24"><?=$expTerminos?></td>
  </tr>
 </table>
</center>
 <table width="100%" class="table-bordered table-striped table-condensed table-hover smart-form has-tickbox">
  <tr>
    <td height="25" class="titulos4">FLUJO HISTORICO DEL EXPEDIENTE ---- FECHA DE INICIO DE PROCESO <?=$expFechaCrea?></td>
  </tr>
</table>
<table  width="100%" class="table-bordered table-striped table-condensed table-hover smart-form has-tickbox" >
  <tr   align="center">
    <th width=100 class="titulos2" height="20"><small>DEPENDENCIA </small></th>
    <th  width=100 class="titulos2" height="20"><small>FECHA</small></th>
     <th  width=100 class="titulos2" height="20"><small>TRANSACCION</small> </th>  
    <th  width=100 class="titulos2" height="20" ><small>USUARIO</small></th>
		<th  width=100 class="titulos2" height="20" ><small>RADICADO</small></th>
    <th  width=200 height="20" class="titulos2"><small>COMENTARIO</small></th>
	<?
		/** FLUJO GRAFICO DE LOS ESTADOS POR EL CUAL PASA EL EXPEDIENTE
			*/
 	$isql = "select 
					fe.SGD_FEXP_DESCRIP
					,fe.SGD_FEXP_TERMINOS
					,fe.SGD_FEXP_CODIGO
					,fe.SGD_FEXP_ORDEN
			from SGD_FEXP_FLUJOEXPEDIENTES fe
		 where 
			fe.SGD_PEXP_CODIGO ='$codigoTipoExp'
			order by fe.SGD_FEXP_ORDEN  ";  
	$rs = $db->query($isql);
	$terminosTotales = 0;
if($rs)
{
	while(!$rs->EOF)
	{
		$etapaFlujo = $rs->fields["SGD_FEXP_DESCRIP"];
		$etapaFlujoTerminos = $rs->fields["SGD_FEXP_TERMINOS"];
		$terminosTotales = $terminosTotales + $etapaFlujoTerminos;
		$codFlujo = $rs->fields["SGD_FEXP_CODIGO"];
		$codOrden = $rs->fields["SGD_FEXP_ORDEN"];
		$flujoCodigo[$codFlujo] = $codOrden;
		$flujoTerminosReal[$codFlujo] = $terminosTotales;
		?>
		<th  width=300 height="10" align=center><small><?=$etapaFlujo?><br>(<?=$etapaFlujoTerminos?> / <?=$flujoTerminosReal[$codFlujo]?> Dias)</small></th>
		<?
		$rs->MoveNext();
	}
}
	?>
  </tr>
  <?
  $sqlFecha = $db->conn->SQLDate("d-m-Y H:i A","he.SGD_HFLD_FECH");
	$sqlFecha = $db->conn->SQLDate("Y-m-d","he.SGD_HFLD_FECH");
 	$isql = "select $sqlFecha as HIST_FECH
			, he.DEPE_CODI
			, he.USUA_CODI
			, he.RADI_NUME_RADI
			, he.SGD_HFLD_OBSERVA as HIST_OBSERVA 
			, he.USUA_DOC
			, he.SGD_TTR_CODIGO
			, he.RADI_NUME_RADI
			, he.SGD_FEXP_CODIGO
			, $sqlFecha as FECHA
			from SGD_HFLD_HISTFLUJODOC he
		 where 
			he.SGD_EXP_NUMERO ='$numeroExpediente'
			order by he.SGD_HFLD_FECH desc ";  

	$i=1;
	$rs = $db->query($isql);
if($rs)
{
    while(!$rs->EOF)
	 {
		$usua_doc_dest = "";
		$usua_doc_hist = "";
		$usua_nomb_historico = "";
		$usua_destino = "";
		$numdata =  trim($rs->fields["CARP_CODI"]);
		if($data =="") $rs1->fields["USUA_NOMB"];
	   		$data = "NULL";
		$numerot = $rs->fields["NUM"];
		$usua_doc_hist = $rs->fields["USUA_DOC"];
		$usua_codi_dest = $rs->fields["USUA_CODI_DEST"];
		$usua_dest=intval(substr($usua_codi_dest,3,3));
		$depe_dest=intval(substr($usua_codi_dest,0,3));
		$usua_codi = $rs->fields["USUA_CODI"];
		$depe_codi = $rs->fields["DEPE_CODI"];
		$codTransac = $rs->fields["SGD_TTR_CODIGO"];
		$descTransaccion = $rs->fields["SGD_TTR_DESCRIP"];
    if(!$codTransac) $codTransac = "0";
		$trans->Transaccion_codigo($codTransac);
		$objUs->usuarioDocto($usua_doc_hist);
		$objDep->Dependencia_codigo($depe_codi);

		error_reporting(0);
		if($carpeta==$numdata)
			{
			$imagen="usuarios.gif";
			}
		else
			{
			$imagen="usuarios.gif";
			}
		if($i==1)
			{
		?>
  <tr class='tpar'> <?  
		    $i=1;
			}
			 ?>
    <td  >
	<?=$objDep->getDepe_nomb()?></td>
    <td >
	<?
			$expFechaHist = $rs->fields["HIST_FECH"];
			echo $expFechaHist;
	?>
 </td>
<td   >
  <?=$trans->getDescripcion()?>
</td>
<td   >
   <?=$objUs->get_usua_nomb()?>
</td>
<td   >
   <?=$rs->fields["RADI_NUME_RADI"]?>
</td>
		<?
		 /**
			 *  Campo qque se limino de forma Temporal USUARIO - DESTINO 
			 * <td class="celdaGris"  >
			 * <?=$usua_destino?> </td> 
			 */
		?>
		<td  width="200"><?=$rs->fields["HIST_OBSERVA"]?></td>
	<? 
			$flujoCodigoActual = $flujoCodigo[$rs->fields["SGD_FEXP_CODIGO"]];
			for($i=0;$i<=$flujoCodigoActual-1;$i++)
			{
					if($flujoCodigoActual-1==$i)
						{
								$classMostrar="titulos4";
							//$fondoImg = "$ruta_raiz/imagenes/internas/moverA.gif";
						}else
						{
							  $classMostrar="titulosError";
								$fondoImg = "";
						}
				?>
						<td class="<?=$classMostrar?>" width="100" background="<?=$fondoImg?>" align="top">
						<?
								if($flujoCodigoActual-1==$i)
								{
								 $tReal = $a->diasHabiles($expFechaCrea,$rs->fields["FECHA"]);
								 echo "$tReal Dias";
								}
						?>
						</td>
				<?
			}
	?>
  </tr>
  <?
	$rs->MoveNext();
  	}
}
  // Finaliza Historicos
	?>
</table>
</div>
</div>
</div>
</body>
</html>
