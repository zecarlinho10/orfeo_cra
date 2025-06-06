<?
//
switch($db->driver)
{	case 'mssql':
	{

		$radi_nume_radi = "convert(varchar(14),a.RADI_NUME_RADI)";
		$tmp_cad1 = "convert(varchar,".$db->conn->concat("'0'","'-'",$radi_nume_radi).")";
		$tmp_cad2 = "convert(varchar,".$db->conn->concat('c.info_codi',"'-'",$radi_nume_radi).")";
		$concatenar = "CAST(DEPE_CODI AS VARCHAR(10))";

		

$isql = 'select '.$radi_nume_radi.' "IMG_Numero Radicado",
			a.RADI_PATH  "HID_RADI_PATH",
			'.$sqlFecha.'  "DAT_Fecha Radicado",
			'.$radi_nume_radi.' "HID_RADI_NUME_RADI",
			c.info_desc "Comentario",
			a.ra_asun "Asunto",
			b.sgd_tpr_descrip as "Tipo Documento",
			'.chr(39).chr(39).'  AS "Informador",
			'.$tmp_cad1.' "CHK_checkValue",
			c.INFO_LEIDO as "HID_RADI_LEIDO",
			 a.RADI_FECH_RADI
 		from radicado a,
 			sgd_tpr_tpdcumento b,
 			informados c,
 			usuario d
		where a.radi_nume_radi=c.radi_nume_radi and a.tdoc_codi=b.sgd_tpr_codigo
			and a.radi_usua_actu=d.usua_codi and a.radi_depe_actu=d.depe_codi
			and c.depe_codi='.$dependencia.' and c.usua_codi='.$codusuario.' '.$where_filtro .'
			and c.info_codi is null
		UNION
		select '.$radi_nume_radi.' "IMG_Numero Radicado",
			a.RADI_PATH  "HID_RADI_PATH",
			'.$sqlFecha.'  "DAT_Fecha Radicado",
			'.$radi_nume_radi.' "HID_RADI_NUME_RADI",
			c.info_desc "Asunto",
			b.sgd_tpr_descrip as "Tipo Documento",
			d2.usua_nomb  AS "Informador",
			'.$tmp_cad2.' "CHK_checkValue",
			c.INFO_LEIDO as "HID_RADI_LEIDO",
			 a.RADI_FECH_RADI
 		from radicado a,
 			sgd_tpr_tpdcumento b,
 			informados c,
 			usuario d, usuario d2
		where a.radi_nume_radi=c.radi_nume_radi and a.tdoc_codi=b.sgd_tpr_codigo
			and a.radi_usua_actu=d.usua_codi and a.radi_depe_actu=d.depe_codi
			and c.depe_codi='.$dependencia.' and c.usua_codi='.$codusuario.' '.$where_filtro .'
			and c.info_codi is not null and d2.usua_doc = c.info_codi
		order by '.$order.' '.$orderTipo;
	}break;
	case 'oracle':
	case 'oci8': 
 case 'oci8po':
	{ 	$radi_nume_radi = "to_char(a.RADI_NUME_RADI)";
		$tmp_cad1 = "to_char(".$db->conn->concat("'0'","'-'",$radi_nume_radi).")";
		$tmp_cad2 = "to_char(".$db->conn->concat("0","c.info_codi","'-'",$radi_nume_radi).")";
		$redondeo = round($sqlOffset."-".$systemDate);
		//$tmp_cad2 = "to_char(".$db->conn->concat('c.info_codi',"'-'",$radi_nume_radi).")";
		//$redondeo = $db->conn->round($sqlOffset."-".$systemDate);
		$concatenar = "CAST(DEPE_CODI AS VARCHAR(10))";
$isql = '
		select '.$radi_nume_radi.' "IMG_Numero Radicado",
			a.RADI_PATH  "HID_RADI_PATH",
			'.$sqlFecha.'  "DAT_Fecha Radicado",
			'.$radi_nume_radi.' "HID_RADI_NUME_RADI",
			s.sgd_dir_nomremdes "Contacto",
			c.info_desc "Comentario",
			a.ra_asun "Asunto",
			b.sgd_tpr_descrip as "Tipo Documento",
			d2.usua_nomb  AS "Informador",
			'.$tmp_cad2.' "CHK_checkValue",
			c.INFO_LEIDO as "HID_RADI_LEIDO"
 		from radicado a,
 			sgd_tpr_tpdcumento b,
 			informados c,
 			usuario d, usuario d2,
 			sgd_dir_drecciones s
		where a.radi_nume_radi=c.radi_nume_radi and a.radi_nume_radi=s.radi_nume_radi and a.tdoc_codi=b.sgd_tpr_codigo
			and a.radi_usua_actu=d.usua_codi and a.radi_depe_actu=d.depe_codi
			and c.depe_codi='.$dependencia.' and c.usua_codi='.$codusuario.' '.$where_filtro .'
			and d2.usua_doc (+) = c.info_codi
		order by '.$order.' '.$orderTipo;
	}break;
}

if ($order == 3) 
{
if ($_SESSION['entidad'] == 'CRA'){
$order = 3;
}else{ 
		  $order = 12;
}		 }


$isql = 'select '.
			$radi_nume_radi.' "IMG_Numero Radicado",
			a.RADI_PATH  "HID_RADI_PATH",
			'.$sqlFecha.'  "DAT_Fecha Radicado",
			'.$radi_nume_radi.' "HID_RADI_NUME_RADI",
			s.sgd_dir_nomremdes "Contacto",
			s.SGD_DIR_NOMBRE as "REMITENTE",
			c.info_desc "Comentario",
			a.ra_asun "Asunto",
			b.sgd_tpr_descrip as "Tipo Documento",
			'.chr(39).chr(39).'  AS "Informador",
			'.$tmp_cad1.' "CHK_checkValue",
			c.INFO_LEIDO as "HID_RADI_LEIDO",
			 a.RADI_FECH_RADI

 		from radicado a,
 			sgd_tpr_tpdcumento b,
 			informados c,
 			usuario d,
 			sgd_dir_drecciones s
		where a.radi_nume_radi=c.radi_nume_radi and a.radi_nume_radi=s.radi_nume_radi
				and a.tdoc_codi=b.sgd_tpr_codigo
			and a.radi_usua_actu=d.usua_codi and a.radi_depe_actu=d.depe_codi
			and c.depe_codi='.$dependencia.' and c.usua_codi='.$codusuario.' '.$where_filtro .'
			and c.info_codi is null
		UNION
		select '.
			$radi_nume_radi.' "IMG_Numero Radicado",
			a.RADI_PATH  "HID_RADI_PATH",
			'.$sqlFecha.'  "DAT_Fecha Radicado",
			'.$radi_nume_radi.' "HID_RADI_NUME_RADI",
			s.sgd_dir_nomremdes "Contacto",
			s.SGD_DIR_NOMBRE as "REMITENTE",
			c.info_desc "Comentario",
			a.ra_asun "Asunto",
			b.sgd_tpr_descrip as "Tipo Documento",
			d.usua_nomb  AS "Informador",
			'.$tmp_cad2.' "CHK_checkValue",
			c.INFO_LEIDO as "HID_RADI_LEIDO",
			 a.RADI_FECH_RADI

 		from radicado a,
 			sgd_tpr_tpdcumento b,
 			informados c,
 			usuario d,
 			sgd_dir_drecciones s
		where a.radi_nume_radi=c.radi_nume_radi and a.tdoc_codi=b.sgd_tpr_codigo
			and a.radi_nume_radi=s.radi_nume_radi and a.radi_usua_actu=d.usua_codi and a.radi_depe_actu=d.depe_codi
			and c.depe_codi='.$dependencia.' and c.usua_codi='.$codusuario.' '.$where_filtro .'
			and c.info_codi is not null and d.usua_doc = c.info_codi
		order by '.$order.' '.$orderTipo;
?>

