<?php


  define ('ENVIO_EMAIL', '1');
  define ('COLOMBIA',     170);
  
  require_once($ruta_raiz."/config.php");
  require_once($ruta_raiz."/include/db/ConnectionHandler.php");
  include_once($ruta_raiz."/class_control/AplIntegrada.php");
  include_once($ruta_raiz."/class_control/anexo.php");
  include_once($ruta_raiz."/class_control/anex_tipo.php");
  include_once($ruta_raiz."/include/tx/Tx.php");
  include_once($ruta_raiz."/include/tx/Radicacion.php");
  include_once($ruta_raiz."/class_control/Municipio.php");
  include_once($ruta_raiz."/include/PHPMailer_v5.1/class.phpmailer.php");
  require_once($ruta_raiz."/tcpdf/config/lang/eng.php");
  require_once($ruta_raiz."/conf/configPHPMailer.php");
  require_once($ruta_raiz."/tcpdf/tcpdf.php");
  #require_once($ruta_raiz."/tcpdf/tcpdf.php");
  require_once($ruta_raiz."/tcpdf/2dbarcodes.php");
  require_once($ruta_raiz."/tcpdf/tcpdf_barcodes_1d.php");



 #echo $barcode;
 # exit;

  $db      = new ConnectionHandler($ruta_raiz);
  $hist    = new Historico($db);
  $Tx      = new Tx($db);
  $anex    = new Anexo($db);
  $anexTip = new Anex_tipo($db);
  $mail    = new PHPMailer(true);
  
  // Es necesario liberar la variable ya que este se utilizara mas adelante como clase
  unset($anexo);
//$db->conn->debug=true;
  $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
  $anexTip->anex_tipo_codigo(7);
  $sqlFechaHoy      = $db->conn->OffsetDate(0, $db->conn->sysTimeStamp);
  $numRadicadoPadre = $_POST["radPadre"];

  $tamanoMax      = 7 * 1024 * 1024; // 7 megabytes
  $fechaGrab      = trim($date1);
  $numramdon      = rand (0,100000);
  $contador       = 0;
  $regFile        = array();
  $conCopiaA      = '';
  $enviadoA       = '';
  $cCopOcu        = '';

  $ddate          = date('d');
  $mdate          = date('m');
  $adate          = date('Y');
  $fechproc4      = substr($adate,2,4);
  $fecha1         = time();
  $fecha          = fechaFormateada($fecha1);

  //DATOS A VALIDAR EN RADICADO //
  $servidorSmtp   = "172.16.1.92:25";
  $tdoc           = NO_DEFINIDO; 
  $tipo_radicado  = (isset($_POST['tipo_radicado']))? $_POST['tipo_radicado'] : null;
  $pais           = COLOMBIA; //OK, codigo pais
  $cont           = 1;        //id del continente
  $radicado_rem   = 7;
  $auxnumero      = str_pad($auxnumero, 5, "0", STR_PAD_LEFT);
  $tipo           = ARCHIVO_PDF;
  $tamano         = 1000;
  $auxsololect    = 'N';
  $radicado_rem   = 1;
  $descr          = 'Pdf respuesta';
  $fechrd         = $ddate.$mdate.$fechproc4;
  $coddepe        = $_SESSION["dependencia"] * 1 ;
  $usua_actu      = $_SESSION["codusuario"];
  $usua           = $_SESSION["krd"];
  $codigoCiu      = $_SESSION["usua_doc"];
  $ln             = $_SESSION["digitosDependencia"];

  $usMailSelect   = $_POST['usMailSelect'];   //correo del emisor de la respuesta
  $destinat       = $_POST["destinatario"];   //correos de los destinanexnexnexnexnexnexnextarios
  $correocopia    = $_POST["concopia"];       //destinatarios con copia
  $conCopOcul     = $_POST["concopiaOculta"]; //con copia oculta
  $anexHtml       = $_POST["anexHtml"];       //con copia oculta
  $docAnex        = $_POST["docAnex"];        //con copia oculta
  $medioRadicar   = ENVIO_EMAIL;   //con copia oculta

  $asu            = $_POST["respuesta"];

  $tpDepeRad      = $coddepe;
  $radUsuaDoc     = $codigoCiu;
  $usua_doc       = $_SESSION["usua_doc"];
  $usuario        = $_SESSION["usua_nomb"];
  $setAutor       = 'Sistema de Gestion Documental Orfeo';
  $SetTitle       = 'Respuesta a solicitud';
  $SetSubject     = 'Metrovivienda';
  $SetKeywords    = 'metrovivienda, respuesta, salida, generar';

  //DATOS EMPRESA
  $sigla          = 'null';
  $iden           = $db->conn->nextId("sec_ciu_ciudadano");//uniqe key

  //ENLACE DEL ANEXO
  $radano = substr($numRadicadoPadre,0,4);

  $desti = "SELECT
              s.sgd_dir_nomremdes,
              s.sgd_dir_direccion,
              s.sgd_dir_tipo,
              s.sgd_dir_mail,
              s.sgd_dir_telefono,
              s.sgd_sec_codigo,
              r.depe_codi,
              r.radi_path
          FROM
              SGD_DIR_DRECCIONES s,
              RADICADO r
          WHERE
              r.RADI_NUME_RADI     = $numRadicadoPadre
              AND s.RADI_NUME_RADI = r.RADI_NUME_RADI";

  $rssPatth       = $db->conn->Execute($desti);

  $dir_nombre     = $rssPatth->fields["sgd_dir_nomremdes"];
  $dir_tipo       = $rssPatth->fields["sgd_dir_tipo"];
  $dir_mail       = $rssPatth->fields["sgd_dir_mail"];
  $dir_telefono   = $rssPatth->fields["sgd_dir_telefono"];
  $dir_direccion  = $rssPatth->fields["sgd_dir_direccion"];
  $pathPadre      = $rssPatth->fields["radi_path"];

  $depCreadora    = substr($numRadicadoPadre, 4, $digitosDependencia);
  
  $ruta3  = "/$radano/$depCreadora/docs/".$ruta;

// CREACION DEL RADICADO RESPUESTA
  //Para crear el numero de radicado se realiza el siguiente procedimiento
  $isql_consec = "SELECT DEPE_RAD_TP$tipo_radicado as secuencia
                    FROM DEPENDENCIA
                    WHERE DEPE_CODI = $tpDepeRad";

  $creaNoRad   = $db->conn->Execute($isql_consec);
  $tpDepeRad   = $creaNoRad->fields["secuencia"];

  $rad = new Radicacion($db);
  $rad->radiTipoDeri  = 0;       // ok ????
  $rad->radiCuentai   = 'null';  // ok, Cuenta Interna, Oficio, Referencia
  $rad->eespCodi      = $iden;   //codigo emepresa de servicios publicos bodega
  $rad->mrecCodi      = 3;       // medio de correspondencia, 3 internet
  $rad->radiFechOfic  = 'now()'; // igual fecha radicado;
  $rad->radiNumeDeri  = $numRadicadoPadre; //ok, radicado padre
  $rad->radiPais      = $pais;   //OK, codigo pais
  $rad->descAnex      = '.';     //OK anexos
  $rad->raAsun        = "Respuesta al radicado " . $numRadicadoPadre; // ok asunto
  $rad->radiDepeActu  = $coddepe;   // ok dependencia actual responsable
  $rad->radiUsuaActu  = $usua_actu; // ok usuario actual responsable
  $rad->radiDepeRadi  = $coddepe;   //ok dependencia que radica
  $rad->usuaCodi      = $usua_actu; // ok usuario actual responsable
  $rad->dependencia   = $coddepe;   //ok dependencia que radica
  $rad->trteCodi      =  0;         //ok, tipo de codigo de remitente
  $rad->tdocCodi      = $tdoc;      //ok, tipo documental
  $rad->tdidCodi      = 0;          //ok, ????
  $rad->carpCodi      = 1;          //ok, carpeta entradas
  $rad->carPer        = 0;          //ok, carpeta personal
  $rad->ra_asun       = "Respuesta al radicado " . $numRadicadoPadre;
  $rad->radiPath      = 'null';
  $rad->sgd_apli_codi = '0';
  $rad->usuaDoc       = $radUsuaDoc;
  $codTx              = 62;

  $nurad = $rad->newRadicado($tipo_radicado, $tpDepeRad);

  if ($nurad=="-1"){
    header("Location: salidaRespuesta.php?$encabe&error=1");
      die;
  }

  $sql_radi_cuentai = 'SELECT radi_cuentai FROM radicado WHERE radi_nume_radi = ' . $numRadicadoPadre;

  $rs_radi_cuentai = $db->conn->Execute($sql_radi_cuentai);
  $referencia = '';

  if (!empty($rs_radi_cuentai) && !$rs_radi_cuentai->EOF)
    $referencia = $rs_radi_cuentai->fields["RADI_CUENTAI"];
  
  //datos para guardar los anexos en la carpeta del nuevo radicado
  $primerno  = substr($nurad, 0, 4);
  $segundono = $_SESSION["dependencia"];
  $ruta1     = $primerno . "/" . $segundono . "/docs/";
  $adjuntos  = 'bodega/'.$ruta1;

  $nextval   = $db->nextId("sec_dir_drecciones");
  //se buscan los datos del radicado padre y se
  //insertaran en los del radicado hijo

  $isql = "insert into SGD_DIR_DRECCIONES(
                              SGD_TRD_CODIGO,
                              SGD_DIR_NOMREMDES,
                              SGD_DIR_DOC,
                              DPTO_CODI,
                              MUNI_CODI,
                              ID_PAIS,
                              ID_CONT,
                              SGD_DOC_FUN,
                              SGD_OEM_CODIGO,
                              SGD_CIU_CODIGO,
                              SGD_ESP_CODI,
                              RADI_NUME_RADI,
                              SGD_SEC_CODIGO,
                              SGD_DIR_DIRECCION,
                              SGD_DIR_TELEFONO,
                              SGD_DIR_MAIL,
                              SGD_DIR_TIPO,
                              SGD_DIR_CODIGO,
                              SGD_DIR_NOMBRE)
                      values( 1,
                              '$dir_nombre',
                              NULL,
                              11,
                              1,
                              170,
                              1,
                              '$usua_doc',
                              NULL,
                              NULL,
                              NULL,
                              $nurad,
                              0,
                              '$dir_direccion',
                              '$dir_telefono',
                              '$dir_mail',
                              1,
                              $nextval,
                              '$dir_nombre')";
# echo "<pre>$isql</pre>"; exit;
 
  $dignatario       = $dir_nombre;
  $rsg              = $db->conn->Execute($isql);

  $mensajeHistorico  = "Se envia respuesta rapida";

  if(!empty($regFile)){
      $mensajeHistorico .= ", con archivos adjuntos";
  }

  //inserta el evento del radicado padre.
  $radicadosSel[0] = $numRadicadoPadre;

  $hist->insertarHistorico($radicadosSel,
                            $coddepe,
                            $usua_actu,
                            $coddepe,
                            $usua_actu,
                            $mensajeHistorico,
                            $codTx);

  //Inserta el evento del radicado de respuesta nuevo.
  $radicadosSel[0] = $nurad;
  
  $hist->insertarHistorico($radicadosSel,
                            $coddepe,
                            $usua_actu,
                            $coddepe,
                            $usua_actu,
                            "Nomensaje",
                            2);

  //Agregar un nuevo evento en el historico para que
  //muestre como contestado y no genere alarmas.
  //A la respuesta se le agrega el siguiente evento
  $hist->insertarHistorico($radicadosSel,
                            $coddepe,
                            $usua_actu,
                            $coddepe,
                            $usua_actu,
                            "Imagen asociada desde respuesta rapida",
                            42);

// VALIDAR DATOS ADJUNTOS
  if(!empty($_FILES["archs"]["name"][0])){
  // Arreglo para Validar la extension
  $sql1     = " select
                      anex_tipo_codi as codigo
                      , anex_tipo_ext as ext
                      , anex_tipo_mime as mime
                  from
                      anexos_tipo";

  $exte = $db->conn->Execute($sql1);

  while(!empty($exte) && !$exte->EOF) {
    $codigo     = $exte->fields["codigo"];
    $ext      = $exte->fields["ext"];
    $mime1      = $exte->fields["mime"];
    $mime2      = explode(",",$mime1);

    //arreglo para validar la extension
    $exts[".".$ext] = array ('codigo'   => $codigo,
                             'mime'   => $mime2);
    $exte->MoveNext();
  }

  //Si no existe la carpeta se crea.
  if(!is_dir($ruta_raiz."/".$adjuntos)){
    $rs = mkdir($adjuntos, 0700);
    if(empty($rs)){
      $errores .= empty($errores)? "&error=2" : '-2';
    }
  }

  $i = 0;
  $anexo = new Anexo($db);

  //Validaciones y envio para grabar archivos
  foreach($_FILES["archs"]["name"] as $key => $name){
    $nombre   = strtolower(trim($_FILES["archs"]["name"][$key]));
    $type   = trim($_FILES["archs"]["type"][$key]);
    $tamano   = trim($_FILES["archs"]["size"][$key]);
    $tmporal  = trim($_FILES["archs"]["tmp_name"][$key]);
    $error    = trim($_FILES["archs"]["error"][$key]);
    $ext    = strrchr($nombre,'.');
    
    if (is_array($exts[$ext])) {
      foreach ($exts[$ext]['mime'] as $value) {
        
        if(eregi($type,$value)) {
          $bandera = true;

          if($tamano < $tamanoMax) {
              //grabar el registro en la base de datos
            if(strlen($str) > 90) {
                $nombre = substr($nombre, '-90:');
            }
            
            $anexo->anex_radi_nume    = $nurad;
            $anexo->usuaCodi          = $usua_actu;
            $anexo->depe_codi         = $coddepe;
            $anexo->anex_solo_lect    = "'S'";
            $anexo->anex_tamano       = $tamano;
            $anexo->anex_creador      = "'".$usua."'";
            $anexo->anex_desc         = "Adjunto: ". $nombre;
            $anexo->anex_nomb_archivo = $nombre;
            $auxnumero                = $anexo->obtenerMaximoNumeroAnexo($nurad);
            $anexoCodigo              = $anexo->anexarFilaRadicado($auxnumero);
            $nomFinal                 = $anexo->get_anex_nomb_archivo();

            //Guardar el archivo en la carpteta ya creada
            $Grabar_path  = $adjuntos.$nomFinal;
            if (move_uploaded_file($tmporal, $ruta_raiz.$Grabar_path)) {
              //si existen adjuntos los agregamos para enviarlos por correo
              $mail->AddAttachment($ruta_raiz."/".$Grabar_path, $nombre);
            }else {
              $errores .= empty($errores)? "&error=6" : '-6';
            }
          } else {
            $errores .= empty($errores)? "&error=5" : '-5';
          }
        }
      }

      if(empty($bandera)) {
        $errores .= empty($errores)? "&error=4" : '-4';
      }
    } else {
      $errores .= empty($errores)? "&error=3" : '-3';
    }

    $contador ++;
  }
}

// AGREGAR LOS ADJUNTOS AL RADICADO
$auxnumero    = $anex->obtenerMaximoNumeroAnexo($nurad);

do{
    $auxnumero += 1;
    $codigo     = trim($numRadicadoPadre) . trim(str_pad($auxnumero, 5, "0", STR_PAD_LEFT));
} while ($anex->existeAnexo($codigo));

$isql = "INSERT INTO ANEXOS (SGD_REM_DESTINO,
                            ANEX_RADI_NUME,
                            ANEX_CODIGO,
                            ANEX_ESTADO,
                            ANEX_TIPO,
                            ANEX_TAMANO,
                            ANEX_SOLO_LECT,
                            ANEX_CREADOR,
                            ANEX_DESC,
                            ANEX_NUMERO,
                            ANEX_NOMB_ARCHIVO,
                            ANEX_BORRADO,
                            ANEX_SALIDA,
                            SGD_DIR_TIPO,
                            ANEX_DEPE_CREADOR,
                            SGD_TPR_CODIGO,
                            ANEX_FECH_ANEX,
                            SGD_APLI_CODI,
                            SGD_TRAD_CODIGO,
                            RADI_NUME_SALIDA,
                            ANEX_TIPO_ENVIO,
                            SGD_EXP_NUMERO)
                    values ($radicado_rem,
                            $numRadicadoPadre,
                            '$codigo',
                            2,
                            '$tipo',
                            $tamano,
                            '$auxsololect',
                            '$usua',
                            '$descr',
                            $auxnumero,
                            '$ruta',
                            'N',
                            1,
                            $radicado_rem,
                            $coddepe,
                            0,
                            $sqlFechaHoy,
                            NULL,
                            0,
                            $nurad,
                            $medioRadicar,
                            NULL)";
$bien = $db->conn->Execute($isql);

// Si actualizo BD correctamente
if (!$bien) {

  $errores .= empty($errores)? "&error=7" : '-7';
} else {

  $ruta   = $codigo . '.pdf';
  $actualizar_anexo = "UPDATE ANEXOS
                          SET ANEX_NOMB_ARCHIVO = '$ruta'
                          WHERE ANEX_CODIGO = '$codigo'";
  $anexo_result = $db->conn->Execute($actualizar_anexo);
  
  if (!$anexo_result)
    exit('Error actualizando el archivo');
  
  $ruta2  = "/bodega/$radano/$depCreadora/docs/" . $ruta;
  
  // Guardando el texto creado
  $archivo_txt = $codigo . '.txt';
  $archivo_grabar_txt = "../bodega/$radano/$depCreadora/docs/" . $archivo_txt;
  $file_content   = fopen($archivo_grabar_txt, 'w');
  $write_result   = fwrite($file_content, $respuesta);
  $closing_result = fclose($file_content);
}

// Remplazar datos en el documento
$respuesta = str_replace('*F_RAD_S*', $fecharad, $respuesta);
//$respuesta = str_replace('*RAD_S*', $nurad, $respuesta);
$respuesta = str_replace('*DIGNATARIO*', $dignatario, $respuesta);
$respuesta = str_replace('*REFERENCIA*', $referencia, $respuesta);
$respuesta = str_replace("\xe2\x80\x8b", '', $respuesta);

// CREACION DE PDF RESPUESTA AL RADICADO
$cond = "SELECT
            DEP_SIGLA,
            DEPE_NOMB
         FROM
            DEPENDENCIA
         WHERE
            DEPE_CODI = $coddepe";

$exte       = $db->conn->Execute($cond);
$dep_sig  = $exte->fields["DEP_SIGLA"];
$dep_nom  = $exte->fields["DEPE_NOMB"];

#echo "------->>>>".$nurad; exit;

require_once($ruta_raiz."/respuestaRapida/gencodebar/html/BCGcode128.php");

/* EN ESTA PARTE CREO EL CODIGO DE BARRAS MEDIANTE DE FUENTES
 $barcodeobj = new TCPDFBarcode($nurad, 'C128');
 $barcode = $barcodeobj->getBarcodeHTML(1, 20, 'black'); 
 #echo $barcode;
 #echo "------------";
 $barcodeobj2 = new TCPDFBarcode($nurad, 'C128');
 $barcode2 = $barcodeobj->getBarcodeHTML(2, 20, 'black'); 
 #echo $barcode2; 
 #echo "------------";
 $barcodeobj3 = new TCPDFBarcode($nurad, 'C128');
 $barcode3 = $barcodeobj->getBarcodeHTML(1, 40, 'black'); 
 #echo $barcode3; 
 #echo "------------";
 */
 $barcodeobj4 = new TCPDFBarcode($nurad, 'C128');
 $barcode4 = $barcodeobj->getBarcodeHTML(2, 40, 'black'); 
 /* #echo $barcode4;
 #exit;
 #echo "--".gettype($barcode4);
 #exit;

 $barcode4 = "


 ";*/
// Remplazar datos en el documento
$respuesta = str_replace('*F_RAD_S*', $fecharad, $respuesta);
$respuesta = str_replace('*RAD_S*', $barcode4, $respuesta);
#$respuesta = str_replace('RAD_S', $nurad, $respuesta);
$respuesta = str_replace('RAD_S', $nurad, $respuesta);
$respuesta = str_replace('*DIGNATARIO*', $dignatario, $respuesta);
$respuesta = str_replace('*REFERENCIA*', $referencia, $respuesta);
$respuesta = str_replace("\xe2\x80\x8b", '', $respuesta);

#echo $respuesta; exit;


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

  //Page header
  public function Header() {
    // Logo
    $this->Image(LOGO_METROVIVIENDA,
                  100,
                  10,
                  25,
                  0,
                  'png',
                  '',
                  'T',
                  false,
                  300,
                  '',
                  false,
                  false,
                  0,
                  false,
                  false,
                  false);
    
    $this->Image(PIE_METROVIVIENDA,
                  25,
                  250,
                  167,
                  '',
                  'png',
                  '',
                  'T',
                  false,
                  300,
                  '',
                  false,
                  false,
                  0,
                  false,
                  false,
                  false);
  }

  // Page footer
  public function Footer() {
    // Position at 15 mm from bottom
    $this->SetY(-20);
  }
}

// create new PDF document
$pdf = new MYPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($setAutor);
$pdf->SetTitle($SetTitle);
$pdf->SetSubject($SetSubject);
$pdf->SetKeywords($SetKeywords);

//Agrego la Fuente para el CODIGO DE BARRASS
$pdf->addTTFfont($ruta_raiz.'/tcpdf/code128.ttf', '', '', 32);//TrueTypeUnicode
#$pdf->SetFont('code128', '', 20, '', true) ;

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// set default font subsetting mode
$pdf->setFontSubsetting(true);


// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// CODE 128 AUTO


$pdf->Ln(2);

//RADICACIÓN CON LA FUNCIÓN TCPDF
#$style['position'] = 'R';
#$pdf->write1DBarcode($nurad, 'C128A', '', '', '', 15, 0.4, $style, 'N');


// output the HTML content
$pdf->writeHTML($respuesta, true, false, true, false, '');

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($ruta_raiz.$ruta2, 'F');

$sqlE = "UPDATE
            RADICADO
         SET
            RADI_PATH = '$ruta3'
         WHERE
            RADI_NUME_RADI = $nurad";

  $db->conn->Execute($sqlE);

  $isqlDepR = "SELECT RADI_DEPE_ACTU,
                      RADI_USUA_ACTU
                  FROM RADICADO
                  WHERE RADI_NUME_RADI = '$nurad'";

  $rsDepR = $db->conn->Execute($isqlDepR);

  $coddepe = $rsDepR->fields['RADI_DEPE_ACTU'];
  $codusua = $rsDepR->fields['RADI_USUA_ACTU'];
?>
