<?
session_start();
/**
  * Flujos edicion de Aristas
	* Modificaciones y Adaptciones por www.correlibre.org 
  * 
	* Se añadio compatibilidad con variables globales en Off
  * Arreglo de Funcionalidad
  *
  * @autor Jairo Losada 2009-08
  * @licencia GNU/GPL V 3
  */
$ruta_raiz = "../../..";
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;
if ( $tipificacion ) $deshabilitado =  ""; else $deshabilitado =  "disabled=true";


if ( $tipificacion ) $deshabilitado =  ""; else $deshabilitado =  "disabled=true";
//	include "$ruta_raiz/debugger.php";


 include "$ruta_raiz/config.php";
	include_once "$ruta_raiz/include/db/ConnectionHandler.php";
    $db = new ConnectionHandler( "$ruta_raiz" );
    if (!defined('ADODB_FETCH_ASSOC'))define('ADODB_FETCH_ASSOC',2);
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	if( $_GET['proceso'] != '' ){
		$procesoSelected = $_GET['proceso'];
	}elseif ( $_POST['proceso'] != ''){
		$procesoSelected = $_POST['proceso'];
	}
	include_once "$ruta_raiz/include/query/flujos/queryAristas.php";	
	$rsDepMax = $db->conn->Execute( $sqlMax );
	$idEtapas = $rsDepMax->fields['MAXETAPAS'];
?>
<html>
<head>
<title>Modificaci&oacute;n de Conexi&oacute;n</title>
<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>

<script language="JavaScript">
<!--
	function validarDatos()
	{ 
		if(document.frmCrearArista.descripcionArista.value == "")
        {       alert("Debe ingresar la descripcion de la Conexion." );
                document.frmCrearArista.descripcionArista.focus();
                return false;
        }
        
		var minimo = document.frmCrearArista.diasMinimo.value;
		var maximo = document.frmCrearArista.diasMaximo.value;
		var enteroMin = parseInt(minimo);
		var enteroMax = parseInt(maximo);
			
        if( enteroMin < 0 || enteroMin > 999 )
        {       alert("El valor para Dias Minimo debe estar entre 0 y 999" );
                document.frmCrearArista.diasMinimo.focus();
                return false;
        }
        if( enteroMax < 0 || enteroMax > 999 )
        {       alert("El valor para Dias Maximo debe estar entre 0 y 999" );
                document.frmCrearArista.diasMaximo.focus();
                return false;
        }
        
        if( minimo != '' && isNaN(enteroMin) )
        {
               alert( "Solo debe ingresar numeros en dias minimo" );
                document.frmCrearArista.diasMinimo.focus();
                return false;
        }

        if( maximo != '' && isNaN(enteroMax) )
        {
               alert( "Solo debe ingresar numeros en dias maximo" );
                document.frmCrearArista.diasMaxnimo.focus();
                return false;
        }
        
        var tipifica = document.frmCrearArista.tipificacion.checked;
        var serieDoc = document.frmCrearArista.codserie.value;
        var subserieDoc = document.frmCrearArista.tsub.value;
        var tipoDoc = document.frmCrearArista.tipo.value;
        
        if( tipifica  && ( serieDoc == 0 || subserieDoc == 0 || tipoDoc == 0 ))
        {       alert("Si selecciona tipificacion, debe seleccionar la serie, subserie y tipo documental." );
                
                return false;
        }
        
        <?  $clickBoton = true; ?>
	 	document.form1.submit();
	}
		
function Start(URL, WIDTH, HEIGHT)
{
 windowprops = "top=0,left=0,location=no,status=no, menubar=no,scrollbars=yes, resizable=yes,width=700,height=500";
 preview = window.open(URL , "preview", windowprops);
}

function cerrar(){
	window.opener.regresar();
	window.close();
}

function regresar(){
	f_close();
}
	
//-->
</script>



</head>
<body >
<?
//	include "$ruta_raiz/debugger.php";

	if( $_GET['proceso'] != '' ){

		$procesoSelected = $_GET['proceso'];
	}
	if( $_GET['aristaAModificar'] != '' ){
		$aristaAModificar = $_GET['aristaAModificar'];
		$queryModifica = "SELECT * FROM SGD_FARS_FARISTAS WHERE SGD_FARS_CODIGO = " .$aristaAModificar;
		$rs = $db->conn->query( $queryModifica );
		$descripcionArista = $rs->fields['SGD_FARS_DESC'];
		$frmNombre = $rs->fields['SGD_FARS_FRMNOMBRE'];
		$frmUrl = $rs->fields['SGD_FARS_FRMLINK'];
		$diasMinimo = $rs->fields['SGD_FARS_DIASMINIMO'];
		$diasMaximo = $rs->fields['SGD_FARS_DIASMAXIMO'];
		$automatico = ( $rs->fields['SGD_FARS_AUTOMATICO'] == 1 ? "automatico" : null );
		$tipificacion = ( $rs->fields['SGD_FARS_TIPIFICACION'] == 1 ? "tipificacion" : null );
		$etapaInicioID =  $rs->fields['SGD_FEXP_CODIGOINI'];
		$etapaFinID =  $rs->fields['SGD_FEXP_CODIGOFIN'];
		$trad =  $rs->fields['SGD_TRAD_CODIGO'];
		
		$codserie = $rs->fields['SGD_SRD_CODIGO'];
		$tsub = $rs->fields['SGD_SBRD_CODIGO'];
		$tipo = $rs->fields['SGD_TPR_CODIGO'];
		if ( $tipificacion ) $deshabilitado =  ""; else $deshabilitado =  "disabled=true";


	}elseif ($_POST['aristaAModificar'] != ''){
		//$etapaInicioID = $_POST['etapaInicial'];
		//$etapaFinID = $_POST['etapaFinal'];
	}
	
			if($_POST['etapaInicial']) $etapaInicioID = $_POST['etapaInicial'];
		if($_POST['etapaFinal']) $etapaFinID = $_POST['etapaFinal'];

	if( ( $_POST['descripcionArista'] != '' &&  $_POST['codserie'] != null &&  $_POST['tsub'] != null &&  $_POST['tipo'] != null  &&  $_POST['codserie'] != 0 &&  $_POST['tsub'] != 0 &&  $_POST['tipo'] != 0 &&  $_POST['tipificacion'] != null &&  $_POST['tipificacion'] != ''  && $ClickModifica == 'Modificar') 	
	||  ( $_POST['descripcionArista'] != '' &&  $_POST['codserie'] == 0 &&  $_POST['tsub'] == 0 &&  $_POST['tipo'] == 0 &&  $_POST['tipificacion'] == null  && $ClickModifica == 'Modificar')
			 ){
			include "$ruta_raiz/include/tx/Proceso.php";
	 		$flujo = new AristaFlujo( $db );

	 		$flujo->initArista( $etapaInicial, $etapaFinal, $descripcionArista, $diasMinimo, $diasMaximo, $trad,$codserie,$tsub, $tipo, $procesoSelected, $_POST['automatico'], $tipificacion ,$frmUrl,$frmNombre );	
			$resultadoInsercion = $flujo-> modificaArista( $aristaAModificar );
	}
?>
<form name='frmCrearArista' action='modificaArista.php?proceso=<?=$procesoSelected?>' method="post" class="smart-form">
<table width="93%"  class="table table-bordered" align="center">
  	<tr bordercolor="#FFFFFF">
    <td colspan="2" class="titulos4">
	<center>
	<p><B><span class=etexto>ADMINISTRACI&Oacute;N DE FLUJOS</span></B> </p>
	<p><B><span class=etexto> Modificar Conexi&oacute;n </span></B></p></center>
	</td>
	</tr>
</table>

<table  width=93% class="table table-bordered" align="center">
	<tr class=timparr>
			<td  height="26">Etapa Inicial</td>
			<td  height="1"><label class="select state-success">
				<?
				$rsDep = $db->conn->Execute( $sql );
				print $rsDep->GetMenu2( "etapaInicial", $etapaInicioID, false, false, 0," class='select'" );
				?>
		</select></td>
	</tr>
	<tr class=timparr>
			<td  height="26">Etapa Final</td>
			<td  height="1"><label class="select state-success">
				<?
				$rsDep = $db->conn->Execute( $sql );
				print $rsDep->GetMenu2( "etapaFinal", $etapaFinID, false, false, 0," class='select'" );
				?></select>
		</td>
	</tr>
</table>
<table class="table table-bordered" width=93%  align="center">
  <tr>
  <td height="23" align="left" colspan="1"  width="25%">
  Descripci&oacute;n:
          </td>
          <td height="23" colspan="3"  width="75%"><label class="input state-success">
          <input type="text" name="descripcionArista"  id="descripcionArista" value="<?=$descripcionArista?>"  size=80 lenght=80 ></label>
  </td>
  </tr>
    <tr>
  <td height="23" align="left" colspan="1"  width="25%">
  Url / Formulario 
          </td>
          <td height="23" colspan="3"  width="75%"><label class="input state-success">
          <input type="text" name="frmNombre"  id="frmNombre" value="<?=$frmNombre?>"  size=80 lenght=100  class="select state-success"></label>
    </td></tr>
    <tr><td>Nombre Formulario/Link</td><td>
          <label class="input state-success"><input type="text" name="frmUrl"  id="frmUrl" value="<?=$frmUrl?>"  size=80 lenght=100 class="select state-success" ></label>
  </td>
  </tr>
        </td>
  </tr>
</table>
<table border=1 width=93% class="table table-bordered" align="center">
	   <tr>
	        <td height="23" colspan="4"  width="25%">
	        	D&iacute;as M&iacute;nimo:
	        </td>
	        <td height="23" colspan="4"  width="25%">
	        	<label class="input state-success"><input type="text" name="diasMinimo" value="<?=$diasMinimo?>" size="15" lenght="3" ></label>
	        </td>
	        <td height="23" colspan="4"  width="25%">
	        	D&iacute;as M&aacute;ximo:
	        </td>
	        <td height="23" colspan="4"  width="25%">
	        	<label class="input state-success"><input type="text" name="diasMaximo" value="<?=$diasMaximo?>" size="15" lenght="3"></label>
	        </td>
        </tr>
        <tr>
            <td height="23" colspan="4"  width="25%">
            	Tipo de Radicado:
            </td>
            <td height="23" colspan="4"  width="25%"><label class="select state-success">
            	<?
			 	include_once "$ruta_raiz/include/query/flujos/queryTiposDoc.php";									
				$rsDep = $db->conn->Execute( $sql );
				print $rsDep->GetMenu2( "trad", $trad, ("0:-- Ninguno --"), false, ""," class='select'" );
				?>
            </label></td>
            <td height="23" colspan="4"  width="25%">
            	Autom&aacute;tico:
            </td>
            <td height="23" colspan="4"  width="25%">
             	   <input type="checkbox" name="automatico" value="$automatic" <? if ($automatico) echo "checked"; else echo "";?> >
            </td>
        </tr>
        <tr>
        	<td height="23" colspan="4"  width="25%">
            	Tipificaci&oacute;n:
            </td>
            <td height="23" colspan="4"  width="25%">
                <input type="checkbox" name="tipificacion" value="$tipificacion" <? if ($tipificacion) echo "checked"; else echo "";?> onchange="submit();">
            </td>
            <td height="23" colspan="4"  width="25%">
            	
            </td>
            <td height="23" colspan="4"  width="25%">
            	
            </td>
        </tr>
</table>

 <table width="93%" align="center" border="0" cellpadding="0" cellspacing="5" class="borde_tab" >
	<tr align="center">
		<td height="35" colspan="2" class="titulos4">Aplicaci&Oacute;n de la TRD para la Conexi&oacute;n</td>
	</tr>
	<tr align="center">
		<td width="36%" >SERIE</td>
		<td width="64%" height="35" ><label class="select state-success">
		<?php
    include "$ruta_raiz/trd/actu_matritrd.php";  
    if(!$codserie) $codserie = 0;
	$fechah=date("dmy") . " ". date("h_m_s");
	$fecha_hoy = Date("Y-m-d");
	$sqlFechaHoy=$db->conn->DBDate($fecha_hoy);
	$check=1;
	$fechaf=date("dmy") . "_" . date("hms");
	$num_car = 4;
	$nomb_varc = "sgd_srd_codigo";
	$nomb_varde = "sgd_srd_descrip";
   	include "$ruta_raiz/include/query/trd/queryCodiDetalle.php";
	$querySerie = "select distinct ($sqlConcat) as detalle, sgd_srd_codigo 
	         from sgd_srd_seriesrd 
			 order by detalle
			  ";
	$rsD=$db->conn->query($querySerie);
	$comentarioDev = "Muestra las Series Docuementales";
	include "$ruta_raiz/include/tx/ComentarioTx.php";
	
	print $rsD->GetMenu2("codserie", $codserie, "0:-- Seleccione --", false,"","onChange='submit()' class='select' " . $deshabilitado );
		?>
				</label></td>
		 	<tr align="center">
				<td width="36%" >SUBSERIE</td>
				<td width="64%" height="35" ><label class="select state-success">
				<?
	$nomb_varc = "sgd_sbrd_codigo";
	$nomb_varde = "sgd_sbrd_descrip";
	include "$ruta_raiz/include/query/trd/queryCodiDetalle.php"; 
   	$querySub = "select distinct ($sqlConcat) as detalle, sgd_sbrd_codigo 
	         from sgd_sbrd_subserierd 
			 where sgd_srd_codigo = '$codserie'
 			       and ".$sqlFechaHoy." between sgd_sbrd_fechini and sgd_sbrd_fechfin
			 order by detalle
			  ";
	$rsSub=$db->conn->query($querySub);
	include "$ruta_raiz/include/tx/ComentarioTx.php";
			print $rsSub->GetMenu2("tsub", $tsub, "0:-- Seleccione --", false,"","onChange='submit()' class='select' " . $deshabilitado );
		?>
				</label></td>
			</tr>
		  	<tr align="center">
				<td width="36%" >TIPO DE DOCUMENTO</td>
				<td width="64%" height="35" ><label class="select state-success">
		<?
			$ent = 1;
			$nomb_varc = "t.sgd_tpr_codigo";
			$nomb_varde = "t.sgd_tpr_descrip";
			include "$ruta_raiz/include/query/trd/queryCodiDetalle.php";
			$queryTip = "select distinct ($sqlConcat) as detalle, t.sgd_tpr_codigo
				         from sgd_mrd_matrird m, sgd_tpr_tpdcumento t
						 where 
			 			       m.sgd_srd_codigo = '$codserie'
						       and m.sgd_sbrd_codigo = '$tsub'
			 			       and t.sgd_tpr_codigo = m.sgd_tpr_codigo
							   and t.sgd_tpr_tp$ent='1'
						 order by detalle";
			$rsTip=$db->conn->query($queryTip);
			include "$ruta_raiz/include/tx/ComentarioTx.php";
			print $rsTip->GetMenu2("tipo", $tipo, "0:-- Seleccione --", false,""," class='select' " . $deshabilitado);
		?>
		</label></td>
	</tr>
</table>

<input name='proceso' type='hidden' value='<?=$procesoSelected?>'>
<input type=hidden id="aristaAModificar" name="aristaAModificar" value='<?=$aristaAModificar?>'>

<table  width=93% class="table table-bordered" align="center">
	<tr >
	      <td height="30" colspan="2" >
		  <footer> <input class="btn btn-success" type="submit" Value="Modificar"  onClick=" return validarDatos();" name="ClickModifica"> 
			<input class="btn" type=button name=Cerrar id=Cerrar Value=Cerrar onclick='cerrar();'></footer>
		  </td>
	</tr>
</table>
<?
if ( ( $_POST['descripcionArista'] != '' &&  $_POST['codserie'] != null &&  $_POST['tsub'] != null &&  $_POST['tipo'] != null  &&  $_POST['codserie'] != 0 &&  $_POST['tsub'] != 0 &&  $_POST['tipo'] != 0 &&  $_POST['tipificacion'] != null &&  $_POST['tipificacion'] != ''  && $ClickModifica == 'Modificar') 	
	||  ( $_POST['descripcionArista'] != '' &&  $_POST['codserie'] == 0 &&  $_POST['tsub'] == 0 &&  $_POST['tipo'] == 0 &&  $_POST['tipificacion'] == null  && $ClickModifica == 'Modificar')
			  ) {
  ?>
		<center>
			<table class="table table-bordered">
				<tr>
					<td class=titulosError>
					   <?=$resultadoInsercion?>
					</td>
				</tr>
			</table>
		</center>
<?
	}
?>
</form>
</body>
</html>