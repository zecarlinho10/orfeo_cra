<?php
include_once "../../config.php"
	//$actividad=$_POST['actividad'];
	$tamano_archivo = $_FILES['file']['size']; 
	$tipo_archivo = $_FILES['file']['type']; 

    if ( 0 < $_FILES['file']['error'] ) {
        //echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    	echo "Error al cargar archivo.";
    }
    else {
    	if (!strpos($tipo_archivo, "pdf")){
    		echo "Debe ser pdf";
    	}
    	else if($tamano_archivo > 10000000){
    		echo "Debe pesar menos de 10Mb";
    		//echo $tamano_archivo;
    	}
    	else{
	        move_uploaded_file($_FILES['file']['tmp_name'], BODEGA.'/actuaciones/' . $_FILES['file']['name']);
	        echo "Archivo cargado exitosamente.";
    	}
    	
    }

?>
