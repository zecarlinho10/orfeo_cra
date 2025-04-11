<?

/**
 * @author  YULLIE QUICANO
 * @mail    yquicano@cra.gov.co
 * @version     1.0
 */
$ruta_raiz = "../";
session_start();
error_reporting(0);
require_once ($ruta_raiz . "include/db/ConnectionHandler.php");

if (! $db)
    $db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
foreach ($_GET as $key => $valor)
    ${$key} = $valor;
foreach ($_POST as $key => $valor)
    ${$key} = $valor;
    
    // En caso de no llegar la dependencia recupera la sesi�n
if (empty($_SESSION))
    include $ruta_raiz . "rec_session.php";

include ("common.php");
$fechah = date("ymd") . "_" . date("hms");
$krd = $_SESSION["krd"];
?>

<html>
<head>
<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
<title>Consultas Expedientes</title>
<meta http-equiv="Content-Type" content="text/html; utf-8">
<link rel="stylesheet" href="<?php echo $ruta_raiz?>estilos/orfeo.css">
<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
 <?php include_once "$ruta_raiz/js/funtionImage.php"; ?>
<script src="<?=$ruta_raiz?>/js/formchek.js"></script>
<script type="text/JavaScript">
	function Consultar() {
	window.open("<?=$ruta_raiz?>/expediente/conExp.php?krd=<?=$krd?>&numRad=<?=$verrad?>&dependencia=<?=$dependencia?>","Consulta Expedientes Existentes","height=800,width=1500,scrollbars=yes");
}
 

  	function noPermiso(){
		alert ("No tiene permiso para acceder");
	}
	
	function verHistExpediente(numeroExpediente,codserie,tsub,tdoc,opcionExp) {
  <?php
$isqlDepR = "SELECT RADI_DEPE_ACTU,RADI_USUA_ACTU from radicado
		            WHERE RADI_NUME_RADI = '$numrad'";
$rsDepR = $db->conn->Execute($isqlDepR);
$coddepe = $rsDepR->fields['RADI_DEPE_ACTU'];
$codusua = $rsDepR->fields['RADI_USUA_ACTU'];
$ind_ProcAnex = "N";

?>
  window.open("<?=$ruta_raiz?>/expediente/verHistoricoExp.php?opcionExp="+opcionExp+"&numeroExpediente="+numeroExpediente+"&nurad=<?=$verrad?>&krd=<?=$krd?>&ind_ProcAnex=<?=$ind_ProcAnex?>","HistExp<?=$fechaH?>","height=800,width=1060,scrollbars=yes");
}
</script>
<style type="text/css">
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #131212;
}
</style>
</head>

<body
	onLoad="crea_var_idlugar_defa(<?php echo "'".($_SESSION['cod_local'])."'"; ?>);">
<?php
$params = "krd=$krd";
?>
<script>
 function limpiar()
		{
	   document.formSeleccion.elements['nume_expe'].value = "";
	   document.formSeleccion.elements['dep'].value = "0";
	   document.formSeleccion.elements['nume_radi'].value = "";
	   document.formSeleccion.elements['nomexpe'].value = "";
	 
  }
</script>
	<form action="busquedaExp.php?<?=$params?>" method="post"
		enctype="multipart/form-data" name="formSeleccion" id="formSeleccion">
		<div class="panel-body">
			<div class="col-md-8 col-md-offset-1 form-group">
				<div class="row">
					<div class="col-md-12 text-center">
						<h3>
							<B><span class=etexto><a name="formSeleccion">B&uacute;squeda de
										Expedientes y Radicados Asociados</a></span></B>
						</h3>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 text-right">
						<h5>Expediente</h5>
					</div>
					<div class="col-md-6 text-left">
						<h5>
							<input class="tex_area input-md form-control" type="text" name="nume_expe"
								maxlength="18" value="<?=$nume_expe?>" >
						</h5>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 text-right">
						<h5>Radicado</h5>
					</div>
					<div class="col-md-6 text-left">
						<h5>
							<input class="tex_area input-md form-control" type="text" name="nume_radi"
								maxlength="17" value="<?=$nume_radi?>" >

						</h5>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 text-right">
						<h5>Dependencia Responsable</h5>
					</div>
					<div class="col-md-6 text-left ">
						<h5>
					<?php
    $sql = "SELECT 'Todas las dependencias' as DEPE_NOMB, 0 AS DEPE_CODI FROM DEPENDENCIA
                 UNION  SELECT DEPE_NOMB, DEPE_CODI AS DEPE_CODI FROM DEPENDENCIA
                 WHERE DEPE_CODI NOT IN (900,905,999,910,1,321,210,410,420,430,77)
				 order by depe_nomb DESC";
    // $db->conn->debug = true;
    $rsDep = $db->conn->Execute($sql);
    if (! $s_DEPE_CODI)
        $s_DEPE_CODI = 0;
    print $rsDep->GetMenu2("dep", "$dep", false, false, 0, " class='select dropdown'");
    ?></h5>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 text-right">
						<h5>Etiqueta o Nombre de Expediente</h5>
					</div>
					<div class="col-md-8 text-left">
						<h5>
							<input class="tex_area input-lg form-control" type="text" name="nomexpe"
								maxlength="4000" value="<?=$nomexpe?>" >
						</h5>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10 text-right">
						<input class="botones btn btn-primary" value="Limpiar"
							onClick="limpiar();" type="button"> <input name="Busqueda"
							type="submit" class="botones btn btn-primary" id="envia22"
							value="Busqueda">&nbsp;&nbsp;
						<!-- <a href="#" onClick="Consultar();" ><span class="leidos"><b>Consulta General</b></span></a> &nbsp; -->

					</div>
				</div>
			</div>
		</div>
	</form>        
   
<?php

if (! empty($_POST['Busqueda']) && ($_POST['Busqueda'] == "Busqueda")) {
    
    $where = "";
    
    $where = (! empty($_POST['dep']) && ($_POST['dep']) != "") ? (($where != "") ? $where . " AND S.DEPE_CODI=" . $_POST['dep'] . "" : " WHERE S.DEPE_CODI= " . $_POST['dep'] . "") : $where;
    
    $where = (! empty($_POST['nume_radi']) && trim($_POST['nume_radi']) != "") ? (($where != "") ? $where . " AND E.RADI_NUME_RADI LIKE '%" . strtoupper(trim($_POST['nume_radi'])) . "%'" : " WHERE E.RADI_NUME_RADI LIKE '%" . strtoupper(trim($_POST['nume_radi']) . "%' ")) : $where;
    
    $where = (! empty($_POST['nume_expe']) && trim($_POST['nume_expe']) != "") ? (($where != "") ? $where . " AND S.SGD_EXP_NUMERO LIKE '%" . strtoupper(trim($_POST['nume_expe'])) . "%'" : " WHERE S.SGD_EXP_NUMERO LIKE '%" . strtoupper(trim($_POST['nume_expe']) . "%' ")) : $where;
    
    $where = (! empty($_POST['nomexpe']) && trim($_POST['nomexpe']) != "") ? (($where != "") ? $where . " AND (s.sgd_sexp_parexp1||s.sgd_sexp_parexp2||s.sgd_sexp_parexp3||s.sgd_sexp_parexp4||s.sgd_sexp_parexp5) LIKE '%" . strtoupper(trim($_POST['nomexpe'])) . "%'" : " WHERE (s.sgd_sexp_parexp1||s.sgd_sexp_parexp2||s.sgd_sexp_parexp3||s.sgd_sexp_parexp4||s.sgd_sexp_parexp5) LIKE '%" . strtoupper(trim($_POST['nomexpe']) . "%'")) : $where;

    
    $camposConcatenar = "(" . $db->conn->Concat("s.sgd_sexp_parexp1", "s.sgd_sexp_parexp2", "s.sgd_sexp_parexp3", "s.sgd_sexp_parexp4", "s.sgd_sexp_parexp5") . ")";
    
    // Restringir Busqueda de la Depenmdencia Control Interno 11/07/2012 Ingeniero Juan Carlos Villalba
    if ($_SESSION['dependencia'] != '360') {
        // echo "in" .($_SESSION['dependencia']) ;
        $isql = "select distinct( e.radi_nume_radi), tp.sgd_tpr_descrip, s.sgd_exp_numero, r.ra_asun, r.radi_fech_radi, dir.sgd_dir_nomremdes, 	 r.radi_path,
                    s.depe_codi, d.depe_nomb, E.SGD_EXP_FECH,
                    $camposConcatenar as PARAMETRO, 
                    (se.sgd_srd_descrip||' - '||su.sgd_sbrd_descrip) AS SESUB
            from sgd_exp_expediente E
            		INNER JOIN SGD_SEXP_SECEXPEDIENTES S ON E.sgd_exp_numero = S.sgd_exp_numero
            		INNER JOIN 	RADICADO R ON E.RADI_NUME_RADI = R.RADI_NUME_RADI
					inner join sgd_dir_drecciones dir on dir.radi_nume_radi = r.radi_nume_radi,
            		dependencia d,
                    sgd_srd_seriesrd se,
		    SGD_TPR_TPDCUMENTO tp,
                    sgd_sbrd_subserierd su
                    {$where} AND 
                    s.sgd_srd_codigo = se.sgd_srd_codigo and
		    tp.sgd_tpr_codigo = R.tdoc_codi and
                    s.sgd_sbrd_codigo = su.sgd_sbrd_codigo and
                    s.sgd_srd_codigo  = su.sgd_srd_codigo and
                    s.depe_codi not in (900,905,910,999,360) and
                    s.depe_codi = d.depe_codi and e.sgd_exp_estado <> 2
                    order by SGD_EXP_NUMERO ASC, SGD_EXP_FECH DESC";
    } else {
        // echo "not in" .($_SESSION['dependencia']) ;
        $isql = "select distinct( e.radi_nume_radi), s.sgd_exp_numero, tp.sgd_tpr_descrip, 
                    r.ra_asun,  r.radi_fech_radi, dir.sgd_dir_nomremdes, 	 r.radi_path,
                    r.sgd_spub_codigo,
                    s.depe_codi, d.depe_nomb, E.SGD_EXP_FECH,
                    $camposConcatenar as PARAMETRO, 
                    (se.sgd_srd_descrip||' - '||su.sgd_sbrd_descrip) AS SESUB
            from sgd_exp_expediente E
            		INNER JOIN SGD_SEXP_SECEXPEDIENTES S ON E.sgd_exp_numero = S.sgd_exp_numero
            		INNER JOIN 	RADICADO R ON E.RADI_NUME_RADI = R.RADI_NUME_RADI
					inner join sgd_dir_drecciones dir on dir.radi_nume_radi = r.radi_nume_radi,
            		dependencia d,
                    sgd_srd_seriesrd se,
		    SGD_TPR_TPDCUMENTO tp,
                    sgd_sbrd_subserierd su
                    {$where} AND 
                    s.sgd_srd_codigo = se.sgd_srd_codigo and
		    tp.sgd_tpr_codigo = R.tdoc_codi and
                    s.sgd_sbrd_codigo = su.sgd_sbrd_codigo and
                    s.sgd_srd_codigo  = su.sgd_srd_codigo and
                    s.depe_codi not in (900,905,910,999) and
                    s.depe_codi = d.depe_codi and e.sgd_exp_estado <> 2
                    order by SGD_EXP_NUMERO ASC, SGD_EXP_FECH DESC";
    }
    
    // Restringir Busqueda de la Depenmdencia Control Interno 11/07/2012 Ingeniero Juan Carlos Villalba
    
    // $db->conn->debug = true;
    $rssql = $db->conn->Execute($isql);
    ?>
		<div class="row borde_tab text-center ">
		<div class="col-md-10 col-md-offset-2 titulos4 panel-header">RADICADOS ASOCIADOS Y
			EXPEDIENTE:</div>
	</div>
	<div class="row borde_tab text-center ">
		<div class="col-md-11 col-md-offset-1">
			<table style="text-align: center; border: 3px solid black; width: 80%"
				 class="borde_tab table table-bordered table-responsive">
				<tr >
					<td class="titulos2" align="center">EXPEDIENTE</td>
					<td class="titulos2" align="center" width="10%">FECHA ASOC.</td>
					<td class="titulos2" align="center">RADICADO</td>
					<td class="titulos2" align="center">ASUNTO</td>
					<td class="titulos2" align="center">TIPO DOC</td>
					<td class="titulos2" align="center">REMITENTE/DESTINO</td>
					<td class="titulos2" align="center" width="10%">FECHA RAD</td>
					<td class="titulos2" align="center">NOMBRE</td>
					<td class="titulos2" align="center">SERIE/SUBSERIE</td>
					<td class="titulos2" align="center">DEPENDENCIA</td>



				</tr>
<?php
    
    while (!empty($rssql) && ! $rssql->EOF) {
        $radi = $rssql->fields['RADI_NUME_RADI'];
        $fechradi = $rssql->fields['RADI_FECH_RADI'];
        $dir = $rssql->fields['SGD_DIR_NOMREMDES'];
        $asun = $rssql->fields['RA_ASUN'];
        $tipo_doc = $rssql->fields['SGD_TPR_DESCRIP'];
        $num_expediente = $rssql->fields['SGD_EXP_NUMERO'];
        $fechexp = $rssql->fields['SGD_EXP_FECH'];
        $par = $rssql->fields['PARAMETRO'];
        $sesub = $rssql->fields['SESUB'];
        $depen = $rssql->fields['DEPE_NOMB'];
        $priv = $rssql->fields['SGD_SPUB_CODIGO'];
        $linkInfGeneral = "<a class='vinculos' href='../verradicado.php?verrad=$radi&krd=$krd&carpeta=8&nomcarpeta=Busquedas&tipo_carp=0'>";
        ?>

        <tr >
					<td class="info" align="center" width="20%"><?=$num_expediente?><span
						class=leidos2> </span>&nbsp;<input type="button" value=".H."
						class="botones_2 btn btn-success glyphicon glyphicon-search"
						onClick="verHistExpediente('<?=$num_expediente?>');"></td>
					<td  class="info" align="center"><?=$fechexp?></td>
                <?if ($priv==1){?>
					<td class="info" align="center"><?=$radi?></td>
				 <?}else{?>
				<td class="info" align="center"><?=$rads= "<a href=\"#2\"  onclick=\"funlinkArchivo('".$radi."','$ruta_raiz');\" target\"Imagen$iii\">".$radi."";?>
					</td> <?}?>
                <td class="info" align="center"><?=$asun?></td>
					<td class="info" align="center"><?=$tipo_doc?></td>
					<td class="info" align="center"><?=$dir?></td>
					<td class="info" align="center"><?=$linkInfGeneral?><?= tohtml($fechradi)?></td>
					<td class="info" align="center"><?=$par?></td>
					<td class="info" align="center"><?=$sesub?></td>
					<td class="info" align="center"><?=$depen?></td>
				</tr> 
				<?
        $rssql->MoveNext();
    }
    
    ?>
	</table>
		</div>
	</div>
<?
}
?>

		
		</form>

</body>
</html>
