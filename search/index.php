<?php
// SOLR PHP Client UI
//
// PHP-UI of Open Semantic Search - http://www.OpenSemanticSearch.org
//
// 2011 - 2014 by Markus Mandalka - http://www.mandalka.name
//
// Free Software - License: GPL

# seguridad Orfeo 
#
session_start();
if(!$ruta_raiz) $ruta_raiz="..";  

if (empty($_SESSION['dependencia'])){
	header ("Location: $ruta_raiz/cerrar_session.php");
}
# do not change config here, use config.php!

#config defaults

$cfg['solr']['host'] = '192.168.100.51';
$cfg['solr']['port'] = 8983;
$cfg['solr']['path'] = '/solr/';


// show newest documents, if no query
$cfg['newest_on_empty_query'] = true;

// no link to admin interface
$cfg['solr']['admin']['uri'] = false;

// only metadata option if server set in config
$cfg['metadata']['server'] = false;

// size of the snippet
$cfg['snippetsize'] = 300;

// todo: convert labels to t() function
// and add to facet config: $lang['en']['facetname'] = 'Facet label';
$cfg['facets']=array(
	'dependencia' =>array('label'=>'Dependencia'),
	'tipo_rad' =>array('label'=>'Tipo Radicado'), 
	'tipo_doc' =>array('label'=>'Tipo Documento'), 
	'features' =>array('label'=>'caracteristicas'),
	'author_s' => array ('label'=>'Autor'),
	'tag_ss' => array ('label'=>'Tags'),
	'text' => array ('label'=>'Text'),
);

// make sure browsers see this page as utf-8 encoded HTML
header('Content-Type: text/html; charset=utf-8');


// include configs
include 'config/config.php';
include 'config/config.mimetypes.php';
include 'config/config.i18n.php';
require_once (realpath(dirname(__FILE__) . "/../") . "/include/db/Connection/Connection.php");
require_once (realpath(dirname(__FILE__) . "/../") . "/tx/verLinkArchivo.php");


function get_uri_help ($language) {

	$result = "doc/help." . $language . ".html";

	// if help.$language.html doesn't exist
	if (!file_exists ($result) ) {
		// use default (english)
		$result = "doc/help.html";
	}
	
	return $result;
}


// create link with actual parameters with one changed parameter
// changing second facet needed if the change of the main parameter will change results to reset page number which could be more than first page

function buildurl($params, $facet=NULL, $newvalue=NULL, $facet2=NULL, $newvalue2=NULL, $facet3=NULL, $newvalue3=NULL, $facet4=NULL, $newvalue4=NULL) {

	if ($facet) {
		$params[$facet]=$newvalue;
	}

	if ($facet2) {
		$params[$facet2]=$newvalue2;
	}

	if ($facet3) {
		$params[$facet3]=$newvalue3;
	}
	
	if ($facet4) {
		$params[$facet4]=$newvalue4;
	}
	
	// if param false, delete it
	foreach ($params as $key=>$value) {

		if ($value==false) {
			unset($params[$key]);
		}

	}

	$uri = "?".http_build_query($params);

	return $uri;

}

function buildurl_addvalue($params, $facet=NULL, $addvalue=NULL, $changefacet=NULL, $newvalue=NULL) {

	if ($facet) {
		$params[$facet][] = $addvalue;
	}


	if ($changefacet) {
		$params[$changefacet] = $newvalue;
	}

	// if param false, delete it
	foreach ($params as $key=>$value) {

		if ($value==false) {
			unset($params[$key]);
		}

	}

	$uri = "?".http_build_query($params);

	return $uri;

}

function buildurl_delvalue($params, $facet=NULL, $delvalue=NULL, $changefacet=NULL, $newvalue=NULL) {

	if ($facet) {

		unset( $params[$facet][array_search($delvalue, $params[$facet])] );

	}


	if ($changefacet) {
		$params[$changefacet] = $newvalue;
	}

	// if param false, delete it
	foreach ($params as $key=>$value) {

		if ($value==false) {
			unset($params[$key]);
		}

	}

	$uri = "?".http_build_query($params);

	return $uri;

}



// Get url of metadata page to the given id (filename or uri of the original content)
function get_metadata_uri ($metadata_server, $id) {

	$url = $metadata_server.md5($id).'?Meta[RefURI]='.urlencode($id); // use md5 hash, because not every cms supports special chars as page id

	return $url;

}

function date2solrstr($timestamp) {
	$date_str = date('Y-m-d', $timestamp).'T'. date('H:i:s', $timestamp).'Z';

	return $date_str;
}



// values for navigating date facet
function get_datevalues(&$results, $params, $downzoom) {


$datevalues = array();

	foreach ($results->facet_counts->facet_ranges->fecha_rad->counts as $facet=>$count) {
		$newstart = $facet;

		if ($downzoom=='decade') {
			$newend = $facet . '+10YEARS';

			$value = substr($facet, 0, 4);

		} elseif ($downzoom=='year') {
			$newend = $facet . '+1YEAR';
			$value = substr($facet, 0, 4);

		} elseif ($downzoom=='month') {
			$newend = $facet . '+1MONTH';
			$value = substr($facet, 5, 2);

		} elseif ($downzoom=='day') {
			$newend = $facet . '+1DAY';
			$value = substr($facet, 8, 2);

		} elseif ($downzoom=='hour') {
			$newend = $facet . '+1HOUR';
			$value = substr($facet, 11, 2);

		} elseif ($downzoom=='minute') {
			$newend = $facet . '+1MINUTE';
			$value = substr($facet, 14, 2);

		} else {
			$newend = $facet . '+1YEAR';
			$value = $facet;
		};


		$link = buildurl($params,'start_dt', $newstart, 'end_dt', $newend, 'zoom', $downzoom, 's', false);

		$datevalues[] = array('label'=> $value, 'count' => $count, 'link' => $link);
	}

	return $datevalues;
}


// build solr queryfilter from groupconfig
function mimegroup2query($mimegroup) {
	global $mimetypes;

	$query='content_type:(';

	$first = true;
	foreach($mimetypes as $mimetype=>$value) {

		if ($value['group'] == $mimegroup) {
			if ($first) {
				$first = false;
			} else { $query .= ' OR ';
			}
			$maskedtype = addcslashes($mimetype, '+-&|!(){}[]^"~*?:\/ ');
			//$query.='content_type:' . $maskedtype . '*';
			$query .= $maskedtype . '*';
		}
	}

	$query .=')';

	return $query;

}

// convert large sizes (in bytes) to better readable unit
function filesize_formatted($size)
{
	$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	$power = $size > 0 ? floor(log($size, 1024)) : 0;
	return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}


				
				
// print a facet and its values as links
function print_facet(&$results, $facet_field, $facet_label) {
					global $params;
				
					if (isset($results->facet_counts->facet_fields->$facet_field)) {
						if (count(get_object_vars($results->facet_counts->facet_fields->$facet_field)) > 0) {
				
						?>
							<div id="<?= $facet_field ?>" class="facet">
								<h2>
									<?= $facet_label ?>
								</h2>
								<ul class="no-bullet">
								<?php
				
								foreach ($results->facet_counts->facet_fields->$facet_field as $facet => $count) {
									print '<li><a onclick="waiting_on();" href="' . buildurl_addvalue($params, $facet_field, $facet, 's', 1) . '">' . htmlspecialchars($facet) . '</a> (' . $count . ')</li>';
								}
								?>
								</ul>
							</div>
							<?php
						}
					} // if isset facetfield
				
}
								
				
function strip_empty_lines($s, $max_empty_lines) {

	$first = true;
	
	$emptylines = 0;
	
	$fp = fopen("php://memory", 'r+');
	fputs($fp, $s);
	rewind($fp);
	while( $line = fgets($fp) ) {
		// if only white spaces
		if ( preg_match("/^[\s]*$/", $line) )
		{
			
			$emptylines++;

			// if not max, write empty line to result
			if ($emptylines < $max_empty_lines) {
				
				// but not if first = beginning of the document
				if ($first == false) {
					$result .= "\n";
				}
			}				
		
		} else { // char is not newline, so reset newline counter and write char to result
			$first = false;
			$emptylines = 0;
			
			$result .= $line;
		}
	
	}
	fclose($fp);
	
	
	
	return $result;
	
}


//
// get parameters
//

$query = isset($_REQUEST['q']) ?  trim($_REQUEST['q']) : false;
$start = (int) isset($_REQUEST['s']) ? $_REQUEST['s'] : 1;
if ($start < 1) $start = 1;



$sort= isset($_REQUEST['sort']) ? $_REQUEST['sort'] : false;
$path= isset($_REQUEST['path']) ? $_REQUEST['path'] : false;


$types = isset($_REQUEST['type']) ? $_REQUEST['type'] : false;
$typegroups = isset($_REQUEST['typegroup']) ? $_REQUEST['typegroup'] : false;

// get parameters for each configurated facet
$selected_facets = array();
foreach ($cfg['facets'] as $facet=>$facet_value) {

	if ( isset($_REQUEST[$facet]) ) {
		$selected_facets[$facet] = $_REQUEST[$facet];
	}
}

$view = isset($_REQUEST['view']) ? $_REQUEST['view'] : 'list';

// startdate and enddate
$start_dt = isset($_REQUEST['start_dt']) ? (string)$_REQUEST['start_dt'] : false;
$end_dt = isset($_REQUEST['end_dt']) ? (string)$_REQUEST['end_dt'] : false;


$zoom = isset($_REQUEST['zoom']) ? (string)$_REQUEST['zoom'] : 'years';


// now we know the view parameter, so lets set limits that fit for the special view
if ($view=='list') {
	$limit = 10;
}
elseif ($view=='table') {
	$limit = 20;
}
elseif ($view=='images') {
	$limit = 6;
}
elseif ($view=='videos') {
	$limit = 6;
}
elseif ($view=='preview') {
	$limit = 1;
}
elseif ($view=='timeline') {
	$limit = 100;
}
elseif ($view=='words') {
	$limit = 0;
}
elseif ($view=='trend') {
	$limit = 0;
} else $limit=10;




// set all params for urlbuilder which have to be active in the further session
$params = array(
		'q' => $query,
		'path' => $path,
		'sort' => $sort,
		's' => $start,
		'view' => $view,
		'type' => $types,
		'typegroup' => $typegroups,
		'zoom' => $zoom,
		'start_dt' => $start_dt,
		'end_dt' => $end_dt,
);

foreach ($selected_facets as $selected_facet=>$facet_value) {

	$params[$selected_facet] = $facet_value;
}



	require_once('./Apache/Solr/Service.php');


	$solr = new Apache_Solr_Service($cfg['solr']['host'], $cfg['solr']['port'], $cfg['solr']['path']);

	// if magic quotes is enabled then stripslashes will be needed
	//if (get_magic_quotes_gpc() == 1) {
		$query = stripslashes($query);
	//}

	// If no query, so show last documents
	if (!$query) {
		if ($cfg['newest_on_empty_query']) {
			$solrquery = '*:*';
			if (!$sort) {
				$sort = 'newest';
			}
		}

	} else {
		// Build query for solr

		// mask query for solr
		$solrquery = addcslashes($query, '&|!{}[]^:\/');
	}

	/*
	 * Fields to select
	*
	* Especially the field "content" maybe too big for php's RAM or causing bad
	* for performance, so select only needet fields we want to print except if
	* table view (where we want to see all fields)
	*/
	if ($view != 'table' && $view != 'preview') {
		$additionalParameters['fl']='id,radicado,dependencia,content_type,asunto,container_s,author_s,fecha_rad';
	}

	//
	// highlighting 
	//
	$additionalParameters['hl'] = 'true';
	$additionalParameters['hl.fl'] = 'content';
	$additionalParameters['hl.fragsize'] = $cfg['snippetsize'];

	if ($view =="preview") {
		$additionalParameters['hl.fragsize'] = '0';
		$additionalParameters['hl.maxAnalyzedChars'] = '1000000000';
		
	}
	else {
		// if there is no snippet show part of content field
		// (i.e. if search matches against filename or all results within path or date)
		$additionalParameters['hl.alternateField'] = 'content';
		$additionalParameters['hl.maxAlternateFieldLength'] = $cfg['snippetsize'];
		 
	}
	
	//
	// humanize query tolerance
	//
	
	// more forgiving solr query-parser than standard (which would trow erros if user forgot to close ( with ) )
	$additionalParameters['defType'] = 'edismax';

	// solrs default is OR (document contains at least one of the words), we want AND (documents contain all words)
	$additionalParameters['q.op'] = 'AND';

	
	//
	//Facets
	//
	
	// build filters from all slected facets and facetvalues
	// and extend the solr query with this filters

	foreach ($cfg['facets'] as $configured_facet => $facet_config) {
		if (isset($selected_facets[$configured_facet])) {

			$selected_facet = $configured_facet;
			foreach ($selected_facets[$selected_facet] as $selected_value) {

				$solrfacet = addcslashes($selected_facet, '+-&|!(){}[]^"~*?:\/ ');
				$solrfacetvalue = addcslashes($selected_value, '+-&|!(){}[]^"~*?:\/ ');
				$solrquery .= ' AND ' . $solrfacet . ':' . $solrfacetvalue;
			}
		}
	}

	// Extend solr query with content_type filter, if set
	if ($types) {
		$solrtype = addcslashes($types[0], '+-&|!(){}[]^"~*?:\/ ');
		$solrquery .= ' AND content_type:' . $solrtype. '*';
	}

	// Extend solr query with content_type group filter, if set
	if ($typegroups) {
		$solrtypegroup = mimegroup2query($typegroups[0]);
		$solrquery .= ' AND ' . $solrtypegroup;
	}

	//$pathfacet = 'path0_s';

	if ($path) {
		$trimmedpath = trim($path, '/');
		$paths = split('/', $trimmedpath);

		// if path check which path_x_s facet to select
		$pathdeepth = count($paths);

		$pathfacet = 'path' . $pathdeepth . '_s';

		// pathfilter to set in solrquery
		$paths = split('/', $trimmedpath);

		$pathfilter = '';
		$pathcounter = 0;

		foreach ($paths as $subpath) {
			$solrpath = addcslashes($subpath, '+-&|!(){}[]^"~*?:\/ ');

			$pathfilter .= ' AND path' . $pathcounter . '_s:' . $solrpath;
			$pathcounter++;
		}

		$solrquery .= $pathfilter;
	}

	// if view is imagegallery extend solrquery to filter images
	// filter on content_type image* so that we dont show textdocuments in image gallery
	if ($view == 'images') {
		$solrquery .= ' AND content_type:image*';
	}

	
	// if view is imagegallery extend solrquery to filter images
	// filter on content_type image* so that we dont show textdocuments in image gallery
	if ($view == 'videos') {
		$solrquery .= ' AND (';
		$solrquery .= 'content_type:video*';

		$solrquery .= ' OR content_type:application\/mp4';
		$solrquery .= ' OR content_type:application\/x-matroska';
		
		$solrquery .= ')';
	}
	
		
	//
	// date filter
	//
	if ($start_dt || $end_dt) {

		// todo: filter []'" to prevent injections
		if ($start_dt) {
			// dont mask : and - which are used to delimiter date and time values
			$start_dt_solr .= addcslashes($start_dt, '&|!(){}[]^"~*?\/');
		} else $start_dt_solr = '*';
	
		if ($end_dt) {
			// dont mask : and - which are used to delimiter date and time values
			$end_dt_solr .= addcslashes($end_dt, '&|!(){}[]^"~*?\/');
		} else $end_dt_solr = '*';

		$solrquery .= ' AND fecha_rad:[ ' . $start_dt_solr . ' TO ' .$end_dt_solr. ']';		
	}
	
	
	//
	// Sort
	//
	
	// (solr standard: score)
	if ($sort == 'newest') {
		$additionalParameters['sort'] = "fecha_rad desc";
	} elseif ($sort == 'oldest') {
		$additionalParameters['sort'] = "fecha_rad asc";
	} elseif ($sort) {
		$additionalParameters['sort'] = addcslashes($sort, '+-&|!(){}[]^"~*?:\/');
	}

	// todo: Get similar queries for "Did you mean?"

	// facets
	$additionalParameters['facet'] = 'true';
	$additionalParameters['facet.mincount'] = 1;

	// base facets fields
	/*$arr_facets = array(
			'content_type',
			'file_modified_dt',
			$pathfacet);*/
	// additional facet fields from config
	foreach ($cfg['facets'] as $facet => $facet_value) {
		if ($facet != 'text') { 
			$arr_facets[] = $facet;
		}
	}


	if ($view=='words') {
		// to let solr count the words for a word cloud we want to have the aggregated field text as facet
		$arr_facets[] = 'text';
	}
	
	
	$additionalParameters['facet.field'] = $arr_facets;


	
	$additionalParameters['f.fecha_rad.facet.mincount'] = 0;
	$additionalParameters['facet.range']= 'fecha_rad';

	// date facet as ranges
	if ($zoom=='years') {
		$gap='+1YEAR';
		$downzoom = 'year';
		$upzoom = false;
		$upzoom_start_dt = false;

	}elseif ($zoom=='year') {
			$gap='+1MONTH';
			$date_label = substr($start_dt, 0, 4);
			$downzoom = 'month';
			$upzoom_label = 'en el año';
			$upzoom = 'years';
			$upzoom_start_dt = false;

	}elseif ($zoom=='month') {
			$gap='+1DAY';
			$date_label = substr($start_dt, 0, 7);
			$downzoom = 'day';
			$upzoom = 'year';
			$upzoom_label = substr($start_dt, 0, 4);
			$upzoom_start_dt = substr($start_dt, 0, 4) . '-01-01T00:00:00Z';
			$upzoom_end_dt = substr($start_dt, 0, 4) . '-01-01T00:00:00Z+1YEAR';
				
	}elseif ($zoom=='day') {
			$gap='+1HOUR';
			$date_label = substr($start_dt, 0, 10);
			$upzoom = 'month';
			$downzoom = 'hour';
			$upzoom_label = substr($start_dt, 0, 7);
			$upzoom_start_dt = substr($start_dt, 0, 7) . '-01T00:00:00Z';
			$upzoom_end_dt = substr($start_dt, 0, 7) . '-01T00:00:00Z+1MONTH';
				
	}elseif ($zoom=='hour') {
			$gap='+1MINUTE';
			$date_label = substr($start_dt, 0, 13);
			$downzoom = 'minute';
			$upzoom = 'day';
			$upzoom_label = substr($start_dt, 0, 10);
			$upzoom_start_dt = substr($start_dt, 0, 10) . 'T00:00:00Z';
			$upzoom_end_dt = substr($start_dt, 0, 10) . 'T00:00:00Z+1DAY';
				
	} else {
			$gap='+1YEAR';
			$upzoom = 'years';
			$downzoom = 'year';
	}


	$additionalParameters['facet.range.gap'] = $gap;


	// start and end dates
	if ($start_dt)	{
			$additionalParameters['facet.range.start'] = (string)$start_dt;
	} else {

			if ($zoom='trend') {
				$additionalParameters['facet.range.start'] = '1980-01-01T00:00:00Z/YEAR';
			}else {
				// todo: more then last 10 years if wanted
				
				$additionalParameters['facet.range.start'] = 'NOW-3YEARS/YEAR';
			}
	}

	if ($end_dt)	{
			$additionalParameters['facet.range.end'] = (string)$end_dt;
	} else {
			$additionalParameters['facet.range.end'] = 'NOW+1YEARS/YEAR';
	}

		
	if ($upzoom) {
			$upzoom_link = buildurl($params, 'start_dt', $upzoom_start_dt, 'end_dt', $upzoom_end_dt, 'zoom', $upzoom);
	}

		
	if ($cfg['debug']) {
		print htmlspecialchars($solrquery) . '<br>';
		print_r($additionalParameters);
	}

	// There is a query, so ask solr
	if ($solrquery) {

		$results = false;
		try {
			$results = $solr->search($solrquery, $start - 1, $limit, $additionalParameters);
			$error = false;
		} catch (Exception $e) {

			// todo: code temporary not available?
			$error = $e->__toString();
		}
	} // isquery -> Ask solr
	if ($cfg['debug']) {
	
		print "Solr results:";
		print_r($results);
	}

	
	//
	// Pagination
	//
	
	// display results
	$total = (int)$results->response->numFound;
	if($limit == 0)
		$limit =1;

	// calculate stats
	$start = min($start, $total);
	$end = min($start + $limit - 1, $total);

	$page = ceil($start / $limit);
	$pages = ceil($total / $limit);

	// if isnextpage build link
	if ($total > $start + $limit - 1) {
		$is_next_page = true;
		$link_next = buildurl($params, 's', $start + $limit);
	} else {
		$is_next_page = false;
	}

	// if isprevpage build link
	if ($start > 1) {
		$is_prev_page = true;
		$link_prev = buildurl($params, 's', $start - $limit);

	} else {
		$is_prev_page = false;
	}
	
	//
	// General links
	//
	// link to help
	$uri_help = get_uri_help ( $cfg['language'] );
	
	
	
	$datevalues = get_datevalues($results, $params, $downzoom);

	if ($embedded) {
		include "templates/view.embedded.php";
	} else {
		include "templates/view.index.php";
	}
	
	function verificaPermisos($radicado,$usuarioDoc, $usuarioCodi, $depeCodi, $usuaNivel, $permisosExpediente){
		$db = Connection::getCurrentInstance();
        $verLinkArchivo = new verLinkArchivo ( $db );
		$verImagen=$verLinkArchivo->havePermisosRadicado($radicado, $usuarioDoc, $usuarioCodi, $depeCodi, $usuaNivel, $permisosExpediente);
		return  $verImagen;
  	}

?>
