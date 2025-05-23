<?
   class CONTROL_ORFEO
   {
   var $cursor;
   var $db;
   var $num_expediente;  // Almacena el nume del expediente
   var $estado_expediente; // Almacena el estado 0 para organizaci�n y 1 para indicar ke ya esta clasificado fisicamente en archivo
   var $exp_titulo;
   var $exp_asunto;
   var $campos_tabla;
   var $campos_vista;
   var $campos_width;
   var $campos_align;
   var $tabla_html;
   var $tabla_htmlE;
     
	 function CONTROL_ORFEO(& $db)
	 {
	 $this->cursor = & $db;
	 $this->db = & $db;	 
	 }
	 
	 function tabla_sql($query,$fecha_ini,$fecha_fin)
	 {
	    error_reporting(7);
	    $jh_db = $this->cursor->query($query);
	
		if ($jh_db) { 
		    echo "<table class='table table-bordered table-striped'>";
			echo "<tr>";
			echo "<td align=right bgcolor='#CCCCCC' height=25 class='titulos2'>FECHA INICIAL : </td>";
	    	echo "<td  width=65% height=25 class='listado2_no_identa'>";
	    	echo "$fecha_ini";
	    	echo "</td>";
	    	echo "</tr>";
			echo "<tr>";
			echo "<td align=right bgcolor='#CCCCCC' height=25 class='titulos2'>FECHA FINAL :</td>";
	    	echo "<td  width=65% height=25 class='listado2_no_identa'>";
	    	echo "$fecha_fin";
	    	echo "</td>";
	    	echo "</tr>";
			echo "<tr>";
	    	echo "<td align=right bgcolor='#CCCCCC height'=25 class='titulos2'>FECHA GENERACION : </td>";
	    	echo "<td  width=65% height=25 class='listado2_no_identa'>";
	    	echo date("Y-m-d - H:i:s");
	    	echo "</td>";
	    	echo "</tr>";	
			echo "</table>";
		  }
	    $tabla_htmlE = "<table class='table table-bordered table-striped'>";
	    echo "<table class='table table-bordered table-striped'>";
		if ($jh_db)
			{
			echo "<tr class='tpar'>";
			$tabla_htmlE .= "<tr bgcolor='#D0D0FF'>";
			$iii = 0;
			foreach($this->campos_vista as $campos_d)
			{
	        		$width = $this->campos_width[$iii];
					$tabla_htmlE .=  "<td width='$width' bgcolor='#CCCCCC'><center>$campos_d</td>";
					echo "<td class='titulos3'  ><center>$campos_d</td>";
					$iii++;
			}
			$tabla_htmlE .=  "</tr>";
			echo "</tr>";
			}
	$tabla_htmlE .=  "</table>";
	$this->tabla_htmlE .= $tabla_htmlE;

	return $nextval;
    }
	
    function tabla_Cuerpo()
	 {
	   $tabla_html = "<table class='table table-bordered table-striped'>";
	    error_reporting(7);
		$tabla_html .=  "<tr>";
		echo "<tr class=paginacion>";
		$iii = 0;
		foreach($this->campos_tabla as $campos_d)
				{
				    switch  ($this->campos_align[$iii])
					{
					case "L":
						$align = "Left";
						break;
					case "C":
						$align = "Center";
						break;
					case "R":
						$align = "Right";
						break;
					default:
						$align = "Left";
						break;
				     }

				$width = $this->campos_width[$iii];
				$width_max = intval(36 * $width / 250);	
	
				if(!$campos_d) $dato = "-"; else $dato=substr($campos_d,0,$width_max);
				$tabla_html .=  "<td  bgcolor='#CCCCCC'  align=$align width=".$this->campos_width[$iii]." height=27>$dato</td>";
				echo "<td align=$align>".$campos_d."</td>";
				$iii++;
			}
			$tabla_html .=  "</tr>";
			echo "</tr>";
	$tabla_html .=  "</table>";
	$this->tabla_html .= $tabla_html;
	return $nextval;
    }
}
