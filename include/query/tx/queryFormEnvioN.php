<?
switch ($tmp_arr_id)
{	case 1:$tmp_where = " and (i.info_codi is null) ";break;
	default:$tmp_where = " ";break;
}
//($tmp_arr_id)? $tmp_where = " " : $tmp_where = " and i.info_codi is null ";
switch($db->driver)
{	
	case 'oracle':
	case 'oci8': 
 case 'oci8po':
	{		$camposConcatenar = "(" . $db->conn->Concat("sb.sgd_sexp_parexp1",
                                                    "sb.sgd_sexp_parexp2",
                                                    "sb.sgd_sexp_parexp3",
                                                    "sb.sgd_sexp_parexp4",
                                                    "sb.sgd_sexp_parexp5") . ")";
//

$expediente = "sb.sgd_exp_numero";
$tmp_cad1 = "to_char(".$expediente.")";


	$isql='select  distinct sb.SGD_EXP_NUMERO as "Expediente", 
					sb.SGD_sEXP_FECH as "Fecha_Creado", 
					'.$camposConcatenar.' as "Etiqueta", 
					'.$tmp_cad1.' AS "CHK_checkValue"
					from sgd_sexp_secexpedientes sb , 
					dependencia d  
					where 
					'.$whereFiltrox.'
					';
		//	$db->conn->debug = true;
			$rs=$db->conn->query($isql);
	
	}
}
?>