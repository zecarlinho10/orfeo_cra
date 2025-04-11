<?
/**
 *
 * @author     CARLOS RICAURTE
 * @version     1.0
 */
$ruta_raiz = "..";
session_start();
error_reporting(0);
require_once("$ruta_raiz/include/db/ConnectionHandler.php");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

include_once($ruta_raiz."/radicacion/crea_combos_universales.php");

//En caso de no llegar la dependencia recupera la sesi�n
if(!$_SESSION['dependencia']) include "$ruta_raiz/rec_session.php";

?>
<html>
<head>
<title>Sticker Web</title>

<script type="text/javascript" src="./js/jquery.js"></script>
<link rel="stylesheet" href="../../estilos/orfeo.css">
<script language="JavaScript" src="../../js/crea_combos_2.js"></script>


<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?

$params = session_name()."=".session_id()."&krd=$krd";

$radicado      = $_POST["radicado"];

$varEnvio = session_name() . "=" . session_id() . "&nurad=$radicado&ent=$ent";

?>

<form action="buscaRadicadoNuevo.php" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >

<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
	<tr align="center">
		<td height="25" colspan="2" class='titulos2'>
			CONSULTA INFORMACION DEL RADICADO
      </td>
    </tr>
	<tr align="center">
		<td width="31%" class='titulos2'>RADICADO</td>
		<td width="69%" height="30" class='listado2' align="left">
			<input name="radicado" id="radicado" type="input" size="50" class="tex_area">
		</td>
	</tr>
	
	<tr align="center">
		<td height="30" colspan="2" class='listado2'>
		<center>
			<button type="submit" class="btn btn-default">Consultar</button>
		</center>
		</td>
	</tr>
</table>
<table width="57%" border="0" cellspacing="5" cellpadding="0" align="center" class='table table-bordered table-striped mart-form'>
</table>
</form>
		
</body>
</html>
