<?php

# Config
$spool = '/var/spool/solr/solr-connector-files/';

?>
<html>
<head>
	<title>Crawl</title>
</head>
<body>

<?php


$uri = isset($_REQUEST['uri']) ? $_REQUEST['uri'] : false;

// If no uri, print form
if (!$uri) {

?>


Verzeichnis, Dateiname oder URL:
<form action="crawl.php" method="post">
<input name="uri" type="text"></input>
<input type="submit" value="Indexieren"></input>
</form>
(Verzeichnis- und Dateinamen bitte ohne das Protokollprefix file://)


<?php
	
	 }
	 // uri given, start crawling
else {
	

// todo:
// check if job running, force parameter

  
  print "Starte Indexvorgang für ".$uri." ...";

// Generate job id as md5 hash from uri
$jobid = md5($uri);

// Set filename to jobid in spooldir
$filename = $spool.'/queue/'.$jobid;

$file = fopen($filename, 'w') or die('Cannot open file: '.$filename);
fwrite($file, $uri);

fclose($file);

// todo: status.php, dort reload alle zig sekunden bis status done

// todo: id.queued mit timestamp

}

?>

</body>
</html>
