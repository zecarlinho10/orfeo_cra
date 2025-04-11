<?php
$ruta_raiz = "../..";

include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
	# conectare la base de datos
$db = new ConnectionHandler( "$ruta_raiz" );

require_once('../clases/crud_proceso.php');
//require_once('../clases/validaciones.php');


$errors = array();	
$objCrudProceso = new CrudProceso($db);
//$objValidaciones = new Validaciones($db);
	//FALTAN
	//idEncargado
	//inicio
	//
	/*Inicia validacion del lado del servidor*/
	if (empty($_POST['id'])) {
	$errors[] = "ID vacío";
	} else if (empty($_POST['fecha'])){
		$errors[] = "fecha vacío";
	} else if (sizeof($errors) == 0){

		if ($objCrudProceso->actualizaProceso($_POST['id'],$_POST['fecha'],$_POST['descripcion'],$_POST['radicado'],$_POST['notificacion'],$_POST['acto'],$_POST['observacion'])){
			$messages[] = "Los datos han sido actualizados satisfactoriamente.";
		} else{
			$errors []= "Lo siento algo ha salido mal intenta nuevamente.";
		}
			
	}
	else {
		$errors []= "Error desconocido.";
	}
		
	if (isset($errors)){
			?>
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> 
					<?php
						foreach ($errors as $error) {
								echo $error;
						}
					?>
			</div>
			<?php
	}
	
	if (isset($messages)){
				
				?>
				<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>¡Bien hecho!</strong>
						<?php
							foreach ($messages as $message) {
									echo $message;
								}
							?>
				</div>
				<?php
	}
 
?>
