<?php
session_start();

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;
 // error_reporting(0); 
$recordSet= array();
$recordWhere= array();
	$ruta_raiz = ".."; 
	if($verrad)
	{
		$ent = substr($verrad,-1);
	}
	 include_once("$ruta_raiz/include/db/ConnectionHandler.php");
	$db = new ConnectionHandler("$ruta_raiz");
	define('ADODB_FETCH_ASSOC',2);
   	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	include_once "$ruta_raiz/include/tx/Historico.php";
	$objHistorico= new Historico($db);
	$arrayRad = array();
	$arrayRad[]=$verrad;
	$fecha_hoy = Date("Y-m-d");
	$sqlFechaHoy=$db->conn->DBDate($fecha_hoy);
	if  (count($recordSet)>0)
    	array_splice($recordSet, 0);  		
	if  (count($recordWhere)>0)
    	array_splice($recordWhere, 0);

	$encabezadol = "$PHP_SELF?".session_name()."=".session_id()."&krd=$krd&verrad=$verrad&dependencia=$dependencia&codusuario=$codusuario&depende=$depende&ent=$ent&numRadi=$numRadi&codiTRDEli=$codiTRDEli&tipVinDocto=$tipVinDocto&mostrar_opc_envio=$mostrar_opc_envio&nomcarpeta=$nomcarpeta&carpeta=$carpeta&datoVer=$datoVer&leido=$leido"; 

	?>
<html>
<head>
<title>Vinculacion Documento</title>
<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
<script>

function regresar(){   	
	document.VincDocu.submit();
}
</script>
</head>
<body>
<div id="content" style="opacity: 1;">
    <div class="row">
        <div class="col-sm-12">
    <!-- widget grid -->
    <section id="widget-grid">
        <!-- row -->
        <div class="row">
            <!-- NEW WIDGET START -->
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget jarviswidget-color-darken" id="wid-id-1" data-widget-editbutton="false">

                    <header>
                        <h2>
                            Vinculaci&oacute; Documentos
                        </h2>
                    </header>

                    <form method="post" action="<?=$encabezadol?>" name="VincDocu" class="smart-form">

                      <?
                     //Incluye una nueva vinculacion entre dos Radicados o Modifica una existente
                      if ($insertar_registro && $numRadi !='' ){
                        //Verificar la existencia del Radicado con el cual se va a realizar la vinculacion del documento
                        $isqlB = "select * FROM RADICADO
                                  where RADI_NUME_RADI = '$numRadi'";
                        $rsB=$db->query($isqlB);
                        $numRadiBusq = $rsB->fields["RADI_NUME_RADI"];
                        if($numRadiBusq=='')
                          {
                            $mensaje = "<hr><center><b><span class='alarmas'>No se encontro el radicado, por favor verifique e intente de nuevo</span></center></b></hr>";
                          }
                        else
                          {
                            //Verificar la seleccion del tipo de vinculo
                            if($tipVinDocto==0)
                              {
                                $mensaje = "<hr><center><b><span class='alarmas'>Debe seleccionar un Tipo de Vinculacion</span></center></b></hr>";
                              }
                            else
                              {
                                 if($tipVinDocto==1)
                                   {
                                     $tipVinDocto = 0 ;
                                   }
                                 $isqlM = "select * FROM RADICADO
                                           where RADI_NUME_RADI = '$verrad'";
                                 $rsM=$db->query($isqlM);
                                 $numRadiBusq = $rsM->fields["RADI_NUME_RADI"];
                                 if($numRadiBusq != '')
                                   {
                                      if  (count($recordSet)>0)
                                           array_splice($recordSet, 0);
                                      if  (count($recordWhere)>0)
                                          array_splice($recordWhere, 0);
                                      $radiDeriAnte = $rsM->fields["RADI_NUME_DERI"];
                                      $tipoDeriAnte = $rsM->fields["RADI_TIPO_DERI"];
                                      //Actualiza el vinculo de documentos en la Tabla Radicados
                                      $recordSet["RADI_NUME_DERI"] = $numRadi;
                                      $recordSet["RADI_TIPO_DERI"] = $tipVinDocto;
                                      $recordWhere["RADI_NUME_RADI"] = $verrad;
                                      $ok = $db->update("RADICADO", $recordSet,$recordWhere);
                                      array_splice($recordSet, 0);
                                      array_splice($recordWhere, 0);
                                      if ($tipVinDocto==0)
                                         {$detaTipoVin = "Anexo de";}
                                      if ($tipVinDocto==2)
                                         {$detaTipoVin = "Asociado de";}
                                      if($ok)
                                        {
                                          $mensaje = "<hr><center><b><span class=info>Vinculacion Documento Actualizado</span></center></b></hr>";
                                          if ($radiDeriAnte=='')
                                             {
                                               $observa = "*Se incluyo Vinculacion Documento* ($numRadi) Tipo ($detaTipoVin)";
                                             }
                                          else
                                             {
                                               $observa = "*Cambio Vinculacion Documento* Anterior($radiDeriAnte) por ($numRadi)";
                                             }
                                          $codusdp = str_pad($dependencia, 3, "0", STR_PAD_LEFT).str_pad($codusuario, 3, "0", STR_PAD_LEFT);
                                          $objHistorico->insertarHistorico($arrayRad,$dependencia ,$codusuario, $dependencia,$codusuario, $observa, 38);
                                         }
                                    }
                                    else
                                    {
                                      $mensaje = "<hr><center><b><span class=info>No se pudo actualizar el Radicado</span></center></b></hr>";
                                    }
                               }
                          }
                         }

                        ?>

                        <table class="table table-bordered table-striped">
                          <tr >
                          <td class="titulos5" >Tipo </td>
                          <td class=listado5 >
                          <select  name='tipVinDocto'  class='select'>
                             <?php
                               if($tipVinDocto==0){$datosel=" selected ";}else {$datosel=" ";}
                                 echo "<option value='0' $datosel><font>-Seleccione-</font></option>";
                               if($tipVinDocto==1){$datosel=" selected ";}else {$datosel=" ";}
                                 echo "<option value='1' $datosel><font>Anexo</font></option>";
                               if($tipVinDocto==2){$datosel=" selected ";}else {$datosel=" ";}
                                echo "<option value='2' $datosel><font>Asociado</font></option>";
                             ?>
                            </select>

                          </td>
                         </tr>
                       <tr>
                         <td class="titulos5" >No. de Radicado</td>
                         <td class=listado5 >
                          <input name="numRadi" type="text" size="20" class="tex_area" value="<?=$numRadi?>">
                         </td>
                         </tr>
                       </table>
                    <br>
                        <table class="table table-bordered table-striped">
                          <tr align="center">
                            <td width="33%" height="25" class="listado2" align="center">
                             <center><input name="insertar_registro" type=submit class="botones_funcion" value="Grabar Cambio "></center></TD>
                             <td width="33%" class="listado2" height="25">
                             <center><input name="actualizar" type="button" class="botones_funcion" id="envia23" onClick="procModificar();"value=" Busqueda "></center></TD>
                            <td width="33%" class="listado2" height="25">
                             <center><input name="aceptar" type="button" class="botones_funcion" id="envia22" onClick=" opener.regresar();window.close();"value=" Cancelar "></center></TD>
                           </tr>
                        </table>
                        <table class="table table-bordered table-striped">
                          <tr align="center">
                            <td>
                            <?
                            include_once "$ruta_raiz/vinculacion/lista_tiposVinculados.php";
                            ?>
                            </td>
                           </tr>
                        </table>

                        <table class="table table-bordered table-striped">
                            <tr>
                                <td colspan="2" class='celdaGris' >
                                    <? echo $mensaje; ?>
                                </td>
                            </tr>
                        </table>
                </div>
            </article>
        </div>
    </section>
</div>
    </div>
</div>
<script>
function borrarArchivo(anexo,linkarch){
	if (confirm('Esta seguro de borrar este Registro ?'))
	{
		nombreventana="ventanaBorrarVin";
		url="mod_vinculacion_transacc.php?borrar=1&usua=<?=$krd?>&codusuario=<?=$codusuario?>&dependencia=<?=$dependencia?>&verrad=<?=$verrad?>&codiVinEli="+anexo+"&linkarchivo="+linkarch;
		window.open(url,nombreventana,'height=250,width=300');
	}
return;
}
function procModificar()
{
	nombreventana="ventanaBusqAV";
	url="../busqueda/busquedaPiloto.php?indiVinculo=1&etapa=1&krd=<?=$krd?>&codusuario=<?=$codusuario?>&dependencia=<?=$dependencia?>&carpeAnt=<?=$carpeta?>&verrad=<?=$verrad?>&s_Listado=VerListado&fechah=$fechah&mostrar_opc_envio=<?=$mostrar_opc_envio?>&nomcarpeta=<?=$nomcarpeta?>&datoVer=<?=$datoVer?>&leido=<?=$leido?>";
	window.open(url,nombreventana,'height=600,width=770,scrollbars=yes');
return;
}

</script>
</form>
</span>
<p>
<?=$mensaje_err?>
</p>
</span>
</body>
</html>
