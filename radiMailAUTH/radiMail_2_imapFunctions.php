<?php
include_once("functionsNimeshrmr.php");

#@author COGEINSAS Wilson Hernández Ortiz <-wilsonhernandezortiz@gmail.com->

# Las funciones aqui presentes han sido desarrolladas por COGEINSAS como una contribucion a la comunidad OrfeoGPL y a la fundacion Correlibre.

function getSection($structure){
	if (isset($structure->parts[0]->parts) || (isset($structure->parts[1]->parts) && strpos($structure->parts[1]->parameters[0]->value,"Apple-Mail") )){
	//if (isset($structure->parts[0]->parts) || isset($structure->parts[1]->parts)){
//		if (isset($structure->parts[0]->parts[0]->parts) || isset($structure->parts[1]->parts[0]->parts)){
		if (isset($structure->parts[0]->parts[0]->parts) || (isset($structure->parts[1]->parts[0]->parts) && $structure->parts[1]->subtype != "RFC822")){
			if(count($structure->parts[0]->parts[0]->parts)>1)
				$section="1.1.".(count($structure->parts[0]->parts[0]->parts));
			else
				$section=1.1;# Si tiene imagenes embebidas  &&  adjuntos
		}
		elseif(isset($structure->parts[0]->parts[1]->parts)){
				$section="1.2.".(count($structure->parts[0]->parts[0]->parts));
				$section="1.2.1";
		}
		else{
			$section=1.2;# Si tiene imagenes embebidas  ||   adjuntos
		}
	}
	elseif($structure->parts[1]->disposition=="ATTACHMENT" || $structure->parts[1]->disposition=="attachment"){
		$section=1;#Solo tiene adjuntos
	}elseif($structure->subtype=="HTML" or $structure->subtype=="PLAIN" or $structure->subtype=="RELATED" or $structure->subtype=="MIXED"){
		$section=1;#Solo tiene adjuntos
	}elseif($structure->parts[1]->parts){
		$section=2.1;	
	}else{
		$section=2;#Si NO tiene adjuntos
	}
	return $section;
}
function getBody($inbox,$msgNo,$section,$charset){
	switch ($charset){
		case "UTF-8":
		case "utf-8":
			$body =  utf8_decode(imap_qprint(imap_fetchbody($inbox,$msgNo,$section)));
			break;
		case "ISO-8859-1":
		case "iso-8859-1":
			$body =  utf8_encode(imap_qprint(imap_fetchbody($inbox,$msgNo,$section)));
			break;
		case "Windows-1252":
			$body = imap_qprint(imap_fetchbody($inbox,$msgNo,$section));
			break;
		default:
			$body =  imap_qprint(imap_fetchbody($inbox,$msgNo,$section));

			break;
	}
//	$body =  utf8_decode(imap_qprint(imap_fetchbody($inbox,$msgNo,$section)));
	if($body==''){
		$body =  imap_qprint(imap_fetchbody($inbox,$msgNo,1));
//		$body=base64_decode($body);
	}
	if(!mb_detect_encoding($body, 'UTF-8', TRUE)) $body=utf8_encode($body);
	if(base64_decode($body,true)){
		$body=base64_decode($body,true);
	}
	if($section=='1.1'){
		$body =  strstr ($body,"<div");# Limpia el string de salida.
	}
	//var_dump($section);
	if($section=='1.1' || $section=='1.2' || $section=='2.1' || $section=='1.1.2'){
		if($section =='1.1.2')
			$inline=getInline($inbox,$msgNo,'1.1');
		else
			$inline=getInline($inbox,$msgNo,$section);
		$dom = new DOMDocument();
		$dom->loadHTML($body);

		//Evaluate Anchor tag in HTML
		$xpath = new DOMXPath($dom);
		$imgs = $xpath->evaluate("/html/body//img");
	//var_dump($dom);

		for ($i = 0; $i < $imgs->length; $i++) {
			$img = $imgs->item($i);
			//remove and set target attribute       
			$isCid=explode(':',$img->getAttribute('src'));
			if ($isCid[0]=='cid'){
				$img->removeAttribute('src');
				$img->setAttribute("src", "data:image;base64,".$inline[$i]);
			}
		}
		// save html
		if($imgs->length>0)
			$body=$dom->saveHTML();
	}
//	var_dump($body);
	return $body;
}
function getInline($inbox,$msgNo,$section){
	#retorna array con las imagenes embebidas
	#returns array with the embedded images
	$structure =  imap_fetchstructure($inbox,$msgNo);
	if($section=='1.1'){
		$countInline=count($structure->parts[0]->parts);
		for ($i=$section+.1;$i<$section+.1*$countInline;$i=$i+.1){
			$inline[]=imap_fetchbody($inbox,$msgNo,$i);
		}
	}elseif($section=='1.2'){
		if ($structure->parts[1]->disposition=="INLINE" || $structure->parts[1]->disposition=="inline" || $structure->parts[1]->parts[1]->disposition=="inline")
			$countInline=count($structure->parts);
		for ($i=2;$i<=$countInline;$i++){
			$inline[]=imap_fetchbody($inbox,$msgNo,$i);
		}
	}elseif($section=='2.1'){
		$countInline=count($structure->parts[1]->parts);
		for ($i=$section+.1;$i<$section+.1*$countInline;$i=$i+.1){
			$inline[]=imap_fetchbody($inbox,$msgNo,$i);
		}
	}
	return array_reverse($inline);	
}
function fileAdttachments($db,$nurad,$user,$filename,$attachNumber,$dependence){
	$ext=strtolower(array_pop(explode(".",$filename)));
	$type = "SELECT ANEX_TIPO_CODI FROM ANEXOS_TIPO WHERE ANEX_TIPO_EXT = '$ext'";
	$type = $db->conn->query($type);
	$type = $type->fields["ANEX_TIPO_CODI"];
	if(!$type) $type = 99;
	$attachNumber=str_pad($attachNumber, 5, "0", STR_PAD_LEFT);
	$code = "$nurad$attachNumber";
	$anexName = $nurad."_$attachNumber.$ext";
	$record["ANEX_RADI_NUME"]    = $nurad;
	$record["ANEX_CODIGO"]       = "'$code'";
	$record["ANEX_SOLO_LECT"]    = "'S'";
	$record["ANEX_CREADOR"]      = "'$user'";
	$record["ANEX_DESC"]         = "' Archivo:.". $filename."'";
	$record["ANEX_NUMERO"]       = $attachNumber;
	$record["ANEX_NOMB_ARCHIVO"] = "'$anexName'";
	$record["ANEX_BORRADO"]      = "'N'";
	$record["ANEX_DEPE_CREADOR"] = $dependence;
	$record["SGD_TPR_CODIGO"]    = '0';
	$record["ANEX_TIPO"]         = $type;
	$sqlDate=$db->conn->DBDate(Date("Y-m-d"));
	$record["ANEX_FECH_ANEX"]    = $sqlDate;
	$anex['name']=$anexName;
	$anex['code']=$code;
	if ($db->insert("anexos", $record, "true")){
		return $anex;
	}
	return false;
	
}
function getCharset($section,$structure){
	if($section=='1.1'){
		$charset=$structure->parts[0]->parts[0]->parts[1]->parameters[0]->value;
	}elseif($section=='1.2'){
		if (is_array($structure->parts[0]->parts[1]->parameters)){
			$charset=$structure->parts[0]->parts[1]->parameters[0]->value;
		}elseif(is_array($structure->parts[1]->parts[0]->parameters)){
			$charset=$structure->parts[1]->parts[0]->parameters[0]->value;
		}else{
			return "No charset";
		}
	}elseif($section=='2'){
		foreach($structure->parts as $p){
			if ($p->ifparameters=='1'){
				if ($p->parameters[0]->attribute=="CHARSET"){
					$charset=$p->parameters[0]->value;
				}
			}
		}
	}
	return $charset;
}
//Funcion que obtiene todas las partes que contiene una estructura, no se usa pero es de gran ayuda cuando no es entiende la estructura de un correo
function getPartList($struct, $base="") {                                                       
     $res=Array();                                                                               
     if (!property_exists($struct,"parts")) {                                                    
        return [$base?:"0"];                                                                     
     } else {                                                                                    
        $num=1;                                                                                  
        if (count($struct->parts)==1) return getPartList($struct->parts[0], $base);              
        foreach ($struct->parts as $p=>$part) {                                                  
              foreach (getPartList($part, $p+1) as $subpart) {                                   
                  $res[]=($base?"$base.":"").$subpart;                                           
              }                                                                                  
       }                                                                                         
    }                                                                                            
   return $res;                                                                                  
 }              
?>
