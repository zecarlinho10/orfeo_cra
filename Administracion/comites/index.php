<?php
/**
* @author Carlos Ricaurte   <carlinhoricaurte@hotmail.com>
* @license  GNU AFFERO GENERAL PUBLIC LICENSE
* Comisión de regulación de agua potable y saneamiento basico
* @copyright


This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

session_start();

if (! $_SESSION['dependencia'] || $_GET['close']) {
    header("Location: $ruta_raiz/login.php");
    echo "<script>parent.frames.location.reload();top.location.reload();</script>";
}

$ruta_raiz = "../..";
if (!$_SESSION['dependencia']) header ("Location: $ruta_raiz/cerrar_session.php");
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

if (!defined('ADODB_ASSOC_CASE')) define('ADODB_ASSOC_CASE', 1);
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

$rs_dep=$db->query("SELECT SGD_TPR_CODIGO, SGD_TPR_DESCRIP
					FROM FLDOC.SGD_TPR_TPDCUMENTO
					WHERE SGD_TPR_ESTADO=1 AND ((SGD_TPR_DESCRIP LIKE '%ACTA%' AND (SGD_TPR_DESCRIP LIKE '%com%' OR SGD_TPR_DESCRIP LIKE '%Com%')) OR 
                								(SGD_TPR_DESCRIP LIKE '%Acta%' AND (SGD_TPR_DESCRIP LIKE '%com%' OR SGD_TPR_DESCRIP LIKE '%Com%')) OR 
                								(SGD_TPR_DESCRIP LIKE '%acta%' AND (SGD_TPR_DESCRIP LIKE '%com%' OR SGD_TPR_DESCRIP LIKE '%Com%'))
                							   )
					ORDER BY SGD_TPR_DESCRIP");
$raidActas = array();

$i=0;
while(!empty($rs_dep) && !$rs_dep->EOF){ 
	 	$raidActas[$i]=$rs_dep->fields; 
		$i++;
		$rs_dep->MoveNext ();
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>.:.Comités.:.</title>
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="jquery.min.js"></script>
<script> 
	function checkear(funcionario_comite){

	        //asocia un funcionario a un comité (acta)
	        let valor = 2;
		    let func=funcionario_comite;
	    	if( $("#chk_funcionario"+func).prop('checked') ) {
                valor = 1;
            }
            else{
            	valor = 0;
            }
		    var parametros = {
                "funcionario_comite" : func,
                "valor" : valor
	        };
	        $.ajax({
	                data:  parametros, //datos que se envian a traves de ajax
	                url:   './asociaFuncionarioComite.php', //archivo que recibe la peticion
	                type:  'post', //método de envio
	                beforeSend: function () {
	                        $("#resultado").html("Procesando, espere por favor...");
	                },
	                success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
	                        $("#resultado").html(response);
	                }
	        });
		}

</script> 

</head>
<body>

<?php


/***************************************************actas*******************************************************/
 $query = "SELECT " . $db->conn->Concat("d.DEPE_CODI", "'-'", "d.DEPE_NOMB") . ", d.DEPE_CODI
              FROM DEPENDENCIA d
              WHERE d.depe_estado = 1
              ORDER BY d.DEPE_CODI, d.DEPE_NOMB";
$rs = $db->conn->query($query);
$depselectInf = $rs->GetMenu2("coddepe", 10, "", false, false, "class='form-control custom-scroll' multiple='multiple' id='lista_dependencia' style='height: 15%;' ");
/***********************************************fin actas*******************************************************/
?>


<div class="container">

<div class="row">

<div class="col-md-12">
<h1>Asociar Funcionarios a Comité CRA</h1>
</div>

</div>
<div class="row">
<div class="col-md-10">
<form method="post" action="index.php">
  <div class="form-group">
    <label for="name1">Tipo de Acta</label>
    <select id="id_acta" class="form-control" name="id_acta" required>
      <option value="">-- SELECCIONE --</option>
		<?php foreach($raidActas as $d):?>
		      <option value="<?php echo $d[0]; ?>"><?php echo $d[1] ?></option>
		<?php endforeach; ?>
    </select>
  </div>

  <section class="smart-form col-12">
					<legend>Dependencias:</legend>

					<section>
						<label class="label"> Dependencia </label> <label class="textarea">
                      <?=$depselectInf?>
                  </label>
					</section>
				<?php if($_TIPO_INFORMADO==1){ ?>
                <section>
						<label class="label"> Usuario </label> <label class="textarea"> <label
							class="textarea"> <select name="coddepe" multiple="multiple"
								class="form-control custom-scroll" id="informarUsuario">
									<option value="0">-- Seleccione un Usuario --</option>
							</select>
						</label>
						</label>
					</section>
				<?php } ?>
                <section class="smart-form">

						<label class="label"> Usuarios Seleccionados para notificar </label>

						<div class="inline-group" id="showusers"></div>

						<div class="alert alert-block alert-success hide">
							<a class="close" data-dismiss="alert" href="#">×</a>
							<div class="inline-group" id="showresult"></div>
						</div>

					</section>

				</section>

</form>
<div id="resultado"></div>
</div>
</div>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		$("#dependencia_id").change(function(){
			$.get("get_funcionarios.php","dependencia_id="+$("#dependencia_id").val(), function(data){
				$("#funcionario_id").html(data);
				console.log(data);
			});
		});

		$("body").on("click", '#lista_dependencia', function(){
	        var values = $(this).val();
	        var id_acta=$("#id_acta").val();
	        if($("#id_acta").val()==0){
	        	alert ("Seleccione un acta de comité.");
	        }
	        else{
	        	$.post( "../../radicacion/ajax_buscarUsuario.php", 
	            	{	SinChecked : values,
	            		id_acta : id_acta
	            	}
	            ).done(
	                function( data ) {
	      		  		$('#showusers').html(data[0]);
	                }
	            );
	        }
            
      	});

	});
</script>
</body>
</html>
