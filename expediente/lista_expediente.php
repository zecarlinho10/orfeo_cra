<div>
<?php
// include "./proceso/workFlowParcial.php";
session_start ();
$dependencia = $_SESSION ["dependencia"];

?>
</div>
<input type="hidden" name="menu_ver_tmp" value=4>
<input type="hidden" name="menu_ver" value=4>
<?php
unset ( $frm );
if ($verrad)
	$expediente->expedienteArchivado ( $verrad, $numExpediente );
$expediente->getInfoExpediente ( $numExpediente );
$nivelExp = $expediente->seguridad;
$docRes = $expediente->expUsuaDoc;
$param1 = $expediente->param;
$responsable = $expediente->responsable;
$isql = "SELECT USUA_PERM_EXPEDIENTE, ID from USUARIO WHERE USUA_LOGIN = '$krd'"; 
$rs = $db->query ( $isql );
//$db->conn->debug = true;
$krdperm = $rs->fields ['USUA_PERM_EXPEDIENTE'];
$krdperm2 = $rs->fields ['ID'];
//Permisos para Excluir Expedientes David Amaya Beltran
$isqlID = "SELECT * FROM AUTM_MEMBRESIAS WHERE AUTG_ID=367 AND AUTU_ID='$krdperm2'";
$rsd = $db->query ( $isqlID );
$Exclexp=$rsd->RecordCount();
//////////////////////////////////////////
$arch = $expediente->arch;
$mostrar = true;
if (! $tsub)
	$tsub = "0";
if (! $tdoc)
	$tdoc = "0";
if (! $codserie)
	$codserie = "0";
?>

	<?php
	if ($usuaPermExpediente) {
		$arrTMP = $expediente->getTRDExp ( $num_expediente, "", "", "" );
		?>
		<table class="table table-bordered table-striped" colspacing=0 cellspacing=0>
			<tr>
				<td width=140><span class="dropdown"> <a
				class="btn btn-xs btn-primary dropdown-toggle"
				data-toggle="dropdown" href="javascript:void(0);">
				&nbsp;&nbsp;Expediente&nbsp;&nbsp;<b class="caret"></b>
				</a>
				<ul class="dropdown-menu">
				<?php
				if ($usuaPermExpediente || ! $numExpediente) {
				?>
 					<li><a onClick="insertarExpediente();">Incluir en...</a></li>
				<?	
					if ($Exclexp<>0) {
					?>
 						<li><a onClick="excluirExpediente();">Excluir de...</a></li>
					<?}?>
					 <li><a onClick="verTipoExpediente('<?= $numExpediente ?>',<?= $codserie ?>,<?= $tsub ?>,<?= $tdoc ?>,'MODIFICAR');">Crear Nuevo Expediente
					 </a></li>
					<li><a onClick="Responsable('<?= $numExpediente ?>');">Cambiar Responsable</a>
					</li>
					
					<li><a onClick="CambiarE(2,'<?= $numExpediente ?>');">Cerrar Expediente</a>
					</li>
					<li><a onClick="seguridadExp('<?= $numExpediente ?>','<?= $nivelExp ?>');">Seguridad</a>
					</li>
					<li><a onClick="foliado('<?= $numExpediente ?>','<?= $dependencia ?>','<?= $arrTMP['fecha'] ?>','<?= $responsable ?>','<?= $param1 ?>');">Indice electr&oacute;nico</a>
 					</li>
					<!--li>
 				<?php
			
					$url = $servidorPyForms . "seleccion_predios/get_predios_list?expediente=$numExpediente";
					$vars = "&nombreProyecto=$param1($numExpediente)";
				?>
 			<a onClick="reportePredios(&quot;<?= $numExpediente ?>&quot;,&quot;<?= $listPredios ?>&quot;,&quot;<?= $vars ?>&quot;,'modeloPredial');">Reporte Inventario de Predios </a>
			</li -->
			<? if (!empty($numExpediente)) { ?>
			<li><a href="javascript:void(0);"
				onClick="window.open ('expediente/stickerExp/index.php?numExp=<?= $numExpediente ?>','sticker<?= $nurad ?>','menubar=0,resizable=0,scrollbars=0,width=450,height=180,toolbar=0,location=0');"
				class="btn btn-link">Sticker Expediente</a></li>
			<? 
			} 
		}
		?>
</ul>

		</span></td>
		<td><small>
 <?= $num_expediente?>
 <input name="num_expediente" type="hidden" size="30" maxlength="18"
				id='num_expediente' value="<?= $num_expediente ?>" class="tex_area">
				Cod : <span class=leidos2> <? echo $param1; ?>
 Responsable: <span class=leidos2> <?= ucwords(strtolower($responsable))?>
		
		
		
		</small></td>
	 <?php
	}
	
	?>
 <input type="hidden" name='funExpediente' id='funExpediente' value="">
		<input type="hidden" name='menu_ver_tmp' id='menu_ver_tmp' value="4">
 <?php
	// CONSULTA SI EL EXPEDIENTE TIENE UNA CLASIFICACION TRD
	
	$codserie = "";
	$tsub = "";
	include_once ("$ruta_raiz/include/tx/Expediente.php");
	$trdExp = new Expediente ( $db );
	$mrdCodigo = $trdExp->consultaTipoExpediente ( "$numExpediente" );
	$trdExpediente = $trdExp->descSerie . " / " . $trdExp->descSubSerie;
	$descPExpediente = $trdExp->descTipoExp;
	$procAutomatico = $trdExpediente->pAutomatico;
	$codserie = $trdExp->codiSRD;
	$tsub = $trdExp->codiSBRD;
	$tdoc = $trdExp->codigoTipoDoc;
	$texp = $trdExp->codigoTipoExp;
	$descFldExp = $trdExp->descFldExp;
	$codigoFldExp = $trdExp->codigoFldExp;
	if (! $codserie)
		$codserie = 0;
	if (! $tsub)
		$tsub = 0;
	if (! $tdoc)
		$tdoc = 0;
	
	$resultadoExp = 0;
	if ($funExpediente == "INSERT_EXP") {
		$resultadoExp = $expediente->insertar_expediente ( $num_expediente, $verrad, $dependencia, $codusuario, $usua_doc );
		if ($resultadoExp == 1) {
			echo '<hr>Se anex&oacute; este radicado al expediente correctamente.<hr>';
		} else {
			echo '<hr><font color=red>No se anex&oacute; este radicado al expediente. V
				Verifique que el numero del expediente exista e intente de nuevo.</font><hr>';
		}
	}
	
	if ($funExpediente == "CREAR_EXP") {
		$resultadoExp = $expediente->crearExpediente ( $num_expediente, $verrad, $dependencia, $codusuario, $usua_doc );
		if ($resultadoExp == 1) {
			echo '<hr>El expediente se creo correctamente<hr>';
		} else {
			echo '<hr><font color=red>El expediente ya se encuentra creado.
			  <br>A continuaci&oacute;n aparece la lista de documentos pertenecientes al expediente que intento crear
			  <br>Si esta seguro de incluirlo en este expediente haga click sobre el boton  "Grabar en Expediente"
			  </font><hr>';
		}
	}
	// echo "<hr>jjjjj $usuaPermExpediente $num_expediente aa $carpeta";
	// if ($carpeta==8) {
	if ($carpeta == 99998) {
		// <input type="button"0. name="UPDATE_EXP" value="ACTUALIZAR EXPEDIENTE" class="botones_mediano" onClick="Start('buscar_usuario.php?busq_salida=',1024,400);">
	}
	if ($ASOC_EXP and ! $funExpediente) {
		for($ii = 1; $ii < $i; $ii ++) {
			$expediente->num_expediente = "";
			$exp_num = $expediente->consulta_exp ( "$radicados_anexos[$ii]" );
			$exp_num = $expediente->num_expediente;
			
			// echo "===>$exp_num==>".$radicados_anexos[$ii]."<br>";
			if ($exp_num == "") {
				$expediente->insertar_expediente ( $num_expediente, $radicados_anexos [$ii], $dependencia, $codusuario, $usua_doc );
			}
		}
	}
	if (! $codigoFldExp)
		$codigoFldExp = "0";
	$num_expediente = $numExpediente;
	if ($numExpediente != "") {
		if ($expIncluido != "") {
			$arrTRDExp = $expediente->getTRDExp ( $expIncluido, "", "", "" );
		} else if ($num_expediente != "") {
			$arrTRDExp = $expediente->getTRDExp ( $num_expediente, "", "", "" );
		}
	}
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
	 	if(preg_match_all($regex3, $content, $matches))
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

	
	
	
	
	
	<Tr>
		<td><small>Clasificacion D.</small></td>
		<td><small>
                <?php echo ucwords(strtolower($arrTRDExp['serie']))." / ".ucwords(strtolower($arrTRDExp['subserie'])); ?>
                <br>
                 <?php if($usuaPermExpediente > 1){ ?>
                    <button type="submit"
					name='edittemasexp_<?=$num_expediente?>'
					class="btn btn-primary btn-xs" id="editadorapidoexpediente">Editar
					..</button>
					<button type='submit' name='savetemasexp_<?=$num_expediente?>'
					value="<?=$num_expediente?>" id="grabadorapidoexpediente"
					class='btn btn-success  btn-xs'>Grabar ..</button>
                 <?php } ?>
                    <table>
                        <?php
		if ($expIncluido != "") {
			$arrDatosParametro = $expediente->getDatosParamExp ( $expIncluido, $dependencia );
		} else if ($numExpediente != "") {
			$arrDatosParametro = $expediente->getDatosParamExp ( $numExpediente, $dependencia );
		}
		
		if ($arrDatosParametro != "") {
			foreach ( $arrDatosParametro as $clave => $datos ) {
				echo "<tr><td><small><br><b>" . ucwords ( strtolower ( $datos ['etiqueta'] ) ) . " : </b><span class='showfield'>" . ucwords ( strtolower ( htmlentities ( $datos ['parametro'] ) ) ) . "</span></small></td>
                                     <td><input  class='editfield' style='display: none;' type='text' name='etique_" . $numExpediente . "[]'
                                      value='" . ucwords ( strtolower ( htmlentities ( $datos ['parametro'] ) ) ) . "'></td></tr>";
			}
		}
		
		?>
                   </table>
		</small></td>
	</tr>
	<tr>
		<td><span class="dropdown"> <a
				class="btn btn-xs btn-primary dropdown-toggle"
				data-toggle="dropdown" href="javascript:void(0);">
					&nbsp;&nbsp;Procedimiento&nbsp;&nbsp; <b class="caret"></b>
			</a>

				<ul class="dropdown-menu">
       <?  if($usuaPermExpediente) { ?>
        		<li><a onClick="verHistExpediente('<?=$numExpediente?>');">Historial
							del Proceso/Exp</a></li>
					<li><a onClick="verWorkFlow('<?=$numExpediente?>','<?=$texp?>');">Ver
							WorkFlow</a></li>
					<li><a onClick="crearProc('<?=$num_expediente?>');">Adicionar
							Proceso</a></li>
       <?  } ?>
      </ul>

		</span></td>
		<td><small>
	<?php
		if ($arrTRDExp ['proceso'] != "") {
			echo $arrTRDExp ['proceso'] . " / " . $arrTRDExp ['terminoProceso'];
		}
		?></small></td>
	</tr>
 
<?
		$aristasSig = "";
		$frm = "";
		if ($descPExpediente) {
			$expediente->consultaTipoExpediente ( $num_expediente );
			include_once ("$ruta_raiz/include/tx/Flujo.php");
			$objFlujo = new Flujo ( $db, $texp, $usua_doc );
			
			$kk = $objFlujo->getArista ( $texp, $codigoFldExp );
			$aristasSig = $objFlujo->aristasSig;
			$frm = array ();
			$iA = 0;
			$ventana = "Default";
			if ($aristasSig) {
				unset ( $frm );
				$frm = array ();
				$frms = 0;
				foreach ( $aristasSig as $key => $arista ) {
					if (trim ( $arista ["FRM_NOMBRE"] ) && trim ( $arista ["FRM_LINK"] )) {
						$ventana = "Max";
						$vartochange = $aristasSig [$key] ["FRM_LINKSELECT"];
						$frm [$iA] ["FRM_NOMBRE"] = $arista ["FRM_NOMBRE"];
						$vartochange = str_replace ( "{numeroRadicado}", "$numRad", $vartochange );
						$vartochange = str_replace ( "{numeroExpediente}", "$numeroExpediente", $vartochange );
						$vartochange = str_replace ( "{dependencia}", "$dependencia", $vartochange );
						$vartochange = str_replace ( "{documentoUsuario}", "$usua_doc", $vartochange );
						$vartochange = str_replace ( "{usuarioDoc}", "$usua_doc", $vartochange );
						$vartochange = str_replace ( "{nombreUsuario}", "$usua_nomb", $vartochange );
						$frm [$iA] ["FRM_LINK"] = './' . $arista ["FRM_LINK"] . '&' . $vartochange;
						$iA ++;
						$frms = 1;
					}
				} // Fin si hay Aristas....
			}
		}
		?>
  <tr>
		<td><span class="dropdown"> <a
				class="btn btn-xs btn-primary dropdown-toggle"
				data-toggle="dropdown" href="#"> &nbsp;&nbsp;Estado&nbsp;&nbsp; <b
					class="caret"></b>
			</a>
				<ul class="dropdown-menu dropdown-menu-large row">
			<?
		
		if ($usuaPermExpediente) {
			?>
				<li><a onClick="verHistExpediente('<?=$num_expediente?>');"></a></li>
					<li><a onClick="crearProc('<?=$num_expediente?>');">Adicionar
							Proceso</a></li>

					<li><a
						onClick="seguridadExp('<?=$num_expediente?>','<?=$nivelExp?>');">Seguridad</a>
					</li>
					<?
		}
		?>
				<li><a
						onClick="modFlujo('<?=$num_expediente?>',<?=$texp?>,<?=$codigoFldExp?>,'<?=$ventana?>')">Modificar
							Estado</a></li>
				</ul>
		</span></Td>
		<td>
	<?php
		if ($frms == 1) {
			?>
	<span class="dropdown"> <a class="dropdown-toggle"
				data-toggle="dropdown"><small><?=ucwords(strtolower($descFldExp));?></small>
					<b class="caret"></b> </a>
				<ul class="dropdown-menu dropdown-menu-large row">
		<?
			foreach ( $frm as $arista ) {
				?>
			<li><a
						onClick="window.open('<?=$arista["FRM_LINK"]?>','frm<?=date('ymdhis')?>','fullscreen=yes, scrollbars=auto')"><?=trim($arista["FRM_NOMBRE"])?>
				</a></li>  
	<?php
			}
			?>
	 </ul>
		</span>
		</td>
 <?php
		} else {
			?>
	 <small><?=ucwords(strtolower($descFldExp)) ?></small>
		</td><?
		}
		?>

</tr>
	/////

	<tr>
		<td><small> Fecha Inicio </small></td>
		<td><small><?php print $arrTRDExp['fecha']; ?></small></td>
	</tr>
	
</table>




</td>
</tr>
<tr>
	<td colspan="2">

 	<div class="row">
			<div class="col-sm-12 col-md-12">
 <?
	include "$ruta_raiz/expediente/expedienteTree.php";
	?>
	</div>
		</div> <script>
/*$( document ).ready(function() {
$( "#grabadorapidoexpediente" ).hide();
});*/
    $('body').on('click', "button[name^=edittemasexp]", function () {
        $('.showfield').toggle();
        $('.editfield').toggle();
    })

    $('body').on('click', "button[name^='savetemasexp']", function () {
        var complement = $(this).val();
        var datos = $("input[name^='etique_" + complement + "']").serialize() + "&saveEtiq=1";
        $.post( "./expediente/lista_expedientes.php", datos, function( data ) {
            $( ".result" ).html( data );
        });
    })

$( "#grabadorapidoexpediente" ).click(function() {
	
});

</script>
