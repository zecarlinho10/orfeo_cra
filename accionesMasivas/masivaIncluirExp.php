<?

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
$radicados=$_GET['radicados'];
//$radicados=explode(",", $_GET['radicados']);
?>

<!DOCTYPE html>
<html>
<head>
	<title>.:.Mover Radicados.:.</title>
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="jquery.min.js"></script>
<script> 


function confirmation() {
    if(confirm("Realmente desea Incluir Expedientes?"))
    {
        return true;
    }
    return false;
}


</script> 

</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6">
			<h1>Incluir radicados en expediente masivamente</h1>
			</div>
		</div>
		<div class="row">
		<div class="col-md-6">
		<form method="post" action="./accionesMasivas/requestAjax/masivaIncluirExp.php" onsubmit="return confirmation()">
		  <div class="form-group">
		  	<input type="hidden" name="radicados" value="<? echo $radicados; ?>">
		    <label for="name1">Digite Expediente:</label>
		    <input name="expedienteain" id="expedienteain" type="text" />
		  </div>

		  <button type="submit" class="btn btn-default">Enviar</button>
		</form>
		</div>
		</div>
	</div>

</body>
</html>
