		function confirmation() {
		    if(confirm("Confirma la generación del Inventario"))
		    {
		        return true;
		    }
		    return false;
		}

		function notificacion(x){
		        //una notificación normal
		    if(x==1){
		    	alert("Actividad ingresada exitosamente.");
		    }
		    else{
		    	alert("Error al ingresar Actividad."); 	
		    }
		      
		      return false;
		}

		

		function generaJavaXML(obj){
			
		    var objXML=obj
		    
	        $.ajax({
	            type:"POST",
	            url:"../archivo/procesaXML.php",
	            data:{"objXML":objXML},
	            beforeSend: function () {
	            	$("#resultado").html("Procesando, espere por favor...");
	            },
	            success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
	                $("#resultado").html(response);
	                //alert ("Actualizado Exitosamente.");
	            }
	        });
		}
