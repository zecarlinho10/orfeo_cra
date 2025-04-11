<?php
 	
include_once(realpath(dirname(__FILE__) . "/../../../")."/include/db/ConnectionHandler.php");

final class Connection{
	private static $db;
	private static $flag;
	private function __construct(){
		global $ruta_raiz;
		self::$db=new ConnectionHandler($ruta_raiz);
		self::$flag=true;
	}
	public static function getCurrentInstance(){
		if(!self::$flag)
			new Connection();
		return self::$db;
	}
	
}
?>
