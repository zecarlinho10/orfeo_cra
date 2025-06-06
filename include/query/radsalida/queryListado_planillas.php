<?
/**
* CONSULTA VERIFICACION PREVIA A LA RADICACION
*/

$where_isql2 = " WHERE DEPE_CODI = $dependencia AND $sqlChar = $fecha_mes AND SGD_FENV_CODIGO =$tipo_envio 
				AND ".$db->conn->substr."(SGD_RENV_PLANILLA,1,1) = '$no_planilla' AND sgd_renv_tipo < 2 ";

switch($db->driver)
{
	case 'mssql':
		{	$wrc=" WHERE SGD_RENV_CODIGO = $renv_codigo AND SGD_RENV_PLANILLA = '' ";
			$where_isql1 = " WHERE (DEPE_CODI = $dependencia AND sgd_renv_fech BETWEEN ".$db->conn->DBTimeStamp($fecha_ini)." 
			AND ".$db->conn->DBTimeStamp($fecha_fin)." AND $sqlChar = $fecha_mes AND SGD_FENV_CODIGO = $tipo_envio
			AND SGD_RENV_PLANILLA = '' AND sgd_renv_tipo <2)
			OR ($sqlChar = $fecha_mes AND SGD_RENV_PLANILLA = '".$no_planilla."' AND SGD_FENV_CODIGO = $tipo_envio
			AND DEPE_CODI= $dependencia
			AND sgd_renv_tipo <2) ";
		/*
		$where_isql2 = '	WHERE DEPE_CODI= ' .$dependencia . 
		' AND '. $sqlChar . ' = '  . $fecha_mes . '
		AND SGD_FENV_CODIGO = 101
		AND SGD_RENV_PLANILLA=' . $no_planilla . 
		' AND sgd_renv_tipo < 2 ';
 	// $query = "select top 32  SGD_RENV_CANTIDAD as CANTIDAD,
 	*/
	$query = "select SGD_RENV_CANTIDAD as CANTIDAD,
		'Certificado' 				as CATEGORIA, 
		".$db->conn->substr."(convert(char(20),RADI_NUME_SAL),5,15) 	as REGISTRO,
		SGD_RENV_NOMBRE as DESTINATARIO,
		SGD_RENV_DESTINO as DESTINO,
		SGD_RENV_PESO	as PESO,
		'' 	as VALOR_PORTE,
		SGD_RENV_VALOR as CERTIFICADO,
		'' as VALOR_ASEGURADO,
		'' as TASA_DE_SEGURO,
		'' as VALOR_REEMBOLSABLE,
		'' as AVISO_DE_LLEGADA,
		'' as SERVICIOS_ESPECIALES,
		(CONVERT(numeric,SGD_RENV_VALOR) * SGD_RENV_CANTIDAD) as VALOR_TOTAL,
		".$db->conn->substr."(convert(char(15),RADI_NUME_GRUPO),5,15) as RADI_NUME_GRUPO
		from SGD_RENV_REGENVIO ";
		}break;
	case 'oracle':
	case 'oci8': 
 case 'oci8po':
	case 'oci805':
	case 'postgres':
		$wrc=" WHERE SGD_RENV_CODIGO = $renv_codigo AND ( SGD_RENV_PLANILLA IS NULL OR SGD_RENV_PLANILLA = '' ) ";
		$query = "select a.SGD_RENV_CANTIDAD as CANTIDAD,
			  d.sgd_fenv_descrip as CATEGORIA,
			a.RADI_NUME_SAL      as REGISTRO,
			".$db->conn->substr."(a.SGD_RENV_NOMBRE,1,50) 	as DESTINATARIO,
                        ".$db->conn->substr."(a.SGD_RENV_DIR,1,63) 	as DIRECCION,
                        a.SGD_RENV_DEPTO                        as DEPARTAMENTO,
			a.SGD_RENV_MPIO 			as DESTINO,
			a.SGD_RENV_PESO			 	as PESO,
			a.SGD_RENV_VALOR 			as VALOR_ENVIO,
			(TO_NUMBER(a.SGD_RENV_VALOR, '999999999') * a.SGD_RENV_CANTIDAD) as VALOR_TOTAL
			from SGD_RENV_REGENVIO a,  SGD_FENV_FRMENVIO d 
                         ";
		$where_isql1 = " WHERE (a.DEPE_CODI= " . $dependencia .
			" AND a.sgd_renv_fech BETWEEN " .$db->conn->DBTimeStamp($fecha_ini).
			" AND " .$db->conn->DBTimeStamp($fecha_fin).
			" AND ". $sqlChar . " = " . $fecha_mes . 
			" AND a.SGD_FENV_CODIGO = $tipo_envio
                          AND a.sgd_fenv_codigo = d.sgd_fenv_codigo
			  AND ( a.SGD_RENV_PLANILLA IS  NULL OR a.SGD_RENV_PLANILLA = '' )
			  AND a.sgd_renv_tipo <=2)
			  OR (" . $sqlChar . " = " . $fecha_mes . 
			" AND a.SGD_RENV_PLANILLA = " . "'" . $no_planilla . "'" .
			" AND a.SGD_FENV_CODIGO = $tipo_envio
                          AND a.sgd_fenv_codigo = d.sgd_fenv_codigo   
			  AND a.DEPE_CODI= " . $dependencia . " 
			  AND a.sgd_renv_tipo <=2) ";


			$where_isql2 = " WHERE a.DEPE_CODI= " .$dependencia . 
			" AND " . $sqlChar . " = " . $fecha_mes . 
			" AND a.SGD_FENV_CODIGO = $tipo_envio
                          AND a.sgd_fenv_codigo = d.sgd_fenv_codigo
			  AND a.SGD_RENV_PLANILLA= " . "'" . $no_planilla . "'" .
			" AND a.sgd_renv_tipo <= 2 ";
			$whereTop = "";
                      
		break;
	}
?>
