<?php

switch ($tmp_arr_id) {
  case 1:
    $tmp_where = " and (i.info_codi is null) ";
    break;
	default:
    $tmp_where = " ";
    break;
}
//($tmp_arr_id)? $tmp_where = " " : $tmp_where = " and i.info_codi is null ";
switch($db->driver) {
  case 'mssql':
  {	switch ($tmp_arr_id)
		{	case 0:
				{	$isql = 'select
							r.RADI_NUME_RADI as "IMG_Numero Radicado"
							,r.RADI_PATH as "HID_RADI_PATH"
							,r.RADI_FECH_RADI as "HOR_RAD_FECH_RADI"
							,'.$sqlFecha.' as "DAT_Fecha Radicado"
							,r.RADI_NUME_RADI as "HID_Numero Radicado"
							,r.RA_ASUN  as "Descripcion"
							,r.RADI_USU_ANTE "Enviado Por"
							,r.RADI_NUME_RADI as "CHK_CHKANULAR"
							from
								radicado r 
		 					where
		 						r.RADI_NUME_RADI is not null
								'.$whereFiltro.'
								'.$whereCarpeta.'
	  						order by '.$order.' '.$orderTipo;
				}break;
			case 1:
				{	$radi_nume_radi = "i.RADI_NUME_RADI ";
					$tmp_cad1 = "".$db->conn->concat("'0'","'-'",$radi_nume_radi)."";
					$tmp_cad2 = "".$db->conn->concat('cast(i.info_codi as varchar(14))',"'-'",$radi_nume_radi)."";
					$isql = 'select
								cast(r.RADI_NUME_RADI as varchar(20)) as "IMG_Numero Radicado"
								,r.RADI_PATH as "HID_RADI_PATH"
								,r.RADI_FECH_RADI as "HOR_RAD_FECH_RADI"
								,'.$sqlFecha.' as "DAT_Fecha Radicado"
								,r.RADI_NUME_RADI as "HID_Numero Radicado"
								,i.info_codi as "HID_InfoCodi"
								,i.info_desc  as "Descripcion"
								,'.chr(39).chr(39).' "Informado Por"
								,'.$tmp_cad1.' "CHK_CHKANULAR"
						 	from
						 		informados i, radicado r
						 	where
						 		i.radi_nume_radi = r.radi_nume_radi and
						  		i.RADI_NUME_RADI is not null
						  		AND (i.USUA_CODI = '.$_SESSION['codusuario'].') AND (i.DEPE_CODI = '.$_SESSION['dependencia'].')
								'.$whereFiltro.$tmp_where.'
								'.$whereCarpeta.'
					  		order by '.$order.' '.$orderTipo;
				}break;
			default:
				{	$radi_nume_radi = "i.RADI_NUME_RADI";
					$tmp_cad1 = "convert(varchar,".$db->conn->concat("'0'","'-'",$radi_nume_radi).")";
					$tmp_cad2 = "convert(varchar,".$db->conn->concat('cast(i.info_codi as varchar(14))',"'-'",$radi_nume_radi).")";
					$isql = 'select
								'.$radi_nume_radi.'	 as "IMG_Numero Radicado"
								,r.RADI_PATH		 as "HID_RADI_PATH"
								,r.RADI_FECH_RADI 	 as "HOR_RAD_FECH_RADI"
								,'.$sqlFecha.'		 as "DAT_Fecha Radicado"
								,'.$radi_nume_radi.' 	 as "HID_Numero Radicado"
								,i.info_codi		 as "HID_InfoCodi"
								,i.info_desc 		 as "Descripcion"
								,u.USUA_NOMB		 as  "Informado Por"
								,'.$tmp_cad2.'		 as  "CHK_CHKANULAR"
		 					from
		 						informados i, radicado r,  usuario u
		 					where
						 		i.radi_nume_radi = r.radi_nume_radi and
						 		i.info_codi = u.usua_doc and
						  		i.RADI_NUME_RADI is not null
						  		AND (i.USUA_CODI = '.$_SESSION['codusuario'].') AND (i.DEPE_CODI = '.$_SESSION['dependencia'].')
								'.$whereFiltro.$tmp_where.'
								'.$whereCarpeta.'
	  						order by '.$order.' '.$orderTipo;
				}break;
		}
	}break;
	case 'oracle':
	case 'oci8': 
 case 'oci8po':
	{	switch ($tmp_arr_id)
		{	case 0:
				{
					$isql = 'select
							r.RADI_NUME_RADIas "IMG_Numero Radicado"
							,r.RADI_PATH as "HID_RADI_PATH"
							,r.RADI_FECH_RADI as "HOR_RAD_FECH_RADI"
							,'.$sqlFecha.' as "DAT_Fecha Radicado"
							,r.RADI_NUME_RADI as "HID_Numero Radicado"
							,r.RA_ASUN  as "Descripcion"
							,td.SGD_TPR_DESCRIP as "Tipo Documento"
							,r.RADI_USU_ANTE "Enviado Por"
							,r.RADI_NUME_RADI "CHK_CHKANULAR"
					 from
						radicado r,
					 	SGD_TPR_TPDCUMENTO td  
				 	where
						r.radi_nume_radi is not null '.
						' and r.tdoc_codi=td.sgd_tpr_codigo
						'.$whereFiltro.$whereCarpeta.'
				  	order by '.$order .' ' .$orderTipo;
				}break;
			case 1:
				{	$radi_nume_radi = "i.RADI_NUME_RADI";
					$tmp_cad1 = "to_char(".$db->conn->concat("'0'","'-'",$radi_nume_radi).")";
					$tmp_cad2 = "to_char(".$db->conn->concat('i.info_codi',"'-'",$radi_nume_radi).")";
					$isql = 'select
								r.RADI_NUME_RADI as "IMG_Numero Radicado"
								,r.RADI_PATH as "HID_RADI_PATH"
								,r.RADI_FECH_RADI as "HOR_RAD_FECH_RADI"
								,'.$sqlFecha.' as "DAT_Fecha Radicado"
								,r.RADI_NUME_RADI as "HID_Numero Radicado"
								,i.info_codi as "HID_InfoCodi"
								,i.info_desc  as "Descripcion"
								,'.chr(39).chr(39).' "Informado Por"
								,'.$tmp_cad1.' "CHK_CHKANULAR"
						 	from
						 		informados i, radicado r
						 	where
						 		i.radi_nume_radi = r.radi_nume_radi and
						  		i.RADI_NUME_RADI is not null
						  		AND (i.USUA_CODI = '.$_SESSION['codusuario'].') AND (i.DEPE_CODI = '.$_SESSION['dependencia'].')
								'.$whereFiltro.$tmp_where.'
								'.$whereCarpeta.'
					  		order by '.$order.' '.$orderTipo;
				}break;
			case 2:
				{	$radi_nume_radi = "i.RADI_NUME_RADI";
					$tmp_cad1 = "to_char(".$db->conn->concat("'0'","'-'",$radi_nume_radi).")";
					$tmp_cad2 = "to_char(".$db->conn->concat('i.info_codi',"'-'",$radi_nume_radi).")";
					$isql = 'select
								'.$radi_nume_radi.' as "IMG_Numero Radicado"
								,r.RADI_PATH as "HID_RADI_PATH"
								,r.RADI_FECH_RADI as "HOR_RAD_FECH_RADI"
								,'.$sqlFecha.' as "DAT_Fecha Radicado"
								,'.$radi_nume_radi.' as "HID_Numero Radicado"
								,i.info_codi as "HID_InfoCodi"
								,i.info_desc  as "Descripcion"
								,u.USUA_NOMB "Informado Por"
								,'.$tmp_cad2.' "CHK_CHKANULAR"
						 	from
						 		informados i, radicado r,  usuario u
						 	where
						 		i.radi_nume_radi = r.radi_nume_radi and
						 		i.info_codi = u.usua_doc and
						  		i.RADI_NUME_RADI is not null
						  		AND (i.USUA_CODI = '.$_SESSION['codusuario'].') AND (i.DEPE_CODI = '.$_SESSION['dependencia'].')
								'.$whereFiltro.$tmp_where.'
								'.$whereCarpeta.'
					  		order by '.$order.' '.$orderTipo;
				}break;
		}
	}
	//Modificado skina
	default:
	{	switch ($tmp_arr_id)
		{	case 0:
				{
					$isql = 'select
							r.RADI_NUME_RADI as "IMG_Numero Radicado"
							,r.RADI_PATH			 as "HID_RADI_PATH"
							,r.RADI_FECH_RADI 	       	 as "HOR_RAD_FECH_RADI"
							,'.$sqlFecha.'			 as "DAT_Fecha Radicado"
							,r.RADI_NUME_RADI		 as "HID_Numero Radicado"
							,r.RA_ASUN 			 as "Descripcion"
							,td.SGD_TPR_DESCRIP		 as "Tipo Documento"
							,r.RADI_USU_ANTE		 as "Enviado Por"
							,r.RADI_NUME_RADI as "CHK_CHKANULAR"
					 from
						radicado r inner join 
					 	SGD_TPR_TPDCUMENTO td on r.tdoc_codi=td.sgd_tpr_codigo
				 	where
						r.radi_nume_radi is not null 
						'.$whereFiltro . $whereCarpeta.'
				  	order by '.$order .' ' .$orderTipo;
				}break;
			case 1:
				{	$radi_nume_radi = "i.RADI_NUME_RADI";
					$tmp_cad1 = "to_char(".$db->conn->concat("'0'","'-'",$radi_nume_radi).")";
					$tmp_cad2 = "to_char(".$db->conn->concat('i.info_codi',"'-'",$radi_nume_radi).")";
					$isql = 'select
								r.RADI_NUME_RADI as "IMG_Numero Radicado"
								,r.RADI_PATH		 as "HID_RADI_PATH"
								,r.RADI_FECH_RADI	 as "HOR_RAD_FECH_RADI"
								,'.$sqlFecha.'		 as "DAT_Fecha Radicado"
								,r.RADI_NUME_RADI as "HID_Numero Radicado"
								,i.info_codi		 as "HID_InfoCodi"
								,i.info_desc 		 as "Descripcion"
								,'.chr(39).chr(39).'	 as "Informado Por"
								,'.$tmp_cad1.'		 as "CHK_CHKANULAR"
						 	from
						 		informados i nner join radicado r on   i.radi_nume_radi = r.radi_nume_radi and
						 	where
						  		i.RADI_NUME_RADI is not null
						  		AND (i.USUA_CODI = '.$_SESSION['codusuario'].') AND (i.DEPE_CODI = '.$_SESSION['dependencia'].')
								'.$whereFiltro.$tmp_where.'
								'.$whereCarpeta.'
					  		order by '.$order.' '.$orderTipo;
				}break;
			case 2:
				{	
					// Modificado Infom�trika 21-Septiembre-2009
                                        // Compatibilidad con PostgreSQL 8.3
                                        // Cambi� i.info_codi = u.usua_doc and
                                        // por CAST( i.info_codi AS VARCHAR(10) ) = u.usua_doc and
					$radi_nume_radi = "r.RADI_NUME_RADI ";
					$tmp_cad1 = $db->conn->concat("'0'","'-'",$radi_nume_radi);
					$tmp_cad2 = $db->conn->concat('i.info_codi',"'-'",$radi_nume_radi);
					$isql = 'select
								'.$radi_nume_radi.'	 as "IMG_Numero Radicado"
								,r.RADI_PATH		 as "HID_RADI_PATH"
								,r.RADI_FECH_RADI	 as "HOR_RAD_FECH_RADI"
								,'.$sqlFecha.'		 as "DAT_Fecha Radicado"
								,'.$radi_nume_radi.'	 as "HID_Numero Radicado"
								,r.info_codi		 as "HID_InfoCodi"
								,i.info_desc 		 as "Descripcion"
								,u.USUA_NOMB 		 as "Informado Por"
								,'.$tmp_cad2.'		 as  "CHK_CHKANULAR"
						 	from
							informados i inner jooin  radicado r on i.radi_nume_radi = r.radi_nume_radi
						   innr join 	usuario u on (CAST( i.info_codi AS VARCHAR(20) ) = u.usua_doc)
						 	where
						  		i.RADI_NUME_RADI is not null
						  		AND (i.USUA_CODI = '.$_SESSION['codusuario'].') AND (i.DEPE_CODI = '.$_SESSION['dependencia'].')
								'.$whereFiltro.' '.$tmp_where.'
								'.$whereCarpeta.'
					  		order by '.$order.' '.$orderTipo;
				}break;
		}
	}
}
?>
