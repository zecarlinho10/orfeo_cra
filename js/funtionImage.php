<?php
/** Funcion de imagenes**/
?>
<script type="text/javascript">

function funlinkArchivo(numrad,rutaRaiz){
nombreventana="linkVistArch";
var rutaPath = "./";
        expr = /linkArchivo.php/;
        var     varDescarga = window.location.pathname.search(expr);
        if (varDescarga < 0 ){
                rutaPath = rutaRaiz ;
        }
url= rutaPath+ "/linkArchivo.php?"+"&numrad="+numrad;
ventana = window.open(url,nombreventana,'scrollbars=1,height=50,width=250');
//setTimeout(nombreventana.close, 70);
  return;
}

function noPermiso(){
	alert ("No tiene permiso para acceder");
}

function abrirArchivo(url){
	nombreventana='Documento'; 
	window.open(url, nombreventana,  'status, width=900,height=500,screenX=100,screenY=75,left=50,top=75');
	return; 
}
</script>
