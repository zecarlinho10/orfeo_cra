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
<?
$noRad = $_REQUEST['nurad'];
?>
<body topmargin="5" leftmargin="0">
    <table width="400px" cellpadding="0" cellspacing="0" onload="window.print()">
        <tr>
            <td  align=left width="300px">
	    	<table width="100%">
	    		<tr>
				<td width="80%"><center><?=$noExpBarras?></center></td>
				<td width="20%"><img src="<?=$dirLogo?>" alt="<?=$entidad_corto?>"  height="42" width="42"></td>
			</tr>
		</table>
                <p><span><b> No: <?=$numExp?> </b></span></p>
                <p><span><b>Fecha: <?=$fecha?> </b></span></p>
	        <p><span><b>No Folios: <?=$numFoliosExp?> Paginas </b></span></p>
                <p><span><b>Serie: <?=$serie?> </b></span></p>
                <p><span><b>Subserie: <?=$subserie?> </b></span></p>
		<!--Falta incluir el destinatario-->
                <p><center><b><?=$entidad_largo?></b></br>
		<?=$entidad_dir?>, Tel: <?=$entidad_tel?>
		</center></p>
                <span  align="left"><b>
                </span>
            </td>
        </tr>
    </table>
</body>
</html>

