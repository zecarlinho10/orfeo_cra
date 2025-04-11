<?$ruta_raiz="../.."?>
<html>
<head>
    
<title>Sticker web</title>
        <script type="text/javascript">
            function imprimir() {
                if (window.print) {
                    window.print();
                } else {
                    alert("La función de impresion no esta soportada por su navegador.");
                }
            }
        </script>
<link rel="stylesheet" href="estilo_imprimir.css" TYPE="text/css" MEDIA="print">
<style type="text/css">

body {
    margin-bottom:0;
    margin-left:0;
    margin-right:0;
    margin-top:0;
    padding-bottom:0;
    padding-left:0;
    padding-right:0;
    padding-top:0
    font-family: Arial, Helvetica, sans-serif;
}

span{
    font-size:   15px;
    line-height: 15px;
    clear:       both;
}
h3,p{
    margin: 0px;
}
td{
    width:auto;
}

</style>
</head>

<body onload="imprimir();" marginheight="0" marginwidth="0">
<table align="left" height="60" border="0">
<tr>
<td colspan="2">
<FONT FACE="Arial" size="1">Destino: <?=$depeNomb?></font>
</td>
</tr>
<tr>
<td valign="center"><img width="80" src="<?=$dirLogo?>"></td>
<td align="center" >
<?=$noRadBarras?>
<br>
<FONT FACE="Arial" size="2">No. <?=$noRad?><br></font>
<FONT FACE="Arial" size="1">Fecha Radicado: <?=substr($radi_fech_radi,0,16)?><br>
<FONT FACE="Arial" size="1">Placa Minera: <?=$vct['RADI_CUENTAI']?><br>
Anexos: <?=$anexos?>.</font>
</td>

</tr>
</table>
</body>
</html>

