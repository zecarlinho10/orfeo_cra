<?php
$krdOld = $krd;
session_start();
error_reporting(0);
$ruta_raiz = "..";
$carpetaOld = $carpeta;
$tipoCarpOld = $tipo_carp;
if (! $tipoCarpOld)
    $tipoCarpOld = $tipo_carpt;
if (! $krd)
    $krd = $krdOld;
    
    // faltaba parametro CAPTURAR valores 05/01/2016
$checkValue = $_POST["checkValue"];
$valor_unit = $_POST["valor_unit"];
$envio_peso = $_POST["envio_peso"];
$Calcular = $_POST["Calcular"];
$reg_envio = $_POST["reg_envio"];
$empresa_envio = $_POST["empresa_envio"];
$radicados = $_POST["radicados"];
$whereFiltro = $_GET["whereFiltro"];

$observaciones = $_POST["observaciones"];
$nombre_us = $_POST["nombre_us"];
$direccion_us = $_POST["direccion_us"];
$destino = $_POST["destino"];
$departamento_us = $_POST["departamento_us"];
$pais_us = $_POST["pais_us"];
$dir_codigo = $_POST["dir_codigo"];

$ra_asun = $_POST["ra_asun"];

$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];

$codigo_envio = $_POST["codigo_envio"];

//

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
if (! $db)
    $db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
// $db->conn->debug=true;
if (! $_SESSION['dependencia'])
    include_once "../rec_session.php";
?>
<html>
<head>
<title>Untitled Document</title>
<link href="../estilos/orfeo.css" rel="stylesheet" type="text/css">
<?php include_once "../htmlheader.inc.php"; ?>
<?php

if (! $radicados) {
    $radicados = implode(',', array_keys($checkValue));
    $whereFiltro = "b.radi_nume_radi in(" . $radicados . ")";
}
$procradi = $radicados;
?>
<script>
function back1()
{	history.go(-1);	}

function generar_envio()
{	if (document.forma.elements['valor_unit'].value == '' || document.forma.elements['valor_unit'].value == "")
	{	alert('Seleccione Empresa de Envio Y digite el peso del mismo');
			return false;
 }
}

</script>

</head>
<body>
	<span class=etexto>
		<center>
			<a class="vinculos"
				href='../envios/cuerpoEnvioNormal.php?<?=session_name()."=".session_id()."&krd=$krd&fecha_h=$fechah&dep_sel=$dep_sel&estado_sal=$estado_sal&estado_sal_max=$estado_sal_max&nomcarpeta=$nomcarpeta"?>'>Devolver
				a Listado</a>
		</center>
	</span>
<?
/**
 * INICIO GRABACION DE DATOS *
 * *
 */
?>
<center>
		<table width="100%" class="borde_tab">
			<tr class="titulos2">
				<td align="center">ENVIO DE DOCUMENTOS</td>
			</tr>
		</table>
	</center>
	<form name='forma'
		action='enviamass.php?<?=session_name()."=".session_id()."&krd=$krd&fecha_h=$fechah&dep_sel=$dep_sel&whereFiltro=$whereFiltro&estado_sal=$estado_sal&estado_sal_max=$estado_sal_max&no_planilla=$no_planilla&codigo_envio=$codigo_envio&verrad_sal=$verrad_sal&nomcarpeta=$nomcarpeta"?>'
		method="post">
		<input type='hidden' name='radicados' value='<?= $radicados ?>'>
<?
include_once ("$ruta_raiz/include/query/envios/queryEnvia.php");

include_once ("$ruta_raiz/include/query/envios/cuerpoEnvioNormal.php");

// FIN MODIFICACION. JQ/CRA 2008
?>
   
  
<table border=4 width=100% class=borde_tab>
			<!--DWLayoutTable-->

<?php
error_reporting(0);
include "$ruta_raiz/config.php";
require_once ("$ruta_raiz/class_control/ControlAplIntegrada.php");
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
// HLP $isql = "SELECT rtrim(a.ANEX_RADI_NUME),a.ANEX_NOMB_ARCHIVO,a.ANEX_DESC,a.SGD_REM_DESTINO,a.SGD_DIR_TIPO, ".
$isql = "SELECT b.radi_nume_radi," . $radi_nume_deri . " AS RADI_NUME_DERI, b.RA_ASUN
		FROM RADICADO b WHERE " . $whereFiltro . " " . $comb_salida . "";
// $db->conn->debug = true;
$db = new ConnectionHandler("$ruta_raiz");
$db->conn->BeginTrans();
if (isset($_POST["reg_envio"])) {
    $objCtrlAplInt = new ControlAplIntegrada($db);
}
if (! defined('ADODB_FETCH_ASSOC')) {
    define('ADODB_FETCH_ASSOC', 2);
}
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$ADODB_COUNTRECS = true;
$rsEnviar = $db->query($isql);
$ADODB_COUNTRECS = false;
$igual_destino = "si";
$tmp = explode('-', $_SESSION['cod_local']);
$tmp_idcl = $tmp[0];
$tmp_idpl = $tmp[1];
$tmp_iddl = $tmp_idpl . '-' . $tmp[2] * 1;
$tmp_idml = $tmp_iddl . '-' . $tmp[3] * 1;
unset($tmp);

// echo $isql;

if ($rsEnviar && ! $rsEnviar->EOF) {
    
    $pCodDepAnt = "";
    $pCodMunAnt = "";
    
    // --
    
    // --
    
    if (! isset($_POST["reg_envio"])) {
        while (! $rsEnviar->EOF) {
            $verrad_sal = $rsEnviar->fields["RADI_NUME_RADI"]; // OK
            $verrad = $rsEnviar->fields["RADI_NUME_RADI"]; // OK
            $verrad_padre = $rsEnviar->fields["RADI_NUME_DERI"];
            $sgd_dir_tipo = $rsEnviar->fields["SGD_DIR_TIPO"];
            $rem_destino = $rsEnviar->fields["SGD_DIR_TIPO"];
            $anex_radi_nume = $rsEnviar->fields["RADI_NUME_RADI"];
            $ra_asun = $rsEnviar->fields["RA_ASUN"];
            $dep_radicado = substr($verrad_sal, 4, 3);
            $ano_radicado = substr($verrad_sal, 0, 4);
            $carp_codi = substr($dep_radicado, 0, 2);
            $radi_path_sal = "/$ano_radicado/$dep_radicado/docs/$ref_pdf";
            
            if (substr($rem_destino, 0, 1) == "7")
                $anex_radi_nume = $verrad_sal;
            $nurad = $anex_radi_nume;
            $verrad = $anex_radi_nume;
            
            $ruta_raiz = "..";
            // include "../ver_datosrad.php";
            
            if ($radicadopadre)
                $radicado = $radicadopadre;
            if ($nurad)
                $radicado = $nurad;
            
            include "../clasesComunes/datosDest.php";
            
            $dat = new DATOSDEST($db, $radicado, $espcodi, $sgd_dir_tipo, $rem_destino);
            $pCodDep = $dat->codep_us;
            $pCodMun = $dat->muni_us;
            $pNombre = $dat->nombre_us;
            $pPriApe = $dat->prim_apel_us;
            $pSegApe = $dat->seg_apel_us;
            $nombre_us = substr($pNombre . " " . $pPriApe . " " . $pSegApe, 0, 33);
            $direccion_us = $dat->direccion_us;
            if ($pCodDepAnt == "")
                $pCodDepAnt = $pCodDep;
            if ($pCodMunAnt == "")
                $pCodMunAnt = $pCodMun;
            if (! $rem_destino)
                $rem_destino = 1;
            $sgd_dir_tipo = 1;
            echo "<input type=hidden name=$espcodi value='$espcodi'>";
            $ruta_raiz = "..";
            include "../jh_class/funciones_sgd.php";
            $a = new LOCALIZACION($pCodDep, $pCodMun, $db);
            
            $departamento_us = $a->departamento;
            $destino = $a->municipio;
            $pais_us = $a->GET_NOMBRE_PAIS($dat->idpais, $db);
            $dir_codigo = $dat->documento_us;
            // include "../envios/listaEnvio.php";
            $cantidadDestinos ++;
            $rsEnviar->MoveNext();
        }
        
        // SE MODIFICA PARA TENER EN CUENTA LOS ENVIOS INTERNACIONALES. JQ-CRA 200808
        
        ?>	
<table border=0 width=100% class=borde_tab>
				<!--DWLayoutTable-->
				<tr class='titulos2'>
					<td>Empresa De envio</td>
					<td>Peso(Gr)</td>
					<td>U.Medida</td>
					<td colspan="2">Valor Total C/U</td>

				</tr>
				<tr class=timparr>
					<td height="26" align="center"><font size=2><B>
    <?php
        // $db->conn->debug=true;
        $rsEnv = $db->conn->query($sql);
        // $db->conn->debug=true;
        print $rsEnv->GetMenu2("empresa_envio", "$empresa_envio", "0:-- Seleccione --", false, "", "class='select'");
        ?>
	</td>
					<td><input type='text' name='envio_peso' id='envio_peso'
						value='<?=$envio_peso?>' size="6" class="tex_area"></td>
		
<?
        
        if (! empty($_POST['Calcular']) && ($_POST['Calcular'] == "Calcular")) {
            
            $radtodos = substr($radicados, 0, 14);
            $sql = "select d.radi_nume_radi, d.muni_codi, d.dpto_codi, d.id_pais, m.muni_codi, 
  m.locales from sgd_dir_drecciones d, municipio m
 		where d.radi_nume_radi= $verrad_sal and m.muni_codi=d.muni_codi and m.dpto_codi=d.dpto_codi";
            // $db->conn->debug=true;
            $rsEnvio = $db->query($sql);
            while (! $rsEnvio->EOF) {
                $muni = $rsEnvio->fields["MUNI_CODI"];
                $depto = $rsEnvio->fields["DPTO_CODI"];
                $pais = $rsEnvio->fields["ID_PAIS"];
                $local = $rsEnvio->fields["LOCALES"];
                
                // nacional 1 , internacional 2
                if ($pais == 170) {
                    $dest_envio = 1;
                } else
                    $dest_envio = 2;
                
                $isql = "select sgd_fenv_codigo, sgd_clta_pesdes, sgd_clta_peshast, sgd_tar_codigo, SGD_CLTA_DESCRIP from sgd_clta_clstarif
 		where sgd_fenv_codigo=$empresa_envio and sgd_clta_codser=$dest_envio
       	and '" . trim($_POST['envio_peso']) . "'>= sgd_clta_pesdes
       	and '" . trim($_POST['envio_peso']) . "' <= sgd_clta_peshast";
                // $db->conn->debug=true;
                $rstar = $db->query($isql);
                $codtar = $rstar->fields["SGD_TAR_CODIGO"];
                $valor_gr = $rstar->fields["SGD_CLTA_DESCRIP"];
                if ($rstar != "")
                    $iisql = "select sgd_tar_valenv1, sgd_tar_valenv2, sgd_tar_valenv1g1, sgd_tar_valenv2g2 from sgd_tar_tarifas
			where  sgd_fenv_codigo=$empresa_envio and sgd_clta_codser=$dest_envio
			and sgd_tar_codigo=$codtar";
                    // $db->conn->debug=true;
                $rsval = $db->query($iisql);
                if ($local == 1)
                    $valor_unit = $rsval->fields["SGD_TAR_VALENV1"];
                
                elseif ($depto != 11 && $pais == 170)
                    $valor_unit = $rsval->fields["SGD_TAR_VALENV2"];
                
                elseif (substr($pais, - 1) == 0 && $pais != 170)
                    $valor_unit = $rsval->fields["SGD_TAR_VALENV1G1"];
                
                elseif (substr($pais, - 1) != 0)
                    $valor_unit = $rsval->fields["SGD_TAR_VALENV2G2"];
                
                $rsEnvio->MoveNext();
            }
        }
        
        ?>
	<td><input type="text" name="valor_gr" id="valor_gr"
						value='<?=$valor_gr?>' size="30" disabled class="tex_area"></td>
					<td align="center"><input type="text" name="valor_unit"
						id="valor_unit" value="<?=$valor_unit?>" readonly></td>
					<td><input name="Calcular" type="submit" class="botones"
						id="envia22" value="Calcular">&nbsp;&nbsp;</td>
				</tr>
			</table>

			<table border=4 width=100% class=borde_tab>
				<tr class='titulos2'>
					<td valign="top">Radicado</td>
					<td valign="top">Radicado Padre</td>
					<td valign="top">Destinatario</td>
					<td valign="top">Direccion</td>
					<td valign="top">Municipio</td>
					<td valign="top">Depto</td>
					<td valign="top">Pa&iacute;s</td>

				</tr>
<?php
        
        include "../envios/listaEnviomass.php";
        
        ?>

	
	<tr>
					<td colspan="7">
						<table class="borde_tab" width="100%" border="3">
							<tr>
								<td height="21" valign="top"><font size=2><center>
											<input name="reg_envio" type="submit"
												value="GENERAR REGISTRO DE ENVIO"
												id="GENERAR REGISTRO DE ENVIO"
												onClick="return generar_envio();" class="botones_largo"> <input
												name="masiva" value="<?=$masiva?>" type="hidden">
										</center></font></td>
							</tr>
						</table>
					</td>
				</tr>
<?php
    } else {
        if (! $k) {
            $rsEnviar->MoveFirst();
            while (! $rsEnviar->EOF) {
                $verrad_sal = $rsEnviar->fields["RADI_NUME_RADI"];
                $verrad_padre = $rsEnviar->fields["RADI_NUME_DERI"];
                $rem_destino = $rsEnviar->fields["SGD_DIR_TIPO"];
                $campos["P_RAD_E"] = $verrad_sal;
                $estQueryAdd = $objCtrlAplInt->queryAdds($verrad_sal, $campos, $MODULO_ENVIOS);
                
                if ($estQueryAdd == 0) {
                    $db->conn->RollbackTrans();
                    die();
                }
                
                if (! $rem_destino)
                    $rem_destino = 1;
                if (! trim($rem_destino))
                    $isql_w = " sgd_dir_tipo is null ";
                else
                    $isql_w = " sgd_dir_tipo='$rem_destino' ";
                
                if ($rsUpdate)
                    $k ++;
                if (! $codigo_envio) { // include_once("$ruta_raiz/include/query/envios/queryEnvia.php");
                    $sql_sgd_renv_codigo = "select SGD_RENV_CODIGO FROM SGD_RENV_REGENVIO ORDER BY SGD_RENV_CODIGO DESC ";
                    $rsRegenvio = $db->conn->SelectLimit($sql_sgd_renv_codigo, 10);
                    $nextval = $rsRegenvio->fields["SGD_RENV_CODIGO"];
                    $nextval ++;
                    $codigo_envio = $nextval;
                    $radi_nume_grupo = $verrad_sal;
                    $isql = "update RADICADO set SGD_EANU_CODIGO=9 where RADI_NUME_RADI =$verrad_sal";
                    $rsUpdate = $db->query($isql);
                } else {
                    $nextval = $codigo_envio;
                    $valor_unit = 0;
                }
                $dir_tipo = $rem_destino;
                $destino = ($_POST['destino1']);
                
                // $valor_unit=;
                
                $isql = "INSERT INTO SGD_RENV_REGENVIO(USUA_DOC ,SGD_RENV_CODIGO ,SGD_FENV_CODIGO
							,SGD_RENV_FECH ,RADI_NUME_SAL ,SGD_RENV_DESTINO ,SGD_RENV_TELEFONO
							,SGD_RENV_MAIL ,SGD_RENV_PESO ,SGD_RENV_VALOR ,SGD_RENV_CERTIFICADO
							,SGD_RENV_ESTADO ,SGD_RENV_NOMBRE ,SGD_DIR_CODIGO ,DEPE_CODI
							,SGD_DIR_TIPO ,RADI_NUME_GRUPO ,SGD_RENV_PLANILLA ,SGD_RENV_DIR
							,SGD_RENV_DEPTO, SGD_RENV_MPIO, SGD_RENV_PAIS, SGD_RENV_OBSERVA ,SGD_RENV_CANTIDAD)
							VALUES('$usua_doc' ,'$nextval' ,'$empresa_envio' ," . $db->conn->OffsetDate(0, $db->conn->sysTimeStamp) . "
									, '$verrad_sal', '$destino', '$telefono', '$mail', '$envio_peso', '$valor_unit', 0, 1, '$nombre_us'
									, '$dir_codigo', '$dependencia', '$dir_tipo', '$radi_nume_grupo', '$no_planilla', '$direccion_us'
									, '$departamento_us' ,'$destino', '$pais_us', '$observaciones',1 )";
                // $db->conn->debug=true;
                
                $rsInsert = $db->query($isql);
                $rsEnviar->MoveNext();
            }
            $db->conn->CommitTrans();
        }
        ?>
			  <table style="width: 100%; border: :4;" class="borde_tab">

					<tr class='titulos2'>
						<td valign="top">Radicado</td>
						<td valign="top">Radicado Padre</td>
						<!--<td valign="top" >Destinatario</td>-->
						<!--<td valign="top" >Direccion</td>-->
						<!--<td valign="top" >Municipio</td>-->
						<!--<td valign="top" >Depto</td>-->
						<!--<td valign="top" >Pa&iacute;s</td>-->

					</tr>
	
	<? 
// $k
        
        include "../envios/listaEnviomass.php";
        echo "<b><span class=listado2>Registro de Envio Generado $valor_unit</span> </b><br><br>";
    } // FIN else no reg_envio
} else {
    echo "<hr><table class=borde_tab><tr class=titulosError><td>NO PUEDE SELECCIONAR VARIOS DOCUMENTOS PARA UN MISMO DESTINO CON CIUDAD Y/O DEPARTAMENTO DIFERENTE</td></tr></table><hr>";
	}



?>
</table>
				</form>
				</script>

</body>
</html>