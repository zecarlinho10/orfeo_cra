<?
/*************************************************************************************/
/* C.R.A.  "COMISION DE REGULACION DE AGUAS Y SANEAMIENTO AMBIENTAL"                 */ 
/*  2022 Carlos Ricaurte      cricaurte@cra.gov.co      Desarrollador            */
/*************************************************************************************/
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

session_start();


$per=1;
$ruta_raiz = "..";
include "$ruta_raiz/rec_session.php";
include_once("$ruta_raiz/include/db/ConnectionHandler.php");
include_once "$ruta_raiz/include/tx/Historico.php";

include_once "documento.php";
include_once "expediente.php";

$db = new ConnectionHandler("$ruta_raiz");
$objHistorico= new Historico($db);

$encabezado = session_name()."=".session_id()."&krd=$krd";
?>
<html height=50,width=150>
<head>
<title>Cambio Estado Expediente</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<!-- CSS -->
<link href="../estilos/jquery-ui.css" rel="stylesheet">
	
	<script src="../js/chosen.jquery.js"></script>
	<script src="../js/jquery-ui.min.js"></script>
	<!-- Just to make our placeholder images work. Don't actually copy the next line! -->
	<script src="../js/holder.min.js"></script>
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="../js/ie10-viewport-bug-workaround.js"></script>
	<!-- Bootstrap core CSS -->
	<link
		href="../estilos/bootstrap-chosen.css?sdf=<?php echo date("ymdhis")?>"
		rel="stylesheet" />
	<link href="../estilos/bootstrap-dialog.css" rel="stylesheet" />


	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->


	<link href="../estilos/ie10-viewport-bug-workaround.css"
		rel="stylesheet" />

	<!-- Custom styles for this template -->
	<link href="../estilos/dashboard.css" rel="stylesheet" />

	<script src="../js/ie-emulation-modes-warning.js"></script>

	<!-- jQuery -->
	<script type="text/javascript"
		src="../js/jquery.validate.min.js?sdf=<?php echo date("ymdhis")?>">
	</script>
	<!--funciones-->
	<script type="text/javascript">
		 var basePath ='<?php echo $ruta_raiz?>';
 	</script>
	<script type="text/javascript"
		src="../js/bootstrap/bootstrap-dialog.js">
	</script>
<script src="ajax.js"></script>

<body bgcolor="#FFFFFF">


<?
$expediente = $_GET['expediente'];
$dependencia= $_GET['dependencia'];
$fecha=$_GET['fecha'];
$responsable=$_GET['responsable'];
$nombre=$_GET['nombre'];

$objExpediente = new ExpedienteSerializable($expediente,$dependencia,$fecha,$responsable,$nombre,"My class");

/*FUNCIONES PHP PARA INDICES*/
/**
 * Esta función devuelve el número de páginas de un archivo pdf
 * Tiene que recibir la ubicación y nombre del archivo
 */
function numeroPaginas($archivo){

	$ret=0;
	$extension = substr($archivo, -4, strlen($archivo));
	if($extension==".pdf"){
		$ret=numeroPaginasPdf($archivo);
	}
	else if($extension=="docx"){
		$ret=get_num_pages_docx($archivo);
	}
	else if($extension==".doc"){
		//$ret=get_num_pages_doc($archivo);
	}

	return $ret;
}

function numeroPaginasPdf($archivoPDF){
	$ret=0;
	$stream = fopen($archivoPDF, "r");
	$content = fread ($stream, filesize($archivoPDF));

	if (file_exists($archivoPDF)) {
	    if(!$stream || !$content)
			return 0;
	 
		$count[0] = 0;
	 
		$regex  = "/\/Count\s+(\d+)/";
		$regex2 = "/\/Page\W*(\d+)/";
		$regex3 = "/\/N\s+(\d+)/";
	 	if(preg_match_all($regex, $content, $matches))
			$count = max($matches);

		return $count[0];
	} else {
	    return 0;
	}
}

function get_num_pages_docx($filename)
{
       $zip = new ZipArchive();

       if($zip->open($filename) === true)
       {  
           if(($index = $zip->locateName('docProps/app.xml')) !== false)
           {
               $data = $zip->getFromIndex($index);
               $zip->close();

               $xml = new SimpleXMLElement($data);
               return $xml->Pages;
           }

           $zip->close();
       }

       return false;
}



function get_num_pages_doc($filename) 
{
       $handle = fopen($filename, 'r');
       $line = @fread($handle, filesize($filename));

           $hex = bin2hex($line);
           $hex_array = str_split($hex, 4);
           $i = 0;
           $line = 0;
           $collection = '';
           foreach($hex_array as $key => $string)
           {
               $collection .= hex_ascii($string);
               $i++;

               if($i == 1)
               {
                   //echo '<b>'.sprintf('%05X', $line).'0:</b> ';
               }

               echo strtoupper($string).' ';

               if($i == 8)
               {
                   //echo ' '.$collection.' <br />'."\n";
                   $collection = '';
                   $i = 0;

                   $line += 1;
               }
           }

       return $line;
}

function hex_ascii($string, $html_safe = true)
{
       $return = '';

       $conv = array($string);
       if(strlen($string) > 2)
       {
           $conv = str_split($string, 2);
       }

       foreach($conv as $string)
       {
           $num = hexdec($string);

           $ascii = '.';
           if($num > 32)
           {   
               $ascii = unichr($num);
           }

           if($html_safe AND ($num == 62 OR $num == 60))
           {
               $return .= htmlentities($ascii);
           }
           else
           {
               $return .= $ascii;
           }
       }

       return $return;
}

function unichr($intval)
{
       return mb_convert_encoding(pack('n', $intval), 'UTF-8', 'UTF-16BE');
}

?>
<form name=cambiar action="" method='post'>

	<div align="center">
                <h3>Número Expediente: <? echo $expediente; ?></h3>
                <h4>Dependencia: <? echo $dependencia; ?></h4>
                <h4>Fecha: <? echo $fecha; ?></h4>
                <h4>Responsable: <? echo $responsable; ?></h4>
                <h4>Nombre Expediente: <? echo $nombre; ?></h4>
           	
            <table border="1">
            	<tr>
            		<th>ID</th>
			    	<th>Nombre del documento</th>
			    	<th>Tipología documental</th>
			    	<th>Fecha de declaración de documento de archivo</th>
			    	<th>Fecha incorporación al expediente</th>
			    	<th>Valor Huella</th>
			    	<th>Función Resumen</th>
			    	<th>Orden documento expediente</th>
			    	<th>Pag. Inicio</th>
			    	<th>Pag. Fin</th>
			    	<th>formato</th>
			    	<th>Tamaño(kb)</th>
			    	<th>Origen</th>
			  	</tr>
            <?

	    $sqlI="SELECT SGD_EXP_NUMERO, R.RADI_NUME_RADI, R.RADI_PATH, SGD_TPR_DESCRIP, R.RADI_FECH_RADI, SGD_EXP_FECH, R.RA_ASUN
			   FROM FLDOC.SGD_EXP_EXPEDIENTE E, FLDOC.RADICADO R 
     		   RIGHT JOIN FLDOC.SGD_TPR_TPDCUMENTO T ON R.TDOC_CODI = T.SGD_TPR_CODIGO
			   WHERE SGD_EXP_NUMERO = '$expediente' AND E.RADI_NUME_RADI = R.RADI_NUME_RADI";
	//echo $sqlI;
 	$rsI = $db->query($sqlI);
            $cIndices=1;
            $cPaginas=1;
            $Ianexo="Si";
            $cadenaSalida="";
            while(!$rsI->EOF){

			  	$nPaginas=0;
			  	$radiI=$rsI->fields['RADI_NUME_RADI'];
				$trdI=$rsI->fields['SGD_TPR_DESCRIP'];
				$pathI=$rsI->fields['RADI_PATH'];
				$asunI=$rsI->fields['RA_ASUN'];
				$fechaI=$rsI->fields['RADI_FECH_RADI'];
				$fechaExpI=$rsI->fields['SGD_EXP_FECH'];
				$padre=$ruta_raiz . "/bodega/" . $pathI;
				if (file_exists($padre)) {
					//echo "radiI:" . $radiI . "pathI:" . $pathI . "<br>";
					//echo "PAPA SI existe:" . $padre ."<br>";
					$origen="Electronico";
					$extension = substr($pathI, -4, strlen($pathI));
					if($extension==".pdf"){
						$origen="Digital";
					}
					
					$stream = fopen($padre, "r");
					$content = fread ($stream, filesize($padre));
					$nPaginas=numeroPaginas($padre);
					$tamanoArchivo=number_format(filesize($padre)/1024,2, '.', '');
					$huellaI = md5_file($padre);
					
					if(!empty($pathI)){
						$cadenaSalida .= "<tr>";
						$cadenaSalida .= "<td>" .$radiI ."</td>";
						$cadenaSalida .= "<td>" .$pathI ."</td>";
						$cadenaSalida .= "<td>" .$trdI ."</td>";
						$cadenaSalida .= "<td>" .$fechaI ."</td>";
						$cadenaSalida .= "<td>" .$fechaExpI ."</td>";
						$cadenaSalida .= "<td>" .$huellaI ."</td>";
						$cadenaSalida .=  "<td>MD5</td>";
						$cadenaSalida .= "<td>" .$cIndices ."</td>";
						$cadenaSalida .= "<td>" .$cPaginas ."</td>";
						$cPaginas+=$nPaginas;
						$cadenaSalida .= "<td>" .($cPaginas-1) ."</td>";
						$cadenaSalida .= "<td>" .$extension ."</td>";
						$cadenaSalida .= "<td>" .$tamanoArchivo ."</td>";
						$cadenaSalida .= "<td>" .$origen ."</td>";
						$cadenaSalida .=  "</tr>";

						$cIndices++;
						
						$objExpediente->addDocument($radiI,$pathI,$trdI,$fechaI,$fechaExpI,$huellaI,"MD5",$cIndices-1,($cPaginas-$nPaginas), $cPaginas-1,$extension,$tamanoArchivo,$origen);
					}
					
				}

				$pathI="";
				$asunI="";
				//$fechaI="";
				//$fechaExpI="";
				$origen="";
				$extension="";
				$nPaginas=0;
				$tamanoArchivo=0;
				$huellaI = "";

				$sqlA= "SELECT A.ANEX_RADI_NUME, ANEX_NOMB_ARCHIVO, ANEX_DESC, ANEX_FECH_ANEX
				FROM ANEXOS A
				WHERE ANEX_BORRADO='N' AND ANEX_RADI_NUME = $radiI AND ANEX_BORRADO = 'N' AND
					ANEX_SALIDA = 0 AND
					(A.ANEX_NOMB_ARCHIVO LIKE '%.pdf' OR A.ANEX_NOMB_ARCHIVO LIKE '%.docx')
					ORDER BY ANEX_FECH_ANEX";
				//echo $sqlA;
				$rsA = $db->query($sqlA);
				while(!$rsA->EOF){
					$tmp=explode(".", $rsA->fields['ANEX_NOMB_ARCHIVO']);
					$radiA=$tmp[0];
					$pathA=$rsA->fields['ANEX_NOMB_ARCHIVO'];
					$asunA=$rsA->fields['ANEX_DESC'];
					$fechaA=$rsA->fields['ANEX_FECH_ANEX'];
					$rutaAbs = substr($radiI, 0, 4) . "/" . intval(substr($radiI, 4, 3) ). "/docs/". $pathA;
					$archivoDOC=$ruta_raiz . "/bodega/" . $rutaAbs;
						
					if (file_exists($archivoDOC)) {
						//echo "SI existe:" . $archivoDOC ."<br>";
						$origen="Electronico";
						$nPaginas=0;
						$extension = substr($pathA, -4, strlen($pathA));
						if($extension==".pdf"){
							$origen="Digital";
						}
						
						
						$nPaginas=numeroPaginas($archivoDOC);
						//echo "---Npaginas:" . $nPaginas ."<br>";
						$tamanoArchivo=number_format(filesize($archivoDOC)/1024,2, '.', '');
						$huellaA = md5_file($archivoDOC);
						$dir=$archivoDOC;

						$cadenaSalida .=  "<tr>";
						$cadenaSalida .=  "<td>" . $radiA ."</td>";
						$cadenaSalida .= "<td>" .$rutaAbs ."</td>";
						$cadenaSalida .= "<td>" .$trdI ."</td>";
						$cadenaSalida .= "<td>" .$fechaI ."</td>";
						$cadenaSalida .= "<td>" .$fechaExpI ."</td>";
						$cadenaSalida .= "<td>" .$huellaA ."</td>";
						$cadenaSalida .=  "<td>MD5</td>";
						$cadenaSalida .= "<td>" .$cIndices ."</td>";
						$cadenaSalida .= "<td>" .$cPaginas ."</td>";
						$cPaginas+=$nPaginas;
						$cadenaSalida .= "<td>" .($cPaginas-1) ."</td>";
						$cadenaSalida .= "<td>" .$extension ."</td>";
						$cadenaSalida .= "<td>" .$tamanoArchivo ."</td>";
						$cadenaSalida .= "<td>" .$origen ."</td>";
						$cadenaSalida .=  "</tr>";
						$cIndices++;
						$objExpediente->addDocument($radiA,$rutaAbs,$trdI,$fechaI,$fechaExpI,$huellaA,"MD5",$cIndices-1,($cPaginas-$nPaginas), $cPaginas-1,$extension,$tamanoArchivo,$origen);
					}	
					else{
						//echo "NO existe:" . $archivoDOC ."<br>";
					}
					$radiA="";
					$pathA="";
					$asunA="";
					$fechaA="";
					$origen="";
					$nPaginas=0;
					$extension = "";
					$origen="";
					$tamanoArchivo=0;
					$huellaA = "";
					$dir="";
						
					$rsA->MoveNext();
				} 

				$rsI->MoveNext();
				
		    } 
		    echo ($cadenaSalida);
		    
            ?>
            </table>
            <br>
            <div class="modal-footer">
            	<?
            	$objExpediente->generaCuerpo();
            	$datosCodificados=json_encode(serialize($objExpediente));
            	

            	//$obj = new MyChildClass(15, 'My class name', 'My data');
            	
            	echo "<button type='button' onclick='generaJavaXML(".$datosCodificados.")'>Generar Acta Foliado de expediente</button>";
            	?>
                <input type="button" value="Cerrar" class="botones_3" onclick="window.close()">
            </div>
            <div id="resultado">
	</div>
</form>

<script type="text/javascript">
    // Muestra las imagenes de los radicados
    function funlinkArchivo(numrad, rutaRaiz){
        var nombreventana = "linkVistArch";
        var url           = rutaRaiz + "/linkArchivo.php?<? echo session_name()."=".session_id()?>"+"&numrad="+numrad;
        var ventana       = window.open(url,nombreventana,'scrollbars=1,height=50,width=250');
        //setTimeout(nombreventana.close, 70);
        return;
    }
</script>
</html>
