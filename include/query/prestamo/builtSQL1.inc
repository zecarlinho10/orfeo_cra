<?PHP
 
/********* LA CONSULTA SE DIVIDIO EN TRES PARTES PARA QUE PUEDA SER USADA TANTO POR *********/
/********* prestamo.php como formEnvio.php. ESTA CORRESPONDE A LA PRIMERA PARTE.    *********/

    // Build SELECT statement 

$sqlPRES_FECH_PEDI=$db->conn->SQLDate("Y-m-d h:i:s A","P.PRES_FECH_PEDI"); 
$sqlPRES_FECH_PRES=$db->conn->SQLDate("Y-m-d h:i:s A","P.PRES_FECH_PRES"); 
$sqlPRES_FECH_CANC=$db->conn->SQLDate("Y-m-d h:i:s A","P.PRES_FECH_CANC"); 
$sqlPRES_FECH_VENC=$db->conn->SQLDate("Y-m-d h:i:s A","P.PRES_FECH_VENC"); 
$sqlPRES_FECH_DEVO=$db->conn->SQLDate("Y-m-d h:i:s A","P.PRES_FECH_DEVO"); 

$sqlOrderPRES_FECH_PEDI=$db->conn->SQLDate("YmdHis","P.PRES_FECH_PEDI"); 
$sqlOrderPRES_FECH_PRES=$db->conn->SQLDate("YmdHis","P.PRES_FECH_PRES"); 
$sqlOrderPRES_FECH_CANC=$db->conn->SQLDate("YmdHis","P.PRES_FECH_CANC"); 
$sqlOrderPRES_FECH_VENC=$db->conn->SQLDate("YmdHis","P.PRES_FECH_VENC"); 
$sqlOrderPRES_FECH_DEVO=$db->conn->SQLDate("YmdHis","P.PRES_FECH_DEVO"); 

  $sSelect1=" select R.RADI_NUME_RADI as RADICADO, ";
  $sSelect2="    
  U.USUA_NOMB as NOMBRE,
  U.USUA_PASW as PASWORDMD5,
  U.ID as IDUSER,
  D.DEPE_NOMB as DEPE_USUA,".
  $sqlOrderPRES_FECH_PEDI.",".
  $sqlOrderPRES_FECH_PRES.",".
  $sqlOrderPRES_FECH_CANC.",".
  $sqlOrderPRES_FECH_VENC.",".
  $sqlOrderPRES_FECH_DEVO.",
  RE.PARAM_VALOR as REQUERIMIENTO,
  R.RADI_NUME_HOJA as NUM_PAG,
  ES.PARAM_VALOR as ESTADO,
  P.USUA_LOGIN_PRES as LOGIN_PRES,
  P.USUA_LOGIN_CANC as LOGIN_CANC,
  P.USUA_LOGIN_RX as LOGIN_RX,
  R.RADI_DEPE_ACTU as DEPE_ACTU,
  P.USUA_LOGIN_ACTU as LOGIN_ACTU,
  P.DEPE_CODI as DEPENDENCIA_CODI,
  P.PRES_ID as PRESTAMO_ID,
  P.PRES_ESTADO as ID_ESTADO,".
    $sqlPRES_FECH_PEDI." as F_PEDIDO,".
          $sqlPRES_FECH_PRES." as F_PRESTADO,".
          $sqlPRES_FECH_CANC." as F_CANCELADO,".
          $sqlPRES_FECH_VENC." as F_VENCIDO,".
          $sqlPRES_FECH_DEVO." as F_DEVUELTO,
              R.RADI_PATH as PATH ";

    $sSelect_non_exp=$sSelect1."(select e2.sgd_exp_numero from sgd_exp_expediente e2 where r.radi_nume_radi=e2.radi_nume_radi limit 1) as EXPEDIENTE,".$sSelect2.",'' as ESTADO_EXP ";// Select radicados sin expediente
    $sSelect_exp    =$sSelect1.
                "E.SGD_EXP_NUMERO as EXPEDIENTE,".
                $sSelect2.
                ",''||E.SGD_EXP_ESTADO as ESTADO_EXP"; // Select radicados con expediente



    // Build FROM statement   

    $sFrom=" from  PRESTAMO P,
                RADICADO R,
                USUARIO U,
                DEPENDENCIA D,
                SGD_PARAMETRO ES,
                SGD_PARAMETRO RE,
                DEPENDENCIA DA ";

    $sFrom_non_exp=$sFrom;                           // from radicados sin expediente
    $sFrom_exp    =$sFrom.",SGD_EXP_EXPEDIENTE E ";  // from radicados con expediente



    // Build WHERE statement

    $sWhere=" where
                U.USUA_LOGIN=P.USUA_LOGIN_ACTU and
                D.DEPE_CODI=P.DEPE_CODI and
                ES.PARAM_CODI=P.PRES_ESTADO and
                ES.PARAM_NOMB='PRESTAMO_ESTADO' and
                RE.PARAM_CODI=P.PRES_REQUERIMIENTO and
                RE.PARAM_NOMB='PRESTAMO_REQUERIMIENTO' and
                DA.DEPE_CODI=P.PRES_DEPE_ARCH and ";

    if($opcionMenu == 1) {
        $sWhere .= "P.PRES_ESTADO = 1 and";
    }else{
        $sWhere .= "P.PRES_ESTADO < 3 and P.PRES_ESTADO > 1 and";
    }

  if(!$ps_numeroExpediente){
    $sWhere .= " P.RADI_NUME_RADI=R.RADI_NUME_RADI  ";
    }  else { 
      $sWhere .= " e.radi_nume_radi = r.radi_nume_radi and P.sgd_exp_numero=e.sgd_exp_numero ";
      // echo "$sWhere <hr>";
    }
      ?>		 	 