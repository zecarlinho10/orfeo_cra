<?
	/**
	  * CONSULTA VERIFICACION PREVIA A LA RADICACION
	  */
	switch($db->driver)
	{
	 case 'mssql':
		$sqlConcat = $db->conn->Concat("convert(char(14),a.radi_nume_sal,0)","'-'","cast(a.sgd_renv_codigo as varchar)");
		$isql = 'select "4" AS CHU_ESTADO
            ,' . 0 . '              AS HID_DEVE_CODIGO
            ,convert(varchar(15), a.radi_nume_sal)        AS "Radicado"
			,convert(varchar(15), c.RADI_NUME_DERI)       AS "Radicado Padre"
			,' . $sqlChar . '       AS "Fecha Envio"
			,a.sgd_renv_planilla    AS "Planilla"
			,a.sgd_renv_nombre      AS "Destinatario"
			,a.sgd_renv_dir         AS "Direccion"
			,a.sgd_renv_depto       AS "Departamento"
			,a.sgd_renv_mpio        AS "Municipio"
			,b.sgd_fenv_descrip     AS "Empresa de Envio"
			,d.USUA_LOGIN           AS "Usuario actual"
			,'.$valor.'             AS "Valor de envio"
			, '. $sqlConcat .  '    AS "CHK_RADI_NUME_SAL"
			,a.sgd_dir_tipo         AS HID_sgd_dir_tipo
			,a.sgd_renv_cantidad    AS HID_sgd_renv_cantidad
			,a.sgd_renv_codigo      AS HID_sgd_renv_codigo
			,convert(varchar(15), c.RADI_FECH_RADI)       AS HID_RADI_FECH_RADI
			,c.RA_ASUN              AS HID_RA_ASUN
			,d.USUA_NOMB            AS HID_USUA_NOMB
			,c.radi_depe_actu       AS HID_radi_depe_actu
			,a.sgd_deve_codigo
		from sgd_renv_regenvio a
			 left outer join SGD_FENV_FRMENVIO b
				on (a.sgd_fenv_codigo=b.sgd_fenv_codigo)
				,radicado c
			 ,usuario d
		where sgd_deve_codigo is null and' .
			$dependencia_busq1 . ' '.
			$db->conn->substr.'(convert(char(15),a.radi_nume_sal), 5, 3)='.$dep_sel.' '.
			$dependencia_busq2 . '
			and a.sgd_renv_estado < 8' .
			$sql_masiva . '
			and	c.radi_nume_radi= a.radi_nume_sal
  			and c.radi_depe_actu=d.depe_codi
			and c.radi_usua_actu=d.usua_codi
      order by ' . $order .$orderTipo;
	break;
	case 'oracle':
	case 'oci8': 
 case 'oci8po':
	case 'oci805':
 	    $sqlConcat = $db->conn->Concat("A.RADI_NUME_SAL","'-'","A.SGD_RENV_CODIGO");

		$isql = "SELECT 4 AS CHU_ESTADO
            ,0 AS HID_DEVE_CODIGO
            ,A.RADI_NUME_SAL        AS \"RADICADO\"
			,C.RADI_NUME_DERI       AS \"RADICADO PADRE\"
			," . $sqlChar . "       AS \"FECHA ENVIO\"
			,A.SGD_RENV_PLANILLA    AS \"PLANILLA\"
			,A.SGD_RENV_NOMBRE      AS \"DESTINATARIO\"
			,A.SGD_RENV_DIR         AS \"DIRECCION\"
			,A.SGD_RENV_DEPTO       AS \"DEPARTAMENTO\"
			,A.SGD_RENV_MPIO        AS \"MUNICIPIO\"
			,B.SGD_FENV_DESCRIP     AS \"EMPRESA DE ENVIO\"
			,D.USUA_LOGIN           AS \"USUARIO ACTUAL\"
			,".$valor."     AS \"VALOR DE ENVIO\"
			, ". $sqlConcat .  "    AS \"CHK_RADI_NUME_SAL\"
			,A.SGD_DIR_TIPO         AS HID_SGD_DIR_TIPO
			,A.SGD_RENV_CANTIDAD    AS HID_SGD_RENV_CANTIDAD
			,A.SGD_RENV_CODIGO      AS HID_SGD_RENV_CODIGO
			,C.RADI_FECH_RADI       AS HID_RADI_FECH_RADI
			,C.RA_ASUN              AS HID_RA_ASUN
			,D.USUA_NOMB            AS HID_USUA_NOMB
            ,C.RADI_DEPE_ACTU       AS HID_RADI_DEPE_ACTU
		FROM SGD_RENV_REGENVIO A,
			 SGD_FENV_FRMENVIO B,
             RADICADO C, USUARIO D
		WHERE SGD_DEVE_CODIGO IS NULL AND " .
			$dependencia_busq1 . " " . $db->conn->substr ." (A.RADI_NUME_SAL, 5, 3)=".$dep_sel." ".
			$dependencia_busq2 . "
			AND A.SGD_FENV_CODIGO (+) = B.SGD_FENV_CODIGO
			AND A.SGD_RENV_ESTADO < 8" .
			$sql_masiva . "
			AND	C.RADI_NUME_RADI= A.RADI_NUME_SAL
  			    AND C.RADI_DEPE_ACTU=D.DEPE_CODI
			    AND C.RADI_USUA_ACTU=D.USUA_CODI
      ORDER BY " . $order .$orderTipo;
	break;
	
	//Modificacion skina
	default:
		
		$sqlConcat = $db->conn->Concat("a.radi_nume_sal","'-'","a.sgd_renv_codigo");
	
		$isql = 'select ' . "4" . '         	AS "CHU_ESTADO"
				,' . 0 . '              AS "HID_DEVE_CODIGO"
				,a.radi_nume_sal        AS "Radicado"
				,c.RADI_NUME_DERI       AS "Radicado Padre"
				,' . $sqlChar . '       AS "Fecha Envio"
				,a.sgd_renv_planilla    AS "Planilla"
				,a.sgd_renv_nombre      AS "Destinatario"
				,a.sgd_renv_dir         AS "Direccion"
				,a.sgd_renv_depto       AS "Departamento"
				,a.sgd_renv_mpio        AS "Municipio"
				,b.sgd_fenv_descrip     AS "Empresa de Envio"
				,d.USUA_LOGIN           AS "Usuario actual"
				,'.$valor.'             AS "Valor de envio"
				, '. $sqlConcat .  '    AS "CHK_RADI_NUME_SAL"
				,a.sgd_dir_tipo         AS "HID_sgd_dir_tipo"
				,a.sgd_renv_cantidad    AS "HID_sgd_renv_cantidad"
				,a.sgd_renv_codigo      AS "HID_sgd_renv_codigo"
				,c.RADI_FECH_RADI       AS "HID_RADI_FECH_RADI"
				,c.RA_ASUN              AS "HID_RA_ASUN"
				,d.USUA_NOMB            AS "HID_USUA_NOMB"
				,c.radi_depe_actu       AS "HID_radi_depe_actu"
			from sgd_renv_regenvio a LEFT OUTER JOIN
				sgd_fenv_frmenvio b
				ON a.sgd_fenv_codigo = b.sgd_fenv_codigo,
				radicado c, usuario d
			where sgd_deve_codigo is null and' .
				$dependencia_busq1 . ' '.
				' c.depe_codi ='."'".$dep_sel."'".' '.
				$dependencia_busq2 . '			
				and a.sgd_renv_estado < 8
				' .$sql_masiva . '
				and c.radi_nume_radi= a.radi_nume_sal
				and c.radi_depe_actu=d.depe_codi
				and c.radi_usua_actu=d.usua_codi
                                and a.sgd_renv_valor != '."''".'
			order by ' . $order .$orderTipo;
	}
?>
