<?
session_start(); 
session_cache_limiter('public');
$ruta_raiz = "..";
if (!$krd or !$dependencia)   include "../rec_session.php";
if(!$tipo_archivo) $tipo_archivo = 0;
?>

<html>
<head>
  <meta http-equiv="Cache-Control" content="cache">
  <meta http-equiv="Pragma" content="public">
<?
$fechah=date("dmy") . "_". date("h_m_s");
$encabezado = session_name()."=".session_id()."&buscar_exp=$buscar_exp&krd=$krd&tipo_archivo=$tipo_archivo&nomcarpeta=$nomcarpeta";
?>
<script>
   function sel_dependencia()
   {
      document.write("<form name=forma_b_correspondencia action='cuerpo_exp.php?<?=$encabezado?>'  method=post>");
	  depsel = form1.dep_sel.value ;  

	  document.write("<input type=hidden name=depsel value="+depsel+">");
	  document.write("<input type=hidden name=estado_sal  value=3>");
	  document.write("<input type=hidden name=estado_sal_max  value=3>");
	  document.write("<input type=hidden name=fechah value='<?=$fechah?>'>");	  	  	  
	  document.write("</form>");	  
	  forma_b_correspondencia.submit();
   }

</script>
<?php  
   error_reporting(0);  
  ?> 
<link rel="stylesheet" href="../../estilos_totales.css">
<?PHP

 if(!$estado_sal)   {$estado_sal=2;}
 if(!$estado_sal_max) $estado_sal_max=3; 
    $accion_sal = "Marcar como Archivado Fisicamente";
	$pagina_sig = "envio.php"; 
	//if(!$dep_sel) $dep_sel = $dependencia;
	$buscar_exp = trim($buscar_exp);
	  if ($dep_sel==0) $dep_sel = "%";
	  $dependencia_busq1= " and d.sgd_exp_estado=$tipo_archivo and d.depe_codi like '$dep_sel'  and (upper(d.sgd_exp_numero) like '%$buscar_exp%' )";
	  $dependencia_busq2= " and d.sgd_exp_estado=$tipo_archivo and d.depe_codi like '$dep_sel'  and (upper(d.sgd_exp_numero) like '%$buscar_exp%' )";	
$tbbordes = "#CEDFC6";
$tbfondo = "#FFFFCC";
if(!$orno){$orno=1;}
$imagen="flechadesc.gif";
?> 
<script>
<!-- Esta funcion esconde el combo de las dependencia e inforados Se activan cuando el menu envie una seal de cambio.-->
 
function window_onload()
		
{
   
   form1.depsel.style.display = '';
   form1.enviara.style.display = '';
   form1.depsel8.style.display = 'none';
   form1.carpper.style.display = 'none';
   setVariables();
  setupDescriptions();
}
<!-- Cuando existe una sean de cambio el program ejecuta esta funcion mostrando el combo seleccionado -->
function changedepesel()
{
  form1.depsel.style.display = 'none';
  form1.carpper.style.display = 'none';
  form1.depsel8.style.display = 'none';
  if(form1.enviara.value==10)
  {
    form1.depsel.style.display = 'none';
	form1.carpper.style.display = '';
	form1.depsel8.style.display = 'none';	
  }
  if(form1.enviara.value==9 )
  {
    form1.depsel.style.display = '';
	form1.carpper.style.display = 'none';
	form1.depsel8.style.display = 'none';
  }  
 if(form1.enviara.value==8 )
  {
  form1.depsel.style.display = 'none';
	form1.depsel8.style.display = '';
	form1.carpper.style.display = 'none';
  }  
}
<!-- Funcion que activa el sistema de marcar o desmarcar todos los check  -->
function markAll()
{
if(form1.marcartodos.checked==1)
for(i=4;i<form1.elements.length;i++)
form1.elements[i].checked=1;
else
    for(i=4;i<form1.elements.length;i++)
  	form1.elements[i].checked=0;
}
<?php
   //include "libjs.php";
	 function tohtml($strValue)
{
  return htmlspecialchars($strValue);
}
?>
</script>
<style type="text/css">
<!--
.textoOpcion {  font-family: Arial, Helvetica, sans-serif; font-size: 8pt; color: #000000; text-decoration: underline}
-->
</style>
</head>

<body bgcolor="#FFFFFF" topmargin="0" >
<div id="object1" style="position:absolute; visibility:show; left:10px; top:-50px; width=80%; z-index:2" > 
  <p>Cuadro de Historico</p>
</div>
<?php
 /* 
 PARA EL FUNCIONAMIENTO CORRECTO DE ESTA PAGINA SE NECESITAN UNAS VARIABLE QUE DEBEN VENIR 
 carpeta  "Codigo de la carpeta a abrir"
 nomcarpeta "Nombre de la Carpeta"
 tipocarpeta "Tipo de Carpeta  (0,1)(Generales,Personales)"
 

 seleccionar todos los checkboxes
*/

    include "../../config.php";
		 $img1="";$img2="";$img3="";$img4="";$img5="";$img6="";$img7="";$img8="";$img9="";
         IF($ordcambio){IF($ascdesc=="DESC" ){$ascdesc="";	$imagen="flechaasc.gif";}else{$ascdesc="DESC";$imagen="flechadesc.gif";}}
		 if($orno==1){$order=" d.sgd_exp_numero $ascdesc";$img1="<img src='../../iconos/$imagen' border=0 alt='$data'>";}
		 if($orno==2){$order=" a.radi_nume_radi $ascdesc";$img2="<img src='../../iconos/$imagen' border=0 alt='$data'>";}
		 if($orno==3){$order=" a.radi_fech_radi $ascdesc";$img3="<img src='../../iconos/$imagen' border=0 alt='$data'>";}
		 if($orno==4){$order=" a.ra_asun $ascdesc";$img4="<img src='../../iconos/$imagen' border=0 alt='$data'>";}
		 if($orno==5){$order=" d.sgd_exp_estado $ascdesc,a.radi_nume_radi ";$img5="<img src='../../iconos/$imagen' border=0 alt='$data'>";}
		 if($orno==6){$order=" f.usua_nomb $ascdesc";$img6="<img src='../../iconos/$imagen' border=0 alt='$data'>";}
 		 if($orno==9){$order=" f.usua_nomb $ascdesc";$img9="<img src='../../iconos/$imagen' border=0 alt='$data'>";}
		 if($orno==11){$order=" d.sgd_exp_fech $ascdesc";$img11="<img src='../../iconos/$imagen' border=0 alt='$data'>";}
		 if($orno==12){$order=" d.sgd_exp_fech_arch $ascdesc";$img12="<img src='../../iconos/$imagen' border=0 alt='$data'>";}
		 if($tipo_archivo==0){$img7=" <img src='../../iconos/flechanoleidos.gif' border=0 alt='$data'> ";}
		 
		 if($tipo_archivo==1){$img7=" <img src='../../iconos/flechanoleidos.gif' border=0 alt='$data'> ";}		 		 

  $datosaenviar = "buscar_exp=$buscar_exp&fechaf=$fechaf&tipo_carp=$tipo_carp&ascdesc=$ascdesc&orno=$orno";
  $encabezado = session_name()."=".session_id()."&buscar_exp=$buscar_exp&krd=$krd&fechah=$fechah&ascdesc=$ascdesc&dep_sel=$dep_sel&tipo_archivo=$tipo_archivo&nomcarpeta=$nomcarpeta&orno=";
    $fechah=date("dmy") . "_". date("h_m_s");

	ora_commiton($handle); 
	$cursor = ora_open($handle);
	$check=1;
	$fechaf=date("dmy") . "_" . date("hms");
    $numeroa=0;$numero=0;$numeros=0;$numerot=0;$numerop=0;$numeroh=0;
    $isql = "select * From usuario where  USUA_LOGIN ='$krd' and USUA_SESION='". substr(session_id(),0,29)."' ";
	$resultado = ora_parse($cursor,$isql);
	$resultado = ora_exec($cursor);
	$row=array();
  ora_fetch_into($cursor,$row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	// Validacion de Usuario y COntrasea MD5
	//echo "** $krd *** $drde";
  if (trim($row["USUA_LOGIN"])==trim($krd))
		{
		

		$nombusuario =$row["USUA_NOMB"];		
		$contraxx=$row["USUA_PASW"];

		$nivelus=$row["CODI_NIVEL"];
		if($row["USUA_NUEVO"]=="1"){		
				?>

  
<br>
<table border=0 width='100%' class='t_bordeGris' align='center' bgcolor="#CCCCCC">
  <tr >
    <td height="20" > 
      
          <?php
	     /** Instruccion que realiza la consulta de radicados segun criterios
		   * Tambien observamos que se encuentra la varialbe $carpetaenviar que maneja la carpeta 11.
		   */
		 $limit = ""; 
	   $isql = "select d.sgd_exp_numero,d.sgd_exp_estado,a.radi_path,d.RADI_NUME_RADI ,a.RADI_NUME_HOJA
	                  ,a.RADI_FECH_RADI,a.RADI_NUME_RADI,a.RA_ASUN  ,a.RADI_PATH   
					  ,a.RADI_USUA_ACTU ,TO_CHAR(a.RADI_FECH_RADI,'DD/MM/YY HH:MIam') AS FECHA 
					  ,b.sgd_tpr_descrip      ,b.sgd_tpr_codigo,b.sgd_tpr_termino
					  ,ROUND(((radi_fech_radi+(b.sgd_tpr_termino * 7/5))-SYSDATE)) AS diasr 
					  ,RADI_LEIDO,RADI_TIPO_DERI,RADI_NUME_DERI,a.radi_depe_actu,
					  e.depe_nomb,f.usua_nomb,d.sgd_exp_fech_arch,d.sgd_exp_fech
			   from radicado a,sgd_tpr_tpdcumento b,SGD_EXP_EXPEDIENTE d, DEPENDENCIA e,USUARIO f
			   where 
			    f.usua_codi=a.radi_usua_actu and f.depe_codi=a.radi_depe_actu
				and e.depe_codi=a.radi_depe_actu 
				and a.tdoc_codi=b.sgd_tpr_codigo
				AND a.radi_nume_radi=d.radi_nume_radi
				$dependencia_busq1
				order by $order
				";
		?> 
				
     
	    <table BORDER=0  cellpad=2 cellspacing='0' WIDTH=98% class='t_bordeGris' valign='top' align='center' >
          <TR >
            <TD width='33%' height="37" > 
              <table width='100%' border='0' cellspacing='1' cellpadding='0'>
                <tr> <?
	     IF($nomcarpeta=="")
			 {
			      $nomcarpeta=" Expedientes ";
			 }

  ?> 
                  <td height="20" class="celdaGris"><img src="../../imagenes/listado.gif" width="85" height="20"></td>
                </tr><tr>
                  <td height="20" class="tituloListado"><span class="etextomenu"><?=$nomcarpeta ?> 
                    </span></td>
                </tr>
              </table>
            </td>
            <TD width='33%' height="37" > 
              <table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="10%" class="celdaGris" height="20"><img src="../../imagenes/usuario.gif" width="58" height="20"></td>
                </tr><tr>
                  <td width="90%" height="20"><span class='etextomenu'><?=$nombusuario ?></span></td>
                </tr>
              </table>
            </td>
            <td height="37" width="33%"> 
              <table width="100%" border="0" cellspacing="1" cellpadding="0">
			  
                <tr> 
                  <td width="16%" class="celdaGris" height="20"> <span class="etextomenu"> <b>Dependencia </span></td>
                </tr><tr>
                  <td width="84%" height="20"><span class='etextomenu'>
                    <? 
					  echo "$depe_nomb";
				    ?>
</span></td>
                </tr>
              </table>
            </TD>
          </tr>
        </table>
        <br>
	 <form name='form1' action='cuerpo_exp.php?<?=session_name()."=".session_id()."&krd=$krd&fechah=$fechah&encabezado&$orno" ?>' method="post">
        <TABLE width="98%" align="center" cellspacing="0" cellpadding="0">
          <tr> 
            <TD class="grisCCCCCC" height="58"> 
              <table BORDER=0  cellpad=2 cellspacing='2' WIDTH=98% class='t_bordeGris' valign='top' align='center' cellpadding="2" >
                <tr bgcolor="#CCCCCC"> 
                  <td width='50%' align='left' height="40" > <img src="../../imagenes/listar.gif" width="73" height="20"> 
                    <a href='cuerpo_exp.php?<?=$encabezado.$orno?>&tipo_archivo=0' alt='Ordenar Por Leidos'><span class='tparr'>
					<?
					if ($tipo_archivo==0) echo  "$img7"; 
					?>
					Por Archivar</span></a> 
                    <? if ($tipo_archivo==1)  echo "$img7"; ?> <a href='cuerpo_exp.php?<?=$encabezado.$orno?>&tipo_archivo=1' class="tpar" alt='Ordenar Por Leidos'><span class='tpar'> 
					<?
					if ($tipo_archivo==1)  echo "<b>"; else echo "</b>";
					?>                    
					Archivados</span></a><span class='tparr'><br>
					Buscar Expediente </span>
					<input type=text name=buscar_exp value='<?=$buscar_exp?>' class="cajac">
					<input type=submit value='Buscar' name=Enviar valign='middle' class='ebuttons2'></td>
                  <td width='35%' align="right"><span class='etextomenu'>
				  <span class="etextomenu"><b>Dependencia que pide el archivo del documento </span>
                    <select name='dep_sel' class='ebuttons2' onChange='submit();' >
					<OPTION VALUE=0 >Todas las Dependencias </OPTION>
                      <?
 							ora_commiton($handle); 
							$cursor = ora_open($handle);
							ora_parse($cursor,"select depe_codi,depe_nomb from DEPENDENCIA ORDER BY DEPE_NOMB");
							ora_exec($cursor);
							$numerot = ora_numrows($cursor);
							$row1 = array();	
							$result1=ora_fetch_into($cursor,$row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);					  
							$dependencianomb=substr($dependencianomb,0,35);
							$result1=ora_fetch_into($cursor,$row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
							DO
							   {
									$depcod = $row1["DEPE_CODI"];
										$depdes = substr($row1["DEPE_NOMB"],0,35);
										IF ($depcod==$dep_sel){
										   $datosdep = " selected "; 
										}else {$datosdep="";}
							  		  echo "<option value=$depcod $datosdep>$depdes</option>\n";
									 }while($result1=ora_fetch_into($cursor,$row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
							  ?>
                    </select>
                  </span> 
			      </td>
				   
                </TR>
              </TABLE>
            </td>
          </tr>
          <tr> 
            <td class="grisCCCCCC"> <?
	  ora_parse($cursor,$isql);
	ora_exec($cursor);
	$row = array();
	// Encabezado de la lista de documentos
	// Cada encabezado tiene un href que permite recargar la pagina con otro orden. 



		 ?> 
              <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr valign="bottom"> 
                  <td>&nbsp;</td>
                </tr>
              </table>
              <table border=0 cellspace=2 cellpad=2 WIDTH=98% class='t_bordeGris' align='center' bgcolor="#CCCCCC">
                <tr bgcolor="#cccccc" class="textoOpcion"> <?

		  ?> 
                   <td  align="center"> 
				  <a href='cuerpo_exp.php?<?=$encabezado ?>1&ordcambio=1' class='textoOpcion' alt='Seleccione una busqueda'>                    </a><a href='cuerpo_exp.php?<?=$encabezado ?>2&ordcambio=1' class='textoOpcion' alt='Seleccione una busqueda'>
                    <?=$img2 ?>
Radicado Entrada</a> <a href='cuerpo_exp.php?<?=$encabezado ?>1&ordcambio=1' class='textoOpcion' alt='Seleccione una busqueda'>
</a></td>

                  <td  width='18%' align="center"> <a href='cuerpo_exp.php?<?=$encabezado ?>3&ordcambio=1' class='textoOpcion' alt='Seleccione una busqueda'> 
                    <?=$img3 ?> Fecha Radicado</a> </td>                  <td width='10%' align="center"> <a href='cuerpo_exp.php?<?=$encabezado ?>2&ordcambio=1' class='textoOpcion' alt='Seleccione una busqueda'> 
                    </a><a href='cuerpo_exp.php?<?=$encabezado ?>1&ordcambio=1' class='textoOpcion' alt='Seleccione una busqueda'>
                    <?=$img1 ?>
Expediente</a> </td>
<td width='10%' align="center"> <a href='cuerpo_exp.php?<?=$encabezado ?>2&ordcambio=1' class='textoOpcion' alt='Seleccione una busqueda'> 
                    </a><a href='cuerpo_exp.php?<?=$encabezado ?>11&ordcambio=1' class='textoOpcion' alt='Seleccione una busqueda'>
                    <?=$img11 ?>
Fecha Clasificaci&oacute;n </a> </td>
                  <td  width='20%' align="center"> <a href='cuerpo_exp.php?<?=$encabezado ?>4&ordcambio=1' class='textoOpcion'  alt='Seleccione una busqueda'> 
                    <?=$img4 ?> Descripcion </a></td>
                  <td  width='15%' align="center">  <a href='cuerpo_exp.php?<?=$encabezado ?>5&ordcambio=1' class='textoOpcion'  alt='Seleccione una busqueda'>
                    <?=$img5 ?>
                    Archivado ? </a></td>
                  <td  width='15%' align="center">  <a href='cuerpo_exp.php?<?=$encabezado ?>12&ordcambio=1' class='textoOpcion'  alt='Seleccione una busqueda'>
                    <?=$img12 ?>
                    Fecha de Archivo </a></td>					
                </tr>
                <?
		 $row = array();
		 $i = 1;
		 $ki=0;
	// Comienza el siclo para mostrar los documentos de la parpeta predeterminada.
	     $registro=$pagina*20;
   while($result1=ora_fetch_into($cursor,$row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC))
	 { 
			if($ki>=$registro and $ki<($registro+20)){
			$data = trim($row["RADI_NUME_RADI"]);
			$numdata =  trim($row["CARP_CODI"]);
	 		$plg_codi = $row["PLG_CODI"];
	 		$plt_codi = $row["PLT_CODI"];			
			$num_expediente = $row["SGD_EXP_NUMERO"];
			$imagen_rad = $row["RADI_PATH"];
			$usuario_actual = $row["USUA_NOMB"];
			$dependencia_actual = $row["DEPE_NOMB"];			
			$estado = $row["SGD_EXP_ESTADO"];
			$fecha_archivo = $row["SGD_EXP_FECH_ARCH"];
			$fecha_clasificacion = $row["SGD_EXP_FECH"];
			if($estado==0) $estado_nomb="No"; else  $estado_nomb="Si";
			
			if($plt_codi==2){$img_estado = "<img src='../../imagenes/docRadicado.gif'  border=0>"; }
			if($plt_codi==3){$img_estado = "<img src='../../imagenes/docImpreso.gif'  border=0>"; }			
			if($plt_codi==4){$img_estado = "<img src='../../imagenes/docEnviado.gif ' border=0>"; }			
			if($row["SGD_TPR_CODIGO"]==9999)
			{
			   if($plt_codi==2){$img_estado = "<img src=../../imagenes/docRecibido.gif  border=0>"; }			
			   if($plt_codi==2){$img_estado = "<img src=../../imagenes/docRadicado.gif  border=0>"; }
			   if($plt_codi==3){$img_estado = "<img src=../../imagenes/docImpreso.gif  border=0>"; }			
			   if($plt_codi==4){$img_estado = "<img src=../../imagenes/docEnviado.gif  border=0>"; }			
			
			   $dep_radicado = substr($row["RADI_NUME_RADI"],4,3);
			   $ano_radicado = substr($row["RADI_NUME_RADI"],0,4);			   
			   
			   $ref_pdf = "bodega/$ano_radicado/$dep_radicado/docs/$ref_pdf";
			   $tipo_sal = "Archivo";
			   $ref_pdf_salida = "<a href='../../bodega/$ano_radicado/$dep_radicado/docs/$ref_pdf' alt='Radicado de Salida $rad_salida'>$img_estado</a>";
			}
			else
			{
			   $tipo_sal = "Plantilla";			
			  $ref_pdf_salida = "<a href='../../$ref_pdf' alt='Radicado de Salida $rad_salida'>$img_estado</a>";
			}
			//$ref_pdf_salida = "<a href='imprimir_pdf_frame?".session_name()."=".session_id() . "&ref_pdf=$ref_pdf&numrad=$numrad'>$img_estado </a>";
            if($data =="") $data = "NULL";
			error_reporting(0);
			 $numerot = $row1["num"];
			 if($estado==0){$leido="r";} else {$leido="";}
             if($i==1){
			    $leido ="tpar$leido";
				$i=2;
			 }else{
			    $leido ="timpar$leido";
   
				$i=1;
			 }
			 $urlimagen = "<a href='../../bodega".$row["RADI_PATH"]."?fechah=$fechah' class='$leido' >$data</a>";
			 ?> 
                <tr class='<?=$leido?>'> <?
			 $radi_tipo_deri = $row["RADI_TIPO_DERI"];
			 $radi_nume_deri = $row["RADI_NUME_DERI"];
			 ?> 
                  <td class='<?=$leido ?>' align="right" width="12%"><?=$urlimagen?>
                    <?
		      	$cursor3 = ora_open($handle);
				$isql3 ="select to_char(HIST_FECH,'DD/MM/YY HH12:MI:SSam')as HIST_FECH,HIST_FECH AS HIST_FECH1,HIST_OBSE from hist_eventos where radi_nume_radi='$data' order by HIST_FECH1 desc ";
				 
				ora_parse($cursor3,$isql3);
				ora_exec($cursor3);
			   $radi_nomb=$row["NOMBRES"] ;
			   ?>                    
                  </td>
                  <td class='<?=$leido ?>' width="10%"  align="right"><? $ruta_raiz="..";?>
                    <span class='$leido'><a href='../../verradicado.php?<?=$encabezado."&num_expediente=$num_expediente&verrad=$data&carpeta_per=0&carpeta=8&nombcarpeta=Expedientes"?>' class='<?=$leido?>'>
                    <?=$row["FECHA"]?>
                  </a></span> </td>
 				  <td class='<?=$leido?>' width="18%">
				  <span class='$leido'><a href='../../verradicado.php?<?=$encabezado."&num_expediente=$num_expediente&verrad=$data&carpeta_per=0&carpeta=8&nombcarpeta=Expedientes"?>' class='<?=$leido?>'> </a></span>
                  <span class="<?=$leido ?>">
                  <?=$num_expediente?>
                  </span> </td>
                  <td class='<?=$leido ?>' width="20%"> <?=$fecha_clasificacion?> 
                  </td>				  
                  <td class='<?=$leido ?>' width="20%"> <?=$row["RA_ASUN"] ?> 
                  </td>

				  <td class='<?=$leido ?>' width="20%"> <?=$fecha_archivo?> 
                  </td>				  
                  <? 
			 if($check<=20){
			 ?> 
                  <?
			 $check=$check+1;
			 }
			 ?> </tr>
                <?
       }
			 $ki=$ki+1;
    }
	 ?> 
              </table>
            </TD>
          </tr>
        </TABLE>

	 </form>
<table border=0 cellspace=2 cellpad=2 WIDTH=98% class='t_bordeGris' align='center'>
        <tr align="center"> 
          <td> <?	 		
	$numerot = ora_numrows($cursor);
	// Se calcula el numero de | a mostrar
	$paginas = ($numerot / 20);
	?><span class='etextou'> Paginas</span> <?
	if(intval($paginas)<=$paginas)
	{$paginas=$paginas;}else{$paginas=$paginas-1;}
	// Se imprime el numero de Paginas.
	for($ii=0;$ii<$paginas;$ii++)
	{
	  if($pagina==$ii){$letrapg="<font color=green size=3>";}else{$letrapg="<font color=blue size=2>";}
	  echo " <a href='cuerpo_exp.php?pagina=$ii&$encabezado$orno'>$letrapg".($ii+1)."</font></a>\n";
	}
	 
	 echo "<input type=hidden name=check value=$check>";
   ?> </td>
        </tr></table>
</td></tr></table>
	  
  
<br>

							 <? 	 
	 
    	  $row = array();
	  }
	  ELSE
	  {  
					   ?> <form name='form1' action='../../enviar.php' method=post>
        <?
					   echo "<input type=hidden name=depsel>";
					   echo "<input type=hidden name=depsel8>";
					   echo "<input type=hidden name=carpper>";		   		   
					   echo "</form>";
					echo "<form action='usuarionuevo.php' method=post name=form2>";
					// Si es un usuario nuevo pide la nueva contrasea.
						if($row["USUA_NUEVO"]=="0")
						{		
						 	 echo "<center><B>USUARIO NUEVO </CENTER>";
							 ECHO "<P><P><center>Por favor introduzca la nueva contrasea<p></p>";
							 echo "<CENTER><input type=hidden name='usuarionew' value=$krd><B>USUARIO $krd<br></p>";
							 echo "<table border=0>";
							 echo "<tr>";
							 echo "<td><center>CONTRASE� </td><td><input type=password name=contradrd vale=''><br></td>";
							 echo "</tr>"				 ;
							 echo "<tr><td><center>RE-ESCRIBA LA CONTRASE� </td><td><input type=password name=contraver vale=''></td>";
							 echo "</tr>";							 
							 echo "</table></p></p>";
							 echo "";
							 echo "";
							 ECHO "<center>Seleccione la dependencia a la cual pertenece \n";
							 

						   ora_commiton($handle); 
						   $cursor = ora_open($handle);
						   ora_parse($cursor,"select depe_codi,depe_nomb from DEPENDENCIA ORDER BY DEPE_NOMB");
						   ora_exec($cursor);
						   $numerot = ora_numrows($cursor);$row1 = array();	
							 
							$result1=ora_fetch_into($cursor,$row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
							 
							
							 echo "<br><b><center>Dependencia <select name='depsel' class='e_buttons'>\n";
							 $dependencianomb=substr($dependencianomb,0,35);
							 echo "<option value=$dependencia>$dependencianomb</option>\n";
							DO
							{
								$depcod = $row1["DEPE_CODI"];
								$depdes = substr($row1["DEPE_NOMB"],0,35);
								echo "<option value=$depcod>$depdes</option>\n";
							 }while($result1=ora_fetch_into($cursor,$row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
							 echo "</select>";
							 echo "<br><input type=submit value=Aceptar>";
							 ?>
      </form> <?

					}else{echo "<input type=hidden name=depsel>";
					      echo "<input type=hidden name=carpper>";
					}					 					
						
		}
	}Else
		{
		   ?><form name='form1' action='../../enviar.php' method=post>
  <div align="center">
    <input type=hidden name=depsel>
    <input type=hidden name=depsel8>
    <input type=hidden name=carpper>
    <span class='etextou'>NO TIENE AUTORIZACION PARA INGRESAR</span><BR>
    <span class='eerrores'><a href='../../login.php' target=_parent><span class="textoOpcion">Por 
    Favor intente validarse de nuevo. Presione aca !</span></a></span> </div>
</form>
           <?
		}
	?>
	 <br> 

 <form name=jh >
 <input type=hidDEN name=jj value=0>
  <input type=hidDEN name=dS value=0>
 </form>
 <script>
function  prueba(Button)
{
   if (event.button == 2) {
  alert("Botn derecho pulsado");
}

}
 </script>
</div></body>
</html>
