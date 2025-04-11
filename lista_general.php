<?php
/**
* @author Jairo Losada   <jlosada@gmail.com>
* @author Cesar Gonzalez <aurigadl@gmail.com>
* @license  GNU AFFERO GENERAL PUBLIC LICENSE
* @copyright

SIIM2 Models are the data definition of SIIM2 Information System
Copyright (C) 2013 Infometrika Ltda.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
session_start();

    $ruta_raiz = "."; 
    if (!$_SESSION['dependencia'])
        header ("Location: $ruta_raiz/cerrar_session.php");

$lkGenerico = "&usuario=$krd&nsesion=".trim(session_id())."&nro=$verradicado"."$datos_envio";
?>
<script>
function regresar()	
{	//window.history.go(0);
	window.location.reload();
  //window.location.href='<a href="#" ></a>'
}
function CambiarE(est,numeroExpediente) {
        window.open("<?=$ruta_raiz?>/archivo/cambiar.php?<?=session_name()?>=<?=session_id()?>&numRad=<?=$verrad?>&expediente="+ numeroExpediente +"&est="+ est +"&","Cambio Estado Expediente","height=100,width=100,scrollbars=yes");
}

//foliado electrónico
function foliado(numeroExpediente,dependencia, fecha, responsable, nombre) {
        window.open("<?=$ruta_raiz?>/archivo/foliado.php?<?=session_name()?>=<?=session_id()?>&numRad=<?=$verrad?>&expediente="+ numeroExpediente + "&dependencia="+ dependencia + "&fecha="+ fecha + "&responsable="+ responsable + "&nombre="+ nombre + "&","Cambio Estado Expediente","height=500,width=1000,scrollbars=yes");
}
function modFlujo(numeroExpediente,texp,codigoFldExp)
{
<?php

if(empty($codigoFldExp))
	$codigoFldExp =0;

#SQL QUE TRAE EL NOMBRE DEL REMITENTE
$sqlremitente = "select SGD_DIR_NOMBRE, SGD_DIR_NOMREMDES from SGD_DIR_DRECCIONES t where t.radi_nume_radi = '$numrad'";
$rsRemitente = $db->conn->Execute($sqlremitente);
$SGD_DIR_NOMBRE = $rsRemitente->fields['SGD_DIR_NOMREMDES'];


        $isqlDepR = "SELECT RADI_DEPE_ACTU,RADI_USUA_ACTU from radicado
                                                        WHERE RADI_NUME_RADI = '$numrad'";
        $rsDepR = $db->conn->Execute($isqlDepR);
        $coddepe = $rsDepR->fields['RADI_DEPE_ACTU'];
        $codusua = $rsDepR->fields['RADI_USUA_ACTU'];
        $ind_ProcAnex="N";
?>
window.open("<?=$ruta_raiz?>/flujo/modFlujoExp.php?<?=session_name()?>=<?=session_id()?>&codigoFldExp="+codigoFldExp+"&numeroExpediente="+numeroExpediente+"&numRad=<?=$verrad?>&texp="+texp+"&ind_ProcAnex=<?=$ind_ProcAnex?>&codusua=<?=$codusua?>","TexpE<?=$fechaH?>","height=250,width=750,scrollbars=yes");
}

function verVinculoDocto(){
    window.open("./vinculacion/mod_vinculacion.php?verrad=<?=$verrad?>&codusuario=<?=$codusuario?>&dependencia=<?=$dependencia?>","Vinculacion_Documento","height=500,width=750,scrollbars=yes");
}
function update_cExp(){
	$.post("<?=$ruta_raiz?>/include/tx/comiteExpertos.php",{numRad: <?=$numrad?>});
}
function update_cNiega(){
	$.post("<?=$ruta_raiz?>/include/tx/seNiega.php",{numRad: <?=$numrad?>});
}
</script>
<table border=0 cellspace=0 colspacing=0 cellspacing=0>
<tr  class=odd>
 <td><small><b>Asunto</b></small></td><td><small><?=$ra_asun ?></small></td>
<td><small><b>Fecha </b></small></td><td><small> <?=$radi_fech_radi ?> &nbsp;&nbsp;</small></td>
</tr>
<tr  cellspace=0 cellpad=0>
<td><small><b>  Paginas</b></small></td><td><small><?=$radi_nume_folio?>/<?=$radi_nume_hoja ?> </small></td><td><small><b>   Anexos:</b></small></td><td><small> <?=$radi_nume_anexo?></small></td>

<?/*Desarollo especifico para la CRA*/
if ($entidad=="CRA"){
?>
<td><small><b>Ver usuarios informados:</b></small></td><td><small><input type=button id=mostrarInformados name=mostrarInformados value='...' class="btn btn-primary btn-xs"></small></td>
		
</tr>
<!-- ui-dialog -->
<div id="dialog-message" title="Hisrtorico de Informados">
<iframe width="100%" height="100%" src="radicacion/histor_infor.php?verrad=<?=$verrad?>"></iframe>


	 <div class="hr hr-12 hr-double"></div>
</div><!-- #dialog-message -->
<script type="text/javascript">
	pageSetUp();
	var pagefunction = function() {
		/*
		 * CONVERT DIALOG TITLE TO HTML
		 * REF: http://stackoverflow.com/questions/14488774/using-html-in-a-dialogs-title-in-jquery-ui-1-10
		 */
		$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
			_title : function(title) {
				if (!this.options.title) {
					title.html("&#160;");
				} else {
					title.html(this.options.title);
				}
			}
		}));
	
		/*
		* DIALOG HEADER ICON
		*/
	
		// Modal Link
		$('#mostrarInformados').click(function() {
			$('#dialog-message').dialog('open');
			return false;
		});
	
		$("#dialog-message").dialog({
			autoOpen : false,
			modal : true,
			width : "800",
			height : "400",
			title : "<div class='widget-header'><h4><i class='icon-ok'></i> Usuarios informados </h4></div>",
			buttons : [{
				html : "Cancel",
				"class" : "btn btn-default",
				click : function() {
					$(this).dialog("close");
				}
			}, {
				html : "<i class='fa fa-check'></i>&nbsp; OK",
				"class" : "btn btn-primary",
				click : function() {
					$(this).dialog("close");
				}
			}]
	
		});
	
	};
	
	// end pagefunction
	
	// run pagefunction on load

	pagefunction();

</script>
<?}
/**********************************/
?>

<tr>
<td><small><b>
Descripci&oacute;n Anexos</b></small></td><td><small> <?=$radi_desc_anex ?></small></td><td><small><b> Anexo/Asociado</b></small></td><td><small>
	<?	
	if($radi_tipo_deri!=1 and $radi_nume_deri)
	   {	echo $radi_nume_deri;
           	 /*
		  * Modificacion acceso a documentos
		  * @author Liliana Gomez Velasquez
		  * @since 10 noviembre 2009
		 */
		 $resulVali = $verLinkArchivo->valPermisoRadi($radi_nume_deri);
                 $verImg = $resulVali['verImg'];
		 if ($verImg == "SI"){
		        echo "<br>(<a class='vinculos' href='$ruta_raiz/verradicado.php?verrad=$radi_nume_deri &session_name()=session_id()&krd=$krd' target='VERRAD$radi_nume_deri_".date("Ymdhi")."'>Ver Datos</a>)";}	
                 else {
                      echo "<br>(<a class='vinculos' href='javascript:noPermiso()'> Ver Datos</a>)"; 
                 }
	   }
	 if($verradPermisos == "Full" or $datoVer=="985")
		{
	?>
		<input type=button name=mostrar_anexo value='...'  class="btn btn-primary btn-xs" onClick="verVinculoDocto();">
	<?
		}
	?>
</small></td><td><small><b>Referencia / Oficio</b></small></td><td><small><?=$cuentai ?></small></td>
</tr>

    <?
		$muniCodiFac = "";
		$dptoCodiFac = "";
		if($sector_grb==6 and $cuentai and $espcodi)
		{	if($muni_us2 and $codep_us2)
			{	$muniCodiFac = $muni_us2;
				$dptoCodiFac = $codep_us2;
			}
			else
			{	if($muni_us1 and $codep_us1)
				{	$muniCodiFac = $muni_us1;
					$dptoCodiFac = $codep_us1;
				}
			}
	?>
		<a href="./consultaSUI/facturacionSUI.php?cuentai=<?=$cuentai?>&muniCodi=<?=$muniCodiFac?>&deptoCodi=<?=$dptoCodiFac?>&espCodi=<?=$espcodi?>" target="FacSUI<?=$cuentai?>"><span class="vinculos">Ver Facturacion</span></a>
	<?
		}
	?>
<tr><td><small><b>Imagen</b></small></td><td><small>	<span class='vinculos'><?=$imagenv ?></span> </small></td><td><small><b>Estado Actual</b></small></td><td><small>
		<span ><?=$descFldExp?></span>&nbsp;&nbsp;&nbsp;
		<? 
			if($verradPermisos == "Full" or $datoVer=="985")
	  		{
	  	?>
  <input type=button name=mostrar_causal value='...' class="btn btn-primary btn-xs" onClick="modFlujo('<?=$numExpediente?>',<?=$texp?>,<?=$codigoFldExp?>)">
		<?
			}
		?>
	</Td><td><small><b>
	Nivel de Seguridad</b></small></td><td><small>
	<?
		if($nivelRad==1)
		{	echo "Confidencial";	}
		else 
		{	echo "P&uacute;blico";	}
		if($verradPermisos == "Full" or $datoVer=="985")
	  	{	$varEnvio = "krd=$krd&numRad=$verrad&nivelRad=$nivelRad";
	?>
		<input type=button name=mostrar_causal value='...' class="btn btn-primary btn-xs" onClick="window.open('<?=$ruta_raiz?>/seguridad/radicado.php?<?=$varEnvio?>','Cambio Nivel de Seguridad Radicado', 'height=220, width=300,left=350,top=300')">
	<?
		}
	?>
	</small></td><tr>
	<tr>
	<th>Clasificaci&oacute;n Documental</th><td><small>
	<?
		if(!$codserie) $codserie = "0";
		if(!$tsub) $tsub = "0";
		if(trim($val_tpdoc_grbTRD)=="///") $val_tpdoc_grbTRD = "";
	?>
		<?=$serie_nombre ?><font color=black>/</font><?=$subserie_nombre ?><font color=black>/</font><?=$tpdoc_nombreTRD ?>
	<?
	$enableComite=false;
		if(($verradPermisos == "Full" or $datoVer=="985") and substr($numrad, -1)<>2 ){
		    $enableComite=true;
	?>
		<input type=button name=mosrtar_tipo_doc2 title="Asigne una TRD a su documento." value='...' class="btn btn-primary btn-xs" onClick="ver_tipodocuTRD(<?=$codserie?>,<?=$tsub?>);">
		<?php }?>
	</small></td>
	<?$termino=$db->conn->Execute("select SGD_TPR_TERMINO from sgd_tpr_tpdcumento tp, radicado r where tp.SGD_TPR_CODIGO=r.TDOC_CODI and r.radi_nume_radi=$verrad");
	$termino=$termino->fields["SGD_TPR_TERMINO"]?>
	<th>Término: </th><td><small><?=$termino?></small></td>
<?
if ($db->entidad=="CRA"){
    $enable=!$enableComite?"disabled='disabled'":"";
    $veriCheckedSQL="select COMITE_EXPERTOS from RADICADO where radi_nume_radi=$numrad";
	$_veriCheckedSQL=$db->conn->Execute($veriCheckedSQL);
	if ($_veriCheckedSQL->fields["COMITE_EXPERTOS"]=="1")
		$checked="checked";
	    
	echo '<th>Comité de Expertos</th><td><small><input type="checkbox" '.$enable.' name="cExp" id="cExp" '.$checked.'  onclick="update_cExp()"></small></td>';
}
?>


<!--< AGREGADO NEMESIS 03/11/2015  ANEXOS TECNICOS>-->

	<th> Anexos Tecnicos:</th><td>
	<?
	$sqlt1="select path_ccu from hist_eventos where hist_fech in(
            select max(hist_fech) from hist_eventos h2
            where h2.radi_nume_Radi='$verrad' and h2.sgd_ttr_codigo=72) and sgd_ttr_codigo=72";
		//$db->conn->debug=true;
		$rst1=$db->query($sqlt1); 
		$termino1=$rst1->fields["PATH_CCU"];		
		//echo "la variable es $termino1";
		
		if(empty($termino1)){
		
		// echo "la variable $var1";
		 ?> 
		  <td >Sin Anexos</a></td>
        <?
		}
		else
		{
		 $var1='/orfeo/bodega/files'.$termino1;
		?> 
		<td><a href=<?=$var1?>  >Anexo CCU</a></td>
		<?
		}
		?> 
 </td>





      <?
	  if ($verradPermisos == "Full"  or $datoVer=="985" ) {
	  ?>
      	<!--<input type=button name="mostrar_causal" value="..." class="btn btn-primary btn-xs" onClick="window.open(<?=$datosEnviar?>,'Tipificacion_Documento','height=300,width=750,scrollbars=no')">-->
      <?
	  }

	   $checkedSQL="select SENIEGA from RADICADO where radi_nume_radi=$numrad";
	   $_checkedSQL=$db->conn->Execute($checkedSQL);
		if ($_checkedSQL->fields["SENIEGA"]=="1")
			$checkedcNiega="checked";
		    
	  ?>
	  </small></td></tr>
	  	<th>Se niega acceso a información</th><td><small><input type="checkbox" name="cNiega" id="cNiega" <?php echo ($checkedcNiega) ?>  onclick="update_cNiega()"></small></td>
	  </table>
</form>
<table width="80%" class="table table-bordered ">
<tr>
 <th  class='alert alert-info'>Nombre</th>
 <th  class='alert alert-info'>Direccion</th>
 <th  class='alert alert-info'>Ciudad / Departamento</th>
 <th  class='alert alert-info'>Mail</th>
 <th  class='alert alert-info'>Telefono</th>
</tr>
<tr> 
 <td><? /* echo $SGD_DIR_NOMBRE;*/ echo $nomRemDes["x1"] ?> </small></td>
 <td><?=$dirDireccion["x1"] ?></small></td>
 <td><?=$dirMuni["x1"]."/".$dirDpto["x1"]; ?></small></td>
 <td><?=$dirEmail["x1"] ?> </small></td>
 <td><?=$dirTel["x1"] ?> </small></td>
</tr>
<tr> 
 <td><?=$nomRemDes["x2"]?></small></td>
 <td><?=$dirDireccion["x2"] ?></small></td>
 <td><?=$dirDpto["x2"]."/".$dirMuni["x2"] ?></small></td>
 <td><?=$dirEmail["x2"] ?> </small></td>
 <td><?=$dirTel["x2"] ?> </small></td>
</tr>
<tr>
  <td> <?=$nombret_us3 ?> -- <?=$cc_documento_us3?></small></td>
  <td> <?=$direccion_us3 ?></small></td>
  <td> <?=$dpto_nombre_us3."/".$muni_nombre_us3 ?></small></td>
  <td><?=$email["x3"] ?> </small></td>
  <td><?=$telefono["x3"] ?> </small></td>

</tr>
</table>
