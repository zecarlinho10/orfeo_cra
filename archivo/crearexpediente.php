<?

//capturar valor seleccion del formulario del metodo post


if (isset($_POST['anoExp'])){$anoExp = $_POST['anoExp'];}
if (isset($_POST['codepe'])){$codepe = $_POST['codepe'];}
if (isset($_POST['codserie'])){$codserie = $_POST['codserie'];}
if (isset($_POST['tsub'])){$tsub = $_POST['tsub'];}
if (isset($_POST['campo'])){$campo = $_POST['campo'];}
if (isset($_POST['usuaDocExp'])){$usuaDocExp = $_POST['usuaDocExp'];}
if (isset($_POST['tipo'])){$tipo = $_POST['tipo'];}
if (isset($_POST['crearExpediente'])){$crearExpediente = $_POST['crearExpediente'];}
if (isset($_POST['Actualizar'])){$Actualizar = $_POST['Actualizar'];}
if (isset($_POST['par'])){$par = $_POST['par'];}
if (isset($_POST['trdExp'])){$par = $_POST['trdExp'];}
if (isset($_POST['consecutivoExp'])){$par = $_POST['consecutivoExp'];}
if (isset($_POST['codiSRD'])){$par = $_POST['codiSRD'];}


	error_reporting(0);
	$krdold = $krd;
 	session_start();
foreach ($_GET as $key => $valor)
    ${$key} = $valor;
foreach ($_POST as $key => $valor)
    ${$key} = $valor;

$krd = $_SESSION["krd"];
	$ruta_raiz = "..";
	if(!$krd) $krd = $krdold;
	if (!isset($_SESSION['dependencia']))	include "$ruta_raiz/rec_session.php";
 	error_reporting(0);

	if (!$nurad) $nurad= $rad;
	if($nurad)
	{
		$ent = substr($nurad,-1);
	}
    include_once("$ruta_raiz/include/db/ConnectionHandler.php");
	$db = new ConnectionHandler("$ruta_raiz");
	include_once "$ruta_raiz/include/tx/Historico.php";
	include_once ("$ruta_raiz/class_control/TipoDocumental.php");
	include_once "$ruta_raiz/include/tx/Expediente.php";
	$trd = new TipoDocumental($db);
	$encabezadol = "$PHP_SELF?opcionExp=$opcionExp&numeroExpediente=$numeroExpediente&dependencia=$dependencia&krd=$krd&nurad=$nurad&coddepe=$coddepe&codusua=$codusua&depende=$depende&ent=$ent&tdoc=$tdoc&codiTRDModi=$codiTRDModi&codiTRDEli=$codiTRDEli&codserie=$codserie&tsub=$tsub&ind_ProcAnex=$ind_ProcAnex&codProc=$codProc";
	
$dependencia=$codepe;
//$par=$par;
	$numeroExpediente=$_POST['$numeroExpediente'];	
?>
<html>
<head>
<title>Tipificar Expediente</title>
<link href="../estilos/orfeo.css" rel="stylesheet" type="text/css" />
 <?php include_once "../htmlheader.inc.php"; ?>	
<script>

function regresar(){
	document.TipoDocu.submit();
}

function Start(URL, WIDTH, HEIGHT)
{
    windowprops = "top=0,left=0,location=no,status=no, menubar=no,scrollbars=yes, resizable=yes,width="+WIDTH+",height="+HEIGHT;
    preview = window.open(URL , "preview", windowprops);
}
</script><style type="text/css">
<!--
.style1 {font-size: 14px}
-->
</style>
</head>
<body bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
 <link rel="stylesheet" type="text/css" href="../js/spiffyCal/spiffyCal_v2_1.css">
 <script language="JavaScript" src="../js/spiffyCal/spiffyCal_v2_1.js"></script>
<form method="post" action="<?=$encabezadol?>" name="TipoDocu">
  <?
  /*
  * Adicion nuevo Registro
  */
  //CARLOS RICAURTE
  //8/03/2018
  //se cambia el 0 por -1 en subseries debido a que hay 
  if ($Actualizar && $tsub !=-1 && $codserie !=0 )
  {
  	if(!$digCheck)
	{
		$digCheck = "E";
	}
  	$codiSRD = $codserie;
	$codiSBRD = $tsub;

//Modificacion DAVID AMAYA BELTRAN
	if(strlen($codiSRD)==3) 
	{
	$trdExp = substr("000".$codiSRD,-3) . substr("00".$codiSBRD,-2);
	$consecutivoExp = substr("0000".$secExp,-4);
	}
	if(strlen($codiSRD)==2) 
	{
	$trdExp = substr("00".$codiSRD,-2) . substr("00".$codiSBRD,-2);
	$consecutivoExp = substr("00000".$secExp,-5);
	}

	//se modifica para que no sea -2 si no -3 Ing. Juan Carlos Vilallba cardenas 09/05/2013
	//$trdExp = substr("00".$codiSRD,-2) . substr("00".$codiSBRD,-2);


	$expediente = new Expediente($db);
	if(!$expManual)
	{
		$secExp = $expediente->secExpediente($codepe,$codiSRD,$codiSBRD,$anoExp);
	}else
	{
		$secExp = $consecutivoExp;
	}
	$consecutivoExp = substr("00000".$secExp,-5);
	$Ncodepe = substr("000".$codepe,-3);
	$numeroExpediente = $anoExp . $Ncodepe . $trdExp . $consecutivoExp . $digCheck;
	if($expPrivado == 1){
		$expPrivado = 1;
	}elseif ($expPrivado == 2) {
		$expPrivado = 2;
	}else {
		$expPrivado = 0;
	}

    /*
     *  Modificado: 09-Junio-2006 Supersolidaria
     *  Arreglo con los parametros del expediente.
	 */
    
foreach ($_POST as $elementos => $valor) {
        if (strncmp($elementos, 'parExp_', 7) == 0) {
            $indice = ( int )substr($elementos, 7);
            $arrParametro[$indice] = $valor;
        }
    }

    $Ncodepe = substr("000".$codepe,-3);
	/**  Procedimiento que Crea el Numero de  Expediente
	  *  @param $numeroExpediente String  Numero Tentativo del expediente, Hya que recordar que en la creacion busca la ultima secuencia creada.
	  *  @param $nurad  Numeric Numero de radicado que se insertara en un expediente.
      *  Modificado: 09-Junio-2006 Supersolidaria
      *  La funcion crearExpediente() recibe los parametros $codiPROC y $arrParametro
	  */
	  	//$numeroExpedienteE = $expediente->crearExpediente( $numeroExpediente,$nurad,$codepe,$codusuario,$usua_doc,$usuaDocExp,$tipo,$codiSRD,$codiSBRD, 'false',$fechaExp, $codProc, $arrParametro, $etiqueta );
	        //$numeroExpedienteE = $expediente->crearExpediente($numeroExpediente, $nurad, $dependencia, $codusuario, $usua_doc, $usuaDocExp, $codiSRD, $codiSBRD, 'false', $fechaExp, $_POST['codProc'], $arrParametro);
		
		$numeroExpedienteE = $expediente->crearExpediente( $numeroExpediente,$nurad,$Ncodepe,$codusuario,$usua_doc,$usuaDocExp,$codiSRD,$codiSBRD, 'false',$fechaExp, $codProc, $arrParametro, $tipo );
		if($numeroExpedienteE==0)
		{
			echo "<CENTER><table class=borde_tab><tr><td class=titulosError>EL EXPEDIENTE QUE INTENTO CREAR YA EXISTE.</td></tr></table>";
		}else
		{
			/**  Procedimiento que Inserta el Radicado en el Expediente
			  *  @param $insercionExp Numeric  Devuelve 1 si inserto el expediente correctamente 0 si Fallo.
				*
			  */
			$insercionExp = $expediente->insertar_expediente( $numeroExpediente,$nurad,$codepe,$codusuario,$usua_doc);
		}
			$codiTRDS = $codiTRD;
			$i++;
            $TRD = $codiTRD;
			$observa = "*TRD*".$codserie."/".$codiSBRD." (Creacion de Expediente.)";
			include_once "$ruta_raiz/include/tx/Historico.php";
			$radicados[] = $nurad;
			$tipoTx = 51;
			$Historico = new Historico($db);
			$Historico->insertarHistoricoExp($numeroExpediente,$radicados, $dependencia,$codusuario, $observa, $tipoTx,0);
			include ("$ruta_raiz/include/tx/Flujo.php");
			$objFlujo = new Flujo($db, $_POST['codProc'],$usua_doc);
			$expEstadoActual = $objFlujo->actualNodoExpediente($numeroExpediente);
			$arrayAristas =$objFlujo->aristasSiguiente($expEstadoActual);
			$aristaActual = $arrayAristas[0];
			$objFlujo->cambioNodoExpediente($numeroExpediente,$nurad,$expEstadoActual,$aristaActual,1,"Creacion Expediente");
  }
	?>
<table border=0 width=70% align="center" class="borde_tab" cellspacing="1" cellpadding="0">

    <?php
    if( $numeroExpedienteE != 0 )
    {
    ?>
	<tr align="center" class="listado2">
	  <td width="33%" height="25" align="center" colspan="2">
        <font color="#CC0000" face="arial" size="2">
          Se ha creado el Expediente No.
        </font>
        <b>
          <font color="#000000" face="arial" size="2">
            <?php print $numeroExpedienteE; ?>
        </b>
        <font color="#CC0000" face="arial" size="2">
          con la siguiente informaci&oacute;n:
        </font>
		</center>
	  </td>
	</tr>
    <?php
    }
    ?>

    <tr align="center" class="titulos2">
      <td height="15" class="titulos2" colspan="2">APLICACION DE LA TRD DEL EXPEDIENTE</td>
    </tr>

    <?php
    if( $numeroExpedienteE != 0 )
    {
        $arrTRDExp = $expediente->getTRDExp( $numeroExpediente, $codserie, $tsub, $codProc );
    ?>
    
    <tr class="titulos5">
      <td>SERIE</td>
      <td>
        <?php print $arrTRDExp['serie']; ?>
	  </td>
    </tr>
	<tr class="titulos5">
	  <td>SUBSERIE</td>
	  <td>
        <?php print $arrTRDExp['subserie']; ?>
	  </td>
    </tr>
	<tr class="titulos5">
      <td>ETIQUETA</td>
      <td>
        <?php 
        	$camposConcatenar = "(" . $db->conn->Concat("sgd_sexp_parexp1",
                                                    "sgd_sexp_parexp2",
                                                    "sgd_sexp_parexp3",
                                                    "sgd_sexp_parexp4",
                                                    "sgd_sexp_parexp5") . ")";
                                                    
            $isqlE="select $camposConcatenar as etiqueta from sgd_sexp_secexpedientes where sgd_exp_numero='$numeroExpedienteE'";                                        
		//$db->conn->debug=true;
            $rs = $db->conn->Execute($isqlE);      
            $etiqueta=$rs->fields['ETIQUETA'];
        print $etiqueta; ?>
      </td>
	</tr>
    <?php
    }
    ?>
</table>
<?php
 /* Modificado para nueva forma de asignación y creación de expedientes a partir del 2 de Nov de 2007
 	@author  YULLIE QUICANO
 	@mail    yquicano@cra.gov.co
*/


if ( !isset( $Actualizar ) ) //Inicio if( $Actualizar )
{
?>
<table width="70%" border="0" cellspacing="1" cellpadding="0" align="center" class="borde_tab">
<tr align="left" colspan="2">
		<td width="31%" class='titulos5'>DEPENDENCIA</td>
		<td width="69%"  class='listado5' align="left">
		
		 <?
		 
$sql = "SELECT DEPE_NOMB, DEPE_CODI AS DEPE_CODI FROM DEPENDENCIA
				WHERE DEPE_ESTADO=1
				 order by DEPE_NOMB";
	$rsDep = $db->conn->Execute($sql);
		if(!$s_DEPE_CODI) $s_DEPE_CODI= 0;
	print $rsDep->GetMenu2("codepe", $codepe, "0:-- Seleccione --", false,"","onChange='submit()' class='select'" );
	
	$isqlDepR = "SELECT depe_codi, dep_central from dependencia WHERE DEPE_CODI = '$codepe'";
	$rsDepR = $db->conn->Execute($isqlDepR);
	
	if ($rsDepR)
	{	$depcentral = $rsDepR->fields['DEP_CENTRAL'];
	}
	?>
	</td>
</tr>
<tr >
<td width="62%" class="titulos5" >SERIE</td>
<td width="38%" class=listado5 >
	<?php
	if(!$tdoc) $tdoc = 0;
	if(!$codserie) $codserie = 0;
	if(!$tsub) $tsub = 0;
	$fechah=date("dmy") . " ". date("h_m_s");
	$fecha_hoy = Date("Y-m-d");
	$sqlFechaHoy=$db->conn->DBDate($fecha_hoy);
	$check=1;
	$fechaf=date("dmy") . "_" . date("hms");
	$num_car = 4;
	$nomb_varc = "s.sgd_srd_codigo";
	$nomb_varde = "s.sgd_srd_descrip";
	include "$ruta_raiz/include/query/trd/queryCodiDetalle.php";
	$querySerie = "select distinct ($sqlConcat) as detalle, s.sgd_srd_codigo
		from sgd_mrd_matrird m, sgd_srd_seriesrd s
		where m.depe_codi = '$codepe'
			and s.sgd_srd_codigo = m.sgd_srd_codigo
			and m.sgd_mrd_esta>=1
		order by detalle
	";
//	 $db->conn->debug=true;
	$rsD=$db->conn->Execute($querySerie);
	$comentarioDev = "Muestra las Series Docuementales";
	include "$ruta_raiz/include/tx/ComentarioTx.php";
	print $rsD->GetMenu2("codserie", $codserie, "0:-- Seleccione --", false,"","onChange='submit()' class='select'" );
 ?>
	</td>
	</tr>
	<tr>
		<td class="titulos5" >SUBSERIE</td>
	<td class=listado5 >
	<?
	$nomb_varc = "su.sgd_sbrd_codigo";
	$nomb_varde = "su.sgd_sbrd_descrip";
	include "$ruta_raiz/include/query/trd/queryCodiDetalle_CRA.php";
	$querySub = "select distinct ($sqlConcat) as detalle, su.sgd_sbrd_codigo
		from sgd_mrd_matrird m, sgd_sbrd_subserierd su
		where m.depe_codi = '$codepe'
			and m.sgd_srd_codigo = '$codserie'
			and su.sgd_srd_codigo = '$codserie'
			and su.sgd_sbrd_codigo = m.sgd_sbrd_codigo
			and ".$sqlFechaHoy." between su.sgd_sbrd_fechini and su.sgd_sbrd_fechfin
		order by detalle
		";
	$rsSub=$db->conn->Execute($querySub);
	include "$ruta_raiz/include/tx/ComentarioTx.php";
	print $rsSub->GetMenu2("tsub", $tsub, "-1:-- Seleccione --", false,"","onChange='submit()' class='select'" );
	if(!$codiSRD)
	{
		$codiSRD = $codserie;
		$codiSBRD = $tsub;
	}
    /**********************************************/
    // Modificacion: 22-Mayo-2006
    // Selecciona el proceso y el codigo correspondiente segun la combinacion
    // Serie-Subserie
   	// $queryPEXP = "select SGD_PEXP_DESCRIP,SGD_PEXP_TERMINOS FROM
   	$queryPEXP = "select SGD_PEXP_DESCRIP,SGD_PEXP_CODIGO FROM
			SGD_PEXP_PROCEXPEDIENTES
			WHERE
				SGD_SRD_CODIGO=$codiSRD
				AND SGD_SBRD_CODIGO=$codiSBRD
			";
			
	$rs=$db->conn->Execute($queryPEXP);
	$texp = $rs->fields["SGD_PEXP_CODIGO"];
    /*
	$expTerminos = $rs->fields["SGD_PEXP_TERMINOS"];
    if ($expTerminos)
    {
    $expDesc = " $expTerminos Dias Calendario de Termino Total";
    }
    */
    /**********************************************/
?>
	  </td>
	</tr>
	<tr>
        <!--Modificacion: 22-Mayo-2006
        Combo para seleccionar el proceso segun la combinacion Serie-Subserie
        -->
        <!--
		<td class="titulos5" colspan="2" ><center>&nbsp;<?=$descTipoExpediente?> - <?=$expDesc?></center></td>
        -->
        <br>
<table border=0 width=70% align="center" class="borde_tab">
 <tr align="center">
	<td width="13%" height="25" class="titulos5" align="center">
	N&uacute;mero de Expediente</TD>
	<?
	if(!$digCheck)
	{
		$digCheck = "E";
	}
	$expediente = new Expediente($db);
	if(!$expManual)
	{
		if(!$anoExp) $anoExp = date("Y");
		$secExp = $expediente->secExpediente($codepe,$codiSRD,$codiSBRD,$anoExp);
	}else
	{
		$secExp = $consecutivoExp;
	}

//Modificacion DAVID AMAYA BELTRAN
	if(strlen($codiSRD)==3) 
	{
		$trdExp = substr("000".$codiSRD,-3) . substr("00".$codiSBRD,-2);
		$consecutivoExp = substr("00000".$secExp,-5);
	}
	if(strlen($codiSRD)==2) 
	{
		$trdExp = substr("00".$codiSRD,-2) . substr("00".$codiSBRD,-2);
		$consecutivoExp = substr("00000".$secExp,-5);
	}

	//se modifica para que no sea -2 si no -3 Ing. Juan Carlos Vilallba cardenas 09/05/2013
	//$trdExp = substr("00".$codiSRD,-2) . substr("00".$codiSBRD,-2);
	//$consecutivoExp = substr("00000".$secExp,-5);

	$Ncodepe = substr("000".$codepe,-3);

  if(!$anoExp) $anoExp = date("Y");
	?>
	<td width="33%" class="listado2" height="25">
	<p>
	<input type=text name=anoExp value='<?=(date('Y'))?>' class=select maxlength="4" size="3" readonly>
	<input type=text name=depExp value='<?=$Ncodepe?>' class=select maxlength="3" size="2" readonly>
	<input type=text name=trdExp value='<?=$trdExp?>' class=select maxlength="6" size="4"readonly>
	<input type=text name=consecutivoExp value='<?=$consecutivoExp?>'  class=select maxlength="5" size=5 readonly>
	<input type=text name=digCheckExp value='<?=$digCheck?>' class=select maxlength="1" size="1" readonly>
	<?
	//$numeroExpediente = $anoExp . $codepe . $trdExp . $consecutivoExp . $digCheck;
	$numeroExpediente = $anoExp . substr("000".$codepe,-3) . $trdExp . $consecutivoExp . $digCheck;
	?>
	</center>


	<br>
			A&ntilde;o-Dependencia-Serie Subserie-Consecutivo-E<br>
			<?=$secExp?>El consecutivo "<?=$consecutivoExp?>" es temporal y puede cambiar en el momento de crear el expediente.
		<br>
	<?=$numeroExpediente?>
	</TD>
	</tr>
  <?php
    $sqlParExp  = "SELECT SGD_PAREXP_ETIQUETA, SGD_PAREXP_ORDEN";
    $sqlParExp .= " FROM SGD_PAREXP_PARAMEXPEDIENTE PE";
    $sqlParExp .= " WHERE PE.DEPE_CODI = ".$dependencia;
    $sqlParExp .= " ORDER BY SGD_PAREXP_ORDEN";
     //print $sqlParExp;
    $rsParExp = $db->conn->Execute( $sqlParExp );
    while ( !$rsParExp->EOF ){
  ?>
    <tr align="center">
      <td width="13%" height="25" class="titulos5" align="left">
  <?php
        print $rsParExp->fields['SGD_PAREXP_ETIQUETA'];
  ?>      </td>
  <?
  		$campo="parExp_".$rsParExp->fields['SGD_PAREXP_ORDEN'];
  	if(!isset($_POST[$campo])){
		
	  	$sql="select sgd_doc_fun, sgd_esp_codi,sgd_oem_codigo from sgd_dir_drecciones where radi_nume_radi =$nurad";
	//$db->conn->debug=true;
	  
	  $rs=$db->conn->Execute($sql);
	  if(!$rs->EOF){
	  	$esp=$rs->fields['SGD_ESP_CODI'];
		$oem=$rs->fields['SGD_OEM_CODIGO'];
		$fun=$rs->fields['SGD_DOC_FUN'];
		if($esp!="" and $rsParExp->fields['SGD_PAREXP_ORDEN']==1){
			$sqe="select nombre_de_la_empresa from bodega_empresas where identificador_empresa = $esp";
			$rse=$db->conn->Execute($sqe);
			if(!$rse->EOF)$par=$rse->fields['NOMBRE_DE_LA_EMPRESA'];
			else $par=$_POST[ 'parExp_'.$rsParExp->fields['SGD_PAREXP_ORDEN'] ]; 
		} elseif($oem>0 and $rsParExp->fields['SGD_PAREXP_ORDEN']==2){
			$sqe="select sgd_oem_oempresa from sgd_oem_oempresas where sgd_oem_codigo = $oem";
			$rse=$db->conn->Execute($sqe);
			if(!$rse->EOF)$par=$rse->fields['SGD_OEM_OEMPRESA'];
			else $par=$_POST[ 'parExp_'.$rsParExp->fields['SGD_PAREXP_ORDEN'] ]; 
		}elseif($fun>0 and $rsParExp->fields['SGD_PAREXP_ORDEN']==4){
			$sqe="select (usua_nomb) AS SGD_NOMBRE_COMPLETO  from usuario where usua_doc = $fun";
			$rse=$db->conn->Execute($sqe);
			if(!$rse->EOF)$par=$rse->fields['SGD_NOMBRE_COMPLETO'];
			else $par=$_POST[ 'parExp_'.$rsParExp->fields['SGD_PAREXP_ORDEN'] ]; 
		}
		else $par=$_POST[ 'parExp_'.$rsParExp->fields['SGD_PAREXP_ORDEN'] ];
	  }
	 
    }else{
    	$par=$_POST[$campo];
    }
    $salida=($par!="")?$par:$salida;
     ?>
      <td width="13%" height="25" class="titulos5" align="left">
        <input type="text" name="<?=$campo?>"" id="<?=$campo;?>" value="<?=$par?>" size="75" class="listado2" ></td>
    </tr>
  <?php	
    $rsParExp->MoveNext();
    }
//$db->conn->debug=true;
  ?>
	
  <tr align="center">
  
    <td width="13%" height="25" class="titulos5" align="center" colspan="2">
      <input type="button" name="Button" value="BUSCAR" class="botones" onClick="Start('buscarParametro.php?busq_salida=<?=$busq_salida ?>&krd=<?=$krd ?>',1024,420);">    </td>
  </tr>

<TD class=titulos5>
		Usuario Responsable</TD>
	<td class=listado2>
<?
	$queryUs = "select usua_nomb, usua_doc from usuario where depe_codi=$codepe AND USUA_ESTA=1
							order by usua_nomb";
	$rsUs = $db->conn->Execute($queryUs);
	print $rsUs->GetMenu2("usuaDocExp", "$usuaDocExp", "0:-- Seleccione --", false,""," class='select' onChange='submit()'");
	
?>	</td>
</tr>
<tr >
<td width="62%" class="titulos5" >Tipo Expediente</td>
<td width="38%" class=listado5 >
<select name=tipo class=select>
<?
if($tipo==0)  $datoss = "selected "; else $datoss = "";
?>
<option value=0 <?=$datoss?>>-Seleccione-</option>
<?
if($tipo==1)  $datoss = "selected "; else $datoss = "";
?>
<option value=1 <?=$datoss?>>General Terceros</option>
<?
if($tipo==2)  $datoss = "selected "; else $datoss = "";
?>
<option value=2 <?=$datoss?>>General E.S.P.</option>
<?
if($tipo==3)  $datoss = "selected "; else $datoss = "";
?>
<option value=3 <?=$datoss?>>Espec&iacute;fico</option>

</select>
</td>
</tr>
</table>
<br>
<?
if( $crearExpediente )
{
	print $tipo;
	//print $numeroExpediente;
?>
	<table border=0 width=70% align="center" class="borde_tab">
		<tr align="center">
			<td width="33%" height="25" class="listado2" align="center">
				<center class="titulosError2">
				ESTA SEGURO DE CREAR EL EXPEDIENTE ? <BR>
				EL EXPEDIENTE QUE VA HA CREAR ES EL :
				</center><B><center class="style1"><?=$numeroExpediente?></center>
				</B>
				<div align="justify"><br>

				  <strong><b>Recuerde:</b>No podr&aacute; modificar el numero de expediente si hay un error en el expediente, mas adelante tendr&aacute; que excluir este radicado del expediente y si es el caso solicitar la anulaci&oacute;n del mismo. Ademas debe tener en cuenta que apenas coloca un nombre de expediente, en Archivo crean una carpeta f&iacute;sica en el cual empezaran a incluir los documentos pertenecientes al mismo. </strong>
				</div>
			</TD>
		</tr>
	</table>
<?
}

?>

<?php
}// Fin if( $Actualizar )
?>
<table border=0 width=70% align="center" class="borde_tab">
	<tr align="center">
	<td width="33%" height="25" class="listado2" align="center">
	<center>
	<?
   
	//var_dump($salida);
	$par=$salida;
	 $sqlb="select distinct (s.sgd_exp_numero), s.sgd_sexp_parexp1, s.sgd_sexp_parexp2, s.sgd_sexp_parexp3, s.sgd_sexp_parexp4, s.sgd_sexp_parexp5       from sgd_sexp_secexpedientes s,
	 			sgd_sbrd_subserierd su, sgd_exp_expediente e 
	 			where s.sgd_srd_codigo='$codserie' 
	 			and s.sgd_sbrd_codigo=su.sgd_sbrd_codigo 
	 			and s.sgd_srd_codigo=su.sgd_srd_codigo 
	 			and s.sgd_sbrd_codigo='$tsub' 
	 			and e.sgd_exp_estado <> 2
	 			and s.depe_codi='$codepe' 
	 			and (s.sgd_sexp_parexp1 = '$salida' or s.sgd_sexp_parexp2 = '$salida' or s.sgd_sexp_parexp3 = '$salida' or s.sgd_sexp_parexp4 = '$salida'or s.sgd_sexp_parexp5 = '$salida')
	 			and s.sgd_sexp_fech > to_date('01/11/07','DD/MM/YY')
	 			and s.sgd_cerrado <> 1 ";
	//$db->conn->debug=true;
	 $rsb=$db->conn->Execute($sqlb);
	
	
	//$db->conn->debug=true;
	 while($rsb && !$rsb->EOF){
	 $expe=$rsb->fields['SGD_EXP_NUMERO'];
	 $rsb->MoveNext();
	

	 }

 //CARLOS RICAURTE
  //8/03/2018
  //se cambia el 0 por -1 en subseries debido a que hay 
  if($tsub!=-1 and $codserie && !$Actualizar and $usuaDocExp and $par)
	{
		if(!$crearExpediente)
		{
            /*
             *  Modificado: 17-Agosto-2006 Supersolidaria
             *  Si hay procesos asociados, muestra un mensaje indicando que se debe seleccionar alguno.
             */
            if( is_array( $arrProceso ) && $codProc == 0 )
            {
			?>
			<input name="crearExpediente" type="button" class="botones_funcion" value=" Crear Expediente " onClick="alert('Por favor seleccione un proceso.'); document.TipoDocu.codProc.focus();">
            <?php
            }
			elseif($expe!=""){
				if($inc==0)echo "Existe el siguiente Expediente ".$expe." para este item: ".$par."";
				elseif($inc==1 and $expe!="")echo "Existe el siguiente Expediente ".$expe." para este item: ".$par."";
				else{
					 ?>
					<input name="crearExpediente" type=submit class="botones_funcion" value=" Crear Expediente ">
					<?
				}
			}
			            else
            {
            print $expe;
            	?>
            			<input name="crearExpediente" type=submit class="botones_funcion" value=" Crear Expediente ">
			<?
            }
        }
        else
        {
			?>
			<input name="Actualizar" type=submit class="botones_funcion" value=" Confirmacion Creacion de Expediente">
			<?
		}
	}
	

	?>
	</center></TD>
	<td width="33%" class="listado2" height="25">
	<center><span bgcolor="#CCCCCC" class="titulos2"><a href='crearexpediente.php?<?=$phpsession?>&krd=<?=$krd?>&<?="fechaf=$fechah&carpeta=8&nomcarpeta=Expedientes&orno=1&adodb_next_page=1"; ?>' target='mainFrame' class="menu_princ"><b>REGRESAR </a></span></center></TD>
	</tr>
</table><script>
function borrarArchivo(anexo,linkarch){
	if (confirm('Esta seguro de borrar este Registro ?'))
	{
		nombreventana="ventanaBorrarR1";
		url="tipificar_documentos_transacciones.php?borrar=1&usua=<?=$krd?>&codusua=<?=$codusua?>&coddepe=<?=$coddepe?>&nurad=<?=$nurad?>&codiTRDEli="+anexo+"&linkarchivo="+linkarch;
		window.open(url,nombreventana,'height=250,width=300');
	}
return;
}
function procModificar()
{
if (document.TipoDocu.tdoc.value != 0 &&  document.TipoDocu.codserie.value != 0 &&  document.TipoDocu.tsub.value != 0)
  {
  <?php
      $sql = "SELECT RADI_NUME_RADI
					FROM SGD_RDF_RETDOCF
					WHERE RADI_NUME_RADI = '$nurad'
				    AND  DEPE_CODI =  '$coddepe'";
		$rs=$db->conn->Execute($sql);
		$radiNumero = $rs->fields["RADI_NUME_RADI"];
		if ($radiNumero !='') {
			?>
			if (confirm('Esta Seguro de Modificar el Registro de su Dependencia ?'))
				{
					nombreventana="ventanaModiR1";
					url="tipificar_documentos_transacciones.php?modificar=1&usua=<?=$krd?>&codusua=<?=$codusua?>&tdoc=<?=$tdoc?>&tsub=<?=$tsub?>&codserie=<?=$codserie?>&coddepe=<?=$coddepe?>&nurad=<?=$nurad?>";
					window.open(url,nombreventana,'height=200,width=300');
				}
			<?php
	 		}else
			{
			?>
			alert("No existe Registro para Modificar ");
			<?php
			}
       ?>
     }
   else
   {
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
</html>
