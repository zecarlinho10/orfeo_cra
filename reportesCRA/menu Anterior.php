<?
	$ruta_raiz = "..";
	session_start();
	if(!$_SESSION['dependencia'] or !$_SESSION['tpDepeRad']) include "$ruta_raiz/rec_session.php";	
	$phpsession = session_name()."=".session_id();
?>
<html>
<head>
<title>Documento  sin t&iacute;tulo</title>

<link rel="stylesheet" href="../estilos/orfeo.css">
</head>

<body>
  <table width="31%" align="center" border="0" cellpadding="0" cellspacing="5" class="borde_tab">
  <tr bordercolor="#FFFFFF">
    <td colspan="2" class="titulos4"><div align="center"><strong>REPORTES CRA</strong></div></td>
  </tr>
  <tr bordercolor="#FFFFFF">
		<td align="center" class="listado2" width="48%">
			<a href='lisEntregaArchivEntradas.php?<?=$phpsession ?>&krd=<?=$krd?>' onClick="window.status='hola tu';return true" onmouseover="window.status='';return true" onmouseout="window.status='';return true" target='mainFrame' class="vinculos">1. Listado de radicados Entrantes </a>
		</td>
		<td align="center" class="listado2" width="48%">
			<a href='lisEntregaArchivSalidas.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">2. Listado de radicados salientes</a>
		</td>
  </tr>
<tr bordercolor="#FFFFFF">
		<td align="center" class="listado2" width="48%">
			<a href='repradentrada.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">3. Radicados de Entrada en Proceso</a>
		</td>

		<td align="center" class="listado2" width="48%">
			<a href='repradsalida.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">4. Radicados de Salida en Proceso</a>
		</td>
		
	</tr>
		<tr>
		
		<td align="center" class="listado2" width="48%">
			<a href='repAsignacionRadicados.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">5. Reporte Radicados por Transacci&oacute;n </a></td>
		<td align="center" class="listado2" width="48%">
			<a href='reptransaccexped.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">6. Reporte Expedientes por Transacci&oacute;n </a>
		</td>
  </tr>
  <tr bordercolor="#FFFFFF">
  
  		<td align="center" class="listado2" width="48%">
			<a href='repradentvcmto.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">7. Radicados Seg&uacute;n Vencimiento</a>

		<td align="center" class="listado2" width="48%">
			<p><a href='../historico/consultaESP.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">8. Archivo Hist&oacute;rico</a>        </p>
		</td>
		
  </tr>
  <tr>
  <td align="center" class="listado2" width="48%">
			<p><a href='repradtipdocumental.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">9. Radicados por Tipo Documental</a>        </p>

  <td align="center" class="listado2" width="48%">
  <a href='../trd/informe_trd2.php?<?=$phpsession ?>&krd=<?=$krd?>&krd=<?=$krd?>&<? echo "fechah=$fechah"; ?>' class="vinculos" target='mainFrame'>10. Listado 
          Tablas de Retencion Documental </a></td>
  		</td>   
<tr bordercolor="#FFFFFF">
		<td align="center" class="listado2" width="48%">
			<a href='repporresponder.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">11. Radicados por Responder</a>

		<td align="center" class="listado2" width="48%">
			<a href='./archivoHistorico/repvigencia.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">12. Reporte Vigencia Expedientes</a>
		</td>	     
  </tr>
 <tr bordercolor="#FFFFFF"> 
		<td align="center" class="listado2" width="48%">
			<a href='repradenviados.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">13. Reporte de Radicados Enviados</a>
		</td>	
		<td align="center" class="listado2" width="48%">
			<a href='repexpedientes.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">14. Expedientes</a>
		</td>	
		</tr>
<tr bordercolor="#FFFFFF"> 
		<td align="center" class="listado2" width="48%">
			<a href='repradusuarios.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">15. Reporte de Radicados por Usuario</a>
		</td>	
		<td align="center" class="listado2" width="48%">
			<a href='repradremdestino.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">16. Reporte de Radicados Remitente - Destino</a>
		</td>	
		</tr>
<tr bordercolor="#FFFFFF"> 
		<td align="center" class="listado2" width="48%">
			<a href='repanulados.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">17. Reporte de Radicados Anulados</a>
		</td>	
		<td align="center" class="listado2" width="48%">
			<a href='reptiempos.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">18. Reporte de Tiempos de Respuesta</a>
		</td>		
<tr bordercolor="#FFFFFF"> 		
			<td align="center" class="listado2" width="48%">
			<a href='ctr.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">19. CTR</a>
		</td>	
				<td align="center" class="listado2" width="48%">
			<a href='represpondidos.php?<?=$phpsession ?>&krd=<?=$krd?>' onClick="window.status='hola tu';return true" onmouseover="window.status='';return true" onmouseout="window.status='';return true" target='mainFrame' class="vinculos">20.Reporte de Radicados Tramitados</a>
		</td>
<tr bordercolor="#FFFFFF"> 		
			<td align="center" class="listado2" width="48%">
			<a href='./repradhistorico.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">21. B&uacute;squeda de Comentarios en Hist&oacute;rico  </a>
		</td>  
		
		<td align="center" class="listado2" width="48%">
			<a href='repradusuarios_rev.php?<?=$phpsession ?>&krd=<?=$krd?>' onClick="return true" onmouseover="window.status='';return true" onmouseout="window.status='';return true" target='mainFrame' class="vinculos">22.Radicados seg&uacute;n revisi&oacute;n o participaci&oacute;n de usuario</a>
		</td>
<tr bordercolor="#FFFFFF"> 		
			<td align="center" class="listado2" width="48%">
			<a href='./SER.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">23. Reporte Indicador SEA/SER  </a>
		</td>  
		<td align="center" class="listado2" width="48%">
			<a href='rep_sindirecc.php?<?=$phpsession ?>&krd=<?=$krd?>' onClick="return true" onmouseover="window.status='';return true" onmouseout="window.status='';return true" target='mainFrame' class="vinculos">24.Radicados no tramitados por la Direcci&oacute;n Ejecutiva</a>
		</td>
<tr bordercolor="#FFFFFF"> 		
			<td align="center" class="listado2" width="48%">
			<a href='./PDA.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">25. Reporte Indicador PDA  </a>
		</td>  
		<td align="center" class="listado2" width="48%">
			<a href='ind_gest.php?<?=$phpsession ?>&krd=<?=$krd?>' onClick="return true" onmouseover="window.status='';return true" onmouseout="window.status='';return true" target='mainFrame' class="vinculos">26.Reporte Indicador Gestión</a>
		</td>
<tr bordercolor="#FFFFFF"> 	
	
			<td align="center" class="listado2" width="48%">
			<a href='./PDA_usuario.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">27. Reporte Gestión Usuarios  </a>
		</td>  		
					<td align="center" class="listado2" width="48%">
			<a href='./PDA_curva_usuario.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">28. Reporte Curva Gestión Usuarios  </a>
		</td>  
<tr bordercolor="#FFFFFF"> 	
	
			<td align="center" class="listado2" width="48%">
			<a href='tiempouso.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">29. Tiempo uso Radicados por dependencia  </a>
		</td> 		
		<td align="center" class="listado2" width="48%">
			<a href='./TCE.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">30. Radicados marcados con traslado a comité de expertos  </a>
		</td> 

<tr bordercolor="#FFFFFF"> 	
	
			<td align="center" class="listado2" width="48%">
			<a href='ind_gest2.php?<?=$phpsession ?>&krd=<?=$krd?>' onClick="return true" onmouseover="window.status='';return true" onmouseout="window.status='';return true" target='mainFrame' class="vinculos">31.Reporte Indicador Gestión 2</a>
		</td> 		
		
<td align="center" class="listado2" width="48%">
			<a href='proceso.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">32. Reporte de radicados Digitalizacion</a>
		</td>

</table>
</body>
</html>
