<?php
/**
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright

SIIM2 Models are the data definition of SIIM2 Information System
Copyright (C) 2013 Infometrika Ltda.

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
session_start();
$ruta_raiz = "..";

if (!$_SESSION['dependencia'])
    header ("Location: $ruta_raiz/cerrar_session.php");

header('Content-Type: application/json');
include_once("$ruta_raiz/include/db/ConnectionHandler.php");
include_once("$ruta_raiz/include/tx/usuario.php");
$db     = new ConnectionHandler("$ruta_raiz");
//$db->conn->debug = true;

if($_POST['search']){
    $usuario= new Usuario($db);
    $trans  = json_decode($_POST['search'], true);

    $search['tdoc'] = $trans['tdoc'];
    $search['docu'] = $trans['docu'];
    $search['name'] = $trans['name'];
    $search['tele'] = $trans['tele'];
    $search['mail'] = $trans['mail'];

    //Filtro por el tipo de usuario
    $result = $usuario->buscarPorParametros( $search );

    if($result){
        echo json_encode($usuario->result);
    }
    die;
}

if($_POST['addUser']){
    $data    = $_POST['addUser'];
    $usuario = new Usuario($db);
    $result  = $usuario->agregarUsuario($data);
    if($result){
        echo json_encode(array($result));
    }
    die;
}

if($_POST['searchUserInDep']){
     $data    = $_POST['searchUserInDep'];
      $usuario = new Usuario($db);
  
  //OBTENEMOS EL JEFE DE LA DEPENDENCIA
  $query = "select usua_codi,usua_nomb from usuario where usua_codi = 1 and depe_codi = $data";
  $rsjefe    = $db->conn->query($query);
  if(!$rsjefe->EOF){
  $codi_jefe             = $rsjefe->fields["USUA_CODI"];
   $nomb_jefe             = $rsjefe->fields["USUA_NOMB"];
  }
  $option .= "<option value='".$codi_jefe."'>JEFE DE DEPENDENCIA ( ".$nomb_jefe.") </option>";
      $result  = $usuario->usuariospordependencias($data);
  
  if($_SESSION["entidad"]!='ANM'){
      if($result){
        foreach ($usuario->result as $valor){
              $nomb = $valor[0];
             $codi = $valor[1];
              $option .= "<option value='".$codi."'>".$nomb."</option>";
          }
          echo  json_encode(array($option));
      };
  }else{
  echo  json_encode(array($option));
  }
      die;
  
  }

if($_POST['MsearchUserInDep']){
$data   = $_POST['MsearchUserInDep'];
$usuario = new Usuario($db);
for ($i=0;$i<count($data);$i++)    
{     
//OBTENEMOS LOS USUARIOS QUE TIENEN PERMISO DE REASIGNAR
  /*
$query = "select distinct AUTU_ID from AUTM_MEMBRESIAS where AUTG_ID = 126";
$rsmem    = $db->conn->Execute($query);
while(!$rsmem->EOF)
{

$id_usuario = $rsmem->fields["AUTU_ID"];

$esql = "select usua_codi,usua_nomb from usuario where ID = $id_usuario  and USUA_ESTA = 1 and depe_codi = $data[$i]";
$rsuse = $db->conn->Execute($esql);
while(!$rsuse->EOF){

$codi_jefe             = $rsuse->fields["USUA_CODI"];
$nomb_jefe             = $rsuse->fields["USUA_NOMB"];

$value_check = $data[$i]."_".$codi_jefe;

$option .= "<label class='radio userinfo'><input type='checkbox'  checked name='radio[]' value='".$value_check."'><i></i>".$nomb_jefe."</label>";
//$option .= "<option value='".$codi_jefe."'> ".$nomb_jefe." </option>";

$rsuse->MoveNext();
}

$rsmem->MoveNext();
}
    */

  $esql = "select usua_codi,usua_nomb from usuario where USUA_ESTA = 1 and depe_codi = $data[$i]";
  $rsuse = $db->conn->Execute($esql);
  while(!$rsuse->EOF){

    $codi_jefe             = $rsuse->fields["USUA_CODI"];
    $nomb_jefe             = $rsuse->fields["USUA_NOMB"];

    $value_check = $data[$i]."_".$codi_jefe;

    $option .= "<label class='radio userinfo'><input type='checkbox'  checked name='radio[]' value='".$value_check."'><i></i>".$nomb_jefe."</label>";
    //$option .= "<option value='".$codi_jefe."'> ".$nomb_jefe." </option>";

    $rsuse->MoveNext();
  }
} 
echo  json_encode(array($option));
die;
}


  if($_POST['SinChecked']){
    $id_acta=$_POST['id_acta'];
    $data   = $_POST['SinChecked'];
    $usuario = new Usuario($db);
    
    for ($i=0;$i<count($data);$i++){  
      $esql = "select ID, usua_codi,usua_nomb from usuario where USUA_ESTA = 1 and depe_codi = $data[$i] ORDER BY usua_nomb";
      $rsuse = $db->conn->Execute($esql);
      while(!$rsuse->EOF){

        $codi             = $rsuse->fields["USUA_CODI"];
        $nomb             = $rsuse->fields["USUA_NOMB"];
        $id               = $rsuse->fields["ID"];
       // $value_check = $data[$i]."-".$codi_jefe;
        $value_check = $id."_".$id_acta;

        $sql = "SELECT ID_COMITE_FUNCIONARIO, ID_COMITE, ID_FUNCIONARIO
                FROM ACTUACIONES.COM_COMITE_FUNCIONARIO
                WHERE ID_COMITE=$id_acta AND ID_FUNCIONARIO=$id";
        $res = $db->conn->Execute($sql);
        $van=0;
        while(!$res->EOF){
          $van=1;
          break;
        }
        $checkeado="";
        if($van==1){
          $checkeado="checked";
        }
         $option .= "<label class='checkbox'><input type='checkbox' name='chk_funcionario".$value_check."' id='chk_funcionario".$value_check."' value='".$value_check."' ".$checkeado." onclick='checkear(\"".$value_check."\")'><i></i>".$nomb."</label>"; 
        $rsuse->MoveNext();
      }
    }
    echo  json_encode(array($option));
    die;
  }
