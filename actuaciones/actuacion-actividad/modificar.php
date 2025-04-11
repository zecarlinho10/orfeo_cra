<?php
$ruta_raiz = "../..";
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
	# conectare la base de datos
$db = new ConnectionHandler( "$ruta_raiz" );

require_once('../clases/crud_actividad.php');
require_once('../clases/validaciones.php');

$errors=array();

$objCrudActividades = new CrudActividades($db);
$objValidaciones = new Validaciones($db);
	//FALTAN
	//idEncargado
	//inicio
	//
	$idActividad=$_POST['id_actividad'];
	/*Inicia validacion del lado del servidor*/
	if (empty($_POST['id'])) {
        $errors[] = "ID vacío";
    } else if ($idActividad==0){
		$errors[] = "Actividad vacío";
	} else if (empty($_POST['observacion'])){
		$errors[] = "Observacion vacío";
	} else if (sizeof($errors)==0){
 
 		if($_POST['radicado'] && $objValidaciones->validarRadicado($_POST['radicado'])==0){
				echo "<script type='text/javascript'>alert('El radicado no existe');</script>";
		}
		else{
			// escaping, additionally removing everything that could be (html/javascript-) code
			
			if ($objCrudActividades->actualizaActividad($_POST['id'],$_POST['id_actividad'],$_POST['radicado'],$_POST['idEncargado'],$_POST['inicio'],$_POST['fin'],$_POST['observacion'])){
				$messages[] = "Los datos han sido actualizados satisfactoriamente.";
			} else{
				$errors []= "Lo siento algo ha salido mal intenta nuevamente.";
			}
			
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
