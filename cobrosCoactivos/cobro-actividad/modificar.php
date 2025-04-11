<?php
$ruta_raiz = "../..";
//include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
	# conectare la base de datos
$db = new ConnectionHandler( "$ruta_raiz" );

require_once('../clases/crud_actividad.php');
require_once('../clases/validaciones.php');


$objCrudActividades = new CrudActividades($db);
$objValidaciones = new Validaciones($db);
	//FALTAN
	//idEncargado
	//inicio
	//
	/*Inicia validacion del lado del servidor*/
	$errors = array();
	if (empty($_POST['id'])) {
        $errors[] = "ID vacío";
	} else if (empty($_POST['resolucion'])){
		$errors[] = "resolucion vacío";
	} else if (sizeof($errors)==0){

		if ($objCrudActividades->actualizaActividad($_POST['id'],$_POST['resolucion'],$_POST['valor'],$_POST['interes'],$_POST['capital'],$_POST['abointer'],$_POST['fecha'],$_POST['vigencia'])){
			$messages[] = "Los datos han sido actualizados satisfactoriamente.";
		} else{
			$errors []= "Lo siento algo ha salido mal intenta nuevamente.";
		}
			
	}
	else {
		$errors []= "Error desconocido.";
	}
		
	if (!empty($errors)){
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