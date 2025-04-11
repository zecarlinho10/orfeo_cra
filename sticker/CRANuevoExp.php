<?$ruta_raiz=".."?>
<html>
<head>
<title>Sticker web</title>
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
h1,p{
    margin: 0px;
}
td{
    width:auto;
}

</style>
</head>
<?
/*
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db     = new ConnectionHandler($ruta_raiz);
*/

$dependencia     = $_REQUEST["dependencia"];
$serie        = $_REQUEST["serie"];
$subserie    = $_REQUEST["subserie"];
$nomexpediente  = $_REQUEST["nomexpediente"];
$finicial   = $_REQUEST["finicial"];
$ffinal     = $_REQUEST["ffinal"];
$expediente     = $_REQUEST["expediente"];
$nfolios    = $_REQUEST["nfolios"];
$carpeta    = $_REQUEST["carpeta"];

?>
<body topmargin="5" leftmargin="0">
    <table width="400px" cellpadding="0" cellspacing="0" onload="window.print()">
        <tr>
            <td  align=left width="300px">
	    	<table width="100%">
	    		<tr>
    				<td><img src="<?=$dirLogo?>" alt="CRA"  ></td>
                    <td><b><center>COMISIÓN DE REGULACIÓN DE AGUA POTABLE Y SANEAMIENTO BASICO -CRA</center></b></td>
			    </tr>
		    </table>
                <p><span>Dependencia  : <?=$dependencia?> </span></p>
                <p><span>Serie        : <?=$serie?> </span></p>
                <p><span>SubSerie     : <?=$subserie?> </span></p>
                <p><span>Nombre Expediente: <?=$nomexpediente?> </span></p>
                <p><span>Fecha inicial: <?=$finicial?>  --  Fecha Final  : <?=$ffinal?></span></p>
                <p><span>Folios       : <?=$nfolios?> --  Carpeta  : <?=$carpeta?></span>
                <h1>EXP: <?=$expediente?> </h1>
            </td>
        </tr>
    </table>
</body>
</html>

