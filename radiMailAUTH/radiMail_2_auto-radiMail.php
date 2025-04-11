<?php
error_reporting(E_ERROR);
#ini_set('display_errors',true);
#error_reporting(0);

$ruta_raiz="/var/www/html/orfeo7/";
var_dump($argv);
require("configRadiMail.php");
include_once    ("$ruta_raiz/include/db/ConnectionHandler.php");
$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
//$db->conn->debug=true;
require("imapFunctions.php");
$sql_dataMail="select usua_email_1,pass_email from usuario where usua_login='ADMON'";
$sql_dataMail="select usua_email_1,pass_email from usuario where usua_login='MVALERO'";
$rs=$db->conn->getArray($sql_dataMail);
$usua_email=$rs[0]["USUA_EMAIL_1"];
$passwd_mail=base64_decode($rs[0]["PASS_EMAIL"]);
$hostname="{imap.gmail.com:993/imap/ssl/novalidate-cert}STARRED";
$hostname="{imap.gmail.com:993/imap/ssl/novalidate-cert}Destacados";
$hostname="{imap.gmail.com:993/imap/ssl/novalidate-cert}[Gmail]/Destacados";
$hostname="{imap.gmail.com:993/imap/ssl/novalidate-cert}ORFEO";
echo "<br> $usua_email - $passwd_mail <br>";
$inbox = imap_open($hostname,$usua_email,$passwd_mail) or die("error de conexion con servidor de correos");
echo "inbox::";
var_dump($inbox);
var_dump(imap_list($inbox,$hostname,'*'));
$emails = imap_search($inbox,'ALL');
$emailCount=count($emails);
echo "count $emailCount ";
$pager=70;
$options=getopt('',array('clean'));
if($emailCount<$pager)	
    $initCount=0;
else
    $initCount=$emailCount-$pager;
for($i=$initCount; $i<$emailCount;$i++){
	$e_uid[]=imap_uid($inbox,$emails[$i]);
}
	var_dump($e_uid);

//if (isset($options["clean"])){
if($argv[1]=="clean"){
	var_dump($e_uid);
	$inbox_uids="'".implode("','",$e_uid)."'";
	echo "\n++++DELETE++++\n";
//	$sql="delete from radimail_adjunto where radimail_id not in ($inbox_uids)";
	$sql="delete from radimail_adjunto";
	$db->conn->Execute($sql);
//	$sql="delete from radimail where uniqueid not in ($inbox_uids)";
	$sql="delete from radimail" ;
	$db->conn->Execute($sql);
}
//$db = pg_connect("host=localhost port=5432 dbname=$servicio user=$usuario password=$contrasena");
$sql="select msgno from radimail order by msgno desc";
$rs=$db->conn->getArray($sql);
$msgno=array_column($rs,'MSGNO');
//var_dump($msgno);
$current_mail=current($msgno);
if($current_mail){
	$to=$current_mail;
}else{
	$to=$emailCount-$pager;
}
++$to;
//for ($i=$emailCount;$i>$to;$i--){
$isql="select uniqueid from radimail";
$rs=$db->conn->GetArray($isql);
$rs=array_column($rs,'UNIQUEID');
//var_dump($e_uid);
//var_dump($rs);
foreach($e_uid as $e){
	$esta=false;
	foreach($rs as $s){
		if ($e==$s){
			$esta=true;
		}
	}
	if ($esta==false){
		$less[]=$e;
	}
}
if (count($less)>=1){
	//for ($i=$to;$i<=$emailCount;$i++){
	foreach ($less as $le){
		$i=imap_msgno($inbox,$le);
		$overview = imap_fetch_overview($inbox,$i,0);
		$fehead = imap_fetchheader($inbox,$i,0);
		$head = imap_header($inbox,$i);
		echo $mail[$i]['msgno'] = $i;
		echo "\n";
		echo $uid=$mail[$i]['uid'] = imap_uid($inbox,$i);
		echo "\n";
		$sql="select * from radimail where uniqueid='$uid'";
		$result = $db->conn->Execute($sql);
		$result = pg_fetch_row($result);
		//if(is_array($result)) break;
		$subject=$asunto=$mail[$i]['mailAsunto'] = imap_utf8(trim($overview[0]->subject));
		echo $date=$fecha=$mail[$i]['mailFecha'] = $date=$head->date;

		$desde=$mail[$i]['mailFrom'] = imap_utf8(trim($overview[0]->from));
		if (strpos($desde,'=?UTF-8?'))
			$desde=mb_decode_mimeheader($desde);
		echo $desde;
		$name=imap_utf8($head->from[0]->personal);
		$email=$head->from[0]->mailbox."@".$head->from[0]->host;
		$date=$head->MailDate;
		$apara=explode("To: ",$fehead);
		for( $l=1; $l<count($apara);$l++){
			if(substr($apara[$l-1],-1)!="-")  //Para buscar el 'To:' real
				$para=$apara[$l];
		}
		
		$para=explode("Content-Type: ",$para)[0];
		$para=explode("Subject: ",$para)[0];
		$para=explode("Cc: ",$para)[0];
		$para=explode("CC: ",$para)[0];
		$para=explode("CC: ",$para)[0];
		$para=explode("X-Report-",$para)[0];
		$para=explode("Received:",$para)[0];
		$para=explode("Message-ID: ",$para)[0];
		$para=explode("X-EOPAttributedMessage: ",$para)[0];
		$para=str_replace("<","(",$para);
		$para=str_replace(">",")",$para);
		$para=substr($para,0,999);
		$cc="";
		$dt = new DateTime($date);
		$dt->setTimezone(new DateTimeZone('America/Bogota'));
		$date=date_format($dt,'d M Y H:i:s P');
		if(substr($date,0,1)==" ")
			$date=substr($date,1);
		$darr=explode(' ',$date);
		$hora = explode(':',$darr[1]);

				$date="F: ".$date;
		//$para.=",".$mail[$i]['mailToF'] = imap_utf8(trim($overview[0]->to));
		$head = imap_header($inbox,$i);

		if($head->ccaddress){
			$cc.=$head->ccaddress;
		}
		if($head->bccaddress){
			$cc.="/bcc:".$head->bccaddress;
		}
		$cc=str_replace("<","(",$cc);
		$cc=str_replace(">",")",$cc);
		if($cc==""){
			$cc=false;	
		}
		$structure =  imap_fetchstructure($inbox,$i);
		$section = getSection($structure);
		var_dump($section);
		$charset = getCharset($section,$structure);
		$partNum="";
		$attachments = getAttachments($inbox, $i, $structure, $partNum);
		$asunto=str_replace("'","",$asunto);
		$asunto=str_replace("/","",$asunto);
		$asunto=str_replace("?","",$asunto);
		$asunto=str_replace(";","",$asunto);
		$asunto=str_replace('"',"",$asunto);
		$desde=str_replace("'"," ",$desde);
		$para=substr($para,0,999);
		$para=str_replace("'","",$para);
		$sql="insert into radimail (msgno,asunto,desde,para,fecha,uniqueid) values ('$i','$asunto','$desde','$para','$date','$uid')";
		//file_put_contents("/tmp/run.log","$i: $sql \n",FILE_APPEND);
		$result = $db->conn->Execute($sql);
		$k=0;
		var_dump($asunto);

		### Almacenamiento de imagen principal en DB
		$sql_adjuntos="insert into radimail_adjunto (radimail_id,name,filename) values ('$uid','$uid-0','$uid.html')";
		$result = $db->conn->Execute($sql_adjuntos);

		### Descarga imagen principal
		echo "\nllega a cuerpo\n ";
		while(!file_exists($ruta_raiz.'/bodega/tmp/radimail/'.$uid.'-0.html')){
			$body = getBody($inbox,$i,$section,$charset); 
			if (base64_decode($body, true) === false)
				$body=$body;
			else
				$body=base64_decode($body);
			unset($listaAdjuntos);
			$attachments2=array();
			foreach ($attachments as $attachment) {
				if($attachment["partNum"]){
					$listaAdjuntos.= "<a href='downloadAttachment.php?func=$func&folder=$folder&uid=$uid&part=".$attachment["partNum"]."&enc=".$attachment["enc"]."'>".imap_utf8($attachment["name"])."</a><br>";
					array_push($attachments2,$attachment);
				}
				else{
					foreach($attachment as $att){
						$listaAdjuntos.= "<a href='downloadAttachment.php?func=$func&folder=$folder&uid=$uid&part=".$att["partNum"]."&enc=".$att["enc"]."'>".imap_utf8($att["name"])."</a><br>";
						array_push($attachments2,$att);
					}
				}
			}
			ob_start();
			$buttonFiled='<button class="btn btn-primary btn-sm replythis"><i class="fa fa-reply"></i> Radicar</button>';
			$links="<!--Remplazame-->";
			include "mensaje.php";
			$data = ob_get_clean();
			$data = str_replace("../","",$data);

			ini_set('display_errors', '1');
			ini_set('display_startup_errors', '1');
			error_reporting(E_ERROR);
			$fp = fopen($ruta_raiz.'/bodega/tmp/radimail/'.$uid.'-0.html', 'w');
			fwrite($fp, $data);
			fclose($fp);
		}

		ini_set('display_errors', '0');
		ini_set('display_startup_errors', '0');
		if(count($attachments2)>0) 
		   $attachments=$attachments2;
		foreach ($attachments as $attachment) {
			++$k;
			unset($name);
			unset($partNum);
			unset($enc);
			unset($type);
			unset($sql_adjuntos);
			$filename=mb_decode_mimeheader($attachment["name"]);
			$type=$attachment["type"];
		/*if($uid=="9321" or $uid=="9196" or $uid=="9201"){
			var_dump($attachment);
		echo "\n";
		}*/
			if (strpos($filename,".")){
				$ext=strtolower(end(explode(".",$filename)));
			}else{
				$ext=strtolower(end(explode("/",$type)));
			}
			if (count($attachment)==1){
				//var_dump($attachment);
				$_attachment=$attachment;
				foreach(current($_attachment) as $at){
					$attachment[]=$at;
				}
				//var_dump($attachment);
				//die;
			}else{
				$name=$uid."-".$k;
				$partNum=$attachment["partNum"];
				$enc=$attachment["enc"];
				$type=$attachment["type"];

				### Almacenamiento de adjuntos en DB
				$sql_adjuntos="insert into radimail_adjunto (radimail_id,name,filename,partnum,enc,type) values ('$uid','$name','$filename','$partNum','$enc','$type')";
				$result = $db->conn->Execute($sql_adjuntos);

				### Descarga de adjuntos
				downloadAttachment($inbox, $uid, $attachment["partNum"], $attachment["enc"], $path, false, false, $name.".".$ext);
			}
			$adj.=" - $name.$ext";
		}
		echo "$uid";
		echo "$adj\n";
		unset($adj);
		unset($listaAdjuntos);
		echo "\n";
	}
}
$options=getopt('',array('clean'));
if (isset($options["clean"])){
	$sql="select msgno, uniqueid from radimail order by uniqueid asc limit (select count(*) from radimail)-15";
	$rs=$db->conn->getArray($sql);
	$rs=array_column($rs,'UNIQUEID');
	foreach ($rs as $key){
		//$db->conn->debug=true;
		$sql="delete from radimail_adjunto where radimail_id in ($key)";
		$db->conn->Execute($sql);
		$sql="delete from radimail where uniqueid in ($key)";
		$db->conn->Execute($sql);
		array_map("unlink", glob("$ruta_raiz/bodega/tmp/radimail/$key*"));
	}
	//var_dump($rs);
echo "Borrando: ";
echo $uniqueids=implode(',',$rs);
}
//var_dump(pg_fetch_all($result));
//var_dump($mail);

?>
