<?php

/*********************************************************************************
 *       Filename: common.php
 *       Generated with CodeCharge 2.0.5
 *       PHP 4.0 build 11/30/2001
 *********************************************************************************/

//error_reporting (E_ALL ^ E_NOTICE);
//===============================
// Database Connection Definition - Defincion de la coneccion a la base de tdatos
//-------------------------------
//Archivo - Manejo de prestamos y devoluciones 
//Connection begin - iniciar coneccion

if (!$ruta_raiz) $ruta_raiz = "..";

require_once("$ruta_raiz/include/db/ConnectionHandler.php");

//if (!$db)
	

// Archivo - Manejo de prestamos y devoluciones 
// Connection end - finalizar coneccion
//===============================
// Site Initialization - Inicializacion del sitio 
//-------------------------------
// Obtain the path where this site is located on the server - obtener la ruta donde este sitio esta localizado en el servidor
//-------------------------------
$app_path = ".";
//-------------------------------
// Create Header and Footer Path variables - Crear variable de ruta para la Cabecera y el pie de pagina
//-------------------------------
$header_filename = "encabezado.html";
//===============================

//===============================
// Common functions - Funciones Comunes
//-------------------------------
// Convert non-standard characters to HTML - Convertir caracteres no estandares a HTML
//-------------------------------
function tohtml($strValue)
{
  return htmlspecialchars($strValue);
}

//-------------------------------
// Convert value to URL - Convertir valor a URL
//-------------------------------
function tourl($strValue)
{
  return urlencode($strValue);
}

//-------------------------------
// Obtain specific URL Parameter from URL string - Obtener un parametro especifico URL desde una cadena URL 
//-------------------------------
function get_param($param_name)
{
  global $HTTP_POST_VARS;
  global $HTTP_GET_VARS;

  $param_value = "";
  if(isset($HTTP_POST_VARS[$param_name]))
    $param_value = $HTTP_POST_VARS[$param_name];
  else if(isset($HTTP_GET_VARS[$param_name]))
    $param_value = $HTTP_GET_VARS[$param_name];

  return $param_value;
}

function get_session($param_name)
{
  global $HTTP_POST_VARS;
  global $HTTP_GET_VARS;
  global ${$param_name};

  $param_value = "";
  if(!isset($_POST[$param_name]) && !isset($_GET[$param_name]) && isset($_SESSION[$param_name])) 
    $param_value = ${$param_name};

  return $param_value;
}

function set_session($param_name, $param_value)
{
  global ${$param_name};
  if(session_is_registered($param_name)) 
    session_unregister($param_name);
  ${$param_name} = $param_value;
  session_register($param_name);
}

function is_number($string_value)
{
  if(is_numeric($string_value) || !strlen($string_value))
    return true;
  else 
    return false;
}

//-------------------------------
// Convert value for use with SQL statement - Convertir valores para uso con una afirmacion SQL
//-------------------------------
function tosql($value, $type)
{
  if(!strlen($value))
    return "NULL";
  else
    if($type == "Number")
      return str_replace (",", ".", doubleval($value));
    else
    {
      /*if(get_magic_quotes_gpc() == 0)
      /{
        $value = str_replace("'","''",$value);
        $value = str_replace("\\","\\\\",$value);
      }
      else
      {*/
        $value = str_replace("\\'","''",$value);
        $value = str_replace("\\\"","\"",$value);
      //}

      return "'" . $value . "'";
    }
}

function strip($value)
{
  //if(get_magic_quotes_gpc() == 0)
   // return $value;
  //else
    return stripslashes($value);
}

function db_fill_array($sql_query)
{
  global  $ruta_raiz;
  $db = new ConnectionHandler($ruta_raiz);
  var_dump($sql_query);
  $db->conn->setFetchMode(ADODB_FETCH_NUM); 
  $rs=$db->query($sql_query);
  $db->conn->setFetchMode(ADODB_FETCH_ASSOC);
  if ($rs && !$rs->EOF  )
  {
    do
    {
      $ar_lookup[$rs->fields[0]] = $rs->fields[1];
      $rs->MoveNext();
    } while ($rs && !$rs->EOF);
    return $ar_lookup;
  }
  else
    return false;

}

//-------------------------------
// Deprecated function - use get_db_value($sql)
//-------------------------------
function dlookup($table_name, $field_name, $where_condition)
{
  $sql = "SELECT " . $field_name . " FROM " . $table_name . " WHERE " . $where_condition;
  return get_db_value($sql);
}


//-------------------------------
// Lookup field in the database based on SQL query - Mirar campos en la base de datos usando una consulta SQL
//-------------------------------
function get_db_value($sql)
{
  global $db;
  $rs=$db->query($sql);
  
  if($rs && !$rs->EOF)
    return $rs->fields[0];
  else 
    return "";
}

//-------------------------------
// Obtain Checkbox value depending on field type - Obteniendo un checkbox dependiendo el tipo de campo.
//-------------------------------
function get_checkbox_value($value, $checked_value, $unchecked_value, $type)
{
  if(!strlen($value))
    return tosql($unchecked_value, $type);
  else
    return tosql($checked_value, $type);
}

//-------------------------------
// Obtain lookup value from array containing List Of Values - Obteniendo un valor de una arreglo que contiene una lista de valores
//-------------------------------
function get_lov_value($value, $array)
{
  $return_result = "";

  if(sizeof($array) % 2 != 0) 
    $array_length = sizeof($array) - 1;
  else
    $array_length = sizeof($array);

  for($i = 0; $i < $array_length; $i = $i + 2)
  {
    if($value == $array[$i]) $return_result = $array[$i+1];
  }

  return $return_result;
}

//-------------------------------
// Verify user's security level and redirect to login page if needed - Verificar nivel de seguridad del usuario y redirigir al login si es necesario
//-------------------------------

function check_security()
{
  if(!session_is_registered("UserID")){
  	$querystring = urlencode(getenv("QUERY_STRING"));
  	$ret_page = urlencode(getenv("REQUEST_URI"));
    include_once ("login.php");
    die;
  }
}

//===============================
//  GlobalFuncs begin -  inicio de funciones globales 
//  GlobalFuncs end - terminacion de funciones globales
//===============================
?>
