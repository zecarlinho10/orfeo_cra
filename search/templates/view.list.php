<?php
// Standard view
//
// Show results as list
?>

<div id="results" class="row">
	<ul class="no-bullet">

<?php
// todo: bei table alle fields ausser content?! und erst felder sammeln, dann html tabelle und for each field if exist
//
foreach ($results->response->docs as $doc) {
    
    $id = $doc->id;
    
    // Type
    $type = $doc->content_type; // todo: contentype schoener mit wertearray
                                
    // URI
                                
    // if part of container like zip, link to container file
                                // if PDF page URI to Deeplink
                                // since PDF Reader can open deep links
    if (isset($doc->container_s) and $type != 'PDF page') {
        $uri = $doc->container_s;
        $deepid = $id;
    } else {
        $uri = $id;
        $deepid = false;
    }
    
    $uri_label = $uri;
    $uri_tip = false;
    
    // if file:// then only filename
    if (strpos($uri, "file://") == 0) {
        $uri_label = basename($uri);
        // for tooptip remove file:// from beginning
        $uri_tip = substr($uri, 7);
    }
    
    if ($deepid) {
        $deep_uri_label = $deepid;
        $deep_uri_tip = false;
        // if file:// then only filename
        if (strpos($deepid, "file://") == 0) {
            $deep_uri_label = basename($deepid);
            // for tooptip remove file:// from beginning
            $deep_uri_tip = substr($deepid, 7);
        }
    }
    
    // Author
    $author = htmlspecialchars($doc->author_s);
    
    // Title
    // $title="Ohne Titel";
    $title = "Radicado";
    if (isset($doc->asunto)) {
        if (! empty($doc->asunto)) {
            $title = htmlspecialchars($doc->asunto);
        }
    }
    
    // Modified date
    if (isset($doc->fecha_rad)) {
        
        // $datetime = $doc->fecha_rad;
        $timestamp = strtotime($doc->fecha_rad);
        date_default_timezone_set("America/Bogota");
        $datetime = date("d/m/Y H:i:s", $timestamp);
        // }elseif (isset($doc->last_modified)) {
        // $datetime = $doc->last_modified;
    } else {
        $datetime = false;
    }
    
    $file_size = 0;
    $file_size_txt = '';
    // File size
    if (isset($doc->file_size_i)) {
        $file_size = $doc->file_size_i;
        $file_size_txt = filesize_formatted($file_size);
    }
    
    // Snippet
    // print_r($results->highlighting->$id);
    
    $snippet = '...';
    if (isset($results->highlighting->$id->content)) {
        $snippet = htmlspecialchars($results->highlighting->$id->content[0]);
    } elseif (isset($results->highlighting->$id)) {
        $snippet = $results->highlighting->$id;
    } elseif (isset($doc->content)) {
        $snippet = $doc->content;
        if (strlen($snippet) > $cfg['snippetsize']) {
            $snippet = substr($snippet, 0, $cfg['snippetsize']) . "...";
        }
    }
    if(!is_object($snippet)){	
    		$snippet = htmlspecialchars($snippet);
    }else{
 		$snippet =".."	;
    }
    
    // but <em> from solr (highlighting) shoud not be converted, so convert back
    $snippet = str_replace('&amp;lt;em&amp;gt;', '<mark>', $snippet);
    $snippet = str_replace('&amp;lt;/em&amp;gt;', '</mark>', $snippet);
    $permisos = verificaPermisos($doc->id, $_SESSION["usua_doc"], $_SESSION["codusuario"], $_SESSION["dependencia"], $_SESSION["nivelus"], null);
    if ($permisos["ver"]) {
        $linkDocto = "<a class=\"vinculos\" href=\"#2\" onclick=\"funlinkArchivo('" . $doc->id . "','../')     ;\" target\"Imagen$iii\">" . $doc->id . "</a> ";
        $uri = "href='../verradicado.php?verrad=" . $doc->id . "&carpeta=9&nomcarpeta=Busquedas&tipo_carp=0'";
    } else {
         $linkDocto = "<a class='vinculos' href='javascript:noPermiso()' > " . $doc->id . "</a>";
         $uri ="href='#' onclick='javascript:noPermiso()' ";
    }
    ?>
<li>

			<div class="title">
				<a class="title" <?=$uri?>><?=$title?></a>
			</div>

			<div class="date"><?=$datetime?></div>
			<div class="uri">
				<span class="uri"> <?php echo $linkDocto?></span>
			</div>

			<div class="snippet">
		<?php if ($author) { print '<div class="author">'.$author.'</div>'; } ?>
	<?php 
	if($permisos["ver"]==false &&!empty($permisos["confidencial"])) {
	    echo "Documento Confidencial";
	}else{
        echo $snippet;
    }
    ?>
	</div>
			<div class="commands">
				<a target="_blank" rel="noopener noreferrer" href="<?=$uri?>"><?php echo t('open'); ?></a> <?php if ($cfg['metadata']['server']) { ?> | <a
					target="_blank" rel="noopener noreferrer" title="<?php echo t('meta description'); ?>"
					href="<?php print get_metadata_uri ($cfg['metadata']['server'], $uri); ?>"><?php echo t('meta'); ?></a> <?php } ?> | <?php //print '<a target="_blank" rel="noopener noreferrer" href="preview.php?id='.urlencode($uri).'">' . t('Preview') . '</a>'; ?>
	</div>
		</li>

<?php
} // foreach doc
?>

    </ul>

</div>

