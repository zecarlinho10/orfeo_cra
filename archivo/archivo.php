<?php
/*************************************************************************************/
/* ORFEO GPL:Sistema de Gestion Documental		http://www.orfeogpl.org	             */
/*	Idea Original de la SUPERINTENDENCIA DE SERVICIOS PUBLICOS DOMICILIARIOS         */
/*				COLOMBIA TEL. (57) (1) 6913005  orfeogpl@gmail.com                   */
/* ===========================                                                       */
/*                                                                                   */
/* Este programa es software libre. usted puede redistribuirlo y/o modificarlo       */
/* bajo los terminos de la licencia GNU General Public publicada por                 */
/* la "Free Software Foundation"; Licencia version 2. 			                     */
/*                                                                                   */
/* Copyright (c) 2005 por :	  	  	                                                 */
/* SSPD "Superintendencia de Servicios Publicos Domiciliarios"                       */
/*   Jairo Hernan Losada  jlosada@gmail.com                Desarrollador             */
/*   Sixto Angel Pinzón López --- angel.pinzon@gmail.com   Desarrollador           */
/* C.R.A.  "COMISION DE REGULACION DE AGUAS Y SANEAMIENTO AMBIENTAL"                 */ 
/*   Liliana Gomez        lgomezv@gmail.com                Desarrolladora            */
/*   Lucia Ojeda          lojedaster@gmail.com             Desarrolladora            */
/* D.N.P. "Departamento Nacional de Planeación"                                     */
/*   Hollman Ladino       hladino@gmail.com                Desarrollador             */
/*                                                                                   */
/* Colocar desde esta lInea las Modificaciones Realizadas Luego de la Version 3.5    */
/*  Nombre Desarrollador   Correo     Fecha   Modificacion                           */
/*  Fabian Mauricio Losada Florez      5-Octubre-2006 						     */
/*************************************************************************************/
 $krdOld = $krd;
$per=1;
session_start();
if(!$krd) $krd = $krdOld;
if (!$ruta_raiz) $ruta_raiz = "..";
include_once "$ruta_raiz/htmlheader.inc.php";
include "$ruta_raiz/rec_session.php";
include_once("$ruta_raiz/include/db/ConnectionHandler.php");
	$db = new ConnectionHandler("$ruta_raiz");
$encabezadol = "$PHP_SELF?".session_name()."=".session_id()."&num_exp=$num_exp&krd=$krd";
?>
<html height=50,width=150>
<head>
<title>Menu Archivo</title>
<link rel="stylesheet" href="../estilos/orfeo.css">
 <?php include_once "../htmlheader.inc.php"; ?>
</head>
<CENTER>
<body bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
 <link rel="stylesheet" type="text/css" href="../js/spiffyCal/spiffyCal_v2_1.css">
 <script language="JavaScript" src="../js/spiffyCal/spiffyCal_v2_1.js">
 </script>

 <form name=from1 action="<?=$encabezadol?>" method='post' action='archivo.php?<?=session_name()?>=<?=trim(session_id())?>&krd=<?=$krd?>&<?="&num_exp=$num_exp"?>'>
<br>

<table border=0 width 100% cellpadding="0" cellspacing="5" class="borde_tab">
<TD class=titulos2 align="center" >
		Menu de Archivo
	</TD>
	<tr>
	<td class=listado2>
<?
	$phpsession = session_name()."=".session_id();
	?>
<span class="leidos2"><a href='../CRA.cuerpoarch.php?<?=$phpsession?>&krd=<?=$krd?>&<?="fechaf=$fechah&carpeta=8&nomcarpeta=Expedientes&orno=1&adodb_next_page=1"; ?>' target='mainFrame' class="menu_princ"><b>1. Registro de Archivo Fisico </a></span>
	</td>
	</tr>
<tr>
  <td class="listado2">
    <span class="leidos2"><a href='../archivo/crearexpediente.php?<?=session_name()."=".session_id()."&dep_sel=$dep_sel&krd=$krd&fechah=$fechah&$orno&adodb_next_page&nomcarpeta&tipo_archivo=$tipo_archivo&carpeta'" ?>" target='mainFrame' class="menu_princ"><b>2. Creaci&oacute;n Independiente de Expedientes </a>
  </td>
</tr>


<tr>
  <td class="listado2">
    <span class="leidos2"><a href='../archivo/etqta_exped.php?<?=session_name()."=".session_id()."&dep_sel=$dep_sel&krd=$krd&fechah=$fechah&$orno&adodb_next_page'" ?>" target='mainFrame' class="menu_princ"><b>3. Cambio de Etiqueta y tipo en Expediente</a>
  </td>
</tr>
<tr>
  <td class="listado2">
    <span class="leidos2"><a href='../CRA.cuerpoexcluir.php?<?=session_name()."=".session_id()."&dep_sel=$dep_sel&krd=$krd&fechah=$fechah&$orno&adodb_next_page&nomcarpeta&tipo_archivo=$tipo_archivo&carpeta'" ?>" target='mainFrame' class="menu_princ"><b>4. Exclusi&oacute;n de Expedientes en F&iacute;sico</a>
  </td>
</tr>

	<tr>
	<td class=listado2>
<span class="leidos2"><a  href='reporte_archivo.php?<?=session_name()."=".session_id()."&krd=$krd&adodb_next_page&nomcarpeta&fechah=$fechah&$orno&carpeta&tipo=1'" ?>' target='mainFrame' class="menu_princ"><b>5. Reporte por Radicados Archivados</a>
	</td>
	</tr>
	
		<tr>
	<td class=listado2>
<span class="leidos2"><a  href='../CRA.cierre_exp.php?<?=session_name()."=".session_id()."&krd=$krd&adodb_next_page&nomcarpeta&fechah=$fechah&$orno&carpeta&tipo=1'" ?>' target='mainFrame' class="menu_princ"><b>6. Cierre de Expedientes Vencidos</a>
	</td>
	</tr>
	
		<tr>
	<td class=listado2>
<span class="leidos2"><a  href='../CRA.cierre_exp_especifico.php?<?=session_name()."=".session_id()."&krd=$krd&adodb_next_page&nomcarpeta&fechah=$fechah&$orno&carpeta&tipo=1'" ?>' target='mainFrame' class="menu_princ"><b>7. Cierre de Expedientes Particulares</a>
	</td>
	</tr>
	<tr>
	<td class=listado2>

<span class="leidos2"><a href='inventario.php?<?=session_name()."=".session_id()."&krd=$krd&adodb_next_page&nomcarpeta&fechah=$fechah&$orno&carpeta&tipo=2'" ?>' target='mainFrame' class="menu_princ"><b> 5.Cambio de Coleccion</a>
	  </td>
	</tr>
	<tr>
	<td class=listado2>

<span class="leidos2"><a href='inventario.php?<?=session_name()."=".session_id()."&krd=$krd&adodb_next_page&nomcarpeta&fechah=$fechah&$orno&carpeta&tipo=1'" ?>' target='mainFrame' class="menu_princ"><b>6.Inventario Consolidado Capacidad</a>	  </td>
	</tr>
	<tr>
	<td class=listado2>

<span class="leidos2"><a href='inventariod.php?<?=session_name()."=".session_id()."&krd=$krd&adodb_next_page&nomcarpeta&fechah=$fechah&$orno&carpeta&tipo=3'" ?>' target='mainFrame' class="menu_princ"><b> 12. Inventario Documental 2012</a>	
<!--<span class="leidos2"><a href='inventario.php?<?=session_name()."=".session_id()."&krd=$krd&adodb_next_page&nomcarpeta&fechah=$fechah&$orno&carpeta&tipo=3'" ?>' target='mainFrame' class="menu_princ"><b>7.Inventario Documental</a>-->	
</td>
	</tr>
<tr>
	<td class=listado2>

<span class="leidos2"><a href='sinexp.php?<?=session_name()."=".session_id()."&krd=$krd&adodb_next_page&nomcarpeta&fechah=$fechah&$orno&carpeta&tipo=3'" ?>' target='mainFrame' class="menu_princ"><b>8.Radicados Archivados Sin Expediente</a>	</td>
	</tr>
<tr>
	<td class=listado2>

<span class="leidos2"><a href='carpetasvirtuales.php?<?=session_name()."=".session_id()."&krd=$krd&adodb_next_page&nomcarpeta&fechah=$fechah&$orno&carpeta&tipo=3'" ?>' target='mainFrame' class="menu_princ"><b>11.Carpetas De Radicados Virtuales</a>	</td>
	</tr>


<tr>
	<td class=listado2>

<span class="leidos2"><a href='../CRA.abrir_exp_especifico.php?<?=session_name()."=".session_id()."&krd=$krd&adodb_next_page&nomcarpeta&fechah=$fechah&$orno&carpeta&tipo=3'" ?>' target='mainFrame' class="menu_princ"><b>9.Reabrir Expedientes</a></td>
	</tr>


<?
/*	
<span class="leidos2"><a href='inventario.php?<?=session_name()."=".session_id()."&krd=$krd&fechah=$fechah&$orno&adodb_next_page&nomcarpeta&carpeta&tipo=2'" ?>' target='mainFrame' class="menu_princ"><b> 5.Cambio de Coleccion</a>
	  </td>
	</tr>
	<tr>
	<td class=listado2>

<span class="leidos2"><a href='inventario.php?<?=session_name()."=".session_id()."&krd=$krd&fechah=$fechah&$orno&nomcarpeta&carpeta&tipo=1'" ?>' target='mainFrame' class="menu_princ"><b>6.Inventario Consolidado Capacidad</a>	  </td>
	</tr>
	<tr>
	<td class=listado2>
<span class="leidos2"><a href='inventario.php?<?=session_name()."=".session_id()."&krd=$krd&fechah=$fechah&$orno&adodb_next_page&nomcarpeta&carpeta&tipo=3'" ?>' target='mainFrame' class="menu_princ"><b>7.Inventario Documental</a>	</td>
	</tr>
<tr>
	<td class=listado2>
<span class="leidos2"><a href='sinexp.php?<?=session_name()."=".session_id()."&krd=$krd&fechah=$fechah&$orno&adodb_next_page&nomcarpeta&carpeta&tipo=3'" ?>' target='mainFrame' class="menu_princ"><b>8.Radicados Archivados Sin Expediente</a>	</td>
	</tr>
<tr>
	<td class=listado2>
<span class="leidos2"><a href='alerta.php?<?=session_name()."=".session_id()."&krd=$krd&fechah=$fechah&$orno&adodb_next_page" ?>' target='mainFrame' class="menu_princ"><b>9.Alerta Expedientes</a>	</td>
	</tr>

*/?>
</table>
</form>
</CENTER>
</html>
