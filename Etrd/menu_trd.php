<? 
/**
 * Este programa despliega el men� principal de administraci�n
 * tablas de Retenci�n documental
 * @version     1.0
 */
error_reporting(0);
session_start();
$ruta_raiz = "..";

require_once("$ruta_raiz/include/db/ConnectionHandler.php"); 
//Si no llega la dependencia recupera la sesi�n
if(!$_SESSION['dependencia']){
	include "$ruta_raiz/rec_session.php";
}
if (!$db)
		$db = new ConnectionHandler($ruta_raiz);

$phpsession = session_name()."=".session_id(); ?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../estilos/orfeo.css">
</head>
<body bgcolor="#FFFFFF" topmargin="0" onLoad="window_onload();">
<table width="47%" align="center" border="0" cellpadding="0" cellspacing="5" class="borde_tab">
     <tr> 
      <td height="25" class="titulos4"> 
        ADMINISTRACION  -TABLAS RETENCION DOCUMENTAL- 
      </td>
    </tr>
    <tr align="center"> 
      <td class="listado2" > 
        <a href='../trd/admin_series.php?<?=$phpsession ?>&krd=<?=$krd?>&krd=<?=$krd?>&<? echo "fechah=$fechah"; ?>' class="vinculos" target='mainFrame'>Series </a>
      </td>
    </tr>
    <tr align="center"> 
      <td class="listado2" > 
        <a href='../trd/admin_subseries.php?<?=$phpsession ?>&krd=<?=$krd?>&krd=<?=$krd?>&<? echo "fechah=$fechah"; ?>' class="vinculos" target='mainFrame'>Subseries </a>
      </td>
    </tr>
    <tr align="center"> 
      <td class="listado2" > 
        <a href='../trd/cuerpoMatriTRD.php?<?=$phpsession ?>&krd=<?=$krd?>&krd=<?=$krd?>&<? echo "fechah=$fechah"; ?>' class="vinculos" target='mainFrame'>Matriz 
          Relaci&oacute;n </a>
      </td>
    </tr>
    <tr align="center"> 
      <td class="listado2" >
	  <a href='../trd/admin_tipodoc.php?<?=$phpsession ?>&krd=<?=$krd?>&krd=<?=$krd?>&<? echo "fechah=$fechah"; ?>' class="vinculos" target='mainFrame'>Tipos 
          Documentales </a>
	  </td>
    </tr>
	<tr align="center"> 
      <td class="listado2" >
	  <a href='../trd/procModTrdArea.php?<?=$phpsession ?>&krd=<?=$krd?>&krd=<?=$krd?>&<? echo "fechah=$fechah"; ?>' class="vinculos" target='mainFrame'>Modificacion 
          TRD Area </a>
	  </td>
    </tr>
	<tr align="center"> 
      <td class="listado2" >
	  <a href='../trd/informe_trd.php?<?=$phpsession ?>&krd=<?=$krd?>&krd=<?=$krd?>&<? echo "fechah=$fechah"; ?>' class="vinculos" target='mainFrame'>Listado 
          Tablas de Retencion Documental </a>
	  </td>
	   </tr>  
		<tr align="center"> 
      <td class="listado2" >
	  <a href='../trd/tipificacion.php?<?=$phpsession ?>&krd=<?=$krd?>&krd=<?=$krd?>&<? echo "fechah=$fechah"; ?>' class="vinculos" target='mainFrame'>Tipificaci&oacute;n de Documentos </a>
	  </td>
	   </tr>    
    </tr>
  </table>
</body>
</html>
