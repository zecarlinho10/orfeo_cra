<?php

session_start();
if (empty($_SESSION["krd"])) {
    if (empty($_REQUEST["krd"])) {
        header('Location: ../../login.php');
    }
    include "../../rec_session.php";
}
$ruta_raiz = "../../";
$fecha = date("Ymdhis");
$_SESSION["idFormulario"] = sha1(microtime(true) . mt_rand(10000, 90000));

require_once('../clases/crud_cobro.php');
require_once('../clases/cobro.php');

include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

//post

$ptxtExpediente = $_POST['txtExpediente'];
$ptxtDeudor = $_POST['txtid_emp'];
$ptxtFuncionario = $_POST['txtFuncionario'];



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>.:.Ingreso Cobros.:.</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!--Deshabilitar modo de compatiblidad de Internet Explorer-->

<link rel="stylesheet" href="../../estilos/bootstrap.min.css"
	type="text/css" />
<link rel="stylesheet"
	href="../../estilos/CRA.bootstrap.min.css?jashd=<?php echo date("ymdhmi")?>"
	type="text/css" />

<!-- CSS -->
<link href="../../estilos/jquery-ui.css" rel="stylesheet">
	<script src="../../js/jquery.min.js"></script>
	<script src="../../js/bootstrap/bootstrap.min.js"></script>
	<script src="../../js/chosen.jquery.js"></script>
	<script src="../../js/jquery-ui.min.js"></script>
	<!-- Just to make our placeholder images work. Don't actually copy the next line! -->
	<script src="../../js/holder.min.js"></script>
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="../../js/ie10-viewport-bug-workaround.js"></script>
	<!-- Bootstrap core CSS -->
	<link href="../../estilos/bootstrap.min.css" rel="stylesheet" />
	<link
		href="../../estilos/bootstrap-chosen.css?sdf=<?php echo date("ymdhis")?>"
		rel="stylesheet" />
	<link href="../../estilos/bootstrap-dialog.css" rel="stylesheet" />


	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->


	<link href="../../estilos/ie10-viewport-bug-workaround.css"
		rel="stylesheet" />

	<!-- Custom styles for this template -->
	<link href="../../estilos/dashboard.css" rel="stylesheet" />

	<script src="../../js/ie-emulation-modes-warning.js"></script>

	<!-- jQuery -->
	<script type="text/javascript"
		src="../../js/jquery.validate.min.js?sdf=<?php echo date("ymdhis")?>">
	</script>
	<!--funciones-->
	<script type="text/javascript">
		 var basePath ='<?php echo $ruta_raiz?>';
         var urlEntidad ='<?php echo $httpWebOficial ?>';
 	</script>
	<script type="text/javascript"
		src="../../js/bootstrap/bootstrap-dialog.js">
	</script>
	<script type="text/javascript"
		src="js/cobro.js?tes=<?php echo date("Ymdhis")?>">
	</script>
	<script> 


	function confirmation() {
	    if(confirm("Confirma el ingreso del nuevo Cobro?"))
	    {
	        return true;
	    }
	    return false;
	}

	function notificacion(x){
	        //una notificación normal
	    if(x==1){
	    	alert("Cobro ingresado exitosamente."); 	
	    }
	    else{
	    	alert("Error al ingresar Cobro."); 	
	    }
	      
	      return false;
	}

	</script> 

</head>
<body id="public">
	<?php

		include ('../clases/terceros.php');
		$objTerceros = new Terceros($db);

		if($ptxtDeudor){
			//Cobros

			$objCobro = new Cobro($db);
			$objCobro->setExpediente($ptxtExpediente);
			$objCobro->setDeudor($ptxtDeudor);
			$objCobro->setFuncionario($ptxtFuncionario);

			$objCrudCobro = new CrudCobro($db);
			
			if($objCrudCobro->insertar($objCobro)==true){
				?>
					<script> 
						notificacion(1);
					</script>
				<?
			}
			else{
				?>
					<script> 
						notificacion(0);
					</script>
				<?
			}
		}

	?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3>Formulario de ingreso de Cobro Coactivo</h3>
		</div>
		<div class="panel-body">
			<div class="container-fluid">
				<form id="frmcobro" class="" autocomplete="off"
					enctype="multipart/form-data" method="post" action="ingresar.php" name="frmcobro" >
					<br />
					<div class="container-fluid col-sm-offset-3">
						<div class="pub">
							<div>
								<div class="noanonimo seltipo">
									<div class="row col-sm-12 col-md-12 dinamic">
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="txtExpediente">Expediente
												</label>
											</div>
											<div class="col-sm-12  col-md-3 ">
												<input class="form-control" value=""
													maxlength="130" tabindex="4"
													minlenght="3" name="txtExpediente" id="txtExpediente"
													type="text" onblur='expedienteFunction()'/>

											</div>
											<div id="resultadoExp"></div>
										</div>
										<div class="row form-group">
											<div class="col-sm-12  col-md-3">
												<label
													class="desc control-label etiqueta  control-label label-success  form-control"
													for="txtnoEmpresa">Prestador
												</label>
											</div>
											<div class="col-sm-12  col-md-9 ">
												<input type="hidden" id="txtid_emp" value="" name="txtid_emp" class="form-control obligatorio"/>
												<input class="form-control obligatorio" value=""
													maxlength="150" title="El campo Prestador es obligatorio "
													minlenght="3" name="txtnoEmpresa" tabindex="4"
													id="txtnoEmpresa" type="text" required/>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-3  col-md-3">
												<label
													class="desc desc     control-label etiqueta  control-label label-success  form-control"
													for="txtFuncionario">Funcionario encargado*
												</label>
											</div>
											<div class="col-sm-3  col-md-6 ">
													<select data-placeholder="Seleccione una opción"
														title="Debe Seleccionar Funcionario, este campo  es Obligatorio"
														class="chosen-select form-control dropdown seleccion"
														id="txtFuncionario" name="txtFuncionario" required>

														<option value="">-- SELECCIONE --</option>
														<?php foreach($objTerceros->getFuncionariosPersimo(534) as $d):?>
														      <option value="<?php echo $d->getId(); ?>"><?php echo $d->getNombre(); ?>
														      	
														      </option>
														<?php endforeach ?>
													</select>
											</div>											
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-8 text-center">
								<input id="saveForm" type="submit" value="Continuar"
									class="btn btn-primary" onclick="if(valida_form()==true) {return confirmation();}" />
							</div>
						</div>
				
				</form>

			</div>
		</div>
	</div>


 <script type="text/javascript">
  var basePath ='<?php echo $ruta_raiz?>';
  var urlOf ="<?php echo   $httpWebOficial;?>"; 
  function display_mensaje(){
	console.log(!$("#Cobro").valid());
	if($("#Cobro").valid()){
	  BootstrapDialog.alert({
			title : "Registro!!",
			message : "Informacion Registrada"
		});

	 }else{
		 valida_form();
	 }
  }
  </script>

	<!--container-->
	<div id="errores"></div>
	<div class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">×</button>
					<h4 class="modal-title glyphicon glyphicon-off">Modal title</h4>
				</div>
				<div class="modal-body">
					<p>One fine body…</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
</body>
</html>

