<?$ruta_raiz="../.."?>
<html>
<head>
<title>Sticker web</title>
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

<body marginheight="0" marginwidth="0">
<table align="left" height="94" width="320" border="0">
<tr>
<td colspan="2">
<div>
<div align="right" style="font-size: 10"><?=substr($radi_fech_radi,0,16)?></div>
<div align="left"  style="font-size: 9" >Destino: <?=$depeNomb?></div>
</div>
</td>
</tr>
<tr>
<td align="center"><img width="50" src="<?=$dirLogo?>"></td>
<td align="left" >
<span><?=$noRadBarras?></span>
<br>
<FONT FACE="Arial" style="font-size: 13">No. <?=$noRad?><br></font>
<FONT FACE="Arial" style="font-size: 10">Placa Minera: <?=$referencia?>  Folios: <?=$folios?><br></FONT>
<FONT FACE="Arial" style="font-size: 10">Anexos: <?=$anexos?>  Anex Desc: <?=$anexDesc?><br></FONT>
</td>

</tr>
</table>
</body>
</html>

