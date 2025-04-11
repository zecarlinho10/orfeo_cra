<?$ruta_raiz=".."?>
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
/*
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db     = new ConnectionHandler($ruta_raiz);
*/

$saludo = $_REQUEST['saludo'];
$destinatario=$_REQUEST['destinatario'];
$cargo=$_REQUEST['cargo'];
$nomempresa=$_REQUEST['empresa'];
$direccion=$_REQUEST['direccion'];
$telefono=$_REQUEST['telefono'];
$ciudad=$_REQUEST['ciudad'];

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
                <br>
                <p><span> <?=$saludo?> </span></p>
                <p><span> <?=$destinatario?> </span></p>
                <p><span> <?=$cargo?> </span></p>
                <p><span> <?=$nomempresa?> </span></p>
                <p><span> <?=$direccion?> </span></p>
                <p><span> <?=$telefono?> </span>
                <p><span> <?=$ciudad?> </span>
            </td>
        </tr>
    </table>
</body>
</html>

