<?php
session_start();
$path_raiz = realpath(dirname(__FILE__) . "/../");
require_once ($path_raiz . "/include/trd/Matriz.php");
require_once ($path_raiz . "/include/trd/Sectores.php");
$matriz = new Matriz();
$sector = new Sectores();

$dependencia = (! empty($_POST["coddepe"])) ? $_POST["coddepe"] : $_SESSION["dependencia"];

switch ($_POST["op"]) {
    case "buscarSerie":
        $salida = $matriz->getSeriesDependencia($dependencia, $dependencia);
        // "<select name=\"serie\" id=\'serie\' class=\'select\'>\n<option value=\"0\">Seleccione una serie</option>\n<option value=\'15\'>15 - CONTRATOS</option>\n<option value=\'29\'>29 - INSTRUMENTOS DE CONTROL</option>\n<option value=\'31\'>31 - INFORMES</option>\n<option value=\'32\'>32 - INVENTARIOS</option>\n<option value=\'3\'>3 - ACTAS</option>\n<option value=\'40\'>40 - PLANES</option>\n<option value=\'43\'>43 - PROGRAMAS</option>\n<option value=\'52\'>52 - SEGURIDAD</option>\n<option value=\'8\'>8 - CAJA MENOR</option>\n</select>\n"
        $sal = $salida->GetMenu2('serie', "0", "0:Seleccione una serie", false, false, " id='serie' class='select '", false);
        $sal = substr($sal, 48);
        echo substr($sal, 0, strlen($sal) - 10);
        break;
    case "buscarSubserie":
        $salida = $matriz->getSubSerie($dependencia, $_POST["serie"]);
        $sal = $salida->GetMenu('subserie', "0", "0:Seleccione una Sub Serie", false, false, " id='subSerie' class='select' ", false);
        $sal = substr($sal, 55);
        echo substr($sal, 0, strlen($sal) - 10);
        break;
    case "buscarTipo":
        $salida = $matriz->getTipoDocumental($dependencia, $_POST["serie"], $_POST["subSerie"]);
        $sal = $salida->GetMenu('tipo', "0", "0:Seleccione una serie", false, false, " id='tipo' class='select' ", false);
        $sal = substr($sal, 47);
        echo substr($sal, 0, strlen($sal) - 10);
        break;
    case "buscarSubserie":
        $salida = $matriz->getSubSerie($dependencia, $_POST["serie"]);
        $sal = $salida->GetMenu('subserie', "0", "0:Seleccione una Sub Serie", false, false, " id='subSerie' class='select' ", false);
        $sal = substr($sal, 51);
        echo substr($sal, 0, strlen($sal) - 10);
        break;
    case "buscaTipos":
        $salida = $matriz->getDetalleTipoDocumental($_POST["tipo"]);
        echo json_encode($salida);
        break;
    case "preload":
        $serie = $_POST["seriecodigo"];
        $subserie = $_POST["subseriecodigo"];
        $tipoDocumento = $_POST["tipocodigo"];
        $dep = $_POST["depecod"];
        $salida = $matriz->getSeriesDependencia($dep, $dep);
        $salSerie = $salida->GetMenu2('serie', $serie, "0:Seleccione una serie", false, false, " id='serie' class='select require chosen-select '", false);
        
        $salida = $matriz->getSubSerie($dep, $serie);
        $salSub = $salida->GetMenu('subserie', $subserie, "0:Seleccione una Sub Serie", false, false, " id='subSerie' class='select require chosen-select ' ", false);
        
        $salida = $matriz->getTipoDocumental($dep, $serie, $subserie);
        $salTipo = $salida->GetMenu('tipo', $tipoDocumento, "0:Seleccione un Tipo Documental", false, false, " id='tipo' class='select require chosen-select ' ", false);
        
        echo '
           <div class="row">
						<div class="col-md-4 col-lg-4 titulos5">
							<label style="display: inline;">Serie:</label>
						</div>
						<div class="col-md-8 col-lg-8 titulos5 text-left">
							' . $salSerie . '
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 col-lg-4 titulos5 ">
							<label style="display: inline;">Sub Serie:</label>
						</div>
						<div class="col-md-8 col-lg-8 titulos5 text-left">
							' . $salSub . '
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 col-lg-4 titulos5 ">
							<label style="display: inline;">Tipo Documental:</label>
						</div>
						<div class="col-md-8 col-lg-8 titulos5 text-left">' . $salTipo . '
						</div>
					</div>
           ';
        break;
    case "buscaSectores":
        $sec = $_POST["seccau"];
        // $sect = $sector->getSector($sec);
        $causal = $sector->getCausal($sec);
        echo '<div class="row">
					<div class="col-md-4 col-lg-4 titulos5 ">
						<label style="display: inline;">Tipificación:</label>
					</div>
					<div class="col-md-8 col-lg-8 titulos5 text-left">' . $causal->GetMenu('causal', "0", ":Seleccione el tipo de solicitud", false, false, " id='causal' class='select chosen-select  ' ", false) . '
					</div>
				</div>
				<script type="text/javascript">
						$("#causal").chosen();
					    $("#causal").trigger("chosen:updated");
					    $("#causal").change(function(){
		                  var param={op:"selDetalle",
				        detalle:$("#causal").val()};
		              $.ajax({
			             type:"POST",
			             url:basePath+\'/trd/menu_trd_lista.php\',
			             data:param,
			             success:function(data){
				                var retorno =jQuery.parseJSON(data);
			                 if(retorno.ter!=null &&  retorno.ter.length > 0 )		    
				            $("#termino").html(retorno.ter);
				            $("#terminoval").html(retorno.ter);	
			         },
			            error:function(){
				        alert("no hay conexi\u00f3n con el servidor");
			         }
		          });
					    });
	           </script>
            	</div>';
        break;
    case "buscaCausal":
        $sec = $_POST["causal"];
        $causal = $sector->getDetalle($sec);
        echo $causal->GetMenu('dettalle', "0", "0:Seleccione una detalle", false, false, " id='subSerie' class='select' ", false);
        break;
    case "selDetalle":
        $salida = array();
        $sec = $_POST["detalle"];
        $detalle = $sector->getAllDetalle($sec);
        $salida["ter"] = $detalle->fields["TERMINO"];
        $salida["des"] = $detalle->fields["SGD_DDCA_DESCRIP"];
        $salida["cod"] = $detalle->fields["SGD_DDCA_CODIGO"];
        echo json_encode($salida);
        break;
    
    case "habProcede":
        
        echo '<br />
         <div class="col-md-2 col-lg-2 titulos5"></div>
        <div class="col-md-2 col-lg-2 titulos5">Buscar entidad</div>
       <div class="col-md-8 col-lg-8 titulos2">
              <div class="col-md-2 col-lg-2 titulos5"><select id="tipoEntidad" ><option value="esp">ESP</option>
            <option value="oem">ENTIDADES</option>
            </select></div>   
             <span>Traslado:</span> 
            <input id="entidad"  class="cuadro" style="width:80%"/>
            <select id="transladoEntidad" name="traslados[]" multiple 
            data-placeholder="seleccione las empresas" style="width:80%">
            <option value="oem">ENTIDADES</option>
            </select>
            <input type="button" id="adicionar" class="btn btn-success"
					value="Adicionar" />
            <script type="text/javascript">
            $("#adicionar").click(function(){
                 BootstrapDialog.show({
                    size: BootstrapDialog.SIZE_WIDE,
                    title:"Adicionar Entidad",
                     message: $("<div></div>").load("crearEntidad.php"),
                        buttons: [{
                                id: "btn-1",
                                label: "Adicionar Entidad",
                                autospin: false, 
                                action: function(dialog) {
                                var $button = this;
                                if($("#crearEntidad").validate()){
                                     $button.spin();
                                     $("#crearEntidad").attr("action",basePath+ "/atencion/controllerAtencion.php");
                                     $("#crearEntidad").submit();
                                    dialog.close();
                                }else{
                                    $button.stopSpin();
                                }
                               }
                        }]
                 });
            });
            $("#tipoEntidad").chosen();
            $("#transladoEntidad").chosen();
            $("#entidad").autocomplete({
		  select :function( event, ui ){
			  console.log(ui.item);
			  event.preventDefault();
			$("#transladoEntidad").append("<option value=\""+$("#tipoEntidad").val()+"-"+ui.item.SGD_CIU_CODIGO+"\" selected=\"selected\">"+ui.item.SGD_CIU_NOMBRE+"</option>") 
            $("#transladoEntidad").trigger("chosen:updated");
            $("#transladoEntidad").trigger("liszt:updated");
            $("#entidad").val("");
          },
		  source: function(request, response) {
		    $.ajax({
		    	type : "POST",
				url : basePath+ "/atencion/controllerAtencion.php",
				 dataType: "json",
				data: {op:"buscarEntidad",identificacion: $("#entidad").val(),tipo:$("#tipoEntidad").val()
		      },
		      success: function(data) {
		    	if (data.success) {
		          response($.map(data.data, function(item) {
		            return {
		            	  SGD_CIU_CEDULA:item.SGD_CIU_CEDULA,
		            	  SGD_CIU_APELL2:item.SGD_CIU_APELL2+" "+item.SGD_CIU_APELL1,
		            	  SGD_CIU_NOMBRE:item.SGD_CIU_NOMBRE,
						  SGD_CIU_DIRECCION:item.SGD_CIU_DIRECCION,
						  SGD_CIU_EMAIL:item.SGD_CIU_EMAIL , 
						  SGD_CIU_TELEFONO:item.SGD_CIU_TELEFONO,
						  SGD_CIU_CODIGO:item.SGD_CIU_CODIGO,
		            	  label: item.SGD_CIU_APELL2 + " " +item.SGD_CIU_NOMBRE,
		                 per:item
		            }
		            
		          }));
		        } 
		      }
		    });
		  },
		  minLength: 3
		});
            
	
	</script>';
        
        break;
    default:
        ;
        break;
}

?>
