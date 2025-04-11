<?php
// make sure browsers see this page as utf-8 encoded HTML
header('Content-Type: text/html; charset=utf-8');


$id = isset($_REQUEST['id']) ?  $_REQUEST['id'] : false;



?>
<html>
  <head>
    <title>Preview <?php if ($id) print htmlspecialchars($id);?></title>
	  <link rel="STYLESHEET" href="css/style.css" type="text/css" />
  </head>
  <body>
  
<?php


  require_once('./Apache/Solr/Service.php');

  $solr = new Apache_Solr_Service('localhost', 8181, '/solr/orfeo');

// query doch und als hl.query rein!

// content scheint inkomplett, evtl. direkt solr abfragen?

// problem mit * hier? ausserdem evtl. brauch ich slashes?
  // if magic quotes is enabled then stripslashes will be needed
  if (get_magic_quotes_gpc() == 1)
  {
    $id = stripslashes($id);
  }


	$solrid=addcslashes($id,'+-&|!(){}[]^"~*?:\/ ');

	$solrquery='id:'.$solrid;
	
	$additionalParameters['defType']='edismax';

	
  try
  {

    $results = $solr->search($solrquery, 0, 1, $additionalParameters);
  }
  catch (Exception $e)
  {
    // in production you'd probably log or email this error to an admin
        // and then show a special message to the user but for this example
        // we're going to show the full exception
        die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
  }






foreach ($results->response->docs as $doc) {

  // URI
  $id = $doc->id;
  
  // Date - todo als metadatenmodul für file:// !
  $date = $doc->Last-modified;
  
  // Author
  $author = htmlspecialchars($doc->author_s);
  $name =  htmlspecialchars($doc->name);
  $manu =  htmlspecialchars($doc->manu_id_s);
  if(isset($doc->cat) && count($doc->cat) >0 ){
  $cat =" ";
   foreach($doc->cat as $value){
	$cat .= $value;		
	}
 }
	
  
  // Title
  if (isset($doc->asunto)) {
    $title= htmlspecialchars($doc->asunto);
  } else { $title="Radicado"; }


  // Type
  $type= $doc->content_type; // todo: contentype schoener mit wertearray

//Content
$content = htmlspecialchars($doc->content);
$content = nl2br($content); // new lines
// todo: jedes vorkommen von mehr als 2 Leerzeilen zu hoechstens 2 Leerzeilen umwandeln

	// Snippet
  if (isset($results->highlighting->$id->content)) {

    $snippet = htmlspecialchars($results->highlighting->$id->content[0]);
  } else { 
	$snippet = $doc->content;
	if (strlen($snippet) > $snippetsize) { $snippet = substr($snippet,0,$snippetsize)."..."; } 
  }
  $snippet = htmlspecialchars($snippet);

  // but <em> from solr shoud not be converted
  
  $snippet=str_replace('&amp;lt;em&amp;gt;', '<em>', $snippet);
  $snippet=str_replace('&amp;lt;/em&amp;gt;', '</em>', $snippet);

?>
	<a class="title" href="<?=$id?>"><?=$title ?></a>
	<div class="uri"><?=$id?></div>


	
		<?php if ($author) { print '<div class="author">'.$author.'</div>:'; } ?>
<div>
	<?php echo  $name ."<br />".$manu ?>
</div>	
	<div class="content">
<?=$content?>
	</div>

<?php
  } // foreach doc
?>


</body>
</html>
