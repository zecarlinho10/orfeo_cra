<html>
<head>
<title><?php echo t('Search');
if ($query) print ' '.htmlspecialchars($query);?></title>

<script src="js/vendor/modernizr.js"></script>
<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="css/foundation.css">

<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script src="js/foundation/foundation.topbar.js"></script>

<script type="text/javascript" src="jquery/jquery.autocomplete.js"></script>
<script type="text/javascript" src="autocomplete.js"></script>
<script type="text/javascript">

function funlinkArchivo(numrad,rutaRaiz){
nombreventana="linkVistArch";
url=rutaRaiz + "/linkArchivo.php?numrad="+numrad;
ventana = window.open(url,nombreventana,'scrollbars=1,height=50,width=250');
setTimeout(nombreventana.close, 70);
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

<link rel="STYLESHEET" href="css/style.css" type="text/css" />
</head>
<body>
<?php 

if ( file_exists("templates/custom/view.index.topbar.php") ) {
	include "templates/custom/view.index.topbar.php";
} else {
	include "templates/view.index.topbar.php";
}
?>

<div class="row">  

	<div id="searchform-wrapper" class="small-12 medium-8 large-9 columns">
		<?php
		/*
		 * SearchForm
		*/
		?>

	
		<form id="searchform" accept-charset="utf-8" method="get">

		<div class="small-12 medium-2 large-2 columns">
			<img id="logo" src="images/search.png" alt="<?php echo t('Search'); ?>">
		</div>
	
		<div id="search-field" class="small-12 medium-8 large-8 columns">
			<input id="q" name="q" type="text" value="<?php echo htmlspecialchars($query, ENT_QUOTES, 'utf-8'); ?>" />
		</div>
		
		<div id="search-button" class="small-12 medium-2 large-2 columns">
					
			<input id="submit" type="submit" class="button postfix" value="<?php echo t("Search"); ?>" onclick="waiting_on()" />
		</div>
	
		</form>		

			
	</div>


</div>


<div class="row">

<div id="main" class="small-12 medium-8 large-8 columns">



	<?php
	
	include 'templates/select_view.php';

	?>
	
	
	<?php 
	
	// if no results, show message
	if ($total == 0) {
		?>
	<div id="noresults" class="panel">


		<?php
		if ($error) {
			print '<p>Error: </p><p>' . $error . '</p>';
		} else {
			// Todo: Vorschlag: (in allen Bereichen, Ähnliches)
			print t('No results');
		}
		?>
	</div>

	<?php
	} // total == 0
	else { // there are results documents
		
		if ($error) {
			print '<p>Error:</p><p>' . $error . '</p>';
		}
		
		// print the results with selected view template
		if ($view == 'list') {
			
			include 'templates/select_sort.php';
			
			include 'templates/pagination.php';						
			include 'templates/view.list.php';
			include 'templates/pagination.php';

		} elseif ($view == 'preview') {

			include 'templates/pagination.php';
			include 'templates/view.preview.php';
			include 'templates/pagination.php';

		} elseif ($view == 'images') {
			include 'templates/select_sort.php';
				
			include 'templates/pagination.php';
			include 'templates/view.images.php';
			include 'templates/pagination.php';

		} elseif ($view == 'videos') {
			include 'templates/select_sort.php';
				
			include 'templates/pagination.php';
			include 'templates/view.videos.php';
			include 'templates/pagination.php';

		} elseif ($view == 'table') {

			include 'templates/pagination.php';
			include 'templates/view.table.php';
			include 'templates/pagination.php';

		} elseif ($view=='words') {

			include 'templates/view.words.php';

		} elseif ($view=='trend') {

			include 'templates/view.trend.php';

		} elseif ($view == 'timeline') {

			include 'templates/pagination.php';
			include 'timeline.php';
			include 'templates/pagination.php';

		}
		else {

			include 'templates/pages.php';
			include 'templates/view.list.php';
			include 'templates/pages.php';

		}


	} // if total <> 0: there were documents
	?>

	</div><?php // main ?>

	
	<div id="sidebar" class="small-12 medium-4 large-4 columns">
	
	<?php
		if ($total > 0) {
				// if preview, schow metadata
				if ($view=="preview") {
					include "templates/view.preview.sidebar.php";
				}	
				else {
				// show facets
					include "templates/view.facets.php";
				}
		}
	?>	
	
	</div>
	
	
</div>
	
	<?php // Wait indicator - will be activated on click = next search (which can take a while and additional clicks would make it worse) ?>
	<div id="wait">
		<img src="images/ajax-loader.gif">
		<p><?php echo t('wait'); ?></p>
	</div>

	<script type="text/javascript">

	/*
	* init foundation responsive framework
	*/
    $(document).foundation();

    </script>
    

	<script type="text/javascript">
	
  /*
   * display wait-indicator
   */
  function waiting_on() {
    document.getElementById('wait').style.visibility = "visible";
    return true;
  }
</script>

</body>
</html>
