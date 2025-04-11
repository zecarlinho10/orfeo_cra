<?
if($mostrarTable)
{
?>
<table class="table table-bordered">
<?
}
?>
<?
if (!$k)  {
?> 
	<tr >
	<td height="21" align="center" > <?=$verrad_sal?></td>
	<td height="21" align="center" > <?=$verrad_padre?></td>
<?
} else {
?>
	<tr >
	<td ><?=$verrad_sal?></td>
	<td ><?=$verrad_padre?></td>
<?
}
?>
	
<!--<tr>-->
	<td ><label class=input><input type=text name=nombre_us id=nombre_us value='<?=$nombre_us?>' size=40 maxlength="110" ></label></td >
	<td ><label class=input><input type=text name=direccion_us id=direccion_us value='<?=$direccion_us?>' class=e_cajas size=30 ></label> </td >
	
	
	<?
	if($cantidadDestinos>=1)
		$nombreDestinos = "destino$cantidadDestinos";
	else
		$nombreDestinos = "destino";
	?>
	<td ><label class=input><input type=text name='<?=$nombreDestinos?>' value='<?=strtoupper($destino)?>' size=20 ></label></td >
	<td ><label class=input><input type=text name='departamento_us' id='departamento_us' value='<?=strtoupper($departamento_us)?>' size=10 ></label></td >
	<td ><label class=input><input type=text name='pais_us' id='pais_us' value='<?=$pais_us?>' size=10  ></label></td >
	<input type=hidden name=dir_codigo id=dir_codigo value='<?=$dir_codigo?>' size=5 >
	</td>
</tr>

<?
if (!$k)  {
?>
	<!--<tr  >-->
	<td height="21" colspan="2" >Asunto
	<label class=input><input type=text disabled name=ra_asun value='<?=$ra_asun?>'  size=120  >
	</td>
<!--</tr>-->


<!--<tr  >-->
	<td height="21" colspan="5">Observaciones o Desc Anexos
	<label class=input><input type=text name=observaciones value='<?=$observaciones?>' size=50 >
</TD>

<!--</TR>-->
</tr>


<?
}
?>
<?
if($mostrarTable)
{
?>
</table>
<?
}
?>