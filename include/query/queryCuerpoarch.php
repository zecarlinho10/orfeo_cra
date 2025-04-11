<?
    switch($db->driver)
    {	case 'oracle':

    	case 'oci8': 
        case 'oci8po':
    	{	
        if($tdocumento==0){
            $w=" AND e.radi_nume_radi = ''";
        }
        else if($tdocumento==0){
            $w=" AND e.radi_nume_radi = ''";
        }
        else if($tdocumento==10){
            $w="";
        }
        else{
            $w=" AND e.radi_nume_radi LIKE '%$tdocumento'";
        }
    	$camposConcatenar = "(" . $db->conn->Concat("s.sgd_sexp_parexp1",
                                                        "s.sgd_sexp_parexp2",
                                                        "s.sgd_sexp_parexp3",
                                                        "s.sgd_sexp_parexp4",
                                                        "s.sgd_sexp_parexp5") . ")";
        //
        $radi_nume_radi = "to_char(e.RADI_NUME_RADI)";
        $tmp_cad1 = "to_char(".$radi_nume_radi.")";
        $expediente="to_char(s.sgd_exp_numero)";

         $isql= "select distinct( e.radi_nume_radi) as Radicado, r.radi_fech_radi as Fecha_Radicado, s.sgd_exp_numero as Expediente, 
                            $camposConcatenar as Etiqueta, 
                            E.SGD_EXP_FECH as Fecha_Incluido,
                            d.depe_nomb as Dependencia,
                            (se.sgd_srd_descrip||' - '||su.sgd_sbrd_descrip) AS Serie_Subserie,
                            $tmp_cad1 AS CHK_checkValue
                    from    sgd_exp_expediente E
                            INNER JOIN SGD_SEXP_SECEXPEDIENTES S ON E.sgd_exp_numero = S.sgd_exp_numero
                            INNER JOIN RADICADO R ON E.RADI_NUME_RADI = R.RADI_NUME_RADI
                            INNER JOIN dependencia d ON s.depe_codi = d.depe_codi
                            INNER JOIN sgd_sbrd_subserierd su ON (s.sgd_srd_codigo = su.sgd_srd_codigo AND s.sgd_sbrd_codigo  = su.sgd_sbrd_codigo)
                            INNER JOIN SGD_SRD_SERIESRD SE ON SE.sgd_srd_codigo = SU.sgd_srd_codigo
                    where 
                            (d.depe_estado <> 0 OR D.DEPE_CODI IN (211,310,341,401,301,320,360)) AND 
                            ((".$db->sysdate()." between su.sgd_sbrd_fechini and su.sgd_sbrd_fechfin) OR SU.SGD_SBRD_CODIGO IN(1,3,4)) AND
                            ((".$db->sysdate()." between sgd_SRD_fechini and sgd_srd_fechfin) OR SU.SGD_SRD_CODIGO IN (99,83,76,101,82)) AND
                            E.SGD_EXP_ESTADO <> 2 AND NOT EXISTS (SELECT 1 FROM HIST_EVENTOS H WHERE E.RADI_NUME_RADI=H.RADI_NUME_RADI AND H.SGD_TTR_CODIGO IN(57) AND ROWNUM <2) AND
                            s.depe_codi not in (900,905,910,999,330)
                            $where_filtro $w
                            order by $order $orderTipo";

         //$db->conn->debug = true;
     	}break;		
    }
?>


