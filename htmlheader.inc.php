<?php
/**
 * @module  bandejas
 *
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright
 
 SIIM2 Models are the data definition of SIIM2 Information System
 Copyright (C) 2013 Infometrika Ltda.
 
 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Affero General Public License for more details.
 
 You should have received a copy of the GNU Affero General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();

if (empty($ruta_raiz)) {
    $ruta_raiz = "../..";
}

echo <<<EOF

      <title>..:: $entidad - Caliope ::..</title>

      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="icon" type="image/x-ico" media="screen" rel="shortcut icon" href="$ruta_raiz/img/favicon.png">
      <!-- Bootstrap core CSS -->
      <link rel="stylesheet" type="text/css" media="screen" href="$ruta_raiz/estilos/bootstrap.min.css">
      <!-- font-awesome CSS -->
      <link rel="stylesheet" type="text/css" media="screen" href="$ruta_raiz/estilos/font-awesome.css">
      <!-- Bootstrap core CSS -->
      <link rel="stylesheet" type="text/css" media="screen" href="$ruta_raiz/estilos/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" media="screen" href="$ruta_raiz/estilos/smartadmin-production.css">
      <link rel="stylesheet" type="text/css" media="screen" href="$ruta_raiz/estilos/smartadmin-skins.css">
      <link rel="stylesheet" type="text/css" media="screen" href="$ruta_raiz/estilos/siim_temp.css">
      <link rel="stylesheet" type="text/css" media="screen" href="$ruta_raiz/estilos/lockscreen.css">
      <link rel="stylesheet" type="text/css" media="screen" href="$ruta_raiz/estilos/uploadfile.css">
      <link rel="stylesheet" type="text/css" media="screen" href="$ruta_raiz/estilos/theme.default_tablesort.css">
	  
	  <style type="text/css">
.botonExcel{cursor:pointer;}
</style>


	  <script type="text/javascript" src="$ruta_raiz/js/jquery.min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/jsplumb/lib/jquery-1.9.0.js"></script>

      <!-- INICIO Utilizados en la administracion de usuarios roles Administracion/usuarios/template/index.tpl -->
      <script type="text/javascript" src="$ruta_raiz/js/jquery.tablesorter.min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/widget-pager.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/jquery.tablesorter.widgets.js"></script>
      <!-- FIN Utilizados en la administracion de usuarios roles Administracion/usuarios/template/index.tpl -->


      <script type="text/javascript" src="$ruta_raiz/js/libs/jquery-ui-1.10.4.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/jarvis.widget.min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/notification/SmartNotification.min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/plugin/sparkline/jquery.sparkline.min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/plugin/jquery-validate/jquery.validate.min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/plugin/masked-input/jquery.maskedinput.min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/plugin/select2/select2.min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/plugin/smartclick/smartclick.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/app.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/plugin/fuelux/wizard/wizard.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/plugin/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/bootstrap.js"></script>
      <script type="text/javascript" src="$ruta_raiz/js/jquery.form.js"></script>

      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/libs/js/yahoo-dom-event.js"></script>
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/libs/js/element-min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/libs/js/button-min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/libs/js/yahoo-min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/libs/js/event-min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/libs/js/connection-min.js"></script>

      <!-- INICIO archivo js para manejar los eventos -->
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/js/subserieMass.js"></script>		<!-- Cambia subserie al seleccionar serie-->
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/js/tipoDocMass.js"></script>		<!-- Cambia tipo Documental al seleccionar SubSerie-->
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/js/asignarTrdMass.js"></script>	<!-- Cambia tipo Documental al seleccionar SubSerie-->
      <!-- FIN archivo js para manejar los eventos -->


      <link rel="stylesheet" type="text/css" href="$ruta_raiz/estilos/fonts-min.css" >
      <!-- <link rel="stylesheet" type="text/css" href="$ruta_raiz/estilos/autocomplete.css"> -->

      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/libs/js/yahoo-dom-event.js"></script>
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/libs/js/datasource-min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/libs/js/connection-min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/libs/js/animation-min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/libs/js/autocomplete-min.js"></script>

      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/libs/js/element-min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/libs/js/yahoo-min.js"></script>
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/libs/js/event-min.js"></script>


      <!-- INICIO archivo js para manejar los eventos -->
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/js/serieMass.js"></script>		<!-- Cambia serie al seleccionar dependencia-->
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/js/subserieMass.js"></script>		<!-- Cambia subserie al seleccionar serie-->
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/js/autocomMass.js"></script>		<!-- Cambia tipodoc al seleccionar subserie-->
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/js/buscarExpMass.js"></script>	<!-- Busca el nombre del exp al pulsar-->
      <script type="text/javascript" src="$ruta_raiz/accionesMasivas/js/incluirEnExpMass.js"></script>	<!-- Incluir radicados en Expediente-->
      <!-- FIN archivo js para manejar los eventos -->

      <script src="$ruta_raiz/js/jsplumb/lib/jquery.ui.touch-punch.min.js"></script>
		<!-- /DEP -->
		<!-- JS -->

		<!-- support lib for bezier stuff -->
		<script src="$ruta_raiz/js/jsplumb/lib/jsBezier-0.6.js"></script>

		<!-- jsplumb geom functions -->
		<script src="$ruta_raiz/js/jsplumb/lib/jsplumb-geom-0.1.js"></script>

		<!-- jsplumb util -->
		<script src="$ruta_raiz/js/jsplumb/src/util.js"></script>

        <!-- base DOM adapter -->
		<script src="$ruta_raiz/js/jsplumb/src/dom-adapter.js"></script>

		<!-- main jsplumb engine -->
		<script src="$ruta_raiz/js/jsplumb/src/jsPlumb.js"></script>

        <!-- endpoint -->
		<script src="$ruta_raiz/js/jsplumb/src/endpoint.js"></script>

        <!-- connection -->
		<script src="$ruta_raiz/js/jsplumb/src/connection.js"></script>

        <!-- anchors -->
		<script src="$ruta_raiz/js/jsplumb/src/anchors.js"></script>

		<!-- connectors, endpoint and overlays  -->
		<script src="$ruta_raiz/js/jsplumb/src/defaults.js"></script>

        <!-- bezier connectors -->
        <script src="$ruta_raiz/js/jsplumb/src/connectors-bezier.js"></script>

		<!-- state machine connectors -->
		<script src="$ruta_raiz/js/jsplumb/src/connectors-statemachine.js"></script>

		<!-- SVG renderer -->
		<script src="$ruta_raiz/js/jsplumb/src/renderers-svg.js"></script>

		<!-- canvas renderer -->
		<script src="$ruta_raiz/js/jsplumb/src/renderers-canvas.js"></script>

		<!-- vml renderer -->
		<script src="$ruta_raiz/js/jsplumb/src/renderers-vml.js"></script>

        <!-- jquery jsPlumb adapter -->
		<script src="$ruta_raiz/js/jsplumb/src/jquery.jsPlumb.js"></script>

		<!-- jquery combogrid -->
		<script src="$ruta_raiz/js/jqgrid/resources/plugin/jquery.ui.combogrid-1.6.3.js"></script>

		<!-- jquery uploadfile permite subir archivos masivamente o Individuales -->
		<script src="$ruta_raiz/js/jquery.uploadfile.js"></script>
		    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
<script src="$ruta_raiz/js/holder.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="$ruta_raiz/js/ie10-viewport-bug-workaround.js"></script>
<!-- Bootstrap core CSS -->

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="$ruta_raiz/estilos/ie10-viewport-bug-workaround.css"
	rel="stylesheet">

<!-- Custom styles for this template -->
<link href="$ruta_raiz/estilos/dashboard.css" rel="stylesheet">

<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="$ruta_raiz/js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="$ruta_raiz/js/ie-emulation-modes-warning.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="$ruta_raiz/js/html5shiv.min.js"></script>
      <script src="$ruta_raiz/js/respond.min.js"></script>
    <![endif]-->
		
		<!-- exportar a EXCEL -->
		<script language="javascript">
        $(document).ready(function() {
	    $(".botonExcel").click(function(event) {
		$("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).html());
		$("#FormularioExportacion").submit();
        });
        });
        </script>
		
		
		
EOF;
