<?php
/*
ini_set('display_errors', 'Off');
ini_set('display_startup_errors', 'Off');
error_reporting(0);
*/
/**
 *
 * @author Jairo Losada <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright SIIM2 Models are the data definition of SIIM2 Information System
 *            Copyright (C) 2013 Infometrika Ltda.
 *           
 *            This program is free software: you can redistribute it and/or modify
 *            it under the terms of the GNU Affero General Public License as published by
 *            the Free Software Foundation, either version 3 of the License, or
 *            any later version.
 *           
 *            This program is distributed in the hope that it will be useful,
 *            but WITHOUT ANY WARRANTY; without even the implied warranty of
 *            MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *            GNU Affero General Public License for more details.
 *           
 *            You should have received a copy of the GNU Affero General Public License
 *            along with this program. If not, see <http://www.gnu.org/licenses/>.
 *           
 */
session_start();
define('MEMORANDO', 3);
define('SIIM2_RECEPCION', 10);

// VARIABLE INCREMENTAL PARA CONTROLAR LOS CAMPOS DE LOS USUARIOS
unset($_SESSION['INCREMENTAL1']);
$_SESSION['INCREMENTAL1'] = 0;

$ruta_raiz = "..";
if (! $_SESSION['dependencia'])
    header("Location: $ruta_raiz/cerrar_session.php");

foreach ($_GET as $key => $valor)
    ${$key} = $valor;
foreach ($_POST as $key => $valor)
    ${$key} = $valor;

/**
 * Fin variables de session de Radicacion de Mail.
 * *
 */
include_once ("$ruta_raiz/include/db/ConnectionHandler.php");
include_once ("$ruta_raiz/include/tx/usuario.php");
include_once ("$ruta_raiz/config.php");

$db = new ConnectionHandler("$ruta_raiz");
$usuario = new Usuario($db);

$showtable = 'hide';
$hidetable = '';
$modificar = 'hide';
$radMail = $_GET["radMail"];
$ddate = date('d');
$mdate = date('m');
$adate = date('Y');
$nurad = trim($nurad);
$hora = date('H:i:s');
$fechaf = $date . $mdate . $adate . $hora;
$dependencia = $_SESSION["dependencia"];
$ADODB_COUNTRECS = true;
$fecha_gen_doc = date("d-m-Y");
$coddepe = $dependencia;
// valor necesario para crear enlaces de los distintos elementos
// como el sticker
$idsession = session_id(); // valor necesario para crear enlaces

//echo "usuario:" . $_SESSION["codusuario"];

$_TIPO_INFORMADO = 1;
$_enable_1 = false;
$_enable_2 = false;
$_name_2 = "Empresas";
$_name_6 = "Usuario";
$_show_type_doc = true;

// CONFIGURACIONES ESPECIFICAS PARA METRO VIVIENDA
//borrro esto carlinho
/*
if ($_SESSION['entidad'] == "METROVIVIENDA") {
    $_TIPO_INFORMADO = 1;
    $_enable_1 = false;
    $_enable_2 = false;
    $_name_2 = "Empresas";
    $_name_6 = "Usuario SIIM2";
    
    // el tipo documental solo lo muestra si es un radicado de Entrada
    if ($ent == 2) {
        $_show_type_doc = true;
    } else {
        $_show_type_doc = false;
    }
}
*/
// CONFIGURACIONES ESPECIFICAS PARA CRA
//borrro if carlinho

//if ($_SESSION['entidad'] == "CRA") {
    $_TIPO_INFORMADO = 2;
    $_enable_1 = true;
    $_enable_2 = true;
    $_name_2 = "Otras Empresas";
    $_name_6 = "Usuario";
    // el tipo documental solo lo muestra si es un radicado de Entrada
    if ($ent == 2) {
        $_show_type_doc = true;
    } else {
        $_show_type_doc = false;
    }
//}

// Mostrar el tipo de radicacion que se esta realizando
$selTipoRad = "select
                  sgd_trad_codigo,
                  sgd_trad_descr,
                  sgd_trad_icono,
                  sgd_trad_genradsal
                from
                  sgd_trad_tiporad
                where sgd_trad_codigo = $ent";

$rs = $db->conn->query($selTipoRad);

if (!empty($rs) &&  !$rs->EOF) {
    $nomEntidad = $rs->fields["SGD_TRAD_DESCR"];
}

$med = (!empty($_GET["tipoMedio"]) && $_GET["tipoMedio"] == "eMail") ? 4 : null;

if ($ent == MEMORANDO) {
    $usuario_selected = 'selected';
    $med = SIIM2_RECEPCION;
} else {
    $ciudadano_selected = 'selected';
}

if ($rad0) {
    $javascriptCapDatos = 'datorad=0';
} else 
    if ($rad1) {
        $javascriptCapDatos = 'datorad=1';
    } else 
        if ($rad2) {
            $javascriptCapDatos = 'datorad=2';
        }

// CARGAR INFORMACION SI SE TRAE DE UN ANEXO O COPIA DE DATOS
if ($radicadopadre) {
    
    $query = "SELECT
                a.*
              FROM
                RADICADO A
              WHERE
                A.RADI_NUME_RADI = $radicadopadre";
    
    $rs = $db->conn->query($query);
    $asu="";
    if (!empty($rs) && !$rs->EOF) {
        $asu = $rs->fields["RA_ASUN"];
        $ane = $rs->fields["RADI_DESC_ANEX"];
        $cuentai = $rs->fields["RADI_CUENTAI"];
        $tdoc = $rs->fields["TDOC_CODI"];
        $med = $rs->fields["MREC_CODI"];
        $coddepe = $rs->fields["RADI_DEPE_ACTU"];
        $codusuarioActu = $rs->fields["RADI_USUA_RADI"];
        $radi_fecha = $rs->fields["RADI_FECH_RADI"];
        $guia = $rs->fields["RADI_NUME_GUIA"];
    }
    
    // Filtro por el tipo de usuario
    $result = $usuario->usuarioPorRadicado($radicadopadre);
    
    if ($result) {
        $showUsers = $usuario->resRadicadoHtml(true);
        $hidetable = '';
        $modificar = 'hide';
        $showtable = '';
    }
}

// CARGAR INFORMACION SI SE ENVIA NUMERO DE RADICADO PARA MODIFICAR

if ($nurad) {
    $query = "SELECT
                a.*
              FROM
                RADICADO A
              WHERE
                A.RADI_NUME_RADI = $nurad";
    
    $rs = $db->conn->query($query);
    
    if (!empty($rs) && !$rs->EOF) {
        $asu = $rs->fields["RA_ASUN"];
        //06/04/2018 CARLOS RICAURTE
        //PARA QUE NO ANEXE PADRE 
        if ($rad1) {
          $radicadopadre="";
        }
        else{
          $radicadopadre = $rs->fields["RADI_NUME_DERI"];
        }
        
        $ane = $rs->fields["RADI_DESC_ANEX"];
        $cuentai = $rs->fields["RADI_CUENTAI"];
        $tdoc = $rs->fields["TDOC_CODI"];
        $med = $rs->fields["MREC_CODI"];
        $coddepe = $rs->fields["RADI_DEPE_ACTU"];
        $codusuarioActu = $rs->fields["RADI_USUA_RADI"];
        $radi_fecha = $rs->fields["RADI_FECH_RADI"];
        $fecha_gen_doc = $rs->fields["RADI_FECH_OFIC"];
        $guia = $rs->fields["RADI_NUME_GUIA"];
        $numFolio = $rs->fields["RADI_NUME_FOLIO"];
        $numAnexo = $rs->fields["RADI_NUME_ANEXO"];
        $esta_fisico = $rs->fields["ESTA_FISICO"];
    }
    
    $date1 = date_create($radi_fecha);
    
    list ($adate, $mdate, $ddate) = explode('-', date_format($date1, 'Y-m-d'));
    list ($adate1, $mdate1, $ddate1) = explode('-', substr($fecha_gen_doc, 0, 10));
    $fecha_gen_doc = "$ddate1-$mdate1-$adate1";
    
    $ent = substr($nurad, - 1);
    
    // Filtro por el tipo de usuario
    $result = $usuario->usuarioPorRadicado($nurad);
    
    if ($result) {
        $showUsers = $usuario->resRadicadoHtml();
        $hidetable = 'hide';
        $modificar = '';
        $showtable = '';
    }
    
    $varEnvio = session_name() . "=" . session_id() . "&nurad=$nurad&ent=$ent";
    $senddata = "<input name='nurad' value='$nurad' type=hidden>";
    $senddata .= "<input name='idCodigo' value='$nurad' type=hidden>";
}
//5/03/2018
//CARLOS RICAURTE
if ($ent == 2) {
    $query = "SELECT " . $db->conn->Concat("d.DEPE_CODI", "'-'", "d.DEPE_NOMB") . ", d.DEPE_CODI
              FROM DEPENDENCIA d
              WHERE d.depe_estado = 1
              ORDER BY d.DEPE_CODI, d.DEPE_NOMB";
} else {
    $query = "SELECT  " . $db->conn->Concat("d.DEPE_CODI", "'-'", "d.DEPE_NOMB") . ", d.DEPE_CODI
          FROM DEPENDENCIA d
              WHERE d.depe_estado = 1
                and d.depe_codi   = $dependencia
          ORDER BY
            d.DEPE_CODI, d.DEPE_NOMB";
}

$rs = $db->conn->query($query);

if ($_TIPO_INFORMADO == 1) {
    $depselect = $rs->GetMenu2("coddepe", $coddepe, "0:-- Seleccione una Dependencia --", false, false, "class='select'");
} else 
    if ($_TIPO_INFORMADO == 2) {
        $depselect = $rs->GetMenu2("coddepe", $coddepe, false, false, "class='select'");
    }

$queryData = "SELECT " . $db->conn->Concat("d.DEPE_CODI", "'-'", "d.DEPE_NOMB") . ", d.DEPE_CODI
                FROM
                DEPENDENCIA d 
		where depe_estado = 1 ";

$rs = $db->conn->query($queryData);

if ($_TIPO_INFORMADO == 1) {
    $depselectInf = $rs->GetMenu2("coddepe", $coddepe, "0:-- Seleccione una Dependencia --", false, false, "class='form-control custom-scroll' id='informar'");
} else 
    if ($_TIPO_INFORMADO == 2) {
        $depselectInf = $rs->GetMenu2("coddepe", $coddepe, "", false, false, "class='form-control custom-scroll' multiple='multiple' id='informar' style='height: 15%;' ");
    }

$query = "SELECT
                MREC_DESC, MREC_CODI
               FROM MEDIO_RECEPCION
               WHERE MREC_CODI <> 0
               ORDER BY MREC_CODI";

$rs = $db->conn->query($query);

$medioRec = $rs->GetMenu2("med", $med, '', false, "", "class='select'");

$query = "SELECT
                  SGD_TPR_DESCRIP
                  ,SGD_TPR_CODIGO
                FROM
                  SGD_TPR_TPDCUMENTO
                WHERE
                  SGD_TPR_TP$ent     ='1'
                  and SGD_TPR_RADICA ='1'
                  ORDER BY SGD_TPR_DESCRIP ";

$opcMenu = "0:-- Seleccione un tipo --";
$fechaHoy = date("Y-m-d");
$fechaHoy = $fechaHoy . "";
$rs = $db->conn->query($query);
$tipoDoc = $rs->GetMenu2("tdoc", 0, "$opcMenu", false, "", "title='Seleccione el tipo documental' class='ecajasfecha'");
//$tipoDoc = $rs->GetMenu2("tdoc", $tdoc, "$opcMenu", false, "", "title='Seleccione el tipo documental' class='ecajasfecha'");
?>

<html>
<head>
  <?php include_once("$ruta_raiz/htmlheader.inc.php")?>
<link rel="stylesheet" href="../tooltips/jquery-ui.css">
<!-- Al colocar esto hace confilcto <script src="../tooltips/jquery-1.10.2.js"></script>-->
<script src="../tooltips/jquery-ui.js"></script>
<link rel="stylesheet" href="../tooltips/tool.css">
<script src="../tooltips/tool.js"></script>
</head>

<body>
	<script type="text/javascript">

    $( document ).ready(function() {

        $( "body" ).on( "click", ".attrtext", function(){
            $('#asu').val($(this).attr('attrtext'));
        });
    });


$(function(){
	$.ajaxSetup({
		beforeSend:function(){
		$('.loading').fadeIn(200);
		},
		complete:function(){
		$('.loading').delay(200).fadeOut(200);
		}});
	});
</script>
	<div class="loading"
		style="position: fixed; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.5) none repeat scroll 0% 0%; text-align: center; display: none; z-index: 1000">
		<img src="../img/preload.gif" alt="loading"
			style="vertical-align: middle; padding-top: 10%" /> 
			<div
			class="text-center"><progress>Radicando</progress>
			<span class="alert-success">
				<strong>procesando</strong>
			</span></div>
	</div>
	<div style="padding-top: 5px; padding-left: 25px">
		<h4>* Campos Obligatorios</h4>
	</div>
	<form method="post" name="formulario" id="formulario">

		<input type=hidden name='ent' value='<?=$ent?>'>

		<div id="content" style="opacity: 1;">
			<div class="row">
				<div class="col-lg-3">
					<h1 class="page-title txt-color-blueDark">
              Radicaci&oacute;n <?=$nomEntidad?>
              <?=$tRadicacionDesc?>
              (Dep <?=$dependencia?>)
              <p>
							<small id="idrad"> <?=$nurad?> <?=$encabezado?></small>
						</p>
					</h1>
				</div>

				<div class="col-lg-6 smart-form">
				<?php if(!empty($radicadopadre)) {?>
				<input type="hidden" name="radicadopadre" value="<?php echo $radicadopadre;?>" />
				<?php }
				if(!empty($rad0)) {?>
                  <input type="hidden" name="rad0" value="<?php echo $rad0;?>" />
				 <?php }
				 if(!empty($rad1)) {?>
	                   <input type="hidden" name="rad1" value="<?php echo $rad1?>" />
				  <?php }
				if(!empty($rad2)) {?>
                   <input type="hidden" name="rad0" value="<?php echo $rad2;?>" />
                   <?php }?>
					<section class="col col-3">
						<label class="label"> DD / MM / AAAA </label>
						<h6> <?=$ddate?> / <?=$mdate?> / <?=$adate?> </h6>
					</section>

					<section class="col col-3">
						<label class="label"> Referencia </label> <label class="input"> <input
							id="cuentai"
							title="Coloque aquí un número de referencia de su comunicación"
							name="cuentai" type="text" maxlength="20"
							value='<?= $cuentai; ?>'>
						</label>
					</section>

					<section class="col col-3">
						<label class="label"> Gu&iacute;a </label> <label class="input"> <input
							type=text name='guia'
							title="Si tiene un número de guía digítelo." id='guia'
							value='<?=$guia?>' size=35>
						</label>
					</section>

					<section class="col col-3">
						<label class="label"> Fecha Doc. dd/mm/aaaa </label> <label
							class="input"> <i class="icon-append fa fa-calendar"></i> <input
							type="text" id="fecha_gen_doc" name="fecha_gen_doc"
							placeholder="Fecah de radicacion" value="<?=$fecha_gen_doc ?>">
						</label>
					</section>

				</div>

				<div id="showRadicar" class="col-lg-3 <?=$hidetable?>">
					<a data-toggle="modal" name='Submit3' value='Radicar'
						class="btn btn-primary btn-lg btn-block pull-right header-btn radicarNuevo">
						<i class="fa fa-circle-arrow-up fa-lg"></i> Radicar documento
					</a>
				</div>

				<div id="showModificar" class="col-lg-3 <?=$modificar?>">
					<a data-toggle="modal" id="modificaRad" name="Submit44"
						class="btn bg-color-greenDark txt-color-white btn-lg btn-block">
            Modificar <?=$nurad?>
            <?=$senddata?>
          </a> 
          <label id="sticker"> <a href="javascript:void(0);"
						onClick="window.open ('./stickerWeb/index.php?<?=$varEnvio?>&alineacion=Center','sticker<?=$nurad?>','menubar=0,resizable=0,scrollbars=0,width=450,height=180,toolbar=0,location=0');"
						class="btn btn-link">Sticker</a> 
            <a href="../verradicado.php?&recargartab=true&<?=$idsession?>&verrad=<?=$nurad?>#tabs-c" class="btn btn-link">Ver radicado</a>
					</label> 
          <label id="asociar"> <a href="javascript:void(0);"
						onClick="window.open ('../uploadFiles/uploadFileRadicado.php?busqRadicados=<?=$nurad?>&Buscar=Buscar&<?=$varEnvio?>&alineacion=Center','busqRadicados=<?=$nurad?>','menubar=0,resizable=0,scrollbars=0,width=550,height=280,toolbar=0,location=0');"
						class="btn btn-link">Asociar Imagen</a>
					</label>
          <label id="nuevo_radicado"> 
            <a href='NEW.php?<?=session_name()."=".trim(session_id())?>&dependencia=<?=$dependencia?>&ent=<?=$ent?>' class="btn btn-link">Nuevo radicado
            </a>
          </label>
				</div>

			</div>

			<div id="alertmessage"></div>

			<section id="widget-grid">

				<!-- row -->
				<div class="row">

					<!-- NEW WIDGET START -->
					<article class="col-lg-12">

						<div data-widget-editbutton="false" id="wid-id-0" role="widget">

							<!-- widget content -->
							<div class="col col-12">

								<labelclass"label"> * Tipo de Remitente Destinatario <br>
								&nbsp; Buscar usuario radicador </label>
								<section id="formsearch" class="form-inline smart-form">
									<section class="col col-1">
										<a id="idnuevo"
											title="Sólo si su destinatario no se encuentra en la búsqueda ingrese uno nuevo."
											href="javascript:void(0);" class="btn btn-sm btn-primary"><i
											class="fa fa-plus-circle"></i> Nuevo </a>
									</section>

									<section class="col col-2">
										<label class="select"> <select id="tipo_usuario"
											class="form-control input-sm">
												<option value='0' <?php echo $ciudadano_select?>>Ciudadano</option>
				<?php if ($_enable_1 == true){ ?> <option value='1'
													<?php echo $esp_select?>>ESP</option> <?php } ?>
				<?php if ($_enable_2 == true){ ?> <option value='2'
													<?php echo $entidad_selected?>> <?=$_name_2?> </option><?php } ?>
                              <option value='6'
													<?php echo $usuario_selected?>><?=$_name_6?></option>
										</select>
										</label>
									</section>

									<section class="col col-2">
										<label class="input"> <i class="icon-prepend fa fa-search"></i>
											<input type=text id='documento_us'
											class="required alphanumeric" placeholder="* Documento">
										</label>
									</section>

									<section class="col col-2">
										<label class="input"> <i class="icon-prepend fa fa-search"></i>
											<INPUT type=text id='nombre_us' value="" placeholder="Nombre">
										</label>
									</section>

									<section class="col col-2">
										<label class="input"> <i class="icon-prepend fa fa-search"></i>
											<input type=text id='telefono_us' value=""
											placeholder="Tel&eacute;fono" required>
										</label>
									</section>

									<section class="col col-2">
										<label class="input"> <i class="icon-prepend fa fa-search"></i>
											<input type="email" id='mail_us' value="" placeholder="Correo Electronico" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" required>
										</label>
									</section>

								</section>

								<!--Muestra Resultados de la busqueda-->
								<section id="showAnswer" class="col-lg-12 hide well">
									<ul id="resBusqueda" class="inbox-download-list">
									</ul>
								</section>
							
							</div>

							<section id="tableSection"
								class="well col-lg-12 smart-form <?=$showtable?>">
								<a id="buttonHide" class="btn pull-right btn-default btn-xs"
									href="javascript:void(0);">Informaci&oacute;n ...</a>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th style="width: 35px;"></th>
											<th>Tipo Documento</th>
											<th>Documento</th>
											<th>Nombres</th>
											<th>Apellidos</th>
											<th>Telefono</th>
											<th>Direcci&oacute;n</th>
											<th>Correo Electronico</th>
											<th>Dignatario</th>
											<th class="toogletd">Municipio</th>
											<th class="toogletd">Departamento</th>
											<th class="toogletd">Pais</th>
										</tr>
									</thead>
									<tbody id="tableshow"><?=$showUsers?></tbody>
								</table>
							</section>
						</div>
					</article>
					<!-- WIDGET END -->
				</div>
			</section>

			<div class="col-lg-12"></div>
			<br />

			<div class="well <?=$modificar?>" id="inforshow">
				<section class="smart-form col col-6">
					<legend>Informar a:</legend>

					<section>
						<label class="label"> Dependencia </label> <label class="textarea">
                      <?=$depselectInf?>
                  </label>
					</section>
<?php if($_TIPO_INFORMADO==1){ ?>
                <section>
						<label class="label"> Usuario </label> <label class="textarea"> <label
							class="textarea"> <select name="coddepe" multiple="multiple"
								class="form-control custom-scroll" id="informarUsuario">
									<option value="0">-- Seleccione un Usuario --</option>
							</select>
						</label>
						</label>
					</section>
<?php } ?>
                <section class="smart-form">

						<label class="label"> Usuarios Seleccionados para notificar </label>

						<div class="inline-group" id="showusers"></div>

						<div class="alert alert-block alert-success hide">
							<a class="close" data-dismiss="alert" href="#">×</a>
							<div class="inline-group" id="showresult"></div>
						</div>

					</section>

					<section>
						<label> <!-- Button trigger modal --> <a data-toggle="modal"
							id="accioninfousua"
							class="btn btn-success btn-sm header-btn hidden-mobile"><i
								class="fa fa-circle-arrow-up fa-lg"> </i> Informar </a>
						</label>
					</section>

				</section>



			</div>
			<br />
			<div class="well">
				<section class="smart-form">
					<section>
            <? if($ent==2){

              $sql_cont = "SELECT
                    sgd_msg_desc,
                    sgd_msg_codi,
                    sgd_msg_etiqueta
                FROM
                    sgd_msg_mensaje 
                WHERE sgd_msg_url='1'";
              $salida = $db->conn->query($sql_cont);
              $buttacc1="";
                while (!empty($salida) && !$salida->EOF) {
                    $i ++;
                    $checked = '';
                    $seleDesc = $salida->fields['SGD_MSG_DESC'];
                    $seleEtiq = $salida->fields['SGD_MSG_ETIQUETA'];
                    $buttacc1 .= "<button type='button' class='btn btn-default attrtext' attrtext='$seleDesc'> $seleEtiq </button>";
                    $salida->MoveNext();
                }
                echo $buttacc1;
            }
            ?>
						<label class="label"> * Asunto </label> 
            
            <label class="textarea"> 
              <textarea id="asu" name="asu" cols="70" rows="4"><?=$asu?></textarea>
						</label> 
            <label class="label"> Medio Recepci&oacute;n / Env&iacute;o 
            </label> 
            <label class="select"> <?=$medioRec?>
            </label> 
            <label class="label"> No. Paginas 
            </label> 
            <label
							class="input"> <input name="nofolios" id="nofolios" type="text"
							size="10" onkeypress="return justNumbers(event);" maxlength="4"
							value="<?=$numFolio?>">
						</label> 
            <label class="label"> No. Anexos 
            </label> 
            <label
							class="input"> <input name="noanexos" id="noanexos" type="text"
							size="10" onkeypress="return justNumbers(event);" maxlength="2"
							value="<?=$numAnexo?>">
						</label> 
            <label class="label"> Descripci&oacute;n Anexos 
            </label>
						<label class="input"> <input name="ane" id="ane" type="text"
							size="70" maxlength="200" value="<?=$ane?>">
						</label>
                  <?php if ($_SESSION["varEstaenfisico"] == 1){ ?>
                   <label class="label"> Fisico en archivo </label> 
                   <input
							       name="esta_fisico" id="esta_fisico" type="checkbox"
							     <?php if ($esta_fisico==1){echo " checked";} ?>>
                    <?php } ?>

		        <?php 
            if ($entidad=="CRA"){
              /*21/02/2018
              CARLOS RICAURTE
              Condicional para diferenciar si es memo y pone por defecto Confidencial
              */
              if ($ent == MEMORANDO) {
                  $checkeadoPublico="";
                  $checkeadoConfidencial="checked";
              } 
              else {
                  $checkeadoPublico="checked";
                  $checkeadoConfidencial="";
              }
              ?>
          		<label class="label"> Nivel de Seguridad:
          			<div>
          				<label> <input type="radio" name="nivelSeguridad" id="publico"
          					value="0" <?php echo $checkeadoPublico; ?> > Público.
          				</label>
          			</div>
          			<div>
          				<label> <input type="radio" name="nivelSeguridad"
          						id="confidencial" value="1" <?php echo $checkeadoConfidencial; ?> > Confidencial.
          				</label>
          			</div>
          		</label>
          		<?
            }
            ?>
                  <label class="label"> *Dependencia </label> <label
							class="select">
                      <?=$depselect?>
                  </label>
		<?php if ($_show_type_doc == true) { ?>
                  <label class="label"> Tipo Documental </label> <label
							class="select">
                      <?=$tipoDoc?>
          		  </label>
          		<?php }else{ ?>
          		<input type="hidden" value="null" name="tdoc">
          		<?php } ?>
              </section>
				</section>
			</div>

			<div class="row">
				<div class="col-lg-9"></div>
				<div id="copyradicar"></div>
			</div>

		</div>
    
	</form>



	<a id="skeleton" href="javascript:void(0);"
		onclick="window.open ('./stickerWeb/index.php?<?=$idsession?>&nurad=xxxxxx&ent=<?=$ent?>','stickerxxxxxx','menubar=0,resizable=0,scrollbars=0,width=450,height=180,toolbar=0,location=0');"
		class="btn btn-link hide">Sticker</a>

	<a id="skeleton8"
		href="../verradicado.php?&recargartab=true&<?=$idsession?>&verrad=xxxxxx#tabs-c"
		class="btn btn-link hide">Ver radicado</a>

	<a id="skeleton9" href="javascript:void(0);"
		onClick="window.open ('../uploadFiles/uploadFileRadicado.php?<?=$idsession?>&busqRadicados=xxxxxx&Buscar=Buscar&alineacion=Center','busqRadicados=xxxxxx','menubar=0,resizable=0,scrollbars=0,width=550,height=280,toolbar=0,location=0');"
		class="btn btn-link hide">Asociar Imagen</a>

	<label class='radio userinfo hide'> <input type="checkbox" checked
		name='radio[]' value=''><i></i>
	</label>

	<script type="text/javascript">
  
  function justNumbers(e)
  {
   var keynum = window.event ? window.event.keyCode : e.which;
   if ((keynum == 8) || (keynum == 46))
   return true;
   return /\d/.test(String.fromCharCode(keynum));
  }
  
  $(document).ready(function() {

      var ALLDATA;
      var INCREMENTAL1 = 0;
      var EJECUCION = false;

      // DO NOT REMOVE : GLOBAL FUNCTIONS!
      pageSetUp();

      $('#copyradicar').html($('#showRadicar').clone());

      //Datepicker muestra fecha
      $('#fecha_gen_doc').datepicker({
        dateFormat : 'dd-mm-yy',
        onSelect : function(selectedDate) {
          $('#date').datepicker('option', 'maxDate', selectedDate);
        }
      });


      //Mostar y ocultar comlumnas de la tabla
      //se filtra por la clase
      $("#buttonHide").click(function(){
          $('.toogletd').toggle();
      });


    /**
     * Generacion de eventos para los usuarios seleccionados
     * permitiendo cambiar la informacion antes de ser enviada al
     * servidor. Guardando de esta manera los datos del usuario con
     * las modificiaciones necesarias
    */
    $("body").on("click", '.fa-check',function(){
        $('label[name^="inp_"]').addClass('hide');
        $('div[name^="div_"]').removeClass('hide');
        var iddiv = $(this).parent().attr('name').substring(4);
        var tex_nuevo = $('label[name=inp_' + iddiv + ']').find('input').val();
        var div_nuevo = $('div[name=div_' + iddiv + ']').clone();
        $('div[name=div_' + iddiv + ']').text(tex_nuevo);
        $('div[name=div_' + iddiv + ']').append(div_nuevo.children());
    });

    /**
     * Generacion de eventos para los usuarios seleccionados
     * permitiendo cambiar la informacion antes de ser enviada al
     * servidor. Guardando de esta manera los datos del usuario con
     * las modificiaciones necesarias
     */
    $("body").on("click", '#informar', function(){

        var values = $(this).val();
        <?php if($_TIPO_INFORMADO==1){ ?>
                $.post( "./ajax_buscarUsuario.php", {searchUserInDep : values }).done(
                    function( data ) {
                        $('#informarUsuario').html(data[0]);
                    }
                );
        <?php }else if ($_TIPO_INFORMADO==2){?>
                $.post( "./ajax_buscarUsuario.php", {MsearchUserInDep : values }).done(
                    function( data ) {
        		  $('#showusers').html(data[0]);
                    }
                );
        <?php } ?>
      });


    /**
     * Generacion de eventos para los usuarios seleccionados
     * Selecciona los usuarios y los muestra para informar con
     * el radicado seleccionado.
     */
<?php if($_TIPO_INFORMADO==1){ ?>
    $("body").on("change", '#informarUsuario', function(){
        $('#informarUsuario :selected').each(function(i, selected){
            var newUser = $('.userinfo').last().clone();
            var text    = $(selected).text();
            var value   = $(selected).val();

            newUser.removeClass('hide');
            newUser.append(text);
            newUser.find('input').val( $('#informar').val() + '_' + value);

            $('#showusers').append(newUser);
        });
    });
<?php } ?>
    $("body").on("click", '#accioninfousua', function(){
        var text = [];
<?php if($_TIPO_INFORMADO==1){ ?>
        $('#showusers').find('input').each(function(index, value){
            text.push($(value).val());
        });
<?php }else if ($_TIPO_INFORMADO==2){ ?>
        $('#showusers').find('input:checked').each(function(index, value){
            text.push($(value).val());
        });
<?php } ?>
        var nurad = $('input[name="nurad"]').val();

        $.post("./ajax_informarUsuario.php", {addUser : text, radicado: nurad}).done(
            function( data ) {
                $('#showresult').text(data['true']);
                $('#showresult').parent().removeClass('hide')
            }
        );
    });

    $("body").on("click", '.fa-pencil',function(){
        var texto = $(this).parent().attr('name');
        $.each($('[name^="inp_' + texto + '"]'), function( index, value ) {
            $(value).removeClass('hide');
        });

        $.each($('[name^="div_' + texto + '"]'), function( index, value ) {
            $(value).addClass('hide');
        });
    });
<?php if($_TIPO_INFORMADO==1){ ?>
    $("body").on("change", '.informarusuarios',function(){
        var content = $(this).val();
        $('#showusers').append("<label class='radio'><input type='radio' name='radio-inline' checked=''><i></i>" +
                        content + "</label>" );
    });
<?php } ?>

    /**
     * Permite crear un nuevo usurio mostrando los campos vacios y
     * dejando que el usuario registre los datos de la persona que necesita.
     * las modificiaciones necesarias
     * Se envia en el codigo dos xx para identificar que es un usuario nuevo.
     * Cuando se carga el usuario de un radicado ya existente en cambio de las dos xx
     * se muestra el codigo con el cual se guardo.
    */

    $("#idnuevo").on("click",function(){
        var iddata = [{ "CODIGO": 'XX' + INCREMENTAL1,
                        "NOMBRE":"",
                        "TELEF":"",
                        "EMAIL":"",
                        "CEDULA":"",
                        "PAIS":"COLOMBIA",
                        "PAIS_CODIGO":"170",
                        "DEP":"D.C.",
                        "DEP_CODIGO":"11",
                        "MUNI":"BOGOTA",
                        "MUNI_CODIGO":"1",
                        "TIPO":$('#tipo_usuario').val(),
                        "APELLIDO":""}];

        $.post( "./ajax_buscarUsuario.php", {addUser : JSON.stringify(iddata)}).done(
            function( data ) {
                $('table').append(data[0]);
                $('#tableSection').removeClass('hide');
                $('.toogletd').hide()
            }
        );

        INCREMENTAL1++;

    });


    $("body").on("keyup", 'input[name$="muni"], input[name$="dep"], input[name$="pais"]', function(){
        if($(this).attr('autocomplete') === undefined){
            addAutocomple(this);
        };
    });


    function addAutocomple(element){
        var accion = $(element).attr('name').split("_")[4];
        var group  = $(element).attr('name').split("_")[3];
        $(element).autocomplete({
            source: function (request, response) {
                $.ajax({
                    url      : "./ajax_buscarDivipola.php",
                    dataType : "json",
                    type     : 'POST',
                    maxRows  : 12,
                    data: {
                        'action': accion,
                        'search': request.term,
                        'muni'  : $('input[name$="'+ group +'_muni"]').val(),
                        'dep'   : $('input[name$="'+ group +'_dep"]').val(),
                        'pais'  : $('input[name$="'+ group +'_pais"]').val()
                    },
                    success: function (data) {
                        response($.map( data, function( item ) {
                            return {
                                label: item.NOMBRE,
                                id   : item.CODIGO
                            }
                        }));
                    }

                });
            },
            minLength: 2,
            select   : function(event, ui) {
                var setempty    = $(this).attr('name').split("_")[4];
                var namehiddent = $(this).attr('name') + "_codigo";
                $("input[name=" + namehiddent + "]").val(ui.item.id);

                switch(setempty) {
                    case 'dep':
                        $('input[name$="'+ group +'_muni"]').attr('value', '');
                        break;

                    case 'pais':
                        $('input[name$="'+ group +'_muni"]').attr('value', '');
                        $('input[name$="'+ group +'_dep"]').attr('value', '');
                        break;

                }
            }
        });
    }


    //Deja en blanco los campos de busqueda al seleccionar
    //un nuevo usuario.
    $("#tipo_usuario").on('change',function(){
        $('#documento_us, #nombre_us, #telefono_us, #mail_us').val("").parent().removeClass('state-success state-error');
        $('#resBusqueda').empty();
        $('#showAnswer').addClass('hide');
    });

    //Eliminar usuarios y borrar el campo de seleccionados
    //si no existe ningun usuario
    $("body").on("click", ".search-table-icon", function(){
        var codUser = $(this).parent().remove();
        var tds     = $('table').children('tbody').children('tr').length;
        if(tds === 0){
            $('#tableSection').addClass('hide');
        };
    });

      function uppFirs(txt){
        return  txt.charAt(0).
          toUpperCase() + txt.substr(1).
          toLowerCase();
      }

      //Valida los campos antes de ser enviados al servidor
      function validate(objData){
        var pass     = false;
        var min      = 3;
        var allempty =
            alldata  = 0;

        if(!$.isEmptyObject(objData)){

          $.each(objData, function(key, val) {
            var valdata = val.value;
            alldata++;
            if((valdata.length < min && valdata.length != 0) || /^a-zA-Z0-9áéíóúÁÉÍÓÚÑñ ]+$/.test(valdata)){
              $('#' + objData[key].id).parent().removeClass('state-success').addClass('state-error');
              delete objData[key];
            }else if (valdata.length == 0){
              $('#' + objData[key].id).parent().removeClass('state-success state-error');
              delete objData[key];
              allempty++;
            }else{
              $('#' + objData[key].id).parent().removeClass('state-error').addClass('state-success');
              pass = true;
            }
          });
        }

        if(alldata === allempty){
          $('#resBusqueda').empty();
          $('#showAnswer').addClass('hide');
        }
        return pass;
      };


    /**
     * Funcion para retornar los usuarios seleccionados y mostrarlos
     * en la tabla seleccionado con las opciones de modificaciones individuales
     * @iddata array de los datos ya seleccionados
     * @returns inserta html procesado a la tabla de usuarios seleccionados
    */
    function passDataToTable(iddata){
        var trTable = [ALLDATA[iddata]];
        $.post( "./ajax_buscarUsuario.php", {addUser : JSON.stringify(trTable)}).done(
            function( data ) {
                $('table').append(data[0]);
                $('#tableSection').removeClass('hide');
                $('.toogletd').hide();
            }
        );
    }

      //Modifica respuesta del servidor para presentarla
      //con formato.
      function formatAnswer(data){
        var dataformat;
        var indiv   = $('#resBusqueda');

        indiv.empty();

        $.each(data, function(i){

          var li     = $('<li/>').appendTo(indiv);
          var nombre = (data[i].NOMBRE === null)? '' : data[i].NOMBRE.replace(/\w\S*/g, uppFirs);
          var apell  = (data[i].APELLIDO === null)? '' :  data[i].APELLIDO.replace(/\w\S*/g, uppFirs);
          var telef  = data[i].TELEF;
          var email  = (data[i].EMAIL)? data[i].EMAIL.toLowerCase() : '';
          var cedula = data[i].CEDULA;
	  var direccion = data[i].DIRECCION;

          var div    = $('<div/>')
                .addClass('well well-sm')
                .html('<div  class="col col-12">'
                      + '<h6 class=" text-success semi-bold">'
                      + cedula
                      + ' <i class="fa fa-plus-square"></i>'
                      + '</h6>'
                    + '</div>'
                    + '<div class="showdot176" ><b>'   + nombre +' '+ apell + '</b></div>'
                    + '<div class="showdot176">'       + telef      + '</div>'
                    + '<div class="showdot176">'       + email      + '</div>'
		+ '<div class="showdot176">'       + direccion      + '</div>' )
                .attr('name', 'cod_' + i)
                .on("click", function(){
                  var codUser = $(this).attr('name').substring(4);
                  var count   = 0;
                  var datali  = $('#showAnswer').children('ul').children('li');
                  passDataToTable(codUser);
                  $(this).addClass('hide');

                  datali.each(function() {
                    var ishide = $( this ).children('div').hasClass("hide");
                    if(ishide){
                      count++;
                    }
                  });

                  $('#showAnswer').addClass('hide');

                })
                .appendTo(li);
        });
        $('#showAnswer').removeClass('hide');
      };

      //Autocomplete busqueda de usuarios
      $("#documento_us, #nombre_us, #telefono_us, #mail_us").on('keyup', function () {
        var data  = {};

        data.docu = { value : $("#documento_us").val(), id : "documento_us"};
        data.name = { value : $("#nombre_us").val(),    id : "nombre_us"};
        data.tele = { value : $("#telefono_us").val(),  id : "telefono_us"};
        data.mail = { value : $("#mail_us").val(),      id : "mail_us"};

        if(validate(data)){
          data.tdoc = $("#tipo_usuario").val()
          $.post( "./ajax_buscarUsuario.php", {search : JSON.stringify(data)}).done(
            function( data ) {
              ALLDATA = data;
              if(data !== null){
                formatAnswer( data );
              }
            }
          );
        }
      });

   //Mostrar validacion del formulario
   function mostrarAlert(objAlert) {
     var type    = objAlert.type;
     var message = objAlert.message;

     var div    = $('<div/>')
       .addClass('alert alert-block alert-' + type)
       .html(
            '<a class="close" data-dismiss="alert" href="#">×</a>'
          + '<h4 class="alert-heading">' + message + '</h4>'
       ).appendTo('#alertmessage');

   };

   function borrarAlert(){
     $('#alertmessage').empty();
   }

   //Radicar documento nuevo
   $('body').on("click", '.radicarNuevo, #modificaRad', EJECUCION ,function(){
        var acction   = $(this).attr("id");
        var pass      = true;
        var idsession = '<?=$idsession?>';

        /* Realizar validaciones antes de enviar el radicado*/

        $('#alertmessage').empty();

        //Folios y Anexos
        if(/[A-Za-z]+$/.test($("#nofolios").val()) ||
           /[A-Za-z]+$/.test($("#noanexos").val())){
             mostrarAlert({ type : 'danger'
                          , message : 'Escriba un número válido en No de folios o anexos.'});
            pass = false;
        }

        //Fecha del radicado
        var fechaActual = new Date();
        var fecha_doc   = $('#fecha_gen_doc').val();
        var dias_doc    = fecha_doc.substring(0,2);
        var mes_doc     = fecha_doc.substring(3,5);
        var ano_doc     = fecha_doc.substring(6,10);

        var fecha       = new Date(ano_doc,mes_doc-1, dias_doc);
        var tiempoRestante = fechaActual.getTime() - fecha.getTime();
        var dias = Math.floor(tiempoRestante / (1000 * 60 * 60 * 24));


        if (dias >960 && dias < 1500){
          mostrarAlert({type : 'danger', message : 'El documento tiene fecha anterior a 60 dias!!.'});
          pass = false;
        }else if (dias > 1500){
          mostrarAlert({type : 'danger', message : 'Verifique la fecha del documento!!'});
          pass = false;
        }else if (dias < 0) {
          mostrarAlert({type : 'danger', message : 'Verifique la fecha del documento !!, es Una fecha Superior a la Del dia de Hoy'});
          pass = false;
        };

        //Usuarios
        if($('input[name^="usuario"]').length === 0){
          mostrarAlert({type : 'danger', message : 'Seleccione un usuario'});
          pass = false;
        };

        //Asunto
        var asu = $('#asu').val();
        //Tamanao del asunto Constante
        var min = 15;
        if(asu.length < min){
          mostrarAlert({type : 'danger', message : 'Asunto no es mayor de ' + min + ' Caracteres. '});
          pass = false;
        };

	//DIRECCION Ò EMAIL EN UN USUARIO NUEVO	
//	var inc = 0;
//	$("tr[name='item_usuario']").each(function(){

	for (var inc = 0; inc < 2; inc++) {
    //CARLOS RICAURTE 30/08/2018 && SE CAMBIA POR ||
    
  	if($("#id_dir_"+inc).val()=='') {
  	  mostrarAlert({type : 'danger', message : 'El destinatario No. ' + (inc+1) + ' Le falta Direccion '});
      pass = false;
      break;
  	}
    if($("#id_ema_"+inc).val() != undefined){
       if($("#id_ema_"+inc).val()=='') {
        mostrarAlert({type : 'danger', message : 'El destinatario No. ' + (inc+1) + ' Le falta Correo electronico '});
        pass = false;
        break;
      }
      else if(validar_email($("#id_ema_"+inc).val())==false) {
        mostrarAlert({type : 'danger', message : 'El destinatario No. ' + (inc+1) + ' Tiene correo no valida'});
        pass = false;
        break;
      }
    }
  }
	// });

        /*
        if(!/^[0-9A-Za-z áéíóúÁÉÍÓÚÑñ\&\#\.\*\>\@\<\_\,\;\:\°\-\%\(\)]+$/.test(asu) && asu.length > 0){
          mostrarAlert({type : 'danger', message : 'Asunto con caracteres no permitidos'});
          pass = false;
        };
        */

        //GUIA
        if($('#guia').val().length > 20){
          mostrarAlert({type : 'danger', message : 'Gu&iacute;a con mas de 20 caracteres'});
          pass = false;
        }

        //REFERENCIA CUENTA_I
        if($('#cuentai').val().length  > 20){
          mostrarAlert({type : 'danger', message : 'Referencia con mas de 20 caracteres'});
          pass = false;
        }

        //Dependencia
        if(parseInt($('select[name="coddepe"]').val()) === 0){
          mostrarAlert({type : 'danger', message : 'Selecciona una dependencia'});
          pass = false;
        }

        if(pass && !EJECUCION){
          //Dejar alertas en blanco
          borrarAlert();
          EJECUCION = true;
          var datos    = $("form").serialize();
          var radicado = '';

          <?php
        
        if ($datos) {
            echo "datos = datos + '&$javascriptCapDatos;'";
        }
        ?>

          if(acction === "modificaRad"){
            datos = datos + "&modificar=true";
          }


          var jqxhr = $.post( "./ajax_radicarNuevo.php", datos ,function(data) {
            for(var k in data) {
              if(data[k].error !== undefined){
                mostrarAlert({type : 'danger', message : data[k].error});
              }else{
                if(acction !== "modificaRad"){
                  radicado = data[k].answer;
                  $('#modificaRad').append(data[k].answer);
                  $('#modificaRad').append("<input type=\"hidden\" name=\"nurad\" value=\"" + data[k].answer + "\" />");

                  $('#idrad').append(data[k].answer);
                }else{
                    mostrarAlert({type : 'success', message : data[k].answer});
                }

                $('#showRadicar').remove();
                $('#showModificar').removeClass('hide');
              }
            };

            if(acction !== "modificaRad"){
                var contentstiker = $('#skeleton') .clone().removeClass('hide')[0].outerHTML.replace(/xxxxxx/g, radicado);
                var contentverrad = $('#skeleton8').clone().removeClass('hide')[0].outerHTML.replace(/xxxxxx/g, radicado);
                var contentasocia = $('#skeleton9').clone().removeClass('hide')[0].outerHTML.replace(/xxxxxx/g, radicado);
                $('#sticker').html(contentstiker + contentverrad);
                $('#asociar').html(contentasocia);

            }
        		<?if (isset($uid)){//El uid representa una radicadion de email, la siguiente linea permite automatizar la radicacion de emails?>
        			window.parent.filed(radicado,<?=$uid?>);
        		<?}?>

            $("#inforshow").removeClass('hide');
            $('#copyradicar').html($('#showModificar').clone());
            if(data[k].answer.substr(-1)==2 && acction !== "modificaRad"){
              //alert ('radicado:' + data[k].answer);
              //window.open ('./stickerWeb/index.php?<?=$idsession?>&nurad=xxxxxx&ent=<?=$ent?>','stickerxxxxxx','menubar=0,resizable=0,scrollbars=0,width=450,height=180,toolbar=0,location=0');
              window.open ('./stickerWeb/index.php?<?=$idsession?>&nurad='+data[k].answer,'stickerxxxxxx','menubar=0,resizable=0,scrollbars=0,width=450,height=180,toolbar=0,location=0');

            }
	  }).fail(function() {
	    alert("Error al radicar");	  
            mostrarAlert({type : 'danger', message : 'Error de conexion al servidor'})
          })

          EJECUCION = false;
        };

  });

    });

    function validar_email( email ) {
      //alert (email);
        var regex = /^(.+)@(.+)$/;
        return regex.test(email) ? true : false;
    }
  </script>
</body>
</html>
