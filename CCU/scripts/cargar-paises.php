<?php
include("clases/class.combos.php");
$selects = new selects();
$paises = $selects->cargarPaises();
	foreach($paises as $d){
		//echo '<script language="javascript">alert("'.$d[2].'");</script>';
		//echo "<option value=\"$d[0]\">$d[2]</option>";
		
		print ('<option value="'.$d[0].'">' . $d[2] . '</option>');
		echo ('<option value="'.$d[0].'">' . $d[2] . '</option>');
		
		//printf('<option value="'.$d[0].'">"'.$d[2].'"</option>');
		//echo "xxx<br>";
		echo '<option value="' . $d[0] . '">' . $d[2] . '</option>';
		//echo '<script language="javascript">alert("'.$d[2].'");</script>';
	}
?>