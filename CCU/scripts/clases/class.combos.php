<?php

$ruta_raiz = "../..";
include "$ruta_raiz/config.php";
include_once( "$ruta_raiz/include/db/ConnectionHandler.php" );




class selects
{
	var $code = "";
	
	function cargarPaises()
	{	
		$db = new ConnectionHandler( "$ruta_raiz" );
		$query=$db->query("SELECT ID_PAIS, ID_CONT, NOMBRE_PAIS FROM SGD_DEF_PAISES ORDER BY NOMBRE_PAIS");
		$paises = array();
		
		$i=0;
		while(!empty($query) && !$query->EOF){ 
				
			 	$paises[$i]=$query->fields; 
				$i++;
				$query->MoveNext ();
				
				/*
				$code = $pais["ID_PAIS"];
				$name = $pais["NOMBRE_PAIS"];				
				$paises[$code]=$name;
				*/
			}
		return $paises;
	}
	function cargarEstados()
	{
		$consulta = parent::consulta("SELECT Name FROM province WHERE Country = '".$this->code."'");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$estados = array();
			while($estado = parent::fetch_assoc($consulta))
			{
				$name = $estado["Name"];				
				$estados[$name]=$name;
			}
			return $estados;
		}
		else
		{
			return false;
		}
	}
		
	function cargarCiudades()
	{
		$consulta = parent::consulta("SELECT Name FROM city WHERE Province = '".$this->code."'");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$ciudades = array();
			while($ciudad = parent::fetch_assoc($consulta))
			{
				$name = $ciudad["Name"];				
				$ciudades[$name]=$name;
			}
			return $ciudades;
		}
		else
		{
			return false;
		}
	}		
}
?>