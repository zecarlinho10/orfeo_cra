<style>
<!-- /* - - - - - - - - - - - - - - - - - - - - -

Title : Wufoo Form Framework
Author : Infinity Box Inc.
URL : http://wufoo.com

Last Updated : October 14, 2008

- - - - - - - - - - - - - - - - - - - - - */
.wufoo {
	font-family: "Lucida Grande", Tahoma, Arial, sans-serif;
}

.wufoo li {
	width: 82%;
}

form ul {
	margin: 0;
	padding: 0;
	list-style-type: none;
	width: 100%;
}

form li {
	display: block;
	margin: 0;
	padding: 4px 5px 2px 9px;
	clear: both;
}

form li:after {
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;
}

* html form li {
	height: 1%;
}

* html form li div {
	display: inline-block;
}

*+html form li div {
	display: inline-block;
}

form li div, form li span {
	margin: 0 0px 0 0;
	padding: 0 0 1px 0;
	color: #444;
}

form li span {
	float: left;
}

form li div.column {
	padding-bottom: 0;
}

form li div span.left, form li div span.right {
	width: 87%;
	margin: 0;
}

form li div span.full input, form li div span.full select, form li div span.left input,
	form li div span.right input, form li div span.left select, form li div span.right select
	{
	width: 100%;
}

.left {
	float: left;
}

.right {
	float: right;
}

.clear {
	clear: both !important;
}

/* ----- INFO ----- */
.info {
	display: inline-block;
	clear: both;
	border-bottom: 1px dotted #ccc;
	margin: 0 0 1em 0;
}

.info[class] {
	display: block;
}

.info h2 {
	font-weight: normal;
	font-size: 160%;
	margin: 0 0 .2em 0;
	clear: left;
}

.info div {
	font-size: 95%;
	line-height: 135%;
	margin: 0 0 1em 0;
	color: #555;
}

/* ----- SECTIONS ----- */
form hr {
	display: none;
}

form li.section {
	border-top: 1px dotted #ccc;
	padding-top: .9em;
	padding-bottom: 0px;
	padding-left: 9px;
	width: 97% !important;
	position: static;
}

form ul li.first {
	border-top: none !important;
	margin-top: 0px !important;
	padding-top: 0px !important;
}

form .section h3 {
	font-weight: normal;
	font-size: 110%;
	line-height: 135%;
	margin: 0 0 .2em 0;
}

form .section div {
	display: block;
	font-size: 85%;
	margin: 0 0 1.2em 0;
	padding: 0;
}

/* ----- LIKERT SCALE ----- */
form li.likert {
	width: 97% !important;
}

.likert table {
	margin: 0 0 1.5em 0;
	background: #fff;
}

.likert caption {
	text-align: left;
	color: #222;
	font-size: 95%;
	font-weight: bold;
	line-height: 135%;
	padding: 4px 0 .4em 0;
}

.likert label {
	font-size: 10px;
	display: block;
}

.likert thead td {
	border-left: 1px solid #dedede;
	font-size: 85%;
	padding: 10px 6px;
}

.likert thead td, .likert thead th {
	background-color: #e5e5e5;
}

.likert th, .likert td {
	border-bottom: 1px solid #dedede;
	padding: 4px 8px;
}

.likert tbody th {
	color: #222;
	font-size: 95%;
	font-weight: bold;
}

.likert td {
	border-left: 1px solid #dedede;
	width: 12%;
	text-align: center;
}

.likert tbody tr.alt td, .likert tbody tr.alt th {
	background-color: #f8f8f8;
}

.likert tbody tr:hover td, .likert tbody tr:hover th {
	background-color: #FFFFCF;
}

/* ----- LABELS ----- */
label.desc {
	line-height: 150%;
	padding: 0 0 1px 0;
	border: none;
	color: #222;
	display: block;
	font-size: 95%;
	font-weight: bold;
}

form li div label, form li span label {
	margin: 0;
	padding-top: 6px;
	clear: both;
	font-size: 9px;
	line-height: 9px;
	color: #444;
	display: block;
}

label.choice {
	font-size: 100%;
	display: block;
	line-height: 1.5em;
	margin: -1.65em 0 0 25px;
	padding: .44em 0 .5em 0;
	color: #222;
	width: 88%;
	display: block;
}

span.symbol {
	font-size: 115%;
	line-height: 130%;
}

form li .datepicker {
	float: left;
	margin: .1em 5px 0 0;
	padding: 0;
	width: 16px;
	height: 16px;
	cursor: pointer !important;
}

/* ----- MIN/MAX COUNT ----- */
form li div label var {
	font-weight: bold;
	font-style: normal;
}

form li div label .currently {
	display: none;
}

/* ----- FIELDS ----- */
input.text, input.file, textarea.textarea, select.select {
	margin: 0;
	font-size: 100%;
	color: #333;
}

input.text, textarea.textarea, .firefox select.select {
	border-top: 1px solid #7c7c7c;
	border-left: 1px solid #c3c3c3;
	border-right: 1px solid #c3c3c3;
	border-bottom: 1px solid #ddd;
}

input.text, input.file {
	padding: 2px 0 2px 0;
}

input.checkbox, input.radio {
	display: block;
	line-height: 1.5em;
	margin: .6em 0 0 3px;
	width: 13px;
	height: 13px;
}

textarea.textarea {
	font-family: "Lucida Grande", Tahoma, Arial, sans-serif;
}

select.select {
	font-family: "Lucida Grande", Tahoma, Arial, sans-serif;
	margin: 1px 0;
	padding: 1px 0 0 0;
}

select.select[class] {
	margin: 0;
	padding: 1px 0 1px 0;
}

*:first-child+html select.select[class] {
	margin: 1px 0;
}

.safari select.select {
	margin-bottom: 1px;
	font-size: 120% !important;
}

/* ----- BUTTONS ----- */
input.btTxt {
	padding: 0 7px;
	width: auto;
	overflow: visible;
}

.buttons {
	clear: both;
	margin-top: 10px;
}

.buttons input {
	font-size: 120%;
	margin-right: 5px;
}

/* ----- TEXT DIRECTION ----- */
.rtl .info h2, .rtl .info div, .rtl label.desc, .rtl label.choice, .rtl div label,
	.rtl span label, .rtl input.text, .rtl textarea.textarea, .rtl select.select,
	.rtl p.instruct, .rtl .section h3, .rtl .section div, .rtl input.btTxt
	{
	direction: rtl;
}

/* ----- SIZES ----- */
.third {
	width: 32% !important;
}

.half {
	width: 48% !important;
}

.full {
	width: 100% !important;
}

input.small, select.small {
	width: 25%;
}

input.medium, select.medium {
	width: 50%;
}

input.large, select.large, textarea.textarea {
	width: 100%;
}

textarea.small {
	height: 5.5em;
}

textarea.medium {
	height: 10em;
}

textarea.large {
	height: 20em;
}

/* ----- ERRORS ----- */
#errorLi {
	width: 99%;
	margin: 0 auto;
	background: #fff;
	border: 1px dotted red;
	margin-bottom: 1em;
	text-align: center;
	padding-top: 4px;
	padding-left: 0px;
	padding-right: 0px;
}

#errorMsgLbl {
	margin: 7px 0 5px 0;
	padding: 0;
	font-size: 125%;
	color: #DF0000;
}

#errorMsg {
	margin: 0 0 .8em 0;
	color: #000;
	font-size: 100%;
}

#errorMsg strong {
	background-color: #FFDFDF;
	padding: 2px 3px;
	color: red;
}

form li.error {
	display: block !important;
	border-bottom: 1px solid #F9B9B2;
	border-right: 1px solid #F9B9B2;
	background-color: #FFDFDF !important;
}

form li.error label {
	color: #DF0000 !important;
}

form p.error {
	display: none;
	color: red;
	font-weight: bold;
	font-size: 10px;
	margin: -2px 0 5px 0;
	clear: both;
}

form li.error p.error {
	display: block;
}

.leftLabel p.error, .rightLabel p.error {
	margin-left: 30%;
	padding-left: 15px;
}

.noI .leftLabel p.error, .noI .rightLabel p.error {
	margin-left: 35%;
	padding-left: 15px;
}

/* ----- REQUIRED ----- */
form .req {
	float: none;
	color: red !important;
	font-weight: bold;
	margin: 0;
}

/* ----- INSTRUCTIONS ----- */
form li.focused {
	background-color: #fff7c0;
}

form li.focused, form li:hover {
	position: relative; /* Makes Instructs z-index stay on top in IE. */
}

form .instruct {
	position: absolute;
	top: 0;
	left: 0;
	z-index: 1000;
	width: 82%;
	margin: 0 0 0 8px;
	padding: 8px 10px 9px 10px;
	border: 1px solid #e6e6e6;
	background: #f5f5f5;
	line-height: 130%;
	font-size: 80%;
	color: #444;
	visibility: hidden;
}

form .instruct small {
	font-size: 105%;
}

form li.focused .instruct, form li:hover .instruct {
	left: 100%; /* Prevent scrollbars for IE Instruct fix */
	visibility: visible;
}

/* ----- ALT INSTRUCTIONS ----- */
li.altInstruct .instruct, li.leftHalf .instruct, li.rightHalf .instruct
	{
	visibility: visible;
	position: static;
	margin: -2px 0 0 0;
	padding: 0 0 7px 0;
	background: none;
	border: none;
	width: 100%;
	font-size: 9px;
	clear: left;
}

/* ----- LABEL LAYOUT ----- */
.leftLabel li, .rightLabel li {
	width: 74% !important;
	padding-top: 9px;
}

.leftLabel label.desc, .rightLabel label.desc {
	float: left;
	width: 29%;
	margin: 2px 15px 0 0;
}

.rightLabel label.desc {
	text-align: right;
}

.leftLabel .column, .rightLabel .column {
	float: left;
}

.leftLabel .small, .rightLabel .small {
	width: 17%;
}

.leftLabel .medium, .rightLabel .medium {
	width: 35%;
}

.leftLabel .large, .leftLabel textarea.textarea, .rightLabel .large,
	.rightLabel textarea.textarea, .leftLabel .column, .rightLabel .column
	{
	width: 64%;
}

* html .leftLabel .small, * html .rightLabel .small {
	width: 23%;
}

* html .leftLabel .medium, * html .rightLabel .medium {
	width: 55%;
}

* html .leftLabel .large, * html .leftLabel textarea.textarea, * html .rightLabel .large,
	* html .rightLabel textarea.textarea {
	width: 97%;
}

.leftLabel p.instruct, .rightLabel p.instruct {
	width: 27%;
	margin-left: 5px;
}

.leftLabel .altInstruct .instruct, .rightLabel .altInstruct .instruct {
	margin-left: 29% !important;
	padding-left: 15px;
	width: 65%;
}

.leftLabel .buttons, .rightLabel .buttons {
	margin-left: 15px;
}

.leftLabel .buttons input, .rightLabel .buttons input {
	margin-left: 29%;
}

* html .leftLabel .buttons input, * html .rightLabel .buttons input {
	margin-left: 22%;
}

* html .leftLabel .buttons, * html .rightLabel .buttons {
	margin-left: 4px;
}

*+html .leftLabel .buttons, *+html .rightLabel .buttons {
	margin-left: 7px;
}

/* ----- NO INSTRUCTIONS ----- */
.noI .instruct {
	display: none !important;
}

.noI form li, .noI form li.buttons {
	width: 97% !important;
}

.noI form li.section {
	padding-left: 9px !important;
}

/* ----- NO INSTRUCTIONS LABEL LAYOUT ----- */
.noI .leftLabel label.desc, .noI .rightLabel label.desc {
	width: 34%;
}

.noI .leftLabel .large, .noI .leftLabel textarea.textarea, .noI .rightLabel .large,
	.noI .rightLabel textarea.textarea, .noI .leftLabel .column, .noI .rightLabel .column
	{
	width: 60%;
}

.noI .leftLabel .buttons input, .noI .rightLabel .buttons input {
	margin-left: 34%;
}

/* ----- FIELD FLOATING ----- */
form li.leftHalf, form li.rightHalf {
	width: 46% !important;
}

li.leftHalf {
	clear: left;
	float: left;
}

li.rightHalf {
	clear: none;
	float: right;
}

li.leftHalf .medium, li.rightHalf .medium, li.leftHalf .small, li.rightHalf .small
	{
	width: 100%;
}

[disabled] {
	/* Text and background colour, medium red on light yellow */
	color: #933;
	background-color: #686868;
}
-->
</style>
<div>
	<form action="controllerAtencion" id="crearEntidad" method="post">
		<div class=" row col-sm-12  col-md-12 dinamic" id="juridica">
			<div class="row form-group">
				<input value="crearEntidad" name="op" type="hidden" /> <input
					value="1" name="tipoPersona" type="hidden" />
				<div class="col-sm-12  col-md-3">
					<label class="desc desc     control-label form-control"
						for="txtnit">Nit*</label>
				</div>
				<div class="col-sm-12  col-md-3 ">
					<input class="form-control required digitos" value=""
						maxlength="11"
						title="el campo Nit es Obligatorio y solo acepta digitos"
						minlenght="3" name="nit" tabindex="4"
						onkeypress="return alpha(event,numbers)" id="txtnit" type="text" />

				</div>
				<div class="col-sm-12  col-md-3">
					<label class="desc desc     control-label form-control"
						for="txtnoEmpresa">Nombre Empresa*</label>
				</div>
				<div class="col-sm-12  col-md-3 ">
					<input class="form-control required" value="" maxlength="11"
						title="el campo Nombre es Obligatorio " minlenght="3"
						name="noEmpresa" tabindex="4"
						onkeypress="return alpha(event,numbers+letters)" id="txtnoEmpresa"
						type="text" />

				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-12  col-md-3">
					<label class="desc  control-label form-control" for="txtnit">Representante
						Legal*</label>
				</div>
				<div class="col-sm-12  col-md-3 ">
					<input class="form-control required" value="" maxlength="11"
						title="el campo Representante es Obligatorio " minlenght="3"
						name="representante" tabindex="4"
						onkeypress="return alpha(event,numbers+letters)" id="txtrep"
						type="text" />

				</div>
				<div class="col-sm-12  col-md-3">
					<label class="desc control-label form-control" for="txtdirEmpresa">Dirección*</label>
				</div>
				<div class="col-sm-12  col-md-3 ">
					<input class="form-control required" value="" maxlength="11"
						tabindex="4" onkeypress="return alpha(event,numbers+letters)"
						title="El campo Dirección  de la empresa es Obligatorio "
						minlenght="3" name="dirEmpresa" id="txtdirEmpresa" type="text" />

				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-12  col-md-3">
					<label class="desc desc     control-label form-control"
						for="txtnfijo">Teléfono Contacto*</label>
				</div>
				<div class="col-sm-12  col-md-3 ">
					<input class="form-control" value="" maxlength="11"
						name="telEmpresa"
						title="El campo teléfono de la Empresa es Obligatorio"
						tabindex="4" onkeypress="return alpha(event,numbers+letters)"
						id="txtcontacto" type="text" />
				</div>
				<div class="col-sm-12  col-md-3">
					<label class="desc control-label form-control" for="txtemmail">Correo
						Eléctronico</label>
				</div>
				<div class="col-sm-12  col-md-3 ">
					<input class="form-control required email" value=""
						name="emailEmpresa"
						title="el campo email es obligatorio y debe ser válido"
						minlenght="3" tabindex="4" id="txtmailEmpresa" type="text" />
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-12  col-md-3">
					<label class="desc desc     control-label form-control" for="grupo">Tipo
						de Empresa*</label>
				</div>
				<div class="col-sm-12  col-md-3 ">
					<select data-placeholder="Tipo de Empresa"
						title="Debe seleccione un tipo de la lista el campo es Obligatorio"
						class="dropdown required seleccion form-control chosen-select "
						id="tipoEmpresa" name="tipoEmpresa">
						<option selected="" value="">Seleccione Tipo de Empresa</option>
						<option value="1" value="">Pública</option>
						<option value="2" value="">Privada</option>
					</select>
				</div>
			</div>
		</div>
		<div class="row col-sm-12  col-md-12" id="ubicacion">
			<div class="row form-group">
				<div class="col-sm-12  col-md-3">
					<label class="desc desc     control-label form-control" for="pais">País*</label>
				</div>
				<div class="col-sm-12  col-md-3 ">
					<select data-placeholder="País"
						title="Debe seleccionar un pais el el campo es obligatorio "
						name="pais" class="chosen-select form-control seleccion" id="pais">
						<option value=""></option>
					</select>
				</div>
				<div class="col-sm-12  col-md-3">
					<label for="dpto" class="error" style="display: none;">Seleccione
						un Departamento el campo es Obligatorio</label> <label
						class="desc control-label form-control" for="txtdocumento"><span>Departamento*</span></label>
				</div>
				<div class="col-sm-12  col-md-3 ">
					<select data-placeholder="Departamento"
						class="chosen-select form-control dropdown seleccion" id="dpto"
						title="el campo Departamento es obligatorio " name="dpto">
					</select>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-12  col-md-3">
					<label class="desc desc     control-label form-control"
						for="txtapellido1">Municipio*</label>
				</div>
				<div class="col-sm-12  col-md-3 ">
					<select data-placeholder="Municipio"
						class="chosen-select form-control dropdown seleccion" id="mcpio"
						title="el campo tipo de Municipio es obligatorio " minlenght="3"
						name="mncpio">
					</select>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		$("#crearEntidad").validate({
			  submitHandler: function(form) {
				    $(form).ajaxSubmit();
				  },
				  rules : {pais : {
						required : true
					}
				},
				messages : {
					pais:"el campo pais es obligatorio "
				}
				});
		</script>
		<script type="text/javascript"
			src="../js/divipola.js?tes=<?php echo date("Ymdhis")?>"></script>
	</form>
</div>
