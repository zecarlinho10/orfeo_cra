<?php

echo "entro por donde No debo"; exit;
class ConnectionHandler {

//Almacena un error, resultado de una transacci�
/**
  * ESTA CLASE INICIA LA CONEXION A LA BD SELECCIONADA
	* @$conn  objeto  Variable que almacena la conexion;
	* @$driver char  Variable que almacena la bd Utilizada.
	* @$rutaRaiz char Indica la ruta para encontrar la ubicacion de la raiz de la aplicacion.
	* @$dirOrfeo char Directorio del servidor web en el cual se encuentra instalado Orfeo.
	*
	*/

var $Error;
var $id_query;

var $driver;
var $rutaRaiz;
var $conn;
var $entidad;
var $entidad_largo;
var $entidad_tel;
var $entidad_dir;
var $querySql;
/* Metodo constructor */
function __construct($ruta_raiz){
	if (!defined('ADODB_ASSOC_CASE')) define('ADODB_ASSOC_CASE',1);
	include (realpath(dirname(__FILE__)) ."/../../../include/class/adodb/adodb.inc.php");
        include_once (realpath(dirname(__FILE__)) ."/../../../include/class/adodb/adodb-paginacion.inc.php");
        include_once (realpath(dirname(__FILE__)) ."/../../../include/class/adodb/tohtml_orfeo.inc.php");
        include (realpath(dirname(__FILE__)) ."/../../../config.php");
        include_once (realpath(dirname(__FILE__)))."/../../../include/utils/Utils.php" ;
        $ADODB_COUNTRECS = false;
	$this->driver = $driver;
	$this->conn  = NewADOConnection("$driver");
	$this->rutaRaiz = $ruta_raiz;
	if ($this->conn->Connect($servidor,$usuario,$contrasena,$servicio) == false)
		die("Error de conexi&oacute;n a la B.D.");
	$this->entidad = $entidad;
	$this->entidad_largo = $entidad_largo;
	$this->entidad_tel = $entidad_tel;
	$this->entidad_dir = $entidad_dir;
}
function imagen()
{
	switch($this->entidad)
	{
		case "CRA":
			$imagen = "png/logoCRA.gif";
		break;
		case "DNP":
			$imagen = "png/logoDNP.gif";
		break;
		case "SSPD":
			$imagen = "png/escudoColombia.jpg";
		break;
		default:
			$imagen = "";
		break;
	}
	return($imagen);
}
//  Retorna False en caso de ocurrir error;
function query($sql)
{
$cursor = $this->conn->Execute($sql);
  return $cursor;
}
//  Retorna la fecha actual segun la BD del driver;
function sysdate()
{
	if($this->driver == "postgres")  return "now()";
  if($this->driver == "oci8")  return "sysdate";
  if($this->driver == "mssql")  return "GETDATE()";
}

/* Devuelve un array correspondiente a la fila de una consulta */
/*	function fetch_row() {

	//return ifx_fetch_row($this->id_query);

	ora_fetch_into($this->idconnection,$row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	$this->id_query=$row;
	return ($row);

	}
*/

/* Devuelve el nmero de campos de una consulta */
/*
	function numfields() {

	return ifx_num_fields($this->id_query);

	}

 */

/* Devuelve el nmero de registros de una consulta */
/*
	function numrows(){

	return ifx_affected_rows($this->id_query);

	}
*/

/* Funcion miembro que carga dos arrays con los nombres de los campos y el tipo de dato respectivamente. */
/*
	function fieldsinfo() {

	$types = ifx_fieldtypes($this->id_query);

	for ($i = 0; $i < count($types); $i++) {

	$this->fieldsnames[$i] = key($types);

	$this->$fieldstypes[$i] = $types[$this->fieldsnames[$i]];

	next($types);

	}

	}

*/
/* Funcion miembro que realiza una consulta a la base de datos y devuelve un record set */

function getResult($sql) {
	if ($sql == "") {
		$this->Error = "No ha especificado una consulta SQL";
		print($this->Error);
		return 0;
	}
	return ($this->query($sql));
}


/* Funcion miembro que ejecuta una instruccion sql a la base de datos. */





/*
   Funcion miembro que recibe como parametros: nombre de la tabla, un array con los nombres de los campos,
   y un array con los valores respectivamente.
*/

	function insert($table,$record) {
  	$temp = array();
    $fieldsnames = array();
  	foreach($record as $fieldName=>$field )
  	{
      $fieldsnames[] = $fieldName;
    	$temp[] = $field;
    }
  	$sql = "insert into " . $table . "(" . join(",",$fieldsnames) . ") values (" . join(",",$temp) . ")";
  	{
  	 echo "<hr>(".$this->driver.") $sql<hr>";
  	}
		$this->querySql = $sql;
		
		//$this->conn->BeginTrans();
    //
    
		$res = $this->conn->Execute($sql);
		//return " $res ";
                return " $sql ";
    //echo "\n **** $res ".$sql ."***** \n";
		if($res)
		{
			//$this->conn->RollbackTrans();
		}
		else
		{
			//$this->conn->CommitTrans();
		}
	return( $res );
  	//return ($this->conn->query($sql));


	}


/*
   Funcion miembro que recibe como parametros: nombre de la tabla,
   un array con los nombres de los campos
   ,un array con los valores, un array con los nombres de los campo id y
   un array con los valores de los campos id respectivamente.
*/



	function update($table, $record, $recordWhere) {

	$tmpSet = array();
	$tmpWhere = array();
	foreach($record as $fieldName=>$field )
	{
	  $tmpSet[] = $fieldName . "=" . $field;
	}

	foreach($recordWhere as $fieldName=>$field )
	{
	  $tmpWhere[] = " " . $fieldName . " = " . $field . " ";
	}
	$sql = "update " . $table ." set " . join(",",$tmpSet) . "    where " . join(" and ",$tmpWhere);

  	{
  	 echo "<hr>(".$this->driver.") $sql<hr>";
  	}
  	$res = $this->conn->Execute( $sql );
	
  	if( !$res )
	{
		//$this->conn->RollbackTrans();
	}
	else
	{	
		//$this->conn->CommitTrans();
	}
	return( $res );
	//return ($this->conn->Execute($sql));

}


/*
   Funcion miembro que recibe como parametros: nombre de la tabla, un array con los
   nombres de los campos id, y un array con los valores de los id.
*/


	function delete($table, $record) {

	$temp = array();

	foreach($record as $fieldName=>$field )
	{
	$tmpWhere[] = "  " . $fieldName . "=" . $field;
	}
	$sql = "delete from " . $table . " where " . join(" and ",$tmpWhere);

	//print("*** $sql ****");
  	{
  	 echo "<hr>(".$this->driver.") $sql<hr>";
  	}
	return ($this->query($sql));

	}

	function nextId($secName){
		if ($this->conn->hasGenID)
			return $this->conn->GenID($secName);
		else{
			$retorno=-1;

			if ($this->driver=="oracle"){
				$q= "select $secName.nextval as SEC from dual";
				$this->conn->SetFetchMode(ADODB_FETCH_ASSOC);
				$rs=$this->query($q);
				//$rs!=false &&
				if  ( !$rs->EOF){
					$retorno = $rs->fields['SEC'];
					//print ("Retorna en la funcion de secuencia($retorno)");
				}
			}
			return $retorno;
		}
	}

 /*
 function datoActualizado($mensaje) {
	echo  "<script>";
	echo  ("alert ('$mensaje');");
	echo  "</script>";

}

*/
/*
   Funcion miembro que libera los recursos de la consulta realizada.
*/

/*
	function free(){

	ifx_free_result($this->id_query);

	}

*/

/*
   Funcion miembro que cierra la conexion abierta a la base de datos.
*/

/*
	function close(){

	ifx_close($this->idconnection);

	}*/
}

?>
