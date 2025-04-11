<?php
session_start();
//ini_set("display_errors",1);
$ruta_raiz = "..";
if (!$_SESSION['dependencia'])
	header ("Location: $ruta_raiz/cerrar_session.php");


foreach ($_POST as $key => $valor)   ${$key} = $valor;
foreach ($_GET as $key => $valor)   ${$key} = $valor;

$usua_doc    = $_SESSION["usua_doc"];
$dependencia = $_SESSION["dependencia"];

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler("$ruta_raiz");	 
$db->conn->debug=false;
define('ADODB_ASSOC_CASE', 1);

$db->conn->StartTrans();#Iniciando transacciones inteligentes de ADODB.

$isql = "
SELECT 
	a.SGD_DIR_TIPO as \"radDestino\", 
	a.RADI_NUME_SALIDA as \"radSalida\", 
	b.RADI_NUME_DERI as \"radPadre\", 
	b.RA_ASUN as \"radAsunto\"
FROM 
	ANEXOS a,
	RADICADO b 
WHERE 
	a.radi_nume_salida=b.radi_nume_radi 
	and a.anex_codigo = ('$codigo') 
	AND anex_estado=3 
	AND a.sgd_dir_tipo <> 7 
ORDER BY a.SGD_DIR_TIPO";

$rs=$db->conn->GetAll($isql);
$radSalida = $rs[0]["RADSALIDA"];
$radPadre = $rs[0]["RADPADRE"];
$radDestino = $rs[0]["RADDESTINO"];

include "$ruta_raiz/clasesComunes/datosDest.php";
error_log($radSalida." ".$radPadre." ".$radDestino,0);
$dat = new DATOSDEST($db,$radSalida,$espcodi,$radDestino,$radDestino);
$pCodDep = $dat->codep_us;
$pCodMun = $dat->muni_us;
$pNombre = $dat->nombre_us;
$pPriApe = $dat->prim_apel_us;
$pSegApe = $dat->seg_apel_us;
$nombre_us    = substr($pNombre . " " . $pPriApe . " " . $pSegApe,0 ,33);
$direccion_us = $dat->direccion_us;

include "$ruta_raiz/jh_class/funciones_sgd.php";
$a = new LOCALIZACION($pCodDep,$pCodMun,$db);
$departamento_us = $a->departamento;
$destino = $a->municipio;
$pais_us = $a->GET_NOMBRE_PAIS($dat->idpais,$db);
$dir_codigo = $dat->documento_us;

$sql_selectAnexos="select * from anexos where anex_codigo='$codigo' and sgd_dir_tipo <>7";
$rs_selectAnexos=$db->conn->Execute($sql_selectAnexos);

$record_updateAnexos["ANEX_ESTADO"] = 4;
$record_updateAnexos["ANEX_FECH_ENVIO"] = $db->conn->OffsetDate(0,$db->conn->sysTimeStamp);
$sql_updateAnexos = $db->conn->GetUpdateSQL($rs_selectAnexos,$record_updateAnexos);
$rs_updateAnexos = $db->conn->Execute($sql_updateAnexos);

$sql_codigoEnvio = "select SGD_RENV_CODIGO FROM SGD_RENV_REGENVIO ORDER BY SGD_RENV_CODIGO DESC ";
$rs_codigoEnvio = $db->conn->SelectLimit($sql_codigoEnvio,1);

$codigoEnvio = $rs_codigoEnvio->fields["SGD_RENV_CODIGO"]+1;
$sql_selectRadicado = "select * from RADICADO where RADI_NUME_RADI =$radSalida";
$rs_selectRadicado = $db->conn->Execute($sql_selectRadicado);

$record_updateRadicado["SGD_EANU_CODIGO"]=9;
$sql_updateRadicado = $db->conn->GetUpdateSQL($rs_selectRadicado,$record_updateRadicado);
$rs_updateRadicado = $db->conn->Execute($sql_updateRadicado);

$record_insertRegenvio["USUA_DOC"]=$usua_doc;
$record_insertRegenvio["SGD_RENV_CODIGO"]=$codigoEnvio;
$record_insertRegenvio["SGD_FENV_CODIGO"]=106;
$record_insertRegenvio["SGD_RENV_FECH"]=$db->conn->OffsetDate(0,$db->conn->sysTimeStamp);
$record_insertRegenvio["RADI_NUME_SAL"]=$radSalida;
$record_insertRegenvio["SGD_RENV_DESTINO"]=$email;
$record_insertRegenvio["SGD_RENV_MAIL"]=$email;
$record_insertRegenvio["SGD_RENV_CERTIFICADO"]=0;
$record_insertRegenvio["SGD_RENV_ESTADO"]=1;
$record_insertRegenvio["SGD_RENV_NOMBRE"]=$nombre_us;
$record_insertRegenvio["SGD_DIR_CODIGO"]=$dir_codigo;
$record_insertRegenvio["DEPE_CODI"]=$dependencia;
$record_insertRegenvio["SGD_DIR_TIPO"]=$radDestino;
$record_insertRegenvio["RADI_NUME_GRUPO"]=$radSalida;
$record_insertRegenvio["SGD_RENV_PLANILLA"]=0;
$record_insertRegenvio["SGD_RENV_DIR"]=$direccion_us;
$record_insertRegenvio["SGD_RENV_DEPTO"]=$departamento_us;
$record_insertRegenvio["SGD_RENV_MPIO"]=$destino;
$record_insertRegenvio["SGD_RENV_PAIS"]=$pais_us;
$record_insertRegenvio["SGD_RENV_OBSERVA"]="";
$record_insertRegenvio["SGD_RENV_CANTIDAD"]=1;
$keys=array_keys($record_insertRegenvio);
$keys=implode(',',$keys);
$values="'".implode("','",$record_insertRegenvio)."'";
$values=str_replace("'(SYSDATE+0)'","(SYSDATE+0)",$values);
$sql_insertRegenvio="insert into SGD_RENV_REGENVIO ($keys) values ($values)";
$rs_insertRegenvio = $db->conn->Execute($sql_insertRegenvio);



if (!$db->conn->HasFailedTrans()){#Verificando errores en consultas de DB
	$envioDigital=true;
	$radicadosSelText=$radSalida;
	$asuntoEnvioDigital="Envio de radicado ".$radSalida." por E-mail ";
	$sql_mailDestino="select SGD_DIR_MAIL  from sgd_dir_drecciones where radi_nume_radi='$radSalida'";

	$rs_mailDestino=$db->conn->Execute($sql_mailDestino);
	$mailDestino=$rs_mailDestino->fields["SGD_DIR_MAIL"];

	ob_start();
	include ("$ruta_raiz/include/mail/CRA.mailInformar.php");
	ob_end_clean(); 

	if ($success===true){
		$request = array(
			"success" => true,
			"message" => "Enviado correctamente"
		);
	}else{
		$request = array(
			"success" => false,
			"message" => "Error envios correo electronicooooo"
		);
	}
}
else{
	$request = array(
		"success" => false,
		"message" => "Error al ejecutar la consulta"
	);
}

$db->conn->CompleteTrans();#Fin de transacciones inteligentes de ADODB.

print json_encode($request);	
?>
