<!-- NEW WIDGET START -->
<article class="col-sm-12 col-md-12" align=left>
<!--widget content -->
<div class="widget-body">
<div class="tree smart-form fa-folder-open">
	<ul>
	<li>
		<span class="alert-success" controlexp="<?=$numExpediente?>"><i class="fa fa-folder-open"></i> <?=$numExpediente?></span>
		<ul id="<?=$numExpediente?>">
		</ul>

		<?php 
		$totalRadicados = $expediente->totalRadicados($numExpediente);
		echo '<div><button id="btn'.$numExpediente.'" style="display:none" title="cargarMas"  class=" btn btn-primary btn-xs" onclick="cargarExpedienteAdi(\''.$numExpediente.'\')" controltotal="'.$totalRadicados.'" controlpage="1"><span class="fa fa-search">cargar mas radicados</span></button></div>';
?>
    </li>
</ul>
</div>
</div> 
</article>
<?php 

               
	   $sExp = array();	
	
	//	foreach($datosExp as $key2 => $value){

		?>
		<li  style="display:none">
			<span>
			<?
			       
			        $sExp = $value["SEXPEDIENTES"];

				
				if($sExp){
				$SExps = "<table><TR><TD COLSPAN=5></small><b>Expedientes Adicionales :</b></small><TD></TR>";
				foreach($sExp as $valueExpedientes){
					$SExps .= "<tr><td><small>".$valueExpedientes["NUMERO"]."&nbsp; </small> </td><td> <small>".$valueExpedientes["PARAM1"]."&nbsp; </small></td><td><small>".$valueExpedientes["PARAM2"]."</small></td></tr>";
				}
				$SExps .= "</table>";
				}
			$numRadicado = $value["NUM_RADICADO"];
			$fechaRad = "<a href='verradicado.php?verrad=$numRadicado&".session_name()."=".session_id()."&nomcarpeta=".$nomcarpeta."#tabs-a'>".$value["FECHA_RADICADO"] . "</a>";
			echo "<TABLE  WIDTH='1050'><TR><TD WIDTH=30><i class='icon-leaf'></i> </TD>
			<TD width=140 align=left>";
			if($valueAnexos["PATH_RADICADO"]) echo "<a href='$ruta_raiz/".$value["PATH_RADICADO"]."' target='".date("ymdhis")."'>";
			echo $numRadicado;
			if($valueAnexos["PATH_RADICADO"]) echo "</a>" ;
			echo "</TD>
			<TD width=140>$fechaRad</TD><TD width=120>".$value["TIPO_DRADICADO"]."</TD><TD width=450>".$value["ASUNTO_RADICADO"]."</TD>
			<TD width=350>$SExps</TD></TR>
			</TABLE>"; ?></span>

			<ul>
		<?
				$carpetaDep = intval(substr($value["NUM_RADICADO"],4,$digitosDependencia));
				$rutaAnexos = "".substr($value["NUM_RADICADO"],0,4). "/$carpetaDep/docs/";
				$anexos = $value["ANEXOS"];
				if($anexos){
				echo "<hr>--->>>>***";
				var_dump($anexos);
				foreach($anexos as $valueAnexos){
			?>
			<li style="display:none">
				<?=$valueAnexos["ANEX_NUMERO"]?> - <?=$valueAnexos["RADI_SALIDA"]?>
				<? if($valueAnexos["ANEX_PATH"]) {?><a href='<?=$ruta_raiz."/bodega/$rutaAnexos".$valueAnexos["ANEX_PATH"]?>' target='<?=date("ymdhis")?>'> <? } ?>
				- <?=$valueAnexos["DESCRIPCION"]?>
				<? if($valueAnexos["ANEX_PATH"]) {?> </a> <? } ?>
			</li>
			<?
				}
			}
			?>
			</ul>
	</li>
	<?
	//}
	if(!empty( $servidorPyForms)){		
		$url = $servidorPyForms."seleccion_predios/get_predios_list?expediente=$numExpediente";
		//$predios2 = file_get_contents($url);
		//$predios = str_replace('"',"'",$predios2);
		//$arrPredios = json_decode($predios2);
		if(is_array($arrPredios)){
			?>
			<li  style="display:none">
				<span  class="alert-success">
				<? echo "Predios"; ?></span>
				<ul>
				<?
					foreach($arrPredios as $key => $valor){
					  $isqlPredio = "SELECT *
							FROM LOTE4686
							WHERE chip = '".$valor->chip."' ";
					    $rsPredio = $db->conn->Execute($isqlPredio);
					    if($rsPredio){
					      $propietarios = $rsPredio->fields["PROPIETARIOS_ACTUALES"];
					      $areaLeng = $rsPredio->fields["SHAPE_LENG"];
					      $area = $rsPredio->fields["SHAPE_AREA"];
					      $direccion = $rsPredio->fields["FUENTE_DIRECCION"];
					      $matricula = $rsPredio->fields["MATRICULA_INMOBILIARIA"];
					      $avaluoCatastral = $rsPredio->fields["AVALUO_CATASTRAL_TERRENO"];
					      $avaluoComercial = $rsPredio->fields["AVALUO_COMERCIAL"];
					    
					    }
					  $linkFichaPredial ="reportePredios('$numeroExpediente','".$valor->chip."','','fichaPrejuridica');";
					?>
				<li style="display:none">
				  <a href="verradicado.php?verrad=<?=$verrad?>&<?=session_name()?>=<?=session_id()?>&nomcarpeta=<?=nomcarpeta?>&prediosExp=<?=$predios?>#tabs-gis">Gis</a>
					<a href="#" onClick="<?=$linkFichaPredial?>" ><?="Chip : ".$valor->chip; ?> </a> - <?="Propietarios : ".$propietarios ?> - <?="Direccion : ".$direccion ?>  - <?="Matricula : ".$matricula ?>
					</a>
				</li>	
				<?
					}
				?>
			</ul>
		</li>
		<? 
		}
		$iSql = "select *, se.ID ID_EXPEDIENTE
						from modelourbanistico mu, sgd_sexp_secexpedientes se
						where mu.expediente_id = se.id and se.sgd_exp_numero like '$numExpediente'";
		//$db->conn->debug = true;
		$rsMU = $db->conn->Execute($iSql);
		if(!$rsMU->EOF){
		 $nombreReporte = "modeloUrbanistico";
		 $nuId = $rsMU->fields["ID_EXPEDIENTE"];
		 $vars = "&id_expediente=". $nuId."&";
			?>
			<li  style="display:none">
				<span  class="alert-success">
				
				<a  onClick="reportePredios(&quot;<?=$numExpediente?>&quot;,&quot;x&quot;,&quot;<?=$vars?>&quot;,&quot;<?=$nombreReporte?>&quot;);">Modelo Urbanistico </a></span>
				<ul>
			</ul>
		</li>
		<? }
	} ?>
		</ul>
	</div>
</div>
		<!-- end widget content -->
</article>
	<!-- WIDGET END -->
	

