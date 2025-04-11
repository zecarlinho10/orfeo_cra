<?php  

$ruta_raiz = "../..";
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );
require_once($ruta_raiz . "/actuaciones/clases/" . "actuacion.php");
require_once($ruta_raiz . "/actuaciones/clases/" . "crud_actuacion.php");
$db = new ConnectionHandler( "$ruta_raiz" );

$objCrudActuacion = new CrudActuacion($db);

$id=$_GET['id']; 

$objActuacion = new Actuacion();
$objActuacion = $objCrudActuacion->getActuacion($id);

$data=array(); 

$data['id']=$id; 
$data['nombre']=$objActuacion->getNombre(); 
$data['objetivo']=$objActuacion->getObjetivo(); 
$data['finicio']=$objActuacion->getFechaInicio(); 
$data['ffin']=$objActuacion->getFechaFin(); 
$data['estado']=$objActuacion->getIdEstado(); 
$data['tipoTramite']=$objActuacion->getTipoTramite(); 
$data['expediente']=$objActuacion->getExpediente(); 
$data['observacion']=$objActuacion->getObservacion(); 

/*
$query=$db->query("SELECT usua_login, usua_nomb FROM USUARIO WHERE USUA_ESTA=1 AND DEPE_CODI=$_GET[dependencia_id] ORDER BY usua_nomb");
$usuarios = array();
//while($r=$query->fetch_object()){ $usuarios[]=$r; }
while(!empty($query) && !$query->EOF){ 
        $usuarios[$i]=$query->fields; 
        $i++;
        $query->MoveNext ();
    }
//
if(count($usuarios)>0){
print "<option value=''>-- SELECCIONE --</option>";
foreach ($usuarios as $s) {
    print "<option value='$s[0]'>$s[1]</option>";
}
}else{
print "<option value=''>-- NO HAY DATOS --</option>";
}


require_once('conexion.php'); 
include_once 'metodos.class.php'; 
$metodo= new metodos(); 
$data=array(); 
    $data['nombreM']=implode(" ",$metodo->buscar("mascota","idM='$id'","nombreM")); 
    $data['sexo']=implode(" ",$metodo->buscar("mascota","idM='$id'","sexo")); 
    $data['raza']=implode(" ",$metodo->buscar("mascota","idM='$id'","raza")); 
    $data['color']=implode(" ",$metodo->buscar("mascota","idM='$id'","color")); 
    $fn=implode(" ",$metodo->buscar("mascota","idM='$id'","fnac")); 
    $data['tipo']=implode(" ",$metodo->buscar("mascota","idM='$id'","tipo")); 
     
    $f= explode('-',$fn); 
$fech=$f[2].'-'.$f[1].'-'.$f[0]; 
$data['fnac']=$fech; 
*/
echo json_encode($data); //esto es enviado al success de la funcion ajax 
?>