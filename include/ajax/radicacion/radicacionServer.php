<?php
session_start();
 // include the server class
include("../../../config.php");
include realpath(dirname(__FILE__)).'/../../../pear/HTML/AJAX/Server.php';
/**
 * Advanced usage of HTML_AJAX_Server
 * Allows for a single server to manage exporting a large number of classes without high overhead per call
 * Also gives a single place to handle setup tasks especially useful if session setup is required
 *
 * The server responds to ajax calls and also serves the js client libraries, so they can be used directly from the PEAR data dir
 * 304 not modified headers are used when server client libraries so they will be cached on the browser reducing overhead
 *
 * @category   HTML
 * @package    AJAX
 * @author     Joshua Eichorn <josh@bluga.net>
 * @copyright  2005 Joshua Eichorn
 * @license    http://www.opensource.org/licenses/lgpl-license.php  LGPL
 * @version    Release: 0.5.6
 * @link       http://pear.php.net/package/HTML_AJAX
 */
// extend HTML_AJAX_Server creating our own custom one with init{ClassName} methods for each class it supports calls on

/**
 * Clase que permite crear un servidor de radicacion tipo Ajax
 * 
 */
class radicacionServer extends HTML_AJAX_Server {
	// this flag must be set to on init methods
	var $initMethods = false;
	//Conexion a Base de datos de Orfeo
	var $db;
  var $ruta_raiz;
	// init method for the testHaa class, includes needed files an registers it for ajax, directly passes in methods to register to specify case in php4
	function initRadicacion() {
		$ruta_raiz = "../../..";
		 session_start();
    //echo "alert('Entro a initRadicacion".$this->db."');";
		include './radicacion.class.php';
		$this->registerClass(new radicacionAjax($this->db,$this->ruta_raiz),'radicacionAjax',array('newRadicadoAjax','insertDireccionAjax','insertVarsDireccionAjax'));
  
	}
	function conexion($db,$ruta_raiz)
	{
	  $this->db = $db;
    $this->ruta_raiz = $ruta_raiz;
	}	
}

// create an instance of our test server

  include_once ("../../../include/db/ConnectionHandler.php");
  $ruta_raiz = "../../..";
  $db = new ConnectionHandler($ruta_raiz);
  $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
  //error_reporting(E_ALL);
  //$db->conn->debug=true; 
$server = new radicacionServer();
$server->conexion($db,$ruta_raiz);
$server->initRadicacion();
$server->handleRequest();
?>
