<?php
// Standard view
//
// Show results as list

?>

<div id="results" class="row">

<ul class="small-block-grid-1 medium-block-grid-2 large-block-grid-2">

<?php
// todo: bei table alle fields ausser content?! und erst felder sammeln, dann html tabelle und for each field if exist
foreach ($results->response->docs as $doc) {

  // URI
  if (isset($doc->container_s)) {
  	$id = $doc->container_s;
  }
  else {
  	$id = $doc->id;
  }
  
  
  $uri_label = $id;
  $uri_tip = false;
  
  // if file:// then only filename
  if (strpos ($id, "file://")==0) {
  	$uri_label = basename($id);
  	// for tooptip remove file:// from beginning
  	$uri_tip = substr( $id, 7 );
  }
  
  // Author
  $author = htmlspecialchars($doc->author_s);

  // Title
  $title = false;
  
  if (isset($doc->title)) {
    if (!empty($doc->title)) {
    	$title= htmlspecialchars($doc->title);
    }
  }

  // Type
  $type= $doc->content_type; // todo: contentype schoener mit wertearray

  // Modified date
  if (isset($doc->file_modified_dt)) {
	$datetime = $doc->file_modified_dt; 
  } elseif (isset($doc->last_modified)) {
  	$datetime = $doc->last_modified;
  } else {
  	$datetime=false;
  }


  // Snippet
  if (isset($results->highlighting->$id->content)) {
    $snippet = htmlspecialchars($results->highlighting->$id->content[0]);
  } else { 
	$snippet = $doc->content;
	if (strlen($snippet) > $snippetsize) {
		$snippet = substr($snippet,0,$snippetsize)."...";
	} 
  }
  $snippet = htmlspecialchars($snippet);

  // but <em> from solr (highlighting) shoud not be converted, so convert back
  $snippet=str_replace('&amp;lt;em&amp;gt;', '<em>', $snippet);
  $snippet=str_replace('&amp;lt;/em&amp;gt;', '</em>', $snippet);

?>
<li>

	<div class="title">
		<a class="title" target="_blank" rel="noopener noreferrer" href="<?=$id?>">
				<?php if ($title) { ?>
			<?=$title ?>
				<?php } ?>
				
						<?php if ($uri_tip) { ?>
					<span data-tooltip class="has-tip" title="<?=$uri_tip?>">
				<?php } ?>
			<?=$uri_label?>
				<?php if ($uri_tip) { ?>
					</span>
				<?php } ?>
		</a>
	</div>
	

	

	<div class="video">
			
<video controls src="<?=$id?>"></video>

	</div>
	
	
	<div class="row">
		<div class="date small-8 columns"><?=$datetime?></div>
		<div class="size small-4 columns"><?=$file_size_txt?></div>	
	</div>
	
	<div class="snippet">
		<?php if ($author) { print '<div class="author">'.$author.'</div>:'; } ?>
<?=$snippet?>
	</div>
	<div class="commands">
			<a target="_blank" rel="noopener noreferrer" href="<?=$id?>"><?php echo t('open'); ?></a> <?php if ($cfg['metadata']['server']) { ?> | <a target="_blank" rel="noopener noreferrer" title="<?php echo t('meta description'); ?>" href="<?php print get_metadata_uri ($cfg['metadata']['server'], $id); ?>"><?php echo t('meta'); ?></a> <?php } ?> | <?php print '<a target="_blank" rel="noopener noreferrer" href="preview.php?id='.urlencode($id).'">' . t('Preview') . '</a>'; ?>
	</div>
</li>

<?php
  } // foreach doc
?>

    </ul>
</div>
