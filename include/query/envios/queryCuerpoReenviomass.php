<?
switch($db->driver)
{

	case 'oracle':
	case 'oci8': 
 case 'oci8po':
	case 'postgres':
	$isql = 'select 
			d.radi_nume_sal AS "IMG_Radicado Salida",
		 	d.sgd_deve_codigo HID_DEVE_CODIGO
			,d.sgd_deve_fech AS "HID_SGD_DEVE_FECH" 
			,r.radi_path as "HID_RADI_PATH"
            ,d.sgd_renv_fech AS "Envio"
            ,d.sgd_renv_planilla as "Planilla"
			,r.radi_fech_radi AS "Fecha Radicado"
			,e.hist_obse AS "Descripcion"
			,d.sgd_deve_fech AS "Fecha Devolucion"
			,d.radi_nume_sal AS "CHK_RADI_NUME_SALIDA" 
			,d.sgd_deve_codigo HID_DEVE_CODIGO1
			,r.RADI_FECH_RADI AS "HID_ANEX_RADI_FECH" 
			,' . "'WWW'" . ' AS "HID_WWW" 
			,' . "'9999'" . ' AS "HID_9999"     
			,d.sgd_dir_tipo AS "HID_SGD_DIR_TIPO"
			,d.sgd_deve_codigo AS "HID_SGD_DEVE_CODIGO" 
	from radicado r, sgd_renv_regenvio d, hist_eventos e
	where d.radi_nume_sal=r.radi_nume_radi
		'.$dependencia_busq2 . '
		and r.radi_nume_radi=e.radi_nume_radi
		and d.sgd_deve_fech is not null
		and e.sgd_ttr_codigo=28
		'.$radicados.'    
		AND ((r.SGD_EANU_CODIGO <> 2
		AND r.SGD_EANU_CODIGO <> 1)
		or r.SGD_EANU_CODIGO IS NULL)
		order by '.$order .' ' .$orderTipo;
	//$db->conn->debug=true;
}
?>
