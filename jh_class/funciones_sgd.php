<?php
error_reporting(7);
$k_localizacion++;
if($k_localizacion == 1)
{
/**
 * Se envian 3 variables: Cod Dpto, Cod Mcpio y Variable de Conexion a la BD.
 *
 * @param  char [$codigo_dep] Codigo departamento, formato 'ppp-ddd'.
 * @param  char [$codigo_mun] Codigo municipio, formato 'ppp-ddd-mmm'.
 * @param  ???? [$db] Variable de conexion a la BD.
 * @return char Nombre del Departamento.
 * @return char Nombre del Municipio.

 */
class LOCALIZACION {

var $municipio;
var $departamento;
var $pais;
var $continente;
//Maneja la conexi�n con la base de datos
 var $db;

function __construct($codigo_dep,$codigo_mun,$db)
{
#echo ">".$codigo_mun."<";
	if($codigo_dep)
	{	if (strpos($codigo_dep,'-'))
		{	$codigo_dep = explode('-', $codigo_dep);
			$codigo_pai = $codigo_dep[0];
			$codigo_dep = $codigo_dep[1];
			$isql = "SELECT DPTO_NOMB FROM DEPARTAMENTO,SGD_DEF_PAISES WHERE DEPARTAMENTO.id_pais = SGD_DEF_PAISES.ID_PAIS AND ".
				"DEPARTAMENTO.DPTO_CODI= $codigo_dep AND DEPARTAMENTO.ID_PAIS=$codigo_pai";
		}
		else
		{	 $isql = "select p.* from departamento p, MUNICIPIO M where p.DPTO_CODI =$codigo_dep and M.MUNI_CODI =$codigo_mun and p.ID_PAIS = M.ID_PAIS";
		}
		$rs1 = $db->query($isql);
	  	$depto=$rs1->fields["DPTO_NOMB"];
		$this->departamento = ( $depto === 'D.C.' ? $depto : ucfirst( strtolower( trim( $depto ) ) ) );
	 }
	else
	{	$this->departamento="";	}
	if($codigo_mun)
	{	if (strpos($codigo_mun,'-'))
		{	$codigo_mun = explode('-', $codigo_mun);
			$codigo_pai = $codigo_mun[0];
			$codigo_dep = $codigo_mun[1];
			$codigo_mun = $codigo_mun[2];
			$isql ="SELECT MUNI_CODI,MUNI_NOMB FROM MUNICIPIO where ".
				"id_pais=$codigo_pai AND DPTO_CODI=$codigo_dep AND MUNI_CODI=$codigo_mun";
		}
		else
		{	$isql ="SELECT MUNI_CODI,MUNI_NOMB FROM MUNICIPIO where DPTO_CODI=$codigo_dep AND MUNI_CODI=$codigo_mun AND ID_PAIS = $codigo_pai";}
		$flag_d = 0;
		$rs1 = $db->query($isql);
		$this->municipio=ucfirst(strtolower(trim($rs1->fields["MUNI_NOMB"])));
	}
	else
 	{	$this->municipio="";	}

	if (isset($codigo_mun))  unset($codigo_mun);
	if (isset($codigo_dep))  unset($codigo_dep);
	if (isset($codigo_pai))  unset($codigo_pai);
}

/**	Recibe el codigo del continente y la variable de conexion retornando el nombre.
 * @param  ???? [$db] Variable de conexi�n a la BD.
 * @return char Nombre del Continente.
*/
function GET_NOMBRE_CONT($codigo,$db)
{
	if(!($codigo or $db))
	{
		$this->continente="";
	}
	else
	{	$isql =	"SELECT NOMBRE_CONT FROM SGD_DEF_CONTINENTES WHERE SGD_DEF_CONTINENTES.ID_CONT=".$codigo;
		$rs1 = $db->query($isql);
		$this->continente=trim($rs1->fields["NOMBRE_CONT"]);
		unset($rs1);
	}
	return $this->continente;
}

/**	Recibe el codigo del pais y la variable de conexion retornando el nombre.
 * @param  int [$codigo] codigo del pais.
 * @param  ??? [$db] variable de conexion a la BD.
 * @return char Nombre del Pais.
*/
function GET_NOMBRE_PAIS($codigo,$db)
{
	if(!($codigo or $db))
	{
		$this->pais="";
	}
	else
	{	$isql =	"SELECT NOMBRE_PAIS FROM SGD_DEF_PAISES WHERE ID_PAIS=".$codigo;
		$rs1 = $db->query($isql);
		$this->pais=trim($rs1->fields["NOMBRE_PAIS"]);
		unset($rs1);
	}
	return $this->pais;
}
}
}
?>
