 <?
     $ruta_raiz = "../";
       session_start();
       if(!$_SESSION['dependencia'] or !$_SESSION['tpDepeRad']) include "$ruta_raiz/rec_session.php";
       $phpsession = "";//session_name()."=".session_id();
       $krd = $_SESSION['krd'];
   ?>
   <html>
   <head>
  <title>Documento  sin t&iacute;tulo</title>
  <?include ($ruta_raiz."/htmlheader.inc.php")?>
 </head>
 
   <body><div class="table-responsive">
    <table class="table table-bordered table-striped mart-form">
    <tr bordercolor="#FFFFFF">
      <td colspan="2" class="titulos4"><div align="center"><strong>REPORTES CRA</strong></div></td>
    </tr>
    <tr bordercolor="#FFFFFF">
          <td align="center" class="listado2" width="48%">
              <a href='lisEntregaArchivEntradas.php?<?=$phpsession ?>&krd=<?=$krd?>' onClick="window.status='hola     tu';return true" onmouseover="window.status='';return true" onmouseout="window.status='';return true" target='    mainFrame' class="vinculos">1. Tipo de Petición </a>
          </td>
          <td align="center" class="listado2" width="48%">
              <a href='lisEntregaArchivSalidas.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vin    culos">2. Medio de recepción</a>
          </td>
    </tr>
  <tr bordercolor="#FFFFFF">
          <td align="center" class="listado2" width="48%">
              <a href='repradentrada.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">3.     Medio de Respuesta</a>
          </td>
 
          <td align="center" class="listado2" width="48%">
             <a href='repradsalida.php?<?=$phpsession ?>&krd=<?=$krd?>' target='mainFrame' class="vinculos">4. Por canal de Recepción </a>
          </td>
 
      </tr>
</table>
</body>
</html>
