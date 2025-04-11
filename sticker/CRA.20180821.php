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
h1,p{
    margin: 0px;
}
td{
    width:auto;
}

</style>
</head>
<?

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db     = new ConnectionHandler($ruta_raiz);

$noRad = $_REQUEST['nurad'];
$isql="SELECT S.DEPE_CODI, D.DEPE_NOMB, S.SGD_SRD_CODIGO, SE.SGD_SRD_DESCRIP, S.SGD_SBRD_CODIGO, SS.SGD_SBRD_DESCRIP, SGD_SEXP_PAREXP1, SGD_SEXP_PAREXP2, S.SGD_EXP_NUMERO
        FROM SGD_EXP_EXPEDIENTE E, SGD_SEXP_SECEXPEDIENTES S, DEPENDENCIA D, SGD_SRD_SERIESRD SE, SGD_SBRD_SUBSERIERD SS
        WHERE E.SGD_EXP_NUMERO = S.SGD_EXP_NUMERO AND S.DEPE_CODI=D.DEPE_CODI AND SE.SGD_SRD_CODIGO=S.SGD_SRD_CODIGO AND 
              S.SGD_SBRD_CODIGO=SS.SGD_SBRD_CODIGO AND S.SGD_SRD_CODIGO=SS.SGD_SRD_CODIGO AND 
              RADI_NUME_RADI=$noRad ORDER BY E.SGD_EXP_FECH";

$rs = $db->conn->query($isql);

while (! $rs->EOF) {
    $depecodi=$rs->fields["DEPE_CODI"];
    $depenom=$rs->fields["DEPE_NOMB"];
    $l1=$depecodi . "-" . $depenom;
    $l1 = substr( $l1, 0, 66 );
    $seriecod=$rs->fields["SGD_SRD_CODIGO"];
    $serienom=$rs->fields["SGD_SRD_DESCRIP"];
    $l2=$seriecod . "-" . $serienom;
    $l2 = substr( $l2, 0, 40 );
    $sscod=$rs->fields["SGD_SBRD_CODIGO"];
    $ssnom=$rs->fields["SGD_SBRD_DESCRIP"];
    $l3=$sscod . "-" . $ssnom;
    $l3 = substr( $l3, 0, 40 );
    if($rs->fields["SGD_SEXP_PAREXP1"]!=NULL){
        $nomexp=$rs->fields["SGD_SEXP_PAREXP1"];
    }
    else{
        $nomexp=$rs->fields["SGD_SEXP_PAREXP2"];
    }
    
    $numexp=$rs->fields["SGD_EXP_NUMERO"];
    $rs->MoveNext();
}
$nomexp = substr( $nomexp, 0, 38 );
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
                <p><span>Dependencia:<?=$l1?></span></p>
                <p><span> Serie: <?=$l2?> </span></p>
                <p><span>Subserie: <?=$l3?> </span></p>
                <p><span>T.Carpeta: <?=$nomexp?> </span></p>
                <p><span>F.Inicial:______________  F.Final:______________</span></p>
                <p><span>Folios:________ Carpeta No:___ De:___</span></p>
                <h1>EXP: <?=$numexp?></h1>
            </td>
        </tr>
    </table>
</body>
</html>

