<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="../../estilos/orfeo.css">
 <! --bootstrap-->

     <!-- Bootstrap core CSS -->
      <link rel="stylesheet" type="text/css" media="screen" href="../estilos/bootstrap.min.css">
      <!-- font-awesome CSS -->
      <link rel="stylesheet" type="text/css" media="screen" href="../estilos/font-awesome.css">
      <!-- Bootstrap core CSS -->
      <link rel="stylesheet" type="text/css" media="screen" href="../estilos/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" media="screen" href="../estilos/smartadmin-production.css">
      <link rel="stylesheet" type="text/css" media="screen" href="../estilos/smartadmin-skins.css">
      <link rel="stylesheet" type="text/css" media="screen" href="../estilos/siim_temp.css">
      <link rel="stylesheet" type="text/css" media="screen" href="../estilos/lockscreen.css">
      <link rel="stylesheet" type="text/css" media="screen" href="../estilos/uploadfile.css">
      <link rel="stylesheet" type="text/css" media="screen" href="..//estilos/theme.default_tablesort.css">

	  <script type="text/javascript" src="../js/jquery.min.js"></script>
      <script type="text/javascript" src="../js/jsplumb/lib/jquery-1.9.0.js"></script>
      <script type="text/javascript" src="../js/plugin/jquery-confirm/jquery-confirm.min.js"></script>
      <link rel="stylesheet" type="text/css" href="../js/plugin/jquery-confirm/jquery-confirm.min.css" >

 <! --cierra bootstrap-->

<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery.min.js"></script>
<form action="" method="post" name="parametized_radmail" id="parametized_radmail">
	
<!-- widget grid -->
    <section id="widget-grid">
      <!-- row -->
      <div>
        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <!-- Widget ID (each widget will need unique ID)-->
          <div class="jarviswidget jarviswidget-color-darken" id="wid-id-1" data-widget-editbutton="false">

            <header>
              <h2>
				Formulario administraci&oacute;n buzones radicaci&oacute;n mail
              </h2>
            </header>

            <!-- widget div-->
            <div>
              <!-- widget content -->
              <div class="widget-body no-padding">

            <table class="table table-bordered table-striped">
			<tr class="message">
				[!message]
						</tr>
			<tr>
				<td class="listado2" >
				<label for="mail">Host</label> 
				</td>
				
				<td colspan="2">
				<input type="text" name="mail_host" class="textogris" value="[!mail_host]" id="mail_host" />
				</td>
			</tr>

			<tr> 
				<td class="listado2">
				<label for="mail">Correo</label> 
				</td>
				
				<td>
					<input type="hidden" name="mail_id" class="textogris" value="[!mail_id]" id="mail_id" />	
					<input type="text" name="mail" class="textogris" value="[!mail]" id="mail" />	
				<td>
			</tr>


			<tr>
				<td class="listado2">
				<label for="pass">Clave</label>
								</td>
				<td class="colspan="2"">
				<input type="password" name="pass" class="textogris" value="" id="pass" />	
				</td>

			<tr>	
				<td class="listado2">
				<label for="dest_alt">Destinatarios alternativos</label>
				</td>
				
				<td id="dependencias">
					<select name='depe_select[]' class="listado2" id='depe_select' multiple='' size='8'>
                              
				</select>
				</td>

				<td id="usuarios">
					<select name='usuaSelect[]' class="listado2"	 id='usuaSelect' multiple='' size='8' style='display:none'>
					</select>
				</td>	
			</tr>

			<tr>
			<td colspan="3" align="center">						
				<input type="submit" name="save" class="btn btn-primary" value="Salvar" id="save" />	
			</td> 
			<tr>
</form>	
</table>


<table class="table table-striped table-bordered imagenaccion">
	<tr>
		<th colspan="6" class="center listado2 titulostabla"> Buz&oacute;n parametrizado para lectura de correo:</th>
	</tr>	
	<tr>
		<th class="center textoazul" ><b>Correo ID</b></th>
		<th class="center textoazul"><b>Host</b></th>
		<th class="center textoazul"><b>Correo</b></th>
		<th class="center textoazul"><b>Clave</b></th>
		<th class="center textoazul"><b>Destinatario Alternativo</b></th>
		<th class="center textoazul"><b>Acciones</b></th>
	</tr>	
	[!mails]
</table>
<script type="text/javascript" src="../js/modulosApp/correosElectronicos/selectUsuarios.js"></script>
<script type="text/javascript" src="../js/modulosApp/correosElectronicos/validarFormulario.js"></script>
