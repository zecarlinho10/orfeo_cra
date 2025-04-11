
<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
$ruta_raiz = "../..";
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
$db = new ConnectionHandler( "$ruta_raiz" );

require_once('./clases/crud_comite_funcionario.php');

$objCrudComiteFuncionario = new CrudComiteFuncionario($db);

$funcionario_comite=$_POST['funcionario_comite'];
$valor=$_POST['valor'];

$variables=explode("_", $funcionario_comite);
$funcionario=$variables[0];
$comite=$variables[1];

if($valor==1){
	if($objCrudComiteFuncionario->insertar($comite,$funcionario)==true){
		echo "Funcionario asociado a comité exitosamente";
	}
	else{
		echo "Error al asociar funcionario a comité";
	}
}
else{
	if($objCrudComiteFuncionario->eliminar($comite,$funcionario)==true){
		echo "Funcionario desasociado exitosamente";
	}
	else{
		echo "Error al desasociar funcionario";
	}
}


?>


