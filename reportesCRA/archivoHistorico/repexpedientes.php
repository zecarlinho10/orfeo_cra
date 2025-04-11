<?
/**
 * Programa que despliega el formulario de consulta de ESP
 * @author  YULLIE QUICANO
 * @mail    yquicano@cra.gov.co
 * @author  modify by Aquiles Canto
 * @mail    xoroastro@yahoo.com    
 * @version     1.0
 */
$ruta_raiz = "../../";
session_start();
error_reporting(0);
require_once($ruta_raiz."include/db/ConnectionHandler.php");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

//En caso de no llegar la dependencia recupera la sesi�n
if(empty($_SESSION)) include $ruta_raiz."rec_session.php";

include ("common.php");
$fechah = date("ymd") . "_" . date("hms");

?>
<html>
<head>
<title>CONSULTA EXPEDIENTES</title>
<?include ($ruta_raiz."/htmlheader.inc.php")?>

<script language="JavaScript" type="text/JavaScript">
/**
* Env�a el formulario de acuerdo a la opci�n seleccionada, que puede ser ver CSV o consultar
*/
function enviar(argumento)
{	document.formSeleccion.action=argumento+"&"+document.formSeleccion.params.value;
	document.formSeleccion.submit();
}


function activa_chk(forma)
{	//alert(forma.tbusqueda.value);
	//var obj = document.getElementById(chk_desact);
	if (forma.slc_tb.value == 0)
		forma.chk_desact.disabled = false;
	else
		forma.chk_desact.disabled = true;
}

	function pasar_datos(fecha)
   {
    <?
	 echo " opener.document.VincDocu.numRadi.value = fecha\n";
	echo "opener.focus(); window.close();\n";
	?>
}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body onLoad="crea_var_idlugar_defa(<?php echo "'".($_SESSION['cod_local'])."'"; ?>);">
<?
$params = session_name()."=".session_id()."&krd=$krd";
?>

<form action="repexpedientes.php?<?=$params?>" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="3" class='titulos2'>
			CONSULTA EXPEDIENTES
        	  <input name="accion" type="hidden" id="accion">
        	<input type="hidden" name="params" value="<?=$params?>">
      </td>
    </tr>
    </tr>
    

       <tr align="center" colspan="2">
		<td width="31%" class='titulos2'>EXPEDIENTE</td>
	<td width="69%" height="30" class='listado2' align="left">
			<input name="exp" id="exp" type="input" size="50" class="tex_area" value="<?php echo $exp?>" />
		</td>
	</td>
     </tr>
        
    <tr align="center" colspan="2">
      <td width="31%" class='titulos2'>ETIQUETA</td>
   <td width="69%" height="30" class='listado2' align="left">
			<input name="etq" id="etq" type="input" size="50" class="tex_area" value="<?php echo $etq?>" />
		</td>			 
	</td>
</tr>	
	<tr align="center">
		<td height="30" colspan="4" class='listado2'>
		
		<center>
			<input name="Consultar" type="submit"  class="botones" id="envia22"   value="Consultar">&nbsp;&nbsp;

		</center>
		
		</td>
	</tr>
</table>
		
</form>
<?php 
function pintarResultados($fila,$i,$n){
		global $ruta_raiz;
		switch($n){
			case 0:
				$salida=$fila['SGD_EXP_NUMERO'];
				break;
			case 1:
				$salida=$fila['SGD_SEXP_FECH'];
				break;
			
			case 2:
				$salida=$fila['PARAMETRO'];
				break;		
			case 3:
				$salida=$fila['SESUB'];
				break;
			case 4:
				$salida=$fila['DEPE_NOMB'];				
				breaK; 
			Default:$salida="ERROr";
		}
		return $salida;	
	}
	
if(!empty($_POST['Consultar'])&& ($_POST['Consultar']=="Consultar")){

	       require_once($ruta_raiz."include/myPaginador.inc.php");
			
		 			
$where=null;
				
			$where=(!empty($_POST['exp']) && trim($_POST['exp'])!="")?(
							($where!="")? $where." AND s.SGD_EXP_NUMERO LIKE '%".strtoupper(trim($_POST['exp']))."%'":" WHERE s.SGD_EXP_NUMERO LIKE '%".strtoupper(trim($_POST['exp'])."%' ")) 			
							:$where;
						
			$where=(!empty($_POST['etq']) && trim($_POST['etq'])!="")?(
							($where!="")? $where." AND (s.sgd_sexp_parexp1||s.sgd_sexp_parexp2||s.sgd_sexp_parexp3||s.sgd_sexp_parexp4||s.sgd_sexp_parexp5) LIKE '%".strtoupper(trim($_POST['etq']))."%'":" WHERE (s.sgd_sexp_parexp1||s.sgd_sexp_parexp2||s.sgd_sexp_parexp3||s.sgd_sexp_parexp4||s.sgd_sexp_parexp5) LIKE '%".strtoupper(trim($_POST['etq'])."%'")) 			
							:$where;				
			
			$camposConcatenar = "(" . $db->conn->Concat("s.sgd_sexp_parexp1",
                                                    "s.sgd_sexp_parexp2",
                                                    "s.sgd_sexp_parexp3",
                                                    "s.sgd_sexp_parexp4",
                                                    "s.sgd_sexp_parexp5") . ")";
		  				
							
		
 			$order=1;      	
			$titulos=array("1#EXPEDIENTE","2#FECHA EXP","3#ETIQUETA","4#SERIE/SUBSERIE","5#DEPENDENCIA");
      	
			
    $isql= "select distinct(s.sgd_exp_numero),
                    s.depe_codi, d.depe_nomb, S.SGD_SEXP_FECH,
                    $camposConcatenar as PARAMETRO, 
                    (se.sgd_srd_descrip||' - '||su.sgd_sbrd_descrip) AS SESUB
            from sgd_sexp_secexpedientes s
            		INNER JOIN SGD_EXP_EXPEDIENTE E ON S.sgd_exp_numero = E.sgd_exp_numero,
            		dependencia d,
                    sgd_srd_seriesrd se,
                    sgd_sbrd_subserierd su
                    {$where} AND 
                    s.sgd_srd_codigo = se.sgd_srd_codigo and
                    s.sgd_sbrd_codigo = su.sgd_sbrd_codigo and
                    s.sgd_srd_codigo  = su.sgd_srd_codigo and
                    s.depe_codi not in (900,905,910,999) and
                    s.depe_codi = d.depe_codi";
 $db->conn->debug = true;
			
		

			$paginador= new myPaginador($db,$isql,1);
			$paginador->modoPintado(true);
			$paginador->setImagenASC($ruta_raiz."iconos/flechaasc.gif");
			$paginador->setImagenDESC($ruta_raiz."iconos/flechadesc.gif");
			$paginador->setFuncionFilas("pintarResultados");
			$paginador->generarPagina($titulos);
	}

	
?>

</div></body>
</html>