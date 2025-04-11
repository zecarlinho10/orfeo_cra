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
    <table width="200px" cellpadding="0" cellspacing="0" onload="window.print()">
        <tr>
            <td  align=left width="300px">
	    	<table width="100%">
	    		<tr>
				<td width="80%"><center><?=$noRadBarras?></center></td>
				<td width="20%"><img src="<?=$dirLogo?>" alt="<?=$entidad_corto?>"  height="42" width="42"></td>
			</tr>
		</table>
	    	<table width="100%">
	    		<tr>
                		<td><p><span style="font-size: 16"><b> No: <?=$nurad?> </b></span></p></td>
                		<td><span style="font-size: 16"><b>C&oacute;d veri:</b> <?=$sgd_rad_codigoverificacion?></span><br></td>
	    		</tr>
		</table>
                <p><span style="font-size: 13">Fecha: <?=$radi_fech_radi?></span></p>
	        <p><span style="font-size: 13">No Paginas: <?=$radi_nume_folio?>  </span><span style="font-size: 13">  No Anexos:<?=$anexos?> Anex Desc: <?=$anexDesc?> </span>  </p>
	        <!--<p><span style="font-size: 13">Asunto: <?=$asunto?></span></p>-->
	        <p><span style="font-size: 13">Remitente: <?=$remitente?></span></p>
		<!--Falta incluir el destinatario-->
                <p><center><span style="font-size: 11"><b><?=$entidad_largo?></b></span></br>
		<span style="font-size: 12"><b><?=$entidad_dir?>, Tel: <?=$entidad_tel?></b></span>
		</center></p>
                <span  align="left">
                </span>
            </td>
        </tr>
    </table>
</body>
</html>

