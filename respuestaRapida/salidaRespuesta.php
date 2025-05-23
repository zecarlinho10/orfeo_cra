<?php
    session_start();
    
    set_time_limit(0);
    
    if($_SESSION["krd"])
        $krd = $_SESSION["krd"];
    
    if (!isset($_SESSION['dependencia']))
        include "../rec_session.php";

    $dependencia = $_SESSION["dependencia"] * 1 ;
    
	  $ruta_raiz = "..";
	  include $ruta_raiz."/htmlheader.inc.php";
    require_once($ruta_raiz."/include/db/ConnectionHandler.php");

    $db      = new ConnectionHandler($ruta_raiz);
    $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);                 
    
    define('SMARTY_DIR', $ruta_libs.'libs/');
    define('SMARTY_DIR', './libs/');
	  require (SMARTY_DIR . 'Smarty.class.php');

    $smarty = new Smarty;
    $smarty->template_dir = './templates';
    $smarty->compile_dir = './templates_c';
    $smarty->config_dir = './configs/';
    $smarty->cache_dir = './cache/';
    
    $smarty->left_delimiter = '<!--{';
    $smarty->right_delimiter = '}-->';

    $errores  = $_GET['error'];
    $nurad    = $_GET['nurad'];
    $sali     = array();

    function errores($errores) {
      $arreglo_errores = array();
      $arreglo_errores[1] = ' No se genero el radicado.';
      $arreglo_errores[] = ' Error no se creo la carpeta para los adjuntos /bodega/adjuntos/.';
      $arreglo_errores[] = ' Un archivo no se envio (Extension invalida)';
      $arreglo_errores[] = ' El formato mime del documento no existe';
      $arreglo_errores[] = ' El tamano del archivo adjunto supero el limite permitido';
      //$arreglo_errores[] = ' No se pudo registrar uno de los adjuntos';
      //$arreglo_errores[] = ' No se pudo grabar uno de los anexos';
      //$arreglo_errores[] = ' Error enviando correo electronico. <br />
      //                       Realize el envio del correo de manera manual.';
      //$arreglo_errores[] = ' Error adjuntando el archivo de la respuesta. <br />
      //                       Realize el envio de manera manual.';
      //$arreglo_errores[] = ' Error adjuntado el archivo del radicado padre. <br />
      //                       Realize el envio del correo de manera manual.';
      return $arreglo_errores[$errores];
    }

    if(!empty($nurad)){
        $isqlDepR = "SELECT 
                        ANEX_NOMB_ARCHIVO AS NOMBRE
                        ,ANEX_DESC
                    FROM 
                        ANEXOS
                    WHERE 
                        ANEX_RADI_NUME='$nurad' 
                        AND  ANEX_BORRADO='N'";

        $rsDepR = $db->conn->Execute($isqlDepR);
        $file   = $rsDepR->fields['NOMBRE'];

        while (!empty($rsDepR) && !$rsDepR->EOF){
            $sali[] = array('path'  => $ruta_raiz."bodega/".substr($file,0,4)."/".$dependencia."/docs/".$file,
                            'desc'  => $rsDepR->fields['ANEX_DESC']);

            $rsDepR->MoveNext();
        }
    }

    $datoserror = explode('-', $errores);

    sort($datoserror);
    $noerrores = $datoserror[0];
    for($i=0;$i<count($datoserror);$i++)
       $error1 .= errores($datoserror[$i]).''; 

    if(empty($errores)){
        $salida = 'ok'; 
    }
#echo "-------------------".$nurad."-------------------"; exit;

	$smarty->assign("krd"	    , $krd);
	$smarty->assign("respuesta_rap", 'true');
	$smarty->assign("noerror"   , $noerrores);
	$smarty->assign("error"	    , $error1);
	$smarty->assign("nurad"	    , $nurad);
	$smarty->assign("sali"      , $sali);
	$smarty->assign("salida"    , $salida);
	$smarty->assign("estilosCaliope"    ,$estilosCaliope);
	$smarty->assign("sid"	    , SID); //Envio de session por get
	$smarty->assign("dependencia"	, $dependencia); //Envio de session por get

	$smarty->display('salidaRespuesta.tpl');
?>
