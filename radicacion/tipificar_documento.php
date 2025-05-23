<?php
session_start();
$ruta_raiz = ".."; 
if (!$_SESSION['dependencia'])
   header ("Location: $ruta_raiz/cerrar_session.php");

/* ORFEO GPL:Sistema de Gestion Documental		http://www.orfeogpl.org	     */
/*	Idea Original de la SUPERINTENDENCIA DE SERVICIOS PUBLICOS DOMICILIARIOS     */
/*				COLOMBIA TEL. (57) (1) 6913005  orfeogpl@gmail.com   */
/* ===========================                                                       */
/*                                                                                   */
/* Este programa es software libre. usted puede redistribuirlo y/o modificarlo       */
/* bajo los terminos de la licencia GNU General Public publicada por                 */
/* la "Free Software Foundation"; Licencia version 3. 			             */
/*                                                                                   */
/* Copyleft  2008 por :	  	  	                                     */
/* SSPd "Superintendencia de Servicios Publicos Domiciliarios"                       */
/*   Jairo Hernan Losada  jlosada@gmail.com                Desarrollador             */
/*   Liliana Gomez        lgomezv@gmail.com                Desarrolladora            */
/*   */
/*   EMPRESA IYU                                             */
/*   Hollman Ladino       hladino@gmail.com                Desarrollador             */
/*                                                                                   */
/* Colocar desde esta lInea las Modificaciones Realizadas Luego de la Version 3.5    */
/*  Nombre Desarrollador   Correo     Fecha   Modificacion                           */
/*  Infometrika            info@infometrika.com    2009-05  Eliminacino Variables Globales */
/*************************************************************************************/

if($_GET["codserie"]) $codserie = $_GET["codserie"];
if($_GET["tsub"]) $tsub = $_GET["tsub"];
if($_GET["tdoc"]) $tdoc = $_GET["tdoc"];
if($_GET["insertar_registro"]) $insertar_registro = $_GET["insertar_registro"];
if($_GET["actualizar"]) $actualizar = $_GET["actualizar"];
if($_GET["borrar"]) $borrar = $_GET["borrar"];
if($_GET["linkarchivo"]) $linkarchivo = $_GET["linkarchivo"];
$krd         = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc    = $_SESSION["usua_doc"];
$codusuario  = $_SESSION["codusuario"];
$seriesVistaTodos = $_SESSION["seriesVistaTodos"];
$nurad       = $_GET["nurad"];
$ar       = intval ($_GET["ar"]);

//Si la tipificacion llega de los anexos
if($ar > 0){$es_anexo = true;}else{$es_anexo=false;}

	if($nurad)
	{
		$ent = substr($nurad,-1);
	}
	//include_once("$ruta_raiz/include/db/ConnectionHandler.php");
        require_once (realpath(dirname(__FILE__) . "/../") . "/include/db/Connection/Connection.php");
        require_once (realpath(dirname(__FILE__) . "/../") . "/include/FuncionesDb.php");
	$db = new ConnectionHandler("$ruta_raiz");
         
        $funciones =  new FuncionesDb($db); 	
        if($krd=="JH"){
		$db->conn->debug=true;
	}

	if (!defined('ADODB_FETCH_ASSOC')) define('ADODB_FETCH_ASSOC',2);
   	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
  	include_once "$ruta_raiz/include/tx/Historico.php";
    include_once ("$ruta_raiz/class_control/TipoDocumental.php");
    include_once "$ruta_raiz/include/tx/Expediente.php";
    $coddepe = $dependencia;
	$codusua = $codusuario;
    $isqlDepR = "SELECT RADI_DEPE_ACTU,RADI_USUA_ACTU from RADICADO WHERE RADI_NUME_RADI = '$nurad'";
    $rsDepR = $db->conn->Execute($isqlDepR);
	if ($rsDepR)
	{	// $coddepe = $rsDepR->fields['RADI_DEPE_ACTU'];
		// $codusua = $rsDepR->fields['RADI_USUA_ACTU'];
	}
	
  	$trd             = new TipoDocumental($db);
    $Historico       = new Historico($db);		  
    $trdExp          = new Expediente($db);
    $numExpediente   = $trdExp->consulta_exp("$nurad");
    $mrdCodigo       = $trdExp->consultaTipoExpediente("$numExpediente");
    $trdExpediente   = $trdExp->descSerie." / ".$trdExp->descSubSerie;
    $descPExpediente = $trdExp->descTipoExp;
    $descFldExp      = $trdExp->descFldExp;
    $codigoFldExp    = $trdExp->codigoFldExp;
    $expUsuaDoc      = $trdExp->expUsuaDoc;
	
    // PARTE DE CODIGO DONDE SE IMPLEMENTA EL CAMBIO DE ESTADO AUTOMATICO AL TIPIFICAR.
	include ("$ruta_raiz/include/tx/Flujo.php");
    // $texp no viene de ningun lado 
    if 	(!empty($texp)){
        $objFlujo = new Flujo($db, $texp,$usua_doc);
        $expEstadoActual = $objFlujo->actualNodoExpediente($numExpediente);
        $arrayAristas =$objFlujo->aristasSiguiente($expEstadoActual);
        $aristaSRD = $objFlujo->aristaSRD;
        $aristaSBRD = $objFlujo->aristaSBRD;
        $aristaTDoc = $objFlujo->aristaTDoc;
        $aristaTRad = $objFlujo->aristaTRad;
        $arrayNodos = $objFlujo->nodosSig;
        $aristaAutomatica = $objFlujo->aristaAutomatico;
        $aristaTDoc = $objFlujo->aristaTDoc;
        if($arrayNodos)
        {
          $i = 0;
          foreach ($arrayNodos as $value)
          {
	            $nodo = $value;
	            $arAutomatica =  $aristaAutomatica[$i];
	            $aristaActual = $arrayAristas[$i];
	            $arSRD =  $aristaSRD[$i];
	            $arSBRD = $aristaSBRD[$i];
	            $arTDoc = $aristaTDoc[$i];
	            $arTRad = $aristaTRad[$i];
	            $nombreNodo = $objFlujo->getNombreNodo($nodo,$texp);
		        if($arAutomatica==1 and $arSRD==$codserie and $arSBRD==$tsub and $arTDoc==$tdoc and $arTRad==$ent)
		        {
		            if($insertar_registro)
		            {
		                $objFlujo->cambioNodoExpediente($numExpediente,$nurad,$nodo,$aristaActual,1,"Cambio de Estado Automatico.");
		                $codiTRDS = $codiTRD;
		                $i++;
		                $TRD = $codiTRD;
		                $observa = "*TRD*".$odserie."/".$codiSBRD." (Creacion de Expediente.)";
		                $radicados= $nurad;
		                $tipoTx = 51;


		                $rs=$db->query($sql);
		                $mensaje = "SE REALIZO CAMBIO DE ESTADO AUTOMATICAMENTE AL EXPEDIENTE No. < $numExpediente > <BR>
		                    EL NUEVO ESTADO DEL EXPEDIENTE ES  <<< $nombreNodo >>>";
		            }else 
		            {
		                $mensaje = "SI ESCOGE ESTE TIPO DOCUMENTAL EL ESTADO DEL EXPEDIENTE  < $numExpediente > 
		                    CAMBIARA EL ESTADO AUTOMATICAMENTE A <BR> <<< $nombreNodo >>>";
		            }
		          echo "<table width=100% class=borde_tab>
		              <tr><td align=center>
		              <span class=titulosError align=center>
		              $mensaje
		              </span>
		              </td></tr>
		              </table><TABLE><TR><TD></TD></TR></TABLE>";
			    }
			    $i++;
	      }
    }
  }
  /*
  * Adicion nuevo Registro
  */
  /*14/03/2018
  CARLOS RICAURTE
  se cambia $tsub !=-1 debido a que ahora hay subseries con código 0 entonces se comienza desde el -1
  if($tsub=='') $tsub=0;
  */
    if ($insertar_registro && $tdoc !=0 && $tsub !=-1 && $codserie !=0 ){
    
	    if($tsub=='') $tsub=0;
	    
	    include_once("../include/query/busqueda/busquedaPiloto1.php");

	    $sql = "SELECT $radi_nume_radi AS RADI_NUME_RADI 
					FROM SGD_RDF_RETDOCF r 
					WHERE RADI_NUME_RADI = '$nurad'";
		if($seriesVistaTodos!=1){
		        $sql.="  AND  DEPE_CODI =  '$coddepe'";
		}
		#echo "NO discriminañ...";
		$rs=$db->conn->query($sql);
		$radiNumero = $rs->fields["RADI_NUME_RADI"];

	    if ($radiNumero !=''){

			$codserie = 0 ;
	  		$tsub = -1  ;
	  		$tdoc = 0;
			$mensaje_err = "<HR>
			   <center><B><FONT COLOR=RED>
			   	Ya existe una Clasificacion para esta dependencia <$coddepe> <BR>  VERIFIQUE LA INFORMACION E INTENTE DE NUEVO
			   	</FONT></B></center>
			   	<HR>";
			}
		else
			{
  						
			$isqlTRD = "select SGD_MRD_CODIGO 
					from SGD_MRD_MATRIRD 
					where SGD_SRD_CODIGO = '$codserie'
					   and sgd_sbrd_codigo = '$tsub'
				       and SGD_TPR_CODIGO = '$tdoc'";
			if($seriesVistaTodos!=1){
							$sql.="  AND  DEPE_CODI =  '$coddepe'";
			}

			$rsTRD = $db->conn->Execute($isqlTRD);
			$i=0;
			while(!$rsTRD->EOF)
			{
	    		$codiTRDS[$i] = $rsTRD->fields['SGD_MRD_CODIGO'];
				$codiTRD = $rsTRD->fields['SGD_MRD_CODIGO'];
	    		$i++;
				$rsTRD->MoveNext();
			}    
			$radicados = $trd->insertarTRD($codiTRDS,$codiTRD,$nurad,$coddepe,$codusua,$tdoc);
		   	$TRD = $codiTRD;
			include "$ruta_raiz/radicacion/detalle_clasificacionTRD.php";
			//Modificado skina
			$sqlH = "SELECT $radi_nume_radi as RADI_NUME_RADI
					FROM SGD_RDF_RETDOCF r 
					WHERE r.RADI_NUME_RADI = '$nurad'
				    AND r.SGD_MRD_CODIGO =  '$codiTRD'";
			$rsH=$db->conn->query($sqlH);
			$i=0;
			while(!$rsH->EOF)
			{
	    		$codiRegH[$i] = $rsH->fields['RADI_NUME_RADI'];
	    		$i++;
				$rsH->MoveNext();
			}

			$observa = "*TRD*$codserie/$tsub (Asigancion tipo documental.)"; 
			$_Tx_id = 32;

			if($es_anexo==true){
			$nrobs = $codiRegH[0];	
			$_Tx_id = 95;
			$observa = "*TRD*$codserie/$tsub (Anexo Tipificado No. $nrobs)";

			//TRAIGO EL RADICADO PADRE
			$_isql ="select radi_nume_deri from radicado where radi_nume_radi = $nrobs";
			$_rsisql =$db->conn->query($_isql);
			$codiRegH[0] = $_rsisql->fields['RADI_NUME_DERI']; 
			if ($ent == 1){ //Si es vacio, traemos el que esta quiere decir que es entrada
				$codiRegH[0] = $nrobs;}
			}


			$Historico->insertarHistorico($codiRegH, $dependencia, $codusuario, $dependencia, $codusuario, $observa,$_Tx_id); 
		  	/*
		  	*Actualiza el campo tdoc_codi de la tabla Radicados
		  	*/
		 	$radiUp = $trd->actualizarTRD($codiRegH,$tdoc);
		
  			$codserie = 0 ;
  			$tsub = 0  ;
  			$tdoc = 0;
		}
  	}

?> 

<html>
<head>
<title>..:: Clasificar Documento ::..</title>
<?php
include $ruta_raiz."/htmlheader.inc.php";
?>
<script>
function regresar(){   	
	document.TipoDocu.submit();
}
</script>
</head>
<body >
<form method="GET" action="<?=$encabezadol?>" name="TipoDocu" class=smart-form> 

<input type='hidden' name='<?=session_name()?>' value='<?=session_id()?>'> 
<input type='hidden' name='nurad'               value='<?=$nurad?>'> 
<input type='hidden' name='ent'                 value='<?=$ent?>'> 
<input type='hidden' name='codiTRDModi'         value='<?=$codiTRDModi?>'> 
<input type='hidden' name='codiTREDli'          value='<?=$codiTREDli?>'> 
<input type='hidden' name='ind_ProcAnex'        value='<?=$ind_ProcAnex?>'> 
<input type='hidden' name='ar'        		value='<?=$ar?>'> 

    <table width=70% align="center" class="table table-bordered">
	  <tr align="center" >
	    <th height="15" ><small>CUADRO DE CLASIFICACION DOCUMENTAL - Radicado No <?=$nurad?></small></th>
      </tr>
	 </table> 
 	<table width="70%" class="table table-bordered">
      <tr >
	  <td  ><small>SERIE</small></td>
	  <td  ><label class=select>
   <?php

   if(!$tdoc) $tdoc = 0;
   if(!$codserie) $codserie = 0;
   if(!$tsub) $tsub = 0;
   $fechah=date("dmy") . " ". date("h_m_s");
   $fecha_hoy = Date("Y-m-d");
   $sqlFechaHoy=$db->conn->DBDate($fecha_hoy);
   $sqlFechaHoy2 = $db->conn->SQLDate('Y-m-d',$db->conn->sysTimeStamp);
   $check=1;
   $fechaf=date("dmy") . "_" . date("hms");
   $num_car = 4;
   $nomb_varc = "s.sgd_srd_codigo";
   $nomb_varde = "s.sgd_srd_descrip";
   include "$ruta_raiz/include/query/trd/queryCodiDetalle.php";
   $querySerie = "select distinct($sqlConcat) as detalle, s.sgd_srd_codigo
   from sgd_mrd_matrird m, sgd_srd_seriesrd s
   where (m.depe_codi = $coddepe or ".$funciones->instr("m.depe_codi_aplica","'$coddepe'")." > 0 )"// cast(m.depe_codi as varchar(5))='$depDireccion')
  ." and s.sgd_srd_codigo = m.sgd_srd_codigo
   and m.sgd_mrd_esta     = 1
   and ".$db->sysdate()." between s.sgd_srd_fechini and s.sgd_srd_fechfin";

   if($seriesVistaTodos!=1){
   //$querySerie .= " and m.depe_codi = $dependencia ";
   }

   //and ".$sqlFechaHoy." between $sgd_srd_fechini and $sgd_srd_fechfin
   $querySerie .= " ";

   $rsD=$db->conn->query($querySerie);
   $comentarioDev = "Muestra las Series Docuementales";
   include "$ruta_raiz/include/tx/ComentarioTx.php";
   print $rsD->GetMenu2("codserie", $codserie, "0:-- Seleccione --", false,"","onChange='submit()' class='select'" );
   ?>
      </label></td>
     </tr>
   <tr>
     <td  ><small>SUBSERIE</small></td>
	 <td ><label class=select>
	<?php
	$nomb_varc = "su.sgd_sbrd_codigo";
	$nomb_varde = "su.sgd_sbrd_descrip";
	include "$ruta_raiz/include/query/trd/queryCodiDetalle.php"; 
   	$querySub = "select distinct ($sqlConcat) as detalle, su.sgd_sbrd_codigo 
	         from sgd_mrd_matrird m, sgd_sbrd_subserierd su
			 where
         cast(m.sgd_mrd_esta as numeric(1))       = 1
			   and m.sgd_srd_codigo = $codserie
				 and su.sgd_srd_codigo = $codserie
			   and su.sgd_sbrd_codigo = m.sgd_sbrd_codigo
         and ".$db->sysdate()." between su.sgd_sbrd_fechini and su.sgd_sbrd_fechfin ";
	if($seriesVistaTodos!=1){
		 $querySub .= " and (cast(m.depe_codi as varchar(5)) = '$coddepe' or m.depe_codi_aplica  like '%$coddepe%' or cast(m.depe_codi as varchar(5))='$depDireccion') ";
		
	}
 	$querySub .= " order by 1
			  ";
	
	$rsSub=$db->conn->query($querySub);
	include "$ruta_raiz/include/tx/ComentarioTx.php";
	print $rsSub->GetMenu2("tsub", $tsub, "-1:-- Seleccione --", false,"","onChange='submit()' class='select'" );

?> 
     </select></label></td>
     </tr>
   <tr>
     <td><small>TIPO DE DOCUMENTO</small></td>
 	 <td><label class=select>
<?php
	$nomb_varc = "t.sgd_tpr_codigo";
	$nomb_varde = "t.sgd_tpr_descrip";
	include "$ruta_raiz/include/query/trd/queryCodiDetalle.php"; 
	if($ent) $queryTrad = " and sgd_tpr_tp$ent >= 1";
	$queryTip = "select distinct ($sqlConcat) as detalle, t.sgd_tpr_codigo 
	        from sgd_mrd_matrird m, sgd_tpr_tpdcumento t, sgd_sbrd_subserierd sb
		 where cast(m.sgd_mrd_esta as numeric(1))       = 1
 		    and m.sgd_srd_codigo     = $codserie
		    and m.sgd_sbrd_codigo    = $tsub
 		    and t.sgd_tpr_codigo = m.sgd_tpr_codigo
 		    and sb.sgd_sbrd_codigo = m.sgd_sbrd_codigo
 		    and sb.sgd_srd_codigo = m.sgd_srd_codigo
                    and t.sgd_tpr_estado = 1
                    $queryTrad
 		    and ".$db->sysdate()." between sb.sgd_sbrd_fechini 
		    and sb.sgd_sbrd_fechfin ";
 	$queryTip .= " order by detalle";
 	
        $rsTip=$db->conn->query($queryTip);
	$ruta_raiz = "..";
	include "$ruta_raiz/include/tx/ComentarioTx.php";
	print $rsTip->GetMenu2("tdoc", $tdoc, "0:-- Seleccione --", false,"","onChange='submit()' class='select'" );
	?></label>
    </td>
    </tr>
	  <tr align="center">
		<td align="center" colspan=3><footer>
        <input name="insertar_registro" type=submit class="btn btn-success btn-xs" value=" Insertar ">
		    <input name="actualizar" type="button" class="btn btn-primary btn-xs" id="envia23" onClick="procModificar();"value=" Modificar ">
<?php
  $respuesta_rap = (isset($_GET['respuesta_rap']))? $_GET['respuesta_rap'] : null;
  if (!$respuesta_rap) {
    echo '<input name="Cerrar" type="button" class="btn btn-default btn-xs" id="envia22" onClick="opener.regresar(); window.close(); " value="Cerrar">';
  }
?>
        <input name="respuesta_rap" type="hidden" value="<?=$respuesta_rap?>">
		    </footer>
		   </td>
	   </tr>
	</table>
	<table width="70%" class="table table-bordered">
	  <tr align="center">
	    <td>
		<?php
		include "lista_tiposAsignados.php";
		if ($ind_ProcAnex=="S")
			{
	      	echo " <br> <input type='button' value='Cerrar' class='botones_largo' onclick='opener.regresar();window.close();'> ";
			}	
		?>
	 	</td>
	   </tr>
	</table>
<script>
function borrarArchivo(anexo,linkarch){
	if (confirm('Esta seguro de borrar este Registro ?'))
	{
		nombreventana="ventanaBorrarR1";
		url="tipificar_documentos_transacciones.php?<?=session_name()."=".session_id()?>&borrar=1&nurad=<?=$nurad?>&codiTRDEli="+anexo+"&linkarchivo="+linkarch;
		window.open(url,nombreventana,'height=250,width=300');
	}
return;
}
function procModificar()
{
	/*14/03/2018
  CARLOS RICAURTE
  se cambia $tsub !=-1 debido a que ahora hay subseries con código 0 entonces se comienza desde el -1
  */
if (document.TipoDocu.tdoc.value != 0 &&  document.TipoDocu.codserie.value != 0 &&  document.TipoDocu.tsub.value != -1){
	<?php
	$sql = "SELECT RADI_NUME_RADI
	        FROM SGD_RDF_RETDOCF 
			WHERE RADI_NUME_RADI = '$nurad'";
	if($seriesVistaTodos!=1){
	        $sql.="  AND  DEPE_CODI =  '$coddepe'";
	}

	$rs=$db->conn->query($sql);
	$radiNumero = $rs->fields["RADI_NUME_RADI"];
	if ($radiNumero !='') {
	?>
	if (confirm('Esta Seguro de Modificar el Registro de su Dependencia ?')){
		nombreventana="ventanaModiR1";
		url="tipificar_documentos_transacciones.php?<?=session_name()."=".session_id()?>&actualizar=1&usua=<?=$krd?>&codusua=<?=$codusua?>&tdoc=<?=$tdoc?>&tsub=<?=$tsub?>&codserie=<?=$codserie?>&coddepe=<?=$coddepe?>&codusuario=<?=$codusuario?>&dependencia=<?=$dependencia?>&nurad=<?=$nurad?>";
		window.open(url,nombreventana,'height=200,width=300');
	}
	<?php
	}
	else{
	?>
		alert("No existe Registro para Modificar ");
	<?php
	}
    ?>
}
else{
	alert("Campos obligatorios ");
}
return;
}

</script>
</form>
</span>
<p>
<?=$mensaje_err?>
</p>
</span>
</body>
<br><br><br><br>
<center><span><p><h4>  <strong> NOTA:</strong> La Clasificación Documental es importante para la organización de los documentos físicos. Por tanto, asegúrese de asignar la TRD de acuerdo a sus funciones en la entidad.  </h4> </p></span></center>
</html>
<?php
  if ($cerrar) {
    echo '<br>
            <script>
              javascript:window.parent.opener.cargarPagina("' . $recargar_anexos . '","tabs-c");
              top.close();
            </script>';
  }
?>
