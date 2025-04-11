<?php

$coltp3Esp = '"'.$tip3Nombre[3][2].'"'; 
if(!$orno) $orno=2;
 /**
   * $db-driver Variable que trae el driver seleccionado en la conexion
   * @var string
   * @access public
   */
 /**
   * $fecha_ini Variable que trae la fecha de Inicio Seleccionada  viene en formato Y-m-d
   * @var string
   * @access public
   */
/**
   * $fecha_fin Variable que trae la fecha de Fin Seleccionada
   * @var string
   * @access public
   */
/**
   * $mrecCodi Variable que trae el medio de recepcion por el cual va a sacar el detalle de la Consulta.
   * @var string
   * @access public
   */
$whereTipoRadicado  = str_replace("A.","r.",$whereTipoRadicado);
$whereTipoRadicado  = str_replace("a.","r.",$whereTipoRadicado);
switch($db->driver)
{ 
  case 'postgres':
  case 'oracle':
  case 'oci8': 
 case 'oci8po':
  case 'oci805':
  case 'ocipo':
    { if ( $dependencia_busq != 99999)
      { /*$condicionE = "   AND SUBSTR( ra.radi_nume_radi, 5, 3 ) = $dependencia_busq  "; */
	  $condicionE = "   AND ra.radi_depe_actu = $dependencia_busq  "; 
	  }

      /*echo "<hr>";
      print_r($_GET);
      echo "<hr>";
/*ESTE COMANDO PARA QUE ME MUESTRE EL PANTALLAZO EN EL EXPLORER*/ 

$queryE = "
			SELECT
			      substr(ra.radi_nume_radi,14) as TIPO_RADICADO,
			      r. RADI_NUME_RADI AS RADICADO,
			      ra.radi_fech_radi AS FECHA_RADICADO,
				  ra.radi_nume_deri AS RADICADO_ASOCIADO,
			      s.SGD_SRD_CODIGO AS SERIE_NUMERO,
			      s.SGD_SRD_DESCRIP AS DESCRIPCION_SERIE,
			      su.SGD_SBRD_CODIGO AS SUBSERIE_NUMERO,
			      su.SGD_SBRD_DESCRIP AS SUBDESCRIPCION_SERIE,
			      t.SGD_TPR_CODIGO AS TIPO_DOCUMENTAL_NUMERO,
			      t.SGD_TPR_DESCRIP AS DESCRIPCION_TIPO_DOCUMENTAL,
			      ra.ra_asun AS ASUNTO,
			      dir.sgd_dir_nomremdes AS NOMBRE_ENTIDAD,
			      dir.sgd_dir_nombre AS PETICIONARIO,
			      ra.radi_usua_actu AS CODIGO_FUNCIONARIO,
			      b.usua_nomb AS NOMBRE_FUNCIONARIO,
			      ra.radi_depe_actu AS CODIGO_DEPENDENCIA,
			      de.depe_nomb AS NOMBRE_DEPENDENCIA, 
			      ra.radi_usu_ante AS CODIGO_FUNCIONARIO_ANTERIOR,
			      us.usua_nomb AS NOMBRE_FUNCIONARIO_ANTERIOR,
			      dir.sgd_dir_direccion AS DIRECCION,
			      dir.sgd_dir_mail AS CORREO_ELECTRONICO,
			      dep.dpto_nomb AS DEPARTAMENTO,
			      mun.muni_nomb AS CIUDAD_MUNICIPIO,
			      (".$db->sysdate()."-ra.RADI_FECH_RADI) AS DIAS_RAD      
			    FROM radicado ra      
			    left join usuario b on ra.radi_usua_actu=b.usua_codi and ra.radi_depe_actu=b.depe_codi
			         left join usuario us on ra.radi_usu_ante=us.usua_doc
			         left outer join SGD_DIR_DRECCIONES dir on dir.radi_nume_radi=ra.radi_nume_radi
			         left join departamento dep on dir.dpto_codi=dep.dpto_codi and dir.id_cont=dep.id_cont and dir.id_pais=dep.id_pais
			         left join  municipio mun on dir.muni_codi=mun.muni_codi and dir.id_cont=mun.id_cont and dir.id_pais=mun.id_pais and dir.dpto_codi=mun.dpto_codi,
			     sgd_rdf_retdocf r,      
			      sgd_mrd_matrird m,
			      sgd_srd_seriesrd s,
			      sgd_sbrd_subserierd su,
			      sgd_tpr_tpdcumento t,
			      dependencia de  
			    WHERE ra.radi_nume_radi=r.radi_nume_radi
$condicionE 
				  AND SUBSTR( ra.radi_nume_radi, 14, 1 ) = 2
			      AND r.sgd_mrd_codigo = m.sgd_mrd_codigo 
			      AND  s.sgd_srd_codigo = m.sgd_srd_codigo 
			      AND  su.sgd_srd_codigo = m.sgd_srd_codigo 
			      AND su.sgd_sbrd_codigo = m.sgd_sbrd_codigo 
			      AND t.sgd_tpr_codigo = m.sgd_tpr_codigo
			      AND ra.radi_depe_actu=DE.DEPE_CODI
			      AND TO_CHAR(ra.radi_fech_radi,'yyyy/mm/dd') BETWEEN '$fecha_ini'  AND '$fecha_fin'
			      
            	  
            	  ";

            	      if($codus) $queryE .= " AND b.USUA_CODI=$codus
            	      	AND b.depe_codi = $dependencia_busq "; 
  

    $queryETodosDetalle = $queryEDetalle . $orderE;
    $queryEDetalle .= $condicionUS . $orderE;
//	echo "<pre>$queryE</pre>";
    }break;
}
if(isset($_GET['genDetalle'])&& $_GET['denDetalle']=1)
    $titulos=array();
else 

	$titulos=array("#",
		"1#TIPO_RADICADO",
		"2#RADICADO",
		"3#FECHA_RADICADO",
		"4#RADICADO_ASOCIADO",
		"5#SERIE_NUMERO",
		"6#DESCRIPCION_SERIE",
		"7#SUBSERIE_NUMERO",
		"8#SUBDESCRIPCION_SERIE",
		"9#TIPO_DOCUMENTAL_NUMERO",
		"10#DESCRIPCION_TIPO_DOCUMENTAL",
		"11#ASUNTO",
		"12#NOMBRE_ENTIDAD",
		"13#PETICIONARIO",
		"14#CODIGO_FUNCIONARIO",
		"15#NOMBRE_FUNCIONARIO",
		"16#CODIGO_DEPENDENCIA",
		"17#NOMBRE_DEPENDENCIA",
		"18#CODIGO_FUNCIONARIO_ANTERIOR",
		"19#NOMBRE_FUNCIONARIO_ANTERIOR",
		"20#DIRECCION",
		"21#CORREO_ELECTRONICO",
		"22#DEPARTAMENTO",
		"23#CIUDAD_MUNICIPIO",
		"24#DIAS_RAD"
	);	
function pintarEstadistica($fila,$indice,$numColumna)
{
  global $ruta_raiz,$_POST,$_GET,$krd;
  $salida="";
  switch ($numColumna)
  {
    case  0:  $salida=$indice;    break;
	case 1: $salida=$fila['TIPO_RADICADO']; break;
	case 2: $salida=$fila['RADICADO']; break;
	case 3: $salida=$fila['FECHA_RADICADO']; break;
	case 4: $salida=$fila['RADICADO_ASOCIADO']; break;
	case 5: $salida=$fila['SERIE_NUMERO']; break;
	case 6: $salida=$fila['DESCRIPCION_SERIE']; break;
	case 7: $salida=$fila['SUBSERIE_NUMERO']; break;
	case 8: $salida=$fila['SUBDESCRIPCION_SERIE']; break;
	case 9: $salida=$fila['TIPO_DOCUMENTAL_NUMERO']; break;
	case 10: $salida=$fila['DESCRIPCION_TIPO_DOCUMENTAL']; break;
	case 11: $salida=$fila['ASUNTO']; break;
	case 12: $salida=$fila['NOMBRE_ENTIDAD']; break;
	case 13: $salida=$fila['PETICIONARIO']; break;
	case 14: $salida=$fila['CODIGO_FUNCIONARIO']; break;
	case 15: $salida=$fila['NOMBRE_FUNCIONARIO']; break;
	case 16: $salida=$fila['CODIGO_DEPENDENCIA']; break;
	case 17: $salida=$fila['NOMBRE_DEPENDENCIA']; break;
	case 18: $salida=$fila['CODIGO_FUNCIONARIO_ANTERIOR']; break;
	case 19: $salida=$fila['NOMBRE_FUNCIONARIO_ANTERIOR']; break;
	case 20: $salida=$fila['DIRECCION']; break;
	case 21: $salida=$fila['CORREO_ELECTRONICO']; break;
	case 22: $salida=$fila['DEPARTAMENTO']; break;
	case 23: $salida=$fila['CIUDAD_MUNICIPIO']; break;
	case 24: $salida=$fila['DIAS_RAD']; break;
    default: $salida=false;
  }

return $salida;
 }
?>

